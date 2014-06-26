unit ServMain;

interface

uses
  Windows, Classes, SvcMgr, ServiceThread, SyncObjs;

type
  TNasService = class(TService)
    procedure ServiceStop(Sender: TService; var Stopped: Boolean);
    procedure ServiceShutdown(Sender: TService);
    procedure ServiceStart(Sender: TService; var Started: Boolean);
  private
    FServiceThread: TServiceThread;
    FServerStartOk: Boolean;
    FTerminateEvent: TEvent;
  public
    destructor Destroy; override;
    function GetServiceController: TServiceController; override;
    procedure StartFinished(SuccessFull: Boolean);
  end;

var
  NasService: TNasService;

procedure ServiceController(CtrlCode: DWord); stdcall;

implementation

{$R *.DFM}

uses
  SysUtils;

procedure ServiceController(CtrlCode: DWord); stdcall;
begin
  NasService.Controller(CtrlCode);
end;

destructor TNasService.Destroy;
begin
  FreeAndNil(FServiceThread);
  FreeAndNil(FTerminateEvent);
  inherited;
end;

function TNasService.GetServiceController: TServiceController;
begin
  Result := ServiceController;
end;

procedure TNasService.ServiceStart(Sender: TService; var Started: Boolean);
begin
  FServerStartOk := False;
  FTerminateEvent := TEvent.Create(nil, True, False, 'InfoServer3LoadedMessage');
  TServiceThread.GetInstance.Suspended := False;
  FTerminateEvent.WaitFor(INFINITE);
  Started := FServerStartOk;
end;

procedure TNasService.ServiceStop(Sender: TService;
  var Stopped: Boolean);
begin
  TServiceThread.ForceClose;
  Stopped := True;
end;

procedure TNasService.StartFinished(SuccessFull: Boolean);
begin
  FServerStartOk := SuccessFull;
  FTerminateEvent.SetEvent;
end;

procedure TNasService.ServiceShutdown(Sender: TService);
begin
  TServiceThread.ForceClose;
end;

end.
