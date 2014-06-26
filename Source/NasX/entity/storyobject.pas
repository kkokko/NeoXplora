unit StoryObject;

{$mode objfpc}{$H+}

interface

uses
  Entity, EntityList;

type
  { TStoryObject }

  TStoryObject = class(TEntity)
  private
    FBody: string;
    FCanOverwrite: Boolean;
    FCategoryId: Integer;
    FMySqlId: Integer;
    FProtos: TEntityList;
    FQA: TEntityList;
    FSentences: TEntityList;
    FTitle: string;
    FUser: string;
  public
    // will set StoryId for Sentences, Protos and Qas
    procedure CascadeStoryId;
    procedure CreateNewProtoSentence(const ASentence: string);
    // sum up Proto lvl 1'st and create body
    procedure UpdateBodyFromProto;
    // update sentence.ProtoIds using the protoObjects
    procedure UpdateProtoIds;
    // update protoObjects using the ProtoIds
    procedure UpdateProtoObjects;
    // updates the Proto.Sentences arrays based on Sentence.ProtoObject
    procedure UpdateSentenceLinks;
  published
    // serialized properties
    property Body: string read FBody write FBody;
    property CategoryId: Integer read FCategoryId write FCategoryId;
    property CanOverwrite: Boolean read FCanOverwrite write FCanOverwrite;
    property Id;
    property MySqlId: Integer read FMySqlId write FMySqlId;
    property Protos: TEntityList read FProtos write FProtos; // array of ProtoObject
    property Qas: TEntityList read FQA write FQA; // array of QAObject
    property Sentences: TEntityList read FSentences write FSentences; // array of SentenceObject
    property Title: string read FTitle write FTitle;
    property User: string read FUser write FUser;
  end;

implementation

uses
  SentenceObject, ProtoObject, SysUtils, QAObject;

{ TStoryObject }

procedure TStoryObject.CascadeStoryId;
var
  I: Integer;
begin
  for I := 0 to Protos.Count - 1 do
    (Protos[I] as TProtoObject).StoryId := Id;
  for I := 0 to Sentences.Count - 1 do
    (Sentences[I] as TSentenceObject).StoryId := Id;
  for I := 0 to Qas.Count - 1 do
    (Qas[I] as TQaObject).StoryId := Id;
end;

procedure TStoryObject.CreateNewProtoSentence(const ASentence: string);
var
  TheSentence: TSentenceObject;
  TheProtoLvl1: TProtoObject;
  TheProtoLvl2: TProtoObject;
begin
  TheProtoLvl1 := TProtoObject.Create(ASentence, 1);
  Protos.Add(TheProtoLvl1);
  TheProtoLvl2 := TProtoObject.Create(ASentence, 2);
  Protos.Add(TheProtoLvl2);
  TheSentence := TSentenceObject.Create(ASentence, TheProtoLvl1, TheProtoLvl2);
  Sentences.Add(TheSentence);
end;

procedure TStoryObject.UpdateBodyFromProto;
var
  TheProto: TProtoObject;
  TheString: string;
  I: Integer;
begin
  FBody := '';
  for I := 0 to Protos.Count - 1 do
  begin
    TheProto := Protos[I] as TProtoObject;
    if TheProto.Level <> 1 then
      Continue;
    TheString := TheProto.Name;
    if Trim(TheString) = '' then
      Continue;
    if not (TheString[Length(TheString)] in ['?', '!']) then
      TheString := TheString + '.';
    if FBody <> '' then
      FBody := FBody + ' ';
    FBody := FBody + TheString;
  end;
end;

procedure TStoryObject.UpdateProtoIds;
var
  I: Integer;
begin
  for I := 0 to Sentences.Count - 1 do
    (Sentences[I] as TSentenceObject).UpdateProtoIds;
end;

procedure TStoryObject.UpdateProtoObjects;
var
  TheSentence: TSentenceObject;
  I: Integer;
begin
  for I := 0 to Sentences.Count - 1 do
  begin
    TheSentence := Sentences[I] as TSentenceObject;
    TheSentence.Proto1Object := Protos.FindFirstWithId(TheSentence.Proto1Id);
    TheSentence.Proto2Object := Protos.FindFirstWithId(TheSentence.Proto2Id);
  end;
end;

procedure TStoryObject.UpdateSentenceLinks;
var
  TheProto: TProtoObject;
  TheSentence: TSentenceObject;
  I: Integer;
begin
  for I := 0 to Protos.Count - 1 do
  begin
    TheProto := Protos[I] as TProtoObject;
    TheProto.Sentences.Clear;
  end;
  for I := 0 to Sentences.Count - 1 do
  begin
    TheSentence := Sentences[I] as TSentenceObject;
    TheProto := TheSentence.Proto2Object as TProtoObject;
    if TheProto = nil then
      Continue;
    TheProto.Sentences.Add(TheSentence);
  end;
end;

initialization
  TStoryObject.RegisterEntityClass;

end.

