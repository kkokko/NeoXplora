unit Level4ParseLanguage;

interface

uses
  BaseParser;

type
  TLevel4ParseLanguage = class(TBaseParser)
  public
    const
      TLevel4ParseSpecialChars = ['{', '}', '[', ']'];
  private
    type
      TLanguageElementType = (
        // elements picked up at the 1'st pass
        letNone,
        letTemplate,         // {{value}}
        letInternalLink,     // [[value]] or [http
        letExternalLink,     // [[value]] or [http
        letHorizontalLine,   // ----(or more)
        // handled errors
        letEBigTemplate         // {{{ value }}}
      );
  private
    function FindLanguageElement: TLanguageElementType;
    function ReadLanguageElement(AType: TLanguageElementType): AnsiString;
  protected
    function DoExecute: AnsiString; override;
  end;

implementation

uses
  SysUtils, LoggerUnit, ElementParseTemplate, ElementParseInternalLink,
  ElementParseExternalLink;

{ TLevel4ParseLanguage }

function TLevel4ParseLanguage.DoExecute: AnsiString;
var
  TheElementType: TLanguageElementType;
begin
  Result := '';
  while FCursor < FEnd do
  begin
    TheElementType := FindLanguageElement;
    while FLastCursor < FCursor do
    begin
      Result := Result + FLastCursor^;
      Inc(FLastCursor);
    end;
    if TheElementType <> letNone then
      Result := Result + ReadLanguageElement(TheElementType);
    FLastCursor := FCursor;
  end;
end;

function TLevel4ParseLanguage.FindLanguageElement: TLanguageElementType;
begin
  Result := letNone;
  repeat
    while (FCursor < FEnd) and (not CharInSet(FCursor^, ['[', '{', '''', '=', '~', '<', '-'])) do
      Inc(FCursor);
    if not (FCursor < FEnd) then
      Exit;
    // check if proper Tag
    case FCursor^ of
      '{': begin
        if (FCursor + 1 <= FEnd) and ((FCursor + 1)^ = '{') then
          if (FCursor + 2 <= FEnd) and ((FCursor + 2)^ = '{') then
            Result := letEBigTemplate
          else
            Result := letTemplate
      end;
      '[': begin
        if (FCursor + 1 <= FEnd) and ((FCursor + 1)^ = '[') then
          Result := letInternalLink
        else if (FCursor + 4 <= FEnd) and ((FCursor + 1)^ = 'h')
          and ((FCursor + 2)^ = 't') and ((FCursor + 3)^ = 't') and ((FCursor + 4)^ = 'p') then
          Result := letExternalLink
      end;
      '-': begin
        if (FCursor + 3 <= FEnd) and ((FCursor + 1)^ = '-')
          and ((FCursor + 2)^ = '-') and ((FCursor + 3)^ = '-') then
          Result := letHorizontalLine;
      end;
    end;
    if Result <> letNone then
      Exit;
    Inc(FCursor);
  until FCursor >= FEnd;
end;

function TLevel4ParseLanguage.ReadLanguageElement(AType: TLanguageElementType): AnsiString;
var
  TheClassType: TBaseParserClass;
begin
  // get rid of errors first
  case AType of
    letEBigTemplate: begin
      while FCursor^ = '{' do
      begin
        Result := Result + '{';
        Inc(FCursor);
      end;
      TLogger.Warn(nil, [FResultsObject.Name, 'BigTemplate', 'Warning: big template "{" ignored']);
      Exit;
    end;
    letNone:
      Exit;
    letTemplate:
      TheClassType := TElementParseTemplate;
    letInternalLink:
      TheClassType := TElementParseInternalLink;
    letExternalLink:
      TheClassType := TElementParseExternalLink;
    letHorizontalLine:
      TheClassType := TElementParseInternalLink;
    else
      Exit;
  end;
  Result := TheClassType.Execute(@FCursor, FEnd, FResultsObject);
end;

end.
