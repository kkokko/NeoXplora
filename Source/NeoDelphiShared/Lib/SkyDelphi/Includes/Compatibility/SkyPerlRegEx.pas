unit SkyPerlRegEx;

interface

uses
  PerlRegEx;

type
  TSkyPerlRegEx = class helper for TPerlRegEx
  public
    class function ExecPerlRegExpr(const ARegExpr, AValue: string): Boolean;
  end;

implementation

{ TSkyPerlRegEx }

class function TSkyPerlRegEx.ExecPerlRegExpr(const ARegExpr, AValue: string): Boolean;
var
  ThePerlRegEx: TPerlRegEx;
begin
  ThePerlRegEx := TPerlRegEx.Create;
  try
    ThePerlRegEx.RegEx := UTF8String(ARegExpr);
    ThePerlRegEx.Subject := UTF8String(AValue);
    Result := ThePerlRegEx.Match;
  finally
    ThePerlRegEx.Free;
  end;
end;

end.
