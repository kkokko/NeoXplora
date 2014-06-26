unit ElementParseInternalLink;

interface

uses
  BaseParser;

type
  TElementParseInternalLink = class(TBaseParser)
  private
    procedure ParseLanguageElements;
  protected
    procedure AdjustBoundaries; override;
    function DoExecute: AnsiString; override;
  end;

implementation

uses
  SysUtils, Level4ParseLanguage, LoggerUnit;

{ TElementParseInternalLink }

procedure TElementParseInternalLink.AdjustBoundaries;
var
  TheContentLength: Integer;
  TheLevel: Integer;
  TheHasSubElements: Boolean;
begin
  Inc(FCursor, 2);
  FStart := FCursor;
  TheLevel := 2;
  TheHasSubElements := False;
  while (FCursor < FEnd) do
  begin
    if FCursor^ = '[' then
    begin
      TheHasSubElements := True;
      Inc(TheLevel);
    end
    else if FCursor^ = ']' then
    begin
      Dec(TheLevel);
      if (TheLevel < 1) then
       FEnd := FCursor + 1;
    end
    else if (not TheHasSubElements) and CharInset(FCursor^,  TLevel4ParseLanguage.TLevel4ParseSpecialChars) then
        TheHasSubElements := True;
    Inc(FCursor);
  end;
  TheContentLength := (FEnd - FStart) - 2;
  SetLength(FContent, TheContentLength);
  if TheContentLength > 0 then
    StrLCopy(PAnsiChar(FContent), FStart, TheContentLength);
  if TheHasSubElements then
    FContent := TLevel4ParseLanguage.Execute(FContent, FResultsObject);
end;

function TElementParseInternalLink.DoExecute: AnsiString;
begin
  ParseLanguageElements;

  FStart := PAnsiChar(FContent);
  FEnd := FStart + Length(FContent);
  FCursor := FStart;
  FLastCursor := FCursor;

  Result := FContent;
end;

procedure TElementParseInternalLink.ParseLanguageElements;
var
  TheLabel: AnsiString;
  TheLink: AnsiString;
  TheLinkSource: AnsiString;
begin
  TheLink := ReadContentWord('|');
  TheLabel := AnsiString(Trim(string(ReadContentWord('|'))));
  if FContent <> '' then
  begin
    repeat
      TheLabel := ReadContentWord('|');
    until (FContent = '');
    FContent := TheLabel;
    TheLabel := '';
  end;

  TheLinkSource := ReadContentWord(TheLink, ':');
  if TheLink <> '' then
  begin
    TheLinkSource := TheLinkSource + ':' + TheLink;
    TheLink := '';
  end;

  if TheLabel = '' then
    TheLabel := TheLinkSource;
  if FContent = '' then
    FContent := TheLabel;
  FResultsObject.AddInternalLink('http://en.wikipedia.org/wiki/' + string(TheLinkSource), string(TheLabel));
end;

end.
