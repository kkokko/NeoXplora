unit EntityList;

{$mode objfpc}{$H+}

interface

uses
  Entity, SkyLists, TypesConsts, TypesFunctions;

type

  { TEntityList }

  TEntityList = class(TSkyObjectList)
  private
    FAscending: Boolean;
    FSortDoublePrecision: Integer;
    FSortTimePrecision: TTimePrecision;
    FOnCompareEntities: TCompareEntitiesFunction;
    procedure SetAscending(const Value: Boolean);
    function CompareEntities(AnEntity1, AnEntity2: TEntity): Integer;
    procedure SetOnCompareEntities(const Value: TCompareEntitiesFunction);
  protected
    function CompareItemsFromIndex(Index1, Index2: Integer): Integer; override;
    function ComparePointers(APointer1, APointer2: Pointer): Integer; override;
    function GetItem(Index: Integer): TEntity; reintroduce; virtual;
    procedure PutItem(AnIndex:Integer; AnEntity: TEntity); reintroduce; virtual;
  public
    constructor Create(AnOwnsObjects: Boolean = False; AnOwnsEntities: Boolean = False); reintroduce;
    function Add(AnEntity: TEntity; ALink: TObject = nil): Integer; reintroduce; virtual;
    function AddObject(AnEntity: TEntity; ALink: TObject): Integer; reintroduce; virtual;
    procedure CopyFrom(AnEntityList: TEntityList);
    procedure Delete(AnEntity: TEntity); reintroduce; virtual;
    function Find(AnEntity: TEntity; out Index: Integer): Boolean; reintroduce; virtual;
    function FindFirstWithId(AnId: TId): TEntity; overload;
    function GetAllEntities: TEntities;
    function IndexOf(AnEntity: TEntity): Integer; reintroduce; virtual;
    function IsEqualTo(AnEntityList: TEntityList): Boolean;

    property Ascending: Boolean read FAscending write SetAscending;
    property Items[Index: Integer]: TEntity read GetItem write PutItem; default;
    property OnCompareEntities: TCompareEntitiesFunction read FOnCompareEntities write SetOnCompareEntities;
  end;

implementation

uses
  Variants, SysUtils, TypInfo;

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
begin
  Result := CompareBooleans(Assigned(AnEntity1), Assigned(AnEntity2));
  if Result <> 0 then
    Exit;
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

constructor TEntityList.Create(AnOwnsObjects: Boolean; AnOwnsEntities: Boolean);
begin
  inherited Create(AnOwnsObjects, AnOwnsEntities);
  FAscending := True;
  FSortDoublePrecision := 4;
  FSortTimePrecision := TypesConsts.dpSECOND;
  Sorted := False;
end;

procedure TEntityList.Delete(AnEntity: TEntity);
begin
  inherited Delete(AnEntity);
end;

function TEntityList.Find(AnEntity: TEntity; out Index: Integer): Boolean;
begin
  Result := inherited Find(AnEntity, Index);
end;

function TEntityList.FindFirstWithId(AnId: TId): TEntity;
var
  I: Integer;
begin
  Result := nil;
  for I := 0 to Count - 1 do
    if Items[I].Id = AnId then
    begin
      Result := Items[I];
      Exit;
    end;
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

end.
