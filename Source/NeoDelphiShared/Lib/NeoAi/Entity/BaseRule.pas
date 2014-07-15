unit BaseRule;

interface

uses
  Entity, TypesConsts, EntityFieldNamesToken;

type
  TBaseRule = class(TEntity)
  private
    FOrder: Integer;
    FRuleId: TId;
  public
    class var
      Tok_Order: TEntityFieldNamesToken;
  published
    property Order: Integer read FOrder write FOrder;
    property RuleId: TId read FRuleId write FRuleId;
  end;

implementation

initialization
  TBaseRule.RegisterEntityClass;
  TBaseRule.RegisterToken(TBaseRule.Tok_Order, 'Order');

end.
