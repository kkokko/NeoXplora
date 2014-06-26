unit SentenceBase;

interface

uses
  Entity, EntityFieldNamesToken, TypesConsts;

type
  TSentenceBase = class(TEntity)
  private
    FName: string;
    FPos: string;
    FSRep: string;
    FRep: string;
    FStoryId: TId;
    FCRep: string;
  protected
    function GetName: string; override;
    procedure SetName(const AName: string); override;
  public
    class var
      Tok_CRep: TEntityFieldNamesToken;
      Tok_GuessIdA: TEntityFieldNamesToken;
      Tok_GuessIdB: TEntityFieldNamesToken;
      Tok_GuessIdC: TEntityFieldNamesToken;
      Tok_GuessIdD: TEntityFieldNamesToken;
      Tok_Id: TEntityFieldNamesToken;
      Tok_Name: TEntityFieldNamesToken;
      Tok_Rep: TEntityFieldNamesToken;
      Tok_SRep: TEntityFieldNamesToken;
      Tok_StoryId: TEntityFieldNamesToken;
      Tok_Pos: TEntityFieldNamesToken;
    class function EntityToken_Id: TEntityFieldNamesToken; override;
    class function EntityToken_Name: TEntityFieldNamesToken; override;
    class procedure RegisterFieldMappings;
  published
    property Id;
    property Name;
    property Rep: string read FRep write FRep;
    property CRep: string read FCRep write FCRep;
    property SRep: string read FSRep write FSRep;
    property Pos: string read FPos write FPos;
    property StoryId: TId read FStoryId write FStoryId;
  end;

implementation

uses
  EntityMappingManager, EntityMapping;

{ TSentenceBase }

class function TSentenceBase.EntityToken_Id: TEntityFieldNamesToken;
begin
  Result := Tok_Id;
end;

class function TSentenceBase.EntityToken_Name: TEntityFieldNamesToken;
begin
  Result := Tok_Name;
end;

function TSentenceBase.GetName: string;
begin
  Result := FName;
end;

class procedure TSentenceBase.RegisterFieldMappings;
var
  TheManager: TEntityMapping;
begin
  TheManager := TEntityMappingManager.GetMapping(Self);
  TheManager.SetValueForField('Id', Tok_Id.PropertyName);
  TheManager.SetValueForField('Name', Tok_Name.PropertyName);
  TheManager.SetValueForField('Rep', Tok_Rep.PropertyName);
  TheManager.SetValueForField('CRep', Tok_CRep.PropertyName);
  TheManager.SetValueForField('SRep', Tok_SRep.PropertyName);
  TheManager.SetValueForField('Pos', Tok_Pos.PropertyName);
  TheManager.SetValueForField('StoryId', Tok_StoryId.PropertyName);
  TheManager.SetValueForField('GuessIdA', Tok_GuessIdA.PropertyName);
  TheManager.SetValueForField('GuessIdB', Tok_GuessIdB.PropertyName);
  TheManager.SetValueForField('GuessIdC', Tok_GuessIdC.PropertyName);
  TheManager.SetValueForField('GuessIdD', Tok_GuessIdD.PropertyName);
end;

procedure TSentenceBase.SetName(const AName: string);
begin
  FName := AName;
end;

initialization
  TSentenceBase.RegisterEntityClassWithMappingToTable('sentence');
  TSentenceBase.RegisterToken(TSentenceBase.Tok_Id, 'sentenceId');
  TSentenceBase.RegisterToken(TSentenceBase.Tok_Name, 'sentence');
  TSentenceBase.RegisterToken(TSentenceBase.Tok_Rep, 'representation');
  TSentenceBase.RegisterToken(TSentenceBase.Tok_CRep, 'context_rep');
  TSentenceBase.RegisterToken(TSentenceBase.Tok_SRep, 'semantic_rep');
  TSentenceBase.RegisterToken(TSentenceBase.Tok_Pos, 'POS');
  TSentenceBase.RegisterToken(TSentenceBase.Tok_StoryId, 'pageId');
  TSentenceBase.RegisterToken(TSentenceBase.Tok_GuessIdA, 'sntid1');
  TSentenceBase.RegisterToken(TSentenceBase.Tok_GuessIdB, 'sntid2');
  TSentenceBase.RegisterToken(TSentenceBase.Tok_GuessIdC, 'sntid3');
  TSentenceBase.RegisterToken(TSentenceBase.Tok_GuessIdD, 'sntid4');
  TSentenceBase.RegisterFieldMappings;

end.
