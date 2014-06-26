unit EntityManyContainer;

interface

uses
  EntityContainer, EntityService, Entity, TypesConsts, SkyLists;

type
  TEntityManyContainer = class(TEntityContainer)
  private
    FConnection: TObject;
    function GetLinks(AIndex: Integer): TEntityService;
    function GetCount: Integer;

    procedure ApplyServicesRemoveDeletedFromDatabase;
    procedure ApplyServicesSetExistsInDataBase(const Value: Boolean);
    function GetItems: TEntityServices;
  protected
    FEntityServiceList: TSkyObjectList;
    FEntityServiceBackupList: TSkyObjectList;
    FLoaded: Boolean;
    FLocked: Boolean;
    FOwnEntityDataClass: TEntityClass;
    FOwnEntityServiceClass: TEntityServiceClass;
    procedure ApplyServicesCommitNewOrChanged; virtual;
    procedure CopyData(AService: TEntityService; AData: TEntity); virtual;
  public
    constructor Create(AConnection: TObject; ADataType: TEntityServiceClass); reintroduce;
    destructor Destroy; override;

    procedure Add(const AEntityService: TEntityService); overload; override;
    procedure Delete(const AEntityService: TEntityService);
    procedure Lock; override;
    procedure CleanBackup; override;
    procedure BeforeCommit; override;
    procedure AfterCommit; override;
    procedure RollBack; override;
    procedure DeleteAll; override;
    procedure LoadFromDB; override;
    procedure CreateBackup;
    procedure RestoreBackup;
    procedure DeleteBackup;
    procedure SetEntities(SomeServices: TEntityServices);
    procedure SetExistsInDataBase(const Value: Boolean); override;
    procedure CopyFrom(AContainer: TEntityContainer); override;
    function FindWithProperty(const AField: TEntityFieldNamesToken; const AValue: Variant): TEntityServices;
    // Update the entity list using ID parsing
    // SomeEntities with Id's will update existing id's
    // SomeEntities wit IdNil will be added
    // Existing entities wich are not in SomeEntities will be deleted
    procedure UpdateByIds(SomeEntities: TEntities);

    property Connection: TObject read FConnection;
    property Count: Integer read GetCount;
    property Items: TEntityServices read GetItems;
    property Links[AIndex: Integer]: TEntityService read GetLinks; default;
  end;

implementation

uses
  EntityTokens, EntityServiceManager, BaseQuery, BaseConnection;

{ TEntityManyContainer }

constructor TEntityManyContainer.Create(AConnection: TObject; ADataType: TEntityServiceClass);
begin
  inherited Create;
  FConnection := AConnection;
  FOwnEntityDataClass := TEntityServiceManager.GetEntityClass(ADataType);
  FOwnEntityServiceClass := ADataType;
  FEntityServiceList := TSkyObjectList.Create(True, False);
  FEntityServiceBackupList := TSkyObjectList.Create(True, False);
  FLocked := False;
  FLoaded := False;
end;

destructor TEntityManyContainer.Destroy;
begin
  FEntityServiceBackupList.Free;
  FEntityServiceList.Free;
  inherited;
end;

procedure TEntityManyContainer.Add(const AEntityService: TEntityService);
var
  TheIndex: Integer;
begin
  // no duplicated allowed
  if (FEntityServiceList.IndexOf(AEntityService) <> - 1) then
    Exit;

  TheIndex := FEntityServiceBackupList.IndexOf(AEntityService);
  // if the item existed, was deleted and now re-added
  if TheIndex <> -1 then
  begin
    // remove the delete flag from from the backup to prevent item beeing freed
    FEntityServiceBackupList.Objects[TheIndex] := nil;
    FEntityServiceList.Add(AEntityService);
  end
  else
    // add object with Data + Object, object will be automatically removed on rollback
    FEntityServiceList.AddObject(AEntityService, AEntityService);
end;

procedure TEntityManyContainer.AfterCommit;
begin
  ApplyServicesRemoveDeletedFromDatabase;
  ApplyServicesCommitNewOrChanged;
end;

procedure TEntityManyContainer.SetEntities(SomeServices: TEntityServices);
var
  I: Integer;
begin
  FEntityServiceBackupList.Clear;
  FEntityServiceList.Clear;
  for I := 0 to High(SomeServices) do
    SomeServices[I].ExistsInDataBase := True;
  Add(SomeServices);
end;

procedure TEntityManyContainer.SetExistsInDataBase(
  const Value: Boolean);
begin
  ApplyServicesSetExistsInDataBase(Value);
end;

procedure TEntityManyContainer.UpdateByIds(SomeEntities: TEntities);
var
  TheItems: TEntityServices;
  TheItem: TEntityService;
  I: Integer;
begin
  for I := 0 to High(SomeEntities) do
    if (SomeEntities[I].Id = IdNil) then
      // Add new items
      Add(FOwnEntityServiceClass.Create(FConnection, SomeEntities[I].CreateACopy))
    else
    begin
      TheItems := FindWithProperty(TEntity.EntityToken_Id, SomeEntities[I].Id);
      if TheItems <> nil then
        CopyData(TheItems[0], SomeEntities[I]);
    end;

  // remove items not in list
  for I := Count - 1 downto 0 do
  begin
    TheItem := Items[I];
    if (TheItem.ExistsInDataBase) and (not TheItem.Locked) then
      Delete(TheItem);
  end;
end;

procedure TEntityManyContainer.CopyData(AService: TEntityService; AData: TEntity);
begin
  // change existing Item
  AService.Lock;
  AService.Data.CopyFrom(AData);
  AService.Data.ExistsInDatabase := True; // gets corrupted when serializing
end;

