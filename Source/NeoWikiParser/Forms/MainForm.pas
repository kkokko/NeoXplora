unit MainForm;

interface

uses
  Classes, Controls, Forms, StdCtrls, ComCtrls, Buttons, ExtCtrls;

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
  TypesFunctions, SysUtils, AppSettings, Graphics, Scheduler;

{$R *.dfm}

procedure TfrmMain.btnStartStopClick(Sender: TObject);
begin
  btnStartStop.Enabled := False;
  try
    if btnStartStop.Caption = 'Start' then
    begin
      FCount := 0;
      btnStartStop.Caption := 'Stop';
      TScheduler.GetInstance;
      Timer1.Enabled := True;
    end
    else
    begin
      btnStartStop.Caption := 'Start';
      TScheduler.EndInstance;
      Timer1.Enabled := False;
    end;
  finally
    btnStartStop.Enabled := True;
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
//  ThePage := TWikiPage.Create('Anarchy');
//  try
//    Memo1.Lines.LoadFromFile('C:\Work\PaulKp\Anarchism\Anarchism.txt');
//    Memo1.Lines.Text := ThePage.LoadPageFromString(Memo1.Lines.Text);
//    Memo2.Lines.Text := ThePage.Results.Tags;
//    Memo5.Lines.Text := ThePage.Results.InternalLinks;
//    Memo6.Lines.Text := ThePage.Results.Templates;
//  finally
//    ThePage.Free;
//  end;
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
  lblInsertedPages.Caption := FloatToStr(TheInfo.PagesInserted);
  Inc(FCount);
  lblParsedSeconds.Caption := FloatToStr(FCount div 5);
  Timer1.Enabled := True;
end;

end.
