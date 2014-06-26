unit RepEntity;

interface

uses
  RepObjectBase, TypesConsts, SkyLists, Entity;

type
  TRepEntity = class(TRepObjectBase)
  public
    type
      TEntityType = (etPerson, etObject, etGroup);
  private
    FPageId: TId;
    FEntityNumber: Integer;
    FProperties: TSkyStringList;
    procedure SetEntityType(const Value: TEntityType);
  protected
    function GetEntityType: TRepEntity.TEntityType; virtual; abstract;
    function GetName: string; override;
  public
    constructor Create(ANumber: Integer); overload;
  published
    property Id;
    property EntityNumber: Integer read FEntityNumber write FEntityNumber;
    property EntityType: TEntityType read GetEntityType write SetEntityType;
    property Kids;
    property Name;
    property PageId: TId read FPageId write FPageId;
    property Properties: TSkyStringList read FProperties write FProperties; // array of TRepProperty
  end;

implementation

uses
  SysUtils;

{ TRepEntity }

constructor TRepEntity.Create(ANumber: Integer);
begin
  Create;
  FEntityNumber := ANumber;
end;

function TRepEntity.GetName: string;
begin
  case EntityType of
    etPerson:
      Result := 'p' + IntToStr(FEntityNumber);
    etObject:
      Result := 'o' + IntToStr(FEntityNumber);
  else // etGroup
    Result := 'g' + IntToStr(FEntityNumber);
  end;
end;

procedure TRepEntity.SetEntityType(const Value: TEntityType);
begin
  // do nothing
end;

initialization
  TRepEntity.RegisterEntityClass;

end.
