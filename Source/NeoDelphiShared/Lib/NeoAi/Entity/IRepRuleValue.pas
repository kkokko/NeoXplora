unit IRepRuleValue;

interface

uses
  TypesConsts, Entity, RepPropertyKey, RepPropertyValue;

type
  TIRepRuleValue = class(TEntity)
  private
    FKey: string;
    FValue: string;
    FRuleId: TId;
    FPropertyType: TRepPropertyKey.TPropertyType;
    FOperatorType: TRepPropertyValue.TOperatorType;
  published
    property Id;
    property Key: string read FKey write FKey;
    property OperatorType: TRepPropertyValue.TOperatorType read FOperatorType write FOperatorType;
    property PropertyType: TRepPropertyKey.TPropertyType read FPropertyType write FPropertyType;
    property RuleId: TId read FRuleId write FRuleId;
    property Value: string read FValue write FValue;
  end;

implementation

uses
  AppConsts;

{ TIRepRuleValue }

initialization
  TIRepRuleValue.RegisterEntityClassWithMappingToTable(ConstNeoPrefix + 'ireprulevalue');

end.
