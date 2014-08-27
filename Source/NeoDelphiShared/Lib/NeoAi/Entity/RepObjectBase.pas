unit RepObjectBase;

interface

uses
  Entity, SkyLists;

type
  TRepObjectBase = class(TEntity)
  public
    type
      TKeyPropertyType = (ptAttribute, ptEvent);
  private
    FKids: TSkyStringList;
  public
    constructor Create; override;

    function GetOrCreateKid(const AName: string; APropertyType: TKeyPropertyType): TEntity;
  published
    property Kids: TSkyStringList read FKids write FKids; // array of TRepObjectBase
  end;

implementation

uses
  RepPropertyKey;

{ TRepObjectBase }

constructor TRepObjectBase.Create;
begin
  inherited;
  FKids.Sorted := True;
end;

function TRepObjectBase.GetOrCreateKid(const AName: string; APropertyType: TKeyPropertyType): TEntity;
begin
  Result := Kids.ObjectOfValueDefault[AName, nil] as TRepPropertyKey;
  if Result = nil then
  begin
    Result := TRepPropertyKey.Create(Self, APropertyType, AName);
    Kids.AddObject(AName, Result);
  end
  else if APropertyType <> (Result as TRepPropertyKey).PropertyType then
    Result := nil;
end;

end.
