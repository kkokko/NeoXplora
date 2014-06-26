unit KeyGen;

interface

type
  TKeyGen = class
  private
    FAppData: array[0..15] of Byte;
    FRezData: array[0..15] of Byte;

    function DoDiv36: AnsiChar;
    function GetSerial(AnApplicationId: Cardinal; ALicenceNo: Word): string;
  public
    class function GenerateSerial(AnApplicationId: Cardinal; ALicenceNo: Word): string;
  end;

implementation

uses
  SysUtils, SkyVioBaseCompatibility;

{ TKeyGen }

class function TKeyGen.GenerateSerial(AnApplicationId: Cardinal; ALicenceNo: Word): string;
var
  TheInstance: TKeyGen;
begin
  TheInstance := TKeyGen.Create;
  try
    Result := TheInstance.GetSerial(AnApplicationId, ALicenceNo);
  finally
    TheInstance.Free;
  end;
end;

function TKeyGen.GetSerial(AnApplicationId: Cardinal; ALicenceNo: Word): string;
const
  DataPosition: array[0..15] of ShortInt = (-1, 0, -1, 1, -1, 4, -1, 5, -1, 2, -1, 3, -1, 6, -1, 7);
var
  TheAppInfo: Int64Rec;
  I, J: Integer;
begin
  TheAppInfo.Cardinals[0] := AnApplicationId;
  TheAppInfo.Words[2] := ALicenceNo;
  TheAppInfo.Words[3] := TVioBase.CRCArr(@TheAppInfo, 0, SizeOf(TheAppInfo) - SizeOf(TheAppInfo.Words[3]));
  Randomize;
  for I := 0 to 15 do
    if DataPosition[I] = - 1 then
      FAppData[I] := Random(256)
    else
      FAppData[I] := TheAppInfo.Bytes[DataPosition[I]];

  RandSeed := AnApplicationId;
  for I := 0 to 15 do
    FAppData[I] := FAppData[I] xor Random(256);
  for I := 0 to 7 do
  begIn
    FRezData[I] := FAppData[0] mod 2;
    FRezData[I] := FRezData[I] * 2 + FAppData[1] mod 2;
    FRezData[I] := FRezData[I] * 2 + FAppData[2] mod 2;
    FRezData[I] := FRezData[I] * 2 + FAppData[3] mod 2;
    FRezData[I] := FRezData[I] * 2 + FAppData[4] mod 2;
    FRezData[I] := FRezData[I] * 2 + FAppData[5] mod 2;
    FRezData[I] := FRezData[I] * 2 + FAppData[6] mod 2;
    FRezData[I] := FRezData[I] * 2 + FAppData[7] mod 2;
    FRezData[8 + I] := FAppData[8] mod 2;
    FRezData[8 + I] := FRezData[8 + I] * 2 + FAppData[9] mod 2;
    FRezData[8 + I] := FRezData[8 + I] * 2 + FAppData[10] mod 2;
    FRezData[8 + I] := FRezData[8 + I] * 2 + FAppData[11] mod 2;
    FRezData[8 + I] := FRezData[8 + I] * 2 + FAppData[12] mod 2;
    FRezData[8 + I] := FRezData[8 + I] * 2 + FAppData[13] mod 2;
    FRezData[8 + I] := FRezData[8 + I] * 2 + FAppData[14] mod 2;
    FRezData[8 + I] := FRezData[8 + I] * 2 + FAppData[15] mod 2;
    for J := 0 to 15 do
      FAppData[J] := FAppData[j] div 2;
  end;
  Result := '';
  for I := 0 to 24 do
  begin
    if (I mod 5 = 0) and (I > 0) then
      Result := Result + '-';
    Result := Result + string(DoDiv36);
  end;
end;

function TKeyGen.DoDiv36: AnsiChar;
var
  TheCurrent: Integer;
  TheResult: Integer;
  I: Integer;
begin
  TheResult := 0;
  for I := 0 to 15 do
  begin
    TheCurrent := 256 * TheResult + FRezData[I];
    TheResult := TheCurrent mod 36;
    FRezData[I] := TheCurrent div 36;
  end;
  if TheResult > 25 then
    Result := AnsiChar(Byte('0') + TheResult - 26)
  else
    Result := AnsiChar(Byte('A') + TheResult);
end;

end.


