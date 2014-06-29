unit RepPropertyKey;

interface

uses
  RepObjectBase, TypesConsts, Entity, SkyLists, RepPropertyValue;

type
  TRepPropertyKey = class(TRepObjectBase)
  public
    type
      TPropertyType = (ptAttribute, ptEvent);
      TParentType = (ptEntity, ptAttrKey, ptAttrValue, ptEventKey, ptEventValue);
  private
    FPropertyKeyType: TPropertyType;
    FParentId: TId;
    FParentType: TParentType;
    FKey: string;
    FValues: TSkyStringList;
    FParent: TEntity;
  public
    constructor Create; overload; override;
    constructor Create(AParent: TEntity; APropertyKeyType: TPropertyType; const AKey: string); overload;

    function AddLiteralValue(AnOperatorType: TRepPropertyValue.TOperatorType; const AValue: string): TRepPropertyValue;
    procedure AddLinkValue(AnOperatorType: TRepPropertyValue.TOperatorType; ALinkType: TRepPropertyValue.TLinkType; ALinkObject: TEntity);
    
    property Parent: TEntity read FParent write FParent;
  published
    property Id;
    property Key: string read FKey write FKey;
    property Kids;
    property ParentId: TId read FParentId write FParentId;
    property ParentType: TParentType read FParentType write FParentType;
    property PropertyType: TPropertyType read FPropertyKeyType write FPropertyKeyType;
    property Values: TSkyStringList read FValues write FValues;
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

constructor TRepPropertyKey.Create(AParent: TEntity; APropertyKeyType: TPropertyType; const AKey: string);
begin
  Create;
  FParent := AParent;
  FPropertyKeyType := APropertyKeyType;
  FKey := AKey;
end;

initialization
  TRepPropertyKey.RegisterEntityClass;

end.
