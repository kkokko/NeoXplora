unit SentenceSplitter;

interface

uses
  SkyLists, SysUtils;

type
  TSentenceSplitter = class
  private
    const
      ConstWordChars = ['a'..'z', 'A'..'Z', '0'..'9', '-'];
  private
    FWordList: TSkyStringStringList;

    procedure ReadWordPunctuation(const AString: string; var AnIndex: Integer); inline;
    procedure ReadWordString(const AString: string; var AnIndex: Integer); inline;

    function ReplaceInvalidStoryChars(const AString: string): string;
  public
    constructor Create;
    destructor Destroy; override;

    // splits and merges a sentence (separates words from punctuation)
    class function SentenceAdjust(const ASentence: string): string;
    // merge a word list into a sentence
    function SentenceFromWords: string;
    // splits a sentence into words considering punctuation and English language rules
    procedure SentenceSplitWords(const ASentence: string);

    // split the story body into sentences.
    // The result will be stored in WordList
    procedure StorySplitProtos(const AStoryBody: string);

    property WordList: TSkyStringStringList read FWordList;
  end;

implementation

{ TSentenceSplitter }

constructor TSentenceSplitter.Create;
begin
  FWordList := TSkyStringStringList.Create;
  FWordList.Sorted := False;
end;

destructor TSentenceSplitter.Destroy;
begin
  FWordList.Free;
  inherited;
end;

class function TSentenceSplitter.SentenceAdjust(const ASentence: string): string;
var
  TheInstance: TSentenceSplitter;
begin
  TheInstance := TSentenceSplitter.Create;
  try
    TheInstance.SentenceSplitWords(ASentence);
    Result := TheInstance.SentenceFromWords;
  finally
    TheInstance.Free;
  end;
end;

function TSentenceSplitter.SentenceFromWords: string;
var
  I: Integer;
begin
  Result := '';
  if WordList.Count = 0 then
    Exit;
  for I := WordList.Count - 1 downto 1 do
    Result := ' ' + WordList[I] + Result;
  Result := WordList[0] + Result;
end;

procedure TSentenceSplitter.ReadWordPunctuation(const AString: string; var AnIndex: Integer);
var
  TheChar: Char;
  TheWord: string;
begin
  TheWord := '';
  while AnIndex <= Length(AString) do
  begin
    TheChar := AString[AnIndex];
    if CharInSet(TheChar, ConstWordChars) then
      Break;
    Inc(AnIndex);
    if TheChar = ' ' then
      Break;
    TheWord := TheWord + TheChar;
  end;
  if TheWord <> '' then
    WordList.Add(TheWord);
end;

procedure TSentenceSplitter.ReadWordString(const AString: string; var AnIndex: Integer);
var
  TheChar: Char;
  TheWord: string;
begin
  TheWord := '';
  while AnIndex <= Length(AString) do
  begin
    TheChar := AString[AnIndex];
    if not CharInSet(TheChar, ConstWordChars) then
      Break;
    TheWord := TheWord + TheChar;
    Inc(AnIndex);
  end;
  if TheWord <> '' then
    WordList.Add(TheWord);
end;

procedure TSentenceSplitter.SentenceSplitWords(const ASentence: string);
var
  I: Integer;
