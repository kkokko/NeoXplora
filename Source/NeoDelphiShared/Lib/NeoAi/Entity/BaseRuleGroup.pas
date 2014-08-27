unit BaseRuleGroup;

interface

uses
  TypesConsts, BaseRule, RepPropertyKey, EntityList;

type
  TBaseRuleGroup = class(TBaseRule)
  private
    FConjunctionType: TRepPropertyKey.TConjunctionType;
    FParentId: TId;
    FMembers: TEntityList;
    FRuleId: TId;
  public
    constructor Create; override;
  published
    property ConjunctionType: TRepPropertyKey.TConjunctionType read FConjunctionType write FConjunctionType;
    property Id;
    property Members: TEntityList read FMembers write FMembers;
    property Order;
    property ParentId: TId read FParentId write FParentId;
    property RuleId: TId read FRuleId write FRuleId;
  end;

implementation

uses
  AppConsts;

{ TBaseRuleGroup }

constructor TBaseRuleGroup.Create;
begin
  inherited;
  // list will continue sorting
  FMembers.Sort(Tok_Order);
end;

initialization
  TBaseRuleGroup.RegisterEntityClass;

end.