unit WindowsService;

interface

uses
  WinSvc, Windows, SysUtils, Classes;

type
  TServiceState = (svsStopped, svsStarting, svsStopping, svsRunning, scvContinueing, svsPausing, svsPaused);

  TWindowsService = class(TComponent)
  private
    FSCHandle: THandle;
    FState: TServiceState;
    FServiceName: string;
    FHandle: THandle;
    FMachineName: string;
    FActive: Boolean;
    procedure SetState(const Value: TServiceState);
    function GetState: TServiceState;
    procedure SetServiceName(const Value: string);
    procedure SetMachineName(const Value: string);
    function GetHandle: THandle;
    procedure SetActive(const Value: Boolean);
    procedure CloseDependendServices(Handle: THandle);
  protected
    procedure CloseHandle;
    procedure CloseHandleSC;
    procedure HandleNeeded;
  public
    destructor Destroy; override;
    class procedure CloseServiceByName(const AServiceName: string);
    class procedure StartServiceByName(const AServiceName: string);
    property Handle: THandle read GetHandle;
  published
    property ServiceName: string read FServiceName write SetServiceName;
    property MachineName: string read FMachineName write SetMachineName;
    property State: TServiceState read GetState write SetState;
    property Active: Boolean read FActive write SetActive;
  end;

implementation

{ TServiceStarter }

procedure TWindowsService.CloseHandle;
begin
  if FHandle <> 0 then
  begin
    CloseServiceHandle(FHandle);
    FHandle := 0;
  end;
end;

procedure TWindowsService.CloseHandleSC;
begin
  if FSCHandle <> 0 then
  begin
    CloseServiceHandle(FSCHandle);
    FSCHandle := 0;
  end;
end;

class procedure TWindowsService.CloseServiceByName(const AServiceName: string);
var
  TheService: TWindowsService;
begin
  TheService := TWindowsService.Create(nil);
  try
    TheService.MachineName := '';
    TheService.ServiceName := AServiceName;
    TheService.State := svsStopped;
    TheService.Active := True;
    while TheService.State <> svsStopped do
    begin
      TheService.State := svsStopped;
      Sleep(100);
    end;
  finally
    TheService.Free;
  end;
end;

class procedure TWindowsService.StartServiceByName(const AServiceName: string);
var
  TheService: TWindowsService;
begin
  TheService := TWindowsService.Create(nil);
  try
    TheService.MachineName := '';
    TheService.ServiceName := AServiceName;
    TheService.State := svsRunning;
    TheService.Active := True;
  finally
    TheService.Free;
  end;
end;

destructor TWindowsService.Destroy;
begin
  CloseHandle;
  inherited;
end;

function TWindowsService.GetHandle: THandle;
begin
  HandleNeeded;
  Result := FHandle;
end;

function TWindowsService.GetState: TServiceState;
var
  ServiceStatus: TServiceStatus;
begin
  if FActive then
    begin
    if (FServiceName = '') then
      begin
      Result := svsStopped;
      Exit;
      end;
    HandleNeeded;
    if not QueryServiceStatus(FHandle, ServiceStatus) then
      RaiseLastOSError;
    Result := TServiceState(ServiceStatus.dwCurrentState - 1);
    end
  else
    Result := FState;
end;

procedure TWindowsService.HandleNeeded;
begin
  if FHandle = 0 then
    begin
    if FSCHandle = 0 then
      begin
      FSCHandle := OpenSCManager(Pointer(FMachineName), nil, GENERIC_EXECUTE);
      if FSCHandle = 0 then
        RaiseLastOSError;
      end;
    FHandle := OpenService(FSCHandle, PChar(FServiceName), GENERIC_EXECUTE+SERVICE_QUERY_STATUS+SERVICE_ENUMERATE_DEPENDENTS);
    if FHandle = 0 then
      RaiseLastOSError;
    end;
end;


procedure TWindowsService.SetActive(const Value: Boolean);
begin
  if FActive = Value then
    Exit;
  FActive := Value;
  if FActive then
    State := FState
  else
  begin
    CloseHandle;
    CloseHandleSC;
  end;
end;

procedure TWindowsService.SetMachineName(const Value: string);
begin
  if FMachineName = Value then
    Exit;
  CloseHandle;
  FMachineName := Value;
end;

