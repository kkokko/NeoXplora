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
  private
    FLinkObject: TEntity;
    FLinkType: TLinkType;
    FValue: string;
    FRepPropertyKey: TEntity;
    FRepPropertyKeyId: TId;
    FOperatorType: TOperatorType;
  public
    constructor Create(ARepPropertyKey: TEntity; AnOperatorType: TOperatorType; const AValue: string); overload;
    constructor Create(ARepPropertyKey: TEntity; AnOperatorType: TOperatorType; ALinkType: TRepPropertyValue.TLinkType; ALinkObject: TEntity); overload;

    property LinkObject: TEntity read FLinkObject write FLinkObject;
    property RepPropertyKey: TEntity read FRepPropertyKey write FRepPropertyKey;
  published
    property Id;
    property Kids;
    property LinkType: TLinkType read FLinkType write FLinkType;
    property OperatorType: TOperatorType read FOperatorType write FOperatorType;
    property RepPropertyKeyId: TId read FRepPropertyKeyId write FRepPropertyKeyId;
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
  FLinkType := ALinkType;
  FLinkObject := ALinkObject;
  FOperatorType := AnOperatorType;
  FRepPropertyKey := ARepPropertyKey;
end;

initialization
  TRepPropertyValue.RegisterEntityClass;

end.
