unit EntityServiceManager;

interface

uses
  SkyLists, EntityService, Entity;

type
  TEntityServiceManager = class(TObject)
  private
    FEntityServiceList: TSkyObjectList;
    class function GetInstance: TEntityServiceManager; inline;
  public
    constructor Create;
    destructor Destroy; override;
    class procedure RegisterEntityServiceClass(AnEntityService: TEntityServiceClass; AnEntityClass: TEntityClass);
    class function GetEntityServiceClass(AnEntityClass: TEntityClass): TEntityServiceClass;
    class function GetEntityClass(AnEntityServiceClass: TEntityServiceClass): TEntityClass;
  end;

implementation

uses
  TypesFunctions;

{ TEntityServiceManager }

constructor TEntityServiceManager.Create;
begin
  inherited Create;
  FEntityServiceList := TSkyObjectList.Create;
end;

destructor TEntityServiceManager.Destroy;
begin
  TypesFunctions.FreeAndNil(FEntityServiceList);
  inherited;
end;

class function TEntityServiceManager.GetEntityClass(AnEntityServiceClass: TEntityServiceClass): TEntityClass;
var
  TheIndex: Integer;
  TheInstance: TEntityServiceManager;
begin
  TheInstance := GetInstance;
  TheIndex := TheInstance.FEntityServiceList.IndexOf(Pointer(AnEntityServiceClass));
  if TheIndex = -1 then
    TheIndex := TheInstance.FEntityServiceList.IndexOf(Pointer(TEntityService));
  Result := Pointer(TheInstance.FEntityServiceList.Objects[TheIndex]);
end;

class function TEntityServiceManager.GetEntityServiceClass(AnEntityClass: TEntityClass): TEntityServiceClass;
var
  TheIndex: Integer;
  TheInstance: TEntityServiceManager;
begin
  TheInstance := GetInstance;
  TheIndex := TheInstance.FEntityServiceList.IndexOfObject(Pointer(AnEntityClass));
  if TheIndex = -1 then
    TheIndex := TheInstance.FEntityServiceList.IndexOfObject(Pointer(TEntity));
  Result := Pointer(TheInstance.FEntityServiceList.Items[TheIndex]);
end;

class function TEntityServiceManager.GetInstance: TEntityServiceManager;
begin
  Result := TEntityService.EntityServiceManager as TEntityServiceManager;
end;

class procedure TEntityServiceManager.RegisterEntityServiceClass(AnEntityService: TEntityServiceClass; AnEntityClass: TEntityClass);
begin
  GetInstance.FEntityServiceList.AddObject(Pointer(AnEntityService), Pointer(AnEntityClass));
end;

end.
