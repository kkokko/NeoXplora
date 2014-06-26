unit EditStory;

{$mode objfpc}{$H+}

interface

uses
  Classes, SysUtils, FileUtil, Forms, Controls, Graphics, Dialogs, StdCtrls,
  DbCtrls, ComCtrls, ExtCtrls, StoryObject, ProtoContainer;

type
  { TEditStoryForm }

  TEditStoryForm = class(TForm)
    btnSave: TButton;
    btnCancel: TButton;
    btnSplitAll: TButton;
    edtStoryTitle: TEdit;
    edtStoryTitleRO: TEdit;
    Label1: TLabel;
    Label2: TLabel;
    memoStory: TMemo;
    PageControl1: TPageControl;
    Panel1: TPanel;
    Panel2: TPanel;
    Panel3: TPanel;
    ScrollBox1: TScrollBox;
    tabStory: TTabSheet;
    tabSentences: TTabSheet;
    procedure btnCancelClick(Sender: TObject);
    procedure btnSaveClick(Sender: TObject);
    procedure btnSplitAllClick(Sender: TObject);
    procedure FormShow(Sender: TObject);
    procedure PageControl1Changing(Sender: TObject; var AllowChange: Boolean);
  private
    FExecuteOk: Boolean;
    FInitialized: Boolean;
    FInitialStory: TStoryObject;
    FStory: TStoryObject;
    FStoryBodyChanged: Boolean;
    FStoryTitleChanged: Boolean;
    FStoryInitialBodyChanged: Boolean;
    FStoryInitialTitleChanged: Boolean;
    FProtoContainer: TProtoContainer;

    procedure CheckStoryChanged;
    procedure RecreateFormControls;
    procedure SetStory(AValue: TStoryObject);
    procedure UpdateStoryFields;
    function ValidateStoryFields: Boolean;
  public
    constructor Create(AnOwner: TComponent); override;
    destructor Destroy; override;

    class function ExecuteAdd: Boolean;
    class function ExecuteModify(AStory: TStoryObject): Boolean;

    property Story: TStoryObject read FStory write SetStory;
  end;

var
  EditStoryForm: TEditStoryForm;

implementation

uses
  CoreUnit, SentenceObject, PosTagger;

{$R *.lfm}

{ TEditStoryForm }

procedure TEditStoryForm.btnCancelClick(Sender: TObject);
begin
  Close;
end;

procedure TEditStoryForm.btnSaveClick(Sender: TObject);
var
  TheSentence: TSentenceObject;
  TheTagger: TPosTagger;
  I: Integer;
begin
  if not ValidateStoryFields then
    Exit;
  CheckStoryChanged;
  // if the proto-sentences were not re-generated do that now
  if FStoryBodyChanged then
  begin
    PageControl1.ActivePage := tabSentences;
    Exit;
  end;
  FStoryInitialBodyChanged := FStoryInitialBodyChanged or (FStory.Id = 0);

  UpdateStoryFields;
  if not (FStoryInitialBodyChanged or FStoryInitialTitleChanged) then
  begin
    ShowMessage('Nothing was changed');
    Close;
    Exit;
  end;
  if not FStoryInitialBodyChanged then
  begin
    Core.StoryUpdate(FStory);
    FInitialStory.Title := FStory.Title;
  end
  else begin
    FStory.UpdateBodyFromProto;
    TheTagger := TPosTagger.Create;
    try
      for I := 0 to FStory.Sentences.Count - 1 do
      begin
        TheSentence := FStory.Sentences[I] as TSentenceObject;
        TheSentence.POS := TheTagger.DoGetTagsForString(TheSentence.Name);
      end;
    finally
      TheTagger.Free;
    end;
    Core.StoryUpdateEverything(FStory);
    if FInitialStory <> nil then
      FInitialStory.CopyFrom(FStory);
  end;
  Core.Database.Transaction.CommitRetaining;
  ShowMessage('Data saved');
  FExecuteOk := True;
  Close;
end;

procedure TEditStoryForm.btnSplitAllClick(Sender: TObject);
begin
  FProtoContainer.SplitAll;
  FStoryInitialBodyChanged := True;
  RecreateFormControls;
end;

procedure TEditStoryForm.FormShow(Sender: TObject);
begin
  FInitialized := True;
end;

procedure TEditStoryForm.PageControl1Changing(Sender: TObject;
  var AllowChange: Boolean);
