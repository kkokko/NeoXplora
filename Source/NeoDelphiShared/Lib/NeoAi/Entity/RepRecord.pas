unit RepRecord;

interface

uses
  Entity, SkyLists, RepEntity;

type
  TRepRecord = class(TEntity)
  private
    FRepEntities: TSkyStringList;
    FSentenceNumber: Integer;
  public
    constructor Create; override;
    procedure Clear;
    function GetAsString: string;
    function GetOrCreateEntity(AKind: Char; ANumber: Integer): TRepEntity; overload;
    function GetOrCreateEntity(AnEntityType: TRepEntity.TEntityType; ANumber: Integer): TRepEntity; overload;
  published
    property SentenceNumber: Integer read FSentenceNumber write FSentenceNumber;
    property RepEntities: TSkyStringList read FRepEntities write FRepEntities; // array of TRepEntity: (Person Object or Group)
  end;

implementation

uses
  SysUtils, RepPerson, RepGroup, RepObject;

{ TRepRecord }

procedure TRepRecord.Clear;
begin
  FRepEntities.Clear;
  FSentenceNumber := 0;
end;

constructor TRepRecord.Create;
begin
  inherited;
  FRepEntities.Sorted := True;
end;

function TRepRecord.GetAsString: string;
var
  TheBuilder: TStringBuilder;
  TheCount: Integer;
  I: Integer;
begin
  TheBuilder := TStringBuilder.Create;
  try
    TheCount := RepEntities.Count;
    if TheCount > 0 then
    begin
      for I := 0 to TheCount - 1 do
      begin
        (RepEntities.Objects[I] as TRepEntity).GetAsString(TheBuilder);
        TheBuilder.Append(', ');
      end;
      TheBuilder.Length := TheBuilder.Length - 2;
    end;
    Result := TheBuilder.ToString;
  finally
    TheBuilder.Free;
  end;
end;

function TRepRecord.GetOrCreateEntity(AnEntityType: TRepEntity.TEntityType; ANumber: Integer): TRepEntity;
begin
  case AnEntityType of
    etPerson:
      Result := GetOrCreateEntity('p', ANumber);
    etObject:
      Result := GetOrCreateEntity('o', ANumber);
  else
    Result := GetOrCreateEntity('g', ANumber);
  end;
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
