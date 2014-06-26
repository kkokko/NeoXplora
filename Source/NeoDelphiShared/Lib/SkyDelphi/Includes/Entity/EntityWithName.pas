unit EntityWithName;

interface

uses
  VersionableEntity, TypesConsts;

type
  TEntityWithName = class(TVersionableEntity)
  private
    FName: string;
  protected
    function GetName: string; override;
    procedure SetName(const AName: string); override;
  public
  public
    class var
      EntityToken_Name: TEntityFieldNamesToken;
    constructor Create(AnId: TId; const AName: string); overload;
  published
    property Id;
    property Name;
    property Version;
  end;

implementation

{ TEntityWithName }

constructor TEntityWithName.Create(AnId: TId; const AName: string);
begin
  Create;
  Id := AnId;
  Name := AName;
end;

function TEntityWithName.GetName: string;
begin
  Result := FName;
end;

procedure TEntityWithName.SetName(const AName: string);
begin
  FName := AName;
end;

initialization
  TEntityWithName.RegisterEntityClass;
  TEntityWithName.RegisterToken(TEntityWithName.EntityToken_Name, 'Name');

end.
