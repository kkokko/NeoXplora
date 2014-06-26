unit Entity;

{$mode objfpc}{$H+}

interface

uses
  TypesConsts, Classes, Variants, StringArray;

type
  TEntity = class;
  TEntityClass = class of TEntity;
  TEntities = array of TEntity;

  TEntity = class(TPersistent)
  private
    class var
      FEntityManager: TObject;
      FEntityMappingManager: TObject;
      FEntityTokens: TObject;
    function GetId: TId;
    var
      FId: TId;
      FExistsInDatabase: Boolean;
    function GetDescription: string;
    procedure CopyPropertyFrom(AEntity: TEntity; const AFieldInfo: TFieldInfo);
    procedure SetDescription(const Value: string);
  protected
 {$IFDEF VER210}
    class destructor Destroy;
 {$ENDIF}
    function GetName: string; virtual;
    procedure SetName(const AName: string); virtual;
  public
    class var
      EntityToken_Id: TEntityFieldNamesToken;
      EntityToken_Name: TEntityFieldNamesToken;
    class function AutoManagedFields: Boolean; virtual;
    class function CreateAndCopyEntities(SomeEntities: TEntities): TEntities;
    class function CreateAndSafeCopyEntity(AClassType: TEntityClass; AnEntity: TEntity): TEntity;
    class function CreateAndSafeCopyEntities(AClassType: TEntityClass; SomeEntities: TEntities): TEntities;
    class function CreateEntityOfClass(const AClassName: string;
      AnExpectedClassType: TEntityClass = nil): TEntity;
    class function EntityManager: TObject;
    class function EntityMappingManager: TObject;
    class function EntityTokens: TObject;
    class procedure FreeEntities(SomeEntities: TEntities);
    class function FriendlyClassName: string; virtual;
    class function GetIdsOfEntities(SomeEntities: TEntities): TIds; overload;
    class function GetIdsOfEntities(SomeEntities: TEntities; AToken: TEntityFieldNamesToken): TIds; overload;
    class function GetNamesOfEntities(SomeEntities: TEntities): TStringArray;
    class procedure RegisterAllDatabaseKeys(AConnection: TObject);
    class procedure RegisterDatabaseKeys(AConnection: TObject); virtual;
    class procedure RegisterToken(out ATokenVar: TEntityFieldNamesToken;
      const AFieldName: string; const AFieldDef: string = '');
    class function SQLToken: string;

    function GetAsArray: TEntities;
    function GetClassName: string;
    function GetFieldNameCount(IgnoreId: Boolean = False): Integer;
    function GetFieldNames(IgnoreId: Boolean = False): TStringArray; virtual;
    function GetFieldInfos(IgnoreId: Boolean = False): {$IFDEF VER210}TFieldInfo.TArray{$ELSE}TFieldInfoArray{$ENDIF};virtual;
    function EvaluateFieldInfos: {$IFDEF VER210}TFieldInfo.TArray{$ELSE}TFieldInfoArray{$ENDIF};
    function GetFieldInfo(const AFieldName: string): TFieldInfo; virtual;
    function GetValueForField(const AField: string): Variant; virtual;
    procedure SetValueForField(const AField: string; AValue: Variant); virtual;
    class function GetDBFieldType(const AFieldName, ADataBaseKind: string;
      out ADataType: string): Boolean; virtual;
    class function GetFieldTypeFromTokens(AClassType: TEntityClass; const AFieldName: string;
      out ADataType: string): Boolean; virtual;
    function HasField(const AFieldName: string): Boolean;
    procedure Initialize; virtual;
    function CreateACopy: TEntity; virtual;
    // copies all the published fields of AEntity
    procedure CopyFrom(AEntity: TEntity; IgnoreFields: TEntityFieldNamesTokenArray = nil); virtual;
    function ToString: AnsiString; reintroduce;

    class function MappedFieldName(const AFieldName: string): string;
    // copies the published fields of AEntity wich exist in Self.FFieldNames
    procedure SafeCopyFrom(AEntity: TEntity; IgnoreFields: TEntityFieldNamesTokenArray = nil); virtual;
    class procedure RegisterEntityClass;
    class procedure RegisterEntityClassWithMappingToTable(const ATableName: string; AMappingManager: TObject = nil);
    function IsEqualTo(AEntity: TEntity): boolean; virtual;
    function Equals(AEntity: TEntity): Boolean; reintroduce;

    function GetAsKeyValues: {$IFDEF VER210}TKeyValue.TArray{$ELSE}TKeyValueArray{$ENDIF}; virtual;

    constructor Create; reintroduce; overload; virtual;
    destructor Destroy; override;

    property Description: string read GetDescription write SetDescription;
    property ExistsInDatabase: Boolean read FExistsInDatabase write FExistsInDatabase;
    property Id: TId read GetId write FId;
    property Name: string read GetName write SetName;
  end;

  PEntity = ^TEntity;
  PEntities = array of PEntity;
  TEntityClasses = array of TEntityClass;
  TCompareEntitiesFunction = function(AEntity1, AEntity2: TEntity): Integer of object;

