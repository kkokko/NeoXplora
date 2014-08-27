unit CRepRuleCondition;

interface

uses
  BaseRuleCondition;

type
  TCRepRuleCondition = class(TBaseRuleCondition)
  published
    property GroupId;
    property Id;
    property Key;
    property OperatorType;
    property Order;
    property KeyPropertyType;
    property Value;
  end;

implementation

uses
  AppConsts;

{ TCRepRuleCondition }

initialization
  TCRepRuleCondition.RegisterEntityClassWithMappingToTable(ConstNeoPrefix + 'creprulecondition');

end.
