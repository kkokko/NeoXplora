unit ElementParseBlockQuote;

interface

uses
  BaseParser, ParseResult;

{ <blockquote>value</blockquote>}
type
  TElementParseBlockQuote = class(TBaseParser)
  protected
    function DoExecute: AnsiString; override;
  public
    class function Execute(const AParamsString, AValue: AnsiString; AResultsObject: TParseResult): AnsiString; overload;
  end;

implementation

uses
  LoggerUnit;

{ TElementParseBlockQuote }

function TElementParseBlockQuote.DoExecute: AnsiString;
begin
  Result := '"' + FContent + '"';
end;

class function TElementParseBlockQuote.Execute(const AParamsString,
  AValue: AnsiString; AResultsObject: TParseResult): AnsiString;
begin
  if AParamsString <> '' then
    TLogger.Warn(nil, [AResultsObject.Name, 'blockquote', 'Warning: blockquote with tag params', string(AParamsString)]);
  Execute(AValue, AResultsObject);
end;

end.
