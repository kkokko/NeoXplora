unit EntityTokens;

interface

uses
  SkyLists, TypesConsts, Entity, StringArray;

type
  TEntityTokens = class(TObject)
  private
    FFieldNamesList: TSkyStringList;
    FFieldDefs: TSkyStringStringList;
    FCurrentId: Integer;
    class function GetInstance: TEntityTokens; inline;
    class function GetFieldNameForEntityTokenString(ATokenString: string): string;
    class function GetClassNameForEntityTokenString(ATokenString: string): string;
  public
    constructor Create;
    destructor Destroy; override;
    class function RegisterFieldNames(AnEntityClass: TEntityClass;
      const AnEntityFieldName, AFieldDef: string): TEntityFieldNamesToken;
    class function GetFieldDef(const AClassName, AFieldName: string;
      out AFieldDef: string): Boolean;
    class function GetFieldNameForToken(AToken: TEntityFieldNamesToken): string;
    class procedure GetTokensInfo(SomeTokens: {$IFDEF VER210}TEntityFieldNamesToken.TArray{$ELSE}TEntityFieldNamesTokenArray{$ENDIF};
      out SomeEntityClasseNames: TStringArray; out SomeFieldNames: TStringArray);
    class procedure Validate;
  end;

implementation

uses
  EntityManager, ExceptionClasses;

{ TEntityTokens }

constructor TEntityTokens.Create;
begin
  inherited Create;
  FCurrentId := 0;
  FFieldNamesList := TSkyStringList.Create;
  FFieldDefs := TSkyStringStringList.Create;
end;

destructor TEntityTokens.Destroy;
begin
  FFieldNamesList.Free;
  FFieldDefs.Free;
  inherited;
end;

class function TEntityTokens.RegisterFieldNames(AnEntityClass: TEntityClass;
  const AnEntityFieldName, AFieldDef: string): TEntityFieldNamesToken;
var
  TheNewValue: string;
  TheIndex: Integer;
begin
  TheNewValue := AnEntityClass.ClassName + '.' + AnEntityFieldName;
  if not GetInstance.FFieldNamesList.Find(TheNewValue, TheIndex) then
  begin
    Inc(GetInstance.FCurrentId);
    TheIndex := GetInstance.FFieldNamesList.AddObject(TheNewValue, Pointer(GetInstance.FCurrentId));
  end;
  Result.TokenId := Integer(GetInstance.FFieldNamesList.Objects[TheIndex]);
  Result.TokenString := GetInstance.FFieldNamesList.Items[TheIndex];
  if AFieldDef = '' then
    GetInstance.FFieldDefs.Delete(Result.TokenString)
  else
    GetInstance.FFieldDefs.Add(Result.TokenString, AFieldDef);
end;

class procedure TEntityTokens.Validate;
var
  I: Integer;
  TheClassName, TheFieldName: string;
begin
  for I := 0 to GetInstance.FFieldNamesList.Count - 1 do
  begin
    TheClassName := GetClassNameForEntityTokenString(GetInstance.FFieldNamesList.Items[I]);
    TheFieldName := GetFieldNameForEntityTokenString(GetInstance.FFieldNamesList.Items[I]);
    TEntityManager.GetEntityFieldInfo(TheClassName, TheFieldName)
  end;
end;

class procedure TEntityTokens.GetTokensInfo(SomeTokens: {$IFDEF VER210}TEntityFieldNamesToken.TArray{$ELSE}TEntityFieldNamesTokenArray{$ENDIF};
  out SomeEntityClasseNames, SomeFieldNames: TStringArray);
var
  I, TheIndex: Integer;
begin
  for I := 0 to High(SomeTokens) do
  begin
    if GetInstance.FFieldNamesList.Find(SomeTokens[I].TokenString, TheIndex) then
    begin
      SomeEntityClasseNames.Add((GetInstance.FFieldNamesList.Items[TheIndex]));
      SomeFieldNames.Add(GetFieldNameForEntityTokenString(GetInstance.FFieldNamesList.Items[TheIndex]));
    end
    else
      raise ESkyEntityFieldNameTokenNotFound.Create(GetInstance, 'GetTokensInfo', SomeTokens[I].TokenString);
  end;
end;

class function TEntityTokens.GetClassNameForEntityTokenString(ATokenString: string): string;
begin
  Result := Copy(ATokenString, 0, Pos('.', ATokenString) - 1);
end;

class function TEntityTokens.GetFieldDef(const AClassName, AFieldName: string;
  out AFieldDef: string): Boolean;
var
  TheIndex: Integer;
begin
  Result := GetInstance.FFieldDefs.Find(AClassName + '.' + AFieldName, TheIndex);
  if Result then
    AFieldDef := GetInstance.FFieldDefs.Objects[TheIndex];
end;

class function TEntityTokens.GetFieldNameForEntityTokenString(ATokenString: string): string;
var
  TheIndex: Integer;
begin
  TheIndex := Pos('.', ATokenString);
  Result := Copy(ATokenString, TheIndex + 1, Length(ATokenString) - TheIndex);
end;

class function TEntityTokens.GetFieldNameForToken(AToken: TEntityFieldNamesToken) : string;
var
  TheIndex: Integer;
begin
  if GetInstance.FFieldNamesList.Find(AToken.TokenString, TheIndex) then
    Result := GetFieldNameForEntityTokenString(GetInstance.FFieldNamesList.Items[TheIndex])
  else
    raise ESkyEntityFieldNameTokenNotFound.Create(GetInstance, 'GetFieldNameForToken', AToken.TokenString);
end;

class function TEntityTokens.GetInstance: TEntityTokens;
begin
  Result := TEntity.EntityTokens as TEntityTokens;
end;

end.
