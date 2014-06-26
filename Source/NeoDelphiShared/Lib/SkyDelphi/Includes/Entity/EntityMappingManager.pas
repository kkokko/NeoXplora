unit EntityMappingManager;

interface

uses
  Entity, EntityMapping, SkyLists;

type
  TEntityMappingManager = class(TObject)
  private
    FMappings: TSkyStringList;
    class function GetInstance: TEntityMappingManager;
  public
    constructor Create;
    destructor Destroy; override;
    class procedure RegisterMapping(AnEntityClass: TEntityClass; AMapping: TEntityMapping);
    class function GetMapping(AnEntityClass: TClass): TEntityMapping; overload;
    class function GetMapping(const AnEntityClassName: string): TEntityMapping; overload;
    class function GetClassTypeForTableName(const ATableName: string): TEntityClass;
  end;

implementation

uses
  SysUtils;

{ TEntityMappingManager }


constructor TEntityMappingManager.Create;
begin
  inherited;
  FMappings := TSkyStringList.Create(True);
end;

destructor TEntityMappingManager.Destroy;
begin
  FMappings.Free;
  inherited;
end;

class function TEntityMappingManager.GetMapping(AnEntityClass: TClass): TEntityMapping;
var
  TheClass: TClass;
  TheInstance: TEntityMappingManager;
begin
  TheInstance := GetInstance;
  TheClass := AnEntityClass;
  Result := nil;
  while (TheClass <> nil) and (TheClass <> TEntity) and (Result = nil) do
  begin
    Result := TheInstance.FMappings.ObjectOfValueDefault[TheClass.ClassName, nil] as TEntityMapping;
    TheClass := TheClass.ClassParent;
  end;
end;

class function TEntityMappingManager.GetInstance: TEntityMappingManager;
begin
  Result := TEntity.EntityMappingManager as TEntityMappingManager;
end;

class function TEntityMappingManager.GetMapping(const AnEntityClassName: string): TEntityMapping;
begin
  Result := GetInstance.FMappings.ObjectOfValueDefault[AnEntityClassName, nil] as TEntityMapping;
end;

class function TEntityMappingManager.GetClassTypeForTableName(const ATableName: string): TEntityClass;
var
  TheClasses: TSkyStringList;
  TheResult: TEntityMapping;
  I: Integer;
begin
  TheClasses := GetInstance.FMappings;
  for I := 0 to TheClasses.Count - 1 do
  begin
    TheResult := (TheClasses.Objects[I] as TEntityMapping);
    if SameText(TheResult.TableName, ATableName) then
    begin
      Result := TheResult.EntityClassType;
      Exit;
    end;
  end;
  Result := nil;
end;

class procedure TEntityMappingManager.RegisterMapping(AnEntityClass: TEntityClass; AMapping: TEntityMapping);
begin
  AMapping.EntityClassType := AnEntityClass;
  GetInstance.FMappings.Add(AnEntityClass.ClassName, AMapping);
end;

end.
