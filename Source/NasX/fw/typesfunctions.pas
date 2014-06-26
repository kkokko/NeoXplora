unit TypesFunctions;

{$mode objfpc}{$H+}

interface

uses
  TypesConsts, DateUtils, StringArray;

function CompareDoubles(Double1, Double2: Double; Precision: Integer = 0): Integer;
function CompareDoublesWithRange(Double1, Double2: Double; ARange: Double): Integer;
function CompareTimes(ADateTime1, ADateTime2: TDateTime; ADatePrecision: TTimePrecision = dpSECOND): Integer;
function RoundDouble(ADouble: Double; APrecision: Integer): Double;
function CompareIntegers(Integer1, Integer2: Integer): Integer;
function CompareInt64s(Integer1, Integer2: Int64): Integer;
function CompareIds(Id1, Id2: TId): Integer;
function ComparePointers(Pointer1, Pointer2: Pointer): Integer;
function CompareBooleans(Boolean1, Boolean2: Boolean): Integer;
function CompareVariants(Value1, Value2: Variant): Integer;
function ConvertKeyStringValuesToTKeyValues(const SomeKeyStrings: TKeyStringValueArray): TKeyValueArray;
procedure FreeAndNil(var Obj);
procedure FreeAndNilObjectArray(Obj: TObjects);
function IfThen(AValue: Boolean; const ATrue: string; const AFalse: string = ''): string; overload; inline;
function StringArrayToVariants(SomeStrings: TStringArray): TVariants;
function IdToStr(AnId: TId): string;
function StrToId(const AString: string): TId;
function CreateEmptyFile(const AFileName: string): Boolean;
function IsDirectoryEmpty(const APath: string): Boolean;
function GetDirectoryContent(const APath: string): TStringArray;
function StringArrayToString(AStringArray: TStringArray; ASeperator: string; AlwaysUseSep: Boolean = False): string;
function GetFileSize(const AFileName: string): Int64;
function GetEmptyObjectArray(ASize: Integer): TObjects;
function DateForSQL(const TheDate: TDate): string;
function DateForAccess(const TheDate: TDate): string;
function IdsToString(SomeIds: TIds; const ASeparator: string = ','): string;
function ReadPString(var AStart: PAnsiChar; AStop: PAnsiChar): AnsiString;
function DateTimeToStr(AValue: TDateTime): string;
function DateToStr(AValue: TDateTime): string;
function ISODateTime2DateTime(const AnIsoDt: string): TDateTime;
function Str2Time(const ATime: string): TDateTime;
function TimeToStr(AValue: TDateTime): string;
function DateTimeToStrEx(AValue: TDateTime): string;
function StrToDateTime(nodeValue: string; var value: TDateTime): boolean;
function StrToExtended(nodeValue: string; var value: extended): boolean; overload;
function TokenPropertyName(AToken: TEntityFieldNamesToken): string;
function BoolToStr(ABool: Boolean): string; inline;

implementation

uses
  Math, SysUtils, Variants;

function DateForSQL(const TheDate: TDate) : string;
var
 TheYear, TheMonth, TheDay: Word;
begin
  DecodeDate(TheDate, TheYear, TheMonth, TheDay) ;
  Result := '''' + Format('%.*d-%.*d-%.*d',[4, TheYear, 2, TheMonth, 2, TheDay]) + '''';
end;

function DateForAccess(const TheDate: TDate) : string;
var
 TheYear, TheMonth, TheDay: Word;
begin
  DecodeDate(TheDate, TheYear, TheMonth, TheDay) ;
  Result := Format('#%.*d-%.*d-%.*d#',[4, TheYear, 2, TheMonth, 2, TheDay]);
end;

function IdToStr(AnId: TId): string;
begin
  Result := IntToStr(AnId);
end;

function StrToId(const AString: string): TId;
begin
  Result := StrToInt64Def(AString, 0);
end;

function CompareDoubles(Double1, Double2: Double; Precision: Integer = 0): Integer;
begin
  if Precision > 0 then
  begin
    if Double1 < Double2 - Power(10, - Precision) then
      Result := -1
    else if Double1 > Double2 + Power(10, - Precision) then
      Result := 1
    else
      Result := 0;
  end
  else
  begin
    if Double1 < Double2 then
      Result := -1
    else if Double1 > Double2 then
      Result := 1
    else
      Result := 0;
  end;
