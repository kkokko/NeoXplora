unit CoreUnit;

{$mode objfpc}{$H+}

interface

uses
  Classes, SysUtils, sqlite3conn, sqldb, StoryObject, ProtoObject,
  SentenceObject, EntityList, QAObject;

type

  { TCore }

  TCore = class
  private
    FDatabase: TSQLite3Connection;
    FDatabaseVersion: Integer;
    FQuery: TSQLQuery;
    class var
      FInstance: TCore;
    function GetDatabaseVersion: Integer;
    class function GetLastInsertedId(AQuery: TSQLQuery): Integer;
    procedure SetDatabase(AValue: TSQLite3Connection);
  public
    constructor Create;
    destructor Destroy; override;

    class function GetInstance: TCore;

    procedure ExecuteSQL(const AnSql: string);
    function GetLastInsertedId: Integer;


    // load all the protos for the storyId
    procedure ProtosLoadByStoryId(AnId: Integer; AResultList: TEntityList);
    // insert into proto table
    procedure ProtoInsert(AProto: TProtoObject);

    // delete all the QA records for storyID
    procedure QaDeleteForStoryId(AnId: Integer);
    // insert into QA table
    procedure QaInsert(AQa: TQaObject);
    // load all the Qas for the storyId
    procedure QasLoadByStoryId(AnId: Integer; AResultList: TEntityList);

    // load all the sentences for the storyId
    procedure SentencesLoadByStoryId(AnId: Integer; AResultList: TEntityList);
    // insert into sentence table
    procedure SentenceInsert(ASentence: TSentenceObject);

    // returns true if another story with same title exists
    function StoryCheckTitleExists(const ATitle: string; AnOwnId: Integer): Boolean;
    // delete the story
    procedure StoryDelete(AStory: TStoryObject);
    // delete all the sentences and protosentences for a story
    procedure StoryDeleteAllSentences(AStory: TStoryObject);
    // get first story id with title
    function StoryGetIdByTitle(const ATitle: string): Integer;
    // insert into story table only
    procedure StoryInsert(AStory: TStoryObject);
    // load all the stories
    procedure StoryLoadAll(AResultList: TEntityList; AStartIndex, ACount: Integer);
    // load story object only
    function StoryLoadById(AnId: Integer): TStoryObject;
    // load story, sentences, protos, qa's
    function StoryLoadFullById(AnId: Integer): TStoryObject;
    // update or create a story and re-create sentences, proto-sentences
    procedure StoryUpdateEverything(AStory: TStoryObject);
    // update importId for storyId
    procedure StoryUpdateImportId(AStoryId, AMysqlId: Integer);
    // update the title of an existing story
    procedure StoryUpdate(AStory: TStoryObject);

    property Database: TSQLite3Connection read FDatabase write SetDatabase;
    property DatabaseVersion: Integer read GetDatabaseVersion;
  end;

function Core: TCore;

implementation

function Core: TCore;
begin
  Result := TCore.GetInstance;
end;

{ TCore }

function TCore.GetLastInsertedId: Integer;
begin
  Result := 0;
  FQuery.SQL.Clear;
  FQuery.SQL.Add('SELECT LAST_INSERT_ROWID() AS rowid');
  FQuery.Open;
  try
    if not FQuery.Eof then
      Result := FQuery.FieldByName('rowid').AsInteger;
  finally
    FQuery.Close;
  end;
end;

procedure TCore.ProtosLoadByStoryId(AnId: Integer; AResultList: TEntityList);
var
  TheProto: TProtoObject;
begin
  FQuery.SQL.Clear;
  FQuery.SQL.Add('select * from protoTBL where storyId = :AStoryId;');
  FQuery.ParamByName('AStoryId').AsInteger := AnId;
  try
    FQuery.Open;
    while not FQuery.EOF do
    begin
      TheProto := TProtoObject.Create;
      TheProto.Id := FQuery.FieldByName('prId').AsInteger;
      TheProto.Level := FQuery.FieldByName('level').AsInteger;
      TheProto.Name := FQuery.FieldByName('name').AsString;
      TheProto.StoryId := AnId;
      AResultList.Add(TheProto);
      FQuery.Next;
    end;
  finally
    FQuery.Close;
  end;
end;

procedure TCore.ProtoInsert(AProto: TProtoObject);
begin
  FQuery.SQL.Clear;
  FQuery.SQL.Add('insert into `protoTBL`(name, level, storyId) values(:AName, :ALevel, :AStoryId);');
  FQuery.ParamByName('AName').AsString := AProto.Name;
  FQuery.ParamByName('ALevel').AsInteger := AProto.Level;
  FQuery.ParamByName('AStoryId').AsInteger := AProto.StoryId;
  FQuery.ExecSQL;
  AProto.Id := GetLastInsertedId;