implementation

uses
  TypInfo, SysUtils, EntityManager, EntityTokens, ExceptionClasses, EntityMapping, EntityMappingManager,
  TypesFunctions, EntityList, EntityJsonWriter, SkyIdList;

class function TEntity.EntityManager: TObject;
begin
  if not Assigned(FEntityManager) then
    FEntityManager := TEntityManager.Create;
  Result := FEntityManager;
end;

class function TEntity.EntityMappingManager: TObject;
begin
  if not Assigned(FEntityMappingManager) then
    FEntityMappingManager := TEntityMappingManager.Create;
  Result := FEntityMappingManager;
end;

class function TEntity.EntityTokens: TObject;
begin
  if not Assigned(FEntityTokens) then
    FEntityTokens := TEntityTokens.Create;
  Result := FEntityTokens;
end;

function TEntity.Equals(AEntity: TEntity): Boolean;
begin
  Result := IsEqualTo(AEntity);
end;

function TEntity.IsEqualTo(AEntity: TEntity): boolean;
var
  I: Integer;
  TheFieldInfos: {$IFDEF VER210}TFieldInfo.TArray{$ELSE}TFieldInfoArray{$ENDIF};
  TheObject, TheNewObject: TObject;
  TheBlobField, TheNewBlobField: TBlobType;
  TheValue1, TheValue2: Variant;
begin
  TheFieldInfos := AEntity.GetFieldInfos;
  Result := False;
  for I := 0 to High(TheFieldInfos) do
  begin
    if not HasField(TheFieldInfos[I].FieldName) then
      Exit;
    if (TheFieldInfos[I].FieldKind = tkClass) then
    begin
      TheObject := TObject(TVarData(GetValueForField(TheFieldInfos[I].FieldName)).VPointer);
      TheNewObject := TBlobType(TVarData(AEntity.GetValueForField(TheFieldInfos[I].FieldName)).VPointer);
      if not (Assigned(TheObject) = Assigned(TheNewObject))then
        Exit;

      if (TBlobType.ClassName = TheFieldInfos[I].FieldType) then
      begin
        TheBlobField := TheObject as TBlobType;
        TheNewBlobField := TheNewObject as TBlobType;
        if TheBlobField.Size <> TheNewBlobField.Size then
          Exit;
        TheNewBlobField.Position := 0;
        TheBlobField.Position := 0;
        if not CompareMem(TheBlobField.Memory, TheNewBlobField.Memory, TheNewBlobField.Size) then
          Exit;
      end
      else if (TEntityList.ClassName = TheFieldInfos[I].FieldType) then
        if not (TheObject as TEntityList).IsEqualTo(TheNewObject as TEntityList) then
          Exit
        else
      else
        if not (TheObject as TEntity).IsEqualTo(TheNewObject as TEntity) then
          Exit;
    end
    else begin
      TheValue1 := GetValueForField(TheFieldInfos[I].FieldName);
      TheValue2 := AEntity.GetValueForField(TheFieldInfos[I].FieldName);
      if ('TDate' = TheFieldInfos[I].FieldType) then
      begin
        if CompareIntegers(Trunc(TheValue1), Trunc(TheValue2)) <> 0 then
          Exit;
      end else if ('TDateTime' = TheFieldInfos[I].FieldType) then
      begin
        if CompareTimes(TheValue1, TheValue2) <> 0 then
          Exit;
      end
      else if ('TTime' = TheFieldInfos[I].FieldType) then
      begin
        if CompareTimes(Frac(TheValue1), Frac(TheValue2)) <> 0 then
          Exit;
      end
      else if (
        ('TPasswordString' <> TheFieldInfos[I].FieldType) and // passwordstrings - do not compare
        (TheValue1 <> TheValue2)
      ) then
        Exit;
    end;
  end;
  Result := True;
