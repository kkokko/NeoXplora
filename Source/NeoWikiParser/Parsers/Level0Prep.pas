unit Level0Prep;

interface

uses
  BaseParser, ParseResult;

type
  TLevel0Prep = class(TBaseParser)
  protected
    function DoExecute: AnsiString; override;
  public
    class function Execute(const AString: AnsiString; AResultsObject: TParseResult): AnsiString; override;
  end;

implementation

uses
  SysUtils, AppUtils;

{ TLevel0Prep }

function TLevel0Prep.DoExecute: AnsiString;
begin
  Result := '';
end;

class function TLevel0Prep.Execute(const AString: AnsiString; AResultsObject: TParseResult): AnsiString;
begin
  Result := FastStringReplace(AString, '&lt;', '<', [rfReplaceAll]);
  Result := FastStringReplace(Result, '&gt;', '>', [rfReplaceAll]);
  Result := FastStringReplace(Result, '&quot;', '"', [rfReplaceAll]);
  Result := FastStringReplace(Result, '&amp;', '&', [rfReplaceAll]);
end;

end.
