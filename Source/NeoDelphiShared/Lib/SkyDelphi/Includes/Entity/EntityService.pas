unit EntityService;

interface

uses
  Entity, SkyLists, TypesConsts;

type
  TEntityService = class;
  TEntityServiceClass = class of TEntityService;
  TEntityServices = array of TEntityService;
  TEntityService = class (TObject)
  private
    FForceUpdate: Boolean;
    FLoaded: Boolean;
    FConnection: TObject;
    class var
      FEntityServiceManager: TObject;

    procedure ApplyContainersLock;
    procedure ApplyContainersBeforeCommit;
    procedure ApplyContainersAfterCommit;
    procedure ApplyContainersRollBack;
    procedure ApplyContainersDeleteAll;
    procedure ApplyContainersCleanUpBackup;
    procedure ApplyContainersSetExistsInDataBase(const Value: Boolean);
    procedure ApplyContainersCopyFrom(ASource: TEntityService);

    procedure ApplyDelete;
    procedure ApplyUpdateOrInsert;
    procedure ApplyLoadFromDB(AnId: TId);
    procedure ApplyLoadContainersFromDB;
    function GetExistsInDataBase: Boolean;
    procedure SetExistsInDataBase(const Value: Boolean);
    function GetEntityChanged: Boolean;
  protected
    FData: TEntity;
    FDataBackup: TEntity; //used by the EntityServiceHelper
    FEntityContainersList: TSkyObjectList;
    FMarkedForDeletion: Boolean;
    FLocked: Boolean;

{$IFDEF VER210}
    class destructor Destroy;
{$ENDIF}

    // called by inherited classes to specify(register) their containers
    procedure InitializeContainers; virtual;
    // add a container to the list
    procedure RegisterEntityContainer(AEntityContainer: TObject);
    // does the actual commit
    procedure ApplyCommit(CleanUpBackup: Boolean);

    function GetId: TId; virtual;
    function GetName: string; virtual;
    procedure SetId(const Value: TId); virtual;
    procedure SetName(const Value: string); virtual;
  public
    // uses AEntityData as FData if it is assigned or creates an EntityData
    constructor Create(AConnection: TObject; AEntityData: TEntity); virtual;

    // loads the EntityData and all the containers from the database
    constructor CreateFromDB(AConnection: TObject; AnId: TId); virtual;
    // creates services for the data it receives. if Load containers is true then
    // it querys the database for container data
    destructor Destroy; override;

    class function CreateFromEntityDatas(AConnection: TObject; SomeEntities: TEntities; LoadContainers: Boolean): TEntityServices;
    class function EntityServiceManager: TObject;
    class procedure FreeEntities(SomeEntities: TEntityServices);
    class function GetDatasForEntities(SomeEntityServices: TEntityServices): TEntities;
    class function LoadAllFromDB(AConnection: TObject; LoadContainers: Boolean = True): TEntityServices;
    // used to register the EntityServiceClass and its relation to the
    // EntityData class
    class procedure RegisterClass(AEntityClass: TEntityClass);

    procedure ApplyContainersLoadFromDB;
    // creates a copy of the entity service including the containers
    function CreateACopy: TEntityService;
    // sets the Locked flag and creates a copy of the data
    procedure Lock;
    // applies the changes to the database
    procedure Commit; overload;
    // applies the changes without checking if the record has changed - usefull when
    // the data was deserialized and we don't want to load it from the database again
    procedure CommitUnchanged;
    // rolls back the changes to the objects using the databackup
    procedure RollBack;
    // validates the business of the data on commit
    procedure Validate; virtual;
    // validates if the record can be deleted
    procedure ValidateDelete; virtual;
    // mark for deletion
    procedure Delete;

    procedure CreateBackup;
    procedure RestoreBackup;
    procedure DeleteBackup;
    procedure Commit(CleanUpBackup: Boolean); overload;

    property Connection: TObject read FConnection;
    property Data: TEntity read FData;
    property Locked: Boolean read FLocked;
    property ExistsInDataBase: Boolean read GetExistsInDataBase write SetExistsInDataBase;
    property EntityChanged: Boolean read GetEntityChanged;
    property ForceUpdate: Boolean read FForceUpdate write FForceUpdate;
    property Id: TId read GetId write SetId;
    property Name: string read GetName write SetName;
  end;

implementation

{ TEntityService }
uses
  EntityServiceManager, TypesFunctions, EntityContainer, BaseConnection;

constructor TEntityService.Create(AConnection: TObject; AEntityData: TEntity);
var
  TheDataType: TEntityClass;
begin
  inherited Create;
  FConnection := AConnection;
  FEntityContainersList := TSkyObjectList.Create(False, True);
  FEntityContainersList.Sorted := False;
  InitializeContainers;
  FMarkedForDeletion := False;
  FLoaded := False;
  FData := AEntityData;
  FDataBackup := nil;
  FLocked := not Assigned(FData);
  if not Assigned(FData) then
  begin
    TheDataType := TEntityServiceManager.GetEntityClass(TEntityServiceClass(Self.ClassType));
    FData := TheDataType.Create;
  end;
