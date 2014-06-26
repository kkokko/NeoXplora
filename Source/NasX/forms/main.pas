unit Main;

{$mode objfpc}{$H+}

interface

uses
  Classes, SysUtils, FileUtil, TAGraph, TASources, TASeries, TARadialSeries,
  Forms, Controls, Graphics, Dialogs, StdCtrls, DBGrids, DBCtrls, ComCtrls,
  ExtCtrls, Buttons, uEditPanel, sqlite3conn, sqldb, DB, contnrs, NASTypes,
  regexpr, CRep, types, Grids, TACustomSeries, CRepDecoder, SkyLists,
  CoreUnit, StoryObject;

type

  { TMainForm }

  TMainForm = class(TForm)
    btAddStory: TButton;
    btDelStory: TButton;
    btnCopyGSReps: TButton;
    btnExportToMySQL: TButton;
    btnLess: TButton;
    btnForward: TButton;
    btnCopyGCReps: TButton;
    btnCopyGReps: TButton;
    btnDefault: TButton;
    btnForward3: TButton;
    btnMore: TButton;
    btnPost: TBitBtn;
    btnPost1: TBitBtn;
    btnBack: TButton;
    btnPredictAnsw: TButton;
    btnPredictAllReps: TButton;
    btnSearch: TButton;
    btnTestGenerateGRep: TButton;
    btnTestGenerateGRep1: TButton;
    btnTestResetCrep: TButton;
    btnPredictReps: TButton;
    btnCopyReps: TButton;
    btnPredictCReps: TButton;
    btnTestPos: TButton;
    btnTestHPos: TButton;
    btnTestHSem: TButton;
    Button1: TButton;
    Button2: TButton;
    Button3: TButton;
    Button4: TButton;
    btnEdit: TButton;
    btnTestText: TButton;
    btnTestAddCrep: TButton;
    Button5: TButton;
    btnTestQA: TButton;
    btnImportMySQL: TButton;
    btnUpdateAllPOS: TButton;
    Chart1: TChart;
    Chart1LineSeries1: TLineSeries;
    Chart1LineSeries2: TLineSeries;
    Chart1LineSeries3: TLineSeries;
    Chart1LineSeries4: TLineSeries;
    chkChatUseQARules: TCheckBox;
    chkPredictRepTurbo: TCheckBox;
    chkPredictAllRepsTurbo: TCheckBox;
    chkTestClearResults: TCheckBox;
    Datasource1: TDatasource;
    Datasource2: TDatasource;
    DBGrid4: TDBGrid;
    edtStoryNumber: TEdit;
    edtSAccuracy1: TDBEdit;
    edtSAccuracy2: TDBEdit;
    edtSAccuracy3: TDBEdit;
    edtAccuracy4: TDBEdit;
    edtSAccuracy4: TDBEdit;
    edtTestQaRep: TEdit;
    edtTestQaQuestion: TEdit;
    edtSearch: TEdit;
    Label25: TLabel;
    Label26: TLabel;
    Label27: TLabel;
    Label28: TLabel;
    lblSentenceSRep: TLabel;
    lbRecIndicator1: TLabel;
    memoSummary: TMemo;
    memoSrepDecoded: TMemo;
    Panel6: TPanel;
    RepTabQuerysemantic_rep: TStringField;
    RepTabQuerysrepguess1: TStringField;
    RepTabQuerysrepguess2: TStringField;
    RepTabQuerysrepguess3: TStringField;
    RepTabQuerysrepguess4: TStringField;
    SearchSentenceDS: TDataSource;
    DatasourceLog: TDatasource;
    DBGrid3: TDBGrid;
    DBGridLog: TDBGrid;
    edtStoryAccuracy1: TEdit;
    edtStoryAccuracy2: TEdit;
    edtAccuracy5: TDBEdit;
    edtChatMsg: TEdit;
    edtStoryAccuracy3: TEdit;
    edtStoryAccuracy4: TEdit;
    edtTestCrepRep: TEdit;
    edtTabPOSpos: TEdit;
    edtTestRepRepresentation: TEdit;
    edtTestRepPOS: TEdit;
    edtTestRepSentence: TEdit;
    edtTestRep1: TEdit;
    edtRepGuess4: TEdit;
    edtGuess1: TEdit;
    edtRepGuess1: TEdit;
    edtGuess2: TEdit;
    edtRepGuess2: TEdit;
    edtGuess3: TEdit;
    edtRepGuess3: TEdit;
    edtGuess4: TEdit;
    edtTabPOSSentence: TEdit;
    edtTestWs1: TEdit;
    edtTestWs2: TEdit;
    edtStoryTitle: TDBEdit;
    DBGrid1: TDBGrid;
    DBGrid2: TDBGrid;
    DBNavigator1: TDBNavigator;
    DBNavigator2: TDBNavigator;
    DBNavigator3: TDBNavigator;
    edtAccuracy1: TDBEdit;
    edtAccuracy2: TDBEdit;
    edtAccuracy3: TDBEdit;
    edtTestPos1: TEdit;
    edtTestPos2: TEdit;
    edtTestRep2: TEdit;
    InsertSentenceQuery: TSQLQuery;
    Label1: TLabel;
    Label10: TLabel;
    Label11: TLabel;
    Label12: TLabel;
    Label13: TLabel;
    Label14: TLabel;
    Label15: TLabel;
    Label16: TLabel;
    Label17: TLabel;
    Label18: TLabel;
    Label19: TLabel;
    Label20: TLabel;
    Label21: TLabel;
    Label22: TLabel;
    Label23: TLabel;
    Label24: TLabel;
    lblPOS1: TLabel;
    lblPOS2: TLabel;
    lblPOS3: TLabel;
    lblPOS4: TLabel;
    lblPOS5: TLabel;
    lblRepresentation: TLabel;
    Label2: TLabel;
    Label3: TLabel;
    Label4: TLabel;
    Label5: TLabel;
    Label6: TLabel;
    Label7: TLabel;
    Label8: TLabel;
    Label9: TLabel;
    lblQAAnswer: TLabel;
    lblQAQuestion: TLabel;
    lblQARule: TLabel;
    lblPOS: TLabel;
    lbRecIndicator: TLabel;
    lblSentenceInd: TLabel;
    lblSentenceRep: TLabel;
    lblSentenceCRep: TLabel;
    ChartSrcWrd: TListChartSource;
    ChartSrcPos: TListChartSource;
    ChartSrcWP: TListChartSource;
    ChartSrcWSEMP: TListChartSource;
    Memo1: TMemo;
    memoCrepDecoded: TMemo;
    memoChat: TMemo;
    memoTestWs: TMemo;
    memoStory: TDBMemo;
    memoCRepDestination: TMemo;
    PageControl1: TPageControl;
    Panel1: TPanel;
    Panel2: TPanel;
    Panel3: TPanel;
    Panel4: TPanel;
    Panel5: TPanel;
    scrollPredictAll: TProgressBar;
    QATabQuery: TSQLQuery;
    QATabQueryanswer: TStringField;
    QATabQueryguessans1: TStringField;
    QATabQueryguessans2: TStringField;
    QATabQueryguessans3: TStringField;
    QATabQueryguessans4: TStringField;
    QATabQueryguessrule1: TStringField;
    QATabQueryguessrule2: TStringField;
    QATabQueryguessrule3: TStringField;
    QATabQueryguessrule4: TStringField;
    QATabQueryqarule: TStringField;
    QATabQueryquestion: TStringField;
    QATabQueryquestionID: TLongintField;
    QATabQuerystoryID: TLongintField;
    RepTabQuerycontext_rep: TStringField;
    RepTabQueryCRcomment: TStringField;
    RepTabQuerycrepguess1: TStringField;
    RepTabQuerycrepguess2: TStringField;
    RepTabQuerycrepguess3: TStringField;
    RepTabQuerycrepguess4: TStringField;
    RepTabQueryguesses1: TStringField;
    RepTabQueryguesses2: TStringField;
    RepTabQueryguesses3: TStringField;
    RepTabQueryguesses4: TStringField;
    RepTabQueryPOS: TStringField;
    RepTabQueryrepguess1: TStringField;
    RepTabQueryrepguess2: TStringField;
    RepTabQueryrepguess3: TStringField;
    RepTabQueryrepguess4: TStringField;
    RepTabQueryrepresentation: TStringField;
    RepTabQueryrep_baseguess: TStringField;
    RepTabQuerysentence: TStringField;
    RepTabQuerysentenceID: TLongintField;
    RepTabQuerysntid1: TStringField;
    RepTabQuerysntid2: TStringField;
    RepTabQuerysntid3: TStringField;
    RepTabQuerysntid4: TStringField;
    RepTabQuerystoryID: TLongintField;
    RepTabQuerytick1: TStringField;
    RepTabQuerytick2: TStringField;
    RepTabQuerytick3: TStringField;
    RepTabQuerytick4: TStringField;
    ScrollBox1: TScrollBox;
    ScrollBoxQA: TScrollBox;
    SQLite3Connection1: TSQLite3Connection;
    SQLQuery1: TSQLQuery;
    SQLQuery1body: TStringField;
    SQLQuery1CRepAcc_100: TBCDField;
    SQLQuery1RepAcc1_100: TBCDField;
    SQLQuery1RepAcc2_100: TBCDField;
    SQLQuery1RepAcc3_100: TBCDField;
    SQLQuery1RepAcc4_100: TBCDField;
    SQLQuery1SRepAcc1_100: TBCDField;
    SQLQuery1SRepAcc2_100: TBCDField;
    SQLQuery1SRepAcc3_100: TBCDField;
    SQLQuery1SRepAcc4_100: TBCDField;
    SQLQuery1storyID: TLongintField;
    SQLQuery1title: TStringField;
    SQLQuery2: TSQLQuery;
    SQLQuery2context_rep: TStringField;
    SQLQuery2CRcomment: TStringField;
    SQLQuery2guesses1: TStringField;
    SQLQuery2guesses2: TStringField;
    SQLQuery2guesses3: TStringField;
    SQLQuery2guesses4: TStringField;
    SQLQuery2POS: TStringField;
    SQLQuery2repguess1: TStringField;
    SQLQuery2repguess2: TStringField;
    SQLQuery2repguess3: TStringField;
    SQLQuery2repguess4: TStringField;
    SQLQuery2representation: TStringField;
    SQLQuery2rep_baseguess: TStringField;
    SQLQuery2sentence: TStringField;
    SQLQuery2sentenceID: TLongintField;
    SQLQuery2sntid1: TStringField;
    SQLQuery2sntid2: TStringField;
    SQLQuery2sntid3: TStringField;
    SQLQuery2sntid4: TStringField;
    SQLQuery2storyID: TLongintField;
    RepTabQuery: TSQLQuery;
    SQLQuery2tick1: TStringField;
    SQLQuery2tick2: TStringField;
    SQLQuery2tick3: TStringField;
    SQLQuery2tick4: TStringField;
    SearchSentenceQuery: TSQLQuery;
    SQLQueryLog: TSQLQuery;
    SQLQueryLogAvgAcc1_100: TBCDField;
    SQLQueryLogAvgAcc1_25: TBCDField;
    SQLQueryLogAvgAcc1_50: TBCDField;
    SQLQueryLogAvgAcc2_100: TBCDField;
    SQLQueryLogAvgAcc2_25: TBCDField;
    SQLQueryLogAvgAcc2_50: TBCDField;
    SQLQueryLogAvgAcc3_100: TBCDField;
    SQLQueryLogAvgAcc3_25: TBCDField;
    SQLQueryLogAvgAcc3_50: TBCDField;
    SQLQueryLogAvgAcc4_100: TBCDField;
    SQLQueryLogAvgAcc4_25: TBCDField;
    SQLQueryLogAvgAcc4_50: TBCDField;
    SQLQueryLogStoryCount: TLongintField;
    SQLQueryLogStorySelectedCount: TLongintField;
    SQLQueryLogStorySelectedPrc: TBCDField;
    SQLQueryLogTimeStamp: TDateTimeField;
    SQLTransaction1: TSQLTransaction;
    tabAlign: TTabSheet;
    TabSheet10: TTabSheet;
    TabSheet11: TTabSheet;
    TabSheet12: TTabSheet;
    TabSheet13: TTabSheet;
    TabSheet14: TTabSheet;
    TabSheet3: TTabSheet;
    tabQA: TTabSheet;
    tabChat: TTabSheet;
    tabRep: TTabSheet;
    tabPOS: TTabSheet;
    TabSheet4: TTabSheet;
    tabLog: TTabSheet;
    TabSheet5: TTabSheet;
    TabSearch: TTabSheet;
    tabSemanticMemory: TTabSheet;
    TabSheet6: TTabSheet;
    TabSheet7: TTabSheet;
    TabSheet8: TTabSheet;
    TabSheet9: TTabSheet;
    tabSummary: TTabSheet;
    tabStats: TTabSheet;
    TimerRefreshStory: TTimer;
    UpdateRepQuery: TSQLQuery;
    TabSheet1: TTabSheet;
    TabSheet2: TTabSheet;
    DeleteSentenceQuery: TSQLQuery;
    procedure btDelStoryClick(Sender: TObject);
    procedure btEditStoryClick(Sender: TObject);
    procedure btnBackClick(Sender: TObject);
    procedure btnCopyGCRepsClick(Sender: TObject);
    procedure btnCopyGRepsClick(Sender: TObject);
    procedure btnCopyGSRepsClick(Sender: TObject);
    procedure btnCopyRepsClick(Sender: TObject);
    procedure btnDefaultClick(Sender: TObject);
    procedure btnExportToMySQLClick(Sender: TObject);
    procedure btnForwardClick(Sender: TObject);
    procedure btnImportMySQLClick(Sender: TObject);
    procedure btnLessClick(Sender: TObject);
    procedure btnMoreClick(Sender: TObject);
    procedure btnPost1Click(Sender: TObject);
    procedure btnPostClick(Sender: TObject);
    procedure btnPredictAnswClick(Sender: TObject);
    procedure btnPredictCRepsClick(Sender: TObject);
    procedure btnPredictAllRepsClick(Sender: TObject);
    procedure btnSearchClick(Sender: TObject);
    procedure btnTestAddCrepClick(Sender: TObject);
    procedure btnTestGenerateGRep1Click(Sender: TObject);
    procedure btnTestGenerateGRepClick(Sender: TObject);
    procedure btnTestQAClick(Sender: TObject);
    procedure btnTestResetCrepClick(Sender: TObject);
    procedure btnTestTextClick(Sender: TObject);
    procedure btnUpdateAllPOSClick(Sender: TObject);
    procedure Button1Click(Sender: TObject);
    procedure Button2Click(Sender: TObject);
    procedure Button3Click(Sender: TObject);
    procedure Button4Click(Sender: TObject);
    procedure btnPredictRepsClick(Sender: TObject);
    procedure btnEditClick(Sender: TObject);
    procedure Button5Click(Sender: TObject);
    procedure Button6Click(Sender: TObject);
    procedure DBGrid3DrawColumnCell(Sender: TObject; const Rect: TRect;
      DataCol: Integer; Column: TColumn; State: TGridDrawState);
    procedure edtChatMsgKeyUp(Sender: TObject; var Key: Word; Shift: TShiftState);
    procedure edtSearchKeyUp(Sender: TObject; var Key: Word; Shift: TShiftState);
    procedure edtStoryNumberEnter(Sender: TObject);
    procedure edtStoryNumberExit(Sender: TObject);
    procedure edtStoryNumberKeyUp(Sender: TObject; var Key: Word;
      Shift: TShiftState);
    procedure edtTestRepSentenceExit(Sender: TObject);
    procedure edtTestWs1Exit(Sender: TObject);
    procedure edtTestWs2Exit(Sender: TObject);
    procedure FormClose(Sender: TObject; var CloseAction: TCloseAction);
    procedure FormCreate(Sender: TObject);
    procedure btAddStoryClick(Sender: TObject);
    procedure ScrollBox1Click(Sender: TObject);
    procedure SearchSentenceQueryAfterScroll(DataSet: TDataSet);
    procedure SQLQuery1AfterScroll(DataSet: TDataSet);
    procedure SQLQuery1BeforeDelete(DataSet: TDataSet);
    procedure SQLQuery2AfterInsert(DataSet: TDataSet);
    procedure TabSearchShow(Sender: TObject);
    procedure TimerRefreshStoryTimer(Sender: TObject);
  private
    { private declarations }
    FDecoder: TCRepDecoder;
    FEditPanelList: TFPObjectList;
    FEditingStoryFlag: boolean;
    FMaxStoryId: Integer;
    FPredictAllActive: Boolean;
    FQAPanelList: TFPObjectList;
    FStoryCount: Integer;
    FStorySentences: TSkyStringList;
    FSummaryLevel: Integer;
    FTestCRep: TCRep;
    FStory: TStoryObject;

    FDontBookMark: Boolean;
    FBookMarkList: TSkyStringList;
    FCurBookmarkIndex: Integer;

    procedure AdjustSummaryButtonsEnabled;
    procedure ClearAccuracyColors;
    procedure EditSentenceEditChange(Sender: TObject);
    procedure EditSentenceLabel1Click(Sender: TObject);

    procedure EditPanelEditChange(Sender: TObject);
    procedure EditPanelLabel1Click(Sender: TObject);

    procedure EditPanelQARuleChange(Sender: TObject);
    procedure EditPanelQARuleLabel1Click(Sender: TObject);

    procedure EditPanelCRepChange(Sender: TObject);
    procedure EditPanelCrepLabel1Click(Sender: TObject);

    procedure EditPanelSRepChange(Sender: TObject);
    procedure EditPanelSrepLabel1Click(Sender: TObject);

    procedure LoadStoryAverage(AMaxId, ATotalCount: Integer; ALoadLogData: Boolean);

    function ConfirmDeleteMsg: boolean;
    function GetLastRowID: integer;
    procedure RenderRepTabSentences;
    procedure RenderQATabSentences;
    procedure ShowNode(ANode: TNode; const AName: string; ATabCount: Integer);

    procedure UpdateRecordInidcators;
    procedure UpdateRepresentation(const ASentenceID: integer; const ARepresentation: string);
    procedure UpdateSentence(const ASentenceID: integer; const ASentence: string);
    procedure UpdateCRep(const ASentenceID: integer; const ACRep: string);
    procedure UpdateSRep(const ASentenceID: integer; const ASRep: string);
    procedure UpdateQARule(const AQAId: integer; const AQARule: string);

    procedure ParseStoryIntoSentences(AStoryID: integer);

    procedure UpdateMemoStoryEditingControls;
  public
    { public declarations }
  end;

  { TBookmarkObject }

  TBookmarkObject = class
  private
    FBookMark: TBookmark;
    FQuery: TSQLQuery;
  public
    constructor Create(AQuery: TSQLQuery; ABookMark: TBookMark);
    destructor Destroy; override;
    property Bookmark: TBookmark read FBookMark write FBookMark;
  end;

