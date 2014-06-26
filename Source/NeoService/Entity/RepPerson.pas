unit RepPerson;

interface

uses
  RepEntity, EntityList;

type
  TRepPerson = class(TRepEntity)
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

{ TRepPerson }

constructor TRepPerson.Create;
begin
  inherited Create;
  FMembers.OwnsItems := False;
end;

function TRepPerson.GetEntityType: TRepEntity.TEntityType;
begin
  Result := etPerson;
end;

initialization
  TRepPerson.RegisterEntityClass;

end.
