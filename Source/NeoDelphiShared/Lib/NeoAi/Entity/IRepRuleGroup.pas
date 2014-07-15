unit IRepRuleGroup;

interface

uses
  TypesConsts, BaseRule, RepPropertyKey, EntityList;

type
  TIRepRuleGroup = class(TBaseRule)
  private
    FConjunctionType: TRepPropertyKey.TConjunctionType;
    FParentId: TId;
    FMembers: TEntityList;
  public
    constructor Create; override;
  published
    property ConjunctionType: TRepPropertyKey.TConjunctionType read FConjunctionType write FConjunctionType;
    property Id;
    property Members: TEntityList read FMembers write FMembers;
    property Order;
    property ParentId: TId read FParentId write FParentId;
    property RuleId;
  end;

implementation

uses
  AppConsts;

{ TIRepRuleGroup }

constructor TIRepRuleGroup.Create;
begin
  inherited;
  // list will continue sorting
  FMembers.Sort(Tok_Order);
end;

initialization
  TIRepRuleGroup.RegisterEntityClassWithMappingToTable(ConstNeoPrefix + 'ireprulegroup');

end.