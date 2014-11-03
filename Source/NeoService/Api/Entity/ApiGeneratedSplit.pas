unit ApiGeneratedSplit;

interface

uses
  Entity, EntityList, SplitGuess;

type
  TApiGeneratedSplit = class(TEntity)
  private
    FSplits: TEntityList;
    FSentence: string;
    FSplitStatus: Boolean;
  public
    class function CreateFromSplitGuess(ASplitGuess: TSplitGuess): TApiGeneratedSplit;
  published
    property Sentence: string read FSentence write FSentence;
    property SplitStatus: Boolean read FSplitStatus write FSplitStatus;
    property Splits: TEntityList read FSplits write FSplits; //array of TApiGeneratedSplit
  end;

implementation

{ TApiGeneratedSplit }

class function TApiGeneratedSplit.CreateFromSplitGuess(ASplitGuess: TSplitGuess): TApiGeneratedSplit;
var
  I: Integer;
begin
  if ASplitGuess = nil then
  begin
    Result := nil;
    Exit;
  end;
  Result := TApiGeneratedSplit.Create;
  Result.Sentence := ASplitGuess.Sentence;
  Result.SplitStatus := ASplitGuess.SplitStatus;
  if ASplitGuess.Splits <> nil then
    for I := 0 to ASplitGuess.Splits.Count - 1 do
      Result.Splits.Add(CreateFromSplitGuess(ASplitGuess.Splits[I] as TSplitGuess));
end;

end.
