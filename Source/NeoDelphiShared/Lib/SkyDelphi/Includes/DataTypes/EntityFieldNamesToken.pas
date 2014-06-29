unit EntityFieldNamesToken;

interface

type
  TEntityFieldNamesToken = record
    var
      TokenString: string;
      FieldDef: string; // containst field definition ex: [varchar](50)
    function ClassName: string;
    function PropertyName: string;
    function SQLToken: string;
 {$IFDEF UNICODE}
    type
      TArray = array of TEntityFieldNamesToken;
    class function ArrayOfValues(SomeValues: array of TEntityFieldNamesToken): TArray; static;
    function AsArray: TArray;
  end;
  {$ELSE}
  end;
  TEntityFieldNamesTokenArray = array of TEntityFieldNamesToken;
  function GetEntityFieldNamesTokenAsArray(AToken: TEntityFieldNamesToken): TEntityFieldNamesTokenArray;
 {$ENDIF}

implementation

{ TEntityFieldNamesToken }

 {$IFDEF UNICODE}
class function TEntityFieldNamesToken.ArrayOfValues(SomeValues: array of TEntityFieldNamesToken): TArray;
var
  I: Integer;
begin
  SetLength(Result, Length(SomeValues));
  for I := 0 to High(SomeValues) do
    Result[I] := SomeValues[I];
end;

function TEntityFieldNamesToken.AsArray: TArray;
begin
  SetLength(Result, 1);
  Result[0] := Self;
end;

  {$ELSE}
function GetEntityFieldNamesTokenAsArray(AToken: TEntityFieldNamesToken): TEntityFieldNamesTokenArray;
begin
  SetLength(Result, 1);
  Result[0] := AToken;
end;

 {$ENDIF}

function TEntityFieldNamesToken.ClassName: string;
var
  TheIndex: Integer;
begin
  TheIndex := Pos('.', TokenString);
  Result := Copy(TokenString, 1, TheIndex);
end;

function TEntityFieldNamesToken.PropertyName: string;
var
  TheIndex: Integer;
begin
  TheIndex := Pos('.', TokenString);
  Result := Copy(TokenString, TheIndex + 1, Length(TokenString) - TheIndex);
end;

function TEntityFieldNamesToken.SQLToken: string;
begin
  Result := '!' + TokenString;
end;

end.

