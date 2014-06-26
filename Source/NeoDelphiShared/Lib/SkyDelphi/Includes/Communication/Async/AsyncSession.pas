unit AsyncSession;

interface

uses
  SkyHttpSession, EntityList, TimedLock, Windows, EventData, TypesConsts, Entity, SkyLists, EventListener, SkyIdList;

type
  TAsyncSession = class(TSkyHttpSession)
  private
    FEvents: TEntityList;
    FTimedLock: TTimedLock;
    FEventListeners: TSkyIdList;
    FLock: TRTLCriticalSection;
    FCurrentEventListenerId: TId;
    FMainSessionId: string;
  public
    constructor Create(const ASessionId, AMainSessionId: string); reintroduce;
    destructor Destroy; override;

    procedure Lock;
    procedure Unlock;

    procedure AnounceEvent(AnEvent: TEventData);
    function GetEvents: TEntities;
    function RegisterEventListener(AListener: TEventListener): TId;
    procedure UnregisterEventListener(AnEventListenerId: TId);

    property TimedLock: TTimedLock read FTimedLock write FTimedLock;
  public
    property EventListeners: TSkyIdList read FEventListeners write FEventListeners;
  published
    property Events: TEntityList read FEvents write FEvents;
    property MainSessionId: string read FMainSessionId write FMainSessionId;
    property SessionId;
  end;

implementation

uses
  SysUtils, Session;

{ TAsyncSession }

procedure TAsyncSession.AnounceEvent(AnEvent: TEventData);
var
  TheEventListener: TEventListener;
  TheEventPassedThru: Boolean;
  TheNewEvent: TEventData;
  I: Integer;
begin
  Lock;
  try
    TheEventPassedThru := False;
    for I := 0 to FEventListeners.Count - 1 do
    begin
      TheEventListener := FEventListeners.Objects[I] as TEventListener;
      if not TheEventListener.EventIsRelevant(AnEvent) then
        Continue;
      TheEventPassedThru := True;
      TheNewEvent := AnEvent.CreateACopy as TEventData;
      TheNewEvent.EventListenerId := TheEventListener.Id;
      Events.Add(TheNewEvent);
    end;
    if TheEventPassedThru then
      TimedLock.BreakLock;
  finally
    Unlock;
  end;
end;

constructor TAsyncSession.Create(const ASessionId, AMainSessionId: string);
begin
  inherited Create(ASessionId);
  InitializeCriticalSection(FLock);
  TimedLock := TTimedLock.Create('TAsyncSession.' + ASessionId);
  TimedLock.LockInterval := 120000; // 2 minutes
  FCurrentEventListenerId := 1;
  FMainSessionId := AMainSessionId;
  FEventListeners := TSkyIdList.Create(True);
end;

destructor TAsyncSession.Destroy;
begin
  TimedLock.Free;
  DeleteCriticalSection(FLock);
  inherited;
end;

function TAsyncSession.GetEvents: TEntities;
var
  TheEventsExist: Boolean;
begin
  Lock;
  try
    TheEventsExist := FEvents.Count <> 0;
  finally
    Unlock;
  end;
  if not TheEventsExist then
    TimedLock.WaitForLock;
  // this object might get deleted when sleeping if the session somehow expires
  if Session.glbSession.HttpServer.AsyncSessions.IndexOf(Session.glbSession.
    HttpRequestInfo.Session.SessionID) = -1 then
    Exit;
  Lock;
  try
    Result := TEntity.CreateAndCopyEntities(FEvents.GetAllEntities);
    FEvents.Clear;
  finally
    Unlock;
  end;
end;

procedure TAsyncSession.Lock;
begin
  EnterCriticalSection(FLock);
end;

function TAsyncSession.RegisterEventListener(AListener: TEventListener): TId;
begin
  Result := FCurrentEventListenerId;
  AListener.Id := Result;
  Inc(FCurrentEventListenerId);
  FEventListeners.Add(Result, AListener);
end;

procedure TAsyncSession.Unlock;
begin
  LeaveCriticalSection(FLock);
end;

procedure TAsyncSession.UnregisterEventListener(AnEventListenerId: TId);
begin
  FEventListeners.Delete(AnEventListenerId);
end;

initialization
  TAsyncSession.RegisterEntityClass;

end.
