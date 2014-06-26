unit SQLUtils;

interface

type
  TSQLUtils = class
  private
    const SQLChars: set of Byte = [0, 10, 13, 26, 34, 39, 92];
  public
    class function SqlStr(AString: string): string;
  end;
implementation

{ TSQLUtils }

class function TSQLUtils.SqlStr(AString: string): string;
var
  TheString: AnsiString;
  TheResult: AnsiString;
  I: integer;
begin
  TheString := AnsiString(AString);
  TheResult := '';
  for I := 1 to Length(TheString) do
  begin
    if not (Byte(TheString[I]) in SQLChars) then
    begin
      TheResult := TheResult + TheString[I];
      Continue;
    end;
    TheResult := TheResult + #92;
    case TheString[I] of
      #0: TheResult := TheResult +  #48;
      #10: TheResult := TheResult +  #110;
      #13: TheResult := TheResult +  #114;
      #26: TheResult := TheResult +  #90;
      #34: TheResult := TheResult +  #34;
      #39: TheResult := TheResult +  #39;
      #92: TheResult := TheResult +  #92;
    end;
  end;
  Result := string(TheResult);
end;

end.
