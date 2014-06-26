unit StringUtils;

interface

uses
  TypesConsts, StringArray;

function ReplaceEnter(AText: string): string;
function RestoreEnter(AText: string): string;

function SubstractStringArray(SomeSource, SomeSubstracted: TStringArray): TStringArray;

implementation

uses
  SysUtils, SkyLists;

function ReplaceEnter(AText: string): string;
begin
  Result := StringReplace(AText, #13, '\r', [rfReplaceAll]);
  Result := StringReplace(Result, #10, '\n', [rfReplaceAll]);
end;

function RestoreEnter(AText: string): string;
begin
  Result := StringReplace(AText, '\r', #13, [rfReplaceAll]);
  Result := StringReplace(Result, '\n', #10, [rfReplaceAll]);
end;

function SubstractStringArray(SomeSource, SomeSubstracted: TStringArray): TStringArray;
var
  TheTempList: TSkyStringList;
  I: Integer;
begin
  TheTempList := TSkyStringList.Create;
  try
    TheTempList.AddMultiple(SomeSource, []);
    for I := 0 to SomeSubstracted.Count - 1 do
      TheTempList.Delete(SomeSubstracted[I]);
    Result := TheTempList.GetAllItems;
  finally
    FreeAndNil(TheTempList);
  end;
end;

end.
