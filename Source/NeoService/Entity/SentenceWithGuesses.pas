unit SentenceWithGuesses;

interface

uses
  SentenceBase, GuessObject, TypesConsts;

type
  TSentenceWithGuesses = class(TSentenceBase)
  private
    FGuesses: TGuessObject;
    FGuessIdA: TId;
    FGuessIdD: TId;
    FGuessIdB: TId;
    FGuessIdC: TId;
  public
    constructor Create; override;
  published
    property Id;
    property Name;
    property Rep;
    property CRep;
    property SRep;
    property Pos;
    property StoryId;
    property GuessIdA: TId read FGuessIdA write FGuessIdA;
    property GuessIdB: TId read FGuessIdB write FGuessIdB;
    property GuessIdC: TId read FGuessIdC write FGuessIdC;
    property GuessIdD: TId read FGuessIdD write FGuessIdD;
    property Guesses: TGuessObject read FGuesses write FGuesses;
  end;

implementation

uses
  EntityMapping, EntityMappingManager;

{ TSentenceWithGuesses }

constructor TSentenceWithGuesses.Create;
begin
  inherited;
  Guesses := TGuessObject.Create;
end;

initialization
  TSentenceWithGuesses.RegisterEntityClassWithMappingToTable('sentence');
  TSentenceWithGuesses.RegisterFieldMappings;

end.