procedure TEntityManyContainer.ApplyServicesCommitNewOrChanged;
var
  I: Integer;
  TheService: TEntityService;
begin
  for I := 0 to FEntityServiceList.Count - 1 do
  begin
    TheService := FEntityServiceList[I] as TEntityService;
    if not TheService.ExistsInDataBase then
      TheService.Lock;
    if TheService.Locked then
      TheService.Commit(False);
  end;
end;

procedure TEntityManyContainer.ApplyServicesRemoveDeletedFromDatabase;
var
  I: Integer;
  TheService: TEntityService;
begin
  for I := 0 to FEntityServiceBackupList.Count - 1 do
  begin
    TheService := FEntityServiceBackupList.Objects[I] as TEntityService;
    if Assigned(TheService) then
      TheService.Commit(False);
  end;
end;

procedure TEntityManyContainer.ApplyServicesSetExistsInDataBase(
  const Value: Boolean);
var
  I: Integer;
  TheEntityService: TEntityService;
begin
  for I := 0 to FEntityServiceList.Count - 1 do
  begin
    TheEntityService := FEntityServiceList[I] as TEntityService;
    TheEntityService.Lock;
    TheEntityService.ExistsInDatabase := Value;
  end;
end;

procedure TEntityManyContainer.BeforeCommit;
begin
  // intentionally left blank
end;

function TEntityManyContainer.GetCount: Integer;
begin
  Result := FEntityServiceList.Count;
end;

function TEntityManyContainer.GetItems: TEntityServices;
begin
  Result := TEntityServices(FEntityServiceList.GetAllItems);
end;

function TEntityManyContainer.GetLinks(AIndex: Integer): TEntityService;
begin
  Result := FEntityServiceList[AIndex] as TEntityService;
end;

procedure TEntityManyContainer.Delete(const AEntityService: TEntityService);
begin
  AEntityService.Lock;
  AEntityService.Delete;
  FEntityServiceBackupList.ObjectOfValue[AEntityService] := AEntityService;
  // free items wich were added but not commited
  if Assigned(FEntityServiceList.ObjectOfValue[AEntityService]) then
  begin
    AEntityService.Free;
    FEntityServiceList.ObjectOfValue[AEntityService] := nil;
  end;
  FEntityServiceList.Delete(AEntityService);
end;

procedure TEntityManyContainer.DeleteAll;
begin
  while FEntityServiceList.Count > 0 do
    Delete(FEntityServiceList.Items[0] as TEntityService);
end;

procedure TEntityManyContainer.LoadFromDB;
var
  TheEntities: TEntities;
begin
  Assert(not FLoaded, 'Load from DB should be called only once per instance');
  TheEntities := (Connection as TBaseConnection).SelectAll(FOwnEntityDataClass);
  SetEntities(FOwnEntityServiceClass.CreateFromEntityDatas(FConnection, TheEntities, False));
  FLoaded := True;
end;

procedure TEntityManyContainer.Lock;
begin
  CreateBackup;
  FLocked := True;
end;

procedure TEntityManyContainer.RollBack;
begin
  RestoreBackup;
  FLocked := False;
end;

procedure TEntityManyContainer.CleanBackup;
var
  I: Integer;
begin
  DeleteBackup;
  // make sure all the objects will get freed when cleaning the list
  for I := 0 to FEntityServiceList.Count - 1 do
    FEntityServiceList.Objects[I] := FEntityServiceList.Items[I];
end;

procedure TEntityManyContainer.CopyFrom(AContainer: TEntityContainer);
var
  I: Integer;
  TheContainer: TEntityManyContainer;
  TheService: TEntityService;
begin
  TheContainer := AContainer as TEntityManyContainer;
  FEntityServiceList.Clear;
  for I := 0 to TheContainer.FEntityServiceList.Count - 1 do
  begin
    TheService := (TheContainer.FEntityServiceList[I] as TEntityService).CreateACopy;
    FEntityServiceList.AddObject(TheService, TheService);
  end;
end;

procedure TEntityManyContainer.CreateBackup;
var
  I: Integer;
begin
  DeleteBackup;
  FEntityServiceBackupList.AddMultiple(FEntityServiceList.GetAllItems, nil);
  // make sure correct objects are freed when clearing the lists
  // copy just the data not the objects and clear the objects from the list
  for I := 0 to FEntityServiceList.Count - 1 do
    FEntityServiceList.Objects[I] := nil;
end;

procedure TEntityManyContainer.RestoreBackup;
var
  I: Integer;
begin
  FEntityServiceList.Clear;
  for I := 0 to FEntityServiceBackupList.Count - 1 do
  begin
    FEntityServiceBackupList.Objects[I] := nil;
    TEntityService(FEntityServiceBackupList[I]).RollBack;
  end;
  FEntityServiceList.AddMultiple(FEntityServiceBackupList.GetAllItems, FEntityServiceBackupList.GetAllItems);
  DeleteBackup;
end;

procedure TEntityManyContainer.DeleteBackup;
begin
  FEntityServiceBackupList.Clear;
end;

function TEntityManyContainer.FindWithProperty(const AField: TEntityFieldNamesToken; const AValue: Variant): TEntityServices;
var
  I: Integer;
  TempList: TSkyObjectList;
begin
  Result := nil;
  TempList := TSkyObjectList.Create(False);
  try
    for I := 0 to FEntityServiceList.Count - 1 do
      if (FEntityServiceList.Items[I] as TEntityService).Data.GetValueForField(AField.PropertyName) = AValue then
        TempList.Add(FEntityServiceList.Items[I]);
    Result := TEntityServices(TempList.GetAllItems);
  finally
    TempList.Free;
  end;
end;

end.
