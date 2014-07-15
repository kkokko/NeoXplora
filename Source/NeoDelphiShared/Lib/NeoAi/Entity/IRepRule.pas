unit IRepRule;

interface

uses
  TypesConsts, Entity, EntityFieldNamesToken, IRepRuleGroup, EntityList;

type
  TIRepRule = class(TEntity)
  private
    FName: string;
    FOrder: Integer;
    FMainRuleGroup: TIRepRuleGroup;
    FValues: TEntityList;
  protected
    function GetName: string; override;
    procedure SetName(const AName: string); override;
  public
    class var
      Tok_Order: TEntityFieldNamesToken;
  published
    property Id;
    property MainRuleGroup: TIRepRuleGroup read FMainRuleGroup write FMainRuleGroup;
    property Name;
    property Order: Integer read FOrder write FOrder;
    property Values: TEntityList read FValues write FValues; // array of TIRepRuleValue
  end;

implementation

uses
  AppConsts;

{ TIRepRule }

function TIRepRule.GetName: string;
begin
  Result := FName;
end;

procedure TIRepRule.SetName(const AName: string);
begin
  FName := AName;
end;

initialization
  TIRepRule.RegisterEntityClassWithMappingToTable(ConstNeoPrefix + 'ireprule');
  TIRepRule.RegisterToken(TIRepRule.Tok_Order, 'Order');

end.