end;

class function TEntity.MappedFieldName(const AFieldName: string): string;
begin
  Result := TEntityMappingManager.GetMapping(Self).GetValueForField(AFieldName);
end;

function TEntity.EvaluateFieldInfos: {$IFDEF VER210}TFieldInfo.TArray{$ELSE}TFieldInfoArray{$ENDIF};
var
  Count, Size, I, TheResultCount: Integer;
  ThePropList: PPropList;
  ThePropInfo: PPropInfo;
begin
  TheResultCount := 0;
  Result := nil;
  Count := GetPropList(ClassInfo, tkAny, nil);
  Size := Count * SizeOf(Pointer);
  GetMem(ThePropList, Size);
  try
    Count := GetPropList(ClassInfo, tkAny, ThePropList);
    for I := 0 to Count - 1 do
    begin
      ThePropInfo := ThePropList^[I];

      if (ThePropInfo^.PropType^.Kind in tkProperties) and (ThePropInfo^.StoredProc <> nil) and
        (ThePropInfo^.SetProc <> nil) and (ThePropInfo^.GetProc <> nil) then
      begin
        SetLength(Result, TheResultCount + 1);
        Result[TheResultCount].FieldName := string(ThePropInfo^.Name);
        Result[TheResultCount].FieldType := string(ThePropInfo^.PropType^.Name);
        Result[TheResultCount].FieldKind  := ThePropInfo^.PropType^.Kind;
        Inc(TheResultCount);
      end;
    end;
  finally
    FreeMem(ThePropList);
  end;
end;

class procedure TEntity.FreeEntities(SomeEntities: TEntities);
var
  I: Integer;
begin
  for I := 0 to Length(SomeEntities) - 1 do
    SomeEntities[I].Free;
end;

class function TEntity.FriendlyClassName: string;
begin
  Result := ClassName;
end;

function TEntity.GetAsArray: TEntities;
begin
  if Self = nil then
    Result := nil
  else
  begin
    SetLength(Result, 1);
    Result[0] := Self;
  end;
end;

function TEntity.GetAsKeyValues: {$IFDEF VER210}TKeyValue.TArray{$ELSE}TKeyValueArray{$ENDIF};
var
  I: Integer;
  TheFieldNames: TStringArray;
begin
  TheFieldNames := GetFieldNames;
  SetLength(Result, Length(TheFieldNames));
  for I := 0 to Length(TheFieldNames) - 1 do
  begin
    Result[I].Key := TheFieldNames[I];
    Result[I].Value := GetValueForField(TheFieldNames[I]);
    Result[I].DataType := 'Variant';
  end;
end;

function TEntity.GetClassName: string;
begin
  Result := Self.ClassName;
end;

class function TEntity.GetFieldTypeFromTokens(AClassType: TEntityClass;
  const AFieldName: string; out ADataType: string): Boolean;
var
  TheClassType: TClass;
