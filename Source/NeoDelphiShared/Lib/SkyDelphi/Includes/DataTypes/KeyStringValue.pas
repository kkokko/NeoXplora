unit KeyStringValue;

interface

type
  TKeyStringValue = record
    var
      Key: string;
      Value: string;
    class function KeyValue(const AKey, AValue: string): TKeyStringValue; static;
 {$IFDEF UNICODE}
    type
      TArray = record
 {$ELSE}
  end;

  TKeyStringValueArray = record
 {$ENDIF}
      private
        var
          FItems: array of TKeyStringValue;
        function GetItem(AIndex: Integer): TKeyStringValue;
        procedure SetItem(AIndex: Integer; const Value: TKeyStringValue);
        function GetCount: Integer;
        procedure SetCount(const Value: Integer);
        function GetKey(AIndex: Integer): string;
        function GetValue(AIndex: Integer): string;
        procedure SetKey(AIndex: Integer; const Value: string);
        procedure SetValue(AIndex: Integer; const Value: string);
        function GetValueForKey(const AKey: string): string;
        procedure SetValueForKey(const AKey, AValue: string);
      public
 {$IFDEF UNICODE}
        class function Empty: TArray; static;
 {$ELSE}
        class function Empty: TKeyStringValueArray; static;
 {$ENDIF}

        procedure Add(const AKey, AValue: string);
        function IndexOfKey(const AKey: string): Integer;
        function IndexOfValue(const AValue: string): Integer;

        property Count: Integer read GetCount write SetCount;
        property Items[AIndex: Integer]: TKeyStringValue read GetItem write SetItem; default;
        property Keys[AIndex: Integer]: string read GetKey write SetKey;
        property ValueForKey[const AKey: string]: string read GetValueForKey write SetValueForKey;
        property Values[AIndex: Integer]: string read GetValue write SetValue;
 {$IFDEF UNICODE}
   end;
   class function MakeArray(SomeKeys, SomeValues: array of string): TArray; static;
 {$ELSE}
    class function MakeArray(SomeKeys, SomeValues: array of string): TKeyStringValueArray; static;
 {$ENDIF}
 end;

implementation

uses
  SysUtils;

{ TKeyStringValue }

class function TKeyStringValue.KeyValue(const AKey, AValue: string): TKeyStringValue;
begin
  Result.Key := AKey;
  Result.Value := AValue;
end;

{$IFDEF UNICODE}
class function TKeyStringValue.MakeArray(SomeKeys, SomeValues: array of string): TArray;
{$ELSE}
class function TKeyStringValueArray.MakeArray(SomeKeys, SomeValues: array of string): TKeyStringValueArray;
{$ENDIF}
var
  I: Integer;
begin
  if Length(SomeKeys) <> Length(SomeValues) then
    raise Exception.Create('TKeyStringValue.KeyValue length of arrays must be equal.');
  Result.Count := Length(SomeKeys);
  for I := 0 to High(SomeKeys) do
    Result[I] := TKeyStringValue.KeyValue(SomeKeys[I], SomeValues[I]);
end;

{ TKeyStringValue.TArray }

procedure {$IFDEF UNICODE}TKeyStringValue.TArray{$ELSE}TKeyStringValueArray{$ENDIF}.Add(const AKey, AValue: string);
var
  TheCount: Integer;
begin
  TheCount := Count;
  Count := Count + 1;
  Keys[TheCount] := AKey;
  Values[TheCount] := AValue;
end;

{$IFDEF UNICODE}
class function TKeyStringValue.TArray.Empty: TArray;
{$ELSE}
class function TKeyStringValueArray.Empty: TKeyStringValueArray;
{$ENDIF}
begin
  Result.Count := 0;
end;

function {$IFDEF UNICODE}TKeyStringValue.TArray{$ELSE}TKeyStringValueArray{$ENDIF}.GetCount: Integer;
begin
  Result := Length(FItems);
end;

function {$IFDEF UNICODE}TKeyStringValue.TArray{$ELSE}TKeyStringValueArray{$ENDIF}.GetItem(AIndex: Integer): TKeyStringValue;
begin
  Result := FItems[AIndex];
end;

function {$IFDEF UNICODE}TKeyStringValue.TArray{$ELSE}TKeyStringValueArray{$ENDIF}.GetKey(AIndex: Integer): string;
begin
  Result := FItems[AIndex].Key;
end;

function {$IFDEF UNICODE}TKeyStringValue.TArray{$ELSE}TKeyStringValueArray{$ENDIF}.GetValue(AIndex: Integer): string;
begin
  Result := FItems[AIndex].Value;
end;

function {$IFDEF UNICODE}TKeyStringValue.TArray{$ELSE}TKeyStringValueArray{$ENDIF}.GetValueForKey(const AKey: string): string;
var
  TheIndex: Integer;
begin
  TheIndex := IndexOfKey(AKey);
  if TheIndex = -1 then
    Result := ''
  else
    Result := FItems[TheIndex].Value;
end;

function {$IFDEF UNICODE}TKeyStringValue.TArray{$ELSE}TKeyStringValueArray{$ENDIF}.IndexOfKey(const AKey: string): Integer;
var
  I: Integer;
begin
  Result := -1;
  for I := 0 to Count - 1 do
    if Items[I].Key = AKey then
    begin
      Result := I;
      Exit;
    end;
end;

function {$IFDEF UNICODE}TKeyStringValue.TArray{$ELSE}TKeyStringValueArray{$ENDIF}.IndexOfValue(const AValue: string): Integer;
var
  I: Integer;
begin
  Result := -1;
  for I := 0 to Count - 1 do
    if Items[I].Value = AValue then
    begin
      Result := I;
      Exit;
    end;
end;

procedure {$IFDEF UNICODE}TKeyStringValue.TArray{$ELSE}TKeyStringValueArray{$ENDIF}.SetCount(const Value: Integer);
begin
  SetLength(FItems, Value);
end;

procedure {$IFDEF UNICODE}TKeyStringValue.TArray{$ELSE}TKeyStringValueArray{$ENDIF}.SetItem(AIndex: Integer;
  const Value: TKeyStringValue);
begin
  FItems[AIndex] := Value;
end;

procedure {$IFDEF UNICODE}TKeyStringValue.TArray{$ELSE}TKeyStringValueArray{$ENDIF}.SetKey(AIndex: Integer; const Value: string);
begin
  FItems[AIndex].Key := Value;
end;

procedure {$IFDEF UNICODE}TKeyStringValue.TArray{$ELSE}TKeyStringValueArray{$ENDIF}.SetValue(AIndex: Integer; const Value: string);
begin
  FItems[AIndex].Value := Value;
end;

procedure {$IFDEF UNICODE}TKeyStringValue.TArray{$ELSE}TKeyStringValueArray{$ENDIF}.SetValueForKey(const AKey, AValue: string);
var
  TheIndex: Integer;
begin
  TheIndex := IndexOfKey(AKey);
  if TheIndex = -1 then
    Add(AKey, AValue)
  else
    Values[TheIndex] := AValue;
end;

end.
