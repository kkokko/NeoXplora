unit RepRecord;

interface

uses
  Entity, SkyLists, RepEntity;

type
  TRepRecord = class(TEntity)
  private
    FRepEntities: TSkyStringList;
  public
    constructor Create; override;
    function GetOrCreateEntity(AKind: Char; ANumber: Integer): TRepEntity;
  published
    property RepEntities: TSkyStringList read FRepEntities write FRepEntities; // array of TRepEntity
  end;

implementation

uses
  SysUtils, RepPerson, RepGroup, RepObject;

{ TRepRecord }

constructor TRepRecord.Create;
begin
  inherited;
  FRepEntities.Sorted := True;
end;

function TRepRecord.GetOrCreateEntity(AKind: Char; ANumber: Integer): TRepEntity;
var
  TheName: string;
begin
  TheName := AKind + IntToStr(ANumber);
  Result := FRepEntities.ObjectOfValueDefault[TheName, nil] as TRepEntity;
  if Result <> nil then
    Exit;
  case AKind of
    'p':
      Result := TRepPerson.Create(ANumber);
    'o':
      Result := TRepObject.Create(ANumber);
  else
    Result := TRepGroup.Create(ANumber);
  end;
  FRepEntities.AddObject(TheName, Result);
end;

initialization
  TRepRecord.RegisterEntityClass;

end.