begin
  Result := False;
  TheClassType := AClassType;
  if TheClassType = nil then
    Exit;
  repeat
    Result := TEntityTokens.GetFieldDef(TheClassType.ClassName, AFieldName, ADataType);
    if Result then
      Exit;
    TheClassType := TheClassType.ClassParent;
  until (TheClassType = nil) or (TheClassType = TPersistent);
end;

class function TEntity.GetDBFieldType(const AFieldName, ADataBaseKind: string;
  out ADataType: string): Boolean;
begin
  Result := False;
end;

function TEntity.GetDescription: string;
begin
  Result := Name;
end;

constructor TEntity.Create;
var
  TheFieldInfos: {$IFDEF VER210}TFieldInfo.TArray{$ELSE}TFieldInfoArray{$ENDIF};
  TheObject: TObject;
  I: Integer;
begin
  inherited;
  FId := 0;
  ExistsInDatabase := False;
  if not AutoManagedFields then
  begin
    // Auto registers the fieldinfos if they are not registered
    TheFieldInfos := TEntityManager.GetEntityFieldInfos(Self, True);

    for I := 0 to High(TheFieldInfos) do
      if TheFieldInfos[I].FieldKind = tkClass then
        if TEntityList.ClassName = TheFieldInfos[I].FieldType then
        begin
          TheObject := TEntityList.Create(False, True);
          SetObjectProp(Self, TheFieldInfos[I].FieldName, TheObject);
        end else if TSkyIdList.ClassName = TheFieldInfos[I].FieldType then
        begin
          TheObject := TSkyIdList.Create(True);
          SetObjectProp(Self, TheFieldInfos[I].FieldName, TheObject);
        end
        else if TBlobType.ClassName = TheFieldInfos[I].FieldType then
        begin
          TheObject := TBlobType.Create;
          SetObjectProp(Self, TheFieldInfos[I].FieldName, TheObject);
        end;
  end;
end;

destructor TEntity.Destroy;
var
  TheFieldInfos: {$IFDEF VER210}TFieldInfo.TArray{$ELSE}TFieldInfoArray{$ENDIF};
  I: Integer;
begin
  TheFieldInfos := GetFieldInfos(True);
  for I := 0 to High(TheFieldInfos) do
    if TheFieldInfos[I].FieldKind = tkClass then
      TObject(TVarData(GetValueForField(TheFieldInfos[I].FieldName)).VPointer).Free;
  inherited;
end;

{$IFDEF VER210}
class destructor TEntity.Destroy;
{$ELSE}
procedure TEntityDestroy;
{$ENDIF}
begin
  TEntity.FEntityMappingManager.Free;
  TEntity.FEntityTokens.Free;
  TEntity.FEntityManager.Free;
end;

function TEntity.CreateACopy: TEntity;
begin
  if Self = nil then
  begin
    Result := nil;
    Exit;
  end;
  Result := TEntityClass(ClassType).Create;
  Result.CopyFrom(Self);
{$IFDEF FullTest}
  Assert((Result <> Self) and Self.IsEqualTo(Result), 'Written entity is not readable!');
{$ENDIF}
end;

class function TEntity.CreateAndCopyEntities(SomeEntities: TEntities): TEntities;
var
  I: Integer;
begin
  Result := TEntities(GetEmptyObjectArray(Length(SomeEntities)));
  try
    for I := 0 to Length(Result) - 1 do
      Result[I] := SomeEntities[I].CreateACopy;
  except
    FreeEntities(Result);
    raise;
  end;
end;

class function TEntity.CreateAndSafeCopyEntities(AClassType: TEntityClass; SomeEntities: TEntities): TEntities;
var
  I: Integer;
begin
  Result := TEntities(GetEmptyObjectArray(Length(SomeEntities)));
  try
    for I := 0 to Length(Result) - 1 do
    begin
      Result[I] := AClassType.Create;
      Result[I].SafeCopyFrom(SomeEntities[I]);
    end;
  except
    TEntity.FreeEntities(TEntities(Result));
    raise;
  end;
