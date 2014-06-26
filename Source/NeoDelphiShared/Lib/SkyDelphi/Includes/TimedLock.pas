unit TimedLock;

interface

uses
  SyncObjs;

type
  TTimedLock = class(TObject)
  private
    FTerminateEvent: TEvent;
    FLockInterval: Int64;
    FLockName: string;
  public
    constructor Create(const ALockName: string); reintroduce;
    destructor Destroy; override;

    function WaitForLock: Boolean;
    procedure BreakLock;

    property LockInterval: Int64 read FLockInterval write FLockInterval;
    property LockName: string read FLockName write FLockName;
  end;

implementation

uses
  SysUtils;

{ TTimedLock }

procedure TTimedLock.BreakLock;
begin
  FTerminateEvent.SetEvent;
end;

constructor TTimedLock.Create(const ALockName: string);
begin
  inherited Create;
  FLockName := ALockName;
  FTerminateEvent := TEvent.Create(nil, True, False, FLockName);
end;

destructor TTimedLock.Destroy;
begin
  FreeAndNil(FTerminateEvent);
  inherited;
end;

function TTimedLock.WaitForLock: Boolean;
begin
  FTerminateEvent.ResetEvent;
  Result := FTerminateEvent.WaitFor(LockInterval) = wrTimeout;
end;

end.
