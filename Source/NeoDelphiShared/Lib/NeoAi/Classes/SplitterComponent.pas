unit SplitterComponent;

interface

uses
  SkyStringBuilder, SkyLists;

const
  ConstWordChars = ['a'..'z', 'A'..'Z', '0'..'9'];

type
  TSplitterComponent = class(TSkyStringBuilder)
{$Region '  Data types'}
  public
    type
      TChainLinkType = (cltWord, cltSeparator, cltSentence);
      TWordChainFilter = (wctAll, wctNoSpaces);
      PWordChain = ^TWordChain;
      TWordChain = record
        ChainLinkType: TChainLinkType;
        Start: Integer;
        // Word only fields
        Length: Integer;
        // Separator only fields
        SeparatorChar: Char; // separators are 1 char only
        // links
        Next: PWordChain;
        Previous: PWordChain;

        function IsOfType(AChainType: TWordChainFilter): Boolean;
      end;
      TWordChainWithInfoType = record
        Chain: PWordChain;
        Length: Integer;
      end;

      PSentenceChain = ^TSentenceChain;
      TSentenceChain = record
        Words: TWordChainWithInfoType;
        Next: PSentenceChain;
        Previous: PSentenceChain;
      end;
      TSentenceChainWithInfoType = record
        Chain: PSentenceChain;
        Length: Integer;
      end;
{$EndRegion}
  private
    FElementCount: Integer;
    FFirstElement: PWordChain;
    FLastElement: PWordChain;
    // parses chain and merges words + separators into simple words ex. that's 1'st  st. e.g. etc.
    procedure AdjustSplitWords;
    function CopyElement(AnElement: PWordChain): PWordChain;
    procedure DoSplitWords;
    // fix incorrect characters that can originate from copy msword, webpages etc
    procedure FixIncorrectCharacters;
    // frees SomeElements, updates links for First^.Previous and Last^.Next
    // if elements are contained by AFirstElement/ALastElement the var's are updated
    procedure FreeWordChainLinks(var AFirstElement: PWordChain; SomeElements: array of PWordChain; var ALastElement: PWordChain);
    function ReadSentenceWordsFromCursor(var AWordChainCursor: PWordChain; AWordChainFilter: TWordChainFilter): TWordChainWithInfoType;
    function NewChainLink: PWordChain; virtual;
    // checks if nodes to the left and right of AnElement
    // follow the the specified patterns to the left and right
    // ALeftPattern, ARightPattern - can contain:
    //   S -- match = true if the ChainLink is of type Separator or NULL
    //   W -- match = true if the ChainLink if of type Word
    //   else: -- match = true if the ChainLink is separator and Equals the specified Char
    // If the pattern matches it will be compared against AMatchList and if found will return true
    // ALL THE CONDITIONS MUST BE TRUE FOR  THE FUNCTION TO RETURN TRUE
    function TestPattern(const ALeftPattern: string; AnElement: PWordChain; const ARightPattern: string;
      const AMatchList: array of string; AUseElementText: Boolean = True): Boolean;
    procedure TrimWordChain(var AFirstElement, ALastElement: PWordChain);
    procedure UnQuoteWordChain;
    function ValidateElementWithPattern(AnElement: PWordChain; APattern: Char; out BPatternIsOk: Boolean): string;
  public
    constructor Create; override;

    // ------------ UTILITY METHODS ------------
    procedure Clear; override;
    procedure FreeSentenceChain(var AChain: PSentenceChain);
    procedure FreeWordChain(var AChain: PWordChain);
    // adds ANewWord to the StringBuilder and updates the current chain to point to it
    procedure ReplaceWordInLink(var AChain: PWordChain; const ANewWord: string);
    // removes the current chain from the link and applies logic on Previous / Next nodes
    // updates AChain to be fist node after the deleted Node(s)
    // if there are no nodes after Updates AChain to the last link in the chain and returns false
    // returns true if the algorithm can continue
    procedure RemoveWordInLink(var AChain: PWordChain);

    // ------------ DATA INPUT METHODS ------------
    // splits a sentence into words considering punctuation and English language rules
    procedure SentenceSplitWords(const ASentence: string); overload;
    procedure SentenceSplitWords(const ASentence: string; AWordChainFilter: TWordChainFilter; AList: TSkyStringStringList); overload;
    // split the Page body into sentences.
    // The result will be stored in WordList
    procedure PageSplitProtos(const APageBody: string);

    // ------------ OUTPUT METHODS ------------
    // returns a chain of sentences
    function NewSentenceChain(AWordChainFilter: TWordChainFilter): TSentenceChainWithInfoType;
    // returns a chain of words + separators
    function NewWordChain(AWordChainFilter: TWordChainFilter): TWordChainWithInfoType;
    procedure NewWordChainToStringStringList(AWordChainFilter: TWordChainFilter; AList: TSkyStringStringList);
    // create a string from a sentence chain
    function SentenceChainAsString(AChain: PSentenceChain; ASeparator: Char = #0): string;
    // create a string from a word chain
    function WordChainAsString(AChain: PWordChain; ASeparator: Char = #0): string;
    // write the string from a word chain to stringbuilder
    procedure WordChainToStringBuilder(AnElement: PWordChain; AResult: TSkyStringBuilder);
    // write the string from a word chain to stringlist
    procedure WordChainToStringStringList(AChain: PWordChain; AList: TSkyStringStringList);
    // write the string from a AnElement
    function WordChainLinkAsString(AnElement: PWordChain): string;

    property ElementCount: Integer read FElementCount;
    property FirstElement: PWordChain read FFirstElement;
  end;

implementation

uses
  SysUtils, TypesConsts;

{ TSplitterComponent }

procedure TSplitterComponent.AdjustSplitWords;
var
  TheElement: PWordChain;
  TheNext: PWordChain;
  ThePrevious: PWordChain;
begin
  ThePrevious := nil;
  TheElement := FFirstElement;
  while TheElement <> nil do
  begin
    TheNext := TheElement^.Next;
    // apostrophe between 2 words - merge 3 words into one
    // otherwise punctuation or quote
    if (TheElement^.ChainLinkType = cltSeparator) and (TheElement^.SeparatorChar = '''') and (TheElement^.Next <> nil) and
      (ThePrevious <> nil) and (ThePrevious^.ChainLinkType = cltWord) and (TheNext^.ChainLinkType = cltWord) then
    begin
      ThePrevious^.Length := ThePrevious^.Length + TheNext^.Length + 1;
      FreeWordChainLinks(FFirstElement, [TheElement, TheNext], FLastElement);
      TheElement := ThePrevious;
    end else if (TheElement^.ChainLinkType = cltSeparator) and (TheElement^.SeparatorChar = '.') then
    begin
      // test for: 'Mr.', 'Mrs.', 'Ms.', 'e.g.', 'etc.', 'i.e.', 'Dr.', 'Prof.', 'Sr.', 'Jr.', 'No.', 'St.'
      if TestPattern('SW', TheElement, 'S', ['mr.', 'mrs.', 'ms.', 'etc.', 'dr.', 'prof.', 'sr.', 'jr.', 'no.', 'st.']) then
      begin
        ThePrevious^.Next := TheElement^.Next;
        if ThePrevious^.Next <> nil then
          ThePrevious^.Next^.Previous := ThePrevious;
        ThePrevious^.Length := ThePrevious^.Length + 1;
        FreeMem(TheElement);
        TheElement := ThePrevious;
      end
      else if TestPattern('SW', TheElement, 'W.S', ['e.g.', 'i.e.']) then
      begin
        ThePrevious^.Length := ThePrevious^.Length + TheNext^.Length + 2;
        FreeWordChainLinks(FFirstElement, [TheElement, TheNext, TheNext^.Next], FLastElement);
        TheElement := ThePrevious;
      end;
    end;
    ThePrevious := TheElement;
    TheElement := TheElement^.Next;
  end;
end;

function TSplitterComponent.WordChainAsString(AChain: PWordChain; ASeparator: Char): string;
var
  TheChainLink: PWordChain;
begin
  Result := '';
  TheChainLink := AChain;
  while TheChainLink <> nil  do
  begin
    Result := Result + WordChainLinkAsString(TheChainLink);
    if ASeparator <> #0 then
      Result := Result + ASeparator;
    TheChainLink := TheChainLink^.Next;
  end;
end;

procedure TSplitterComponent.WordChainToStringStringList(AChain: PWordChain; AList: TSkyStringStringList);
var
  TheChainLink: PWordChain;
begin
  TheChainLink := AChain;
  while TheChainLink <> nil do
  begin
    AList.Add(WordChainLinkAsString(TheChainLink));
    TheChainLink := TheChainLink^.Next;
  end;
end;

procedure TSplitterComponent.Clear;
begin
  inherited Clear;
  if FLastElement <> nil then
    FreeWordChain(FFirstElement);
  FElementCount := 0;
  FFirstElement := nil;
  FLastElement := nil;
end;

function TSplitterComponent.SentenceChainAsString(AChain: PSentenceChain; ASeparator: Char): string;
var
  TheChain: PSentenceChain;
begin
  Result := '';
  TheChain := AChain;
  while TheChain <> nil do
  begin
    Result := Result + WordChainAsString(TheChain^.Words.Chain, ASeparator) + ReturnLf;
    TheChain := TheChain^.Next;
  end;
end;

procedure TSplitterComponent.SentenceSplitWords(const ASentence: string; AWordChainFilter: TWordChainFilter;
  AList: TSkyStringStringList);
begin
  SentenceSplitWords(ASentence);
  AList.Clear;
  NewWordChainToStringStringList(AWordChainFilter, AList);
end;

procedure TSplitterComponent.SentenceSplitWords(const ASentence: string);
begin
  Clear;
  Append(ASentence);
  DoSplitWords;
  AdjustSplitWords;
end;

function TSplitterComponent.ValidateElementWithPattern(AnElement: PWordChain; APattern: Char; out BPatternIsOk: Boolean): string;
begin
  BPatternIsOk := False;
  Result := '';
  case APattern of
    'S': begin
      if (AnElement <> nil) and (AnElement^.ChainLinkType = cltWord) then
        Exit;
      // add nothing to the string
    end;
    'W': begin
      if (AnElement = nil) or (AnElement^.ChainLinkType = cltSeparator) then
        Exit;
      Result := WordChainLinkAsString(AnElement);
    end
    else begin
      if (AnElement = nil) or (AnElement^.ChainLinkType = cltWord) then
        Exit;
      Result := WordChainLinkAsString(AnElement);
    end
  end;
  BPatternIsOk := True;
end;

function TSplitterComponent.TestPattern(const ALeftPattern: string; AnElement: PWordChain; const ARightPattern: string;
  const AMatchList: array of string; AUseElementText: Boolean): Boolean;
var
  TheElement: PWordChain;
  ThePatternIsOk: Boolean;
  TheString: string;
  I: Integer;
begin
  Result := False;
  TheString := '';
  // check left pattern
  TheElement := AnElement;
  for I := System.Length(ALeftPattern) downto 1 do
  begin
    if TheElement = nil then
      Exit;
    TheElement := TheElement^.Previous;
    TheString := ValidateElementWithPattern(TheElement, ALeftPattern[I], ThePatternIsOk) + TheString;
    if not ThePatternIsOk then
      Exit;
  end;
  if AUseElementText then
    TheString := TheString + WordChainLinkAsString(AnElement);

  // check right pattern
  TheElement := AnElement;
  for I := 1 to System.Length(ARightPattern) do
  begin
    if TheElement = nil then
      Exit;
    TheElement := TheElement^.Next;
    TheString := TheString + ValidateElementWithPattern(TheElement, ARightPattern[I], ThePatternIsOk);
    if not ThePatternIsOk then
      Exit;
  end;

  // Check result in list
  for I := 0 to High(AMatchList) do
    if SameText(AMatchList[I], TheString) then
    begin
      Result := True;
      Exit;
    end;
end;

procedure TSplitterComponent.TrimWordChain(var AFirstElement, ALastElement: PWordChain);
var
  TheElement: PWordChain;
begin
  // remove leading spaces
  while (AFirstElement <> nil) and (AFirstElement^.ChainLinkType = cltSeparator) and (' ' = AFirstElement^.SeparatorChar) do
  begin
    TheElement := AFirstElement^.Next;
    FreeMem(AFirstElement);
    AFirstElement := TheElement;
    AFirstElement^.Previous := nil;
  end;
  if AFirstElement = nil then
  begin
    ALastElement := nil;
    Exit;
  end;
  // remove trailing spaces
  while (ALastElement <> nil) and (ALastElement^.ChainLinkType = cltSeparator) and (' ' = ALastElement^.SeparatorChar) do
  begin
    TheElement := ALastElement^.Previous;
    FreeMem(ALastElement);
    ALastElement := TheElement;
    ALastElement^.Next := nil;
  end;
  if ALastElement = nil then
  begin
    AFirstElement := nil;
    Exit;
  end;
end;

procedure TSplitterComponent.UnQuoteWordChain;
var
  TheElement: PWordChain;
begin
  // remove quotes
  while (FFirstElement <> nil) and (FFirstElement^.ChainLinkType = cltSeparator) and
    ('"' = FFirstElement^.SeparatorChar) and ('"' = FLastElement^.SeparatorChar) do
  begin
    TheElement := FFirstElement^.Next;
    FreeMem(FFirstElement);
    FFirstElement := TheElement;
    FFirstElement^.Previous := nil;
    if FFirstElement <> nil then
    begin
      TheElement := FLastElement^.Previous;
      FreeMem(FLastElement);
      FLastElement := TheElement;
      FLastElement^.Next := nil;
    end else begin
      FLastElement := nil;
      Exit;
    end;
    if FLastElement = nil then
      FFirstElement := nil;
  end;
end;

procedure TSplitterComponent.PageSplitProtos(const APageBody: string);
begin
  Clear;
  Append(APageBody);
  FixIncorrectCharacters;
  DoSplitWords;
  TrimWordChain(FFirstElement, FLastElement);
  UnQuoteWordChain;
  AdjustSplitWords;
end;

function TSplitterComponent.CopyElement(AnElement: PWordChain): PWordChain;
begin
  Result := New(PWordChain);
  Result^.ChainLinkType := AnElement^.ChainLinkType;
  Result^.Start := AnElement^.Start;
  Result^.Length := AnElement^.Length;
  Result^.SeparatorChar := AnElement^.SeparatorChar;
  Result^.Previous := nil;
  Result^.Next := nil;
end;

constructor TSplitterComponent.Create;
begin
  inherited;
  FLastElement := nil;
  Clear;
end;

procedure TSplitterComponent.DoSplitWords;
var
  TheChainLink: PWordChain;
  TheIsWord: Boolean;
  TheLength: Integer;
  ThePosition: Integer;
  TheStart: Integer;
begin
  // simple split
  TheStart := 0;
  TheLength := Length;
  while TheStart < TheLength do
  begin
    ThePosition := TheStart;
    TheIsWord := False;
    while (ThePosition < TheLength) and CharInSet(FData[ThePosition], ConstWordChars) do
    begin
      TheIsWord := True;
      Inc(ThePosition);
    end;
    TheChainLink := NewChainLink;
    TheChainLink^.Start := TheStart;
    if TheIsWord then
    begin
      TheChainLink^.ChainLinkType := cltWord;
      TheChainLink^.Length := ThePosition - TheStart;
    end
    else
    begin
      TheChainLink^.ChainLinkType := cltSeparator;
      TheChainLink^.SeparatorChar := FData[TheStart];
      Inc(ThePosition);
    end;
    TheStart := ThePosition;
  end;
end;

function TSplitterComponent.WordChainLinkAsString(AnElement: PWordChain): string;
begin
  if AnElement^.ChainLinkType = cltSeparator then
    Result := AnElement^.SeparatorChar
  else
    Result := ToStringNoChecks(AnElement^.Start, AnElement^.Length);
end;

procedure TSplitterComponent.WordChainToStringBuilder(AnElement: PWordChain; AResult: TSkyStringBuilder);
var
  TheElement: PWordChain;
begin
  TheElement := AnElement;
  while TheElement <> nil do
  begin
    AResult.Append(WordChainLinkAsString(TheElement));
    TheElement := TheElement^.Next;
  end;
end;

procedure TSplitterComponent.FixIncorrectCharacters;
begin
  Replace(#13, '');
  Replace(#9, ' ');
  Replace(#10, ' ');
  Replace(#$C2#$AB, '"'); // « (U+00AB) in UTF-8
  Replace(#$C2#$BB, '"'); // » (U+00BB) in UTF-8
  Replace(#$E2#$80#$93, '-');
  Replace(#$E2#$80#$94, '-');
  Replace(#$E2#$80#$98, ''''); // ‘ (U+2018) in UTF-8
  Replace(#$E2#$80#$99, ''''); // ’ (U+2019) in UTF-8
  Replace(#$E2#$80#$9A, ''''); // ‚ (U+201A) in UTF-8
  Replace(#$E2#$80#$9B, ''''); // ? (U+201B) in UTF-8
  Replace(#$E2#$80#$9C, '"'); // “ (U+201C) in UTF-8
  Replace(#$E2#$80#$9D, '"'); // ” (U+201D) in UTF-8
  Replace(#$E2#$80#$9E, '"'); // „ (U+201E) in UTF-8
  Replace(#$E2#$80#$9F, '"'); // ? (U+201F) in UTF-8
  Replace(#$E2#$80#$B9, ''''); // ‹ (U+2039) in UTF-8
  Replace(#$E2#$80#$BA, ''''); // › (U+203A) in UTF-8
end;

procedure TSplitterComponent.FreeSentenceChain(var AChain: PSentenceChain);
var
  TheLink: PSentenceChain;
  TheNext: PSentenceChain;
begin
  TheLink := AChain;
  while TheLink <> nil do
  begin
    TheNext := TheLink^.Next;
    FreeWordChain(TheLink^.Words.Chain);
    FreeMem(TheLink);
    TheLink := TheNext;
  end;
end;

procedure TSplitterComponent.FreeWordChain(var AChain: PWordChain);
var
  TheLink: PWordChain;
  TheNext: PWordChain;
begin
  TheLink := AChain;
  while TheLink <> nil do
  begin
    TheNext := TheLink^.Next;
    FreeMem(TheLink);
    TheLink := TheNext;
  end;
end;

procedure TSplitterComponent.FreeWordChainLinks(var AFirstElement: PWordChain; SomeElements: array of PWordChain;
  var ALastElement: PWordChain);
var
  TheFirst, TheLast: PWordChain;
  TheLength: Integer;
  I: Integer;
begin
  TheLength := System.Length(SomeElements);
  TheFirst := SomeElements[0];
  TheLast := SomeElements[TheLength - 1];
  if TheFirst^.Previous = nil then
    AFirstElement := TheLast^.Next
  else
    TheFirst^.Previous^.Next := TheLast^.Next;
  if TheLast^.Next = nil then
    ALastElement := TheFirst^.Previous
  else
    TheLast^.Next^.Previous := TheFirst^.Previous;
  for I := 0 to TheLength - 1 do
    FreeMem(SomeElements[I]);
end;

function TSplitterComponent.NewChainLink: PWordChain;
begin
  Result := New(PWordChain);
  if FFirstElement = nil then
    FFirstElement := Result
  else
    FLastElement^.Next := Result;
  Result^.Previous := FLastElement;
  Result^.Next := nil;
  FLastElement := Result;
end;

function TSplitterComponent.NewSentenceChain(AWordChainFilter: TWordChainFilter): TSentenceChainWithInfoType;
var
  TheElement: PWordChain;
  TheSentence: PSentenceChain;
  ThePreviousSentence: PSentenceChain;
begin
  Result.Length := 0;
  Result.Chain := nil;

  TheElement := FFirstElement;
  while (TheElement <> nil) and (not TheElement^.IsOfType(AWordChainFilter)) do
    TheElement := TheElement^.Next;

  ThePreviousSentence := nil;
  while TheElement <> nil do
  begin
    TheSentence := New(PSentenceChain);
    TheSentence^.Previous := ThePreviousSentence;
    TheSentence^.Next := nil;
    if ThePreviousSentence <> nil then
      ThePreviousSentence^.Next := TheSentence;
    if Result.Chain = nil then
      Result.Chain := TheSentence;
    TheSentence^.Words := ReadSentenceWordsFromCursor(TheElement, AWordChainFilter);
    Inc(Result.Length);
    ThePreviousSentence := TheSentence;
  end;
end;

function TSplitterComponent.ReadSentenceWordsFromCursor(var AWordChainCursor: PWordChain;
  AWordChainFilter: TWordChainFilter): TWordChainWithInfoType;
var
  TheLastElement: PWordChain;
  TheNewElement: PWordChain;
  TheIsInQuote: Boolean;
begin
  Result.Length := 0;
  Result.Chain := nil;
  // find first element in chain that corresponds to the type
  while (AWordChainCursor <> nil) and (not AWordChainCursor^.IsOfType(AWordChainFilter)) do
    AWordChainCursor := AWordChainCursor^.Next;
  if AWordChainCursor = nil then
    Exit;
  TheIsInQuote := (AWordChainCursor^.ChainLinkType = cltSeparator) and (AWordChainCursor^.SeparatorChar = '"');
  Result.Chain := CopyElement(AWordChainCursor);
  TheLastElement := Result.Chain;
  AWordChainCursor := AWordChainCursor^.Next;
  while (AWordChainCursor <> nil) do
  begin
    if AWordChainCursor^.IsOfType(AWordChainFilter) then
    begin
      if (AWordChainCursor^.ChainLinkType = cltSeparator) and (AWordChainCursor^.SeparatorChar = '"') then
        TheIsInQuote := not TheIsInQuote;
      TheNewElement := CopyElement(AWordChainCursor);
      TheNewElement^.Previous := TheLastElement;
      TheLastElement^.Next := TheNewElement;
      TheLastElement := TheNewElement;
      Inc(Result.Length);
      if (not TheIsInQuote) and (AWordChainCursor^.ChainLinkType = cltSeparator) and
        CharInSet(AWordChainCursor^.SeparatorChar, ['.', '!', '?']) then
      begin
        TrimWordChain(Result.Chain, TheLastElement);
        AWordChainCursor := AWordChainCursor^.Next;
        Exit;
      end;
    end;
    AWordChainCursor := AWordChainCursor^.Next;
  end;
  TrimWordChain(Result.Chain, TheLastElement);
end;

procedure TSplitterComponent.RemoveWordInLink(var AChain: PWordChain);
var
  TheLink: PWordChain;
begin
  TheLink := AChain;
  // first word + space
  if TestPattern('N', TheLink, ' ', [' '], False) then
  begin
    AChain := TheLink^.Next^.Next;
    FreeWordChainLinks(FFirstElement, [TheLink, TheLink^.Next], FLastElement);
  end
  // space + last element
  else if TestPattern(' ', TheLink, 'N', [' '], False) then
  begin
    AChain := TheLink^.Previous^.Previous;
    FreeWordChainLinks(FFirstElement, [TheLink^.Previous, TheLink], FLastElement);
  end
  // space + word/separator + space
  else if TestPattern(' ', TheLink, ' ', ['  '], False) then
  begin
    AChain := TheLink^.Previous;
    FreeWordChainLinks(FFirstElement, [TheLink, TheLink^.Next], FLastElement);
  end
  // remove current node only
  else begin
    AChain := TheLink^.Previous;
    FreeWordChainLinks(FFirstElement, [TheLink], FLastElement);
  end;
end;

procedure TSplitterComponent.ReplaceWordInLink(var AChain: PWordChain; const ANewWord: string);
begin
  AChain.Start := Length;
  AChain.Length := System.Length(ANewWord);
  Append(ANewWord);
end;

function TSplitterComponent.NewWordChain(AWordChainFilter: TWordChainFilter): TWordChainWithInfoType;
var
  TheElement: PWordChain;
  TheLastElement: PWordChain;
  TheNewElement: PWordChain;
begin
  Result.Length := 0;
  Result.Chain := nil;
  // find first element in chain that corresponds to the type
  TheElement := FFirstElement;
  while (TheElement <> nil) and (not TheElement^.IsOfType(AWordChainFilter)) do
    TheElement := TheElement^.Next;
  if TheElement = nil then
    Exit;
  Result.Chain := CopyElement(TheElement);
  TheLastElement := Result.Chain;
  TheElement := TheElement^.Next;
  while (TheElement <> nil) do
  begin
    if TheElement^.IsOfType(AWordChainFilter) then
    begin
      TheNewElement := CopyElement(TheElement);
      TheNewElement^.Previous := TheLastElement;
      TheLastElement^.Next := TheNewElement;
      TheLastElement := TheNewElement;
      Inc(Result.Length);
    end;
    TheElement := TheElement^.Next;
  end;
end;

procedure TSplitterComponent.NewWordChainToStringStringList(AWordChainFilter: TWordChainFilter;
  AList: TSkyStringStringList);
var
  TheElement: PWordChain;
begin
  TheElement := FFirstElement;
  while (TheElement <> nil) do
  begin
    if TheElement^.IsOfType(AWordChainFilter) then
      AList.Add(WordChainLinkAsString(TheElement));
    TheElement := TheElement^.Next;
  end;
end;

{ TSplitterComponent.TWordChain }

function TSplitterComponent.TWordChain.IsOfType(AChainType: TWordChainFilter): Boolean;
begin
  Result := (wctAll = AChainType) or (ChainLinkType <> cltSeparator) or (SeparatorChar <> ' ');
end;

end.
