unit Level2ParseTags;

interface

uses
  BaseParser;

type
  TLevel2ParseTags = class(TBaseParser)
  private
    function FindTagStart: AnsiString;
    function FindTagEnd: AnsiString;
    function ReadTag(const ATagName: AnsiString): AnsiString;

    function HandleTag(const AName, AParamsString, AValue: AnsiString): AnsiString;
  protected
    function DoExecute: AnsiString; override;
  end;

implementation

uses
  SysUtils, LoggerUnit, ElementParseTagRef, ElementParseBlockQuote;

{ TLevel2ParseTags }

function TLevel2ParseTags.DoExecute: AnsiString;
var
  TheTagName: AnsiString;
begin
  Result := '';
  while FCursor < FEnd do
  begin
    TheTagName := FindTagStart;
    while FLastCursor < FCursor do
    begin
      Result := Result + FLastCursor^;
      Inc(FLastCursor);
    end;
    Result := Result + ReadTag(TheTagName);
    FLastCursor := FCursor;
  end;
end;

function TLevel2ParseTags.FindTagStart: AnsiString;
var
  TheCursor: PAnsiChar;
begin
  Result := '';
  TheCursor := nil;
  repeat
    while (FCursor < FEnd) and (FCursor^ <> '<') do
      Inc(FCursor);
    // if proper Tag
    if (FCursor + 1 < FEnd) and (CharInSet(FCursor[1], ['a'..'z', 'A'..'Z'])) then
    begin
      TheCursor := (FCursor + 1);
      Break;
    end;
    Inc(FCursor);
  until FCursor >= FEnd;
  if TheCursor = nil then
    Exit;
  while (TheCursor < FEnd) and (not CharInSet(TheCursor^, [' ', '/', '>'])) do
  begin
    Result := Result + TheCursor^;
    Inc(TheCursor);
  end;
end;

function TLevel2ParseTags.HandleTag(const AName, AParamsString, AValue: AnsiString): AnsiString;
begin
  if AName = 'ref' then
  begin
    //vio - refs disabled
    //Result := TElementParseTagRef.Execute(AParamsString, AValue, FResultsObject)
    Result := '';
  end
  else if (AName = 'blockquote') then
    Result := TElementParseBlockQuote.Execute(AParamsString, AValue, FResultsObject)
  else if AName = 'br' then
    Result := #13#10
  else begin
    Result := AValue;
    FResultsObject.AddUnknownTag(string(AName));
  end;
end;

function TLevel2ParseTags.FindTagEnd: AnsiString;
var
  TheCursor: PAnsiChar;
begin
  Result := '';
  TheCursor := nil;
  repeat
    while (FCursor < FEnd) and (FCursor^ <> '<') do
      Inc(FCursor);
    // if proper Tag
    if (FCursor + 1 < FEnd) and (FCursor[1] = '/') then
    begin
      TheCursor := (FCursor + 2);
      Break;
    end;
    Inc(FCursor);
  until FCursor >= FEnd;
  if TheCursor = nil then
    Exit;
  while (TheCursor < FEnd) and (TheCursor^ <> '>') do
  begin
    Result := Result + TheCursor^;
    Inc(TheCursor);
  end;
end;

function TLevel2ParseTags.ReadTag(const ATagName: AnsiString): AnsiString;
var
  TheLastCursor: PAnsiChar;
  TheTagName: AnsiString;
  TheTagParams: AnsiString;
  TheTagValue: AnsiString;
begin
  if (FCursor = FEnd) or (FCursor^ <> '<') then
    Exit;
  TheTagParams := '';
  TheTagValue := '';

  Inc(FCursor, Length(ATagName) + 1);
  while (FCursor^ <> '>') and (FCursor < FEnd) do
  begin
    if FCursor^ = '<' then
      TLogger.Warn(nil, [FResultsObject.Name, 'Invalid tag', 'Warning: < character found in tag start', ' tag text: ' +  string(TheTagValue)]);
    TheTagParams := TheTagParams + FCursor^;
    Inc(FCursor);
  end;
  TheTagParams := AnsiString(Trim(string(TheTagParams)));
  // simple tag, exiting
  if  (FCursor - 1)^ = '/' then
  begin
    SetLength(TheTagParams, Length(TheTagParams) - 1);
    Inc(FCursor);
    Result := HandleTag(AnsiStrLower(PAnsiChar(ATagName)), TheTagParams, TheTagValue);
    Exit;
  end;

  Inc(FCursor);
  TheLastCursor := FCursor;
  repeat
    TheTagName := FindTagEnd;
    Inc(FCursor);
  until (TheTagName = ATagName) or (FCursor >= FEnd);

  while TheLastCursor < FCursor do
  begin
    TheTagValue := TheTagValue + TheLastCursor^;
    Inc(TheLastCursor);
  end;
  SetLength(TheTagValue, Length(TheTagValue) - 1);

  while (FCursor^ <> '>') and (FCursor < FEnd) do
  begin
    if FCursor^ = '<' then
      TLogger.Warn(nil, [FResultsObject.Name, 'Invalid tag', 'Warning: < character found in tag close', ' tag text: ' +  string(TheTagValue)]);
    Inc(FCursor);
  end;
  Result := HandleTag(AnsiStrLower(PAnsiChar(ATagName)), TheTagParams, TheTagValue);
  Inc(FCursor);
end;

end.