end;

{$IFDEF VER210}
class destructor TEntityService.Destroy;
{$ELSE}
procedure TEntityServiceDestroy;
{$ENDIF}
begin
  TEntityService.FEntityServiceManager.Free;
end;

function TEntityService.CreateACopy: TEntityService;
begin
  Result := TEntityServiceClass(ClassType).Create(FConnection, FData.ClassType.Create as TEntity);
  Result.FLoaded := FLoaded;
  Result.FLocked := FLocked;
  Result.FMarkedForDeletion := FMarkedForDeletion;
  Result.FData.CopyFrom(FData);
  Result.ApplyContainersCopyFrom(Self);
end;

constructor TEntityService.CreateFromDB(AConnection: TObject; AnId: TId);
begin
  FConnection := AConnection;
  ApplyLoadFromDB(AnId);
  Create(FConnection, FData);
  ApplyLoadContainersFromDB;
  FLoaded := True;
end;

procedure TEntityService.Delete;
begin
  FMarkedForDeletion := True;
  ApplyContainersDeleteAll;
end;

destructor TEntityService.Destroy;
begin
  DeleteBackup;
  FEntityContainersList.Free;
  TypesFunctions.FreeAndNil(FData);
  inherited;
end;

class function TEntityService.EntityServiceManager: TObject;
begin
  if not Assigned(FEntityServiceManager) then
    FEntityServiceManager := TEntityServiceManager.Create;
  Result := FEntityServiceManager;
end;

class procedure TEntityService.FreeEntities(SomeEntities: TEntityServices);
var
  I: Integer;
begin
  for I := 0 to High(SomeEntities) do
    SomeEntities[I].Free;
end;

class function TEntityService.GetDatasForEntities(SomeEntityServices: TEntityServices): TEntities;
var
  I: Integer;
begin
  SetLength(Result, Length(SomeEntityServices));
  for I := 0 to High(SomeEntityServices) do
    Result[I] := SomeEntityServices[I].Data;
end;

function TEntityService.GetEntityChanged: Boolean;
begin
  Result := not FData.IsEqualTo(FDataBackup);
end;

function TEntityService.GetExistsInDataBase: Boolean;
begin
  if Assigned(Self) and (Assigned(FData)) then
    Result := FData.ExistsInDatabase
  else
    Result := False;
end;

function TEntityService.GetId: TId;
begin
  Result := Data.Id;
end;

function TEntityService.GetName: string;
begin
  Result := Data.Name;
end;

class procedure TEntityService.RegisterClass(AEntityClass: TEntityClass);
begin
  TEntityServiceManager.RegisterEntityServiceClass(Self, AEntityClass);
end;

procedure TEntityService.InitializeContainers;
begin
  // implemented in descendant class
end;

procedure TEntityService.RegisterEntityContainer(
  AEntityContainer: TObject);
begin
  FEntityContainersList.Add(AEntityContainer);
end;

class function TEntityService.CreateFromEntityDatas(AConnection: TObject; SomeEntities: TEntities;
  LoadContainers: Boolean): TEntityServices;
var
  I: Integer;
  TheServiceClass: TEntityServiceClass;
begin
  SetLength(Result, Length(SomeEntities));
  for I := 0 to High(Result) do
  begin
    TheServiceClass := TEntityServiceManager.GetEntityServiceClass(TEntityClass(SomeEntities[I].ClassType));
    Result[I] := TheServiceClass.Create(AConnection, SomeEntities[I]);
    if LoadContainers then
      Result[I].ApplyLoadContainersFromDB;
  end;
end;

class function TEntityService.LoadAllFromDB(AConnection: TObject; LoadContainers: Boolean): TEntityServices;
var
  TheDataType: TEntityClass;
  TheEntities: TEntities;
begin
  TheDataType := TEntityServiceManager.GetEntityClass(Self);
  TheEntities := (AConnection as TBaseConnection).SelectAll(TheDataType);
  Result := CreateFromEntityDatas(AConnection, TheEntities, LoadContainers);
end;

procedure TEntityService.Lock;
begin
  if FLocked then
    Exit;
  CreateBackup;
  ApplyContainersLock;
  FLocked := True;
  ForceUpdate := False;
end;

procedure TEntityService.ApplyUpdateOrInsert;
begin
  if not FData.ExistsInDatabase then
  begin
    // if the data does not exist in the database then Insert it
    FData.Id := (Connection as TBaseConnection).InsertEntity(FData);
    FData.ExistsInDatabase := True;
  end
  else
    // if it does exist and it has changed then update it
    if ForceUpdate or EntityChanged then
      (Connection as TBaseConnection).UpdateEntity(FData);
end;

procedure TEntityService.ApplyContainersAfterCommit;
var
  I: Integer;
begin
  for I := 0 to FEntityContainersList.Count - 1 do
    (FEntityContainersList.Items[I] as TEntityContainer).AfterCommit;
end;

procedure TEntityService.ApplyContainersBeforeCommit;
var
  I: Integer;
