unit Level5Prep;

interface

uses
  BaseParser, ParseResults;

type
  TLevel5Prep = class(TBaseParser)
  public
    class function Execute(const AString: AnsiString; AResultsObject: TParseResults): AnsiString; override;
  end;

implementation

uses
  SysUtils, AppUtils;

{ TLevel5Prep }

class function TLevel5Prep.Execute(const AString: AnsiString; AResultsObject: TParseResults): AnsiString;
begin
  Result := FastStringReplace(AString, '&nbsp;', '', [rfReplaceAll]);
  Result := FastStringReplace(Result, '* *', '*', [rfReplaceAll]);
  Result := FastStringReplace(Result, #13#10'.', '.'#13#10, [rfReplaceAll]);
  Result := FastStringReplace(Result, '*', #13#10'*', [rfReplaceAll]);
  Result := FastStringReplace(Result, '*'#13#10, '', [rfReplaceAll]);
  Result := FastStringReplace(Result, #13#10#13#10'*', #13#10'*', [rfReplaceAll]);
  Result := FastStringReplace(Result, '...', '......', [rfReplaceAll]);
  Result := FastStringReplace(Result, '..', '.', [rfReplaceAll]);
end;

end.