begin
  if not FInitialized then
    Exit;
  AllowChange := False;
  if PageControl1.PageIndex = 0 then
  begin
    if not ValidateStoryFields then
      Exit;
    CheckStoryChanged;
    UpdateStoryFields;
    if FStoryBodyChanged then
      RecreateFormControls;
    edtStoryTitleRO.Text := FStory.Title;
  end else
  begin
    FStory.UpdateBodyFromProto;
    memoStory.Lines.Text := FStory.Body;
  end;
  AllowChange := True;
end;

procedure TEditStoryForm.CheckStoryChanged;
begin
  FStoryBodyChanged := FStory.Body <> Trim(memoStory.Lines.Text);
  FStoryTitleChanged := FStory.Title <> Trim(edtStoryTitle.Text);
  FStoryInitialBodyChanged := FStoryInitialBodyChanged or FStoryBodyChanged;
  FStoryInitialTitleChanged := FStoryInitialTitleChanged or FStoryTitleChanged;
end;

procedure TEditStoryForm.RecreateFormControls;
var
  TheScrollBarPosition: Integer;
begin
  TheScrollBarPosition := ScrollBox1.VertScrollBar.Position;
  try
    FProtoContainer.ReCreateProtoPanels;
  finally
    ScrollBox1.VertScrollBar.Position := TheScrollBarPosition;
  end;
end;

function TEditStoryForm.ValidateStoryFields: Boolean;
begin
  Result := False;
  if Trim(edtStoryTitle.Text) = '' then
  begin
    MessageDlg('Story title should not be empty', mtError, [mbOK], -1);
    Exit;
  end;
  if Trim(memoStory.Lines.Text) = '' then
  begin
    MessageDlg('Story body should not be empty', mtError, [mbOK], -1);
    Exit;
  end;
  if Core.StoryCheckTitleExists(Trim(edtStoryTitle.Text), FStory.Id) then
  begin
    MessageDlg('Story title already exists', mtError, [mbOK], -1);
    Exit;
  end;
  Result := True;
end;

procedure TEditStoryForm.SetStory(AValue: TStoryObject);
begin
  FStory.Free;
  FInitialStory := AValue;
  if AValue <> nil then
    FStory := AValue.CreateACopy as TStoryObject
  else
    FStory := TStoryObject.Create;
  edtStoryTitle.Caption := FStory.Title;
  memoStory.Lines.Text := FStory.Body;
  FProtoContainer := TProtoContainer.Create(ScrollBox1, FStory);
  if AValue <> nil then
  begin
    FStory.UpdateProtoObjects;
    RecreateFormControls;
  end;
end;

procedure TEditStoryForm.UpdateStoryFields;
begin
  if FStoryTitleChanged then
    FStory.Title := Trim(edtStoryTitle.Text);
  if FStoryBodyChanged then
  begin
    FStory.Body := Trim(memoStory.Lines.Text);
    FProtoContainer.UpdateProtoFromBody;
  end;
end;

constructor TEditStoryForm.Create(AnOwner: TComponent);
begin
  inherited Create(AnOwner);
  FProtoContainer := TProtoContainer.Create(ScrollBox1, FStory);
  FInitialized := False;
  PageControl1.ActivePageIndex := 0;
  FStoryInitialBodyChanged := False;
  FStoryInitialTitleChanged := False;
end;

destructor TEditStoryForm.Destroy;
begin
  FStory.Free;
  FProtoContainer.Free;
  inherited Destroy;
end;

class function TEditStoryForm.ExecuteAdd: Boolean;
var
  TheInstance: TEditStoryForm;
begin
  TheInstance := TEditStoryForm.Create(nil);
  try
    TheInstance.FExecuteOk := False;
    TheInstance.Story := nil;
    TheInstance.ShowModal;
    Result := TheInstance.FExecuteOk;
  finally
    TheInstance.Free;
  end;
end;

class function TEditStoryForm.ExecuteModify(AStory: TStoryObject): Boolean;
var
  TheInstance: TEditStoryForm;
begin
  TheInstance := TEditStoryForm.Create(nil);
  try
    TheInstance.FExecuteOk := False;
    TheInstance.Story := AStory;
    TheInstance.ShowModal;
    Result := TheInstance.FExecuteOk;
  finally
    TheInstance.Free;
  end;
end;

end.