var
  MainForm: TMainForm;

const
  strRecIndicator = 'of %d';
  strNewRecIndicator = 'of %d';

implementation

uses
  RepCalculator, SentenceList, PosTagger, SentenceAlgorithm, SentenceListElement,
  QAAlgorithm, Math, MySQLExport, SentenceSplitter, MySQLImport, EditStory,
  Upgrade;

{$R *.lfm}

{ TBookmarkObject }

constructor TBookmarkObject.Create(AQuery: TSQLQuery; ABookMark: TBookMark);
begin
  FQuery := AQuery;
  FBookMark := ABookMark;
end;

destructor TBookmarkObject.Destroy;
begin
  FQuery.FreeBookmark(FBookMark);
  inherited Destroy;
end;

{ TMainForm }

procedure TMainForm.FormCreate(Sender: TObject);
begin
  FStory := nil;
  FDontBookMark := True;
  FBookMarkList := TSkyStringList.Create;
  FBookMarkList.OwnsObjects := True;
  FCurBookmarkIndex := 0;
  FDecoder := TCRepDecoder.Create;

  FStorySentences := TSkyStringList.Create;
  FEditPanelList := TFPObjectList.Create;
  FQAPanelList := TFPObjectList.Create;

  PageControl1.ActivePageIndex := 0;

  //SQLite3Connection1.DatabaseName:='/Users/paul/Paul/Lazarus/NASX/NASX.2.4/DBdemo.sqlite'; (* Paul's box *)
  //SQLite3Connection1.DatabaseName := 'C:\NASX.2.3\DBdemo.sqlite'; (* Larry's box *)
  //SQLite3Connection1.DatabaseName:='D:\work\PaulKp\NASX\NASX\DBdemo.sqlite'; (* Viorel's box *)
  SQLite3Connection1.DatabaseName := 'DBdemo.sqlite';

  SQLite3Connection1.Connected := True;
  Core.Database := SQLite3Connection1;

  if not TUpgrade.CheckDatabaseIsCorrectVersion then
  begin
    if (MessageDlg('Database needs to be upgraded. '#13#10 +
      'This will be done automatically. '#13#10 +
      'Press ok to continue', mtConfirmation, mbOKCancel, -1) <> mrOk) then
    begin
      Application.Terminate;
      Exit;
    end;
    TUpgrade.UpgradeDatabase;
  end;

  SQLQuery1.Open;
  FPredictAllActive := True;
  SQLQuery1.Last;
  FStoryCount := SQLQuery1.RecordCount;
  FMaxStoryId := SQLQuery1.FieldByName('storyId').AsInteger;
  SQLQuery1.First;
  FPredictAllActive := False;

  DataSource1.DataSet := SQLQuery1;

  DBGrid1.DataSource := DataSource1;
  DBGrid1.AutoFillColumns := True;

  DBNavigator1.DataSource := DataSource1;
  SQLQuery2.Open;
  RepTabQuery.Open;
  QATabQuery.Open;
  SQLQuery1.First;

  FTestCRep := TCRep.Create;
  LoadStoryAverage(FMaxStoryId, FStoryCount, True);

  //load logs
  SQLQueryLog.Open;
end;

procedure TMainForm.FormClose(Sender: TObject; var CloseAction: TCloseAction);
begin
  FEditPanelList.Free;
  FStorySentences.Free;
  FTestCRep.Free;
  FStory.Free;
end;

procedure TMainForm.SQLQuery1AfterScroll(DataSet: TDataSet);
var
  TheAc1, TheAc2, TheAc3, TheAc4: Integer;
  TheMax: Integer;
  TheStoryBookMark: TBookmark;
  TheBookMarkString: string;

  procedure SetAccuracyFont(AControl: TDBEdit; AValue: Integer);
  begin
    if AValue = TheMax then
    begin
      AControl.Font.Style := [fsBold];
      AControl.Font.Color := clBlue
    end
    else
    begin
      AControl.Font.Style := [];
      AControl.Font.Color := clDefault;
    end;
  end;
begin
  if FPredictAllActive then
    Exit;
  UpdateRecordInidcators;

  FEditingStoryFlag := False;
  UpdateMemoStoryEditingControls;

  if not FDontBookMark then
  begin
    if SQLQuery1.Active then
    begin
      FStory.Free;
      FStory := Core.StoryLoadFullById(SQLQuery1.FieldByName('storyId').AsInteger);

      TheStoryBookMark := SQLQuery1.GetBookmark;
      TheBookMarkString := IntToStr(Integer(TheStoryBookMark));
      if FBookMarkList.IndexOf(TheBookMarkString) > 0 then
         Exit;
      while FBookMarkList.Count > FCurBookmarkIndex do
        FBookMarkList.DeleteFromIndex(FBookMarkList.Count - 1);
      FBookMarkList.AddObject(TheBookMarkString, TBookmarkObject.Create(SQLQuery1,
        TheStoryBookMark));
      FCurBookmarkIndex := FBookMarkList.Count;
    end;
  end;
  FDontBookMark := False;

  if FBookMarkList.Count = 0 then
     btnBack.Enabled := False
  else btnBack.Enabled := True;

  if FCurBookmarkIndex <= 1 then
    btnBack.Enabled := False
  else
    btnBack.Enabled := True;

  if FCurBookmarkIndex = FBookMarkList.Count then
    btnForward.Enabled := False
  else
    btnForward.Enabled := True;

  RenderRepTabSentences;
  RenderQATabSentences;

  TheAc1 := SQLQuery1.FieldByName('RepAcc1_100').AsInteger;
  TheAc2 := SQLQuery1.FieldByName('RepAcc2_100').AsInteger;
  TheAc3 := SQLQuery1.FieldByName('RepAcc3_100').AsInteger;
  TheAc4 := SQLQuery1.FieldByName('RepAcc4_100').AsInteger;
  TheMax := Max(Max(TheAc1, TheAc2), Max(TheAc3, TheAc4));

  SetAccuracyFont(edtAccuracy1, TheAc1);
  SetAccuracyFont(edtAccuracy2, TheAc2);
  SetAccuracyFont(edtAccuracy3, TheAc3);
  SetAccuracyFont(edtAccuracy4, TheAc4);

  TheAc1 := SQLQuery1.FieldByName('SRepAcc1_100').AsInteger;
  TheAc2 := SQLQuery1.FieldByName('SRepAcc2_100').AsInteger;
  TheAc3 := SQLQuery1.FieldByName('SRepAcc3_100').AsInteger;
  TheAc4 := SQLQuery1.FieldByName('SRepAcc4_100').AsInteger;
  TheMax := Max(Max(TheAc1, TheAc2), Max(TheAc3, TheAc4));

  SetAccuracyFont(edtSAccuracy1, TheAc1);
  SetAccuracyFont(edtSAccuracy2, TheAc2);
  SetAccuracyFont(edtSAccuracy3, TheAc3);
  SetAccuracyFont(edtSAccuracy4, TheAc4);
end;

procedure TMainForm.UpdateRecordInidcators;
begin
  if SQLQuery1.State = dsInsert then
    lbRecIndicator.Caption := Format(strNewRecIndicator, [SQLQuery1.RecordCount])
  else
    lbRecIndicator.Caption := Format(strRecIndicator, [SQLQuery1.RecordCount]);
  edtStoryNumber.Text := IntToStr(SQLQuery1.RecNo);
end;

procedure TMainForm.SQLQuery1BeforeDelete(DataSet: TDataSet);
begin
  DeleteSentenceQuery.Prepare;
  DeleteSentenceQuery.ExecSQL;
end;

procedure TMainForm.SQLQuery2AfterInsert(DataSet: TDataSet);
begin
  DataSet.FieldByName('storyID').AsInteger := SQLQuery1.FieldByName('storyID').AsInteger;
  Memo1.Lines.add('Foriegn field value: ' + IntToStr(SQLQuery1.FieldByName('storyID').AsInteger));
end;

procedure TMainForm.TabSearchShow(Sender: TObject);
begin
  SearchSentenceQuery.Close;
  edtSearch.Text := '';
  edtSearch.SetFocus;
end;

procedure TMainForm.TimerRefreshStoryTimer(Sender: TObject);
begin
  (Sender as TTimer).Enabled := False;
  SQLQuery1AfterScroll(nil);
end;