begin
  for I := 0 to FEntityContainersList.Count - 1 do
    (FEntityContainersList.Items[I] as TEntityContainer).BeforeCommit;
end;

procedure TEntityService.ApplyContainersCleanUpBackup;
var
  I: Integer;
begin
  for I := 0 to FEntityContainersList.Count - 1 do
    (FEntityContainersList.Items[I] as TEntityContainer).CleanBackup;
end;

procedure TEntityService.ApplyContainersCopyFrom(ASource: TEntityService);
var
  I: Integer;
begin
  for I := 0 to FEntityContainersList.Count - 1 do
    (FEntityContainersList.Items[I] as TEntityContainer).CopyFrom(
      ASource.FEntityContainersList.Items[I] as TEntityContainer);
end;

procedure TEntityService.ApplyContainersDeleteAll;
var
  I: Integer;
begin
  for I := 0 to FEntityContainersList.Count - 1 do
    (FEntityContainersList.Items[I] as TEntityContainer).DeleteAll;
end;

procedure TEntityService.ApplyContainersLoadFromDB;
var
  I: Integer;
begin
  if FLoaded then
    Exit;
  for I := 0 to FEntityContainersList.Count - 1 do
    (FEntityContainersList.Items[I] as TEntityContainer).LoadFromDB;
  FLoaded := True;
end;

procedure TEntityService.ApplyContainersLock;
var
  I: Integer;
begin
  for I := 0 to FEntityContainersList.Count - 1 do
    (FEntityContainersList.Items[I] as TEntityContainer).Lock;
end;

procedure TEntityService.ApplyContainersRollBack;
var
  I: Integer;
begin
  for I := 0 to FEntityContainersList.Count - 1 do
    (FEntityContainersList.Items[I] as TEntityContainer).RollBack;
end;

procedure TEntityService.ApplyContainersSetExistsInDataBase(const Value: Boolean);
var
  I: Integer;
begin
  for I := 0 to FEntityContainersList.Count - 1 do
    (FEntityContainersList.Items[I] as TEntityContainer).SetExistsInDataBase(Value);
end;

procedure TEntityService.ApplyDelete;
begin
  if FData.ExistsInDatabase then
    (Connection as TBaseConnection).DeleteEntity(FData);
end;

procedure TEntityService.ApplyCommit(CleanUpBackup: Boolean);
begin
  Assert((not ExistsInDataBase) or FLocked, 'Commit done without locking the class');
  if FMarkedForDeletion then
  begin
    ValidateDelete;
    ApplyContainersBeforeCommit;
    ApplyDelete;
    ApplyContainersAfterCommit;
  end
  else
  begin
    Validate;
    ApplyContainersBeforeCommit;
    ApplyUpdateOrInsert;
    ApplyContainersAfterCommit;
  end;
  if CleanUpBackup then
    ApplyContainersCleanUpBackup;

  FLocked := False;
end;

procedure TEntityService.Commit;
begin
  Commit(True);
end;

procedure TEntityService.CommitUnchanged;
begin
  ForceUpdate := True;
  Commit;
end;

procedure TEntityService.RollBack;
begin
  ApplyContainersRollBack;
  RestoreBackup;
  FLocked := False;
end;

procedure TEntityService.SetExistsInDataBase(const Value: Boolean);
begin
  if (not Assigned(FData)) or (Value = FData.ExistsInDatabase) then
    Exit;
  if Value then
    Commit
  else
  begin
    FData.ExistsInDatabase := Value;
    ApplyContainersSetExistsInDataBase(Value);
  end;
end;

procedure TEntityService.SetId(const Value: TId);
begin
  Data.Id := Value;
end;

procedure TEntityService.SetName(const Value: string);
begin
  Data.Name := Value;
end;

procedure TEntityService.Validate;
begin
  // validation done in inherited class
end;

procedure TEntityService.ValidateDelete;
begin
  // validation done in inherited class
end;

procedure TEntityService.ApplyLoadContainersFromDB;
begin
  ApplyContainersLoadFromDB;
  FLoaded := True;
end;

procedure TEntityService.ApplyLoadFromDB(AnId: TId);
var
  TheDataType: TEntityClass;
begin
  TheDataType := TEntityServiceManager.GetEntityClass(TEntityServiceClass(Self.ClassType));
  FData := (Connection as TBaseConnection).SelectById(TheDataType, AnId);
  FData.ExistsInDatabase := True;
end;

procedure TEntityService.Commit(CleanUpBackup: Boolean);
begin
  ApplyCommit(CleanUpBackup);
end;

procedure TEntityService.CreateBackup;
begin
  DeleteBackup;
  FDataBackup := FData.CreateACopy;
end;

procedure TEntityService.RestoreBackup;
begin
  if Assigned(FDataBackup) then
    FData.CopyFrom(FDataBackup)
  else
    FreeAndNil(FData);
  DeleteBackup;
end;

procedure TEntityService.DeleteBackup;
begin
  FreeAndNil(FDataBackup);
end;

initialization
  TEntityService.RegisterClass(TEntity);

{$IFNDEF VER210}
finalization
  TEntityServiceDestroy;
{$ENDIF}

end.
