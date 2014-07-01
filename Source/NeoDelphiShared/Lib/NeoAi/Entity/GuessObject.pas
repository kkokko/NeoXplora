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
    FGuessIdA: TId;
    FGuessIdD: TId;
    FGuessIdB: TId;
    FGuessIdC: TId;
  public
    class var
      Tok_Id: TEntityFieldNamesToken;
      Tok_MatchSentenceA: TEntityFieldNamesToken;
      Tok_MatchSentenceB: TEntityFieldNamesToken;
      Tok_MatchSentenceC: TEntityFieldNamesToken;
      Tok_MatchSentenceD: TEntityFieldNamesToken;
      Tok_RepGuessA: TEntityFieldNamesToken;
      Tok_RepGuessB: TEntityFieldNamesToken;
      Tok_RepGuessC: TEntityFieldNamesToken;
      Tok_RepGuessD: TEntityFieldNamesToken;
      Tok_CRepGuessA: TEntityFieldNamesToken;
      Tok_SRepGuessA: TEntityFieldNamesToken;
      Tok_SRepGuessB: TEntityFieldNamesToken;
      Tok_SRepGuessC: TEntityFieldNamesToken;
      Tok_SRepGuessD: TEntityFieldNamesToken;
      Tok_GuessIdA: TEntityFieldNamesToken;
      Tok_GuessIdB: TEntityFieldNamesToken;
      Tok_GuessIdC: TEntityFieldNamesToken;
      Tok_GuessIdD: TEntityFieldNamesToken;
    class function EntityToken_Id: TEntityFieldNamesToken; override;
    class procedure RegisterFieldMappings;
  published
    property Id;
    property MatchSentenceA: string read FMatchSentenceA write FMatchSentenceA;
    property MatchSentenceB: string read FMatchSentenceB write FMatchSentenceB;
    property MatchSentenceC: string read FMatchSentenceC write FMatchSentenceC;
    property MatchSentenceD: string read FMatchSentenceD write FMatchSentenceD;
    property RepGuessA: string read FRepGuessA write FRepGuessA;
    property RepGuessB: string read FRepGuessB write FRepGuessB;
    property RepGuessC: string read FRepGuessC write FRepGuessC;
    property RepGuessD: string read FRepGuessD write FRepGuessD;
    property CRepGuessA: string read FCRepGuessA write FCRepGuessA;
    property SRepGuessA: string read FSRepGuessA write FSRepGuessA;
    property SRepGuessB: string read FSRepGuessB write FSRepGuessB;
    property SRepGuessC: string read FSRepGuessC write FSRepGuessC;
    property SRepGuessD: string read FSRepGuessD write FSRepGuessD;
    property GuessIdA: TId read FGuessIdA write FGuessIdA;
    property GuessIdB: TId read FGuessIdB write FGuessIdB;
    property GuessIdC: TId read FGuessIdC write FGuessIdC;
    property GuessIdD: TId read FGuessIdD write FGuessIdD;
  end;

implementation

uses
  EntityMappingManager, EntityMapping;

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
  TheManager.SetValueForField('Id', Tok_Id.PropertyName);
  TheManager.SetValueForField('MatchSentenceA', Tok_MatchSentenceA.PropertyName);
  TheManager.SetValueForField('MatchSentenceB', Tok_MatchSentenceB.PropertyName);
  TheManager.SetValueForField('MatchSentenceC', Tok_MatchSentenceC.PropertyName);
  TheManager.SetValueForField('MatchSentenceD', Tok_MatchSentenceD.PropertyName);
  TheManager.SetValueForField('RepGuessA', Tok_RepGuessA.PropertyName);
  TheManager.SetValueForField('RepGuessB', Tok_RepGuessB.PropertyName);
  TheManager.SetValueForField('RepGuessC', Tok_RepGuessC.PropertyName);
  TheManager.SetValueForField('RepGuessD', Tok_RepGuessD.PropertyName);
  TheManager.SetValueForField('CRepGuessA', Tok_CRepGuessA.PropertyName);
  TheManager.SetValueForField('SRepGuessA', Tok_SRepGuessA.PropertyName);
  TheManager.SetValueForField('SRepGuessB', Tok_SRepGuessB.PropertyName);
  TheManager.SetValueForField('SRepGuessC', Tok_SRepGuessC.PropertyName);
  TheManager.SetValueForField('SRepGuessD', Tok_SRepGuessD.PropertyName);
  TheManager.SetValueForField('GuessIdA', Tok_GuessIdA.PropertyName);
  TheManager.SetValueForField('GuessIdB', Tok_GuessIdB.PropertyName);
  TheManager.SetValueForField('GuessIdC', Tok_GuessIdC.PropertyName);
  TheManager.SetValueForField('GuessIdD', Tok_GuessIdD.PropertyName);
end;

initialization
  TGuessObject.RegisterEntityClassWithMappingToTable('sentence');
  TGuessObject.RegisterToken(TGuessObject.Tok_Id, 'sentenceId');
  TGuessObject.RegisterToken(TGuessObject.Tok_MatchSentenceA, '');
  TGuessObject.RegisterToken(TGuessObject.Tok_MatchSentenceB, '');
  TGuessObject.RegisterToken(TGuessObject.Tok_MatchSentenceC, '');
  TGuessObject.RegisterToken(TGuessObject.Tok_MatchSentenceD, '');
  TGuessObject.RegisterToken(TGuessObject.Tok_RepGuessA, '');
  TGuessObject.RegisterToken(TGuessObject.Tok_RepGuessB, '');
  TGuessObject.RegisterToken(TGuessObject.Tok_RepGuessC, '');
  TGuessObject.RegisterToken(TGuessObject.Tok_RepGuessD, '');
  TGuessObject.RegisterToken(TGuessObject.Tok_CRepGuessA, '');
  TGuessObject.RegisterToken(TGuessObject.Tok_SRepGuessA, '');
  TGuessObject.RegisterToken(TGuessObject.Tok_SRepGuessB, '');
  TGuessObject.RegisterToken(TGuessObject.Tok_SRepGuessC, '');
  TGuessObject.RegisterToken(TGuessObject.Tok_SRepGuessD, '');
  TGuessObject.RegisterToken(TGuessObject.Tok_GuessIdA, 'sntid1');
  TGuessObject.RegisterToken(TGuessObject.Tok_GuessIdB, 'sntid2');
  TGuessObject.RegisterToken(TGuessObject.Tok_GuessIdC, 'sntid3');
  TGuessObject.RegisterToken(TGuessObject.Tok_GuessIdD, 'sntid4');
  TGuessObject.RegisterFieldMappings;

end.