end;

class function TEntity.CreateAndSafeCopyEntity(AClassType: TEntityClass;
  AnEntity: TEntity): TEntity;
begin
  Result := AClassType.Create;
  try
    Result.SafeCopyFrom(AnEntity);
  except
    Result.Free;
    raise;
  end;
end;

class function TEntity.CreateEntityOfClass(const AClassName: string;
  AnExpectedClassType: TEntityClass): TEntity;
var
  TheClassType: TEntityClass;
begin
  TheClassType := TEntityClass(TEntityManager.GetEntityClassForName(AClassName));
  if TheClassType = nil then
    raise ESkyClassNotRegistered.Create('TEntity', 'CreateEntityOfClass', AClassName);
  if (AnExpectedClassType <> nil) and not (TheClassType.InheritsFrom(AnExpectedClassType)) then
      raise ESkyInvalidClassType.Create(nil, 'TEntityXmlStreamer.ReadEntityFromNode',
        AnExpectedClassType.ClassName, AClassName);
  Result := TheClassType.Create;
end;

procedure TEntity.CopyPropertyFrom(AEntity: TEntity; const AFieldInfo: TFieldInfo);
var
  TheBlobField, TheNewBlobField: TBlobType;
  TheList, TheNewList: TEntityList;
  TheIdList, TheNewIdList: TSkyIdList;
  TheEntity, TheNewEntity: TEntity;
  TheClassType: TEntityClass;
begin
  if (AFieldInfo.FieldKind = tkClass) then
    if (TBlobType.ClassName = AFieldInfo.FieldType) then
    begin
      TheNewBlobField := TBlobType(TVarData(AEntity.GetValueForField(AFieldInfo.FieldName)).VPointer);
      if TheNewBlobField <> nil then
      begin
        TheBlobField := TBlobType.Create;
        try
          TheBlobField.LoadFromStream(TheNewBlobField);
        except
          TheBlobField.Free;
        end;
      end
      else
        TheBlobField := nil;
      GetObjectProp(Self, AFieldInfo.FieldName).Free;
      SetObjectProp(Self, AFieldInfo.FieldName, TheBlobField);
    end
    else if (TEntityList.ClassName = AFieldInfo.FieldType) then
    begin
      GetObjectProp(Self, AFieldInfo.FieldName).Free;
      TheList := TEntityList.Create(False, True);
      TheNewList := TEntityList(TVarData(AEntity.GetValueForField(AFieldInfo.FieldName)).VPointer);
      TheList.CopyFrom(TheNewList);
      SetObjectProp(Self, AFieldInfo.FieldName, TheList);
    end
    else if (TSkyIdList.ClassName = AFieldInfo.FieldType) then
    begin
      GetObjectProp(Self, AFieldInfo.FieldName).Free;
      TheIdList := TSkyIdList.Create(True);
      TheNewIdList := TSkyIdList(TVarData(AEntity.GetValueForField(AFieldInfo.FieldName)).VPointer);
      TheIdList.CopyFrom(TheNewIdList);
      SetObjectProp(Self, AFieldInfo.FieldName, TheIdList);
    end
    else begin
      // otherwise assume class - TEntity
      TheNewEntity := TEntity(TVarData(AEntity.GetValueForField(AFieldInfo.FieldName)).VPointer);
      if not Assigned(TheNewEntity) then
        TheEntity := nil
      else
      begin
        TheClassType := TEntityClass(TEntityManager.GetEntityClassForName(AFieldInfo.FieldType));
        if TheClassType = nil then
          raise ESkyClassNotRegistered.Create('TEntityXmlStreamer', 'ReadEntityFromNode', AFieldInfo.FieldType);
        if TheNewEntity.InheritsFrom(TheClassType) then
          TheEntity := TheNewEntity.CreateACopy
        else
        begin
          TheEntity := CreateEntityOfClass(AFieldInfo.FieldType);
          TheEntity.SafeCopyFrom(TheNewEntity);
        end;
      end;
      GetObjectProp(Self, AFieldInfo.FieldName).Free;
      SetObjectProp(Self, AFieldInfo.FieldName, TheEntity);
    end
  else
    SetValueForField(AFieldInfo.FieldName, AEntity.GetValueForField(AFieldInfo.FieldName));
