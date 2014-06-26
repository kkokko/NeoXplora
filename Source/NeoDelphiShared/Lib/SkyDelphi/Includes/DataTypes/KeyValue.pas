unit KeyValue;

interface

type
  TKeyValue = record
 {$IFDEF UNICODE}
    type
      TArray = array of TKeyValue;
 {$ENDIF}
    var
      Key: string;
      Value: Variant;
      DataType: string;

    class function Create(const AKey: string; const AValue: Variant;
      const ADataType: string): TKeyValue; static;
 {$IFDEF UNICODE}
    function AsArray: TArray;
  end;
  {$ELSE}
  end;
    TKeyValueArray = array of TKeyValue;
 {$ENDIF}

implementation

{ TKeyValue }

 {$IFDEF UNICODE}
function TKeyValue.AsArray: TArray;
begin
  SetLength(Result, 1);
  Result[0] := Self;
end;
 {$ENDIF}

class function TKeyValue.Create(const AKey: string; const AValue: Variant;
  const ADataType: string): TKeyValue;
begin
  Result.Key := AKey;
  Result.Value := AValue;
  Result.DataType := ADataType;
end;

end.