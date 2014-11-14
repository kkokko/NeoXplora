unit Separator;

interface

uses
  Entity, EntityFieldNamesToken, TypesConsts;

type
  TSeparator = class(TEntity)
  private
    FName: string;
  protected
    function GetName: string; override;
    procedure SetName(const AName: string); override;
  public
    class var
      Tok_Id: TEntityFieldNamesToken;
      Tok_Name: TEntityFieldNamesToken;
    class function EntityToken_Id: TEntityFieldNamesToken; override;
    class function EntityToken_Name: TEntityFieldNamesToken; override;
 published
    property Id;
    property Name;
  end;

implementation

uses
  EntityMappingManager, EntityMapping, AppConsts, EntityTokens;

{ TSeparator }

class function TSeparator.EntityToken_Id: TEntityFieldNamesToken;
begin
  Result := Tok_Id;
end;

class function TSeparator.EntityToken_Name: TEntityFieldNamesToken;
begin
  Result := Tok_Name;
end;

function TSeparator.GetName: string;
begin
  Result := FName;
end;

procedure TSeparator.SetName(const AName: string);
begin
  FName := AName;
end;

initialization
  TSeparator.RegisterEntityClassWithMappingToTable(ConstNeoPrefix + 'separator');
  TSeparator.RegisterToken(TSeparator.Tok_Id, 'Id');
  TSeparator.RegisterToken(TSeparator.Tok_Name, 'Value');
  TEntityMappingManager.GetMapping(TSeparator).SetValueForField('Name', TSeparator.Tok_Name.PropertyName);

end.
