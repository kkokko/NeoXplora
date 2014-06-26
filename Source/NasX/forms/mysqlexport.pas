unit MySQLExport;

{$mode objfpc}{$H+}

interface

uses
  Classes, SysUtils, FileUtil, Forms, Controls, Graphics, Dialogs, ComCtrls, StdCtrls,
  sqlite3conn, sqldb, ServerInterface;

type

  { TMySQLExportForm }

  TMySQLExportForm = class(TForm)
    btnExport: TButton;
    Button1: TButton;
    btnConnect: TButton;
    Button2: TButton;
    btnIIBack: TButton;
    edtHost: TEdit;
    edtUser: TEdit;
    edtPassword: TEdit;
    Label1: TLabel;
    lblLocalCount: TLabel;
    lblServerCount: TLabel;
    Label2: TLabel;
    Label3: TLabel;
    Label4: TLabel;
    Label6: TLabel;
    Label7: TLabel;
    Label8: TLabel;
    pcMain: TPageControl;
    tabConInfo: TTabSheet;
    tabImportInfo: TTabSheet;
    procedure btnIIBackClick(Sender: TObject);
    procedure btnExportClick(Sender: TObject);
    procedure Button1Click(Sender: TObject);
    procedure btnConnectClick(Sender: TObject);
  private
    FServer: TServerInterface;
    FImportFinished: Boolean;
    FLiteQuery: TSQLQuery;
    FSQLTransaction: TSQLTransaction;
    FTransaction: TSQLTransaction;
    function LoadImportInfo: Boolean;
    procedure SetLiteDB(AValue: TSQLite3Connection);
  public
    constructor Create(AnOwner: TComponent); override;

    property ImportFinished: Boolean read FImportFinished;
    property Transaction: TSQLTransaction write FTransaction;
    property LiteDB: TSQLite3Connection write SetLiteDB;
  end;

var
  MySQLExportForm: TMySQLExportForm;

implementation

{$R *.lfm}

uses
  RequestGetImportInfo, RequestSetStoriesFromExport, StoryObject, SentenceObject,
  QAObject, StoryIdObject, CoreUnit;

{ TMySQLExportForm }

procedure TMySQLExportForm.Button1Click(Sender: TObject);
begin
  Close;
end;

procedure TMySQLExportForm.btnIIBackClick(Sender: TObject);
begin
  pcMain.ActivePage := tabConInfo;
end;

procedure TMySQLExportForm.btnExportClick(Sender: TObject);
var
  TheRequest: TRequestSetStoriesFromExport;
  TheResponse: TResponseSetStoriesFromExport;
  TheStoryId: TStoryIdObject;
  TheIndex: Integer;
  I: Integer;
begin
  TheRequest := TRequestSetStoriesFromExport.Create;
  try
    TheIndex := 0;
    repeat
      TheRequest.Stories.Clear;
      Core.StoryLoadAll(TheRequest.Stories, TheIndex, 20);
      TheIndex := TheIndex + 20;
      if TheRequest.Stories.Count = 0 then
        Break;
      TheResponse := FServer.SetStoriesFromExport(TheRequest);
      if TheResponse = nil then
        Exit;
      for I := 0 to TheResponse.StoryIds.Count - 1 do
      begin
        TheStoryId := TheResponse.StoryIds[I] as TStoryIdObject;
        Core.StoryUpdateImportId(TheStoryId.SQLiteId, TheStoryId.MySQLId);
      end;
    until 1 = 0;
  finally
    TheRequest.Free;
  end;

  FTransaction.CommitRetaining;
  ShowMessage(Format('Export finished: %d added %d modified.', [TheResponse.AddedCount, TheResponse.UpdatedCount]));
  FImportFinished := True;
  Close;
end;

procedure TMySQLExportForm.btnConnectClick(Sender: TObject);
begin
  FServer.UserName := edtUser.Text;
  FServer.Password := edtPassword.Text;
  FServer.ServerAddress := edtHost.Text;
  if LoadImportInfo then
    pcMain.ActivePage := tabImportInfo;
end;

function TMySQLExportForm.LoadImportInfo: Boolean;
var
  TheRequest: TRequestGetImportInfo;
  TheResponse: TResponseGetImportInfo;
begin
  Result := False;
  FLiteQuery.SQL.Clear;
  FLiteQuery.SQL.Add('select count(*) Number from storyTBL;');
  FLiteQuery.Open;
  try
    if FLiteQuery.EOF then
      Exit;
    lblLocalCount.Caption := IntToStr(FLiteQuery.FieldByName('Number').AsInteger);
  finally
    FLiteQuery.Close;
  end;

  TheResponse := nil;
  TheRequest := TRequestGetImportInfo.Create;
  try
    TheRequest.SendIdList := False;
    TheResponse := FServer.GetImportInfo(TheRequest);
    if TheResponse = nil then
      Exit;
    lblServerCount.Caption := IntToStr(TheResponse.StoryTotal);
    Result := True;
  finally
    TheRequest.Free;
    TheResponse.Free;
  end;
end;

procedure TMySQLExportForm.SetLiteDB(AValue: TSQLite3Connection);
begin
  FLiteQuery.DataBase := AValue;
end;

constructor TMySQLExportForm.Create(AnOwner: TComponent);
begin
  inherited Create(AnOwner);
  FServer := TServerInterface.Create;
  FSQLTransaction := TSQLTransaction.Create(nil);
  pcMain.ActivePage := tabConInfo;
  FLiteQuery := TSQLQuery.Create(nil);
  FImportFinished := False;
end;

end.

