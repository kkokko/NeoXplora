unit AsyncClientCommands;

interface

uses
  Command, Communication;

type
  TCommandRegisterEventThread = class(TCommand)
  protected
    class function DoExecute(ARequest: TRequest): TGenericResponse; override;
    class function SessionRequired: Boolean; override;
  end;

  TCommandCheckEvents = class(TCommand)
  protected
    class function DoExecute(ARequest: TRequest): TGenericResponse; override;
  end;

procedure RegisterAsyncClientCommands;

implementation

uses
  Session, ClientRequestRegisterEventThread, ClientRequestCheckEvents, ClientSession,
  ExceptionClasses, AsyncSession;

procedure RegisterAsyncClientCommands;
begin
  TCommandRegisterEventThread.RegisterClass(TClientRequestRegisterEventThread);
  TCommandCheckEvents.RegisterClass(TClientRequestCheckEvents);
end;

{ TCommandRegisterEventThread }

class function TCommandRegisterEventThread.DoExecute(ARequest: TRequest): TGenericResponse;
var
  TheAsyncSession: TAsyncSession;
  TheAsyncSessionId: string;
  TheMainSession: TClientSession;
  TheMainSessionId: string;
begin
  TheMainSessionId := (ARequest as TClientRequestRegisterEventThread).MainSessionId;
  TheMainSession := Session.glbSession.HttpServer.Sessions.ObjectOfValueDefault[
    TheMainSessionId, nil] as TClientSession;
  if not Assigned(TheMainSession) then
    raise ESkyMainSessionNotFound.Create(nil, 'TCommandRegisterEventThread.DoExecute');

  // Create a new session id
  TheAsyncSessionId := glbSession.HttpServer.HttpS.CreateSession(Session.glbSession.HttpContext,
    Session.glbSession.HttpResponseInfo, Session.glbSession.HttpRequestInfo).SessionID;
  // Remove if exists in the session lists
  glbSession.HttpServer.Sessions.Delete(TheAsyncSessionId);
  glbSession.HttpServer.AsyncSessions.Delete(TheAsyncSessionId);
  // Create the session object
  TheAsyncSession := TAsyncSession.Create(TheAsyncSessionId, TheMainSessionId);
  Session.glbSession.Data := TheAsyncSession;
  TheMainSession.AsyncSession := TheAsyncSessionId;
  Session.glbSession.HttpServer.Sessions.Add(TheAsyncSessionId, TheAsyncSession);
  Session.glbSession.HttpServer.AsyncSessions.Add(TheAsyncSessionId, TheAsyncSession);

  Result := TClientResponseRegisterEventThread.Create(TheAsyncSessionId);
end;

class function TCommandRegisterEventThread.SessionRequired: Boolean;
begin
  Result := False;
end;

{ TCommandCheckEvents }

class function TCommandCheckEvents.DoExecute(ARequest: TRequest): TGenericResponse;
var
  TheAsyncSession: TAsyncSession;
begin
  TheAsyncSession := Session.glbSession.HttpServer.AsyncSessions.ObjectOfValueDefault[
    Session.glbSession.HttpRequestInfo.Session.SessionID, nil] as TAsyncSession;
  if not Assigned(TheAsyncSession) then
    raise ESkyInvalidSession.Create(nil, 'TCommand.Execute')
  else
    Result := TClientResponseCheckEvents.Create(TheAsyncSession.GetEvents);
end;

end.