end;

function RoundDouble(ADouble: Double; APrecision: Integer): Double;
begin
  Assert(APrecision >= 0);
  if (APrecision > 0) then
    Result := Power(10, -APrecision) * Round(Power(10, APrecision) * ADouble)
  else
    Result := ADouble; //do nothing
end;

function CompareIntegers(Integer1, Integer2: Integer): Integer;
begin
  if Integer1 < Integer2 then
    Result := -1
  else if Integer1 > Integer2 then
    Result := 1
  else
    Result := 0;
end;

function CompareInt64s(Integer1, Integer2: Int64): Integer;
begin
  if Integer1 < Integer2 then
    Result := -1
  else if Integer1 > Integer2 then
    Result := 1
  else
    Result := 0;
end;

function CompareIds(Id1, Id2: TId): Integer;
begin
  Result := CompareInt64s(Id1, Id2);
end;

function ComparePointers(Pointer1, Pointer2: Pointer): Integer;
begin
  Result := CompareIntegers(Integer(Pointer1), Integer(Pointer2));
end;

function CompareBooleans(Boolean1, Boolean2: Boolean): Integer;
begin
  Result := IfThen(Boolean1, 1, 0) - IfThen(Boolean2, 1, 0);
end;

function CompareVariants(Value1, Value2: Variant): Integer;
begin
  Result := AnsiCompareText(VarToStr(Value1), VarToStr(Value2));
end;

function ConvertKeyStringValuesToTKeyValues(
  const SomeKeyStrings: TKeyStringValueArray): TKeyValueArray;
var
  I: Integer;
begin
  SetLength(Result, Length(SomeKeyStrings));
  for I := 0 to Length(SomeKeyStrings) - 1 do
  begin
    Result[I].Key := SomeKeyStrings[I].Key;
    Result[I].Value:= SomeKeyStrings[I].Value;
  end;
end;

procedure FreeAndNil(var Obj);
var
  Temp: TObject;
begin
  Temp := TObject(Obj);
  Pointer(Obj) := nil;
  if Temp <> nil then
    Temp.Free;
end;

procedure FreeAndNilObjectArray(Obj: TObjects);
var
  I: Integer;
begin
  for I := 0 to Length(Obj) - 1 do
    FreeAndNil(Obj[I]);
end;

function IfThen(AValue: Boolean; const ATrue: string; const AFalse: string = ''): string;
begin
  if AValue then
    Result := ATrue
  else
    Result := AFalse;
end;

function CompareDoublesWithRange(Double1, Double2: Double; ARange: Double): Integer;
begin
  if Double1 + ARange < Double2 then
    Result := -1
  else if Double1 > Double2 + ARange then
    Result := 1
  else
    Result := 0;
end;

function CompareTimes(ADateTime1, ADateTime2: TDateTime; ADatePrecision: TTimePrecision): Integer;
begin
  Result := CompareIntegers(YearOf(ADateTime1), YearOf(ADateTime2));
  if (Result <> 0) or (ADatePrecision <= dpYEAR) then
    Exit;
  if (ADatePrecision = dpWEEK) then
  begin
    Result := CompareIntegers(WeekOf(ADateTime1), WeekOf(ADateTime2));
    Exit;
  end;
  case ADatePrecision of
    dpMONTH: Result := CompareIntegers(MonthOf(ADateTime1), MonthOf(ADateTime2));
    dpDAY: Result := CompareIntegers(Trunc(ADateTime1), Trunc(ADateTime2));
  end;
  if (Result <> 0) or (ADatePrecision <= dpDAY) then
    Exit;
  case ADatePrecision of
    dpHOUR:   Result := CompareDoublesWithRange(ADateTime1, ADateTime2, 1/24);
    dpMINUTE: Result := CompareDoublesWithRange(ADateTime1, ADateTime2, 1/1440);
    dpSECOND: Result := CompareDoublesWithRange(ADateTime1, ADateTime2, 1/86400);
  end;
end;

function StringArrayToVariants(SomeStrings: TStringArray): TVariants;
var
  I: Integer;
begin
  SetLength(Result, Length(SomeStrings));
  for I := 0 to Length(Result) - 1 do
    Result[I] := SomeStrings[I];
