unit EntityList;

interface

uses
  Entity, SkyLists, TypesConsts, TypesFunctions;

type
  TEntityList = class(TSkyObjectList)
  private
    FAscending: Boolean;
    FSortDoublePrecision: Integer;
    FSortTimePrecision: TTimePrecision;
    FOnCompareEntities: TCompareEntitiesFunction;
    FSortTokens: {$IFDEF VER210}TEntityFieldNamesToken.TArray{$ELSE}TEntityFieldNamesTokenArray{$ENDIF};
    procedure SetAscending(const Value: Boolean);
    function CompareEntities(AnEntity1, AnEntity2: TEntity): Integer;
    procedure SetOnCompareEntities(const Value: TCompareEntitiesFunction);
    procedure SetSortTokens(const Value: {$IFDEF VER210}TEntityFieldNamesToken.TArray{$ELSE}TEntityFieldNamesTokenArray{$ENDIF});
  protected
    function CompareItemsFromIndex(Index1, Index2: Integer): Integer; override;
    function ComparePointers(APointer1, APointer2: Pointer): Integer; override;
    function GetItem(Index: Integer): TEntity; reintroduce; virtual;
    procedure PutItem(AnIndex:Integer; AnEntity: TEntity); reintroduce; virtual;
  public
    constructor Create(OwnsObjects: Boolean = False; OwnsEntities: Boolean = False); reintroduce;
    function Add(AnEntity: TEntity; ALink: TObject = nil): Integer; reintroduce; virtual;
    function AddObject(AnEntity: TEntity; ALink: TObject): Integer; reintroduce; virtual;
    procedure CopyFrom(AnEntityList: TEntityList);
    procedure Delete(AnEntity: TEntity); reintroduce; virtual;
    procedure DeleteFirstWithValue(AToken: TEntityFieldNamesToken; AnId: TId);
    procedure DeleteWithId(AnId: TId);
    function Find(AnEntity: TEntity; out Index: Integer): Boolean; reintroduce; virtual;
    function FindFirstWithProperty(const AField: TEntityFieldNamesToken; const AValue: Variant): TEntity; overload;
    class function FindFirstWithProperty(SomeEntities: TEntities;
      const AField: TEntityFieldNamesToken; const AValue: Variant): TEntity; overload;
    function FindFirstWithProperties(SomeFields: array of TEntityFieldNamesToken; SomeValues: array of Variant): TEntity;
    function FindWithProperties(SomeFields: array of TEntityFieldNamesToken; SomeValues: array of Variant): TEntities;
    function FindWithProperty(const AField: TEntityFieldNamesToken; const AValue: Variant): TEntities;
    function GetAllEntities: TEntities;
    function IndexOf(AnEntity: TEntity): Integer; reintroduce; virtual;
    function IsEqualTo(AnEntityList: TEntityList): Boolean;
    function PropertyEquals(const AField: TEntityFieldNamesToken; const AValue: Variant): Boolean;
    procedure Sort(ASortToken: TEntityFieldNamesToken); overload;
    class function SortEntities(SomeEntities: TEntities; ASortToken: TEntityFieldNamesToken): TEntities;

    property Ascending: Boolean read FAscending write SetAscending;
    property Items[Index: Integer]: TEntity read GetItem write PutItem; default;
    property OnCompareEntities: TCompareEntitiesFunction read FOnCompareEntities write SetOnCompareEntities;
    property SortTokens: {$IFDEF VER210}TEntityFieldNamesToken.TArray{$ELSE}TEntityFieldNamesTokenArray{$ENDIF} read FSortTokens write SetSortTokens;
    property SortTimePrecision: TTimePrecision read FSortTimePrecision write FSortTimePrecision;
    property SortDoublePrecision: Integer read FSortDoublePrecision write FSortDoublePrecision;
  end;

implementation

uses
  EntityTokens, Variants, SysUtils, TypInfo;

{ TEntityList }

function TEntityList.Add(AnEntity: TEntity; ALink: TObject): Integer;
begin
  Result := AddObject(AnEntity, ALink);
end;

function TEntityList.AddObject(AnEntity: TEntity; ALink: TObject): Integer;
begin
  Result := inherited AddObject(AnEntity, ALink)
end;

function TEntityList.CompareEntities(AnEntity1, AnEntity2: TEntity): Integer;
var
  TheValue1, TheValue2: Variant;
  TheFieldName, TheSortFieldType : string;
  TheFieldInfo: TFieldInfo;
  TheTypeInfo: PTypeInfo;
  I: Integer;