end;

class function TEntity.AutoManagedFields: Boolean;
begin
  Result := False;
end;

procedure TEntity.CopyFrom(AEntity: TEntity; IgnoreFields: TEntityFieldNamesTokenArray);
var
  TheFieldInfos: {$IFDEF VER210}TFieldInfo.TArray{$ELSE}TFieldInfoArray{$ENDIF};
  TheFieldFound: Boolean;
  I, J: Integer;
begin
  if AEntity = nil then
    Exit;
  TheFieldInfos := AEntity.GetFieldInfos;
  for I := 0 to High(TheFieldInfos) do
  begin
    TheFieldFound := False;
    for J := 0 to High(IgnoreFields) do
      if SameText(TokenPropertyName(IgnoreFields[J]), TheFieldInfos[I].FieldName) then
      begin
        TheFieldFound := True;
        Break;
      end;
    if not TheFieldFound then
      CopyPropertyFrom(AEntity, TheFieldInfos[I]);
  end;
  ExistsInDatabase := AEntity.ExistsInDatabase;
end;

procedure TEntity.SafeCopyFrom(AEntity: TEntity; IgnoreFields: TEntityFieldNamesTokenArray);
var
  TheFieldInfos: {$IFDEF VER210}TFieldInfo.TArray{$ELSE}TFieldInfoArray{$ENDIF};
  TheFieldInfo: TFieldInfo;
  TheFieldInfoClass: TFieldInfoListClass;
  TheFieldFound: Boolean;
  I, J: Integer;
begin
  TheFieldInfoClass := TEntityManager.GetFieldInfoClassForEntityClass(ClassType);
  TheFieldInfos := AEntity.GetFieldInfos;
  for I := 0 to High(TheFieldInfos) do
  begin
    TheFieldFound := False;
    for J := 0 to High(IgnoreFields) do
      if SameText(TokenPropertyName(IgnoreFields[J]), TheFieldInfos[I].FieldName) then
      begin
        TheFieldFound := True;
        Break;
      end;
    if (not TheFieldFound) and (TheFieldInfoClass.FindFieldByName(
      TheFieldInfos[I].FieldName, TheFieldInfo))
    then
      CopyPropertyFrom(AEntity, TheFieldInfos[I]);
  end;
  ExistsInDatabase := AEntity.ExistsInDatabase;
end;

function TEntity.GetFieldInfos(IgnoreId: Boolean = False): {$IFDEF VER210}TFieldInfo.TArray{$ELSE}TFieldInfoArray{$ENDIF};
begin
  Result := TEntityManager.GetEntityFieldInfos(Self, IgnoreId);
end;

function TEntity.GetFieldInfo(const AFieldName: string): TFieldInfo;
begin
  Result := TEntityManager.GetEntityFieldInfo(ClassType, AFieldName);
end;

function TEntity.GetFieldNameCount(IgnoreId: Boolean): Integer;
begin
  Result := Length(GetFieldNames(IgnoreId));
end;

function TEntity.GetFieldNames(IgnoreId: Boolean = False): TStringArray;
begin
  Result := TEntityManager.GetEntityFieldNames(ClassType, IgnoreId);
end;

function TEntity.GetName: string;
begin
  Result := ClassName;
end;

function TEntity.GetId: TId;
begin
  if Self = nil then
    Result := IdNil
  else
    Result := FId;
end;

class function TEntity.GetIdsOfEntities(SomeEntities: TEntities): TIds;
var
  I: Integer;
begin
  SetLength(Result, Length(SomeEntities));
  for I := 0 to High(SomeEntities) do
    Result[I] := SomeEntities[I].Id;
