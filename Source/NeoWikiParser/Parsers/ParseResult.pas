unit ParseResult;

interface

uses
  Entity, EntityList, PageBase;

type
  TParseResult = class(TEntity)
  private
    FName: string;
    FSentences: TEntityList;
    FPage: TPageBase;
  public
    procedure AddInternalLink(const ALink, ALabel: string);
    procedure AddExternalLink(const ALink, ALabel: string);
    procedure AddRef(const AParams, AValue: string);

    procedure SaveToDatabase;
  published
    property Name: string read FName write FName;

//    property Links: TEntityList read FLinks write FLinks;
//    property Refs: TEntityList read FRefs write FRefs;
    property Page: TPageBase read FPage write FPage;
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
  ThePageId: TId;
  I: Integer;
begin
  ThePageId := App.SQLConnection.InsertEntity(Page);
  TheProto := TProto.Create;
  try
    TheProto.Level := 1;
    TheProto.PageId := ThePageId;
    for I := 0 to Sentences.Count - 1 do
    begin
      TheSentence := Sentences[I] as TSentenceBase;
      TheProto.Name := TheSentence.Name;
      TheProto.Order := TheSentence.Order;
      TheSentence.PageId := ThePageId;
      TheSentence.ProtoId := App.SQLConnection.InsertEntity(TheProto);
      App.SQLConnection.InsertEntity(TheSentence);
    end;
  finally
    TheProto.Free;
  end;
end;

end.

