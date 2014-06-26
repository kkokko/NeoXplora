unit WebInterfaceHandler;

interface

uses
  SkyHttpServer, IdCustomHTTPServer;

type
  TWebInterfaceHandler = class(TSkyHttpServer)
  protected
    procedure RegisterHttpCommands; override;
  public
    class function GetInstance: TWebInterfaceHandler;
    class procedure EndInstance;
    constructor Create; override;
    procedure BringOnline;
    procedure HandleSessionEnd(Sender: TIdHTTPSession); override;
  end;

implementation

uses
  AppUnit, TypesFunctions, AppHttpCommandRequestJson, AppClientSession,
  ServerCore, Session, LoggerUnit;

var
  _WebInterfaceHandler: TWebInterfaceHandler = nil;

{ TWebInterfaceHandler }

class function TWebInterfaceHandler.GetInstance: TWebInterfaceHandler;
begin
  if not Assigned(_WebInterfaceHandler) then
    _WebInterfaceHandler := TWebInterfaceHandler.Create;
  Result := _WebInterfaceHandler;
end;

procedure TWebInterfaceHandler.HandleSessionEnd(Sender: TIdHTTPSession);
begin
  try
    if (Session.glbSession.HttpRequestInfo <> nil) then
    begin
      inherited;
      Exit;
    end;
    try
      Session.glbSession.Data := Sessions.ObjectOfValueDefault[Sender.SessionID, nil];
      if Session.glbSession.Data <> nil then
        App.CloseSession;
    finally
      inherited;
    end;
  except
    TLogger.Warn(Self, ['Error closing http session.']);
  end;
end;

constructor TWebInterfaceHandler.Create;
begin
  inherited;
  SessionExpireTime := App.Settings.SessionExpire;
  SessionClass := TAppClientSession;
end;

class procedure TWebInterfaceHandler.EndInstance;
begin
  FreeAndNil(_WebInterfaceHandler);
end;

procedure TWebInterfaceHandler.BringOnline;
begin
  Listen(App.Settings.ServicePort);
end;

procedure TWebInterfaceHandler.RegisterHttpCommands;
begin
  inherited;
  RegisterHttpCommand('/Request.json', TAppHttpCommandRequestJson);
  RegisterHttpCommand('/', TAppHttpCommandRequestJson);
  DefaultHttpCommand := TAppHttpCommandRequestJson;
end;

end.
