unit PosTagger;

interface

uses
  SkyLists;

type
  TPosTagger = class
  private
    FWords: TSkyStringStringList;
  public
    constructor Create;
    destructor Destroy; override;

    function GetTagsForWords(AWordList: TSkyStringStringList; AnUseModifiedPos: Boolean = False): string;

    property Words: TSkyStringStringList read FWords write FWords;
  end;

implementation

uses
  SysUtils, LexiconLine, AppUnit, Entity, SplitterComponent;

{ TPosTagger }

constructor TPosTagger.Create;
var
  TheLexicons: TEntities;
  TheLine: TLexiconLine;
  I: Integer;
begin
  FWords := TSkyStringStringList.Create;
  FWords.CaseSensitive := True;
  FWords.Sorted := False;
  TheLexicons := App.SQLConnection.SelectAll(TLexiconLine);
  try
    for I := 0 to High(TheLexicons) do
    begin
      TheLine := TheLexicons[I] as TLexiconLine;
      FWords.AddObject(TheLine.Word, TheLine.Pos);
    end;
  finally
    TEntity.FreeEntities(TheLexicons);
  end;
  FWords.Sorted := True;
end;

destructor TPosTagger.Destroy;
begin
  FWords.Free;
  inherited Destroy;
end;

function TPosTagger.GetTagsForWords(AWordList: TSkyStringStringList; AnUseModifiedPos: Boolean): string;
var
  TheMofiedNoun: Boolean;
  TheLastWord: string;
  TheListIndex: Integer;
  ThePossiblePOS: string;
  TheTag, TheLastTag: string;
  TheIndex: Integer;
  TheWord: string;
begin
  Result := '';
  TheLastTag := '';
  TheLastWord := '';
  //connecting to the SQLite database that contains the lexicon
  TheIndex := 0;
  while TheIndex < AWordList.Count do
  begin
    TheWord := AWordList[TheIndex];
    if TheWord = ' ' then
    begin
      Inc(TheIndex);
      Continue;
    end;

    //get the possible parts of speech for that word from the SQLite database
    ThePossiblePOS := FWords.ObjectOfValueDefault[TheWord, ''];

    // get from dict if set
    if (ThePossiblePOS = '') then
    begin
      TheTag := 'NN';
      TheMofiedNoun := AnUseModifiedPos;
    end
    else
    begin
      TheMofiedNoun := False;
      TheListIndex := Pos(' ', ThePossiblePOS);
      if TheListIndex = 0 then
        TheTag := ThePossiblePOS
      else
        TheTag := Copy(ThePossiblePOS, 1, TheListIndex - 1);
    end;

    // Converts verbs after 'the' to nouns
    if (TheLastTag = 'DT') and ((Pos('VBD', TheTag) > 0) or (Pos('VBP', TheTag) > 0) or (Pos('VB', TheTag) > 0)) then
      TheTag  := 'NN';

    // Convert noun to number if . appears
    if ((TheTag[1] = 'N') and (Pos('.', TheWord)>0)) then
      TheTag := 'CD';

    // Convert noun to past particle if ends with 'ed'
    if ((TheTag[1] = 'N') and (Length(TheWord) > 2) and (LowerCase(Copy(TheWord, Length(TheWord) - 1, 2)) = 'ed')) then
      TheTag := 'VBN';

    // Anything that ends 'ly' is an adverb
    if ((Length(TheWord) > 2) and (LowerCase(Copy(TheWord, Length(TheWord) - 1, 2)) = 'ly')) then
      TheTag := 'RB';

    // Common noun to adjective if it ends with al
    if ((TheTag = 'NN') and (Length(TheWord) > 2) and (LowerCase(Copy(TheWord, Length(TheWord) - 1, 2)) = 'al')) then
      TheTag := 'JJ';

    // Noun to verb if the word before is 'would'
    if ((TheTag = 'NN') and (TheLastWord = 'would')) then
      TheTag := 'VB';

    // Convert noun to plural if it ends with an s
    if ((TheTag = 'NN') and (TheWord[Length(TheWord)] = 's')) and (
      (Length(TheWord) < 2) or (TheWord[Length(TheWord) - 1] <> 's'))then
      TheTag := 'NNS';

    // Convert common noun to gerund
    if ((TheTag='NN') or (TheTag='NNS')) and (Length(TheWord) > 3) and (LowerCase(Copy(TheWord, Length(TheWord) - 2, 3)) = 'ing') then
      TheTag := 'VBG';

    // If we get noun noun, and the second can be a verb, convert to verb
    if (TheLastTag <> '') and ((TheTag[1]='N') and (TheLastTag[1] = 'N')) then
    begin
      if (Pos('VBN', ThePossiblePOS) > 0) then
        TheTag:='VBN'
      else if (Pos('VBZ', ThePossiblePOS) > 0) then
        TheTag:='VBZ';
    end;

    if TheMofiedNoun and (TheTag = 'NN') then
      TheTag := 'NNPU';

    TheLastTag := TheTag;
    TheLastWord := TheWord;
    Result := Result + TheTag + ' ';
    AWordList.Objects[TheIndex] := TheTag;
    Inc(TheIndex);
  end;
  Result := Trim(Result);
end;

end.

