unit RepProperties;

interface

uses
  Entity, TypesConsts;

type
  TRepProperties = class(TEntity)
  public
    type
      TPropertyType = (ptAttribute, ptEvent);
      TParentType = (ptEntity, ptAttrKey, ptAttrValue, ptEventKey, ptEventValue);
      TLinkType = (ltEntity, ltAttrKey, ltEventKey);
  private
    FRepPropertyType: TPropertyType;
    FParentId: TId;
    FParentType: TParentType;
    FLinkType: TLinkType;
    FKey: string;
    FIsLink: Boolean;
    FPageId: TId;
    FValue: string;
  published
    property Id;
    property IsLink: Boolean read FIsLink write FIsLink;
    property Key: string read FKey write FKey;
    property LinkType: TLinkType read FLinkType write FLinkType;
    property PageId: TId read FPageId write FPageId;
    property ParentId: TId read FParentId write FParentId;
    property ParentType: TParentType read FParentType write FParentType;
    property PropertyType: TPropertyType read FRepPropertyType write FRepPropertyType;
    property Value: string read FValue write FValue;
  end;

implementation

end.
