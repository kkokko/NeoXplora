unit MySQLImport;

{$mode objfpc}{$H+}

interface

uses
  Classes, SysUtils, FileUtil, Forms, Controls, Graphics, Dialogs, ComCtrls, StdCtrls,
  sqlite3conn, sqldb, ServerInterface, RequestGetImportInfo;

type

  { TMySQLImportForm }

  TMySQLImportForm = class(TForm)
    btnImport: TButton;
    Button1: TButton;
    btnConnect: TButton;
    Button2: TButton;
    btnIIBack: TButton;
    edtHost: TEdit;
    edtUser: TEdit;
    edtPassword: TEdit;
    Label1: TLabel;
    lblInProgress: TLabel;
    Label11: TLabel;
    lblNew: TLabel;
    Label2: TLabel;
    Label3: TLabel;
    Label4: TLabel;
    Label6: TLabel;
    Label7: TLabel;
    Label8: TLabel;
    lblTotalStories: TLabel;
    pcMain: TPageControl;
    tabConInfo: TTabSheet;
    tabImportInfo: TTabSheet;
    procedure btnIIBackClick(Sender: TObject);
    procedure btnImportClick(Sender: TObject);
    procedure Button1Click(Sender: TObject);
    procedure btnConnectClick(Sender: TObject);
    procedure FormDestroy(Sender: TObject);
  private
    FServer: TServerInterface;
    FImportFinished: Boolean;
    FLiteQuery: TSQLQuery;
    FSQLTransaction: TSQLTransaction;
    FTransaction: TSQLTransaction;
    FResponseInfo: TResponseGetImportInfo;
    procedure SetResponseInfo(AValue: TResponseGetImportInfo);
    function LoadImportInfo: Boolean;
    procedure SetLiteDB(AValue: TSQLite3Connection);
    property ResponseInfo: TResponseGetImportInfo read FResponseInfo write SetResponseInfo;
  public
    constructor Create(AnOwner: TComponent); override;

    property ImportFinished: Boolean read FImportFinished;
    property Transaction: TSQLTransaction write FTransaction;
    property LiteDB: TSQLite3Connection write SetLiteDB;
  end;

var
  MySQLImportForm: TMySQLImportForm;

implementation

{$R *.lfm}

uses
  CoreUnit, RequestGetStoriesForImport, StoryObject, SentenceObject, QAObject, StoryIdObject;

{ TMySQLImportForm }

procedure TMySQLImportForm.Button1Click(Sender: TObject);
begin
  Close;
end;

procedure TMySQLImportForm.btnIIBackClick(Sender: TObject);
begin
  pcMain.ActivePage := tabConInfo;
end;

procedure TMySQLImportForm.btnImportClick(Sender: TObject);
var
  TheRequest: TRequestGetStoriesForImport;
  TheResponse: TResponseGetStoriesForImport;
  TheStory: TStoryObject;
  TheId: Integer;
  TheIds: string;
  TheCount: Integer;
  TheIndex: Integer;
  I: Integer;
begin
  TheResponse := nil;
  TheRequest := TRequestGetStoriesForImport.Create;
  try
    while FResponseInfo.IdList.Count > 0 do
    begin
      TheIndex := FResponseInfo.IdList.Count - 1;
      TheCount := 0;
      TheIds := '';
      while (TheCount < 30) and (TheIndex >=0) do
      begin
        if TheCount <> 0 then
          TheIds := TheIds + ',';
        TheId := (FResponseInfo.IdList[TheIndex] as TStoryIdObject).MySQLId;
        TheIds := TheIds + IntToStr(TheId);
        Dec(TheIndex);
        Inc(TheCount);
      end;
      FResponseInfo.IdList.Count := TheIndex + 1;
      TheRequest.SpecifiedIds := TheIds;
      TheResponse := FServer.GetStoriesForImport(TheRequest);
      if TheResponse = nil then
        Exit;

      if (TheResponse.Stories.Count = 0) or (TheResponse.Stories.Count > TheCount) then
      begin
        MessageDlg(Format('Received story count(%d) different then expected(%s)',
          [TheResponse.Stories.Count, IntToStr(TheCount)]), mtError, [mbOK], -1);
        Exit;
      end;

      for I := 0 to TheResponse.Stories.Count - 1 do
      begin
        TheStory := TheResponse.Stories[I] as TStoryObject;
        TheStory.MySqlId := TheStory.Id;
        if TheStory.CanOverwrite then
          TheStory.Id := Core.StoryGetIdByTitle(TheStory.Title)
        else
          TheStory.Id := 0;
        TheStory.UpdateProtoObjects;
        Core.StoryUpdateEverything(TheStory);
      end;
    end;
  finally
    TheResponse.Free;
    TheRequest.Free;
  end;
  FTransaction.CommitRetaining;
  ShowMessage('Import finished');
  FImportFinished := True;
  Close;
end;

procedure TMySQLImportForm.btnConnectClick(Sender: TObject);
begin
  FServer.UserName := edtUser.Text;
  FServer.Password := edtPassword.Text;
  FServer.ServerAddress := edtHost.Text;
  if LoadImportInfo then
    pcMain.ActivePage := tabImportInfo;
end;

procedure TMySQLImportForm.FormDestroy(Sender: TObject);
begin
  FResponseInfo.Free;
end;

procedure TMySQLImportForm.SetResponseInfo(AValue: TResponseGetImportInfo);
begin
  if FResponseInfo=AValue then
    Exit;
  FResponseInfo.Free;
  FResponseInfo := AValue;
end;

function TMySQLImportForm.LoadImportInfo: Boolean;
var
  TheImportIds: string;
  TheRequest: TRequestGetImportInfo;
  TheResponse: TResponseGetImportInfo;
begin
  Result := False;
  FLiteQuery.SQL.Clear;
  FLiteQuery.SQL.Add('select cast(ifnull(group_concat(ImportId, '',''), '''') as blob) ImportIds ' +
  ' from storyTBL where ifnull(ImportId, 0) <> 0;');
  FLiteQuery.Open;
  try
    if FLiteQuery.EOF then
      Exit;
    TheImportIds := FLiteQuery.FieldByName('ImportIds').AsString;
  finally
    FLiteQuery.Close;
  end;

  TheRequest := TRequestGetImportInfo.Create;
  try
    TheRequest.SendIdList := True;
    TheRequest.ImportIds := TheImportIds;
    ResponseInfo := FServer.GetImportInfo(TheRequest);
    if ResponseInfo = nil then
      Exit;
    lblTotalStories.Caption := IntToStr(ResponseInfo.StoryTotal);
    lblInProgress.Caption := IntToStr(ResponseInfo.StoryInProgress);
    lblNew.Caption := IntToStr(ResponseInfo.StoryNew);
    Result := True;
  finally
    TheRequest.Free;
  end;
end;

procedure TMySQLImportForm.SetLiteDB(AValue: TSQLite3Connection);
begin
  FLiteQuery.DataBase := AValue;
end;

constructor TMySQLImportForm.Create(AnOwner: TComponent);
begin
  inherited Create(AnOwner);
  FServer := TServerInterface.Create;
  FSQLTransaction := TSQLTransaction.Create(nil);
  pcMain.ActivePage := tabConInfo;
  FLiteQuery := TSQLQuery.Create(nil);
  FImportFinished := False;
end;

end.

