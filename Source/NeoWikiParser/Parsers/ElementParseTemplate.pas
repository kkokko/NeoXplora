unit ElementParseTemplate;

interface

uses
  BaseParser;

// {{value}}
type
  TElementParseTemplate = class(TBaseParser)
  private
    procedure ParseLanguageElements;
  protected
    procedure AdjustBoundaries; override;
    function DoExecute: AnsiString; override;
  end;

implementation

uses
  SysUtils, Level4ParseLanguage;

{ TElementParseTemplate }

procedure TElementParseTemplate.AdjustBoundaries;
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
    if FCursor^ = '{' then
    begin
      TheHasSubElements := True;
      Inc(TheLevel);
    end
    else if FCursor^ = '}' then
    begin
      Dec(TheLevel);
      if (TheLevel < 1) and ((FCursor - 1)^ = '}') then
       FEnd := FCursor + 1;
    end
    else if (not TheHasSubElements) and CharInset(FCursor^,  TLevel4ParseLanguage.TLevel4ParseSpecialChars) then
      TheHasSubElements := True;
    Inc(FCursor);
  end;
  TheContentLength := (FEnd - FStart) - 2;
  SetLength(FContent, TheContentLength);
  StrLCopy(PAnsiChar(FContent), FStart, TheContentLength);
  if TheHasSubElements then
    FContent := TLevel4ParseLanguage.Execute(FContent, FResultsObject);
end;

function TElementParseTemplate.DoExecute: AnsiString;
begin
  Result := '';
  Exit;
  ParseLanguageElements;

  FStart := PAnsiChar(FContent);
  FEnd := FStart + Length(FContent);
  FCursor := FStart;
  FLastCursor := FCursor;

  Result := FContent;
end;

procedure TElementParseTemplate.ParseLanguageElements;
var
  TheTemplateName: AnsiString;
begin
  TheTemplateName := ReadContentWord('|');
//  FResultsObject.Templates := FResultsObject.Templates + 'Template(' + string(TheTemplateName) + '): ' + string(FContent) + #13#10;
  FContent := '';
end;

end.
