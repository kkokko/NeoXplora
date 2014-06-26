unit ElementParseTagRef;

interface

uses
  BaseParser, ParseResults;

{ <ref params>value</ref>}
type
  TElementParseTagRef = class(TBaseParser)
  protected
    function DoExecute: AnsiString; override;
  public
    class function Execute(const AParamsString, AValue: AnsiString; AResultsObject: TParseResults): AnsiString; overload;
  end;

implementation

uses
  Level4ParseLanguage;

{ TElementParseTagRef }

function TElementParseTagRef.DoExecute: AnsiString;
begin
  Result := '';
end;

class function TElementParseTagRef.Execute(const AParamsString, AValue: AnsiString;
  AResultsObject: TParseResults): AnsiString;
var
  TheValue: AnsiString;
begin
  if AValue <> '' then
  begin
    TheValue := TLevel4ParseLanguage.Execute(AValue, AResultsObject);
    if TheValue <> '' then
      AResultsObject.AddRef(string(AParamsString), string(TheValue));
  end;
  Result := '';
end;

end.