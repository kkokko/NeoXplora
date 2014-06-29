unit Scheduler;

interface

uses
  Windows, FileReadThread, DatabaseWriterThread, ParseResult;

type
  TScheduler = class
  private
    const
      ConstWhenStopFileReader = 2000;
      ConstWhenRestartFileReader = 1000;
    class var
      FInstance: TScheduler;
  public
    type
      TStatusInfo = record
        BytesParsed: Int64;
        BytesTotal: Int64;
        PagesRead: Int64;
        PagesParsed: Int64;
        PagesInserted: Int64;
        PagesInsertQueue: Int64;
      end;
      PParseRecord = ^TParseRecord;
      TParseRecord = record
        Name: AnsiString;
        Text: AnsiString;
        Next: PParseRecord;
      end;
      PInsertRecord = ^TInsertRecord;
      TInsertRecord = record
        Data: TParseResult;
        Next: PInsertRecord;
      end;
  private
    FDatabaseWriterThread: TDatabaseWriterThread;
    FFileReadThread: TFileReadThread;
    FFirstRecord: PParseRecord;
    FLastRecord: PParseRecord;
    FFirstInsertRecord: PInsertRecord;
    FLastInsertRecord: PInsertRecord;
    FLock: TRTLCriticalSection;
    FQueueLength: Integer;
    FInsertQueueLength: Integer;
    FPagesRead: Int64;
    FPagesParsed: Int64;
    FPagesInserted: Int64;
    FProcessingOver: Boolean;
    FReadingPaused: Boolean;
    FWorkerCount: Integer;

    procedure SetReadingPaused(const Value: Boolean);

    class function GetProcessingOver: Boolean; static;
    class procedure SetProcessingOver(const Value: Boolean); static;
    function GetStatusInfo: TStatusInfo;

    property ReadingPaused: Boolean read FReadingPaused write SetReadingPaused;
  public
    constructor Create;
    destructor Destroy; override;

    procedure CreateThreads;

    class procedure AddToQueue(const AName, AText: AnsiString);
    class function ReadFromQueue: TParseRecord;
    class procedure AddToInsertQueue(AResult: TParseResult);
    class function ReadFromInsertQueue: TParseResult;

    class procedure NotifyWorkerStarted;
    class procedure NotifyWorkerFinished;

    class function GetInstance: TScheduler;
    class procedure EndInstance;

    property StatusInfo: TStatusInfo read GetStatusInfo;
    class property ProcessingOver: Boolean read GetProcessingOver write SetProcessingOver;
  end;

implementation

uses
  AppSettings, WikiPageProcessingThread, AppUnit, LoggerUnit;

{ TScheduler }

class procedure TScheduler.AddToQueue(const AName, AText: AnsiString);
var
  TheInstance: TScheduler;
  TheRecord: PParseRecord;
begin
  New(TheRecord);
  Initialize(TheRecord^);
  TheRecord^.Name := AName;
  TheRecord^.Text := AText;
  TheRecord^.Next := nil;
  TheInstance := GetInstance;
  EnterCriticalSection(TheInstance.FLock);
  if TheInstance.FLastRecord <> nil then
    TheInstance.FLastRecord^.Next := TheRecord;
  TheInstance.FLastRecord := TheRecord;
  if TheInstance.FFirstRecord = nil then
    TheInstance.FFirstRecord := TheRecord;
  Inc(TheInstance.FQueueLength);
  Inc(TheInstance.FPagesRead);
  TheInstance.ReadingPaused := TheInstance.ReadingPaused or (TheInstance.FQueueLength >= ConstWhenStopFileReader);
  LeaveCriticalSection(TheInstance.FLock);
end;

class function TScheduler.ReadFromInsertQueue: TParseResult;
var
  TheInstance: TScheduler;
  TheRecord: PInsertRecord;
begin
  Result := nil;
  TheInstance := GetInstance;
  EnterCriticalSection(TheInstance.FLock);
  try
    TheRecord := TheInstance.FFirstInsertRecord;
    if TheRecord = nil then
      Exit;
    TheInstance.FFirstInsertRecord := TheRecord^.Next;
    if TheInstance.FFirstInsertRecord = nil then
      TheInstance.FLastInsertRecord := nil;
    Dec(TheInstance.FInsertQueueLength);
    Inc(TheInstance.FPagesInserted);
    TheInstance.ReadingPaused := TheInstance.ReadingPaused and (
      (TheInstance.FQueueLength > ConstWhenRestartFileReader) or
      (TheInstance.FInsertQueueLength > ConstWhenRestartFileReader)
    );
  finally
    LeaveCriticalSection(TheInstance.FLock);
  end;
  Result := TheRecord^.Data;
  Dispose(TheRecord);
end;

class function TScheduler.ReadFromQueue: TParseRecord;
var
  TheInstance: TScheduler;
  TheRecord: PParseRecord;
begin
  Result.Name := '';
  TheInstance := GetInstance;
  EnterCriticalSection(TheInstance.FLock);
  try
    TheRecord := TheInstance.FFirstRecord;
    if TheRecord = nil then
      Exit;
    TheInstance.FFirstRecord := TheRecord^.Next;
    if TheInstance.FFirstRecord = nil then
      TheInstance.FLastRecord := nil;
    Dec(TheInstance.FQueueLength);
    Inc(TheInstance.FPagesParsed);
    TheInstance.ReadingPaused := TheInstance.ReadingPaused and (
      (TheInstance.FQueueLength > ConstWhenRestartFileReader) or
      (TheInstance.FInsertQueueLength > ConstWhenRestartFileReader)
    );
  finally
    LeaveCriticalSection(TheInstance.FLock);
  end;
  Result := TheRecord^;
  Result.Next := nil;
  Finalize(TheRecord^);
  Dispose(TheRecord);
