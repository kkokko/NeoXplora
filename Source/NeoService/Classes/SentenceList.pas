unit SentenceList;

interface

uses
  Classes, contnrs, NASTypes, SentenceAlgorithm, SentenceListElement, SkyLists, Entity, GuessObject, TypesConsts,
  SkyIdList, SentenceWithGuesses;

type
  TSentenceList = class
  private
    FSentenceAlgorithm: TSentenceAlgorithm;
    FSentences: TSkyIdList;
    function CheckSmallPosMatch(ASentence1, ASentence2: TSentenceListElement): Boolean;
    function GetSentenceCount: Integer;
  public
    constructor Create;
    destructor Destroy; override;

    procedure AddSentence(SentenceWords: TSkyStringStringList; AnId: TId; const ASentence, ARepresentation, ASemRep, APos: string);
    procedure GetRepGuess(SentenceWords: TSkyStringStringList; const ASentence, APos: string; AStep: Integer;
      AIsTurboMode: Boolean; AResult: TGuessObject);
    procedure SetHypernyms(SomeHypernymStrings: TEntities);
    procedure RecalculateGuesses(ASentenceWordList: TSkyStringStringList; ASentence: TSentenceWithGuesses);

    property SentenceCount: Integer read GetSentenceCount;
  end;

implementation

{ TSentenceList }

function TSentenceList.CheckSmallPosMatch(ASentence1, ASentence2: TSentenceListElement): Boolean;
var
  ThePos13, ThePos24, ThePos35: string;
  TheIndex: Integer;
begin
  Result := True;
  if ASentence1.PosWords.Count > 4 then
    ThePos35 := ASentence1.PosWords[2] + ' ' + ASentence1.PosWords[3] + ASentence1.PosWords[4]
  else
    ThePos35 := '';
  if ASentence1.PosWords.Count > 3 then
    ThePos24 := ASentence1.PosWords[1] + ' ' + ASentence1.PosWords[2] + ASentence1.PosWords[3]
  else
    ThePos24 := '';

  ThePos13 := '';
  TheIndex := 0;
  while (TheIndex < 3) and (TheIndex < ASentence1.PosWords.Count) do
  begin
    if TheIndex > 0 then
      ThePos13 := ThePos13 + ' ';
    ThePos13 := ThePos13 + ASentence1.PosWords[TheIndex];
    TheIndex := TheIndex + 1;
  end;
  if ThePos13 = '' then
    Exit;
  Result := (Pos(ThePos13, ASentence2.PosStr) <> 0) or
    ((ThePos24 <> '') and (Pos(ThePos24, ASentence2.PosStr) <> 0)) or
    ((ThePos35 <> '') and (Pos(ThePos35, ASentence2.PosStr) <> 0));
end;

constructor TSentenceList.Create;
begin
  FSentences := TSkyIdList.Create;
  FSentences.OwnsObjects := True;
  FSentences.Sorted := True;
  FSentenceAlgorithm := TSentenceAlgorithm.Create;
end;

destructor TSentenceList.Destroy;
begin
  FSentences.Free;
  FSentenceAlgorithm.Free;
end;

procedure TSentenceList.AddSentence(SentenceWords: TSkyStringStringList; AnId: TId; const ASentence, ARepresentation, ASemRep, APos: string);
begin
  FSentences.AddObject(AnId, TSentenceListElement.Create(SentenceWords, AnId, ASentence, ARepresentation, ASemRep, APos));
end;

procedure TSentenceList.GetRepGuess(SentenceWords: TSkyStringStringList; const ASentence, APos: string; AStep: Integer;
  AIsTurboMode: Boolean; AResult: TGuessObject);
var
  TheBestScoreA, TheBestScoreB, TheBestScoreC, TheBestScoreD: Double;
  TheCurrentSentence: TSentenceListElement;
  I: Integer;
