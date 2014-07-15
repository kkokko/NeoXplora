unit IRepRuleCondition;

interface

uses
  TypesConsts, BaseRule, RepPropertyKey, RepPropertyValue;

type
  TIRepRuleCondition = class(TBaseRule)
  private
    FKey: string;
    FGroupId: TId;
    FValue: string;
    FPropertyType: TRepPropertyKey.TPropertyType;
    FOperatorType: TRepPropertyValue.TOperatorType;
  published
    property GroupId: TId read FGroupId write FGroupId;
    property Id;
    property Key: string read FKey write FKey;
    property OperatorType: TRepPropertyValue.TOperatorType read FOperatorType write FOperatorType;
    property Order;
    property PropertyType: TRepPropertyKey.TPropertyType read FPropertyType write FPropertyType;
    property RuleId;
    property Value: string read FValue write FValue;
  end;

implementation

uses
  AppConsts;

{ TIRepRuleCondition }

initialization
  TIRepRuleCondition.RegisterEntityClassWithMappingToTable(ConstNeoPrefix + 'ireprulecondition');

end.
