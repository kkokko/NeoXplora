unit SentenceList;

interface

uses
  SentenceAlgorithm, SentenceListElement, SkyLists, GuessObject, TypesConsts,
  SkyIdList, SentenceWithGuesses, Hypernym;

type
  TSentenceList = class
  private
    FSentences: TSkyIdList;
    FHypernym: THypernym;
    FScoringMode: TSentenceAlgorithm.TScoringMode;
    function CheckSmallPosMatch(ASentence1, ASentence2: TSentenceListElement): Boolean;
    function GetSentenceCount: Integer;
  public
    constructor Create;
    destructor Destroy; override;

    procedure AddSentence(SentenceWords: TSkyStringStringList; AnId: TId; const ASentence, ARepresentation, ASemRep, APos: string);
    procedure GetRepGuess(SentenceWords: TSkyStringStringList; const ASentence, APos: string; AStep: Integer;
      AIsTurboMode: Boolean; AResult: TGuessObject; ACalculateOnlyD: Boolean = False);
    procedure RecalculateGuesses(ASentenceWordList: TSkyStringStringList; ASentence: TSentenceWithGuesses);

    property SentenceCount: Integer read GetSentenceCount;
    property Hypernym: THypernym read FHypernym write FHyperNym;
    property ScoringMode: TSentenceAlgorithm.TScoringMode read FScoringMode write FScoringMode;
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
end;

destructor TSentenceList.Destroy;
begin
  FSentences.Free;
  inherited;
end;

procedure TSentenceList.AddSentence(SentenceWords: TSkyStringStringList; AnId: TId; const ASentence, ARepresentation, ASemRep, APos: string);
begin
  FSentences.AddObject(AnId, TSentenceListElement.Create(SentenceWords, AnId, ASentence, ARepresentation, ASemRep, APos));
end;

procedure TSentenceList.GetRepGuess(SentenceWords: TSkyStringStringList; const ASentence, APos: string; AStep: Integer;
  AIsTurboMode: Boolean; AResult: TGuessObject; ACalculateOnlyD: Boolean);
var
  TheBestScoreA, TheBestScoreB, TheBestScoreC, TheBestScoreD: Double;
  TheCurrentSentence: TSentenceListElement;
  TheSentenceAlgorithm: TSentenceAlgorithm;
  I: Integer;
begin
  TheBestScoreA := 0;
  TheBestScoreB := 0;
  TheBestScoreC := 0;
  TheBestScoreD := 0;
  TheSentenceAlgorithm := nil;
  TheCurrentSentence := TSentenceListElement.Create(SentenceWords, IdNil, ASentence, '', '', APos);
  try
    TheSentenceAlgorithm := TSentenceAlgorithm.Create;
    TheSentenceAlgorithm.ScoringMode := FScoringMode;
    TheSentenceAlgorithm.Element1 := TheCurrentSentence;
    for I := 0 to FSentences.Count - 1 do
      if I mod AStep = 0 then
      begin
        TheSentenceAlgorithm.Element2 := FSentences.Objects[I] as TSentenceListElement;
        if AIsTurboMode and not CheckSmallPosMatch(TheCurrentSentence, TheSentenceAlgorithm.Element2) then
          Continue;
        if TheSentenceAlgorithm.Element1.Sentence = TheSentenceAlgorithm.Element2.Sentence then
          Continue;
        if not ACalculateOnlyD then
        begin
          TheSentenceAlgorithm.RunTextMatch(TheBestScoreA, AResult.FGuessAId, AResult.FRepGuessA, AResult.FSRepGuessA, AResult.FMatchSentenceA);
          TheSentenceAlgorithm.RunPosMatch(TheBestScoreB, AResult.FGuessBId, AResult.FRepGuessB, AResult.FSRepGuessB, AResult.FMatchSentenceB);
          TheSentenceAlgorithm.RunHybridPosMatch(TheBestScoreC, AResult.FGuessCId, AResult.FRepGuessC, AResult.FSRepGuessC, AResult.FMatchSentenceC);
        end;
        TheSentenceAlgorithm.RunHybridSemMatch(TheBestScoreD, AResult.FGuessDId, AResult.FRepGuessD, AResult.FSRepGuessD, AResult.FMatchSentenceD);
      end;
  finally
    TheCurrentSentence.Free;
    TheSentenceAlgorithm.Free;
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
  TheSentenceAlgorithm: TSentenceAlgorithm;
begin
  TheBestScoreA := 0;
  TheBestScoreB := 0;
  TheBestScoreC := 0;
  TheBestScoreD := 0;
  TheSentenceAlgorithm := nil;
  TheCurrentSentence := TSentenceListElement.Create(ASentenceWordList, IdNil, ASentence.Name, '', '', ASentence.Pos);
  try
    TheSentenceAlgorithm := TSentenceAlgorithm.Create;
    TheSentenceAlgorithm.Element1 := TheCurrentSentence;
    TheSentenceAlgorithm.Element2 := FSentences.ObjectOfValueDefault[ASentence.GuessAId, nil] as TSentenceListElement;
    TheSentenceAlgorithm.RunTextMatch(TheBestScoreA, ASentence.Guesses.FGuessAId, ASentence.Guesses.FRepGuessA,
      ASentence.Guesses.FSRepGuessA, ASentence.Guesses.FMatchSentenceA);

    TheSentenceAlgorithm.Element2 := FSentences.ObjectOfValueDefault[ASentence.GuessBId, nil] as TSentenceListElement;
    TheSentenceAlgorithm.RunPosMatch(TheBestScoreB, ASentence.Guesses.FGuessBId, ASentence.Guesses.FRepGuessB,
      ASentence.Guesses.FSRepGuessB, ASentence.Guesses.FMatchSentenceB);

    TheSentenceAlgorithm.Element2 := FSentences.ObjectOfValueDefault[ASentence.GuessCId, nil] as TSentenceListElement;
    TheSentenceAlgorithm.RunHybridPosMatch(TheBestScoreC, ASentence.Guesses.FGuessCId, ASentence.Guesses.FRepGuessC,
      ASentence.Guesses.FSRepGuessC, ASentence.Guesses.FMatchSentenceC);

    TheSentenceAlgorithm.Element2 := FSentences.ObjectOfValueDefault[ASentence.GuessDId, nil] as TSentenceListElement;
    TheSentenceAlgorithm.RunHybridSemMatch(TheBestScoreD, ASentence.Guesses.FGuessDId, ASentence.Guesses.FRepGuessD,
      ASentence.Guesses.FSRepGuessD, ASentence.Guesses.FMatchSentenceD);
  finally
    TheCurrentSentence.Free;
    TheSentenceAlgorithm.Free;
  end;
end;

end.

