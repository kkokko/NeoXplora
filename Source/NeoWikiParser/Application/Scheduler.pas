unit Scheduler;

interface

uses
  Classes, Windows, FileReadThread, DatabaseWriterThread;

type
  TScheduler = class
  private
    const
      ConstWhenStopFileReader = 10000;
      ConstWhenRestartFileReader = 5000;
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
      end;
      PParseRecord = ^TParseRecord;
      TParseRecord = record
        Name: AnsiString;
        Text: AnsiString;
        Next: PParseRecord;
      end;
  private
    FDatabaseWriterThread: TDatabaseWriterThread;
    FFileReadThread: TFileReadThread;
    FFirstRecord: PParseRecord;
    FLastRecord: PParseRecord;
    FLock: TRTLCriticalSection;
    FQueueLength: Integer;
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

    class procedure NotifyWorkerStarted;
    class procedure NotifyWorkerFinished;

    class function GetInstance: TScheduler;
    class procedure EndInstance;

    property StatusInfo: TStatusInfo read GetStatusInfo;
    class property ProcessingOver: Boolean read GetProcessingOver write SetProcessingOver;
  end;

implementation

uses
  AppSettings, ProcessingThread, ParseResults;

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
    TheInstance.ReadingPaused := TheInstance.ReadingPaused and (TheInstance.FQueueLength > ConstWhenRestartFileReader);
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

constructor TScheduler.Create;
begin
  InitializeCriticalSection(FLock);
  FFirstRecord := nil;
  FLastRecord := nil;
  FReadingPaused := False;
  FWorkerCount := 0;
  FPagesRead := 0;
  FPagesParsed := 0;
  FPagesInserted := 0;
end;

procedure TScheduler.CreateThreads;
var
  I: Integer;
begin
  FFileReadThread := TFileReadThread.Create;
  FDatabaseWriterThread := TDatabaseWriterThread.Create;
  for I := 0 to Settings.ProcessingThreads - 1 do
    TProcessingThread.Create;
end;

destructor TScheduler.Destroy;
var
  TheAString: AnsiString;
  TheFile: TMemoryStream;
  TheRecord: PParseRecord;
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

  FFileReadThread.Free;
  FDatabaseWriterThread.Free;

  DeleteCriticalSection(FLock);


  TheAString := UTF8Encode(TParseResults.GetUnknownTags);
  TheFile := TMemoryStream.Create;
  try
    TheFile.Write(TheAString[1], Length(TheAString));
    TheFile.savetofile('Unknown tags.txt');
  finally
    TheFile.Free;
  end;
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