end;

procedure TScheduler.SetReadingPaused(const Value: Boolean);
begin
  if FReadingPaused = Value then
    Exit;
  FReadingPaused := Value;
  FFileReadThread.CanParse := not Value;
end;

class procedure TScheduler.AddToInsertQueue(AResult: TParseResult);
var
  TheInstance: TScheduler;
  TheRecord: PInsertRecord;
begin
  New(TheRecord);
  TheRecord^.Next := nil;
  TheRecord^.Data := AResult;
  TheInstance := GetInstance;
  EnterCriticalSection(TheInstance.FLock);
  if TheInstance.FLastInsertRecord <> nil then
    TheInstance.FLastInsertRecord^.Next := TheRecord;
  TheInstance.FLastInsertRecord := TheRecord;
  if TheInstance.FFirstInsertRecord = nil then
    TheInstance.FFirstInsertRecord := TheRecord;
  Inc(TheInstance.FInsertQueueLength);
  TheInstance.ReadingPaused := TheInstance.ReadingPaused or (TheInstance.FInsertQueueLength >= ConstWhenStopFileReader);
  LeaveCriticalSection(TheInstance.FLock);
end;

constructor TScheduler.Create;
begin
  InitializeCriticalSection(FLock);
  FFirstRecord := nil;
  FLastRecord := nil;
  FFirstInsertRecord := nil;
  FLastInsertRecord := nil;
  FReadingPaused := False;
  FWorkerCount := 0;
  FPagesRead := 0;
  FPagesParsed := 0;
  FPagesInserted := 0;
  FQueueLength := 0;
  FInsertQueueLength := 0;
  TLogger.Info(nil, ['App getinstance']);
  TApp.GetInstance;
end;

procedure TScheduler.CreateThreads;
var
  I: Integer;
begin
  // initialize the pos tagger
  TLogger.Info(nil, ['App pos tagger']);
  App.PosTagger;
  // initialize the sentence list
  TLogger.Info(nil, ['App sentence list']);
  App.SentenceList;
  TLogger.Info(nil, ['App create threads']);
  FFileReadThread := TFileReadThread.Create;
  FDatabaseWriterThread := TDatabaseWriterThread.Create;
  for I := 0 to Settings.ProcessingThreads - 1 do
    TWikiPageProcessingThread.Create;
  TLogger.Info(nil, ['App after create th']);
end;

destructor TScheduler.Destroy;
var
  TheRecord: PParseRecord;
  TheInsertRecord: PInsertRecord;
begin
  ProcessingOver := True;
  FFileReadThread.ShutDown;
  FDatabaseWriterThread.ShutDown;
  while FWorkerCount > 0 do
    Sleep(10);

  while FFirstRecord <> nil do
  begin
    TheRecord := FFirstRecord^.Next;
    Finalize(FFirstRecord^);
    Dispose(FFirstRecord);
    FFirstRecord := TheRecord;
  end;

  while FFirstInsertRecord <> nil do
  begin
    TheInsertRecord := FFirstInsertRecord^.Next;
    FFirstInsertRecord^.Data.Free;
    Dispose(FFirstInsertRecord);
    FFirstInsertRecord := TheInsertRecord;
  end;

  FFileReadThread.Free;
  FDatabaseWriterThread.Free;

  DeleteCriticalSection(FLock);
  inherited;
end;

class procedure TScheduler.EndInstance;
begin
  FInstance.Free;
  FInstance := nil;
end;

class function TScheduler.GetInstance: TScheduler;
begin
  if FInstance = nil then
  begin
    FInstance := TScheduler.Create;
    FInstance.CreateThreads;
  end;
  Result := FInstance;
end;

class procedure TScheduler.SetProcessingOver(const Value: Boolean);
var
  TheInstance: TScheduler;
begin
  TheInstance := TScheduler.GetInstance;
  EnterCriticalSection(TheInstance.FLock);
  TheInstance.ReadingPaused := True;
  TheInstance.FProcessingOver := Value;
  LeaveCriticalSection(TheInstance.FLock);
end;

class function TScheduler.GetProcessingOver: Boolean;
var
  TheInstance: TScheduler;
begin
  TheInstance := TScheduler.GetInstance;
  EnterCriticalSection(TheInstance.FLock);
  Result := TheInstance.FProcessingOver;
  LeaveCriticalSection(TheInstance.FLock);
end;

function TScheduler.GetStatusInfo: TStatusInfo;
begin
  EnterCriticalSection(FLock);
  Result.PagesRead := FPagesRead;
  Result.PagesParsed := FPagesParsed;
  Result.PagesInserted := FPagesInserted;
  Result.PagesInsertQueue := FInsertQueueLength;
  LeaveCriticalSection(FLock);
  Result.BytesTotal := FFileReadThread.BytesTotal;
  Result.BytesParsed := FFileReadThread.BytesParsed;
end;

class procedure TScheduler.NotifyWorkerStarted;
var
  TheInstance: TScheduler;
begin
  TheInstance := TScheduler.GetInstance;
  EnterCriticalSection(TheInstance.FLock);
  Inc(TheInstance.FWorkerCount);
  LeaveCriticalSection(TheInstance.FLock);
end;

class procedure TScheduler.NotifyWorkerFinished;
var
  TheInstance: TScheduler;
begin
  TheInstance := TScheduler.GetInstance;
  EnterCriticalSection(TheInstance.FLock);
  Dec(TheInstance.FWorkerCount);
  LeaveCriticalSection(TheInstance.FLock);
end;

end.