procedure TMainForm.AdjustSummaryButtonsEnabled;
begin
  btnLess.Enabled := FSummaryLevel > 0;
  btnMore.Enabled := FSummaryLevel < FDecoder.MaxLevel;
  btnDefault.Enabled := (FSummaryLevel <> 1) or ((FSummaryLevel <> 0) and (FDecoder.MaxLevel = 0));
end;

procedure TMainForm.EditSentenceEditChange(Sender: TObject);
var
  ThePanel: TEditPanel;
  TheEdit: TEdit;
begin
  TheEdit := Sender as TEdit;
  ThePanel := TheEdit.Parent as TEditPanel;
  if ThePanel.Label0Text = TheEdit.Text then
    Exit;
  ThePanel.SetLabelText(0, TheEdit.Text);
  UpdateSentence(ThePanel.Tag, ThePanel.Edit0Text);
end;

procedure TMainForm.EditSentenceLabel1Click(Sender: TObject);
var
  TheQuery: TSQLQuery;
  TheStoryId: Integer;
begin
  TheQuery := TSQLQuery.Create(nil);
  try
    TheQuery.DataBase := SQLite3Connection1;
    TheQuery.SQL.Text := 'Select StoryID from StoryTbl where body like :ABody;';
    TheQuery.ParamByName('ABody').AsString := '%' + TLabel(Sender).Caption + '%';
    TheQuery.Open;
    if TheQuery.EOF then
      Exit;
    TheStoryId := TheQuery.FieldByName('StoryID').AsInteger;
  finally
    TheQuery.Free;
  end;
  FPredictAllActive := True;
  SQLQuery1.Locate('StoryID', TheStoryId, []);
  FPredictAllActive := False;
  // work around for a crash that happens when you reload data here
  TimerRefreshStory.Enabled := True;
end;

procedure TMainForm.EditPanelEditChange(Sender: TObject);
var
  ThePanel: TEditPanel;
  TheEdit: TEdit;
begin
  TheEdit := Sender as TEdit;
  ThePanel := TheEdit.Parent as TEditPanel;
  if (ThePanel.Label0Text = TheEdit.Text) or (TheEdit.Text = '') or (TheEdit.Text = '-') then
    Exit;
  ThePanel.SetLabelText(0, TheEdit.Text);
  UpdateRepresentation(ThePanel.Tag, ThePanel.Edit0Text);
end;

procedure TMainForm.EditPanelSRepChange(Sender: TObject);
var
  ThePanel: TEditPanel;
  TheEdit: TEdit;
begin
  TheEdit := Sender as TEdit;
  ThePanel := TheEdit.Parent as TEditPanel;
  if (ThePanel.Label0Text = TheEdit.Text) or (TheEdit.Text = '') or (TheEdit.Text = '-') then
    Exit;
  ThePanel.SetLabelText(0, TheEdit.Text);
  UpdateSRep(ThePanel.Tag, ThePanel.Edit0Text);
end;

procedure TMainForm.EditPanelLabel1Click(Sender: TObject);
var
  TheEditPanel: TEditPanel;
begin
  TheEditPanel := TEditPanel(TLabel(Sender).Parent);
  TheEditPanel.SetLabelText(0, TLabel(Sender).Caption);
  TheEditPanel.SetEditText(TLabel(Sender).Caption);
  UpdateRepresentation(TheEditPanel.Tag, TheEditPanel.Edit0Text);
end;

procedure TMainForm.EditPanelSrepLabel1Click(Sender: TObject);
var
  TheEditPanel: TEditPanel;
begin
  TheEditPanel := TEditPanel(TLabel(Sender).Parent);
  TheEditPanel.SetLabelText(0, TLabel(Sender).Caption);
  TheEditPanel.SetEditText(TLabel(Sender).Caption);
  UpdateSRep(TheEditPanel.Tag, TheEditPanel.Edit0Text);
end;

procedure TMainForm.EditPanelQARuleChange(Sender: TObject);
var
  ThePanel: TEditPanel;
  TheEdit: TEdit;
begin
  TheEdit := Sender as TEdit;
  ThePanel := TheEdit.Parent as TEditPanel;
  if (ThePanel.Label0Text = TheEdit.Text) or (TheEdit.Text = '') or (TheEdit.Text = '-') then
    Exit;
  ThePanel.SetLabelText(0, TheEdit.Text);
  UpdateQARule(ThePanel.Tag, ThePanel.Edit0Text);
end;

procedure TMainForm.EditPanelQARuleLabel1Click(Sender: TObject);
var
  TheEditPanel: TEditPanel;
begin
  TheEditPanel := TEditPanel(TLabel(Sender).Parent);
  TheEditPanel.SetLabelText(0, TLabel(Sender).Caption);
  TheEditPanel.SetEditText(TLabel(Sender).Caption);
  UpdateQARule(TheEditPanel.Tag, TheEditPanel.Edit0Text);
end;

procedure TMainForm.EditPanelCRepChange(Sender: TObject);
var
  ThePanel: TEditPanel;
  TheEdit: TEdit;
begin
  TheEdit := Sender as TEdit;
  ThePanel := TheEdit.Parent as TEditPanel;
  if (ThePanel.Label0Text = TheEdit.Text) or (TheEdit.Text = '') or (TheEdit.Text = '-') then
    Exit;
  ThePanel.SetLabelText(0, TheEdit.Text);
  UpdateCRep(ThePanel.Tag, ThePanel.Edit0Text);
end;

procedure TMainForm.EditPanelCrepLabel1Click(Sender: TObject);
var
  TheEditPanel: TEditPanel;
begin
  TheEditPanel := TEditPanel(TLabel(Sender).Parent);
  TheEditPanel.SetLabelText(0, TLabel(Sender).Caption);
  TheEditPanel.SetEditText(TLabel(Sender).Caption);
  UpdateCRep(TheEditPanel.Tag, TheEditPanel.Edit0Text);
end;

procedure TMainForm.LoadStoryAverage(AMaxId, ATotalCount: Integer; ALoadLogData: Boolean);
var
  TheQuery: TSQLQuery;
  TheAvg1, TheAvg2, TheAvg3, TheAvg4, TheMax: Double;
  TheAc125, TheAc225, TheAc325, TheAc425: Double;
  TheAc150, TheAc250, TheAc350, TheAc450: Double;
  TheSelectedCount: Integer;

  procedure SetAccuracyFont(AControl: TEdit; AValue: Double);
  begin
    if Round(AValue) = TheMax then
    begin
      AControl.Font.Style := [fsBold];
      AControl.Font.Color := clBlue
    end
    else
    begin
      AControl.Font.Style := [];
      AControl.Font.Color := clDefault;
    end;
  end;
begin
  TheQuery := TSQLQuery.Create(nil);
  try
    TheQuery.DataBase := SQLite3Connection1;
    if ALoadLogData then
      TheQuery.SQL.Add('select StorySelectedCount SelectedCount, ' +
      'AvgAcc1_25 AVG1_25, ' +
      'AvgAcc2_25 AVG2_25, ' +
      'AvgAcc3_25 AVG3_25, ' +
      'AvgAcc4_25 AVG4_25, ' +
      'AvgAcc1_50 AVG1_50, ' +
      'AvgAcc2_50 AVG2_50, ' +
      'AvgAcc3_50 AVG3_50, ' +
      'AvgAcc4_50 AVG4_50, ' +
      'AvgAcc1_100 AVG1_100, ' +
      'AvgAcc2_100 AVG2_100, ' +
      'AvgAcc3_100 AVG3_100, ' +
      'AvgAcc4_100 AVG4_100 ' +
      'from logTBL order by logId desc limit 1 ')
    else
    begin
      TheQuery.SQL.Add('select ' +
        'avg(RepAcc1_100) AVG1_100, ' +
        'avg(RepAcc2_100) AVG2_100, ' +
        'avg(RepAcc3_100) AVG3_100, ' +
        'avg(RepAcc4_100) AVG4_100, ' +
        'avg(RepAcc1_25) AVG1_25, ' +
        'avg(RepAcc2_25) AVG2_25, ' +
        'avg(RepAcc3_25) AVG3_25, ' +
        'avg(RepAcc4_25) AVG4_25, ' +
        'avg(RepAcc1_50) AVG1_50, ' +
        'avg(RepAcc2_50) AVG2_50, ' +
        'avg(RepAcc3_50) AVG3_50, ' +
        'avg(RepAcc4_50) AVG4_50, Count(storyId) SelectedCount ' +
        'from StoryTbl where storyId <= :storyId;');
      TheQuery.ParamByName('storyId').AsInteger := AMaxId;
    end;
    TheQuery.Open;
    if TheQuery.Eof then
      Exit;
    TheAvg1 := TheQuery.FieldByName('AVG1_100').AsFloat;
    TheAvg2 := TheQuery.FieldByName('AVG2_100').AsFloat;
    TheAvg3 := TheQuery.FieldByName('AVG3_100').AsFloat;
    TheAvg4 := TheQuery.FieldByName('AVG4_100').AsFloat;
    TheMax := Max(Max(Round(TheAvg1), Round(TheAvg2)),
      Max(Round(TheAvg3), Round(TheAvg4)));

    TheAc125 := TheQuery.FieldByName('AVG1_25').AsFloat;
    TheAc225 := TheQuery.FieldByName('AVG2_25').AsFloat;
    TheAc325 := TheQuery.FieldByName('AVG3_25').AsFloat;
    TheAc425 := TheQuery.FieldByName('AVG4_25').AsFloat;

    TheAc150 := TheQuery.FieldByName('AVG1_50').AsFloat;
    TheAc250 := TheQuery.FieldByName('AVG2_50').AsFloat;
    TheAc350 := TheQuery.FieldByName('AVG3_50').AsFloat;
    TheAc450 := TheQuery.FieldByName('AVG4_50').AsFloat;

    TheSelectedCount := TheQuery.FieldByName('SelectedCount').AsInteger;

    ChartSrcWrd.DataPoints.Text := Format('0|0|?|'#13#10'25|%d|?|'#13#10'50|%d|?|'#13#10'100|%d|?|'#13#10,
      [Round(TheAc125), Round(TheAc150), Round(TheAvg1)]);
    ChartSrcPos.DataPoints.Text := Format('0|0|?|'#13#10'25|%d|?|'#13#10'50|%d|?|'#13#10'100|%d|?|'#13#10,
      [Round(TheAc225), Round(TheAc250), Round(TheAvg2)]);
    ChartSrcWP.DataPoints.Text := Format('0|0|?|'#13#10'25|%d|?|'#13#10'50|%d|?|'#13#10'100|%d|?|'#13#10,
      [Round(TheAc325), Round(TheAc350), Round(TheAvg3)]);
    ChartSrcWSEMP.DataPoints.Text := Format('0|0|?|'#13#10'25|%d|?|'#13#10'50|%d|?|'#13#10'100|%d|?|'#13#10,
      [Round(TheAc425), Round(TheAc450), Round(TheAvg4)]);

    edtStoryAccuracy1.Text := IntToStr(Round(TheAvg1));
    edtStoryAccuracy2.Text := IntToStr(Round(TheAvg2));
    edtStoryAccuracy3.Text := IntToStr(Round(TheAvg3));
    edtStoryAccuracy4.Text := IntToStr(Round(TheAvg4));

    SetAccuracyFont(edtStoryAccuracy1, TheAvg1);
    SetAccuracyFont(edtStoryAccuracy2, TheAvg2);
    SetAccuracyFont(edtStoryAccuracy3, TheAvg3);
    SetAccuracyFont(edtStoryAccuracy4, TheAvg4);

    TheQuery.Close;
    if not ALoadLogData then
    begin
      TheQuery.SQL.Clear;
      TheQuery.SQL.Add('insert into logTBL(TimeStamp, StoryCount, ' +
        'StorySelectedPrc, StorySelectedCount, AvgAcc1_25, AvgAcc2_25, AvgAcc3_25, ' +
        'AvgAcc4_25, AvgAcc1_50, AvgAcc2_50, AvgAcc3_50, AvgAcc4_50, AvgAcc1_100, ' +
        'AvgAcc2_100, AvgAcc3_100, AvgAcc4_100) values( ' +
        'datetime(), :StoryCount, :StorySelectedPrc, :StorySelectedCount, ' +
        ':AvgAcc1_25, :AvgAcc2_25, :AvgAcc3_25, :AvgAcc4_25, :AvgAcc1_50, ' +
        ':AvgAcc2_50, :AvgAcc3_50, :AvgAcc4_50, :AvgAcc1_100, :AvgAcc2_100, ' +
        ':AvgAcc3_100, :AvgAcc4_100);'
      );

      TheQuery.ParamByName('StoryCount').AsInteger := ATotalCount;
      TheQuery.ParamByName('StorySelectedCount').AsInteger := TheSelectedCount;
      if ATotalCount = 0 then
        TheQuery.ParamByName('StorySelectedPrc').AsFloat := 0
      else
        TheQuery.ParamByName('StorySelectedPrc').AsFloat := TheSelectedCount / ATotalCount * 100;

      TheQuery.ParamByName('AvgAcc1_25').AsFloat := TheAc125;
      TheQuery.ParamByName('AvgAcc2_25').AsFloat := TheAc225;
      TheQuery.ParamByName('AvgAcc3_25').AsFloat := TheAc325;
      TheQuery.ParamByName('AvgAcc4_25').AsFloat := TheAc425;

      TheQuery.ParamByName('AvgAcc1_50').AsFloat := TheAc150;
      TheQuery.ParamByName('AvgAcc2_50').AsFloat := TheAc250;
      TheQuery.ParamByName('AvgAcc3_50').AsFloat := TheAc350;
      TheQuery.ParamByName('AvgAcc4_50').AsFloat := TheAc450;

      TheQuery.ParamByName('AvgAcc1_100').AsFloat := TheAvg1;
      TheQuery.ParamByName('AvgAcc2_100').AsFloat := TheAvg2;
      TheQuery.ParamByName('AvgAcc3_100').AsFloat := TheAvg3;
      TheQuery.ParamByName('AvgAcc4_100').AsFloat := TheAvg4;

      TheQuery.ExecSQL;
    end;
    SQLTransaction1.CommitRetaining;
  finally
    TheQuery.Free;
  end;
