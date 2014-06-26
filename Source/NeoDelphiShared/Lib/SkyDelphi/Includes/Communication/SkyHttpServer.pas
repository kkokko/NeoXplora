unit SkyHttpServer;

interface

uses
  Classes, Windows, SysUtils, IdHTTPServer, IdContext, IdCustomHTTPServer,
  SkyLists, SkyHttpLogLine, SkyHttpSession, HttpCommand, EntityList,
  EventData;

type
  TSkyHttpServerDownloadProc = procedure(Sender: TObject; ASession: TSkyHttpSession; const AFilename: string;
    AResponseStream: TMemorystream) of object;
  TSkyHttpServerUploadProc = procedure(Sender: TObject; ASession: TSkyHttpSession; const AFilename: string;
    ARequestStream, AResponseStream: TMemorystream) of object;
  TSkyHttServerNewSessionProc = function(Sender: TObject; const ASessionId: string): TSkyHttpSession of object;

  TSkyHttpServer = class(TObject)
  private
    FLock: TRTLCriticalSection;
    FSessions: TSkyStringList;
    FLog: TSkyObjectList;
    FSessionExpireTime: Integer;
    FMaxLogSize: Integer;
    FCreatedOn: TDateTime;
    FSessionClass: TSkyHttpSessionClass;
    FOnRequestDownload: TSkyHttpServerDownloadProc;
    FOnRequestUpload: TSkyHttpServerUploadProc;
    FHttpCommands: TSkyClassTypeList;
    FDefaultHttpCommand: THttpCommandClass;
    FHttpS: TIdHTTPServer;
    FAsyncSessions: TSkyStringList;

    procedure SetSessionExpireTime(const Value: Integer);
  protected
    procedure ActivateHttps; virtual;
    procedure AddLogLine(ALogLine: TSkyHttpLogLine); virtual;
    procedure HandleCommandGet(AContext: TIdContext; ARequestInfo: TIdHTTPRequestInfo; AResponseInfo: TIdHTTPResponseInfo); virtual;
    procedure RegisterHttpCommand(const AName: string; ACommandClass: THttpCommandClass);
    procedure RegisterHttpCommands; virtual;
  public
    constructor Create; reintroduce; virtual;
    destructor Destroy; override;

    procedure AnounceEvent(AnEvent: TEventData);
    function AddSession(const ASessionId: string): TSkyHttpSession;
    function CheckSession(ARequestInfo: TIdHTTPRequestInfo): Boolean;
    procedure Disconnect;
    function GetSessionObject(ASession: TIdHTTPSession): TSkyHttpSession;
    function GetSessionString(ASession: TIdHTTPSession): string;
    procedure HandleSessionEnd(Sender: TIdHTTPSession); virtual;
    procedure Listen(APort: Word);
    procedure Lock;
    procedure UnLock;

    property AsyncSessions: TSkyStringList read FAsyncSessions write FAsyncSessions;
    property CreatedOn: TDateTime read FCreatedOn;
    property DefaultHttpCommand: THttpCommandClass read FDefaultHttpCommand write FDefaultHttpCommand;
    property Log: TSkyObjectList read FLog;
    property HttpS: TIdHTTPServer read FHttps;
    property MaxLogSize: Integer read FMaxLogSize write FMaxLogSize;
    property SessionClass: TSkyHttpSessionClass read FSessionClass write FSessionClass;
    property SessionExpireTime: Integer read FSessionExpireTime write SetSessionExpireTime; // in seconds
    property Sessions: TSkyStringList read FSessions;
    property OnRequestDownload: TSkyHttpServerDownloadProc index 0 read FOnRequestDownload write FOnRequestDownload;
    property OnRequestUpload: TSkyHttpServerUploadProc index 1 read FOnRequestUpload write FOnRequestUpload;
  end;

implementation

uses
  SkyException, HttpCommandVer, HttpCommandStatus,
  HttpCommandPing, HttpCommandRoot, HttpCommandDefault, ExceptionClasses,
  Session, AsyncSession, Entity;

{ TSkyHttpServer }
  
