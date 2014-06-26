unit EntityTextExport;

interface

uses
  Classes, Entity, TypesConsts;

type
  TEntityTextExport = class(TObject)
  public
    class function WriteEntity(AnEntity: TEntity): string;
  end;

implementation

uses
  Variants, SysUtils;

class function TEntityTextExport.WriteEntity(AnEntity: TEntity): string;
var
  TheFieldInfos: {$IFDEF VER210}TFieldInfo.TArray{$ELSE}TFieldInfoArray{$ENDIF};
  I: Integer;
  TheValue: Variant;
begin
  Result := AnEntity.Name + ':' + AnEntity.ClassName + ReturnLF;
  TheFieldInfos := AnEntity.GetFieldInfos;
  for I := 0 to High(TheFieldInfos) do
  begin
    TheValue := AnEntity.GetValueForField(TheFieldInfos[I].FieldName);
    Result := Result + '  ' + TheFieldInfos[I].FieldName;
    Result := Result + ': ' + VarToStr(TheValue);
  end;
end;

end.
