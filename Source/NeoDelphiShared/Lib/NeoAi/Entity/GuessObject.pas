unit GuessObject;

interface

uses
  Entity, TypesConsts;

type
  TGuessObject = class(TEntity)
  public
    FMatchSentenceA: string;
    FMatchSentenceB: string;
    FMatchSentenceC: string;
    FMatchSentenceD: string;
    FRepGuessB: string;
    FRepGuessC: string;
    FRepGuessA: string;
    FRepGuessD: string;
    FSRepGuessB: string;
    FSRepGuessC: string;
    FSRepGuessA: string;
    FSRepGuessD: string;
    FCRepGuessA: string;
    FGuessAId: TId;
    FGuessBId: TId;
    FGuessCId: TId;
    FGuessDId: TId;
    FMatchScore: Double;
  public
    class var
      Tok_Id: TEntityFieldNamesToken;
      Tok_MatchSentenceA: TEntityFieldNamesToken;
      Tok_MatchSentenceB: TEntityFieldNamesToken;
      Tok_MatchSentenceC: TEntityFieldNamesToken;
      Tok_MatchSentenceD: TEntityFieldNamesToken;
      Tok_MatchScore: TEntityFieldNamesToken;
      Tok_RepGuessA: TEntityFieldNamesToken;
      Tok_RepGuessB: TEntityFieldNamesToken;
      Tok_RepGuessC: TEntityFieldNamesToken;
      Tok_RepGuessD: TEntityFieldNamesToken;
      Tok_CRepGuessA: TEntityFieldNamesToken;
      Tok_SRepGuessA: TEntityFieldNamesToken;
      Tok_SRepGuessB: TEntityFieldNamesToken;
      Tok_SRepGuessC: TEntityFieldNamesToken;
      Tok_SRepGuessD: TEntityFieldNamesToken;
      Tok_GuessAId: TEntityFieldNamesToken;
      Tok_GuessBId: TEntityFieldNamesToken;
      Tok_GuessCId: TEntityFieldNamesToken;
      Tok_GuessDId: TEntityFieldNamesToken;
    class function EntityToken_Id: TEntityFieldNamesToken; override;
    class procedure RegisterFieldMappings;
  published
    property Id;
    property MatchSentenceA: string read FMatchSentenceA write FMatchSentenceA;
    property MatchSentenceB: string read FMatchSentenceB write FMatchSentenceB;
    property MatchSentenceC: string read FMatchSentenceC write FMatchSentenceC;
    property MatchSentenceD: string read FMatchSentenceD write FMatchSentenceD;
    property MatchScore: Double read FMatchScore write FMatchScore;
    property RepGuessA: string read FRepGuessA write FRepGuessA;
    property RepGuessB: string read FRepGuessB write FRepGuessB;
    property RepGuessC: string read FRepGuessC write FRepGuessC;
    property RepGuessD: string read FRepGuessD write FRepGuessD;
    property SRepGuessA: string read FSRepGuessA write FSRepGuessA;
    property SRepGuessB: string read FSRepGuessB write FSRepGuessB;
    property SRepGuessC: string read FSRepGuessC write FSRepGuessC;
    property SRepGuessD: string read FSRepGuessD write FSRepGuessD;
    property GuessAId: TId read FGuessAId write FGuessAId;
    property GuessBId: TId read FGuessBId write FGuessBId;
    property GuessCId: TId read FGuessCId write FGuessCId;
    property GuessDId: TId read FGuessDId write FGuessDId;
  end;

implementation

uses
  EntityMappingManager, EntityMapping, AppConsts;

{ TGuessObject }

class function TGuessObject.EntityToken_Id: TEntityFieldNamesToken;
begin
  Result := Tok_Id;
end;

class procedure TGuessObject.RegisterFieldMappings;
var
  TheManager: TEntityMapping;
begin
  TheManager := TEntityMappingManager.GetMapping(Self);
  TheManager.SetValueForField('MatchSentenceA', Tok_MatchSentenceA.PropertyName);
  TheManager.SetValueForField('MatchSentenceB', Tok_MatchSentenceB.PropertyName);
  TheManager.SetValueForField('MatchSentenceC', Tok_MatchSentenceC.PropertyName);
  TheManager.SetValueForField('MatchSentenceD', Tok_MatchSentenceD.PropertyName);
  TheManager.SetValueForField('MatchScore', Tok_MatchScore.PropertyName);
  TheManager.SetValueForField('RepGuessA', Tok_RepGuessA.PropertyName);
  TheManager.SetValueForField('RepGuessB', Tok_RepGuessB.PropertyName);
  TheManager.SetValueForField('RepGuessC', Tok_RepGuessC.PropertyName);
  TheManager.SetValueForField('RepGuessD', Tok_RepGuessD.PropertyName);
  TheManager.SetValueForField('CRepGuessA', Tok_CRepGuessA.PropertyName);
  TheManager.SetValueForField('SRepGuessA', Tok_SRepGuessA.PropertyName);
  TheManager.SetValueForField('SRepGuessB', Tok_SRepGuessB.PropertyName);
  TheManager.SetValueForField('SRepGuessC', Tok_SRepGuessC.PropertyName);
  TheManager.SetValueForField('SRepGuessD', Tok_SRepGuessD.PropertyName);
end;

initialization
  TGuessObject.RegisterEntityClassWithMappingToTable(ConstNeoPrefix + 'sentence');
  TGuessObject.RegisterToken(TGuessObject.Tok_Id, 'Id');
  // empty token names to prevent saving to the database
  TGuessObject.RegisterToken(TGuessObject.Tok_MatchSentenceA, '');
  TGuessObject.RegisterToken(TGuessObject.Tok_MatchSentenceB, '');
  TGuessObject.RegisterToken(TGuessObject.Tok_MatchSentenceC, '');
  TGuessObject.RegisterToken(TGuessObject.Tok_MatchSentenceD, '');
  TGuessObject.RegisterToken(TGuessObject.Tok_MatchScore, '');
  TGuessObject.RegisterToken(TGuessObject.Tok_RepGuessA, '');
  TGuessObject.RegisterToken(TGuessObject.Tok_RepGuessB, '');
  TGuessObject.RegisterToken(TGuessObject.Tok_RepGuessC, '');
  TGuessObject.RegisterToken(TGuessObject.Tok_RepGuessD, '');
  TGuessObject.RegisterToken(TGuessObject.Tok_CRepGuessA, '');
  TGuessObject.RegisterToken(TGuessObject.Tok_SRepGuessA, '');
  TGuessObject.RegisterToken(TGuessObject.Tok_SRepGuessB, '');
  TGuessObject.RegisterToken(TGuessObject.Tok_SRepGuessC, '');
  TGuessObject.RegisterToken(TGuessObject.Tok_SRepGuessD, '');
  TGuessObject.RegisterToken(TGuessObject.Tok_GuessAId, 'GuessAId');
  TGuessObject.RegisterToken(TGuessObject.Tok_GuessBId, 'GuessBId');
  TGuessObject.RegisterToken(TGuessObject.Tok_GuessCId, 'GuessCId');
  TGuessObject.RegisterToken(TGuessObject.Tok_GuessDId, 'GuessDId');
  TGuessObject.RegisterFieldMappings;

end.
