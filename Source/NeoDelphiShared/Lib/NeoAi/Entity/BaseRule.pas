unit BaseRule;

interface

uses
  Entity, TypesConsts, EntityFieldNamesToken;

type
  TBaseRule = class(TEntity)
  private
    FOrder: Integer;
  public
    class var
      Tok_Order: TEntityFieldNamesToken;
  published
    property Order: Integer read FOrder write FOrder;
  end;

implementation

initialization
  TBaseRule.RegisterEntityClass;
  TBaseRule.RegisterToken(TBaseRule.Tok_Order, 'Order');

end.