procedure TSkyHttpServer.AddLogLine(ALogLine: TSkyHttpLogLine);
begin
  Lock;
  try
    FLog.InsertItem(0, ALogLine, ALogLine);
    if FLog.Count > MaxLogSize then
      FLog.DeleteFromIndex(MaxLogSize);
  finally
    UnLock;
  end;
end;

function TSkyHttpServer.AddSession(const ASessionId: string): TSkyHttpSession;
begin
  Lock;
  try
    Result := SessionClass.Create(ASessionId);
    Sessions.AddObject(ASessionId, Result);
  finally
    UnLock;
  end;
end;

procedure TSkyHttpServer.AnounceEvent(AnEvent: TEventData);
var
  I: Integer;
begin
  try
    for I := 0 to FAsyncSessions.Count - 1 do
      (FAsyncSessions.Objects[I] as TAsyncSession).AnounceEvent(AnEvent);
  finally
    AnEvent.Free;
  end;
end;

function TSkyHttpServer.CheckSession(ARequestInfo: TIdHTTPRequestInfo): Boolean;
begin
  if GetSessionString(ARequestInfo.Session) = '' then
    raise ESkyInvalidSession.Create(Self, 'CheckSession');
  Result := True;
end;

constructor TSkyHttpServer.Create;
begin
  inherited Create;
  FHttpS := nil; // will be created on Listen
  FSessions := TSkyStringList.Create;
  FSessions.OwnsObjects := True;
  FLog := TSkyObjectList.Create;
  FLog.OwnsObjects := True;
  FLog.Sorted := False;
  InitializeCriticalSection(FLock);
  FSessionExpireTime := 100;
  FMaxLogSize := 1000;
  FCreatedOn := Now;
  FSessionClass := TSkyHttpSession;
  FHttpCommands := TSkyClassTypeList.Create;
  FAsyncSessions := TSkyStringList.Create(False);
  RegisterHttpCommands;
end;

destructor TSkyHttpServer.Destroy;
begin
  try
    Disconnect; 
  except 
  end;
  FreeAndNil(FHttpS);
  FreeAndNil(FSessions);
  FreeAndNil(FLog);
  FHttpCommands.Free;
  FAsyncSessions.Free;
  DeleteCriticalSection(FLock);
  inherited;
end;

procedure TSkyHttpServer.Disconnect;
begin
  FHttpS.Active := False;
end;

function TSkyHttpServer.GetSessionObject(ASession: TIdHTTPSession): TSkyHttpSession;
begin
  if Assigned(ASession) then
  begin
    Lock;
    try
      Result := FSessions.ObjectOfValueDefault[ASession.SessionID, nil] as TSkyHttpSession;
    finally
      UnLock;
    end;
  end
  else
    Result := nil;
end;

function TSkyHttpServer.GetSessionString(ASession: TIdHTTPSession): string;
begin
  if Assigned(ASession) then
    Result := ASession.SessionID
  else
    Result := '';
end;

procedure TSkyHttpServer.RegisterHttpCommands;
begin
  RegisterHttpCommand('/auto/ver', THttpCommandVer);
  RegisterHttpCommand('/status', THttpCommandStatus);
  RegisterHttpCommand('/auto/ping', THttpCommandPing);
  RegisterHttpCommand('/', THttpCommandRoot);
  FDefaultHttpCommand := THttpCommandDefault;
end;

procedure TSkyHttpServer.HandleCommandGet(AContext: TIdContext;
  ARequestInfo: TIdHTTPRequestInfo; AResponseInfo: TIdHTTPResponseInfo);
var
  TheLogLine: TSkyHttpLogLine;
  TheDocument, TheResponseText: string;
  TheCallFinished: Boolean;
  TheCommand: THttpCommandClass;
  TheShortString: ShortString;