end;

class function TEntity.GetIdsOfEntities(SomeEntities: TEntities;
  AToken: TEntityFieldNamesToken): TIds;
var
  I: Integer;
begin
  SetLength(Result, Length(SomeEntities));
  for I := 0 to High(SomeEntities) do
    Result[I] := Int64(SomeEntities[I].GetValueForField(TokenPropertyName(AToken)));
end;

class function TEntity.GetNamesOfEntities(SomeEntities: TEntities): TStringArray;
var
  I: Integer;
begin
  SetLength(Result, Length(SomeEntities));
  for I := 0 to High(SomeEntities) do
    Result[I] := SomeEntities[I].Name;
end;

function TEntity.GetValueForField(const AField: string): Variant;
begin
  Result := GetPropValue(Self, AField);
end;

function TEntity.HasField(const AFieldName: string): Boolean;
begin
  Result := GetPropInfo(Self, AFieldName) <> nil;
end;

procedure TEntity.Initialize;
begin
end;

class procedure TEntity.RegisterAllDatabaseKeys(AConnection: TObject);
var
  TheClasses: TEntityClasses;
  I: Integer;
begin
  TheClasses := TEntityManager.GetAllEntityClasses;
  for I := 0 to High(TheClasses) do
    TheClasses[I].RegisterDatabaseKeys(AConnection);
end;

class procedure TEntity.RegisterDatabaseKeys(AConnection: TObject);
begin
  // override in inherited
end;

class procedure TEntity.RegisterEntityClass;
begin
  TEntityManager.RegisterEntityClass(Self);
end;

class procedure TEntity.RegisterEntityClassWithMappingToTable(const ATableName: string; AMappingManager: TObject);
begin
  Assert((AMappingManager = nil) or (AMappingManager is TEntityMappingManager), 'Wrong classtype for the Mapping Manager');
  RegisterEntityClass;
  if Assigned(AMappingManager) then
    (AMappingManager as TEntityMappingManager).RegisterMapping(Self,  TEntityMapping.Create(ATableName, TEntityMapping.EntityClassToMappingFields(Self)))
  else
    TEntityMappingManager.RegisterMapping(Self,  TEntityMapping.Create(ATableName, TEntityMapping.EntityClassToMappingFields(Self)));
end;

class procedure TEntity.RegisterToken(out ATokenVar: TEntityFieldNamesToken;
  const AFieldName: string; const AFieldDef: string);
begin
  ATokenVar := TEntityTokens.RegisterFieldNames(Self, AFieldName, AFieldDef);
end;

procedure TEntity.SetDescription(const Value: string);
begin
  // implemented in inherited classes
end;

procedure TEntity.SetName(const AName: string);
begin
  // implemented in inherited classes
end;

procedure TEntity.SetValueForField(const AField: string; AValue: Variant);
begin
  SetPropValue(Self, AField, AValue);
end;

class function TEntity.SQLToken: string;
begin
  Result := '!' + Self.ClassName;
end;

function TEntity.ToString: AnsiString;
var
  TheStream: TMemoryStream;
begin
  if Self = nil then
    Exit;
  TheStream := TMemoryStream.Create;
  try
    {$IFNDEF PACKAGE}
    TEntityJsonWriter.WriteEntity(TheStream, Self);
    {$ENDIF}
    SetLength(Result, TheStream.Size);
    if TheStream.Size > 0 then
    begin
      TheStream.Position := 0;
      TheStream.Read(Result[1], TheStream.Size);
    end;
  finally
    TheStream.Free;
  end;
end;

initialization
  TEntity.RegisterEntityClass;
  TEntity.RegisterToken(TEntity.EntityToken_Id, 'Id');
  TEntity.RegisterToken(TEntity.EntityToken_Name, 'Name');
  TEntity(0).ToString;

{$IFNDEF VER210}
finalization
  TEntityDestroy;
{$ENDIF}

end.
