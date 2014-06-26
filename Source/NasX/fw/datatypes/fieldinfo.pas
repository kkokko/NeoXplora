unit FieldInfo;

{$mode objfpc}{$H+}

interface

uses
  TypInfo;

type
  TFieldInfo = record
    FieldName: string;
    FieldType: string;
    FieldKind: TTypeKind;
  end;
  TFieldInfoArray = array of TFieldInfo;

implementation

end.
