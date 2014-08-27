unit RepGroup;

interface

uses
  RepEntity, EntityList, SysUtils;

type
  TRepGroup = class(TRepEntity)
  private
    FMembers: TEntityList;
  public
    procedure GetAsString(AStringBuilder: TStringBuilder); override;
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

procedure TRepGroup.GetAsString(AStringBuilder: TStringBuilder);
var
  TheKidsCount: Integer;
  TheMembersCount: Integer;
  I: Integer;
begin
  TheMembersCount := Members.Count;
  TheKidsCount := Kids.Count;
  if TheMembersCount > 0 then
  begin
    AStringBuilder.Append(GetName);
    AStringBuilder.Append('(');
    for I := 0 to TheMembersCount - 1 do
    begin
      AStringBuilder.Append('[');
      AStringBuilder.Append((Members.Objects[I] as TRepEntity).Name);
      if I < TheMembersCount - 1 then
        AStringBuilder.Append('] + ')
      else
        AStringBuilder.Append(']');
    end;
    if TheKidsCount > 0 then
      AStringBuilder.Append(', ');
    GetPropertiesAsString(AStringBuilder);
    AStringBuilder.Append(')');
  end else
    inherited;
end;

function TRepGroup.GetEntityType: TRepEntity.TEntityType;
begin
  Result := etGroup;
end;

initialization
  TRepGroup.RegisterEntityClass;

end.