begin
  Result := CompareBooleans(Assigned(AnEntity1), Assigned(AnEntity2));
  if Result <> 0 then
    Exit;
  if FSortTokens = nil then
  begin
    Result := inherited ComparePointers(AnEntity1, AnEntity2);
    Exit;
  end;

  for I := 0 to High(FSortTokens) do
  begin
    TheFieldName := TEntityTokens.GetFieldNameForToken(FSortTokens[I]);
    TheValue1 := AnEntity1.GetValueForField(TheFieldName);
    TheValue2 := AnEntity2.GetValueForField(TheFieldName);
    TheFieldInfo := AnEntity1.GetFieldInfo(TheFieldName);
    TheSortFieldType := TheFieldInfo.FieldType;
    
    if TheFieldInfo.FieldKind = tkEnumeration then
    begin
      TheTypeInfo := GetPropInfo(AnEntity1, TheFieldName)^.PropType^;
      Result := TypesFunctions.CompareIntegers(GetEnumValue(TheTypeInfo, 
        TheValue1), GetEnumValue(TheTypeInfo, TheValue2));
    end
    else if VarIsFloat(TheValue1) then
      if (TheSortFieldType = 'TDate') or (TheSortFieldType = 'TTime') or (TheSortFieldType = 'TDateTime') then
        Result := TypesFunctions.CompareTimes(TheValue1, TheValue2, FSortTimePrecision)
      else
        Result := TypesFunctions.CompareDoubles(TheValue1, TheValue2, FSortDoublePrecision)
    else
      if VarIsStr(TheValue1) then
        Result := CompareText(TheValue1, TheValue2)
      else
       if TheValue1 < TheValue2 then
          Result := -1
        else if TheValue1 > TheValue2 then
          Result := 1;

    if not Ascending then
      Result := - Result;

    if Result <> 0 then
      Exit;
  end;
end;

function TEntityList.CompareItemsFromIndex(Index1, Index2: Integer): Integer;
begin
  if(not Assigned(FOnCompareEntities)) then
    Result := CompareEntities(Items[Index1], Items[Index2])
  else
    Result :=  FOnCompareEntities(Items[Index1], Items[Index2]);
end;

function TEntityList.ComparePointers(APointer1, APointer2: Pointer): Integer;
begin
  if(not Assigned(FOnCompareEntities)) then
    Result := CompareEntities(TEntity(TObject(APointer1)), TEntity(TObject(APointer2)))
  else
    Result :=  FOnCompareEntities(TEntity(TObject(APointer1)), TEntity(TObject(APointer2)));
end;

procedure TEntityList.CopyFrom(AnEntityList: TEntityList);
var
  TheEntity: TEntity;
  I: Integer;
begin
  Clear;
  for I := 0 to AnEntityList.Count - 1 do
  begin
    if OwnsItems then
      TheEntity := AnEntityList.Items[I].CreateACopy
    else
      TheEntity := AnEntityList.Items[I];
     // if we own objects, do not copy them unless they are entitties
    if OwnsObjects then
      if AnEntityList.Objects[I] is TEntity then
        Add(TheEntity, (AnEntityList.Objects[I] as TEntity).CreateACopy)
      else
        // object value is not copy due to parenting :)
        // we don't want to free someone elses children
        Add(TheEntity, nil)
    else
      Add(TheEntity, AnEntityList.Objects[I]);
  end;
end;

constructor TEntityList.Create(OwnsObjects, OwnsEntities: Boolean);
begin
  inherited Create(OwnsObjects, OwnsEntities);
  FAscending := True;
  FSortDoublePrecision := 4;
  FSortTimePrecision := TypesConsts.dpSECOND;
  FSortTokens := nil;
  Sorted := False;
end;

procedure TEntityList.Delete(AnEntity: TEntity);
begin
  inherited Delete(AnEntity);
end;

procedure TEntityList.DeleteWithId(AnId: TId);
begin
  Delete(FindFirstWithProperty(TEntity.EntityToken_Id, AnId));
end;

procedure TEntityList.DeleteFirstWithValue(AToken: TEntityFieldNamesToken; AnId: TId);
begin
  Delete(FindFirstWithProperty(AToken, AnId));
end;

function TEntityList.Find(AnEntity: TEntity; out Index: Integer): Boolean;
begin
  Result := inherited Find(AnEntity, Index);
end;

function TEntityList.FindWithProperties(SomeFields: array of TEntityFieldNamesToken;
  SomeValues: array of Variant): TEntities;
var
  I, J: Integer;
  TempList: TEntityList;
  TheObjectFound: Boolean;
