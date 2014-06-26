unit EntityFieldNamesToken;

{$mode objfpc}{$H+}

interface

type
  TEntityFieldNamesToken = record
    TokenId: Integer;
    TokenString: string;
    FieldDef: string; // containst field definition ex: [varchar](50)
  end;
  TEntityFieldNamesTokenArray = array of TEntityFieldNamesToken;

  function GetEntityFieldNamesTokenAsArray(AToken: TEntityFieldNamesToken): TEntityFieldNamesTokenArray;

implementation

{ TEntityFieldNamesToken }

function GetEntityFieldNamesTokenAsArray(AToken: TEntityFieldNamesToken): TEntityFieldNamesTokenArray;
begin
  SetLength(Result, 1);
  Result[0] := AToken;
end;

end.
