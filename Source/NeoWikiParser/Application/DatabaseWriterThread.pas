unit DatabaseWriterThread;

interface

uses
  Classes;

type
  TDatabaseWriterThread = class(TThread)
  protected
    procedure Execute; override;
  public
    constructor Create; reintroduce;
    destructor Destroy; override;

    procedure ShutDown;
  end;

implementation

{ TDatabaseWriterThread }

constructor TDatabaseWriterThread.Create;
begin
  inherited Create(True);
  FreeOnTerminate := False;
end;

destructor TDatabaseWriterThread.Destroy;
begin

  inherited;
end;

procedure TDatabaseWriterThread.Execute;
begin
end;

procedure TDatabaseWriterThread.ShutDown;
begin

end;

end.
