unit ElementParseExternalLink;

interface

uses
  BaseParser;

type
  TElementParseExternalLink = class(TBaseParser)
  private
    procedure ParseLanguageElements;
  protected
    procedure AdjustBoundaries; override;
    function DoExecute: AnsiString; override;
  end;

implementation

uses
  SysUtils, Level4ParseLanguage;

{ TElementParseExternalLink }

procedure TElementParseExternalLink.AdjustBoundaries;
var
  TheContentLength: Integer;
  TheLevel: Integer;
  TheHasSubElements: Boolean;
begin
  Inc(FCursor, 1);
  FStart := FCursor;
  TheLevel := 1;
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
  TheContentLength := (FEnd - FStart) - 1;
  SetLength(FContent, TheContentLength);
  StrLCopy(PAnsiChar(FContent), FStart, TheContentLength);
  if TheHasSubElements then
    FContent := TLevel4ParseLanguage.Execute(FContent, FResultsObject);
end;

function TElementParseExternalLink.DoExecute: AnsiString;
begin
  ParseLanguageElements;

  FStart := PAnsiChar(FContent);
  FEnd := FStart + Length(FContent);
  FCursor := FStart;
  FLastCursor := FCursor;

  Result := FContent;
end;

procedure TElementParseExternalLink.ParseLanguageElements;
var
  TheLabel: AnsiString;
  TheLink: AnsiString;
  TheLinkSource: AnsiString;
begin
  TheLink := ReadContentWord(':') + ':' + ReadContentWord(' ');
  TheLabel := FContent;
  if (Length(TheLabel) > 0) and (TheLabel[1] = ':') then
    Delete(TheLabel, 1, 1);
  TheLabel := AnsiString(Trim(string(TheLabel)));
  if TheLabel = '' then
    TheLabel := TheLink;
  FResultsObject.AddExternalLink(string(TheLinkSource + TheLink), string(TheLabel));
end;

end.
