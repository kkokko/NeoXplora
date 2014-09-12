unit RepPropertyKey;

interface

uses
  RepObjectBase, TypesConsts, Entity, SkyLists, RepPropertyValue, SysUtils;

type
  TRepPropertyKey = class(TRepObjectBase)
  public
    type
      TConjunctionType = (ctAnd, ctOr);
  private
    FPropertyKeyType: TRepObjectBase.TKeyPropertyType;
    FParentId: TId;
    FKey: string;
    FValues: TSkyStringList;
    FParent: TEntity;
    procedure GetPropertyKeyTypeAsString(AStringBuilder: TStringBuilder);
  public
    constructor Create; overload; override;
    constructor Create(AParent: TEntity; APropertyKeyType: TRepObjectBase.TKeyPropertyType; const AKey: string); overload;

    function AddLiteralValue(AnOperatorType: TRepPropertyValue.TOperatorType; const AValue: string): TRepPropertyValue;
    procedure AddLinkValue(AnOperatorType: TRepPropertyValue.TOperatorType; ALinkType: TRepPropertyValue.TLinkType; ALinkObject: TEntity);
    function CountValuesWithOperatorTypes(SomeValueTypes: TRepPropertyValue.TOperatorTypes): Integer;
    procedure GetAsString(AStringBuilder: TStringBuilder);
    function GetValuesWithOperatorTypes(SomeValueTypes: TRepPropertyValue.TOperatorTypes): TEntities;

    property Parent: TEntity read FParent write FParent;
  published
    property Id;
    property Key: string read FKey write FKey;
    property Kids; // array of string: TRepPropertyKey
    property ParentId: TId read FParentId write FParentId;
    property PropertyType: TRepObjectBase.TKeyPropertyType read FPropertyKeyType write FPropertyKeyType;
    property Values: TSkyStringList read FValues write FValues; // array of (string: TRepPropertyValue)
  end;

implementation

{ TRepPropertyKey }

procedure TRepPropertyKey.AddLinkValue(AnOperatorType: TRepPropertyValue.TOperatorType; ALinkType: TRepPropertyValue.TLinkType; ALinkObject: TEntity);
begin
  if FValues.IndexOf('"' + IntToStr(Integer(ALinkObject))) <> -1 then
    Exit;
  FValues.AddObject('"' + IntToStr(Integer(ALinkObject)), TRepPropertyValue.Create(Self, AnOperatorType, ALinkType, ALinkObject));
end;

function TRepPropertyKey.AddLiteralValue(AnOperatorType: TRepPropertyValue.TOperatorType; const AValue: string): TRepPropertyValue;
begin
  Result := FValues.ObjectOfValueDefault[AValue, nil] as TRepPropertyValue;
  if Result <> nil then
    Exit;
  Result := TRepPropertyValue.Create(Self, AnOperatorType, AValue);
  FValues.AddObject(AValue, Result);
end;

constructor TRepPropertyKey.Create;
begin
  inherited;
  FValues.Sorted := True;
end;

function TRepPropertyKey.CountValuesWithOperatorTypes(SomeValueTypes: TRepPropertyValue.TOperatorTypes): Integer;
var
  I: Integer;
begin
  Result := 0;
  for I := 0 to Values.Count - 1 do
    if ((Values.Objects[I] as TRepPropertyValue).OperatorType in SomeValueTypes) then
      Inc(Result);
end;

constructor TRepPropertyKey.Create(AParent: TEntity; APropertyKeyType: TRepObjectBase.TKeyPropertyType; const AKey: string);
begin
  Create;
  FParent := AParent;
  FPropertyKeyType := APropertyKeyType;
  FKey := AKey;
end;

procedure TRepPropertyKey.GetAsString(AStringBuilder: TStringBuilder);
var
  TheCount: Integer;
  TheEntities: TEntities;
begin
  TheEntities := GetValuesWithOperatorTypes([otEquals]);
  TheCount := Length(TheEntities);
  if TheCount = 0 then
  begin
    GetPropertyKeyTypeAsString(AStringBuilder);
    AStringBuilder.Append(Key);
    Exit;
  end;
{  if AMergeValues then
  begin
    GetPropertyKeyTypeAsString(AStringBuilder);
    AStringBuilder.Append(Key);
    for I := 0 to TheCount - 1 do
    begin
      TheValue := TheEntities  TRepPropertyValue;
    end;
  end;}
end;

procedure TRepPropertyKey.GetPropertyKeyTypeAsString(AStringBuilder: TStringBuilder);
begin
  case PropertyType of
    ptAttribute:
      AStringBuilder.Append('.');
    ptEvent:
      AStringBuilder.Append(':');
    else
      raise Exception.Create('Invalid PropertyKeyType');
  end;
end;

function TRepPropertyKey.GetValuesWithOperatorTypes(SomeValueTypes: TRepPropertyValue.TOperatorTypes): TEntities;
var
  TheCount: Integer;
  I: Integer;
begin
  SetLength(Result, Values.Count);
  TheCount := 0;
  for I := 0 to Values.Count - 1 do
    if ((Values.Objects[I] as TRepPropertyValue).OperatorType in SomeValueTypes) then
    begin
      Result[TheCount] := Values.Objects[I] as TEntity;
      Inc(TheCount);
    end;
  SetLength(Result, TheCount);
end;

initialization
  TRepPropertyKey.RegisterEntityClass;

end.