begin
  TheBestScoreA := 0;
  TheBestScoreB := 0;
  TheBestScoreC := 0;
  TheBestScoreD := 0;
  TheCurrentSentence := TSentenceListElement.Create(SentenceWords, IdNil, ASentence, '', '', APos);
  try
    FSentenceAlgorithm.Element1 := TheCurrentSentence;
    for I := 0 to FSentences.Count - 1 do
      if I mod AStep = 0 then
      begin
        FSentenceAlgorithm.Element2 := FSentences.Objects[I] as TSentenceListElement;
        if AIsTurboMode and not CheckSmallPosMatch(TheCurrentSentence, FSentenceAlgorithm.Element2) then
          Continue;
        if FSentenceAlgorithm.Element1.Sentence = FSentenceAlgorithm.Element2.Sentence then
          Continue;
        FSentenceAlgorithm.RunTextMatch(TheBestScoreA, AResult.FGuessIdA, AResult.FRepGuessA, AResult.FSRepGuessA, AResult.FMatchSentenceA);
        FSentenceAlgorithm.RunPosMatch(TheBestScoreB, AResult.FGuessIdB, AResult.FRepGuessB, AResult.FSRepGuessB, AResult.FMatchSentenceB);
        FSentenceAlgorithm.RunHybridPosMatch(TheBestScoreC, AResult.FGuessIdC, AResult.FRepGuessC, AResult.FSRepGuessC, AResult.FMatchSentenceC);
        FSentenceAlgorithm.RunHybridSemMatch(TheBestScoreD, AResult.FGuessIdD, AResult.FRepGuessD, AResult.FSRepGuessD, AResult.FMatchSentenceD);
      end;
  finally
    TheCurrentSentence.Free;
  end;
end;

function TSentenceList.GetSentenceCount: Integer;
begin
  Result := FSentences.Count;
end;

procedure TSentenceList.RecalculateGuesses(ASentenceWordList: TSkyStringStringList; ASentence: TSentenceWithGuesses);
var
  TheBestScoreA, TheBestScoreB, TheBestScoreC, TheBestScoreD: Double;
  TheCurrentSentence: TSentenceListElement;
begin
  TheBestScoreA := 0;
  TheBestScoreB := 0;
  TheBestScoreC := 0;
  TheBestScoreD := 0;
  TheCurrentSentence := TSentenceListElement.Create(ASentenceWordList, IdNil, ASentence.Name, '', '', ASentence.Pos);
  try
    FSentenceAlgorithm.Element1 := TheCurrentSentence;

    FSentenceAlgorithm.Element2 := FSentences.ObjectOfValueDefault[ASentence.GuessIdA, nil] as TSentenceListElement;
    FSentenceAlgorithm.RunTextMatch(TheBestScoreA, ASentence.Guesses.FGuessIdA, ASentence.Guesses.FRepGuessA,
      ASentence.Guesses.FSRepGuessA, ASentence.Guesses.FMatchSentenceA);

    FSentenceAlgorithm.Element2 := FSentences.ObjectOfValueDefault[ASentence.GuessIdB, nil] as TSentenceListElement;
    FSentenceAlgorithm.RunPosMatch(TheBestScoreB, ASentence.Guesses.FGuessIdB, ASentence.Guesses.FRepGuessB,
      ASentence.Guesses.FSRepGuessB, ASentence.Guesses.FMatchSentenceB);

    FSentenceAlgorithm.Element2 := FSentences.ObjectOfValueDefault[ASentence.GuessIdC, nil] as TSentenceListElement;
    FSentenceAlgorithm.RunHybridPosMatch(TheBestScoreC, ASentence.Guesses.FGuessIdC, ASentence.Guesses.FRepGuessC,
      ASentence.Guesses.FSRepGuessC, ASentence.Guesses.FMatchSentenceC);

    FSentenceAlgorithm.Element2 := FSentences.ObjectOfValueDefault[ASentence.GuessIdD, nil] as TSentenceListElement;
    FSentenceAlgorithm.RunHybridSemMatch(TheBestScoreD, ASentence.Guesses.FGuessIdD, ASentence.Guesses.FRepGuessD,
      ASentence.Guesses.FSRepGuessD, ASentence.Guesses.FMatchSentenceD);
  finally
    TheCurrentSentence.Free;
  end;
end;

procedure TSentenceList.SetHypernyms(SomeHypernymStrings: TEntities);
begin
  FSentenceAlgorithm.SetHypernyms(SomeHypernymStrings);
end;

end.

