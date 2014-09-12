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

implementation

end.