end;

procedure TCore.QaDeleteForStoryId(AnId: Integer);
begin
  FQuery.SQL.Clear;
  FQuery.SQL.Add('delete from qaTBL where storyId = :AStoryId;');
  FQuery.ParamByName('AStoryId').AsInteger := AnId;
  FQuery.ExecSQL;
end;

procedure TCore.QaInsert(AQa: TQaObject);
begin
  FQuery.SQL.Clear;
  FQuery.SQL.Add(
    'insert into qaTBL(storyID, question, answer, qarule) ' +
    'VALUES(:AStoryId, :AQuestion, :AnAnswer, :ARule);'
  );
  FQuery.ParamByName('AStoryId').AsInteger := AQa.StoryId;
  FQuery.ParamByName('AQuestion').AsString := AQa.Question;
  FQuery.ParamByName('AnAnswer').AsString := AQa.Answer;
  FQuery.ParamByName('ARule').AsString := AQa.QARule;
  FQuery.ExecSQL;
  AQa.Id := GetLastInsertedId;
end;

procedure TCore.QasLoadByStoryId(AnId: Integer; AResultList: TEntityList);
var
  TheQa: TQaObject;
begin
  FQuery.SQL.Clear;
  FQuery.SQL.Add('select * from qaTBL where storyId = :AStoryId;');
  FQuery.ParamByName('AStoryId').AsInteger := AnId;
  try
    FQuery.Open;
    while not FQuery.EOF do
    begin
      TheQa := TQaObject.Create;
      TheQa.Id := FQuery.FieldByName('questionID').AsInteger;
      TheQa.Answer := FQuery.FieldByName('answer').AsString;
      TheQa.QARule := FQuery.FieldByName('qarule').AsString;
      TheQa.Question := FQuery.FieldByName('question').AsString;
      TheQa.StoryId := AnId;
      AResultList.Add(TheQa);
      FQuery.Next;
    end;
  finally
    FQuery.Close;
  end;
end;

procedure TCore.SentencesLoadByStoryId(AnId: Integer; AResultList: TEntityList);
var
  TheSentence: TSentenceObject;
begin
  FQuery.SQL.Clear;
  FQuery.SQL.Add('select * from sentenceTBL where storyId = :AStoryId;');
  FQuery.ParamByName('AStoryId').AsInteger := AnId;
  try
    FQuery.Open;
    while not FQuery.EOF do
    begin
      TheSentence := TSentenceObject.Create;
      TheSentence.ContextRep := FQuery.FieldByName('context_rep').AsString;
      TheSentence.Id := FQuery.FieldByName('sentenceID').AsInteger;
      TheSentence.Name := FQuery.FieldByName('sentence').AsString;
      TheSentence.POS := FQuery.FieldByName('POS').AsString;
      TheSentence.Proto1Id := FQuery.FieldByName('proto1Id').AsInteger;
      TheSentence.Proto2Id := FQuery.FieldByName('proto2Id').AsInteger;
      TheSentence.Representation := FQuery.FieldByName('representation').AsString;
      TheSentence.SemanticRep := FQuery.FieldByName('Semantic_rep').AsString;
      TheSentence.StoryId := AnId;
      AResultList.Add(TheSentence);
      FQuery.Next;
    end;
  finally
    FQuery.Close;
  end;
end;

procedure TCore.SentenceInsert(ASentence: TSentenceObject);
begin
  FQuery.SQL.Clear;
  FQuery.SQL.Add(
    'insert into sentenceTBL(storyID, sentence, proto1Id, proto2Id, ' +
    'representation, context_rep, POS, Semantic_rep) ' +
    'VALUES(:AStoryId, :ASent, :AProto1Id, :AProto2Id, :ARep, :ACRep, :APos, :ASemRep);'
  );
  FQuery.ParamByName('AStoryId').AsInteger := ASentence.StoryId;
  FQuery.ParamByName('ASent').AsString := ASentence.Name;
  FQuery.ParamByName('AProto1Id').AsInteger := ASentence.Proto1Id;
  FQuery.ParamByName('AProto2Id').AsInteger := ASentence.Proto2Id;
  FQuery.ParamByName('ARep').AsString := ASentence.Representation;
  FQuery.ParamByName('ACRep').AsString := ASentence.ContextRep;
  FQuery.ParamByName('APos').AsString := ASentence.POS;
  FQuery.ParamByName('ASemRep').AsString := ASentence.SemanticRep;
  FQuery.ExecSQL;
  ASentence.Id := GetLastInsertedId;
end;

