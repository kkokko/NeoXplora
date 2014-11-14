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
    function GetSentenceMatch(const ASentence1Text, ASentence2Text: string): TSplitGuess;

    procedure LoadProtosFromDatabase;

    property SepWeight: Integer write SetSepWeight;
    property SplitThreshold: Double read FSplitThreshold write FSplitThreshold;
    property UseExact: Boolean read FUseExact write FUseExact;
  end;

implementation

uses
  AppSQLServerQuery, ProtoOrSentence, SentenceListElement, Split, SkyLists, TypesFunctions, SysUtils, EntityList;

{ TSplitGuessAlgorithm }

procedure TSplitGuessAlgorithm.LoadProtosFromDatabase;
var
  ThePos: string;
  TheProto: TProtoOrSentence;
  TheProtos: TEntities;
  I: Integer;
begin
  TheProtos := TAppSQLServerQuery.GetSplitProtos;
  try
    for I := 0 to High(TheProtos) do
    begin
      TheProto := TheProtos[I] as TProtoOrSentence;
      FSplitter.SentenceSplitWords(TheProto.Name);
      ThePos := FPosTagger.GetTagsForWords(FSplitter, True);
      FSentenceList.AddSentence(FSplitter.WordList, TheProto.Id, TheProto.Name, '', '', ThePos);
    end;
  finally
    TEntity.FreeEntities(TheProtos);
  end;
end;

constructor TSplitGuessAlgorithm.Create(AHypernym: THypernym; APosTagger: TPosTagger);
begin
  FSplitter := TSentenceSplitter.Create;
  FPosTagger := APosTagger;
  FSentenceList := TSentenceList.Create;
  FSentenceList.Hypernym := AHypernym;
  FSentenceList.ScoringMode := smProto;
  FGuessObject := TGuessObject.Create;
  FSentenceAlgorithm := TSentenceAlgorithm.Create;
  FSentenceAlgorithm.ScoringMode := smProto;
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

  Result := TSplitGuess.Create;
  try
    TheSplitGuesses := TSkyStringList.Create;
    try
      if (AParentId = FGuessObject.GuessDId) or (FGuessObject.MatchScore < FSplitThreshold) or (0 > FGuessObject.GuessDId) then
        TheSplits := nil
      else
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
        for I := 0 to FSentenceAlgorithm.AlignInList.Count - 1 do
        begin
          if FSentenceAlgorithm.AlignOutList[I] = '-' then
            Continue;
          if FSentenceAlgorithm.AlignInList[I] = '-' then
            Result.Substitutions.Add(FSentenceAlgorithm.AlignOutList[I], '-NULL-')
          else
            Result.Substitutions.Add(FSentenceAlgorithm.AlignOutList[I], FSentenceAlgorithm.AlignInList[I]);
        end;

        TheResultSentence := '';
        for I := 0 to High(TheSplits) do
        begin
          TheSplit := TheSplits[I] as TSplit;
          TheAdjustedSentence := FSentenceAlgorithm.GetAdjustedRep(TheSplit.Name);
          if (Length(TheAdjustedSentence) > 0) then
            TheAdjustedSentence[1] := UpperCase(TheAdjustedSentence[1])[1];
          if (TheAdjustedSentence = '') or AnsiSameText(Trim(TheAdjustedSentence), Trim(ASentenceText)) then
            Continue;
          TheResultSentence := TheResultSentence + TheAdjustedSentence + '. ';
          if ThePerformSplit then
            TheSplitGuesses.Add(TheAdjustedSentence)
          else
          begin
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
      if Result.Splits.Count = 0 then
        Result.SplitStatus := False;
    finally
      TheSplitGuesses.Free;
    end;
  except
    Result.Free;
    raise;
  end;
end;

function TSplitGuessAlgorithm.GetSentenceMatch(const ASentence1Text, ASentence2Text: string): TSplitGuess;
var
  ThePos: string;
begin
  FSplitter.SentenceSplitWords(ASentence2Text);
  ThePos := FPosTagger.GetTagsForWords(FSplitter, True);
  FSentenceList.AddSentence(FSplitter.WordList, -1, ASentence2Text, '', '', ThePos);
  Result := GenerateProtoGuess(ASentence1Text, 1);
end;

procedure TSplitGuessAlgorithm.SetSepWeight(const Value: Integer);
begin
  FSentenceAlgorithm.WeightMatchProto := Value;
  FSentenceList.WeightMatchProto := Value;
end;

end.
