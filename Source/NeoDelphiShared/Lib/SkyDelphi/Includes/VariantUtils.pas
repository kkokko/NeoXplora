unit VariantUtils;

interface

uses
  Classes, StringArray;

function DeserializeVariant(Stream: TStream): Variant;
function SerializeVariant(Data: Variant; Stream: TStream): Integer;
function StringArrayToVariant(AnArray: array of string): Variant;
function VariantToStringArray(AVariant: Variant): TStringArray;

implementation

uses
  SysUtils, Variants;

function IsSimpleType(VarType: Word) : Boolean;
begin
  Result := (VarType and varTypeMask) in [varByte, varSmallint, varInteger,
    varSingle, varDouble, varCurrency, varDate, varError, varBoolean];
end;
function GetSimpleTypeSize(VarType: word): integer;
begin
  case(VarType and varTypeMask) of
    varSmallint:
      Result := SizeOf(Smallint);
    varInteger:
      Result := SizeOf(Integer);
    varSingle:
      Result := SizeOf(Single);
    varDouble:
      Result := SizeOf(Double);
    varCurrency:
      Result := SizeOf(Currency);
    varDate:
      Result := SizeOf(Double);
    varError:
      Result := SizeOf(Longint);
    varBoolean:
      Result := SizeOf(Boolean);
    varByte:
      Result := SizeOf(Byte);
  else
    Result := -1;
  end;
  Assert(False, 'Serialising Arrays is not implemented');
end;

function DeserializeVariant(Stream: TStream): Variant;
var
  VariantType: Word;
  TempVariant: Variant;
  function Read1(var Buffer; Count: Longint): Longint;
  begin
    Result := Stream.Read(Buffer, Count);
    if Result <> Count then
      raise Exception.Create('Invalid stream data!');
  end;
  procedure ReadSingleData(VariantType: word; out V: variant);
  var
    {$IFDEF VER210}S: string;{$ENDIF}
    L: integer;
    SS: AnsiString;
  begin
    VarCast(V, V, VariantType);
    case VariantType of
      varSmallint: Read1(TVarData(V).VSmallint, SizeOf(Smallint));
      varWord: Read1(TVarData(V).VWord, SizeOf(Word));
      varInteger: Read1(TVarData(V).VInteger, SizeOf(Integer));
      varSingle: Read1(TVarData(V).VSingle, SizeOf(Single));
      varDouble: Read1(TVarData(V).VDouble, SizeOf(Double));
      varCurrency: Read1(TVarData(V).VCurrency, SizeOf(Currency));
      varDate: Read1(TVarData(V).VDate, SizeOf(Double));
      varOleStr, varString:
      begin
        Read1(L, SizeOf(Integer));
        SetLength(SS, L);
        Read1(SS[1], L);
        V := SS;
      end;
{$IFDEF VER210}
      varUString:
      begin
        Read1(L, SizeOf(Integer));
        if L = 0 then
          S := ''
        else
        begin
          SetLength(S, L);
          Read1(S[1], L * SizeOf(WideChar));
          V := S;
        end;
      end;
{$ENDIF}
      varError: Read1(TVarData(V).VError, SizeOf(Longint));
      varBoolean: Read1(TVarData(V).VBoolean, SizeOf(Boolean));
      varByte: Read1(TVarData(V).VByte, SizeOf(Byte));
      varInt64: Read1(TVarData(V).VInt64, SizeOf(Int64));
{$IFDEF VER210}
      varUInt64: Read1(TVarData(V).VUInt64, SizeOf(UInt64));
{$ENDIF}
    end;
 end;
  procedure ReadArrayData(VariantType: word; out V: variant);
  var
    I: integer;
    Size: integer;
    Element: Variant;
    VarType: word;
    ElementSize: integer;
    ArrayDataSize: integer;
    PData: pointer;
  begin
    Read1(Size, SizeOf(integer));
    V := VarArrayCreate([0, Size - 1], VariantType and varTypeMask);
    if IsSimpleType(VariantType) then
    begin
      ElementSize := GetSimpleTypeSize(TVarData(V).VType);
      ArrayDataSize := Size * ElementSize;
      Stream.Size := Stream.Size + ArrayDataSize;
      PData := VarArrayLock(V);
      try
         Stream.Read(PData^, ArrayDataSize);
      finally
         VarArrayUnlock(V);
      end;
    end
    else
    for I := 0 to Size - 1 do begin
      Read1(VarType, SizeOf(word));
      if VarType and varArray = 0 then ReadSingleData(VarType, Element)
      else ReadArrayData(VarType, Element);
      V[I] := Element;
    end;
  end;
