unit EventCheckerThread;

interface

uses
  Classes, ClientInterface, SysUtils, Windows, EntityList, Messages, EventData, SkyLists, TypesConsts, SkyIdList;

type
  TEventCheckerThread = class(TThread)
  private
    type
      TEventTriggeredProc = procedure(AnEvent: TEventData) of object;
    type
      TEventStorageObject = class(TObject)
        Event: TEventTriggeredProc;
        constructor Create(AnEvent: TEventTriggeredProc); reintroduce;
      end;
    class var
      FInstance: TEventCheckerThread;
    var
      FCallBackMessage: Cardinal;
      FClientInterface: TClientInterface;
      FClientInterfaceClass: TClientInterfaceClass;
      FEventHandlers: TSkyIdList;
      FHandle: HWND;
      FLock: TRTLCriticalSection;
      FMainThreadSession: string;
    procedure ReadEvents;
    procedure RegisterEventThread;
    procedure Logout;
    procedure WindProc(var TheMessage: TMessage);
  protected
    procedure Execute; override;
  public
    constructor Create(const ASession: string; AClientInterfaceClass:
      TClientInterfaceClass); reintroduce;
    destructor Destroy; override;

    class function GetInstance(const ASession: string; AClientInterfaceClass:
      TClientInterfaceClass): TEventCheckerThread;
    class procedure EndInstance;

    procedure RegisterEventHandler(AnEventId: TId; AProc: TEventTriggeredProc);
    procedure UnRegisterEventHandler(AnEventId: TId);

    property ClientInterface: TClientInterface read FClientInterface;
  end;

implementation

uses
  ClientRequestRegisterEventThread, ClientRequestCheckEvents, ClientRequestLogout;

{ TEventCheckerThread }

constructor TEventCheckerThread.Create(const ASession: string;
  AClientInterfaceClass: TClientInterfaceClass);
begin
  inherited Create(True);
  FMainThreadSession := ASession;
  FClientInterfaceClass := AClientInterfaceClass;
  FHandle := Classes.AllocateHWnd(WindProc);
  FCallBackMessage := RegisterWindowMessage('TEventCheckerThread.CallBack');
  FreeOnTerminate := True;
  FEventHandlers := TSkyIdList.Create(True);
  FEventHandlers.Sorted := True;
  InitializeCriticalSection(FLock);
  Suspended := False;
end;

destructor TEventCheckerThread.Destroy;
begin
  DeallocateHWnd(FHandle);
  ClientInterface.Free;
  FEventHandlers.Free;
  DeleteCriticalSection(FLock);
  inherited;
end;

class procedure TEventCheckerThread.EndInstance;
var
  TheHandle: Cardinal;
begin
  if Assigned(FInstance) then
  begin
    TheHandle := FInstance.Handle;
    FInstance.Terminate;
    WaitForSingleObject(TheHandle, INFINITE);
  end;
end;

procedure TEventCheckerThread.RegisterEventThread;
var
  TheResponse: TClientResponseRegisterEventThread;
begin
  TheResponse := ClientInterface.ExecuteRequest(TClientRequestRegisterEventThread.
    Create(FMainThreadSession)) as TClientResponseRegisterEventThread;
  ClientInterface.RegisterSession(TheResponse.SessionId);
end;

procedure TEventCheckerThread.RegisterEventHandler(AnEventId: TId;
  AProc: TEventTriggeredProc);
begin
  EnterCriticalSection(FLock);
  try
    FEventHandlers.Delete(AnEventId);
    FEventHandlers.Add(AnEventId, TEventStorageObject.Create(AProc));
  finally
    LeaveCriticalSection(FLock);
  end;
end;

procedure TEventCheckerThread.UnRegisterEventHandler(AnEventId: TId);
begin
  EnterCriticalSection(FLock);
  try
    FEventHandlers.Delete(AnEventId);
  finally
    LeaveCriticalSection(FLock);
  end;
end;

procedure TEventCheckerThread.WindProc(var TheMessage: TMessage);
var
  TheList: TEntityList;
  TheEvent: TEventData;
  TheStorage: TEventStorageObject;
  I: Integer;
begin
  Dispatch(TheMessage);
  if TheMessage.Msg <> FCallBackMessage then
    Exit;
  TheList := TEntityList(TheMessage.WParam);
  for I := 0 to TheList.Count - 1 do
  begin
    TheEvent := TheList[I] as TEventData;
    EnterCriticalSection(FLock);
    try
      TheStorage := FEventHandlers.ObjectOfValueDefault[TheEvent.EventListenerId,
        nil] as TEventStorageObject;
      if not Assigned(TheStorage) then
        Continue;
    finally
      LeaveCriticalSection(FLock);
    end;
    TheStorage.Event(TheEvent);
  end;
end;

procedure TEventCheckerThread.ReadEvents;
var
  TheResponse: TClientResponseCheckEvents;
begin
  TheResponse := ClientInterface.ExecuteRequest(TClientRequestCheckEvents.Create) as
    TClientResponseCheckEvents;
  if 0 = TheResponse.Events.Count then Exit;
  // sending the Events pointer and await for the call to finish
  SendMessage(FHandle, FCallBackMessage, Integer(TheResponse.Events), 0);
end;

procedure TEventCheckerThread.Execute;
begin
  try
    FClientInterface := FClientInterfaceClass.Create;
    FClientInterface.MaintainSession := False;
    RegisterEventThread;
    repeat
        ReadEvents;
    until Terminated;
    Logout;
  except // eat all exceptions
    FInstance := nil;
  end;
end;

class function TEventCheckerThread.GetInstance(const ASession: string;
  AClientInterfaceClass: TClientInterfaceClass): TEventCheckerThread;
begin
  if not Assigned(FInstance) then
    FInstance := TEventCheckerThread.Create(ASession, AClientInterfaceClass);
  Result := FInstance;
end;

procedure TEventCheckerThread.Logout;
begin
  ClientInterface.ExecuteRequest(TClientRequestLogout.Create);
end;

{ TEventCheckerThread.TEventStorageObject }

constructor TEventCheckerThread.TEventStorageObject.Create(
  AnEvent: TEventTriggeredProc);
begin
  inherited Create;
  Event := AnEvent;
end;

end.
