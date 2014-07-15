unit RepPerson;

interface

uses
  RepEntity;

type
  TRepPerson = class(TRepEntity)
  protected
    function GetEntityType: TRepEntity.TEntityType; override;
  published
    property Id;
    property EntityNumber;
    property EntityType;
    property Kids;
    property Name;
    property PageId;
  end;

implementation

{ TRepPerson }

function TRepPerson.GetEntityType: TRepEntity.TEntityType;
begin
  Result := etPerson;
end;

initialization
  TRepPerson.RegisterEntityClass;

end.
