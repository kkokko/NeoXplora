unit ApiGeneratedSplitFull;

interface

uses
  ApiGeneratedSplit, SplitGuess, SkyLists;

type
  TApiGeneratedSplitFull = class(TApiGeneratedSplit)
  private
    FMatchedSplit: string;
    FMatchPos: string;
    FPos: string;
    FSplitMatchScore: Double;
    FSubstitutions: TSkyStringStringList;
  public
    class function CreateFromSplitGuess(ASplitGuess: TSplitGuess): TApiGeneratedSplitFull; reintroduce;
  published
    property Sentence;
    property Pos: string read FPos write FPos;
    property MatchSentence: string read FMatchedSplit write FMatchedSplit;
    property MatchPos: string read FMatchPos write FMatchPos;
    property MatchScore: Double read FSplitMatchScore write FSplitMatchScore;
    property SplitStatus;
    property Splits;
    property Substitutions: TSkyStringStringList read FSubstitutions write FSubstitutions;
  end;

implementation

{ TApiGeneratedSplitFull }

class function TApiGeneratedSplitFull.CreateFromSplitGuess(ASplitGuess: TSplitGuess): TApiGeneratedSplitFull;
var
  I: Integer;
begin
  if ASplitGuess = nil then
  begin
    Result := nil;
    Exit;
  end;
  Result := TApiGeneratedSplitFull.Create;
  Result.Sentence := ASplitGuess.Sentence;
  Result.Pos := ASplitGuess.Pos;
  Result.MatchSentence := ASplitGuess.MatchText;
  Result.MatchPos := ASplitGuess.MatchPos;
  Result.MatchScore := ASplitGuess.MatchScore;
  Result.SplitStatus := ASplitGuess.SplitStatus;
  if ASplitGuess.Splits <> nil then
    for I := 0 to ASplitGuess.Splits.Count - 1 do
      Result.Splits.Add(CreateFromSplitGuess(ASplitGuess.Splits[I] as TSplitGuess));
  Result.Substitutions.CopyFrom(ASplitGuess.Substitutions);
end;

end.