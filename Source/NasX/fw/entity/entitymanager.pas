unit EntityManager;

{$mode objfpc}{$H+}

interface

uses
  TypesConsts, Entity, SkyLists, StringArray;

type
  TFieldInfoListClass = class
  public
    FieldInfos: {$IFDEF VER210}TFieldInfo.TArray{$ELSE}TFieldInfoArray{$ENDIF};
    function FindFieldByName(const AFieldName: string; out AFieldInfo: TFieldInfo): Boolean;
    function GetFieldInfosIgnoreId: {$IFDEF VER210}TFieldInfo.TArray{$ELSE}TFieldInfoArray{$ENDIF};
  end;

  TEntityManager = class(TObject)
  private
    class function GetInstance: TEntityManager; inline;
    class function RegisterEntity(AnEntity: TEntity): TFieldInfoListClass;
  protected
    FEntityClassNames: TSkyClassTypeList;
    FEntityClassInfos: TSkyObjectList;
  public
    constructor Create;
    destructor Destroy; override;

    // FieldInfo related methods
    class function GetEntityFieldInfo(AnEntityClass: TClass; const AFieldName: string): TFieldInfo; overload;
    class function GetEntityFieldInfo(AnEntityClassName: string; const AFieldName: string): TFieldInfo; overload;
    class function GetEntityFieldInfos(AnEntityClass: TClass; IgnoreId: Boolean = False): {$IFDEF VER210}TFieldInfo.TArray{$ELSE}TFieldInfoArray{$ENDIF}; overload;
    class function GetEntityFieldInfos(AnEntity: TEntity; IgnoreId: Boolean = False): {$IFDEF VER210}TFieldInfo.TArray{$ELSE}TFieldInfoArray{$ENDIF}; overload;
    class function GetEntityFieldNames(AnEntityClass: TClass; IgnoreId: Boolean = False): TStringArray;
    class function GetFieldInfoClassForEntityClass(AnEntityClass: TClass): TFieldInfoListClass;

    // class related methods
    class function GetEntityClassForName(const AnEntityClassName: string): TEntityClass;
    class function GetAllEntityClasses: TEntityClasses;
    class procedure RegisterEntityClass(AnEntityClass: TClass);
    class procedure RegisterEntityClasses(SomeEntityClasses: array of TClass);
  end;

implementation

uses
  SysUtils;

{ TFieldInfoListClass }

function TFieldInfoListClass.FindFieldByName(const AFieldName: string;
  out AFieldInfo: TFieldInfo): Boolean;
var
  I: Integer;
begin
  Result := True;
  for I := 0 to High(FieldInfos) do
    if AnsiSameText(AFieldName, FieldInfos[I].FieldName) then
    begin
      AFieldInfo := FieldInfos[I];
      Exit;
    end;
  Result := False;
end;

function TFieldInfoListClass.GetFieldInfosIgnoreId: {$IFDEF VER210}TFieldInfo.TArray{$ELSE}TFieldInfoArray{$ENDIF};
var
  TheCount: Integer;
  I: Integer;
begin
  SetLength(Result, Length(FieldInfos));
  TheCount := 0;
  for I := 0 to High(FieldInfos) do
    if FieldInfos[I].FieldName <> 'Id' then
    begin
      Result[TheCount] := FieldInfos[I];
      Inc(TheCount);
    end;
  SetLength(Result, TheCount);
end;

{ TEntityManager }

constructor TEntityManager.Create;
begin
  inherited;
  FEntityClassInfos := TSkyObjectList.Create(True); // owns objects
  FEntityClassNames := TSkyClassTypeList.Create;
end;

destructor TEntityManager.Destroy;
begin
  FEntityClassNames.Free;
  FEntityClassInfos.Free;
  inherited;
end;

class function TEntityManager.RegisterEntity(AnEntity: TEntity): TFieldInfoListClass;
var
  TheFieldInfos: {$IFDEF VER210}TFieldInfo.TArray{$ELSE}TFieldInfoArray{$ENDIF};
begin
  TheFieldInfos := AnEntity.EvaluateFieldInfos;
  Result := TFieldInfoListClass.Create;
  Result.FieldInfos := TheFieldInfos;
  GetInstance.FEntityClassInfos.Add(TObject(AnEntity.ClassType), Result);
end;

class function TEntityManager.GetFieldInfoClassForEntityClass(AnEntityClass: TClass): TFieldInfoListClass;
var
  TheEntity: TEntity;
