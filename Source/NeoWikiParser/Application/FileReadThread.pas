unit FileReadThread;

interface

uses
  Classes, Windows, TimedLock;

type
  TFileReadThread = class(TThread)
  public
    const
      ConstBufSize = 10 * 1024 * 1024;
  private
    FLock: TRTLCriticalSection;
    FCanParse: Boolean;
    FTimedLock: TTimedLock;

    FMainFile: TFileStream;
    FBuffer: AnsiString;
    FStart: PAnsiChar;
    FEnd: PAnsiChar;
    FMax: PAnsiChar;
    FLeftOvers: AnsiString;

    FBytesParsed: Int64;
    FBytesTotal: Int64;

    procedure DoParse;
    function ReadPageRecord: AnsiString;

    function GetCanParse: Boolean;
    procedure SetCanParse(const Value: Boolean);
    function GetBytesParsed: Int64;
  protected
    procedure Execute; override;
  public
    constructor Create; reintroduce;
    destructor Destroy; override;

    procedure AddPage(const AString: AnsiString);
    procedure ShutDown;

    // set this flag when you want to stop the thread from reading
    property CanParse: Boolean read GetCanParse write SetCanParse;
    property BytesParsed: Int64 read GetBytesParsed;
    property BytesTotal: Int64 read FBytesTotal;
  end;

implementation

uses
  Math, SysUtils, AppSettings, Scheduler, AppUtils, LoggerUnit;

{ TFileReadThread }

procedure TFileReadThread.DoParse;
var
  TheString: AnsiString;
begin
  while CanParse and ((FEnd <> nil) or (FMainFile.Position < FMainFile.Size)) do
  begin
    TheString := ReadPageRecord;
    if TheString = '' then
      Exit;
    TheString := FLeftOvers + TheString;
    AddPage(TheString);
    FLeftOvers := '';
    EnterCriticalSection(FLock);
    try
      FBytesParsed := FMainFile.Position;
    finally
      LeaveCriticalSection(FLock);
    end;
  end;
  CanParse := False;
end;

function TFileReadThread.ReadPageRecord: AnsiString;
var
  TheSize: Integer;
begin
  Result := '';
  FStart := nil;
  repeat
    if FEnd = nil then
    begin
      TheSize := Min(ConstBufSize, (FMainFile.Size - FMainFile.Position));
      if TheSize = 0 then
        Exit;
      SetLength(FBuffer, TheSize + 1);
      FMainFile.Read(FBuffer[1], TheSize);
      FBuffer[TheSize + 1] := #0;
      FMax := @FBuffer[TheSize + 1];
      if FStart = nil then
        FEnd := @FBuffer[1]
      else
        FStart := @FBuffer[1];
    end;

    if FStart = nil then
      FStart := FastAnsiPos(FEnd, '<page>');

    if FStart = nil then
    begin
      TheSize := FMax - FEnd;
      SetLength(FLeftOvers, Length(FLeftOvers) + TheSize);
      StrLCopy(PAnsiChar(FLeftOvers)+ Length(FLeftOvers) - TheSize, FEnd, TheSize);
      FEnd := nil;
      Continue;
    end;

    FEnd := FastAnsiPos(FStart, '</page>');
    if FEnd = nil then
    begin
      TheSize := FMax - FStart;
      SetLength(FLeftOvers, Length(FLeftOvers) + TheSize);
      StrLCopy(PAnsiChar(FLeftOvers) + Length(FLeftOvers) - TheSize, FStart, TheSize);
      Continue;
    end else
     Inc(FEnd, 7);
  until (FStart <> nil) and (FEnd <> nil);
  TheSize := FEnd - FStart - 13; //do not return <page> tags
  SetLength(Result, TheSize);
  StrLCopy(PAnsiChar(Result), FStart + 6, TheSize);
end;

procedure TFileReadThread.AddPage(const AString: AnsiString);
var
  TheName: AnsiString;
  TheRedirect: AnsiString;
  TheText: AnsiString;
begin
  TheName := ExtractTextBetweenSeparators(AString, '<title>', '</title>');
  TheRedirect := ExtractTextBetweenSeparators(AString, '<redirect title="', '" />');
  if TheRedirect <> '' then
  begin
    // TODO:
    //    AddRedirect(TheName, TheRedirect);
    Exit;
  end;
  TheText := ExtractValueFromTag(AString, 'text');
  TScheduler.AddToQueue(TheName, TheText);
end;

constructor TFileReadThread.Create;
begin
  inherited Create(True);
  FreeOnTerminate := False;
  InitializeCriticalSection(FLock);
  FTimedLock := TTimedLock.Create('NasWikiParser.FileReadThread');
  FTimedLock.LockInterval := 3000; // 3 seconds
  FMainFile := TFileStream.Create(Settings.WikiFile, fmOpenRead or fmShareDenyNone);
  FBytesTotal := FMainFile.Size;
  Suspended := False;
end;

destructor TFileReadThread.Destroy;
begin
  FMainFile.Free;
  DeleteCriticalSection(FLock);
  FTimedLock.Free;
  inherited;
end;

procedure TFileReadThread.Execute;
begin
  FBytesParsed := 0;
  FCanParse := True;
  repeat
    try
      if CanParse then
        DoParse
      else
        FTimedLock.WaitForLock;
    except on E: Exception do // eat all exceptions
      TLogger.Error(Self, E);
    end;
  until Terminated;
end;

function TFileReadThread.GetBytesParsed: Int64;
begin
  EnterCriticalSection(FLock);
  try
    Result := FBytesParsed;
  finally
    LeaveCriticalSection(FLock);
  end;
end;

function TFileReadThread.GetCanParse: Boolean;
begin
  EnterCriticalSection(FLock);
  try
    Result := FCanParse;
  finally
    LeaveCriticalSection(FLock);
  end;
end;

procedure TFileReadThread.SetCanParse(const Value: Boolean);
begin
  EnterCriticalSection(FLock);
  try
    FCanParse := Value;
  finally
    LeaveCriticalSection(FLock);
  end;
  if Value then
    FTimedLock.BreakLock;
end;

procedure TFileReadThread.ShutDown;
var
  TheHandle: Cardinal;
begin
  TheHandle := Handle;
  Terminate;
  FTimedLock.BreakLock;
  WaitForSingleObject(TheHandle, INFINITE);
end;

end.