end;

procedure TMainForm.RenderRepTabSentences;
var
  ThePanelSentence, ThePanelRep, ThePanelContextRep, ThePanelSemanticRep: TEditPanel;
  c, h: integer;
  TheTotalWidth: Integer;
  TheWidth1, TheWidth2, TheWidth3, TheWidth4: Integer;
  I: Integer;
begin
  FEditPanelList.Clear;
  h := 117; //Designed height of the EditPanel component

  c := 0;
  //FSentences.ClearSentenceText;
  //FSentences.StoryId := SQLQuery1.FieldByName('storyID').AsInteger;
  TheTotalWidth := ScrollBox1.Width - 95; // spacing
  TheWidth1 := Round(TheTotalWidth * 0.2);
  TheWidth2 := Round(TheTotalWidth * 0.25);
  TheWidth3 := Round(TheTotalWidth * 0.25);
  TheWidth4 := Round(TheTotalWidth * 0.25);
  lblSentenceInd.Left := 15;
  lblSentenceRep.Left := 45 + TheWidth1;
  lblSentenceCRep.Left := 75 + TheWidth1 + TheWidth2;
  lblSentenceSRep.Left := 105 + TheWidth1 + TheWidth2 + TheWidth3;
  if RepTabQuery.Active then
    RepTabQuery.First;

  memoCrepDecoded.Lines.Clear;
  memoSrepDecoded.Lines.Clear;
  FDecoder.Clear;
  while not RepTabQuery.EOF do
  begin
    ThePanelSentence := TEditPanel.Create(ScrollBox1);
    ThePanelRep := TEditPanel.Create(ScrollBox1);
    ThePanelContextRep := TEditPanel.Create(ScrollBox1);
    ThePanelSemanticRep := TEditPanel.Create(ScrollBox1);

    ThePanelSentence.Tag := RepTabQuery.FieldByName('sentenceID').AsInteger;
    ThePanelRep.Tag := RepTabQuery.FieldByName('sentenceID').AsInteger;
    ThePanelContextRep.Tag := RepTabQuery.FieldByName('sentenceID').AsInteger;
    ThePanelSemanticRep.Tag := RepTabQuery.FieldByName('sentenceID').AsInteger;

    ThePanelSentence.Parent := ScrollBox1;
    ThePanelSentence.Top := (h * c) + (15 * (c + 1));
    ThePanelSentence.Left := 15;
    ThePanelSentence.AdjustControlWidth(TheWidth1);

    ThePanelSentence.EventsActive := True;
    ThePanelSentence.OnEditChange := @EditSentenceEditChange;
    ThePanelSentence.OnLabel1Click := @EditSentenceLabel1Click;
    ThePanelSentence.OnLabel2Click := @EditSentenceLabel1Click;
    ThePanelSentence.OnLabel3Click := @EditSentenceLabel1Click;
    ThePanelSentence.OnLabel4Click := @EditSentenceLabel1Click;

    if RepTabQuery.FieldByName('sentence').AsString = '' then
      ThePanelSentence.Label0Text := '-'
    else
      ThePanelSentence.Label0Text := RepTabQuery.FieldByName('sentence').AsString;
    ThePanelSentence.Label1Text := RepTabQuery.FieldByName('guesses1').AsString;
    ThePanelSentence.Label2Text := RepTabQuery.FieldByName('guesses2').AsString;
    ThePanelSentence.Label3Text := RepTabQuery.FieldByName('guesses3').AsString;
    ThePanelSentence.Label4Text := RepTabQuery.FieldByName('guesses4').AsString;

    ThePanelRep.Parent := ScrollBox1;
    ThePanelRep.Top := ThePanelSentence.Top;
    ThePanelRep.Left := 45 + TheWidth1;
    ThePanelRep.AdjustControlWidth(TheWidth2);

    ThePanelRep.EventsActive := True;
    ThePanelRep.OnEditChange := @EditPanelEditChange;
    ThePanelRep.OnLabel1Click := @EditPanelLabel1Click;
    ThePanelRep.OnLabel2Click := @EditPanelLabel1Click;
    ThePanelRep.OnLabel3Click := @EditPanelLabel1Click;
    ThePanelRep.OnLabel4Click := @EditPanelLabel1Click;

    if RepTabQuery.FieldByName('representation').AsString = '' then
      ThePanelRep.Label0Text := '-'
    else
      ThePanelRep.Label0Text := RepTabQuery.FieldByName('representation').AsString;
    ThePanelRep.Label1Text := RepTabQuery.FieldByName('repguess1').AsString;
    ThePanelRep.Label2Text := RepTabQuery.FieldByName('repguess2').AsString;
    ThePanelRep.Label3Text := RepTabQuery.FieldByName('repguess3').AsString;
    ThePanelRep.Label4Text := RepTabQuery.FieldByName('repguess4').AsString;

    ThePanelContextRep.Parent := ScrollBox1;
    ThePanelContextRep.Top := ThePanelSentence.Top;
    ThePanelContextRep.Left := 75 + TheWidth1 + TheWidth2;
    ThePanelContextRep.AdjustControlWidth(TheWidth3);

    ThePanelContextRep.EventsActive := True;
    ThePanelContextRep.OnEditChange := @EditPanelCRepChange;
    ThePanelContextRep.OnLabel1Click := @EditPanelCrepLabel1Click;
    ThePanelContextRep.OnLabel2Click := @EditPanelCrepLabel1Click;
    ThePanelContextRep.OnLabel3Click := @EditPanelCrepLabel1Click;
    ThePanelContextRep.OnLabel4Click := @EditPanelCrepLabel1Click;

    if RepTabQuery.FieldByName('context_rep').AsString = '' then
      ThePanelContextRep.Label0Text := '-'
    else
      ThePanelContextRep.Label0Text := RepTabQuery.FieldByName('context_rep').AsString;
    ThePanelContextRep.Label1Text := RepTabQuery.FieldByName('crepguess1').AsString;
    ThePanelContextRep.Label2Text := RepTabQuery.FieldByName('crepguess2').AsString;
    ThePanelContextRep.Label3Text := RepTabQuery.FieldByName('crepguess3').AsString;
    ThePanelContextRep.Label4Text := RepTabQuery.FieldByName('crepguess4').AsString;

    FDecoder.AddCrep(ThePanelContextRep.Label0Text);

    ThePanelSemanticRep.Parent := ScrollBox1;
    ThePanelSemanticRep.Top := ThePanelSentence.Top;
    ThePanelSemanticRep.Left := 105 + TheWidth1 + TheWidth2 + TheWidth3;
    ThePanelSemanticRep.AdjustControlWidth(TheWidth4);

    ThePanelSemanticRep.EventsActive := True;
    ThePanelSemanticRep.OnEditChange := @EditPanelSRepChange;
    ThePanelSemanticRep.OnLabel1Click := @EditPanelSrepLabel1Click;
    ThePanelSemanticRep.OnLabel2Click := @EditPanelSrepLabel1Click;
    ThePanelSemanticRep.OnLabel3Click := @EditPanelSrepLabel1Click;
    ThePanelSemanticRep.OnLabel4Click := @EditPanelSrepLabel1Click;

    if RepTabQuery.FieldByName('semantic_rep').AsString = '' then
      ThePanelSemanticRep.Label0Text := '-'
    else
      ThePanelSemanticRep.Label0Text := RepTabQuery.FieldByName('semantic_rep').AsString;
    ThePanelSemanticRep.Label1Text := RepTabQuery.FieldByName('srepguess1').AsString;
    ThePanelSemanticRep.Label2Text := RepTabQuery.FieldByName('srepguess2').AsString;
    ThePanelSemanticRep.Label3Text := RepTabQuery.FieldByName('srepguess3').AsString;
    ThePanelSemanticRep.Label4Text := RepTabQuery.FieldByName('srepguess4').AsString;

    memoSrepDecoded.Lines.Add(ThePanelSemanticRep.Label0Text);

    FEditPanelList.Add(ThePanelSentence);
    FEditPanelList.Add(ThePanelRep);
    FEditPanelList.Add(ThePanelContextRep);
    FEditPanelList.Add(ThePanelSemanticRep);

    Inc(c);
    RepTabQuery.Next;
  end;
  FDecoder.PostProcess;
  if FDecoder.MaxLevel > 0 then
    FSummaryLevel := 1
  else
    FSummaryLevel := 0;
  AdjustSummaryButtonsEnabled;
  btnDefault.Caption := 'Default';

  for I := 0 to FDecoder.PItems.Count - 1 do
    ShowNode(FDecoder.PItems.Objects[I] as TNode, FDecoder.PItems[I], 0);
  memoSummary.Lines.Text := FDecoder.GetFriendlyText(FSummaryLevel);
end;

procedure TMainForm.RenderQATabSentences;
var
  ThePanelQuestion, ThePanelAnswer, ThePanelRule: TEditPanel;
  c, h: integer;
  TheTotalWidth: Integer;
  TheWidth1, TheWidth2, TheWidth3: Integer;
begin
  //create QA tab

  FQAPanelList.Clear;
  h := 117; //Designed height of the EditPanel component

  c := 0;
  TheTotalWidth := ScrollBoxQA.Width - 95; // spacing
  TheWidth1 := Round(TheTotalWidth * 0.3);
  TheWidth2 := Round(TheTotalWidth * 0.35);
  TheWidth3 := Round(TheTotalWidth * 0.35);
  lblQAQuestion.Left := 15;
  lblQAAnswer.Left := 45 + TheWidth1;
  lblQARule.Left := 75 + TheWidth1 + TheWidth2;
  if QATabQuery.Active then
    QATabQuery.First;

  while not QATabQuery.EOF do
  begin
    ThePanelQuestion := TEditPanel.Create(ScrollBox1);
    ThePanelAnswer := TEditPanel.Create(ScrollBox1);
    ThePanelRule := TEditPanel.Create(ScrollBox1);

    ThePanelQuestion.Tag := QATabQuery.FieldByName('questionID').AsInteger;
    ThePanelAnswer.Tag := QATabQuery.FieldByName('questionID').AsInteger;
    ThePanelRule.Tag := QATabQuery.FieldByName('questionID').AsInteger;

    ThePanelQuestion.Parent := ScrollBoxQA;
    ThePanelQuestion.Top := (h * c) + (15 * (c + 1));
    ThePanelQuestion.Left := 15;
    ThePanelQuestion.AdjustControlWidth(TheWidth1);

    ThePanelQuestion.Label0Text := QATabQuery.FieldByName('question').AsString;

    ThePanelAnswer.Parent := ScrollBoxQA;
    ThePanelAnswer.Top := ThePanelQuestion.Top;
    ThePanelAnswer.Left := 45 + TheWidth1;
    ThePanelAnswer.AdjustControlWidth(TheWidth2);

    ThePanelAnswer.Label0Text := QATabQuery.FieldByName('answer').AsString;
    ThePanelAnswer.Label1Text := QATabQuery.FieldByName('guessans1').AsString;
    ThePanelAnswer.Label2Text := QATabQuery.FieldByName('guessans2').AsString;
    ThePanelAnswer.Label3Text := QATabQuery.FieldByName('guessans3').AsString;
    ThePanelAnswer.Label4Text := QATabQuery.FieldByName('guessans4').AsString;

    ThePanelRule.Parent := ScrollBoxQA;
    ThePanelRule.Top := ThePanelQuestion.Top;
    ThePanelRule.Left := 75 + TheWidth1 + TheWidth2;
    ThePanelRule.AdjustControlWidth(TheWidth3);

    ThePanelRule.EventsActive := True;
    ThePanelRule.OnEditChange := @EditPanelQARuleChange;
    ThePanelRule.OnLabel1Click := @EditPanelQARuleLabel1Click;
    ThePanelRule.OnLabel2Click := @EditPanelQARuleLabel1Click;
    ThePanelRule.OnLabel3Click := @EditPanelQARuleLabel1Click;
    ThePanelRule.OnLabel4Click := @EditPanelQARuleLabel1Click;

    ThePanelRule.Label0Text := QATabQuery.FieldByName('qarule').AsString;
    ThePanelRule.Label1Text := QATabQuery.FieldByName('guessrule1').AsString;
    ThePanelRule.Label2Text := QATabQuery.FieldByName('guessrule2').AsString;
    ThePanelRule.Label3Text := QATabQuery.FieldByName('guessrule3').AsString;
    ThePanelRule.Label4Text := QATabQuery.FieldByName('guessrule4').AsString;

    FQAPanelList.Add(ThePanelQuestion);
    FQAPanelList.Add(ThePanelAnswer);
    FQAPanelList.Add(ThePanelRule);

    Inc(c);
    QATabQuery.Next;
  end;
end;

function TMainForm.ConfirmDeleteMsg: boolean;
begin
  Result := (MessageDlg('Delete?', 'Really delete this story?', mtConfirmation, mbOKCancel, 0) = mrOk);
end;

procedure TMainForm.UpdateRepresentation(const ASentenceID: integer; const ARepresentation: string);
var
  TheQuery: TSQLQuery;
