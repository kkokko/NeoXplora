unit TypesFunctions;

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
 {$IFDEF UNICODE}
function ConvertKeyStringValuesToTKeyValues(const SomeKeyStrings: TKeyStringValue.TArray): TKeyValue.TArray;
{$ELSE}
function ConvertKeyStringValuesToTKeyValues(const SomeKeyStrings: TKeyStringValueArray): TKeyValueArray;
 {$ENDIF}
procedure FreeAndNil(var Obj); inline;
procedure FreeAndNilObjectArray(Obj: TObjects);
function IfThen(AValue: Boolean; const ATrue: string; const AFalse: string = ''): string; overload; inline;
function ExpandEnviromentString(const AString: string): string;
function StringArrayToVariants(SomeStrings: TStringArray): TVariants;
function IdToStr(AnId: TId): string;
function StrToId(const AString: string): TId;
function CreateEmptyFile(const AFileName: string): Boolean;
function IsDirectoryEmpty(const APath: string): Boolean;
function GetDirectoryContent(const APath: string): TStringArray;
function StringArrayToString(AStringArray: TStringArray; ASeperator: string; AlwaysUseSep: Boolean = False): string;
function GetFileSize(const AFileName: string): Int64;
function GetEmptyObjectArray(ASize: Integer): TObjects;
function DeleteFileOrFolder(const AName: string): Boolean;
function DateForSQL(const TheDate: TDate): string;
function DateForAccess(const TheDate: TDate): string;
function IdsToString(SomeIds: TIds; const ASeparator: string = ','): string;
function ReadPString(var AStart: PAnsiChar; AStop: PAnsiChar): AnsiString;

implementation

uses
  Math, SysUtils, Variants, Windows, ShellAPI;

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

 {$IFDEF UNICODE}
function ConvertKeyStringValuesToTKeyValues(const SomeKeyStrings: TKeyStringValue.TArray): TKeyValue.TArray;
{$ELSE}
function ConvertKeyStringValuesToTKeyValues(const SomeKeyStrings: TKeyStringValueArray): TKeyValueArray;
 {$ENDIF}
var
  I, TheCount: Integer;
begin
  TheCount := SomeKeyStrings.Count;
  SetLength(Result, TheCount);
  for I := 0 to TheCount - 1 do
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
  if (ADateTime1 < 0) or (ADateTime2 < 0) then
    Result := CompareIntegers(Trunc(ADateTime1), Trunc(ADateTime2))
  else
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

function ExpandEnviromentString(const AString: string): string;
var
  TheBufferSize: Integer; // size of expanded string
begin
  // Get required buffer size
  TheBufferSize := ExpandEnvironmentStrings(PChar(AString), nil, 0);
  if TheBufferSize > 0 then
  begin
    // Read expanded string into result string
    SetLength(Result, TheBufferSize - 1);
    ExpandEnvironmentStrings(PChar(AString), PChar(Result), TheBufferSize);
    TheBufferSize := Length(Result);

    if (TheBufferSize > 0) and (#0 = Result[TheBufferSize]) then
      SetLength(Result, TheBufferSize - 1);
  end
  else
    // Trying to expand empty string
    Result := AString;
end;

function StringArrayToVariants(SomeStrings: TStringArray): TVariants;
var
  I: Integer;
begin
  SetLength(Result, SomeStrings.Count);
  for I := 0 to SomeStrings.Count - 1 do
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
  Result := TStringArray.Empty;
  if FindFirst(IncludeTrailingPathDelimiter(APath) + '*.*', faAnyFile, TheSearchRec) = 0 then
  try
    repeat
      if (TheSearchRec.Name = '.') or (TheSearchRec.Name = '..') then
        Continue;
      if TheIndex = Result.Count then
        Result.Count := Max(200, Result.Count * 2);
      Result[TheIndex] := TheSearchRec.Name;
      Inc(TheIndex);
    until FindNext(TheSearchRec) <> 0;
  finally
    SysUtils.FindClose(TheSearchRec);
  end;
  Result.Count := TheIndex;
end;

function StringArrayToString(AStringArray: TStringArray; ASeperator: string; AlwaysUseSep: Boolean): string;
var
  I: Integer;
begin
  Result := '';
  for I := 0 to AStringArray.Count - 1 do
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

function DeleteFileOrFolder(const AName: string): Boolean;
var
  TheSHFileOpStruct : TSHFileOpStruct;
  TheDirBuf : array [0..4999] of Char;
begin
  if FileExists(AName) then
    Result := DeleteFile(PChar(AName))
  else if not DirectoryExists(AName) then
    Result := True
  else
    try
      FillChar(TheSHFileOpStruct, Sizeof(TheSHFileOpStruct), 0) ;
      FillChar(TheDirBuf, Sizeof(TheDirBuf), 0);
      StrPCopy(TheDirBuf, AName) ;
      TheSHFileOpStruct.Wnd := 0;
      TheSHFileOpStruct.pFrom := @TheDirBuf;
      TheSHFileOpStruct.wFunc := FO_DELETE;
      TheSHFileOpStruct.fFlags := FOF_NOCONFIRMATION or FOF_SILENT;
      Result := SHFileOperation(TheSHFileOpStruct) = 0;
     except
        Result := False;
    end;
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

end.
