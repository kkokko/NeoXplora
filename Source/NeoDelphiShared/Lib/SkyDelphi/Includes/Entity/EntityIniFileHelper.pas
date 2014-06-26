unit EntityIniFileHelper;

interface

uses
  Entity, IniFiles;

type
  TEntityIniFile = class Helper for TEntity
  public
    procedure LoadFromIni(AIniFile: TIniFile; const ASection: string);
    procedure SaveToIni(AIniFile: TIniFile; const ASection: string);
  end;

implementation

uses
  TypesConsts, Variants, StringArray;

{ TEntityIniFile }

procedure TEntityIniFile.LoadFromIni(AIniFile: TIniFile; const ASection: string);
var
  I: Integer;
  TheFieldNames: TStringArray;
  TheValue: string;
begin
  TheFieldNames := GetFieldNames;
  for I := 0 to TheFieldNames.Count - 1 do
  begin
    TheValue := AIniFile.ReadString(ASection, TheFieldNames[I], '');
    if TheValue <> '' then
      SetValueForField(TheFieldNames[I], TheValue);
  end;
end;

procedure TEntityIniFile.SaveToIni(AIniFile: TIniFile; const ASection: string);
var
  I: Integer;
  TheFieldNames: TStringArray;
  TheValue: string;
begin
  TheFieldNames := GetFieldNames;
  for I := 0 to TheFieldNames.Count - 1 do
  begin
    TheValue := VarToStr(GetValueForField(TheFieldNames[I]));
    if TheValue <> '' then
      AIniFile.WriteString(ASection, TheFieldNames[I], TheValue);
  end;
end;

end.