begin
  if not (ARequestInfo.CommandType in [hcGET, hcPOST]) then //serverul nu stie decat GET si Post, altceva=bad request
  begin
    AResponseInfo.ResponseNo := 500; //RSHTTPMethodNotAllowed
    AResponseInfo.ResponseText := 'only get and post supported';
    AResponseInfo.WriteHeader;
    AResponseInfo.WriteContent;
    Exit;
  end;
  if (ARequestInfo.CommandType = hcPOST) and (ARequestInfo.PostStream = nil) then
  begin
    ARequestInfo.PostStream := TMemoryStream.Create;
    if ARequestInfo.FormParams <> '' then
    begin
      TheShortString := UTF8EncodeToShortString(ARequestInfo.FormParams);
      ARequestInfo.PostStream.Write(TheShortString[1], Length(TheShortString));
    end;
  end;
  Session.glbSession.HttpContext := AContext;
  Session.glbSession.HttpRequestInfo := ARequestInfo;
  Session.glbSession.HttpResponseInfo := AResponseInfo;
  Session.glbSession.HttpServer := Self;
  if (Session.glbSession.Data = nil) and (ARequestInfo.Session <> nil) then
    Session.glbSession.Data := TEntity(FSessions.ObjectOfValueDefault[ARequestInfo.Session.SessionID, nil]).CreateACopy;

  TheLogLine := TSkyHttpLogLine.Create(ARequestInfo);
  TheCallFinished := False;
  try
    TheDocument := ARequestInfo.Document;
    TheLogLine.ResponseCode := 200;
    TheResponseText := 'OK';
    AResponseInfo.Responseno := TheLogLine.ResponseCode;

    AResponseInfo.ContentStream := TMemoryStream.Create;
    try
      if Assigned(ARequestInfo.PostStream) then
        ARequestInfo.PostStream.Position := 0;
      TheCommand := THttpCommandClass(FHttpCommands.FindByName(TheDocument));
      if TheCommand <> nil then
        TheCommand.Execute(Self, TheResponseText)
      else
        FDefaultHttpCommand.Execute(Self, TheResponseText);
      AResponseInfo.WriteHeader;
      AResponseInfo.WriteContent;
    finally
      AResponseInfo.ContentStream.Free;
      AResponseInfo.ContentStream := nil;
    end;
    TheCallFinished := True;
  except on E: Exception do
  begin
    TheLogLine.ResponseCode := 500; // Internal Server Error
    if (E is ESkyException) then
      TheResponseText := (E as ESkyException).TranslatedMessage
    else
      TheResponseText := 'Internal server error: ' + E.Message;
  end;
  end;
  AddLogLine(TheLogLine);
  TheLogLine.ResponseText := TheResponseText;
  if not TheCallFinished then
  begin
    if not AResponseInfo.HeaderHasBeenWritten then
      AResponseInfo.WriteHeader;
    AResponseInfo.WriteContent; 
  end;
end;

procedure TSkyHttpServer.HandleSessionEnd(Sender: TIdHTTPSession);
begin
  Lock;
  try
    FAsyncSessions.Delete(Sender.SessionID);
    FSessions.Delete(Sender.SessionID);
  finally
    UnLock;
  end;
end;

procedure TSkyHttpServer.Listen(APort: Word);
begin
  if not Assigned(FHttpS) then
  begin
    FHttpS := TIdHTTPServer.Create(nil);
    FHttpS.OnCommandGet := HandleCommandGet;
    FHttpS.OnSessionEnd := HandleSessionEnd;
    FHttpS.SessionState := True;
    FHttpS.AutoStartSession := False;
    FHttpS.KeepAlive := False;
    FHttpS.ParseParams := False; // do not parse form params
  end
  else
    Disconnect;
  Randomize;
  Lock;
  try
    FSessions.Clear;
    FLog.Clear;
  finally
    UnLock;
  end;
  FHttpS.DefaultPort := APort;
  FHttpS.SessionTimeOut := SessionExpireTime * 1000;
  ActivateHttps;
end;

procedure TSkyHttpServer.ActivateHttps;
begin
  FHttpS.Active := True;
end;

procedure TSkyHttpServer.Lock;
begin
  EnterCriticalSection(FLock);
end;

procedure TSkyHttpServer.RegisterHttpCommand(const AName: string;
  ACommandClass: THttpCommandClass);
begin
  FHttpCommands.Add(ACommandClass, AName);
end;

procedure TSkyHttpServer.SetSessionExpireTime(const Value: Integer);
begin
  FSessionExpireTime := Value;
  if Assigned(FHttpS) then
    FHttpS.SessionTimeOut := FSessionExpireTime * 1000;
end;

procedure TSkyHttpServer.UnLock;
begin
  LeaveCriticalSection(FLock);
end;

end.