end;

function CreateEmptyFile(const AFileName: string): Boolean;
var
  TheFileHandle: Integer;
  ThePath: string;
begin
  Result := False;
  ThePath := ExtractFilePath(AFileName);
  if DirectoryExists(ThePath) = False then
    if not ForceDirectories(ThePath) then
      Exit;
  TheFileHandle := FileCreate(AFileName);
  Result := TheFileHandle > 0;
  if Result then
    FileClose(TheFileHandle);
end;

function IsDirectoryEmpty(const APath: string): Boolean;
var
  TheSearchRec: TSearchRec;
begin
  Result := True;
  if FindFirst(IncludeTrailingPathDelimiter(APath) + '*.*', faAnyFile, TheSearchRec) = 0 then
  try
    repeat
      if (TheSearchRec.Name = '.') or (TheSearchRec.Name = '..') then
        Continue;
      Result := False;
      Exit;
    until FindNext(TheSearchRec) <> 0;
  finally
    SysUtils.FindClose(TheSearchRec);
  end;
end;

function GetDirectoryContent(const APath: string): TStringArray;
var
  TheSearchRec: TSearchRec;
  TheIndex: Integer;
begin
  TheIndex := 0;
  Result := nil;
  if FindFirst(IncludeTrailingPathDelimiter(APath) + '*.*', faAnyFile, TheSearchRec) = 0 then
  try
    repeat
      if (TheSearchRec.Name = '.') or (TheSearchRec.Name = '..') then
        Continue;
      if TheIndex = Length(Result) then
        SetLength(Result, Max(200, Length(Result)* 2));
      Result[TheIndex] := TheSearchRec.Name;
      Inc(TheIndex);
    until FindNext(TheSearchRec) <> 0;
  finally
    SysUtils.FindClose(TheSearchRec);
  end;
  SetLength(Result, TheIndex);
end;

function StringArrayToString(AStringArray: TStringArray; ASeperator: string; AlwaysUseSep: Boolean): string;
var
  I: Integer;
begin
  Result := '';
  for I := 0 to Length(AStringArray) - 1 do
    if AlwaysUseSep then
      Result := Result + AStringArray[I] + ASeperator
    else if (Result = '') then
      Result := AStringArray[I]
    else
      Result := Result + ASeperator + AStringArray[I];
end;

function GetFileSize(const AFileName: string): Int64;
var
  TheSearchRec: TSearchRec;
begin
  try
    if FindFirst(ExpandFileName(AFileName), faAnyFile, TheSearchRec) = 0 then
      Result := TheSearchRec.Size
    else
      Result := -1;
  finally
    SysUtils.FindClose(TheSearchRec);
  end;
end;

function GetEmptyObjectArray(ASize: Integer): TObjects;
var
  I: Integer;
begin
  SetLength(Result, ASize);
  for I := 0 to ASize - 1 do
    Result[I] := nil;
end;

function IdsToString(SomeIds: TIds; const ASeparator: string): string;
var
  I: Integer;
begin
  Result := '';
  if Length(SomeIds) = 0 then
    Exit;
  for I := 0 to High(SomeIds) do
    Result := Result + IdToStr(SomeIds[I]) + ASeparator;
  SetLength(Result, Length(Result) - Length(ASeparator));
end;

function ReadPString(var AStart: PAnsiChar; AStop: PAnsiChar): AnsiString;
begin
  Result := '';
  if AStop = nil then
  begin
    Result := AnsiString(AStart);
    Exit;
  end;
  while AStart < AStop  do
  begin
    Result := Result + AStart^;
    Inc(AStart);
  end;
end;

function DateTimeToStr(AValue: TDateTime): string;
begin
  if Trunc(AValue) = 0 then
    Result := FormatDateTime('" "hh":"mm":"ss', AValue)
  else
    Result := FormatDateTime('yyyy-mm-dd" "hh":"mm":"ss', AValue);
end;

function DateTimeToStrEx(AValue: TDateTime): string;
begin
  if Trunc(AValue) = 0 then
    Result := TimeToStr(AValue)
  else if Frac(AValue) = 0 then
    Result := DateToStr(AValue)
  else
    Result := DateTimeToStr(AValue);
end;

