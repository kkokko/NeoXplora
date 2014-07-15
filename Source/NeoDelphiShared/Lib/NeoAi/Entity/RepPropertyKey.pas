unit RepPropertyKey;

interface

uses
  RepObjectBase, TypesConsts, Entity, SkyLists, RepPropertyValue;

type
  TRepPropertyKey = class(TRepObjectBase)
  public
    type
      TConjunctionType = (ctAnd, ctOr);
      TParentType = (ptEntity, ptAttrKey, ptAttrValue, ptEventKey, ptEventValue);
  private
    FPropertyKeyType: TRepObjectBase.TPropertyType;
    FParentId: TId;
    FParentType: TParentType;
    FKey: string;
    FValues: TSkyStringList;
    FParent: TEntity;
  public
    constructor Create; overload; override;
    constructor Create(AParent: TEntity; APropertyKeyType: TRepObjectBase.TPropertyType; const AKey: string); overload;

    function AddLiteralValue(AnOperatorType: TRepPropertyValue.TOperatorType; const AValue: string): TRepPropertyValue;
    procedure AddLinkValue(AnOperatorType: TRepPropertyValue.TOperatorType; ALinkType: TRepPropertyValue.TLinkType; ALinkObject: TEntity);
    function GetValuesWithOperatorTypes(SomeValueType: TRepPropertyValue.TOperatorTypes): TEntities;
    
    property Parent: TEntity read FParent write FParent;
  published
    property Id;
    property Key: string read FKey write FKey;
    property Kids;
    property ParentId: TId read FParentId write FParentId;
    property ParentType: TParentType read FParentType write FParentType;
    property PropertyType: TRepObjectBase.TPropertyType read FPropertyKeyType write FPropertyKeyType;
    property Values: TSkyStringList read FValues write FValues; // array of (string: TRepPropertyValue)
  end;

implementation

uses
  SysUtils;

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

constructor TRepPropertyKey.Create(AParent: TEntity; APropertyKeyType: TRepObjectBase.TPropertyType; const AKey: string);
begin
  Create;
  FParent := AParent;
  FPropertyKeyType := APropertyKeyType;
  FKey := AKey;
end;

function TRepPropertyKey.GetValuesWithOperatorTypes(SomeValueType: TRepPropertyValue.TOperatorTypes): TEntities;
var
  TheCount: Integer;
  I: Integer;
begin
  SetLength(Result, Values.Count);
  TheCount := 0;
  for I := 0 to Values.Count - 1 do
    if ((Values.Objects[I] as TRepPropertyValue).OperatorType in SomeValueType) then
    begin
      Result[TheCount] := Values.Objects[I] as TEntity;
      Inc(TheCount);
    end;
  SetLength(Result, TheCount);
end;

initialization
  TRepPropertyKey.RegisterEntityClass;

end.
