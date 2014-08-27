unit RepPropertyValue;

interface

uses
  RepObjectBase, TypesConsts, Entity;

type
  TRepPropertyValue = class(TRepObjectBase)
  public
    type
      TLinkType = (ltNone, ltEntity, ltAttrKey, ltEventKey);
      TOperatorType = (otNone, otEquals, otSmaller, otSmallerOrEqual, otGreater, otGreaterOrEqual, otDiffers);
      TOperatorTypes = set of TOperatorType;
  private
    FLinkObject: TEntity;
    FValue: string;
    FRepPropertyKey: TEntity;
    FKeyId: TId;
    FOperatorType: TOperatorType;
    FTargetValueId: TId;
    FTargetEntityId: TId;
    FTargetKeyId: TId;
  public
    constructor Create(ARepPropertyKey: TEntity; AnOperatorType: TOperatorType; const AValue: string); overload;
    constructor Create(ARepPropertyKey: TEntity; AnOperatorType: TOperatorType; ALinkType: TRepPropertyValue.TLinkType; ALinkObject: TEntity); overload;

    property LinkObject: TEntity read FLinkObject write FLinkObject;
    property RepPropertyKey: TEntity read FRepPropertyKey write FRepPropertyKey;
  published
    property Id;
    property Kids;
    property TargetEntityId: TId read FTargetEntityId write FTargetEntityId;
    property TargetKeyId: TId read FTargetKeyId write FTargetKeyId;
    property TargetValueId: TId read FTargetValueId write FTargetValueId;
    property OperatorType: TOperatorType read FOperatorType write FOperatorType;
    property KeyId: TId read FKeyId write FKeyId;
    property Value: string read FValue write FValue;
  end;

implementation

{ TRepPropertyValue }

constructor TRepPropertyValue.Create(ARepPropertyKey: TEntity; AnOperatorType: TOperatorType; const AValue: string);
begin
  Create;
  FOperatorType := AnOperatorType;
  FRepPropertyKey := ARepPropertyKey;
  FValue := AValue;
end;

constructor TRepPropertyValue.Create(ARepPropertyKey: TEntity; AnOperatorType: TOperatorType;
  ALinkType: TRepPropertyValue.TLinkType; ALinkObject: TEntity);
begin
  Create;
  case ALinkType of
    ltEntity:
      FTargetEntityId := ALinkObject.Id;
    ltAttrKey:
      FTargetKeyId := ALinkObject.Id;
    ltEventKey:
      FTargetValueId := ALinkObject.Id;
  end;
  FLinkObject := ALinkObject;
  FOperatorType := AnOperatorType;
  FRepPropertyKey := ARepPropertyKey;
end;

initialization
  TRepPropertyValue.RegisterEntityClass;

end.