begin
  Read1(VariantType, SizeOf(word));
  if VariantType and varArray = 0 then
  begin
    VarCast(TempVariant, TempVariant, VariantType);
    ReadSingleData(VariantType and varTypeMask, TempVariant);
  end
  else
    ReadArrayData(VariantType and varTypeMask, TempVariant);
  Result := TempVariant;
end;

function SerializeVariant(Data: Variant; Stream: TStream): Integer;
var
  VariantType: Word;
  ThePosition: Int64;
  procedure WriteSingleData(V: variant);
  var
    {$IFDEF VER210}S: string;{$ENDIF}
    SS: ShortString;
    L: integer;
  begin
    //Write Size & Type
    VariantType := TVarData(V).VType and varTypeMask;
    Stream.Write (TVarData(V).VType, SizeOf(word));
    //Write V
    case VariantType of
      varSmallint: Stream.Write (TVarData(V).VSmallint, SizeOf(Smallint));
      varWord: Stream.Write (TVarData(V).VSmallint, SizeOf(Word));
      varInteger: Stream.Write(TVarData(V).VInteger, SizeOf(Integer));
      varSingle: Stream.Write(TVarData(V).VSingle, SizeOf(Single));
      varDouble: Stream.Write(TVarData(V).VDouble, SizeOf(Double));
      varCurrency: Stream.Write(TVarData(V).VCurrency, SizeOf(Currency));
      varDate: Stream.Write(TVarData(V).VDate, SizeOf(Double));
      varOleStr, varString:
      begin
        SS := AnsiString(V);
        L := Length(SS);
        Stream.Write(L, SizeOf(L));
        Stream.Write(SS[1], Length(SS));
      end;
{$IFDEF VER210}
      varUString:
      begin
        S := VarToStr(V);
        L := Length(S);
        Stream.Write(L, SizeOf(L));
        Stream.Write(PChar(S)[0], Length(S) * SizeOf(WideChar));
      end;
{$ENDIF}
      varError: Stream.Write(TVarData(V).VError, SizeOf(Longint));
      varBoolean: Stream.Write(TVarData(V).VBoolean, SizeOf(Boolean));
      varByte: Stream.Write(TVarData(V).VByte, SizeOf(Byte));
      varInt64{$IFDEF VER210}, varUInt64{$ENDIF}: Stream.Write(TVarData(V).VInt64, SizeOf(Int64));
    else
      raise Exception.Create('Unknown Variant Type');
    end;
 end;
  procedure WriteArrayData(V: Variant);
  var
    I: integer;
    LowBound, HighBound, Size: integer;
    Element: Variant;
    ElementSize: integer;
    ArrayDataSize: integer;
    PData: pointer;
  begin
    Stream.Write(TVarData(V).VType, SizeOf(word));
    LowBound := VarArrayLowBound(V, 1);
    HighBound := VarArrayHighBound(V, 1);
    Size := HighBound - LowBound + 1;
    Stream.Write(Size, SizeOf(integer));
    if(Size > 0) and IsSimpleType(TVarData(V).VType) then
    begin
      ElementSize := GetSimpleTypeSize(TVarData(V).VType);
      ArrayDataSize := Size * ElementSize;
      Stream.Size := (Stream.Size + ArrayDataSize);
      PData := VarArrayLock(V);
      try
        Stream.Write(PData^, ArrayDataSize);
      finally
        VarArrayUnlock(V);
      end;
    end
    else
      for I := LowBound to HighBound do
      begin
        Element := V[I];
        if VarIsArray(Element) then
          WriteArrayData(Element)
        else
          WriteSingleData(Element);
      end;
  end;
begin
  Result := 0;
  if(VarIsNull(Data)) or(VarIsEmpty(Data)) then
    Exit;
  ThePosition := Stream.Position;
  if VarIsArray(Data) then
    WriteArrayData(Data)
  else
    WriteSingleData(Data);
  Result := Stream.Position - ThePosition;
end;

function StringArrayToVariant(AnArray: array of string): Variant;
var
  I, TheLength: Integer;
begin
  TheLength := Length(AnArray) - 1;
  Result := VarArrayCreate([0, TheLength], varVariant);
  for I := 0 to TheLength do
    Result[I] := AnArray[I];
end;

function VariantToStringArray(AVariant: Variant): TStringArray;
var
  I, TheLength: Integer;
begin
  if (VarIsNull(AVariant)) or (VarIsEmpty(AVariant) or (not VarIsArray(AVariant))) then
    Exit;
  TheLength := VarArrayHighBound(AVariant, 1);
  Result.Count := TheLength + 1;
  for I := 0 to TheLength do
    Result[I] := AVariant[I];
end;

end.