begin
  TheQuery := TSQLQuery.Create(nil);
  try
    TheQuery.DataBase := SQLite3Connection1;
    TheQuery.SQL.Add('update sentenceTBL set representation = :representation where sentenceID = :sentenceID');
    TheQuery.ParamByName('sentenceID').AsInteger := ASentenceID;
    TheQuery.ParamByName('representation').AsString := ARepresentation;
    TheQuery.ExecSQL;
  finally
    TheQuery.Free;
  end;
  if SQLTransaction1.Active then
    SQLTransaction1.CommitRetaining;
end;

procedure TMainForm.UpdateSentence(const ASentenceID: integer; const ASentence: string);
var
  TheQuery: TSQLQuery;
  TheStoryId: Integer;
  TheSentences: string;
begin
  TheQuery := TSQLQuery.Create(nil);
  try
    TheQuery.DataBase := SQLite3Connection1;
    TheQuery.SQL.Add('update sentenceTBL set sentence = :sentence, pos = :strpos where sentenceID = :sentenceID');
    TheQuery.ParamByName('sentenceID').AsInteger := ASentenceID;
    TheQuery.ParamByName('sentence').AsString := ASentence;
    TheQuery.ParamByName('strpos').AsString := TPosTagger.GetTagsForString(ASentence);
    TheQuery.ExecSQL;

    RepTabQuery.Close;
    RepTabQuery.Open;

    TheSentences := '';
    while not RepTabQuery.EOF do
    begin
      TheSentences := TheSentences + RepTabQuery.FieldByName('sentence').AsString + '. ';
      RepTabQuery.Next;
    end;
    TheSentences := Trim(TheSentences);

    TheStoryId := SQLQuery1.FieldByName('storyId').AsInteger;
    TheQuery.SQL.Clear;
    TheQuery.SQL.Add('update storyTBL set body = :body where storyId = :storyId;');
    TheQuery.ParamByName('storyId').AsInteger := TheStoryId;
    TheQuery.ParamByName('body').AsString := TheSentences;
    TheQuery.ExecSQL;

    SQLQuery1.Edit;
    SQLQuery1.FieldByName('body').AsString := TheSentences;
    SQLQuery1.Post;
  finally
    TheQuery.Free;
  end;
  if SQLTransaction1.Active then
    SQLTransaction1.CommitRetaining;
end;

procedure TMainForm.UpdateCRep(const ASentenceID: integer; const ACRep: string);
var
  TheQuery: TSQLQuery;
begin
  TheQuery := TSQLQuery.Create(nil);
  try
    TheQuery.DataBase := SQLite3Connection1;
    TheQuery.SQL.Add('update sentenceTBL set context_rep = :crep where sentenceID = :sentenceID');
    TheQuery.ParamByName('sentenceID').AsInteger := ASentenceID;
    TheQuery.ParamByName('crep').AsString := ACRep;
    TheQuery.ExecSQL;
  finally
    TheQuery.Free;
  end;
  if SQLTransaction1.Active then
    SQLTransaction1.CommitRetaining;
end;

procedure TMainForm.UpdateSRep(const ASentenceID: integer; const ASRep: string);
var
  TheQuery: TSQLQuery;
begin
  TheQuery := TSQLQuery.Create(nil);
  try
    TheQuery.DataBase := SQLite3Connection1;
    TheQuery.SQL.Add('update sentenceTBL set semantic_rep = :srep where sentenceID = :sentenceID');
    TheQuery.ParamByName('sentenceID').AsInteger := ASentenceID;
    TheQuery.ParamByName('srep').AsString := ASRep;
    TheQuery.ExecSQL;
  finally
    TheQuery.Free;
  end;
  if SQLTransaction1.Active then
    SQLTransaction1.CommitRetaining;
end;

procedure TMainForm.UpdateQARule(const AQAId: integer; const AQARule: string);
var
  TheQuery: TSQLQuery;
begin
  TheQuery := TSQLQuery.Create(nil);
  try
    TheQuery.DataBase := SQLite3Connection1;
    TheQuery.SQL.Add('update qaTBL set qarule = :rule where questionId = :qaID');
    TheQuery.ParamByName('qaID').AsInteger := AQAId;
    TheQuery.ParamByName('rule').AsString := AQARule;
    TheQuery.ExecSQL;
  finally
    TheQuery.Free;
  end;
  if SQLTransaction1.Active then
    SQLTransaction1.CommitRetaining;
end;

procedure TMainForm.ParseStoryIntoSentences(AStoryID: integer);
var
  o: string;
  sl: TSkyStringList;
  c: integer;
  TheTagger: TPosTagger;