begin
  FWordList.Clear;
  I := 1;
  while I <= Length(ASentence) do
  begin
    ReadWordPunctuation(ASentence, I);
    ReadWordString(ASentence, I);
  end;

  I := 1;
  // fist and last word not processed intentionally
  while I < WordList.Count - 2 do
  begin
    // if simple quote is found, check for:
    if (WordList[I] = '''') then
      if CharInSet(WordList[I - 1][1], ConstWordChars) and (
          // 1'st
          (WordList[I + 1] = 'st') or
          // 2'nd
          (WordList[I + 1] = 'nd') or
          // 3'rd
          (WordList[I + 1] = 'rd') or
          // 4'th, 5'th, etc.
          (WordList[I + 1] = 'th') or
          // can't
          (WordList[I + 1] = 't')
      ) then
      begin
        WordList[I - 1] := WordList[I - 1] + WordList[I] + WordList[I + 1];
        WordList.DeleteFromIndex(I + 1);
        WordList.DeleteFromIndex(I);
        Dec(I);
      end
      else if (
        SameText(WordList[I - 1], 'that') or
        SameText(WordList[I - 1], 'there')
      ) and(SameText(WordList[I + 1], 's')) then
      begin
        WordList[I - 1] := WordList[I - 1] + WordList[I] + WordList[I + 1];
        WordList.DeleteFromIndex(I + 1);
        WordList.DeleteFromIndex(I);
        Dec(I);
      end
      else
      begin
        WordList[I] := WordList[I] + WordList[I + 1];
        WordList.DeleteFromIndex(I + 1);
      end;
    Inc(I);
  end;
  I := WordList.Count - 1;
  if WordList[I] = '.' then
    WordList.DeleteFromIndex(I);
end;

function TSentenceSplitter.ReplaceInvalidStoryChars(const AString: string): string;
begin
  Result := StringReplace(AString, #13, '', [rfReplaceAll]);
  Result := StringReplace(Result, #9, ' ', [rfReplaceAll]);
  Result := StringReplace(Result, #10, ' ', [rfReplaceAll]);
  Result := StringReplace(Result, #$C2#$AB, '"', [rfReplaceAll]); // « (U+00AB) in UTF-8
  Result := StringReplace(Result, #$C2#$BB, '"', [rfReplaceAll]); // » (U+00BB) in UTF-8
  Result := StringReplace(Result, #$E2#$80#$93, '-', [rfReplaceAll]);
  Result := StringReplace(Result, #$E2#$80#$94, '-', [rfReplaceAll]);
  Result := StringReplace(Result, #$E2#$80#$98, '''', [rfReplaceAll]); // ‘ (U+2018) in UTF-8
  Result := StringReplace(Result, #$E2#$80#$99, '''', [rfReplaceAll]); // ’ (U+2019) in UTF-8
  Result := StringReplace(Result, #$E2#$80#$9A, '''', [rfReplaceAll]); // ‚ (U+201A) in UTF-8
  Result := StringReplace(Result, #$E2#$80#$9B, '''', [rfReplaceAll]); // ‛ (U+201B) in UTF-8
  Result := StringReplace(Result, #$E2#$80#$9C, '"', [rfReplaceAll]); // “ (U+201C) in UTF-8
  Result := StringReplace(Result, #$E2#$80#$9D, '"', [rfReplaceAll]); // ” (U+201D) in UTF-8
  Result := StringReplace(Result, #$E2#$80#$9E, '"', [rfReplaceAll]); // „ (U+201E) in UTF-8
  Result := StringReplace(Result, #$E2#$80#$9F, '"', [rfReplaceAll]); // ‟ (U+201F) in UTF-8
  Result := StringReplace(Result, #$E2#$80#$B9, '''', [rfReplaceAll]); // ‹ (U+2039) in UTF-8
  Result := StringReplace(Result, #$E2#$80#$BA, '''', [rfReplaceAll]); // › (U+203A) in UTF-8
end;

procedure TSentenceSplitter.StorySplitProtos(const AStoryBody: string);
var
  TheBody: string;
  TheInQuote: Boolean;
  TheIndex: Integer;
  TheSentence: string;
  TheLastWord: string;
  TheChar: Char;
begin
  FWordList.Clear;
  TheBody := Trim(ReplaceInvalidStoryChars(AStoryBody));

  TheBody := StringReplace(TheBody, 'Mr.', 'Mr%&^', [rfReplaceAll, rfIgnoreCase]);
  TheBody := StringReplace(TheBody, 'Mrs.', 'Mrs%&^', [rfReplaceAll, rfIgnoreCase]);
  TheBody := StringReplace(TheBody, 'Ms.', 'Ms%&^', [rfReplaceAll, rfIgnoreCase]);
  TheBody := StringReplace(TheBody, 'e.g.', 'e%&^g%&^', [rfReplaceAll, rfIgnoreCase]);
  TheBody := StringReplace(TheBody, 'etc.', 'etc%&^', [rfReplaceAll, rfIgnoreCase]);
  TheBody := StringReplace(TheBody, 'i.e.', 'i%&^e%&^', [rfReplaceAll, rfIgnoreCase]);
  TheBody := StringReplace(TheBody, 'Dr.', 'Dr%&^', [rfReplaceAll, rfIgnoreCase]);
  TheBody := StringReplace(TheBody, 'Prof.', 'Prof%&^', [rfReplaceAll, rfIgnoreCase]);
  TheBody := StringReplace(TheBody, 'Sr.', 'Sr%&^', [rfReplaceAll, rfIgnoreCase]);
  TheBody := StringReplace(TheBody, 'Jr.', 'Jr%&^', [rfReplaceAll, rfIgnoreCase]);
  TheBody := StringReplace(TheBody, 'No.', 'No%&^', [rfReplaceAll, rfIgnoreCase]);
  TheBody := StringReplace(TheBody, 'St.', 'St%&^', [rfReplaceAll, rfIgnoreCase]);
  TheBody := StringReplace(TheBody, 'p.m.', 'p%&^m%&^', [rfReplaceAll, rfIgnoreCase]);

  //If the ENTIRE story is INSIDE ONLY one open and ONLY one closed quote, remove them BEFORE processing.
  if (Length(TheBody) > 1) and (TheBody[1] = '"') and (TheBody[Length(TheBody)] = '"') then
    TheBody := Copy(TheBody, 2, Length(TheBody) - 2);

  TheIndex := 1;
  while TheIndex <= Length(TheBody) do
  begin
    TheSentence := '';
    TheInQuote := False;
    TheLastWord := '';
    while TheIndex <= Length(TheBody) do
    begin
      TheChar := TheBody[TheIndex];
      TheLastWord := TheLastWord + TheChar;
      if not CharInSet(TheChar , ['a'..'z', 'A'..'Z', '.']) then
        TheLastWord := '';
      Inc(TheIndex);
      if TheChar = '"' then
        TheInQuote := not TheInQuote;
      if (not TheInQuote) and (TheChar = '.') then
        Break;
      if TheChar = '' then
        TheSentence := TrimRight(TheSentence)
      else
        TheSentence := TheSentence + TheChar;
      if (not TheInQuote) and CharInSet(TheChar, ['!', '?']) and (Length(TheSentence) > 1) then
        Break;
    end;
    TheSentence := Trim(StringReplace(TheSentence, '%&^', '.', [rfReplaceAll]));
    if TheSentence <> '' then
      FWordList.Add(TheSentence);
  end;
end;

end.
