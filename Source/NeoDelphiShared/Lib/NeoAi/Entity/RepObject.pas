unit RepObject;

interface

uses
  RepEntity;

type
  TRepObject = class(TRepEntity)
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

{ TRepObject }

function TRepObject.GetEntityType: TRepEntity.TEntityType;
begin
  Result := etObject;
end;

initialization
  TRepObject.RegisterEntityClass;

end.