begin
  TheTagger := nil;
  sl := TSkyStringList.Create;
  try
    TheTagger := TPosTagger.Create;
    o := SQLQuery1.FieldByName('body').AsString;

    sl.Text := StringReplace(o, '.', #13#10, [rfReplaceAll]);

    // Delete all sentences for this story
    DeleteSentenceQuery.ParamByName('storyID').AsInteger := AStoryID;
    DeleteSentenceQuery.Prepare;
    DeleteSentenceQuery.ExecSQL;

    // Insert new sentences for this story
    for c := 0 to sl.Count - 1 do
    begin
      sl[c] := Trim(sl[c]);

      if sl[c] <> EmptyStr then
      begin
        InsertSentenceQuery.ParamByName('storyID').AsInteger := AStoryID;
        InsertSentenceQuery.ParamByName('sentence').AsString := sl[c];
        InsertSentenceQuery.ParamByName('POS').AsString := TheTagger.DoGetTagsForString(sl[c]);
        InsertSentenceQuery.Prepare;
        InsertSentenceQuery.ExecSQL;
      end;
    end;
  finally
    sl.Free;
    TheTagger.Free;
  end;

  SQLTransaction1.CommitRetaining;
end;

procedure TMainForm.UpdateMemoStoryEditingControls;
begin
  memoStory.ReadOnly := not FEditingStoryFlag;
  dbnavigator3.Refresh;

  if FEditingStoryFlag then
  begin
    memoStory.Color := clWindow;
    btnEdit.Caption := 'Save';
  end
  else
  begin
    memoStory.Color := clBtnFace;
    btnEdit.Caption := 'Edit';
  end;
  edtStoryTitle.ReadOnly := memoStory.ReadOnly;
  edtStoryTitle.Color := memoStory.Color;
end;

procedure TMainForm.btAddStoryClick(Sender: TObject);
begin
  if TEditStoryForm.ExecuteAdd then
  begin
    SQLQuery1.Refresh;
    SQLQuery1.Last;
    RepTabQuery.Active := True;
    SQLQuery2.Open;
    RepTabQuery.Open;
    QATabQuery.Open;
  end;
end;

procedure TMainForm.ScrollBox1Click(Sender: TObject);
begin
  TScrollBox(Sender).SetFocus;
end;

procedure TMainForm.SearchSentenceQueryAfterScroll(DataSet: TDataSet);
var
  TheStoryId: Integer;
begin
  if (not SearchSentenceQuery.Active) or (not SQLQuery1.Active) then
    Exit;
  TheStoryId := SearchSentenceQuery.FieldByName('StoryID').AsInteger;
  SQLQuery1.Locate('StoryID', TheStoryId, []);
end;

procedure TMainForm.Button1Click(Sender: TObject);
begin
  SQLQuery1.UpdateMode := TUpdateMode.upWhereAll;
  SQLQuery1.ApplyUpdates;
  SQLTransaction1.CommitRetaining;
end;

procedure TMainForm.btEditStoryClick(Sender: TObject);
begin
  SQLQuery1.Edit;
end;

procedure TMainForm.btnBackClick(Sender: TObject);
var
  TheBookMarkObject: TBookmarkObject;
begin
  FDontBookMark := True;
  if FCurBookmarkIndex < 1 then
  exit;
  FCurBookmarkIndex := FCurBookmarkIndex - 1;
  TheBookMarkObject := FBookMarkList.Objects[FCurBookmarkIndex - 1] as TBookMarkObject;
  SQLQuery1.GotoBookmark(TheBookMarkObject.Bookmark);
end;

procedure TMainForm.btnCopyGCRepsClick(Sender: TObject);
var
  TheQuery: TSQLQuery;
  TheStoryId: Integer;
begin
  if (MessageDlg('Replace CONTEXT REP with GUESS REP', 'Replace CONTEXT REPS for all the sentences in this story?',
    mtWarning, mbOKCancel, -1) <> mrOK) then
    Exit;
  TheStoryId := SQLQuery1.FieldByName('storyID').AsInteger;
  TheQuery := TSQLQuery.Create(nil);
  try
    TheQuery.DataBase := SQLite3Connection1;
    TheQuery.SQL.Add('update sentenceTBL set context_rep = crepguess1 where storyId = :storyId');
    TheQuery.ParamByName('storyId').AsInteger := TheStoryId;
   TheQuery.ExecSQL;
  finally
    TheQuery.Free;
  end;
  if SQLTransaction1.Active then
    SQLTransaction1.CommitRetaining;
  RepTabQuery.Close;
  RepTabQuery.Open;
  SQLQuery1AfterScroll(SQLQuery1);
end;

procedure TMainForm.btnCopyGRepsClick(Sender: TObject);
var
  TheQuery: TSQLQuery;
  TheStoryId: Integer;
begin
  if (MessageDlg('Replace REP with Guess Rep 4', 'Replace GUESS REPS for all the sentences in this story?',
    mtWarning, mbOKCancel, -1) <> mrOK) then
    Exit;
  TheStoryId := SQLQuery1.FieldByName('storyID').AsInteger;
  TheQuery := TSQLQuery.Create(nil);
  try
    TheQuery.DataBase := SQLite3Connection1;
    TheQuery.SQL.Add('update sentenceTBL set representation = repguess4 where storyId = :storyId');
    TheQuery.ParamByName('storyId').AsInteger := TheStoryId;
    TheQuery.ExecSQL;
  finally
    TheQuery.Free;
  end;
  if SQLTransaction1.Active then
    SQLTransaction1.CommitRetaining;
  RepTabQuery.Close;
  RepTabQuery.Open;
  SQLQuery1AfterScroll(SQLQuery1);
end;

procedure TMainForm.btnCopyGSRepsClick(Sender: TObject);
var
  TheQuery: TSQLQuery;
  TheStoryId: Integer;
begin
  if (MessageDlg('Replace SEM REP with Guess Rep 4', 'Replace SEM GUESS REPS for all the sentences in this story?',
    mtWarning, mbOKCancel, -1) <> mrOK) then
    Exit;
  TheStoryId := SQLQuery1.FieldByName('storyID').AsInteger;
  TheQuery := TSQLQuery.Create(nil);
  try
    TheQuery.DataBase := SQLite3Connection1;
    TheQuery.SQL.Add('update sentenceTBL set Semantic_rep = srepguess4 where storyId = :storyId');
    TheQuery.ParamByName('storyId').AsInteger := TheStoryId;
    TheQuery.ExecSQL;
  finally
    TheQuery.Free;
  end;
  if SQLTransaction1.Active then
    SQLTransaction1.CommitRetaining;
  RepTabQuery.Close;
  RepTabQuery.Open;
  SQLQuery1AfterScroll(SQLQuery1);
end;

procedure TMainForm.btnCopyRepsClick(Sender: TObject);
var
  TheQuery: TSQLQuery;
  TheStoryId: Integer;
begin
  if (MessageDlg('Replace CONTEXT REP with Rep', 'Replace CONTEXT REPS for all the sentences in this story?',
    mtWarning, mbOKCancel, -1) <> mrOK) then
    Exit;
  TheStoryId := SQLQuery1.FieldByName('storyID').AsInteger;
  TheQuery := TSQLQuery.Create(nil);
  try
    TheQuery.DataBase := SQLite3Connection1;
    TheQuery.SQL.Add('update sentenceTBL set context_rep = representation where storyId = :storyId');
    TheQuery.ParamByName('storyId').AsInteger := TheStoryId;
   TheQuery.ExecSQL;
  finally
    TheQuery.Free;
  end;
  if SQLTransaction1.Active then
    SQLTransaction1.CommitRetaining;
  RepTabQuery.Close;
  RepTabQuery.Open;
  SQLQuery1AfterScroll(SQLQuery1);
end;

procedure TMainForm.btnDefaultClick(Sender: TObject);
begin
  if FDecoder.MaxLevel = 0 then
    FSummaryLevel := 0
  else
    FSummaryLevel := 1;
  AdjustSummaryButtonsEnabled;
  memoSummary.Lines.Text := FDecoder.GetFriendlyText(FSummaryLevel);
end;

procedure TMainForm.btnExportToMySQLClick(Sender: TObject);
var
  TheForm: TMySqlExportForm;
begin
  TheForm := TMySqlExportForm.Create(nil);
  try
   TheForm.LiteDB := SQLite3Connection1;
   TheForm.Transaction := SQLTransaction1;
   TheForm.ShowModal;
   if TheForm.ImportFinished then
   begin
     SQLQuery1.Close;
     SQLQuery1.Open;
     SQLQuery1.Last;
     RepTabQuery.Active := True;
     SQLQuery2.Open;
     RepTabQuery.Open;
     QATabQuery.Open;
   end;
  finally
    TheForm.Free;
  end;
end;

procedure TMainForm.btnForwardClick(Sender: TObject);
var
  TheBookMarkObject: TBookmarkObject;
begin
  FDontBookMark := True;
  if FCurBookmarkIndex < 1 then
  exit;
  FCurBookmarkIndex := FCurBookmarkIndex + 1;
  TheBookMarkObject := FBookMarkList.Objects[FCurBookmarkIndex - 1] as TBookMarkObject;
  SQLQuery1.GotoBookmark(TheBookMarkObject.Bookmark);
end;

procedure TMainForm.btnImportMySQLClick(Sender: TObject);
var
  TheForm: TMySqlImportForm;
begin
  TheForm := TMySQLImportForm.Create(nil);
  try
   TheForm.LiteDB := SQLite3Connection1;
   TheForm.Transaction := SQLTransaction1;
   TheForm.ShowModal;
   if TheForm.ImportFinished then
   begin
     SQLQuery1.Close;
     SQLQuery1.Open;
     SQLQuery1.Last;
     RepTabQuery.Active := True;
     SQLQuery2.Open;
     RepTabQuery.Open;
     QATabQuery.Open;
   end;
  finally
    TheForm.Free;
  end;
end;

procedure TMainForm.btnLessClick(Sender: TObject);
begin
  if FSummaryLevel <= 0 then
    Exit;
  FSummaryLevel := FSummaryLevel - 1;
  AdjustSummaryButtonsEnabled;
  memoSummary.Lines.Text := FDecoder.GetFriendlyText(FSummaryLevel);
end;

procedure TMainForm.btnMoreClick(Sender: TObject);
begin
  if FSummaryLevel >= FDecoder.MaxLevel then
    Exit;
  FSummaryLevel := FSummaryLevel + 1;
  AdjustSummaryButtonsEnabled;
  memoSummary.Lines.Text := FDecoder.GetFriendlyText(FSummaryLevel);
end;

procedure TMainForm.btnPost1Click(Sender: TObject);
begin
  memoChat.Lines.Text := 'NEO'#13#10'Do you have any questions?'#13#10#13#10;
end;

procedure TMainForm.btnPostClick(Sender: TObject);
var
  TheAnswer: string;
  TheStoryId: Integer;
begin
  //post chat
  if(edtChatMsg.Text <> '') then
  begin
    memoChat.Lines.Add('USER');
    memoChat.Lines.Add(edtChatMsg.Text);
    memoChat.Lines.Add('');
    //generate answer
    TheStoryId := SQLQuery1.FieldByName('storyID').AsInteger;
    TheAnswer := TQAAlgorithm.FindAnswerInDB(SQLite3Connection1, edtChatMsg.Text, TheStoryId, chkChatUseQARules.Checked);
    memoChat.Lines.Add('NEO');
    if(TheAnswer <> '') then
      memoChat.Lines.Add(TheAnswer)
    else
      memoChat.Lines.Add('I don''t know yet, I am still learning..');
    memoChat.Lines.Add('');
    edtChatMsg.Text := '';
  end;
end;

procedure TMainForm.btnPredictAnswClick(Sender: TObject);
var
  TheQuery: TSQLQuery;
  TheQuestionId: integer;
  TheStoryId: integer;
  TheRule: string;
  TheAnswer: string;
  TheAnswerValue: Double;
  TheQA: TQAAlgorithm;
  TheQuestion: string;
  TheSplitter: TSentenceSplitter;
begin
  TheSplitter := nil;
  TheQuery := TSQLQuery.Create(nil);
  try
    TheSplitter := TSentenceSplitter.Create;
    TheQuery.DataBase := SQLite3Connection1;
    TheQuery.SQL.Add(
      'update qaTBL set guessans1=:guessans1 ' +
      'where storyID=:storyID and questionId=:questionId'
      );
    TheStoryId := SQLQuery1.FieldByName('storyID').AsInteger;
    TheQuery.ParamByName('storyID').AsInteger := TheStoryId;

    QATabQuery.First;
    while not QATabQuery.EOF do
    begin
      TheRule := QATabQuery.FieldByName('qarule').AsString;
      TheQuestion := QATabQuery.FieldByName('question').AsString;
      TheSplitter.SentenceSplitWords(TheQuestion);
      TheQA := TQAAlgorithm.Create(SQLite3Connection1, TheStoryId, TheRule);
      try
        TheQA.GetPropertyAnswer(TheSplitter.WordList, TheAnswer, TheAnswerValue, True);
      finally
        TheQA.Free;
      end;
      TheQuestionId := QATabQuery.FieldByName('questionId').AsInteger;

      TheQuery.ParamByName('questionId').AsInteger := TheQuestionId;
      TheQuery.ParamByName('guessans1').AsString := TheAnswer;
      TheQuery.ExecSQL;
      QATabQuery.Next;
    end;

    SQLTransaction1.CommitRetaining;

    QATabQuery.Close;
    QATabQuery.Open;
  finally
    TheQuery.Free;
    TheSplitter.Free;
  end;
  SQLQuery1AfterScroll(SQLQuery1);
end;

procedure TMainForm.btnPredictCRepsClick(Sender: TObject);
var
  TheQuery: TSQLQuery;
  TheSentenceId: integer;
  TheStoryId: integer;
  TheCRep: string;
  TheCRepGuess: string;
  TheTCRep: TCRep;
  TheRepresentation: string;
  TheRepCalculator: TRepCalculator;
begin
  TheTCRep := nil;
  TheRepCalculator := nil;
  TheQuery := TSQLQuery.Create(nil);
  try
    TheTCRep := TCRep.Create;
    TheRepCalculator := TRepCalculator.Create;

    TheQuery.DataBase := SQLite3Connection1;
    TheQuery.SQL.Add(
      'update sentenceTBL set crepguess1=:crepguess1 where ' +
      'storyID=:storyID and sentenceID=:sentenceID'
      );
    TheStoryId := SQLQuery1.FieldByName('storyID').AsInteger;
    TheQuery.ParamByName('storyID').AsInteger := TheStoryId;

    RepTabQuery.Last;
    scrollPredictAll.Max := RepTabQuery.RecordCount;
    scrollPredictAll.Position := 1;

    RepTabQuery.First;
    while not RepTabQuery.EOF do
    begin
      TheSentenceId := RepTabQuery.FieldByName('sentenceID').AsInteger;
      TheRepresentation := RepTabQuery.FieldByName('representation').AsString;
      TheCRep := RepTabQuery.FieldByName('context_rep').AsString;

      TheCRepGuess := TheTCRep.AddSentence(TheRepresentation);
      TheRepCalculator.AddSentence(TheCRep, TheCRepGuess, '', '', '');

      TheQuery.ParamByName('sentenceID').AsInteger := TheSentenceId;
      TheQuery.ParamByName('crepguess1').AsString := TheCRepGuess;
      TheQuery.ExecSQL;
      RepTabQuery.Next;
      scrollPredictAll.Position := scrollPredictAll.Position + 1;
      Application.ProcessMessages;
    end;
    scrollPredictAll.Position := 0;
    TheQuery.SQL.Clear;
    TheQuery.SQL.Add('update storyTBL set CRepAcc_100=:CRepAcc_100 where storyID=:storyID;');
    TheQuery.ParamByName('storyID').AsInteger := TheStoryId;
    TheQuery.ParamByName('CRepAcc_100').AsFloat := TheRepCalculator.RepAcc1;
    TheQuery.ExecSQL;

    SQLTransaction1.CommitRetaining;

    SQLQuery1.Edit;
    SQLQuery1.FieldByName('CRepAcc_100').AsFloat := TheRepCalculator.RepAcc1;
    SQLQuery1.Post;

    RepTabQuery.Close;
    RepTabQuery.Open;
  finally
    TheQuery.Free;
    TheTCRep.Free;
    TheRepCalculator.Free;
  end;
  SQLQuery1AfterScroll(SQLQuery1);
end;

procedure TMainForm.btnPredictAllRepsClick(Sender: TObject);
begin
  ClearAccuracyColors;
  Application.ProcessMessages;
  FPredictAllActive := True;
  SQLQuery1.DisableControls;
  try
    SQLQuery1.Last;
    SQLQuery1.First;
    scrollPredictAll.Max := SQLQuery1.RecordCount;
    scrollPredictAll.Position := 1;

    while not SQLQuery1.EOF do
    begin
      RepTabQuery.Close;
      RepTabQuery.ParamByName('storyId').AsInteger := SQLQuery1.FieldByName('storyId').AsInteger;
      RepTabQuery.Open;

      btnPredictRepsClick(btnPredictAllReps);
      SQLQuery1.Next;

      scrollPredictAll.Position := scrollPredictAll.Position + 1;
      Application.ProcessMessages;
    end;
    SQLQuery1.First;
    LoadStoryAverage(RepTabQuery.ParamByName('storyId').AsInteger, FStoryCount, False);
  finally
    FPredictAllActive := False;
    SQLQuery1.EnableControls;
  end;
  scrollPredictAll.Position := 0;
end;

procedure TMainForm.btnSearchClick(Sender: TObject);
var
  TheText: string;
begin
  TheText := Trim(edtSearch.Text);
  if TheText = '' then
    Exit;
  SearchSentenceQuery.Close;
  SearchSentenceQuery.Sql.Clear;
  SearchSentenceQuery.Sql.Text :=
    'select se.*, st.Title ' +
    'from SentenceTbl se LEFT JOIN StoryTBL st ON se.StoryID = st.StoryID ' +
    ' where se.sentence like :ASentence or st.title like :ATitle';
  SearchSentenceQuery.ParamByName('ASentence').AsString := '%' + TheText + '%';
  SearchSentenceQuery.ParamByName('ATitle').AsString := '%' + TheText + '%';
  SearchSentenceQuery.Open;
end;

procedure TMainForm.btnTestAddCrepClick(Sender: TObject);
begin
  memoCRepDestination.Lines.Add(FTestCRep.AddSentence(edtTestCrepRep.Text));
end;

procedure TMainForm.btnTestGenerateGRep1Click(Sender: TObject);
begin
  edtTabPOSpos.Text := TPosTagger.GetTagsForString(edtTabPOSSentence.text);
end;

procedure TMainForm.btnTestGenerateGRepClick(Sender: TObject);
  var
  TheGuesses: TRepGuessData;
  ThePos: string;
  TheQuery: TSQLQuery;
  TheSentence: string;
  TheRepresentation: string;
  TheRepCalculator: TRepCalculator;
  TheSentenceList: TSentenceList;
  TheSplitter: TSentenceSplitter;
begin
  // GENERATE G REP
  TheRepCalculator := nil;
  TheSentenceList := nil;
  TheSplitter := nil;
  TheQuery := TSQLQuery.Create(nil);
  try
    TheRepCalculator := TRepCalculator.Create;
    TheSplitter := TSentenceSplitter.Create;
    TheQuery.DataBase := SQLite3Connection1;
    TheQuery.SQL.Add('select sentenceID, sentence, representation, POS from sentenceTBL where trim(representation) <> '''';');
    TheQuery.Open;
    TheSentenceList := TSentenceList.Create(SQLite3Connection1);
    while not TheQuery.EOF do
    begin
      TheSentence := TheQuery.FieldByName('sentence').AsString;
      TheRepresentation := TheQuery.FieldByName('representation').AsString;
      ThePos := TheQuery.FieldByName('POS').AsString;
      TheSplitter.SentenceSplitWords(TheSentence);
      TheSentenceList.AddSentence(TheSplitter.WordList, TheSentence, TheRepresentation, '', ThePos);
      TheQuery.Next;
    end;
    TheQuery.Close;
    TheQuery.SQL.Clear;

    TheSentence := edtTestRepSentence.Text;
    ThePos := edtTestRepPOS.Text;
    TheRepresentation := edtTestRepRepresentation.Text;
    TheSplitter.SentenceSplitWords(TheSentence);

    TheGuesses := TheSentenceList.GetRepGuess(TheSplitter.WordList, TheSentence, ThePos, 1, False);

    TheRepCalculator.AddSentence(TheRepresentation, TheGuesses.RepGuessA,
        TheGuesses.RepGuessB, TheGuesses.RepGuessC, TheGuesses.RepGuessD);

     edtRepGuess1.Text := TheGuesses.RepGuessA;
     edtRepGuess2.Text := TheGuesses.RepGuessB;
     edtRepGuess3.Text := TheGuesses.RepGuessC;
     edtRepGuess4.Text := TheGuesses.RepGuessD;

     edtGuess1.Text := TheGuesses.MatchSentenceA;
     edtGuess2.Text := TheGuesses.MatchSentenceB;
     edtGuess3.Text := TheGuesses.MatchSentenceC;
     edtGuess4.Text := TheGuesses.MatchSentenceD;
  finally
    TheQuery.Free;
    TheSentenceList.Free;
    TheRepCalculator.Free;
    TheSplitter.Free;
  end;
end;

procedure TMainForm.btnTestQAClick(Sender: TObject);
var
  TheSplitter: TSentenceSplitter;
  TheString: string;
  TheValue: Double;
  I: Integer;
begin
  FDecoder.Clear;
  memoCrepDecoded.Lines.Clear;
  FDecoder.AddCrep(edtTestQaRep.Text);
  FDecoder.PostProcess;
  for I := 0 to FDecoder.PItems.Count - 1 do
    ShowNode(FDecoder.PItems.Objects[I] as TNode, FDecoder.PItems[I], 0);

  TheSplitter := TSentenceSplitter.Create;
  try
    memoCrepDecoded.Lines.Add('');
    TheSplitter.SentenceSplitWords(edtTestQaQuestion.Text);
    TheValue := 0;
    TheString := '';
    for I := 0 to TheSplitter.WordList.Count - 1 do
    begin
      FDecoder.GetBestScoreForWord(TheSplitter.WordList[I], TheSplitter.WordList, TheString, TheValue);
      memoCrepDecoded.Lines.Add(Format('%s = %s(%.2f)',[TheSplitter.WordList[I], TheString, TheValue]));
    end;
  finally
    TheSplitter.Free;
  end;
end;

procedure TMainForm.btnTestResetCrepClick(Sender: TObject);
begin
  FTestCRep.Clear;
end;

procedure TMainForm.btnTestTextClick(Sender: TObject);
var
  TheSAlg: TSentenceAlgorithm;
  TheScore: Double;
  TheSplitter: TSentenceSplitter;
  I: Integer;
begin
  if chkTestClearResults.Checked then
    memoTestWs.Lines.Clear;
  TheSplitter := nil;
  TheSAlg := TSentenceAlgorithm.Create(SQLite3Connection1);
  try
    TheSplitter := TSentenceSplitter.Create;
    TheSplitter.SentenceSplitWords(edtTestWs1.Text);
    TheSAlg.Element1 := TSentenceListElement.Create(TheSplitter.WordList, edtTestWs1.Text, '', '', edtTestPos1.Text);
    TheSplitter.SentenceSplitWords(edtTestWs2.Text);
    TheSAlg.Element2 := TSentenceListElement.Create(TheSplitter.WordList, edtTestWs2.Text, edtTestRep2.Text, '', edtTestPos2.Text);
    if Sender = btnTestText then
      memoTestWs.Lines.Add('Running Text align test:')
    else if Sender = btnTestPos then
      memoTestWs.Lines.Add('Running POS align test:')
    else if Sender = btnTestHPos then
      memoTestWs.Lines.Add('Running Hybrid POS align test:')
    else
      memoTestWs.Lines.Add('Running Hybrid Sem align test:');
    memoTestWs.Lines.Add('Texts:');
    memoTestWs.Lines.Add(edtTestWs1.Text);
    memoTestWs.Lines.Add(edtTestWs2.Text);
    memoTestWs.Lines.Add('');
    if Sender = btnTestText then
      TheScore := TheSAlg.TestRunTextMatch
    else if Sender = btnTestPos then
      TheScore := TheSAlg.TestRunPosMatch
    else if Sender = btnTestHPos then
      TheScore := TheSAlg.TestRunHybridPosMatch
    else
      TheScore := TheSAlg.TestRunHybridSemMatch;
    memoTestWs.Lines.Add('Score: ' + FloatToStr(TheScore));
    edtTestRep1.Text := TheSAlg.GetAdjustedRep(TheSAlg.Element2.Representation);
    memoTestWs.Lines.Add('Guess Rep: ' + edtTestRep1.Text);
    memoTestWs.Lines.Add('');
    for I := 0 to TheSAlg.AlignInList.Count - 1 do
      memoTestWs.Lines.Add(TheSAlg.AlignInList[I] + ' : ' + TheSAlg.AlignOutList[I]);
    memoTestWs.Lines.Add('---------- Semantic matrix ------------');
    memoTestWs.Lines.AddStrings(TheSAlg.ComparisonLog);
    memoTestWs.Lines.Add('----------       Done      ------------');
  finally
    TheSplitter.Free;
    TheSAlg.Element1.Free;
    TheSAlg.Element2.Free;
    TheSAlg.Free;
  end;
end;

procedure TMainForm.btnUpdateAllPOSClick(Sender: TObject);
var
  TheQuery: TSQLQuery;
  ThePos: string;
  TheSentence: string;
  TheTagger: TPosTagger;
begin
  ClearAccuracyColors;
  Application.ProcessMessages;
  FPredictAllActive := True;
  TheQuery := nil;
  SQLQuery1.DisableControls;
  try
    TheTagger := TPosTagger.Create;
    TheQuery := TSQLQuery.Create(nil);
    TheQuery.DataBase := SQLite3Connection1;
    TheQuery.SQL.Add('update sentenceTBL set pos = :strpos where sentenceID = :sentenceID');

    SQLQuery1.Last;
    SQLQuery1.First;
    scrollPredictAll.Max := SQLQuery1.RecordCount;
    scrollPredictAll.Position := 1;

    while not SQLQuery1.EOF do
    begin
      RepTabQuery.Close;
      RepTabQuery.ParamByName('storyId').AsInteger := SQLQuery1.FieldByName('storyId').AsInteger;
      RepTabQuery.Open;

      RepTabQuery.First;
      while not RepTabQuery.EOF do
      begin
        TheSentence := RepTabQuery.FieldByName('sentence').AsString;
        ThePos := TheTagger.DoGetTagsForString(TheSentence);
        TheQuery.ParamByName('sentenceID').AsInteger := RepTabQuery.FieldByName('sentenceID').AsInteger;
        TheQuery.ParamByName('strpos').AsString := ThePos;
        TheQuery.ExecSQL;
        RepTabQuery.Next;
      end;

      SQLQuery1.Next;
      scrollPredictAll.Position := scrollPredictAll.Position + 1;
      Application.ProcessMessages;
    end;
    SQLTransaction1.CommitRetaining;
    SQLQuery1.First;
    LoadStoryAverage(RepTabQuery.ParamByName('storyId').AsInteger, FStoryCount, False);
  finally
    FPredictAllActive := False;
    SQLQuery1.EnableControls;
    TheQuery.Free;
    TheTagger.Free;
  end;
  scrollPredictAll.Position := 0;
end;

procedure TMainForm.btDelStoryClick(Sender: TObject);
begin
  if not ConfirmDeleteMsg then
    Exit;

  Core.StoryDelete(FStory);
  SQLTransaction1.CommitRetaining;

  SQLQuery1.Close;
  SQLQuery1.Open;
  SQLQuery1.Last;
  RepTabQuery.Active := True;
  SQLQuery2.Open;
  RepTabQuery.Open;
  QATabQuery.Open;
end;

procedure TMainForm.Button2Click(Sender: TObject);
begin
  SQLTransaction1.RollbackRetaining;
end;

procedure TMainForm.Button3Click(Sender: TObject);
begin
  SQLQuery2.UpdateMode := upWhereAll;
  SQLQuery2.ApplyUpdates;
  SQLTransaction1.CommitRetaining;
end;

procedure TMainForm.Button4Click(Sender: TObject);
begin
  SQLTransaction1.RollbackRetaining;
end;

procedure TMainForm.ClearAccuracyColors;
begin
  edtStoryAccuracy1.Font.Style := [];
  edtStoryAccuracy1.Font.Color := clDefault;
  edtStoryAccuracy2.Font.Style := [];
  edtStoryAccuracy2.Font.Color := clDefault;
  edtStoryAccuracy3.Font.Style := [];
  edtStoryAccuracy3.Font.Color := clDefault;
  edtStoryAccuracy4.Font.Style := [];
  edtStoryAccuracy4.Font.Color := clDefault;
  edtSAccuracy1.Font.Style := [];
  edtSAccuracy1.Font.Color := clDefault;
  edtSAccuracy2.Font.Style := [];
  edtSAccuracy2.Font.Color := clDefault;
  edtSAccuracy3.Font.Style := [];
  edtSAccuracy3.Font.Color := clDefault;
  edtSAccuracy4.Font.Style := [];
  edtSAccuracy4.Font.Color := clDefault;
end;

procedure TMainForm.btnPredictRepsClick(Sender: TObject);
var
  TheGuesses: TRepGuessData;
  ThePos: string;
  TheQuery: TSQLQuery;
  TheSentence: string;
  TheSentenceId: integer;
  TheStoryId: integer;
  TheRepresentation: string;
  TheSemanticRepresentation: string;
  TheRepCalculator: TRepCalculator;
  TheRepCalculator25: TRepCalculator;
  TheRepCalculator50: TRepCalculator;
  TheSRepCalculator: TRepCalculator;
  TheSRepCalculator25: TRepCalculator;
  TheSRepCalculator50: TRepCalculator;
  TheSentenceList: TSentenceList;
  TheSplitter: TSentenceSplitter;
  TheTurboPredict: Boolean;
begin
  if Sender = btnPredictReps then
  begin
    ClearAccuracyColors;
    Application.ProcessMessages;
    TheTurboPredict := chkPredictRepTurbo.Checked;
  end
  else
    TheTurboPredict := chkPredictAllRepsTurbo.Checked;

  TheRepCalculator := nil;
  TheRepCalculator25 := nil;
  TheRepCalculator50 := nil;
  TheSRepCalculator := nil;
  TheSRepCalculator25 := nil;
  TheSRepCalculator50 := nil;
  TheSentenceList := nil;
  TheSplitter := nil;

  TheQuery := TSQLQuery.Create(nil);
  try
    TheSplitter := TSentenceSplitter.Create;
    TheRepCalculator := TRepCalculator.Create;
    TheRepCalculator25 := TRepCalculator.Create;
    TheRepCalculator50 := TRepCalculator.Create;
    TheSRepCalculator := TRepCalculator.Create;
    TheSRepCalculator25 := TRepCalculator.Create;
    TheSRepCalculator50 := TRepCalculator.Create;
    TheQuery.DataBase := SQLite3Connection1;
    TheQuery.SQL.Add('select sentenceID, sentence, representation, Semantic_Rep, POS from sentenceTBL where trim(representation) <> '''';');
    TheQuery.Open;
    TheSentenceList := TSentenceList.Create(SQLite3Connection1);
    while not TheQuery.EOF do
    begin
      TheSentence := TheQuery.FieldByName('sentence').AsString;
      TheRepresentation := TheQuery.FieldByName('representation').AsString;
      TheSemanticRepresentation := TheQuery.FieldByName('semantic_rep').AsString;
      ThePos := TheQuery.FieldByName('POS').AsString;
      TheSplitter.SentenceSplitWords(TheSentence);
      TheSentenceList.AddSentence(TheSplitter.WordList, TheSentence, TheRepresentation, TheSemanticRepresentation, ThePos);
      TheQuery.Next;
    end;
    TheQuery.Close;
    TheQuery.SQL.Clear;

    TheQuery.SQL.Add(
      'update sentenceTBL set repguess1=:repguess1, repguess2=:repguess2, ' +
      'repguess3=:repguess3, repguess4=:repguess4, ' +
      'srepguess1=:srepguess1, srepguess2=:srepguess2, ' +
      'srepguess3=:srepguess3, srepguess4=:srepguess4, ' +
      'guesses1=:guesses1, guesses2=:guesses2, guesses3=:guesses3, ' +
      'guesses4=:guesses4 where storyID=:storyID and sentenceID=:sentenceID'
      );
    TheStoryId := SQLQuery1.FieldByName('storyID').AsInteger;
    TheQuery.ParamByName('storyID').AsInteger := TheStoryId;

    if Sender = btnPredictReps then
    begin
      RepTabQuery.Last;
      scrollPredictAll.Max := RepTabQuery.RecordCount;
      scrollPredictAll.Position := 1;
    end;

    RepTabQuery.First;
    while not RepTabQuery.EOF do
    begin
      TheSentence := RepTabQuery.FieldByName('sentence').AsString;
      ThePos := RepTabQuery.FieldByName('POS').AsString;
      TheRepresentation := RepTabQuery.FieldByName('representation').AsString;
      TheSemanticRepresentation := RepTabQuery.FieldByName('semantic_rep').AsString;
      TheSentenceId := RepTabQuery.FieldByName('sentenceID').AsInteger;
      TheSplitter.SentenceSplitWords(TheSentence);

      TheGuesses := TheSentenceList.GetRepGuess(TheSplitter.WordList, TheSentence, ThePos, 1, TheTurboPredict);

      TheRepCalculator.AddSentence(TheRepresentation, TheGuesses.RepGuessA,
        TheGuesses.RepGuessB, TheGuesses.RepGuessC, TheGuesses.RepGuessD);
      TheSRepCalculator.AddSentence(TheSemanticRepresentation, TheGuesses.SRepGuessA,
        TheGuesses.SRepGuessB, TheGuesses.SRepGuessC, TheGuesses.SRepGuessD);

      TheQuery.ParamByName('sentenceID').AsInteger := TheSentenceId;
      TheQuery.ParamByName('repguess1').AsString := TheGuesses.RepGuessA;
      TheQuery.ParamByName('repguess2').AsString := TheGuesses.RepGuessB;
      TheQuery.ParamByName('repguess3').AsString := TheGuesses.RepGuessC;
      TheQuery.ParamByName('repguess4').AsString := TheGuesses.RepGuessD;
      TheQuery.ParamByName('guesses1').AsString := TheGuesses.MatchSentenceA;
      TheQuery.ParamByName('guesses2').AsString := TheGuesses.MatchSentenceB;
      TheQuery.ParamByName('guesses3').AsString := TheGuesses.MatchSentenceC;
      TheQuery.ParamByName('guesses4').AsString := TheGuesses.MatchSentenceD;

      if Trim(TheGuesses.SRepGuessA) = '' then
        TheQuery.ParamByName('srepguess1').AsString := '<BLANK>'
      else
        TheQuery.ParamByName('srepguess1').AsString := TheGuesses.SRepGuessA;

      if Trim(TheGuesses.SRepGuessB) = '' then
        TheQuery.ParamByName('srepguess2').AsString := '<BLANK>'
      else
        TheQuery.ParamByName('srepguess2').AsString := TheGuesses.SRepGuessB;

      if Trim(TheGuesses.SRepGuessC) = '' then
        TheQuery.ParamByName('srepguess3').AsString := '<BLANK>'
      else
        TheQuery.ParamByName('srepguess3').AsString := TheGuesses.SRepGuessC;

      if Trim(TheGuesses.SRepGuessD) = '' then
        TheQuery.ParamByName('srepguess4').AsString := '<BLANK>'
      else
        TheQuery.ParamByName('srepguess4').AsString := TheGuesses.SRepGuessD;

      TheQuery.ExecSQL;

      TheGuesses := TheSentenceList.GetRepGuess(TheSplitter.WordList, TheSentence, ThePos, 4, TheTurboPredict);
      TheRepCalculator25.AddSentence(TheRepresentation, TheGuesses.RepGuessA,
        TheGuesses.RepGuessB, TheGuesses.RepGuessC, TheGuesses.RepGuessD);
      TheSRepCalculator25.AddSentence(TheSemanticRepresentation, TheGuesses.SRepGuessA,
        TheGuesses.SRepGuessB, TheGuesses.SRepGuessC, TheGuesses.SRepGuessD);

      TheGuesses := TheSentenceList.GetRepGuess(TheSplitter.WordList, TheSentence, ThePos, 2, TheTurboPredict);
      TheRepCalculator50.AddSentence(TheRepresentation, TheGuesses.RepGuessA,
        TheGuesses.RepGuessB, TheGuesses.RepGuessC, TheGuesses.RepGuessD);
      TheSRepCalculator50.AddSentence(TheSemanticRepresentation, TheGuesses.SRepGuessA,
        TheGuesses.SRepGuessB, TheGuesses.SRepGuessC, TheGuesses.SRepGuessD);

      RepTabQuery.Next;
      if Sender = btnPredictReps then
        scrollPredictAll.Position := scrollPredictAll.Position + 1;
      Application.ProcessMessages;
    end;
    if Sender = btnPredictReps then
      scrollPredictAll.Position := 0;

    TheQuery.SQL.Clear;
    TheQuery.SQL.Add('update storyTBL set ' +
      'RepAcc1_25=:RepAcc1_25, RepAcc2_25=:RepAcc2_25, RepAcc3_25=:RepAcc3_25, RepAcc4_25=:RepAcc4_25, ' +
      'RepAcc1_50=:RepAcc1_50, RepAcc2_50=:RepAcc2_50, RepAcc3_50=:RepAcc3_50, RepAcc4_50=:RepAcc4_50, ' +
      'RepAcc1_100=:RepAcc1_100, RepAcc2_100=:RepAcc2_100, RepAcc3_100=:RepAcc3_100, RepAcc4_100=:RepAcc4_100, ' +
      'SRepAcc1_25=:SRepAcc1_25, SRepAcc2_25=:SRepAcc2_25, SRepAcc3_25=:SRepAcc3_25, SRepAcc4_25=:SRepAcc4_25, ' +
      'SRepAcc1_50=:SRepAcc1_50, SRepAcc2_50=:SRepAcc2_50, SRepAcc3_50=:SRepAcc3_50, SRepAcc4_50=:SRepAcc4_50, ' +
      'SRepAcc1_100=:SRepAcc1_100, SRepAcc2_100=:SRepAcc2_100, SRepAcc3_100=:SRepAcc3_100, SRepAcc4_100=:SRepAcc4_100 ' +
      ' where storyID=:storyID;');
    TheQuery.ParamByName('storyID').AsInteger := TheStoryId;
    TheQuery.ParamByName('RepAcc1_25').AsFloat := TheRepCalculator25.RepAcc1;
    TheQuery.ParamByName('RepAcc2_25').AsFloat := TheRepCalculator25.RepAcc2;
    TheQuery.ParamByName('RepAcc3_25').AsFloat := TheRepCalculator25.RepAcc3;
    TheQuery.ParamByName('RepAcc4_25').AsFloat := TheRepCalculator25.RepAcc4;
    TheQuery.ParamByName('RepAcc1_50').AsFloat := TheRepCalculator50.RepAcc1;
    TheQuery.ParamByName('RepAcc2_50').AsFloat := TheRepCalculator50.RepAcc2;
    TheQuery.ParamByName('RepAcc3_50').AsFloat := TheRepCalculator50.RepAcc3;
    TheQuery.ParamByName('RepAcc4_50').AsFloat := TheRepCalculator50.RepAcc4;
    TheQuery.ParamByName('RepAcc1_100').AsFloat := TheRepCalculator.RepAcc1;
    TheQuery.ParamByName('RepAcc2_100').AsFloat := TheRepCalculator.RepAcc2;
    TheQuery.ParamByName('RepAcc3_100').AsFloat := TheRepCalculator.RepAcc3;
    TheQuery.ParamByName('RepAcc4_100').AsFloat := TheRepCalculator.RepAcc4;
    TheQuery.ParamByName('SRepAcc1_25').AsFloat := TheSRepCalculator25.RepAcc1;
    TheQuery.ParamByName('SRepAcc2_25').AsFloat := TheSRepCalculator25.RepAcc2;
    TheQuery.ParamByName('SRepAcc3_25').AsFloat := TheSRepCalculator25.RepAcc3;
    TheQuery.ParamByName('SRepAcc4_25').AsFloat := TheSRepCalculator25.RepAcc4;
    TheQuery.ParamByName('SRepAcc1_50').AsFloat := TheSRepCalculator50.RepAcc1;
    TheQuery.ParamByName('SRepAcc2_50').AsFloat := TheSRepCalculator50.RepAcc2;
    TheQuery.ParamByName('SRepAcc3_50').AsFloat := TheSRepCalculator50.RepAcc3;
    TheQuery.ParamByName('SRepAcc4_50').AsFloat := TheSRepCalculator50.RepAcc4;
    TheQuery.ParamByName('SRepAcc1_100').AsFloat := TheSRepCalculator.RepAcc1;
    TheQuery.ParamByName('SRepAcc2_100').AsFloat := TheSRepCalculator.RepAcc2;
    TheQuery.ParamByName('SRepAcc3_100').AsFloat := TheSRepCalculator.RepAcc3;
    TheQuery.ParamByName('SRepAcc4_100').AsFloat := TheSRepCalculator.RepAcc4;
    TheQuery.ExecSQL;

    SQLTransaction1.CommitRetaining;

    SQLQuery1.Edit;
    SQLQuery1.FieldByName('RepAcc1_100').AsFloat := TheRepCalculator.RepAcc1;
    SQLQuery1.FieldByName('RepAcc2_100').AsFloat := TheRepCalculator.RepAcc2;
    SQLQuery1.FieldByName('RepAcc3_100').AsFloat := TheRepCalculator.RepAcc3;
    SQLQuery1.FieldByName('RepAcc4_100').AsFloat := TheRepCalculator.RepAcc4;
    SQLQuery1.FieldByName('SRepAcc1_100').AsFloat := TheSRepCalculator.RepAcc1;
    SQLQuery1.FieldByName('SRepAcc2_100').AsFloat := TheSRepCalculator.RepAcc2;
    SQLQuery1.FieldByName('SRepAcc3_100').AsFloat := TheSRepCalculator.RepAcc3;
    SQLQuery1.FieldByName('SRepAcc4_100').AsFloat := TheSRepCalculator.RepAcc4;
    SQLQuery1.Post;

    RepTabQuery.Close;
    RepTabQuery.Open;
  finally
    TheQuery.Free;
    TheSentenceList.Free;
    TheRepCalculator25.Free;
    TheRepCalculator50.Free;
    TheRepCalculator.Free;
    TheSplitter.Free;
  end;
  SQLQuery1AfterScroll(SQLQuery1);
  if Sender = btnPredictReps then
    LoadStoryAverage(FMaxStoryId, FStoryCount, False);
end;

function TMainForm.GetLastRowID: integer;
var
  qry: TSQLQuery;
begin
  qry := TSQLQuery.Create(nil);
  try
    qry.DataBase := SQLite3Connection1;
    qry.Transaction := SQLTransaction1;

    qry.SQL.Text := 'SELECT LAST_INSERT_ROWID() AS rowid';
    qry.Open;

    Result := qry.Fields[0].AsInteger;
  finally
    qry.Free;
  end;

end;

procedure TMainForm.btnEditClick(Sender: TObject);
begin
  FStory := Core.StoryLoadFullById(SQLQuery1.FieldByName('storyId').AsInteger);
  if TEditStoryForm.ExecuteModify(FStory) then
  begin
    SQLQuery1.Edit;
    SQLQuery1.FieldByName('title').AsString := FStory.Title;
    SQLQuery1.FieldByName('body').AsString := FStory.Body;
    SQLQuery1.Post;

    RepTabQuery.Close;
    RepTabQuery.Open;
    SQLQuery1AfterScroll(SQLQuery1);
  end;
end;

procedure TMainForm.Button5Click(Sender: TObject);
var
  TheText: string;
  TheLastId: Integer;
begin
  FPredictAllActive := True;
  try
    SQLQuery1.Last;
    FStoryCount := SQLQuery1.RecordCount;
    TheLastId := SQLQuery1.FieldByName('storyId').AsInteger;
    SQLQuery1.First;
  finally
    FPredictAllActive := False;
  end;
  repeat
    TheText := IntToStr(TheLastId);
    TheText := InputBox('Last story id to consider', 'Enter last story Id to consider:', TheText);
  until TheText = IntToStr(StrToInt(TheText));
  LoadStoryAverage(StrToInt(TheText), FStoryCount, False);
end;

procedure TMainForm.Button6Click(Sender: TObject);
begin

end;

procedure TMainForm.ShowNode(ANode: TNode; const AName: string; ATabCount: Integer);
var
  TheString: string;
  I: Integer;
begin
  TheString := '';

  for I := 0 to ATabCount - 1 do
    TheString := TheString + #9;  // + '    ';
  TheString := TheString + upcase(AName);

  if ANode.DisplayValue <> '' then
//  TheString := upcase(TheString) + #9  /// + '( = ' + ANode.DisplayValue + ' )
  TheString := TheString + #9 + ANode.DisplayValue
  else
    TheString := TheString + #9 + 'null';
  memoCrepDecoded.Lines.Add(TheString);
  for I := 0 to ANode.SubKeys.Count - 1 do
    ShowNode(ANode.SubKeys.Objects[I] as TNode, ANode.SubKeys[I], ATabCount + 1);
end;

procedure TMainForm.DBGrid3DrawColumnCell(Sender: TObject; const Rect: TRect;
  DataCol: Integer; Column: TColumn; State: TGridDrawState);
var
  TheDbGrid: TDBGrid;
  TheAc1, TheAc2, TheAc3, TheAc4, TheMax: Float;
begin
  TheDbGrid := Sender as TDBGrid;
  if Column.FieldName = 'AvgRep' then
    TheDbGrid.Canvas.Font.Style := [fsBold];

  TheAc1 := SQLQuery1.FieldByName('RepAcc1_100').AsFloat;
  TheAc2 := SQLQuery1.FieldByName('RepAcc2_100').AsFloat;
  TheAc3 := SQLQuery1.FieldByName('RepAcc3_100').AsFloat;
  TheAc4 := SQLQuery1.FieldByName('RepAcc4_100').AsFloat;
  TheMax := Max(Max(TheAc1, TheAc2), Max(TheAc3, TheAc4));

  if (Column.FieldName = 'RepAcc1_100') and (Column.Field.AsFloat = TheMax) then
  begin
    TheDbGrid.Canvas.Font.Style := [fsBold];
    TheDbGrid.Canvas.Font.Color := clBlue;
  end;

  if (Column.FieldName = 'RepAcc2_100') and (Column.Field.AsFloat = TheMax) then
  begin
    TheDbGrid.Canvas.Font.Style := [fsBold];
    TheDbGrid.Canvas.Font.Color := clBlue;
  end;

  if (Column.FieldName = 'RepAcc3_100') and (Column.Field.AsFloat = TheMax) then
  begin
    TheDbGrid.Canvas.Font.Style := [fsBold];
    TheDbGrid.Canvas.Font.Color := clBlue;
  end;

  if (Column.FieldName = 'RepAcc4_100') and (Column.Field.AsFloat = TheMax) then
  begin
    TheDbGrid.Canvas.Font.Style := [fsBold];
    TheDbGrid.Canvas.Font.Color := clBlue;
  end;

  TheDbGrid.DefaultDrawColumnCell(Rect, DataCol, Column, State);
end;

procedure TMainForm.edtChatMsgKeyUp(Sender: TObject; var Key: Word;
  Shift: TShiftState);
begin
  if Key = 13 then
    btnPostClick(btnPost);
end;

procedure TMainForm.edtSearchKeyUp(Sender: TObject; var Key: Word;
  Shift: TShiftState);
begin
  if Key = 13 then
    btnSearchClick(Sender);
end;

procedure TMainForm.edtStoryNumberEnter(Sender: TObject);
begin
  edtStoryNumber.SelectAll;
end;

procedure TMainForm.edtStoryNumberExit(Sender: TObject);
var
  TheNumber: Integer;
begin
  TheNumber := StrToIntDef(edtStoryNumber.Text, 0);
  if edtStoryNumber.Text <> IntToStr(TheNumber) then
  begin
    edtStoryNumber.Text := IntToStr(SQLQuery1.RecNo);
  end else
  begin
    try
      SQLQuery1.RecNo := TheNumber;
    finally
      edtStoryNumber.Text := IntToStr(SQLQuery1.RecNo);
    end;
  end;
end;

procedure TMainForm.edtStoryNumberKeyUp(Sender: TObject; var Key: Word;
  Shift: TShiftState);
begin
  if Key = 13 then
    edtStoryNumberExit(Sender);
end;

procedure TMainForm.edtTestRepSentenceExit(Sender: TObject);
begin
  edtTestRepPOS.Text := TPosTagger.GetTagsForString(edtTestRepSentence.text);
end;

procedure TMainForm.edtTestWs1Exit(Sender: TObject);
begin
  edtTestPos1.Text := TPosTagger.GetTagsForString(edtTestWs1.Text);
end;

procedure TMainForm.edtTestWs2Exit(Sender: TObject);
begin
  edtTestPos2.Text := TPosTagger.GetTagsForString(edtTestWs2.Text);
end;

end.
