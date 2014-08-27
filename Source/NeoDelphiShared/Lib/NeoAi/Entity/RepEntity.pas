unit RepEntity;

interface

uses
  RepObjectBase, TypesConsts, SkyLists, Entity, EntityList, SysUtils;

type
  // a Rep entity can be either a Person, Object or Group
  // it has Attributes and Events(kids)
  TRepEntity = class(TRepObjectBase)
  public
    type
      TEntityType = (etPerson, etObject, etGroup);
  private
    FPageId: TId;
    FEntityNumber: Integer;
    function HasMultipleKeys: Boolean;
    procedure SetEntityType(const Value: TEntityType);
  protected
    function GetEntityType: TRepEntity.TEntityType; virtual; abstract;
    function GetName: string; override;
  public
    constructor Create(ANumber: Integer); overload;
    procedure GetAsString(AStringBuilder: TStringBuilder); virtual;
    procedure GetPropertiesAsString(AStringBuilder: TStringBuilder);
  published
    property Id;
    property EntityNumber: Integer read FEntityNumber write FEntityNumber;
    property EntityType: TEntityType read GetEntityType write SetEntityType;
    property Kids; // array of string:TRepPropertyKey
    property Name;
    property PageId: TId read FPageId write FPageId;
  end;
  TRepEntityClass = class of TRepEntity;

implementation

uses
  RepPropertyKey, RepPropertyValue;

{ TRepEntity }

constructor TRepEntity.Create(ANumber: Integer);
begin
  Create;
  FEntityNumber := ANumber;
end;

procedure TRepEntity.GetAsString(AStringBuilder: TStringBuilder);
var
  TheKeyCount: Integer;
  TheRepPropertyKey: TRepPropertyKey;
  I: Integer;
begin
  TheKeyCount := Kids.Count;
  if TheKeyCount = 0 then
    Exit;
  AStringBuilder.Append(GetName);
  if not HasMultipleKeys then
  begin
    TheRepPropertyKey := Kids.Objects[0] as TRepPropertyKey;
    TheRepPropertyKey.GetAsString(AStringBuilder);
    Exit;
  end;
  AStringBuilder.Append('(');
  GetPropertiesAsString(AStringBuilder);
  AStringBuilder.Append(')');
end;

procedure TRepEntity.GetPropertiesAsString(AStringBuilder: TStringBuilder);
var
  TheCount: Integer;
  TheRepPropertyKey: TRepPropertyKey;
  I: Integer;
begin
  TheCount := Kids.Count;
  if TheCount = 0 then
    Exit;
  for I := 0 to TheCount - 1 do
  begin
    TheRepPropertyKey := Kids.Objects[I] as TRepPropertyKey;
    TheRepPropertyKey.GetAsString(AStringBuilder);
    AStringBuilder.Append(', ');
  end;
  AStringBuilder.Length := AStringBuilder.Length - 2;
end;

function TRepEntity.HasMultipleKeys: Boolean;
var
  TheKeyCount: Integer;
  TheRepPropertyKey: TRepPropertyKey;
begin
  Result := False;
  TheKeyCount := Kids.Count;
  if TheKeyCount = 0 then
    Exit;
  Result := TheKeyCount > 1;
  if Result then
    Exit;
  TheRepPropertyKey := Kids.Objects[0] as TRepPropertyKey;
  TheKeyCount := TheRepPropertyKey.CountValuesWithOperatorTypes(
    [otSmaller, otSmallerOrEqual, otGreater, otGreaterOrEqual, otDiffers]);
  if TheRepPropertyKey.CountValuesWithOperatorTypes([otEquals]) > 0 then
    Inc(TheKeyCount);
  Result := TheKeyCount > 1;
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
