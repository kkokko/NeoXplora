unit RepGroup;

interface

uses
  RepEntity, EntityList;

type
  TRepGroup = class(TRepEntity)
  private
    FMembers: TEntityList;
  published
    function GetEntityType: TRepEntity.TEntityType; override;
  published
    property Id;
    property EntityNumber;
    property EntityType;
    property Kids;
    property Members: TEntityList read FMembers write FMembers; // array of TRepEntity
    property Name;
    property PageId;
  end;

implementation

{ TRepGroup }

function TRepGroup.GetEntityType: TRepEntity.TEntityType;
begin
  Result := etGroup;
end;

initialization
  TRepGroup.RegisterEntityClass;

end.
