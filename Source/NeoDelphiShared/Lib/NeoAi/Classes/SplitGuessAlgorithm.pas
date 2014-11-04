unit SplitGuessAlgorithm;

interface

uses
  SplitGuess, SentenceSplitter, Entity, SentenceList, Hypernym, PosTagger, GuessObject, SentenceAlgorithm, SkyIdList,
  TypesConsts;

type
  TSplitGuessAlgorithm = class
  private
    const
      CONST_InfiniteMaxIterationsActual = 10;
  private
    FSplitThreshold: Double;
    FUseExact: Boolean;

    FPosTagger: TPosTagger;
    FSentenceList: TSentenceList;
    FSplitter: TSentenceSplitter;
    FSentenceAlgorithm: TSentenceAlgorithm;
    FGuessObject: TGuessObject;

    procedure SetSepWeight(const Value: Integer);
  public
    constructor Create(AHypernym: THypernym; APosTagger: TPosTagger);
    destructor Destroy; override;

    function GenerateProtoGuess(const ASentenceText: string; AMaxIterations: Integer; AParentId: TId = IdNil): TSplitGuess;

    property SepWeight: Integer write SetSepWeight;
    property SplitThreshold: Double read FSplitThreshold write FSplitThreshold;
    property UseExact: Boolean read FUseExact write FUseExact;
  end;

implementation

uses
  AppSQLServerQuery, Proto, SentenceListElement, Split, SkyLists, TypesFunctions, SysUtils, EntityList;

{ TSplitGuessAlgorithm }

constructor TSplitGuessAlgorithm.Create(AHypernym: THypernym; APosTagger: TPosTagger);
var
  ThePos: string;
  TheProto: TProto;
  TheProtos: TEntities;
  I: Integer;
begin
  TheProtos := TAppSQLServerQuery.GetSplitProtos;
  try
    FSplitter := TSentenceSplitter.Create;
    FPosTagger := APosTagger;
    FSentenceList := TSentenceList.Create;
    FSentenceList.Hypernym := AHypernym;
    FSentenceList.ScoringMode := smProto;
    FGuessObject := TGuessObject.Create;
    FSentenceAlgorithm := TSentenceAlgorithm.Create;
    FSentenceAlgorithm.ScoringMode := smProto;
    for I := 0 to High(TheProtos) do
    begin
      TheProto := TheProtos[I] as TProto;
      FSplitter.SentenceSplitWords(TheProto.Name);
      ThePos := FPosTagger.GetTagsForWords(FSplitter, True);
      FSentenceList.AddSentence(FSplitter.WordList, TheProto.Id, TheProto.Name, '', '', ThePos);
    end;
  finally
    TEntity.FreeEntities(TheProtos);
  end;
end;

destructor TSplitGuessAlgorithm.Destroy;
begin
  FSplitter.Free;
  FSentenceList.Free;
  FGuessObject.Free;
  FSentenceAlgorithm.Free;
  inherited;
end;

function TSplitGuessAlgorithm.GenerateProtoGuess(const ASentenceText: string; AMaxIterations: Integer; AParentId: TId): TSplitGuess;
var
  TheAdjustedSentence: string;
  TheChildGuess: TSplitGuess;
  TheMaxIterations: Integer;
  TheResultSentence: string;
  ThePerformSplit: Boolean;
  ThePos: string;
  TheSplit: TSplit;
  TheSplits: TEntities;
  TheSplitGuesses: TSkyStringList;
  I: Integer;
