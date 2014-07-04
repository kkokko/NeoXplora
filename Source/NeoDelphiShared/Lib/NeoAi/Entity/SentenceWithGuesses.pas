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
    property PageId;
    property GuessAId;
    property GuessBId;
    property GuessCId;
    property GuessDId;
    property Guesses: TGuessObject read FGuesses write FGuesses;
  end;

implementation

uses
  AppConsts;

{ TSentenceWithGuesses }

constructor TSentenceWithGuesses.Create;
begin
  inherited;
  Guesses := TGuessObject.Create;
end;

initialization
  TSentenceWithGuesses.RegisterEntityClassWithMappingToTable(ConstNeoPrefix + 'sentence');

end.
