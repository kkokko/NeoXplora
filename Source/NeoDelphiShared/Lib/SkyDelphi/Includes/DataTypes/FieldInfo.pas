unit FieldInfo;

interface

uses
  TypInfo;

type
  TFieldInfo = record
    FieldName: string;
    FieldType: string;
    FieldKind: TTypeKind;
 {$IFDEF UNICODE}
    type
      TArray = array of TFieldInfo;
      TPointer = ^TFieldInfo;
  end;
  {$ELSE}
  end;
  TFieldInfoArray = array of TFieldInfo;
 {$ENDIF}

implementation

end.
