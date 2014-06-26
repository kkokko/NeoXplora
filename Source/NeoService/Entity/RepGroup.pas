unit RepGroup;

interface

uses
  RepEntity, EntityList;

type
  TRepGroup = class(TRepEntity)
  private
    FMembers: TEntityList;
  protected
    function GetEntityType: TRepEntity.TEntityType; override;
  public
    constructor Create; override;
  published
    property Id;
    property EntityNumber;
    property EntityType;
    property Members: TEntityList read FMembers write FMembers;
    property Name;
    property PageId;
    property Properties;
  end;

implementation

{ TRepGroup }

constructor TRepGroup.Create;
begin
  inherited Create;
  FMembers.OwnsItems := False;
end;

function TRepGroup.GetEntityType: TRepEntity.TEntityType;
begin
  Result := etGroup;
end;

initialization
  TRepGroup.RegisterEntityClass;

end.
