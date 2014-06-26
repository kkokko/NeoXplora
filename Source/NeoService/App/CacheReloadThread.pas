unit CacheReloadThread;

interface

uses
  Classes, TimedLock;

type
  TCacheReloadThread = class(TThread)
  private
    class var
      FInstance: TCacheReloadThread;
  private
    FTimedLock: TTimedLock;
  protected
    procedure Execute; override;
  public
    constructor Create; reintroduce;
    destructor Destroy; override;

    class function GetInstance: TCacheReloadThread;
    class procedure EndInstance;

    property TimedLock: TTimedLock read FTimedLock write FTimedLock;
  end;

implementation

uses
  Windows, ServerCore, AppUnit;

{ TCacheReloadThread }

constructor TCacheReloadThread.Create;
begin
  inherited Create(True);
  FreeOnTerminate := True;
  FTimedLock := TTimedLock.Create('PlanningServer.TMailerThread');
  FTimedLock.LockInterval := 3000; // 5 minutes
  Suspended := False;
end;

destructor TCacheReloadThread.Destroy;
begin
  FTimedLock.Free;
  inherited;
end;

class procedure TCacheReloadThread.EndInstance;
var
  TheHandle: Cardinal;
begin
  if Assigned(FInstance) then
  begin
    TheHandle := FInstance.Handle;
    FInstance.Terminate;
    FInstance.TimedLock.BreakLock;
    WaitForSingleObject(TheHandle, INFINITE);
  end;
end;

procedure TCacheReloadThread.Execute;
begin
  App.CreateDefaultDatabaseConnection;
  try
    repeat
      try
        if TimedLock.WaitForLock then // sleep 5 mins
          Core.CacheReload;
      except // eat all exceptions
      end;
    until Terminated;
  finally
    App.RemoveDefaultDatabaseConnection;
  end;
end;

class function TCacheReloadThread.GetInstance: TCacheReloadThread;
begin
  if not Assigned(FInstance) then
    FInstance := TCacheReloadThread.Create;
  Result := FInstance;
end;

end.
