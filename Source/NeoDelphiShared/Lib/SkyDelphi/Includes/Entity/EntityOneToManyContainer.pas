unit EntityOneToManyContainer;

interface

uses
  EntityManyContainer, EntityService, Entity, TypesConsts, SkyLists;

type
  TEntityOneToManyContainer = class(TEntityManyContainer)
  private
    FParentService: TEntityService;
    FParentField, FOwnField: string;
  protected
    procedure ApplyServicesCommitNewOrChanged; override;
    procedure CopyData(AService: TEntityService; AData: TEntity); override;
  public
    constructor Create(AParent: TEntityService; ADataType: TEntityServiceClass;
      AParentFieldNameToken: TEntityFieldNamesToken;
      AJoinFieldNameToken: TEntityFieldNamesToken); reintroduce;
    procedure LoadFromDB; override;
  end;

implementation

uses
  EntityTokens, EntityServiceManager, BaseQuery, BaseConnection;

{ TEntityOneToManyContainer }

constructor TEntityOneToManyContainer.Create(AParent: TEntityService; ADataType: TEntityServiceClass;
  AParentFieldNameToken, AJoinFieldNameToken: TEntityFieldNamesToken);
begin
  inherited Create(AParent.Connection, ADataType);
  FParentService := AParent;
  FParentField := TEntityTokens.GetFieldNameForToken(AParentFieldNameToken);
  FOwnField := TEntityTokens.GetFieldNameForToken(AJoinFieldNameToken);
end;

procedure TEntityOneToManyContainer.LoadFromDB;
var
  TheEntities: TEntities;
  TheEntityService: TEntityService;
  TheId: TId;
  I: Integer;
begin
  Assert(not FLoaded, 'Load from DB should be called only once per instance');
  //load container entities
  TheId := FParentService.Data.GetValueForField(FParentField);
  TheEntities := TBaseQuery.LoadOneToMany(FParentService.Connection as TBaseConnection, FOwnEntityDataClass, FOwnField,
    TheId);
  for I := 0 to High(TheEntities) do
  begin
    TheEntityService := FOwnEntityServiceClass.Create(FParentService.Connection, TheEntities[I]);
    TheEntityService.Data.ExistsInDatabase := True;
    Add(TheEntityService);
  end;
  FLoaded := True;
end;

procedure TEntityOneToManyContainer.ApplyServicesCommitNewOrChanged;
var
  I: Integer;
  TheService: TEntityService;
  TheValue: Variant;
begin
  for I := 0 to FEntityServiceList.Count - 1 do
  begin
    TheService := FEntityServiceList[I] as TEntityService;
    if not TheService.ExistsInDataBase then
    begin
      TheService.Lock;
      TheValue := FParentService.Data.GetValueForField(FParentField);
      TheService.Data.SetValueForField(FOwnField, TheValue);
    end;
    if TheService.Locked then
      TheService.Commit(False);
  end;
end;

procedure TEntityOneToManyContainer.CopyData(AService: TEntityService; AData: TEntity);
begin
  inherited;
  AService.Data.SetValueForField(FOwnField, FParentService.Id);
end;

end.
