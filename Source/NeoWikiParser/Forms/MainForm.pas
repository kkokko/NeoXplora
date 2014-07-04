unit MainForm;

interface

uses
  Classes, Controls, Forms, StdCtrls, ExtCtrls;

type
  TfrmMain = class(TForm)
    Label1: TLabel;
    Label2: TLabel;
    lblParsedMb: TLabel;
    lblReadPages: TLabel;
    Label3: TLabel;
    lblTotalMb: TLabel;
    Timer1: TTimer;
    Label4: TLabel;
    lblParsedSeconds: TLabel;
    btnStartStop: TButton;
    Label5: TLabel;
    lblFileName: TLabel;
    lblParsedPagesC: TLabel;
    lblParsedPages: TLabel;
    Label8: TLabel;
    lblInsertedPages: TLabel;
    Label6: TLabel;
    lblInserteQueuePages: TLabel;
    procedure FormCreate(Sender: TObject);
    procedure FormDestroy(Sender: TObject);
    procedure Timer1Timer(Sender: TObject);
    procedure btnStartStopClick(Sender: TObject);
  private
    FCount: Integer;
    const
      ConstGigabyte = 1024 * 1024 * 1024;
  public
    { Public declarations }
  end;

var
  frmMain: TfrmMain;

implementation

uses
  TypesFunctions, SysUtils, AppSettings, Graphics, Scheduler, Dialogs;

{$R *.dfm}

procedure TfrmMain.btnStartStopClick(Sender: TObject);
begin
  try
    btnStartStop.Enabled := False;
    try
      if btnStartStop.Caption = 'Start' then
      begin
        ShowMessage('Starting');
        FCount := 0;
        btnStartStop.Caption := 'Stop';
        TScheduler.GetInstance;
        Timer1.Enabled := True;
      end
      else
      begin
        ShowMessage('Stopping');
        btnStartStop.Caption := 'Start';
        TScheduler.EndInstance;
        Timer1.Enabled := False;
      end;
    finally
      btnStartStop.Enabled := True;
    end;
  except on e: Exception do
    ShowMessage(E.Message);
  end;
end;

procedure TfrmMain.FormCreate(Sender: TObject);
begin
  if not FileExists(Settings.WikiFile) then
  begin
    lblFileName.Caption := 'File does not exist: ' + Settings.WikiFile;
    lblFileName.Font.Color := clRed;
    btnStartStop.Enabled := False;
  end
  else
    lblFileName.Caption := Settings.WikiFile;
end;

procedure TfrmMain.FormDestroy(Sender: TObject);
begin
  TScheduler.EndInstance;
end;

procedure TfrmMain.Timer1Timer(Sender: TObject);
var
  TheInfo: TScheduler.TStatusInfo;
begin
  Timer1.Enabled := False;
  TheInfo := TScheduler.GetInstance.StatusInfo;
  lblTotalMb.Caption := FloatToStr(RoundDouble(TheInfo.BytesTotal / ConstGigabyte, 2));
  lblParsedMb.Caption := FloatToStr(RoundDouble(TheInfo.BytesParsed / ConstGigabyte, 2));
  lblReadPages.Caption := FloatToStr(TheInfo.PagesRead);
  lblParsedPages.Caption := FloatToStr(TheInfo.PagesParsed);
  lblInserteQueuePages.Caption := FloatToStr(TheInfo.PagesInsertQueue);
  lblInsertedPages.Caption := FloatToStr(TheInfo.PagesInserted);
  Inc(FCount);
  lblParsedSeconds.Caption := FloatToStr(FCount div 5);
  Timer1.Enabled := True;
end;

end.
