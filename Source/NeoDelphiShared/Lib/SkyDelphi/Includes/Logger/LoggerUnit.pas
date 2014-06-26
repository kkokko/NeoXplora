unit LoggerUnit;

interface

uses
  Classes, SysUtils, SkyException, SkyLists, Windows, TypesConsts;

type
  TLogger = class;
  TLoggerClass = class of TLogger;

  TLogger = class(TObject)// Thread safe class
  private
    class var
      FInstance: TLogger;
    var
      FFileName: TSkyString;
      FBuffer: TSkyStringList;
      FFileStream: TFileStream;
      FSupressExceptions: Boolean;
      FLock: TRTLCriticalSection;
    procedure Lock;
    procedure UnLock;
    procedure OpenFile;
    procedure CloseFile;
    procedure WriteToFile;
    procedure LogTextError(ASender: TObject; AnError: Exception);
    procedure SetFileName(const Value: TSkyString);
    function GetFileName: TSkyString;
    function GetSupressExceptions: Boolean;
    procedure SetSupressExceptions(const Value: Boolean);
  protected
 {$IFDEF UNICODE}
    class destructor Destroy;
 {$ENDIF}
  public
    constructor Create;
    destructor Destroy; override;
    class procedure Error(ASender: TObject; AnError: Exception); overload;
    class procedure Error(AnError: ESkyException); overload;
    class procedure Error(ASender: TObject; const SomeStrings: array of TSkyString; ATime: TDateTime = 0); overload;
    class function GetInstance: TLogger;
    class procedure Info(ASender: TObject; const SomeStrings: array of TSkyString; ATime: TDateTime = 0);
    class procedure Warn(ASender: TObject; const SomeStrings: array of TSkyString; ATime: TDateTime = 0);
    property FileName: TSkyString read GetFileName write SetFileName;
    property SupressExceptions: Boolean read GetSupressExceptions write SetSupressExceptions;
  end;

implementation

uses
  TypesFunctions, TypesTranslations, StringUtils, Variants, Forms;

{ TLogger }

class procedure TLogger.Error(ASender: TObject; AnError: Exception);
begin
  if AnError is ESkyException then
    Error(AnError as ESkyException)
  else
    GetInstance.LogTextError(ASender, AnError);
end;

constructor TLogger.Create;
begin
  inherited Create;
  InitializeCriticalSection(FLock);
  FFileStream := nil;
  FFileName := ExtractFilePath(Application.ExeName) + 'Log.txt';
  FBuffer := TSkyStringList.Create;
  FBuffer.Sorted := False;
  FSupressExceptions := True;
end;

{$IFDEF UNICODE}
class destructor TLogger.Destroy;
{$ELSE}
procedure TLoggerDestroy;
 {$ENDIF}
begin
  FreeAndNil(TLogger.FInstance);
end;

destructor TLogger.Destroy;
begin
  Self.CloseFile;
  FBuffer.Free;
  DeleteCriticalSection(FLock);
  inherited;
end;

class procedure TLogger.Info(ASender: TObject; const SomeStrings: array of TSkyString; ATime: TDateTime = 0);
var
  TheLogger: TLogger;
begin
  TheLogger := GetInstance;
  TheLogger.Lock;
  try
    TheLogger.FBuffer.Clear;
    TheLogger.FBuffer.Add(TypesFunctions.IfThen(ATime = 0, DateTimeToStr(Now, _SQLFormat), DateTimeToStr(ATime, _SQLFormat)));
    TheLogger.FBuffer.Add(MessageTypeToStr(lmtInfo));
    if Assigned(ASender) then
      TheLogger.FBuffer.Add(ASender.ClassName)
    else
      TheLogger.FBuffer.Add('(nil)');
    TheLogger.FBuffer.AddMultiple(SomeStrings, []);
    TheLogger.WriteToFile;
  finally
    TheLogger.UnLock;
  end;
end;

class procedure TLogger.Warn(ASender: TObject; const SomeStrings: array of TSkyString; ATime: TDateTime = 0);
var
  TheLogger: TLogger;
begin
  TheLogger := GetInstance;
  TheLogger.Lock;
  try
    TheLogger.FBuffer.Clear;
    TheLogger.FBuffer.Add(IfThen(ATime = 0, DateTimeToStr(Now, _SQLFormat), DateTimeToStr(ATime, _SQLFormat)));
    TheLogger.FBuffer.Add(MessageTypeToStr(lmtWarning));
    if Assigned(ASender) then
      TheLogger.FBuffer.Add(ASender.ClassName)
    else
      TheLogger.FBuffer.Add('(nil)');
    TheLogger.FBuffer.AddMultiple(SomeStrings, []);
    TheLogger.WriteToFile;
  finally
    TheLogger.UnLock;
  end;
end;

