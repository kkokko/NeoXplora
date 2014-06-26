unit Level3Prep;

interface

uses
  BaseParser, ParseResults;

type
  TLevel3Prep = class(TBaseParser)
  public
    class function Execute(const AString: AnsiString; AResultsObject: TParseResults): AnsiString; override;
  end;

implementation

uses
  SysUtils, AppUtils;

{ TLevel3Prep }

class function TLevel3Prep.Execute(const AString: AnsiString; AResultsObject: TParseResults): AnsiString;
begin
  Result := FastStringReplace(AString, '&nbsp;...', '$^&%$#!@#', [rfReplaceAll]);
  Result := FastStringReplace(Result, '}.', '}', [rfReplaceAll]);
  Result := FastStringReplace(Result, '.{', '{', [rfReplaceAll]);
  Result := FastStringReplace(Result, '.''''', '''''', [rfReplaceAll]);
  Result := FastStringReplace(Result, '.==', #13#10'==', [rfReplaceAll]);
  Result := FastStringReplace(Result, '==.', '==', [rfReplaceAll]);
  Result := FastStringReplace(Result, '].', '] ', [rfReplaceAll]);
  Result := FastStringReplace(Result, '.[', ' [', [rfReplaceAll]);
  Result := FastStringReplace(Result, '.*', #13#10'*', [rfReplaceAll]);
  Result := FastStringReplace(Result, '...', '.'#13#10, [rfReplaceAll]);
  Result := FastStringReplace(Result, '..', #13#10, [rfReplaceAll]);
  Result := FastStringReplace(Result, '''''''''''', '', [rfReplaceAll]); // bold italic
  Result := FastStringReplace(Result, '''''''', '', [rfReplaceAll]); // bold
  Result := FastStringReplace(Result, '''''', '', [rfReplaceAll]); // italic
  Result := FastStringReplace(Result, '$^&%$#!@#', '...', [rfReplaceAll]);
  Result := FastStringReplace(Result, #13#10'==', #13#10#13#10'==', [rfReplaceAll]);
  Result := FastStringReplace(Result, #13#10#13#10#13#10, #13#10#13#10, [rfReplaceAll]);
end;

end.
