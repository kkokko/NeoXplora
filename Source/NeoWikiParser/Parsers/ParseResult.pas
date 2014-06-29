unit ParseResult;

interface

uses
  Entity, EntityList, StoryBase;

type
  TParseResult = class(TEntity)
  private
    FName: string;
    FSentences: TEntityList;
    FStory: TStoryBase;
  public
    procedure AddInternalLink(const ALink, ALabel: string);
    procedure AddExternalLink(const ALink, ALabel: string);
    procedure AddRef(const AParams, AValue: string);

    procedure SaveToDatabase;
  published
    property Name: string read FName write FName;

//    property Links: TEntityList read FLinks write FLinks;
//    property Refs: TEntityList read FRefs write FRefs;
    property Story: TStoryBase read FStory write FStory;
    property Sentences: TEntityList read FSentences write FSentences;
  end;

implementation

uses
  Skylists, AppUnit, Proto, TypesConsts, SentenceBase;

{ TParseResult }

procedure TParseResult.AddExternalLink(const ALink, ALabel: string);
begin
  // not used yet
end;

procedure TParseResult.AddInternalLink(const ALink, ALabel: string);
begin
  // not used yet
end;

procedure TParseResult.AddRef(const AParams, AValue: string);
begin
  // not used yet
end;

procedure TParseResult.SaveToDatabase;
var
  TheProto: TProto;
  TheSentence: TSentenceBase;
  TheStoryId: TId;
  I: Integer;
begin
  TheStoryId := App.SQLConnection.InsertEntity(Story);
  TheProto := TProto.Create;
  try
    TheProto.Level := 1;
    TheProto.StoryId := TheStoryId;
    for I := 0 to Sentences.Count - 1 do
    begin
      TheSentence := Sentences[I] as TSentenceBase;
      TheProto.Name := TheSentence.Name;
      TheSentence.StoryId := TheStoryId;
      TheSentence.ProtoId := App.SQLConnection.InsertEntity(TheProto);
      App.SQLConnection.InsertEntity(TheSentence);
    end;
  finally
    TheProto.Free;
  end;
end;

end.