procedure TWindowsService.SetServiceName(const Value: string);
begin
  if FServiceName = Value then
    Exit;
  CloseHandle;
  CloseHandleSC;
  FServiceName := Value;
end;

procedure TWindowsService.CloseDependendServices(Handle: THandle);
type
  TEnumServiceStatusArray = array[0..$FFFF] of TEnumServiceStatus;
  PEnumServiceStatusArray = ^TEnumServiceStatusArray;
var
  DependendHandle: THandle;
  I: Integer;
  ServicesCount: Cardinal;
  NeededBytes: Cardinal;
  DependendServices : PEnumServiceStatusArray;
begin
  NeededBytes := 1 * SizeOf(TEnumServiceStatusArray);
  GetMem(DependendServices, NeededBytes);
  while not EnumDependentServices(Handle, SERVICE_ACTIVE, DependendServices^[0], NeededBytes, NeededBytes, ServicesCount) do
    begin
    if GetLastError = ERROR_MORE_DATA then
      begin
      FreeMem(DependendServices);
      GetMem(DependendServices, NeededBytes);
      end
    else
      RaiseLastOSError;
    end;
  for I := 0 to ServicesCount - 1 do
    with DependendServices^[I] do
      begin
      DependendHandle := OpenService(FSCHandle, lpServiceName, GENERIC_EXECUTE+SERVICE_QUERY_STATUS+SERVICE_ENUMERATE_DEPENDENTS);
      while not ControlService(DependendHandle, SERVICE_CONTROL_STOP, ServiceStatus) do
        case GetLastError of
          ERROR_DEPENDENT_SERVICES_RUNNING:
            CloseDependendServices(DependendHandle);
          ERROR_SERVICE_NOT_ACTIVE:
            Break;
          ERROR_SERVICE_CANNOT_ACCEPT_CTRL: ;
          else
            RaiseLastOSError;
          end;
      CloseServiceHandle(DependendHandle);
      end;
  FreeMem(DependendServices);
end;

procedure TWindowsService.SetState(const Value: TServiceState);
const
{
SERVICE_CONTROL_STOP
Requests the service to stop. The hService handle must have SERVICE_STOP access.
SERVICE_CONTROL_PAUSE
Requests the service to pause. The hService handle must have SERVICE_PAUSE_CONTINUE access.
SERVICE_CONTROL_CONTINUE
Requests the paused service to resume. The hService handle must have SERVICE_PAUSE_CONTINUE access.
SERVICE_CONTROL_INTERROGATE
Requests the service to update immediately its current status information to the service control manager. The hService handle must have SERVICE_INTERROGATE access.
SERVICE_CONTROL_SHUTDOWN
}
  StateControlMap: array[TServiceState] of Integer = (SERVICE_CONTROL_STOP, SERVICE_CONTROL_CONTINUE, SERVICE_CONTROL_STOP, SERVICE_CONTROL_CONTINUE, SERVICE_CONTROL_CONTINUE, SERVICE_CONTROL_PAUSE, SERVICE_CONTROL_PAUSE);
var
  Error: Cardinal;
  StateSet: boolean;
  Arg: PChar;
  ServiceStatus: TServiceStatus;
begin
  FState := Value;
  if Active then
  begin
    HandleNeeded;
    Arg := nil;
    StateSet := False;
    // svsStopped, svsStarting, svsStopping, svsRunning, scvContinueing, svsPausing, svsPaused
    repeat
      if not ControlService(FHandle, StateControlMap[Value], ServiceStatus) then
      begin
        Error := GetLastError;
        case Error of
          ERROR_SERVICE_CANNOT_ACCEPT_CTRL:
          begin
            Sleep(10);
          end;
          ERROR_SERVICE_NOT_ACTIVE:
            if not (Value in [svsStopped, svsStopping]) then
            begin
              if not StartService(FHandle, 0, Arg) then
              begin
                Error := GetLastError;
                if Error <> ERROR_SERVICE_CANNOT_ACCEPT_CTRL then
                  RaiseLastOSError
                else
                  Sleep(10);
              end;
              StateSet := Value in [svsRunning, scvContinueing];
            end
            else
              StateSet := True;
          ERROR_DEPENDENT_SERVICES_RUNNING:
            CloseDependendServices(FHandle);
          else
            RaiseLastOSError;
          end;
        end
      else
        StateSet := True;
    until StateSet;
  end;
end;

end.