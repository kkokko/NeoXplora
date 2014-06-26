unit DebugForm;

interface

uses
  Windows, SysUtils, Classes, Controls, Forms,
  Dialogs, StdCtrls, DB, MemDS, DBAccess, Uni;

type
  TfrmDebug = class(TForm)
    btnClose: TButton;
    btnRunScript: TButton;
    memoInputScript: TMemo;
    memoResult: TMemo;
    lblScript: TLabel;
    lblResult: TLabel;
    btnRecreateTables: TButton;
    procedure btnCloseClick(Sender: TObject);
    procedure FormShow(Sender: TObject);
    procedure FormDestroy(Sender: TObject);
    procedure btnRunScriptClick(Sender: TObject);
    procedure btnRecreateTablesClick(Sender: TObject);
  private
    { Private declarations }
  public
    { Public declarations }
  end;

var
  frmDebug: TfrmDebug;

implementation

{$R *.dfm}

uses
  ServiceThread, AppUnit, SkyHttpClient, Entity, ServerCore, DateUtils, TimedLock;

procedure TfrmDebug.btnCloseClick(Sender: TObject);
begin
  Close;
end;

procedure TfrmDebug.btnRecreateTablesClick(Sender: TObject);
begin
  if MessageDlg('This will drop and create the tables. Data might be lost. Continue ?', mtWarning, mbYesNo, 0) <> mrYes then
    Exit;
  App.RecreateTables;
end;

procedure TfrmDebug.btnRunScriptClick(Sender: TObject);
var
  TheHttpClient: TSkyHttpClient;
  TheLink: string;
  TheString: AnsiString;
  TheAnsiString: AnsiString;
  I: Integer;
  ThePost: Boolean;
  TheStart, TheEnd: TDateTime;
begin
  TheHttpClient := TSkyHttpClient.Create;
  try
    memoResult.Clear;
    try
      memoResult.Lines.Add('Connecting to localhost:' + IntToStr(App.Settings.ServicePort));
      I := 0;
      while I < memoInputScript.Lines.Count do
      begin
        TheLink := Trim(memoInputScript.Lines[I]);
        if (TheLink = '') or (TheLink[1] = '/') then
        begin
          Inc(I);
          Continue;
        end;
        TheHttpClient.SendStream.Clear;
        ThePost := TheLink[1] = '!';

        if ThePost then
        begin
          Delete(TheLink, 1, 1);
          repeat
            Inc(I);
            TheString := AnsiString(Trim(memoInputScript.Lines[I]));
            if (TheString = '') or (TheString[1] <> '!') then
              Break;
            TheHttpClient.SendStream.Write(TheString[2], Length(TheString) - 1);
          until (I = memoInputScript.Lines.Count);
        end;

        TheLink := 'http://127.0.0.1:' + IntToStr(App.Settings.ServicePort) + '/' + TheLink;
        memoResult.Lines.Add('');
        memoResult.Lines.Add('----------------- Read: ' + TheLink);
        TheStart := Now;
        if ThePost then
          TheHttpClient.PostStream(TheLink)
        else
          TheHttpClient.GetStream(TheLink);
        TheEnd := Now;
        TheHttpClient.ReceivedStream.Position := 0;
        if TheHttpClient.ReceivedStream.Size = 0 then
          TheAnsiString := ''
        else
        begin
          SetLength(TheAnsiString, TheHttpClient.ReceivedStream.Size);
          CopyMemory(@TheAnsiString[1], TheHttpClient.ReceivedStream.Memory, TheHttpClient.ReceivedStream.Size);
        end;
        memoResult.Lines.Add('Time: ' + IntToStr(MilliSecondsBetween(TheEnd, TheStart)));
        memoResult.Lines.Add('Response code: ' + IntToStr(TheHttpClient.LastResponseCode));
        memoResult.Lines.Add(string(TheAnsiString));
        memoResult.Lines.Add('-------------------- ');
        memoResult.Lines.Add('');
        if TheHttpClient.LastResponseCode = 240 then
          Exit;
        Inc(I);
      end;
      TheHttpClient.DisConnect;
    except on E: exception do
      memoResult.Lines.Add('Error: ' + e.Message);
    end;
  finally
    FreeAndNil(TheHttpClient);
  end;
end;

procedure TfrmDebug.FormDestroy(Sender: TObject);
begin
  TServiceThread.GetInstance.Free;
end;

procedure TfrmDebug.FormShow(Sender: TObject);
begin
  TServiceThread.GetInstance.Run;
end;

end.