begin
  TheMaxIterations := AMaxIterations;
  if (AMaxIterations = 0) or (TheMaxIterations > CONST_InfiniteMaxIterationsActual) then
    TheMaxIterations := CONST_InfiniteMaxIterationsActual;
  FSplitter.SentenceSplitWords(ASentenceText);
  ThePos := FPosTagger.GetTagsForString(ASentenceText);
  FSentenceList.GetRepGuess(FSplitter.WordList, ASentenceText, ThePos, 1, False, FGuessObject, True, FUseExact);
  if AParentId = FGuessObject.GuessDId then
  begin
    Result := nil;
    Exit;
  end;

  Result := TSplitGuess.Create;
  try
    TheSplitGuesses := TSkyStringList.Create;
    try
      TheSplits := TAppSQLServerQuery.GetSplitsForProtoId(FGuessObject.GuessDId);
      try
        FSentenceAlgorithm.Element1 := TSentenceListElement.Create(FSplitter.WordList, IdNil, ASentenceText, '', '', ThePos);

        Result.Sentence := ASentenceText;
        // Result.Pos := .. see below
        Result.MatchText := FGuessObject.MatchSentenceD;
        Result.MatchId :=  FGuessObject.GuessDId;
        Result.MatchPos := FPosTagger.GetTagsForString(FGuessObject.MatchSentenceD);
        Result.MatchScore := RoundDouble(FGuessObject.MatchScore, 4);
        Result.SplitStatus := FGuessObject.MatchScore >= FSplitThreshold;
        ThePerformSplit := Result.SplitStatus and (TheMaxIterations > 1);

        FSplitter.SentenceSplitWords(FGuessObject.MatchSentenceD);
        ThePos := FPosTagger.GetTagsForString(FGuessObject.MatchSentenceD);
        FSentenceAlgorithm.Element2 := TSentenceListElement.Create(FSplitter.WordList, IdNil, FGuessObject.MatchSentenceD, '', '', ThePos);
        FSentenceAlgorithm.DoRunHybridSemMatch;
        FSentenceAlgorithm.AddMissingInWordsToOutList;

        TheResultSentence := '';
        for I := 0 to High(TheSplits) do
        begin
          TheSplit := TheSplits[I] as TSplit;
          TheAdjustedSentence := FSentenceAlgorithm.GetAdjustedRep(TheSplit.Name);
          if (Length(TheAdjustedSentence) > 0) then
            TheAdjustedSentence[1] := UpperCase(TheAdjustedSentence[1])[1];
          if (TheAdjustedSentence = '') or (TheAdjustedSentence = ASentenceText) then
            Continue;

          TheResultSentence := TheResultSentence + TheAdjustedSentence + '. ';
          if  ThePerformSplit then
            TheSplitGuesses.Add(TheAdjustedSentence)
          else begin
            // guess - match
            TheChildGuess := TSplitGuess.Create;
            TheChildGuess.Sentence := TheAdjustedSentence;
            TheChildGuess.Pos := FPosTagger.GetTagsForString(TheChildGuess.Pos);
            TheChildGuess.MatchText := TheSplit.Name;
            TheChildGuess.MatchId := TheSplit.Id;
            TheChildGuess.MatchPos := FPosTagger.GetTagsForString(TheChildGuess.MatchText);
            TheChildGuess.MatchScore := 0;
            TheChildGuess.SplitStatus := False;
            Result.Splits.Add(TheChildGuess);
          end
        end;
      finally
        TEntity.FreeEntities(TheSplits);
        FSentenceAlgorithm.Element1.Free;
        FSentenceAlgorithm.Element2.Free;
      end;
      FSplitter.SentenceSplitWords(TheResultSentence);
      Result.Pos:= FPosTagger.GetTagsForWords(FSplitter, True);

      for I := 0 to TheSplitGuesses.Count - 1 do
      begin
        TheChildGuess := self.GenerateProtoGuess(TheSplitGuesses[I], TheMaxIterations - 1, Result.MatchId);
        if TheChildGuess <> nil then
          Result.Splits.Add(TheChildGuess);
      end;
      Result.SplitStatus := Result.Splits.Count > 0;
    finally
      TheSplitGuesses.Free;
    end;
  except
    Result.Free;
    raise;
  end;
end;

procedure TSplitGuessAlgorithm.SetSepWeight(const Value: Integer);
begin
  FSentenceAlgorithm.WeightMatchProto := Value;
end;

end.