begin
  if (not Assigned(AnEntityClass)) or TEntityClass(AnEntityClass).AutoManagedFields then
    raise Exception.Create('TEntityManager.GetFieldInfoClassForEntityClass');
  Result := GetInstance.FEntityClassInfos.ObjectOfValueDefault[TObject(AnEntityClass), nil] as TFieldInfoListClass;
  if Assigned(Result) then
    Exit;
  TheEntity := TEntityClass(AnEntityClass).Create;
  try
    // creating a class is usually enough to get the field infos registered
    Result := GetInstance.FEntityClassInfos.ObjectOfValueDefault[TObject(AnEntityClass), nil] as TFieldInfoListClass;
    if Assigned(Result) then
      Exit;
    Result := RegisterEntity(TheEntity);
  finally
    TheEntity.Free;
  end;
end;

class function TEntityManager.GetEntityFieldInfo(AnEntityClass: TClass; const AFieldName: string): TFieldInfo;
begin
  if (not Assigned(AnEntityClass)) or TEntityClass(AnEntityClass).AutoManagedFields then
    raise Exception.Create('TEntityManager.GetEntityFieldInfo');
  if not GetFieldInfoClassForEntityClass(AnEntityClass).FindFieldByName(AFieldName, Result) then
    raise Exception.Create('TEntityManager.GetEntityFieldInfo');
end;

class function TEntityManager.GetAllEntityClasses: TEntityClasses;
begin
  Result := TEntityClasses(GetInstance.FEntityClassNames.GetAllItems);
end;

class function TEntityManager.GetEntityClassForName(const AnEntityClassName: string): TEntityClass;
begin
  Result := TEntityClass(GetInstance.FEntityClassNames.FindByName(AnEntityClassName));
end;

class function TEntityManager.GetEntityFieldInfo(AnEntityClassName: string;
  const AFieldName: string): TFieldInfo;
begin
  Result := GetEntityFieldInfo(GetEntityClassForName(AnEntityClassName), AFieldName);
end;

class function TEntityManager.GetEntityFieldInfos(AnEntity: TEntity;
  IgnoreId: Boolean): {$IFDEF VER210}TFieldInfo.TArray{$ELSE}TFieldInfoArray{$ENDIF};
var
  TheFieldInfoClass: TFieldInfoListClass;
begin
  if AnEntity.AutoManagedFields then
    raise Exception.Create('TEntityManager.GetEntityFieldInfos');
  TheFieldInfoClass := GetInstance.FEntityClassInfos.ObjectOfValueDefault[
    TObject(AnEntity.ClassType), nil] as TFieldInfoListClass;
  if not Assigned(TheFieldInfoClass) then
    TheFieldInfoClass := RegisterEntity(AnEntity);
  if IgnoreId then
    Result := TheFieldInfoClass.GetFieldInfosIgnoreId
  else
    Result := TheFieldInfoClass.FieldInfos;
end;

class function TEntityManager.GetEntityFieldInfos(AnEntityClass: TClass;
  IgnoreId: Boolean): {$IFDEF VER210}TFieldInfo.TArray{$ELSE}TFieldInfoArray{$ENDIF};
var
  TheFieldInfoClass: TFieldInfoListClass;
begin
  if (not Assigned(AnEntityClass)) or TEntityClass(AnEntityClass).AutoManagedFields then
    raise Exception.Create('TEntityManager.GetEntityFieldInfos');
  TheFieldInfoClass := GetFieldInfoClassForEntityClass(AnEntityClass);
  if IgnoreId then
    Result := TheFieldInfoClass.GetFieldInfosIgnoreId
  else
    Result := TheFieldInfoClass.FieldInfos;
end;

class function TEntityManager.GetEntityFieldNames(AnEntityClass: TClass;
  IgnoreId: Boolean): TStringArray;
var
  TheFieldInfos: {$IFDEF VER210}TFieldInfo.TArray{$ELSE}TFieldInfoArray{$ENDIF};
  I: Integer;
begin
  Result := nil;
  if (not Assigned(AnEntityClass)) or TEntityClass(AnEntityClass).AutoManagedFields then
    raise Exception.Create('TEntityManager.GetEntityFieldNames');
  TheFieldInfos := GetEntityFieldInfos(AnEntityClass, IgnoreId);
  for I := 0 to High(TheFieldInfos) do
  begin
    SetLength(Result, Length(Result) + 1);
    Result[Length(Result) - 1] := TheFieldInfos[I].FieldName;
  end;
end;

class function TEntityManager.GetInstance: TEntityManager;
begin
  Result := TEntity.EntityManager as TEntityManager;
end;

class procedure TEntityManager.RegisterEntityClass(AnEntityClass: TClass);
begin
  GetInstance.FEntityClassNames.Add(AnEntityClass, AnEntityClass.ClassName);
end;

class procedure TEntityManager.RegisterEntityClasses(SomeEntityClasses: array of TClass);
var
  I: Integer;
begin
  for I := 0 to High(SomeEntityClasses) do
    RegisterEntityClass(SomeEntityClasses[I]);
end;

end.
