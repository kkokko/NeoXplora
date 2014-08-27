unit BaseRuleCondition;

interface

uses
  TypesConsts, BaseRule, RepPropertyKey, RepPropertyValue;

type
  TBaseRuleCondition = class(TBaseRule)
  private
    FKey: string;
    FGroupId: TId;
    FValue: string;
    FKeyPropertyType: TRepPropertyKey.TKeyPropertyType;
    FOperatorType: TRepPropertyValue.TOperatorType;
  published
    property GroupId: TId read FGroupId write FGroupId;
    property Id;
    property Key: string read FKey write FKey;
    property OperatorType: TRepPropertyValue.TOperatorType read FOperatorType write FOperatorType;
    property Order;
    property KeyPropertyType: TRepPropertyKey.TKeyPropertyType read FKeyPropertyType write FKeyPropertyType;
    property Value: string read FValue write FValue;
  end;

implementation

{ TBaseRuleCondition }

initialization
  TBaseRuleCondition.RegisterEntityClass;

end.