begin
  Assert(Length(SomeFields) = Length(SomeValues), 'Both arrays should have the same length!');
  TempList := TEntityList.Create(False, False);
  try
    for I := 0 to Count - 1 do
    begin
      TheObjectFound := True;
      for J := 0 to Length(SomeFields) - 1 do
        if Items[I].GetValueForField(SomeFields[J].PropertyName) <> SomeValues[J] then
        begin
          TheObjectFound := False;
          Break;
        end;
      if TheObjectFound then
        TempList.Add(Items[I]);
    end;
    Result := TempList.GetAllEntities;
  finally
    TempList.Free;
  end;
end;

function TEntityList.FindWithProperty(const AField: TEntityFieldNamesToken; const AValue: Variant): TEntities;
begin
  Result := FindWithProperties([AField], [AValue]);
end;

function TEntityList.FindFirstWithProperties(SomeFields: array of TEntityFieldNamesToken;
  SomeValues: array of Variant): TEntity;
var
  I, J: Integer;
  TheObjectFound: Boolean;
begin
  Assert(Length(SomeFields) = Length(SomeValues), 'Both arrays should have the same length!');
  for I := 0 to Count - 1 do
  begin
    TheObjectFound := True;
    for J := 0 to Length(SomeFields) - 1 do
      if Items[I].GetValueForField(SomeFields[J].PropertyName) <> SomeValues[J] then
      begin
        TheObjectFound := False;
        Break;
      end;
    if TheObjectFound then
    begin
      Result := Items[I];
      Exit;
    end;
  end;
  Result := nil;
end;

class function TEntityList.FindFirstWithProperty(SomeEntities: TEntities;
  const AField: TEntityFieldNamesToken; const AValue: Variant): TEntity;
var
  TheList: TEntityList;
begin
  TheList := TEntityList.Create;
  try
    TheList.AddMultiple(TObjects(SomeEntities), nil);
    Result := TheList.FindFirstWithProperty(AField, AValue);
  finally
    TheList.Free;
  end;
end;

function TEntityList.FindFirstWithProperty(const AField: TEntityFieldNamesToken; const AValue: Variant): TEntity;
begin
  Result := FindFirstWithProperties([AField], [AValue]);
end;

function TEntityList.GetAllEntities: TEntities;
begin
  Result := TEntities(GetAllItems);
end;

function TEntityList.GetItem(Index: Integer): TEntity;
begin
  Result := TEntity(inherited GetItem(Index));
end;

function TEntityList.IndexOf(AnEntity: TEntity): Integer;
begin
  Result := inherited IndexOf(AnEntity);
end;

function TEntityList.IsEqualTo(AnEntityList: TEntityList): Boolean;
var
  I: Integer;
begin
  Result := Count = AnEntityList.Count;
  if not Result then
    Exit;
  for I := 0 to Count - 1 do
  begin
    Result := Items[I].IsEqualTo(AnEntityList.Items[I]) and
    (
      (Objects[I] = AnEntityList.Objects[I]) or
      TEntity(Objects[I]).IsEqualTo(TEntity(AnEntityList.Objects[I]))
    );
    if not Result then
    Exit;
  end;
end;

function TEntityList.PropertyEquals(const AField: TEntityFieldNamesToken;
  const AValue: Variant): Boolean;
var
  I: Integer;
begin
  Result := False;
  for I := 0 to Count - 1 do
    if Items[I].GetValueForField(AField.PropertyName) <> AValue then
      Exit;
  Result := True;
end;

procedure TEntityList.PutItem(AnIndex: Integer; AnEntity: TEntity);
begin
  inherited PutItem(AnIndex, AnEntity);
end;

procedure TEntityList.SetAscending(const Value: Boolean);
begin
  if FAscending = Value then
    Exit;
  FAscending := Value;
  Sort;
end;

procedure TEntityList.SetOnCompareEntities(
  const Value: TCompareEntitiesFunction);
begin
  FOnCompareEntities := Value;
  Sorted := False;
end;


procedure TEntityList.SetSortTokens(const Value: {$IFDEF VER210}TEntityFieldNamesToken.TArray{$ELSE}TEntityFieldNamesTokenArray{$ENDIF});
begin
  FSortTokens := Value;
  Sorted := False;
end;

procedure TEntityList.Sort(ASortToken: TEntityFieldNamesToken);
begin
  Sorted := False;
  SetLength(FSortTokens, 1);
  FSortTokens[0] := ASortToken;
  Sort;
end;

class function TEntityList.SortEntities(SomeEntities: TEntities;
  ASortToken: TEntityFieldNamesToken): TEntities;
var
  TheList: TEntityList;
begin
  TheList := TEntityList.Create;
  TheList.Sorted := False;
  try
    TheList.AddMultiple(TObjects(SomeEntities), nil);
    TheList.Sort(ASortToken);
    Result := TheList.GetAllEntities;
  finally
    FreeAndNil(TheList);
  end;
end;

end.