function DateToStr(AValue: TDateTime): string;
begin
  Result := FormatDateTime('yyyy-mm-dd', AValue);
end;

function TimeToStr(AValue: TDateTime): string;
begin
  Result := FormatDateTime('hh":"mm":"ss', AValue);
end;

function ISODateTime2DateTime(const AnIsoDt: string): TDateTime;
var
  TheDate: string;
  TheDay: Word;
  TheIndex: Integer;
  TheMonth: Word;
  TheTime: string;
  TheYear: Word;
begin
  TheIndex := Pos (' ', AnIsoDt);
  // detect all known date/time formats
  if (TheIndex = 0) and (Pos('-', AnIsoDt)>0) then
    TheIndex := Length(AnIsoDt) + 1;
  TheDate := Trim(Copy(AnIsoDt, 1, TheIndex - 1));
  TheTime := Trim(Copy(AnIsoDt, TheIndex + 1, Length(AnIsoDt) - TheIndex));
  Result := 0;
  if TheDate <> '' then begin
    TheIndex := Pos ('-', TheDate);
    TheYear :=  StrToInt(Copy(TheDate, 1, TheIndex - 1));
    Delete(TheDate, 1, TheIndex);
    TheIndex := Pos ('-', TheDate);
    TheMonth :=  StrToInt(Copy(TheDate, 1, TheIndex - 1));
    TheDay := StrToInt(Copy(TheDate, TheIndex + 1, Length(TheDate) - TheIndex));
    Result := EncodeDate(TheYear, TheMonth, TheDay);
  end;
  Result := Result + Frac(Str2Time(TheTime));
end;

function Str2Time(const ATime: string): TDateTime;
var
  TheHour: Word;
  TheIndex: Integer;
  TheMinute: Word;
  TheMSec: Word;
  TheSecond: Word;
  TheTime: string;
begin
  TheTime := Trim(ATime);
  if TheTime = '' then
    Result := 0
  else begin
    TheIndex := Pos(':', TheTime);
    TheHour := StrToInt(Copy(TheTime, 1, TheIndex - 1));
    Delete(TheTime, 1, TheIndex);
    TheIndex := Pos(':', TheTime);
    TheMinute := StrToInt(Copy(TheTime, 1, TheIndex - 1));
    Delete(TheTime, 1, TheIndex);
    TheIndex := Pos('.', TheTime);
    if TheIndex > 0 then begin
      TheMSec := StrToInt(Copy(TheTime, TheIndex + 1, Length(TheTime) - TheIndex));
      Delete(TheTime, TheIndex, Length(TheTime) - TheIndex + 1);
    end
    else
      TheMSec := 0;
    TheSecond := StrToInt(TheTime);
    Result := EncodeTime(TheHour, TheMinute, TheSecond, TheMSec);
  end;
end;

function StrToDateTime(nodeValue: string; var value: TDateTime): boolean;
begin
  try
    value := ISODateTime2DateTime(StringReplace(NodeValue, 'T', ' ', []));
    Result := true;
  except
    Result := false;
  end;
end;

function DoXMLStrToExtended(nodeValue: string): extended;
begin
  try
    Result := StrToFloat(StringReplace(nodeValue, DEFAULT_DECIMALSEPARATOR,
      FormatSettings.DecimalSeparator, [rfReplaceAll]));
  except
    on EConvertError do begin
      if (nodeValue = 'INF') or (nodeValue = '+INF') then
        Result := 1.1e+4932
      else if nodeValue = '-INF' then
        Result := 3.4e-4932
      else
        raise;
    end;
  end;
end; { StrToExtended }

function StrToExtended(nodeValue: string; var value: extended): boolean;
begin
  try
    value := DoXMLStrToExtended(nodeValue);
    Result := true;
  except
    on EConvertError do
      Result := false;
  end;
end; { StrToExtended }

function TokenPropertyName(AToken: TEntityFieldNamesToken): string;
var
  TheIndex: Integer;
begin
  TheIndex := Pos('.', AToken.TokenString);
  Result := Copy(AToken.TokenString, TheIndex + 1, Length(AToken.TokenString) - TheIndex);
end;

function BoolToStr(ABool: Boolean): string;
begin
  if ABool then
    Result := 'True' // do not localize
  else
    Result := 'False'; // do not localize
end;

end.
