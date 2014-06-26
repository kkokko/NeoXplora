unit EntityMapping;

{$mode objfpc}{$H+}

interface

uses
  GenericEntity, TypesConsts, Entity;

type
  TEntityMapping = class;
  TEntityMappingArray = array of TEntityMapping;

  TEntityMapping = class(TGenericEntity)
  private
    FTableName: string;
    FEntityClassType: TEntityClass;
  protected
    function GetValueForField(const AField: string): Variant; reintroduce;
    function GetFieldForValue(AValue: Variant): string; reintroduce;
  public
    function GetMappedNameForField(const AField: string): string;
    function GetFieldForMappedName(const AMappedName: string): string;
    class function EntityToMappingFields(AEntity: TEntity): {$IFDEF VER210}TKeyStringValue.TArray{$ELSE}TKeyStringValueArray{$ENDIF};
    class function EntityClassToMappingFields(AEntityClass: TEntityClass): {$IFDEF VER210}TKeyStringValue.TArray{$ELSE}TKeyStringValueArray{$ENDIF};
    constructor Create(const ATableName: string; SomeMappingFields: {$IFDEF VER210}TKeyStringValue.TArray{$ELSE}TKeyStringValueArray{$ENDIF}); reintroduce;
    property EntityClassType: TEntityClass read FEntityClassType write FEntityClassType;
    property TableName: string read FTableName;
  end;

implementation

{ TEntityMapping }
uses
  TypesFunctions, EntityManager, Variants;

class function TEntityMapping.EntityToMappingFields(AEntity: TEntity): {$IFDEF VER210}TKeyStringValue.TArray{$ELSE}TKeyStringValueArray{$ENDIF};
begin
  Result := EntityClassToMappingFields(TEntityClass(AEntity.ClassType));
end;

class function TEntityMapping.EntityClassToMappingFields(AEntityClass: TEntityClass): {$IFDEF VER210}TKeyStringValue.TArray{$ELSE}TKeyStringValueArray{$ENDIF};
var
  TheFieldInfos: {$IFDEF VER210}TFieldInfo.TArray{$ELSE}TFieldInfoArray{$ENDIF};
  I: Integer;
begin
  TheFieldInfos := TEntityManager.GetEntityFieldInfos(AEntityClass);
  SetLength(Result, Length(TheFieldInfos));
  for I := 0 to High(TheFieldInfos) do
  begin
    Result[I].Key := TheFieldInfos[I].FieldName;
    Result[I].Value := TheFieldInfos[I].FieldName;
  end;
end;

function TEntityMapping.GetFieldForMappedName(const AMappedName: string): string;
begin
  Result := inherited GetFieldForValue(AMappedName);
end;

function TEntityMapping.GetFieldForValue(AValue: Variant): string;
begin
  Result := '';
end;

function TEntityMapping.GetMappedNameForField(const AField: string): string;
begin
  Result := inherited GetValueForField(AField);
end;

function TEntityMapping.GetValueForField(const AField: string): Variant;
begin
  Result := Unassigned;
end;

constructor TEntityMapping.Create(const ATableName: string; SomeMappingFields: {$IFDEF VER210}TKeyStringValue.TArray{$ELSE}TKeyStringValueArray{$ENDIF});
begin
  inherited Create;
  FTableName := ATableName;
  SetValuesForFields(ConvertKeyStringValuesToTKeyValues(SomeMappingFields));
end;

end.
