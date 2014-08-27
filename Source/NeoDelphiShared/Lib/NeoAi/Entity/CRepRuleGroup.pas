unit CRepRuleGroup;

interface

uses
  BaseRuleGroup;

type
  TCRepRuleGroup = class(TBaseRuleGroup)
  published
    property ConjunctionType;
    property Id;
    property Members;
    property Order;
    property ParentId;
    property RuleId;
  end;

implementation

uses
  AppConsts;

initialization
  TCRepRuleGroup.RegisterEntityClassWithMappingToTable(ConstNeoPrefix + 'creprulegroup');

end.