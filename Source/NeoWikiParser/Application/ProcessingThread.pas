unit ProcessingThread;

interface

uses
  Classes, WikiPage;

type
  TProcessingThread = class(TThread)
  private
    FWikiPage: TWikiPage;
  protected
    procedure Execute; override;
  public
    constructor Create; reintroduce;
    destructor Destroy; override;
  end;

implementation

uses
  Scheduler, SysUtils;

{ TProcessingThread }

constructor TProcessingThread.Create;
begin
  inherited Create(True);
  FreeOnTerminate := True;
  FWikiPage := TWikiPage.Create;
  Suspended := False;
end;

destructor TProcessingThread.Destroy;
begin
  FWikiPage.Free;
  inherited;
end;

procedure TProcessingThread.Execute;
var
  TheRecord: TScheduler.TParseRecord;
begin
  TScheduler.NotifyWorkerStarted;
  try
    repeat
      if TScheduler.ProcessingOver then
      begin
        Terminate;
        Exit;
      end;
      try
        TheRecord := TScheduler.ReadFromQueue;
        if TheRecord.Name <> '' then
          FWikiPage.LoadPageFromString(TheRecord.Name, TheRecord.Text)
        else
          Sleep(1);
      except // eat all exceptions
      end;
    until Terminated;
  finally
    TScheduler.NotifyWorkerFinished;
  end;
end;

end.
