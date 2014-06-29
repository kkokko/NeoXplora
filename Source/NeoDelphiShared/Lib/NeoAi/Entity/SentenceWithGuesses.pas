unit SentenceWithGuesses;

interface

uses
  SentenceBase, GuessObject, TypesConsts;

type
  TSentenceWithGuesses = class(TSentenceBase)
  private
    FGuesses: TGuessObject;
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
    property GuessIdA;
    property GuessIdB;
    property GuessIdC;
    property GuessIdD;
    property Guesses: TGuessObject read FGuesses write FGuesses;
  end;

implementation



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
