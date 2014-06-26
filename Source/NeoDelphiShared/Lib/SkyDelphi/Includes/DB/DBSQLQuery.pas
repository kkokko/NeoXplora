unit DBSQLQuery;

interface

uses
  TypesConsts, StringArray;

type
  TDBSQLQuery = record
    Name: string;
    Query: TStringArray;
    Params: {$IFDEF VER210}TKeyStringValue.TArray{$ELSE}TKeyStringValueArray{$ENDIF};
  end;
  PDBSQLQuery = ^TDBSQLQuery;

procedure RegisterDBSQLQuery(AQuery: TDBSQLQuery);
procedure RegisterDBSQLQueries(const SomeQueries: array of TDBSQLQuery);

implementation

var
  _DBSQLQueries: array of TDBSQLQuery = nil;

procedure RegisterDBSQLQuery(AQuery: TDBSQLQuery);
var
  TheCount: Integer;
begin
  TheCount := Length(_DBSQLQueries);
  SetLength(_DBSQLQueries, TheCount + 1);
  _DBSQLQueries[TheCount] := AQuery;
end;

procedure RegisterDBSQLQueries(const SomeQueries: array of TDBSQLQuery);
var
  I: Integer;
begin
  for I := 0 to High(SomeQueries) do
    RegisterDBSQLQuery(SomeQueries[I]);
end;

end.
