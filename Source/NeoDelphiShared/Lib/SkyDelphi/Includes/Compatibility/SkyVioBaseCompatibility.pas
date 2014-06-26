unit SkyVioBaseCompatibility;

interface

type
  TVioBase = class
  public
    class function CRC(const TheString: AnsiString): Word;
    class function CRCArr(APointer: Pointer; AStartPoint, ASize: Integer): Word;
    class function WrongCRC(APointer: Pointer; AStartPoint, ASize: Integer): Word;
    class function CRCArr2(APointer: Pointer; AStartPoint, ASize: Integer;
      IsFinal: Boolean; var AInitialCRC: cardinal): word;
    class procedure XorBuffer(ABuffer: Pointer; ASize: Integer);
  end;

implementation

{ TVioBase }

class function TVioBase.CRC(const TheString: AnsiString): Word;
var
  sum, l, k: longint;
  m: record
    case integer of
      1: (b1, b2: byte);
      2: (w: word);
  end;
begin
  sum := 0;
  l := length(TheString);
  k := 1;
  while (l > 1) do
  begin
    m.b1 := Byte(TheString[k]);
    m.b2 := Byte(TheString[k + 1]);
    sum := sum + m.w;
    l := l - 2;
    inc(k, 2);
  end;
  if l = 1 then
    sum := sum + byte(TheString[k]);
  sum := (sum shr 16) + (sum and $FFFF);
  sum := sum + sum shr 16;
  crc := word(sum);
end;


class function TVioBase.CRCArr(APointer: Pointer; AStartPoint, ASize: Integer): Word;
var
  Rep, Rest: Integer;
  Sum: Word;
label
  gg1, gg2, gg3;
begin
  Result := 0;
  if ASize = 0 then
    exit;
  rep := ASize div 2;
  rest := ASize mod 2;

  asm
    push eax
    push ebx
    push ecx
    push edx

    mov eax, APointer
    add eax, AStartPoint
    mov ecx, rep
    mov edx,0
    xor ebx, ebx

    cmp ecx,0
    jz gg3
    gg1:
    mov bx, [eax]
    add edx,ebx
    adc edx,0
    add eax, 2
    dec ecx
    jnz gg1

    gg3:
    cmp rest, 0
    je gg2
    xor ebx,ebx
    mov bl, byte ptr [eax]
    add edx, ebx
    adc edx, 0
    gg2:
    mov ecx, edx
    shr ecx, 16
    and edx, 0FFFFh
    add dx, cx
    adc dx,0

    mov sum, dx
    pop edx
    pop ecx
    pop ebx
    pop eax
  end;
  Result := Sum;
end;

class function TVioBase.WrongCRC(APointer: Pointer; AStartPoint, ASize: Integer): Word;
var
  TheSize: Integer;
begin
  if ASize < 65537 * 2 then
    TheSize := ASize
  else
  begin
    TheSize := ASize mod 131072;
    if TheSize < 2 then
      TheSize := 131072 + TheSize mod 2;
  end;
  Result := CRCArr(APointer, AStartPoint, TheSize);
end;

class procedure TVioBase.XorBuffer(ABuffer: Pointer; ASize: Integer);
var
  I: Integer;
  TheByte: ^Byte;
begin
  TheByte := ABuffer;
  for I := 0 to ASize - 1 do
  begin
    TheByte^ := Byte(TheByte^ xor Random(256));
    Inc(TheByte);
  end;
end;

class function TVioBase.CRCArr2(APointer: Pointer; AStartPoint, ASize: Integer;
  IsFinal: Boolean; var AInitialCRC: cardinal): word;
var
  Rep, Rest: Integer;
  Sum: Word;
  Sum2: Cardinal;
label
  gg1, gg2, gg3;
begin
  rep := ASize div 2;
  rest := ASize mod 2;
  sum2 := AInitialCRC;

  asm
    push eax
    push ebx
    push ecx
    push edx

    mov eax, APointer
    add eax, AStartPoint
    mov ecx, rep
    mov edx, sum2
    xor ebx, ebx

    cmp ecx,0
    jz gg3
    gg1:
    mov bx, [eax]
    add edx,ebx
    adc edx,0
    add eax, 2
    dec ecx
    jnz gg1

    gg3:
    cmp rest, 0
    je gg2
    xor ebx,ebx
    mov bl, byte ptr [eax]
    add edx, ebx
    adc edx, 0
    gg2:

    mov dword ptr [sum2], edx
    mov ecx, edx
    shr ecx, 16
    and edx, 0FFFFh
    add dx, cx
    adc dx,0

    mov sum, dx
    pop edx
    pop ecx
    pop ebx
    pop eax
  end;

  if IsFinal then
  begin
    result := sum;
    AInitialCRC := 0;
  end
  else
  begin
    result := 0;
    AInitialCRC := sum2;
  end;
end;

end.

