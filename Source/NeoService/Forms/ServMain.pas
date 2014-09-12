unit ServMain;

interface

uses
  Windows, Classes, SvcMgr;

type
  TNeoXploraService = class(TService)
    procedure ServiceStart(Sender: TService; var Started: Boolean);
    procedure ServiceStop(Sender: TService; var Stopped: Boolean);
    procedure ServiceShutdown(Sender: TService);
    procedure ServiceBeforeInstall(Sender: TService);
    procedure ServiceCreate(Sender: TObject);
    procedure ServiceAfterInstall(Sender: TService);
  private
    FServiceThread: TServiceThread;
    FServerStartOk: Boolean;
    procedure ServiceLoadInfo(Sender: TObject);
  public
    destructor Destroy; override;
    function GetServiceController: TServiceController; override;
    procedure StartFinished(SuccessFull: Boolean);
  end;

var
  NeoXploraService: TNeoXploraService;

procedure ServiceController(CtrlCode: DWord); stdcall;

implementation

{$R *.DFM}

uses
  ServiceThread, ActiveX, WinSvc, SysUtils;

type
  SERVICE_DESCRIPTION = packed record
    lpDescription: PChar;
  end;

  PSERVICE_DESCRIPTION = ^SERVICE_DESCRIPTION;

procedure ServiceController(CtrlCode: DWord); stdcall;
begin
  NeoXploraService.Controller(CtrlCode);
end;

destructor TNeoXploraService.Destroy;
begin
  FreeAndNil(FServiceThread);
  inherited;
end;

function TNeoXploraService.GetServiceController: TServiceController;
begin
  Result := ServiceController;
end;

procedure TNeoXploraService.ServiceAfterInstall(Sender: TService);
var
  SvcMgr, Svc: SC_HANDLE;
  desc: SERVICE_DESCRIPTION;
begin
  SvcMgr := OpenSCManager(nil, nil, SC_MANAGER_ALL_ACCESS);
  if SvcMgr = 0 then
    Exit;
  try
    Svc := OpenService(SvcMgr, PChar(Name), SERVICE_ALL_ACCESS);
    if Svc = 0 then
      RaiseLastOSError;
    try
      desc.lpDescription := PWideChar(System.ParamStr(0) + ' ' + Name + ' "' + DisplayName + '"');
      ChangeServiceConfig( Svc, SERVICE_NO_CHANGE, SERVICE_NO_CHANGE,SERVICE_NO_CHANGE, desc.lpDescription, nil, nil, nil, nil, nil, nil);
    finally
      CloseServiceHandle(Svc);
    end;
  finally
    CloseServiceHandle(SvcMgr);
  end;
end;

procedure TNeoXploraService.ServiceBeforeInstall(Sender: TService);
begin
  ServiceLoadInfo(Sender);
end;

procedure TNeoXploraService.ServiceCreate(Sender: TObject);
begin
  ServiceLoadInfo(Sender);
end;

procedure TNeoXploraService.ServiceLoadInfo(Sender : TObject);// new method, not an override
var
  TheFirstParam: Integer;
begin
  if System.ParamCount > 1 then
    if SameText(System.ParamStr(1), '/install') then
      TheFirstParam := 2
    else
      TheFirstParam := 1
  else
    TheFirstParam := 2;
  if System.ParamCount >= TheFirstParam then
    Name := System.ParamStr(TheFirstParam);
  if System.ParamCount = TheFirstParam + 1 then
    DisplayName := System.ParamStr(TheFirstParam + 1)
  else
    DisplayName := Name + ' software by Sky Project';
end;

procedure TNeoXploraService.ServiceStart(Sender: TService; var Started: Boolean);
begin
  CoInitialize(nil);
  try
    FServerStartOk := False;
    TServiceThread.GetInstance.Suspended := False;
    Started := FServerStartOk;
  finally
    CoUnInitialize;
  end;
end;

procedure TNeoXploraService.ServiceStop(Sender: TService;
  var Stopped: Boolean);
begin
  TServiceThread.ForceClose;
  Stopped := True;
end;

procedure TNeoXploraService.StartFinished(SuccessFull: Boolean);
begin
  FServerStartOk := SuccessFull;
end;

procedure TNeoXploraService.ServiceShutdown(Sender: TService);
begin
  TServiceThread.ForceClose;
end;

end.