procedure TLogger.LogTextError(ASender: TObject; AnError: Exception);
begin
  Lock;
  try
    FBuffer.Clear;
    FBuffer.Add(DateTimeToStr(Now, _SQLFormat));
    FBuffer.Add(MessageTypeToStr(lmtError));
    if Assigned(ASender) then
      FBuffer.Add(ASender.ClassName)
    else
      FBuffer.Add('(nil)');
    FBuffer.Add(AnError.Message);
    WriteToFile;
  finally
    UnLock;
  end;
end;

class procedure TLogger.Error(AnError: ESkyException);
var
  I: Integer;
  TheKeyValues: {$IFDEF UNICODE}TKeyValue.TArray{$ELSE}TKeyValueArray{$ENDIF};
  TheLogger: TLogger;
begin
  TheLogger := GetInstance;
  TheLogger.Lock;
  try
    TheLogger.FBuffer.Clear;
    TheLogger.FBuffer.Add(DateTimeToStr(AnError.MessageInfo.DateTime, _SQLFormat));
    TheLogger.FBuffer.Add(MessageTypeToStr(AnError.MessageInfo.MessageType));
    TheLogger.FBuffer.Add(AnError.MessageInfo.EntityClassName);
    TheLogger.FBuffer.Add(AnError.TranslatedMessage);
    TheKeyValues := AnError.Params.GetAsKeyValues;
    for I := 0 to High(TheKeyValues) do
      TheLogger.FBuffer.Add(TheKeyValues[I].Key +': ' + VarToStr(TheKeyValues[I].Value));
    TheLogger.WriteToFile;
  finally
    TheLogger.UnLock;
  end;
end;

procedure TLogger.OpenFile;
begin
  if Assigned(FFileStream) then
    Exit;
  if FileExists(FileName) then
    FFileStream := TFileStream.Create(FileName, fmOpenWrite or fmShareDenyNone)
  else
    FFileStream := TFileStream.Create(FileName, fmCreate or fmOpenWrite or fmShareDenyNone);
end;

procedure TLogger.SetFileName(const Value: TSkyString);
begin
  Lock;
  try
    CloseFile;
    FFileName := Value;
  finally
    UnLock;
  end;
end;

procedure TLogger.CloseFile;
begin
  if not Assigned(FFileStream) then
    Exit;
  FreeAndNil(FFileStream);
end;

procedure TLogger.WriteToFile;
var
  I, TheCount: Integer;
  TheString: AnsiString;
begin
  try
    OpenFile;
    FFileStream.Seek(0, soFromEnd);
    TheCount := FBuffer.Count - 1;
    for I := 0 to TheCount do
    begin
      TheString := AnsiString(FBuffer.Items[I]);
      if I <> TheCount then
      begin
        TheString := AnsiString(ReplaceEnter(TSkyString(TheString)));
        TheString := TheString + TabSeparator;
      end
      else
      begin
        TheString := AnsiString(ReplaceEnter(TSkyString(TheString)));
        TheString := TheString + ReturnLF;
      end;
      FFileStream.Write(TheString[1], Length(TheString));
    end;
  except
    if not FSupressExceptions then
      raise;
  end;
end;

procedure TLogger.Lock;
begin
  EnterCriticalSection(FLock);
end;

procedure TLogger.UnLock;
begin
  LeaveCriticalSection(FLock);
end;

function TLogger.GetFileName: TSkyString;
begin
  Lock;
  try
    Result := FFileName;
  finally
    UnLock;
  end;
end;

class function TLogger.GetInstance: TLogger;
begin
  if not Assigned(TLogger.FInstance) then
    TLogger.FInstance := TLogger.Create;
  Result := TLogger.FInstance;
end;

function TLogger.GetSupressExceptions: Boolean;
begin
  Lock;
  try
    Result := FSupressExceptions;
  finally
    UnLock;
  end;
end;

procedure TLogger.SetSupressExceptions(const Value: Boolean);
begin
  Lock;
  try
    FSupressExceptions := Value;
  finally
    UnLock;
  end;
end;

class procedure TLogger.Error(ASender: TObject; const SomeStrings: array of TSkyString;
  ATime: TDateTime);
var
  TheLogger: TLogger;
begin
  TheLogger := GetInstance;
  TheLogger.Lock;
  try
    TheLogger.FBuffer.Clear;
    TheLogger.FBuffer.Add(IfThen(ATime = 0, DateTimeToStr(Now, _SQLFormat), DateTimeToStr(ATime, _SQLFormat)));
    TheLogger.FBuffer.Add(MessageTypeToStr(lmtError));
    if Assigned(ASender) then
      TheLogger.FBuffer.Add(ASender.ClassName)
    else
      TheLogger.FBuffer.Add('(nil)');
    TheLogger.FBuffer.AddMultiple(SomeStrings, []);
    TheLogger.WriteToFile;
  finally
    TheLogger.UnLock;
  end;
end;

{$IFNDEF UNICODE}
initialization

finalization
  TLoggerDestroy;

{$ENDIF}

end.
