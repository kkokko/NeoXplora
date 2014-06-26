unit EntityOneToOneContainer;

interface

uses
  EntityContainer, EntityService, Entity, TypesConsts;

type
  TEntityOneToOneContainer = class(TEntityContainer)
  private
    FParentService: TEntityService;
    FOwnEntityClass: TEntityClass;
    FOwnEntityServiceClass: TEntityServiceClass;
    FParentField, FOwnField: string;
    FLoaded: Boolean;
    function GetLink: TEntityService;
  protected
    FEntityServiceData: TEntityService;
    FEntityServiceBackupData: TEntityService;
    FLocked: Boolean;
  public
    constructor Create(AParent: TEntityService; ADataType: TEntityServiceClass;
      AParentFieldNameToken: TEntityFieldNamesToken;
      AJoinFieldNameToken: TEntityFieldNamesToken); reintroduce;

    procedure SetExistsInDataBase(const Value: Boolean); override;
    procedure Add(const AEntityService: TEntityService); override;
    procedure AfterCommit; override;
    procedure BeforeCommit; override;
    procedure CleanBackup; override;
    procedure DeleteAll; override;
    procedure LoadFromDB; override;
    procedure Lock; override;
    procedure RollBack; override;
    procedure CreateBackup;
    procedure RestoreBackup;
    procedure DeleteBackup;
    procedure CopyFrom(AContainer: TEntityContainer); override;
    property Link: TEntityService read GetLink write Add;
  end;

implementation

uses
  EntityTokens, TypesFunctions, EntityServiceManager, Variants;

{ TEntityOneToOneContainer }

procedure TEntityOneToOneContainer.Add(const AEntityService: TEntityService);
begin
  if FEntityServiceData = AEntityService then
    Exit;
  if FEntityServiceData <> FEntityServiceBackupData then
    FreeAndNil(FEntityServiceData);
  FEntityServiceData := AEntityService;
end;

procedure TEntityOneToOneContainer.AfterCommit;
begin
  // intentionally left blank
end;

procedure TEntityOneToOneContainer.SetExistsInDataBase(
  const Value: Boolean);
begin
  // intentionally left blank
end;

procedure TEntityOneToOneContainer.BeforeCommit;
var
  TheValue: Variant;
begin
  // if the Entity service does not exist in the database then insert it
  if Assigned(FEntityServiceData) and (not FEntityServiceData.ExistsInDataBase) then
    FEntityServiceData.Commit;
  if not Assigned(FEntityServiceData) then
    TheValue := 0
  else
    TheValue := FEntityServiceData.Data.GetValueForField(FOwnField);
  // set the new Id to the parent EntityService.LinkField
  FParentService.Data.SetValueForField(FParentField, TheValue);
end;

constructor TEntityOneToOneContainer.Create(AParent: TEntityService;
  ADataType: TEntityServiceClass; AParentFieldNameToken,
  AJoinFieldNameToken: TEntityFieldNamesToken);
begin
  FParentService := AParent;
  FParentField := TEntityTokens.GetFieldNameForToken(AParentFieldNameToken);
  FOwnField := TEntityTokens.GetFieldNameForToken(AJoinFieldNameToken);
  FOwnEntityClass := TEntityServiceManager.GetEntityClass(ADataType);
  FOwnEntityServiceClass := ADataType;
  FEntityServiceBackupData := TEntityService.Create(AParent.Connection);
  FEntityServiceData := nil;
  FLoaded := False;
end;

procedure TEntityOneToOneContainer.DeleteAll;
begin
  Link := nil;
end;

function TEntityOneToOneContainer.GetLink: TEntityService;
begin
  Result := FEntityServiceData;
end;

procedure TEntityOneToOneContainer.Lock;
begin
  CreateBackup;
  FLocked := True;
end;

procedure TEntityOneToOneContainer.RollBack;
begin
  RestoreBackup;
  FLocked := False;
end;

procedure TEntityOneToOneContainer.LoadFromDB;
var
  TheId: TId;
begin
  Assert(not FLoaded, 'Load from DB should be called only once per instance');
  TheId := FParentService.Data.GetValueForField(FParentField);
  if TheId = 0 then
    Exit;
  FEntityServiceData := FOwnEntityServiceClass.CreateFromDB(FParentService.Connection, TheId);
  FLoaded := True;
end;


procedure TEntityOneToOneContainer.CleanBackup;
begin
  // intentionally left blank
end;

procedure TEntityOneToOneContainer.CopyFrom(AContainer: TEntityContainer);
begin
  Link := (AContainer as TEntityOneToOneContainer).Link.CreateACopy;
end;

procedure TEntityOneToOneContainer.CreateBackup;
begin
  DeleteBackup;
  FEntityServiceBackupData := FEntityServiceData;
end;

procedure TEntityOneToOneContainer.RestoreBackup;
begin
  FEntityServiceData := FEntityServiceBackupData;
  DeleteBackup;
end;

procedure TEntityOneToOneContainer.DeleteBackup;
begin
  TypesFunctions.FreeAndNil(FEntityServiceBackupData);
end;

end.