function TCore.StoryCheckTitleExists(const ATitle: string; AnOwnId: Integer): Boolean;
begin
  Result := False;
  FQuery.SQL.Clear;
  FQuery.SQL.Add('select count(*) number from storyTBL where title = :ATitle and storyId <> :AStoryId;');
  FQuery.ParamByName('ATitle').AsString := ATitle;
  FQuery.ParamByName('AStoryId').AsInteger := AnOwnId;
  try
    FQuery.Open;
    if not FQuery.EOF then
      Result := FQuery.FieldByName('number').AsInteger > 0;
  finally
    FQuery.Close;
  end;
end;

procedure TCore.StoryDelete(AStory: TStoryObject);
begin
  FQuery.SQL.Clear;
  FQuery.SQL.Add('delete from storyTBL where storyId = :storyId;');
  FQuery.ParamByName('storyId').AsInteger := AStory.Id;
  FQuery.ExecSQL;

  StoryDeleteAllSentences(AStory);
  QaDeleteForStoryId(AStory.Id);
end;

function TCore.GetDatabaseVersion: Integer;
begin
  if FDatabaseVersion = -1 then
  begin
    FDatabaseVersion := 0;
    FQuery.SQL.Clear;
    FQuery.SQL.Add('select `Value` from `zapp` where `key`= ''version''');
    try
      try
        FQuery.Open;
        if not FQuery.Eof then
          FDatabaseVersion := StrToIntDef(FQuery.FieldByName('Value').AsString, 0);
      except // eat the exception, it will error when table is missing
      end;
    finally
      FQuery.Close;
    end;
  end;
  Result := FDatabaseVersion;
end;

class function TCore.GetLastInsertedId(AQuery: TSQLQuery): Integer;
begin
  Result := 0;
  AQuery.SQL.Clear;
  AQuery.SQL.Add('SELECT LAST_INSERT_ROWID() AS rowid');
  AQuery.Open;
  try
    if not AQuery.Eof then
      Result := AQuery.Fields[0].AsInteger;
  finally
    AQuery.Close;
  end;
end;

procedure TCore.SetDatabase(AValue: TSQLite3Connection);
begin
  FDatabase := AValue;
  FQuery.Database := AValue;
end;

constructor TCore.Create;
begin
  FQuery := TSQLQuery.Create(nil);
  FDatabaseVersion := -1;
end;

destructor TCore.Destroy;
begin
  FQuery.Free;
  inherited Destroy;
end;

class function TCore.GetInstance: TCore;
begin
  if not Assigned(FInstance) then
    FInstance := TCore.Create;
  Result := FInstance;
end;

procedure TCore.ExecuteSQL(const AnSql: string);
begin
  FQuery.SQL.Clear;
  FQuery.SQL.Add(AnSql);
  FQuery.ExecSQL;
end;

procedure TCore.StoryDeleteAllSentences(AStory: TStoryObject);
begin
  FQuery.SQL.Clear;
  FQuery.SQL.Add('delete from sentenceTBL where storyId = :AStoryId');
  FQuery.ParamByName('AStoryId').AsInteger := AStory.Id;
  FQuery.ExecSQL;
  FQuery.SQL.Clear;
  FQuery.SQL.Add('delete from protoTBL where storyId = :AStoryId');
  FQuery.ParamByName('AStoryId').AsInteger := AStory.Id;
  FQuery.ExecSQL;
end;

function TCore.StoryGetIdByTitle(const ATitle: string): Integer;
begin
  Result := 0;
  FQuery.SQL.Clear;
  FQuery.SQL.Add('select storyId from storyTBL where title = :ATitle limit 0, 1;');
  FQuery.ParamByName('ATitle').AsString := ATitle;
  try
    FQuery.Open;
    if not FQuery.EOF then
      Result := FQuery.FieldByName('storyId').AsInteger;
  finally
    FQuery.Close;
  end;
end;

procedure TCore.StoryInsert(AStory: TStoryObject);
begin
  FQuery.SQL.Clear;
  FQuery.SQL.Add(
    'insert into storyTBL(ImportId, title, body, username, categoryId) ' +
    'VALUES(:AnImportId, :ATitle, :ABody, :AUser, :ACategoryId);'
  );
  FQuery.ParamByName('AnImportId').AsInteger := AStory.MySqlId;
  FQuery.ParamByName('ATitle').AsString := AStory.Title;
  FQuery.ParamByName('ABody').AsString := AStory.Body;
  FQuery.ParamByName('AUser').AsString := AStory.User;
  FQuery.ParamByName('ACategoryId').AsInteger := AStory.CategoryId;
  FQuery.ExecSQL;
  AStory.Id := GetLastInsertedId(FQuery);
end;

procedure TCore.StoryLoadAll(AResultList: TEntityList; AStartIndex, ACount: Integer);
var
  TheStory: TStoryObject;
  I: Integer;
