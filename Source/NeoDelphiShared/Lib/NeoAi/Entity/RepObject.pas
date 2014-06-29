unit RepObject;

interface

uses
  RepEntity, EntityList;

type
  TRepObject = class(TRepEntity)
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

{ TRepObject }

constructor TRepObject.Create;
begin
  inherited Create;
  FMembers.OwnsItems := False;
end;

function TRepObject.GetEntityType: TRepEntity.TEntityType;
begin
  Result := etObject;
end;

initialization
  TRepObject.RegisterEntityClass;

end.
