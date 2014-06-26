unit KeyValue;

{$mode objfpc}{$H+}

interface

type
  TKeyValue = record
    Key: string;
    Value: Variant;
    DataType: string;
  end;
  TKeyValueArray = array of TKeyValue;

implementation

{ TKeyValue }

end.