begin
  FQuery.SQL.Clear;
  FQuery.SQL.Add('select * from storyTBL order by storyId limit :AStartIndex, :ACount;');
  FQuery.ParamByName('AStartIndex').AsInteger := AStartIndex;
  FQuery.ParamByName('ACount').AsInteger := ACount;
  try
    FQuery.Open;
    while not FQuery.EOF do
    begin
      TheStory := TStoryObject.Create;
      TheStory.Body := FQuery.FieldByName('body').AsString;
      TheStory.CategoryId := FQuery.FieldByName('categoryID').AsInteger;
      TheStory.Id := FQuery.FieldByName('storyID').AsInteger;
      TheStory.MySqlId := FQuery.FieldByName('ImportId').AsInteger;
      TheStory.Title := FQuery.FieldByName('title').AsString;
      TheStory.User := FQuery.FieldByName('username').AsString;
      AResultList.Add(TheStory);
      FQuery.Next;
    end;
  finally
    FQuery.Close;
  end;
  for I := 0 to AResultList.Count - 1 do
  begin
    TheStory := AResultList[I] as TStoryObject;
    ProtosLoadByStoryId(TheStory.Id, TheStory.Protos);
    SentencesLoadByStoryId(TheStory.Id, TheStory.Sentences);
    QasLoadByStoryId(TheStory.Id, TheStory.Qas)
  end;
end;

function TCore.StoryLoadById(AnId: Integer): TStoryObject;
begin
  FQuery.SQL.Clear;
  FQuery.SQL.Add('select * from storyTBL where storyId = :AStoryId;');
  FQuery.ParamByName('AStoryId').AsInteger := AnId;
  try
    FQuery.Open;
    Result := TStoryObject.Create;
    Result.Body := FQuery.FieldByName('body').AsString;
    Result.CategoryId := FQuery.FieldByName('categoryID').AsInteger;
    Result.Id := AnId;
    Result.MySqlId := FQuery.FieldByName('ImportId').AsInteger;
    Result.Title := FQuery.FieldByName('title').AsString;
    Result.User := FQuery.FieldByName('username').AsString;
  finally
    FQuery.Close;
  end;
end;

function TCore.StoryLoadFullById(AnId: Integer): TStoryObject;
begin
  Result := StoryLoadById(AnId);
  ProtosLoadByStoryId(AnId, Result.Protos);
  SentencesLoadByStoryId(AnId, Result.Sentences);
  QasLoadByStoryId(AnId, Result.Qas)
end;

procedure TCore.StoryUpdateEverything(AStory: TStoryObject);
var
  I: Integer;
begin
  if AStory.Id  <> 0 then
    StoryUpdate(AStory)
  else
    StoryInsert(AStory);
  AStory.CascadeStoryId;
  StoryDeleteAllSentences(AStory);
  for I := 0 to AStory.Protos.Count - 1 do
    ProtoInsert(AStory.Protos[I] as TProtoObject);
  AStory.UpdateProtoIds;
  for I := 0 to AStory.Sentences.Count - 1 do
    SentenceInsert(AStory.Sentences[I] as TSentenceObject);
  QaDeleteForStoryId(AStory.Id);
  for I := 0 to AStory.Qas.Count - 1 do
    QaInsert(AStory.Qas[I] as TQAObject);
end;

procedure TCore.StoryUpdateImportId(AStoryId, AMysqlId: Integer);
begin
  FQuery.SQL.Clear;
  FQuery.SQL.Add('UPDATE storyTBL SET ImportId = :AnImportId WHERE storyId = :AStoryId');
  FQuery.ParamByName('AnImportId').AsInteger := AStoryId;
  FQuery.ParamByName('AStoryId').AsInteger := AMysqlId;
  FQuery.ExecSQL;
end;

procedure TCore.StoryUpdate(AStory: TStoryObject);
begin
  FQuery.SQL.Clear;
  FQuery.SQL.Add(
    'update storyTBL set ImportId = :AnImportId, title = :ATitle, '+
    'body = :ABody, username = :AUser, categoryId = :ACategoryId ' +
    'where storyId = :AStoryId'
  );
  FQuery.ParamByName('AnImportId').AsInteger := AStory.MySqlId;
  FQuery.ParamByName('ATitle').AsString := AStory.Title;
  FQuery.ParamByName('ABody').AsString := AStory.Body;
  FQuery.ParamByName('AUser').AsString := AStory.User;
  FQuery.ParamByName('ACategoryId').AsInteger := AStory.CategoryId;
  FQuery.ParamByName('AStoryId').AsInteger := AStory.Id;
  FQuery.ExecSQL;
end;

end.

