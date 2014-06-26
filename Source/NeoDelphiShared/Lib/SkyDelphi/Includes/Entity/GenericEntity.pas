unit GenericEntity;

interface

uses
  Entity, SkyLists, TypesConsts, StringArray;

type
  TGenericEntity = class;
  TGenericEntityArray = array of TGenericEntity;

  TGenericEntity = class(TEntity)
  private
    function GetFieldCount: Integer;
    function GetFieldName(AIndex: Integer): string;
    function GetFieldValue(AIndex: Integer): Variant;
    procedure SetFieldName(AIndex: Integer; const Value: string);
    procedure SetFieldValue(AIndex: Integer; const Value: Variant);
  protected
    FFieldValues: TSkyStringVariantList;
  public
    constructor Create; override;
    destructor Destroy; override;

    class function AutoManagedFields: Boolean; override;

    procedure Clear;
    procedure ClearValueForField(const AField: string);
    function GetFieldForValue(AValue: Variant): string; virtual;
    function GetFieldInfos(IgnoreId: Boolean = False): {$IFDEF UNICODE}TFieldInfo.TArray{$ELSE}TFieldInfoArray{$ENDIF}; override;
    function GetFieldInfo(const AFieldName: string): TFieldInfo; override;
    function GetFieldNames(IgnoreId: Boolean = False): TStringArray; override;
    function GetValueForField(const AField: string): Variant; override;
    procedure SafeCopyFrom(AEntity: TEntity; IgnoreFields: TEntityFieldNamesTokenArray); override;
    procedure SetValueForField(const AField: string; AValue: Variant); override;
    procedure SetValuesForFields(const SomeKeyValues: {$IFDEF UNICODE}TKeyValue.TArray{$ELSE}TKeyValueArray{$ENDIF});

    property FieldCount: Integer read GetFieldCount;
    property FieldNames[AIndex: Integer]: string read GetFieldName write SetFieldName;
    property FieldValues[AIndex: Integer]: Variant read GetFieldValue write SetFieldValue;
  end;

implementation

{ TEntityMapping }
uses
  TypInfo, Variants, ExceptionClasses;

class function TGenericEntity.AutoManagedFields: Boolean;
begin
  Result := True;
end;

procedure TGenericEntity.Clear;
begin
  FFieldValues.Clear;
end;

constructor TGenericEntity.Create;
begin
  inherited Create;
  FFieldValues := TSkyStringVariantList.Create;
end;

destructor TGenericEntity.Destroy;
begin
  FFieldValues.Free;
  inherited;
end;

function TGenericEntity.GetFieldCount: Integer;
begin
  Result := FFieldValues.Count;
end;

function TGenericEntity.GetFieldForValue(AValue: Variant): string;
var
  TheIndex: Integer;
begin
  TheIndex := FFieldValues.IndexOfObject(AValue);
  if TheIndex = -1 then
    Result := ''
  else
    Result := FFieldValues[TheIndex];
end;

function TGenericEntity.GetFieldInfo(const AFieldName: string): TFieldInfo;
var
  TheIndex: Integer;
begin
  if not FFieldValues.Find(AFieldName, TheIndex) then
    raise ESkyFieldDoesNotExist.Create(Self, 'GetFieldInfo', AFieldName);
  Result.FieldName := AFieldName;
  Result.FieldType := 'Variant';
  Result.FieldKind := tkVariant;
end;

function TGenericEntity.GetFieldInfos(IgnoreId: Boolean): {$IFDEF UNICODE}TFieldInfo.TArray{$ELSE}TFieldInfoArray{$ENDIF};
var
  TheCount: Integer;
  I: Integer;
begin
  SetLength(Result, FFieldValues.Count);
  TheCount := 0;
  for I := 0 to FFieldValues.Count - 1 do
    if IgnoreId and (FFieldValues[I] = 'Id') then
      Continue
    else
    begin
      Result[TheCount].FieldName := FFieldValues[I];
      Result[TheCount].FieldType := 'Variant';
      Result[TheCount].FieldKind := tkVariant;
      Inc(TheCount);
    end;
  SetLength(Result, TheCount);
end;

function TGenericEntity.GetFieldName(AIndex: Integer): string;
begin
  Result := FFieldValues[AIndex];
end;

function TGenericEntity.GetFieldNames(IgnoreId: Boolean): TStringArray;
var
  I: Integer;
begin
  for I := 0 to FFieldValues.Count - 1 do
    if (not IgnoreId) or (FFieldValues[I] <> 'Id') then
      Result.Add(FFieldValues[I]);
end;

function TGenericEntity.GetFieldValue(AIndex: Integer): Variant;
begin
  Result := GetValueForField(FieldNames[AIndex]);
end;

function TGenericEntity.GetValueForField(const AField: string): Variant;
var
  TheIndex: Integer;
begin
  TheIndex := FFieldValues.IndexOf(AField);
  if TheIndex = -1 then
    Result := Unassigned
  else
    Result := FFieldValues.Objects[TheIndex];
end;

procedure TGenericEntity.SafeCopyFrom(AEntity: TEntity; IgnoreFields: TEntityFieldNamesTokenArray);
begin
  // copy all properties
  CopyFrom(AEntity, IgnoreFields);
end;

procedure TGenericEntity.SetFieldName(AIndex: Integer; const Value: string);
var
  TheValueIndex: Integer;
begin
  TheValueIndex := FFieldValues.IndexOf(FieldNames[AIndex]);
  FFieldValues[TheValueIndex] := Value;
end;

procedure TGenericEntity.SetFieldValue(AIndex: Integer; const Value: Variant);
begin
  FFieldValues.Objects[FFieldValues.IndexOf(FieldNames[AIndex])] := Value;
end;

procedure TGenericEntity.SetValueForField(const AField: string; AValue: Variant);
var
  TheIndex: Integer;
begin
  TheIndex := FFieldValues.IndexOf(AField);
  if TheIndex = -1 then
    FFieldValues.AddObject(AField, AValue)
  else
    FFieldValues.Objects[TheIndex] := AValue;
  if ('Id' = AField) and (VarType(AValue) = vtInt64) then
    Id := AValue;
end;

procedure TGenericEntity.ClearValueForField(const AField: string);
begin
  FFieldValues.Delete(AField);
end;

procedure TGenericEntity.SetValuesForFields(const SomeKeyValues: {$IFDEF UNICODE}TKeyValue.TArray{$ELSE}TKeyValueArray{$ENDIF});
var
  I: Integer;
begin
  for I := 0 to Length(SomeKeyValues) - 1 do
    SetValueForField(SomeKeyValues[I].Key, SomeKeyValues[I].Value);
end;

initialization
  TGenericEntity.RegisterEntityClass;

end.
