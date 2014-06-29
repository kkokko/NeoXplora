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
    procedure ShutDown;
  end;

implementation

uses
  ParseResult, Scheduler, SysUtils, LoggerUnit;

{ TDatabaseWriterThread }

constructor TDatabaseWriterThread.Create;
begin
  inherited Create(True);
  FreeOnTerminate := False;
  Suspended := False;
end;

procedure TDatabaseWriterThread.Execute;
var
  TheRecord: TParseResult;
begin
  repeat
    if TScheduler.ProcessingOver then
    begin
      Terminate;
      Exit;
    end;
    try
      TheRecord := TScheduler.ReadFromInsertQueue;
      if TheRecord <> nil then
        TheRecord.SaveToDatabase
      else
        Sleep(10);
    except on E: Exception do // eat all exceptions
      TLogger.Error(Self, E);
    end;
  until Terminated;
end;

procedure TDatabaseWriterThread.ShutDown;
begin

end;

end.
