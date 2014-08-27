unit CRepRule;

interface

uses
  TypesConsts, Entity, EntityFieldNamesToken, CRepRuleGroup, EntityList;

type
  TCRepRule = class(TEntity)
  private
    FMainRuleGroup: TCRepRuleGroup;
    FName: string;
    FOrder: Integer;
    FRuleScore: Integer;
  protected
    function GetName: string; override;
    procedure SetName(const AName: string); override;
  public
    class var
      Tok_Order: TEntityFieldNamesToken;
  published
    property Id;
    property MainRuleGroup: TCRepRuleGroup read FMainRuleGroup write FMainRuleGroup;
    property Name;
    property Order: Integer read FOrder write FOrder;
    property RuleScore: Integer read FRuleScore write FRuleScore;
  end;

implementation

uses
  AppConsts;

{ TCRepRule }

function TCRepRule.GetName: string;
begin
  Result := FName;
end;

procedure TCRepRule.SetName(const AName: string);
begin
  FName := AName;
end;

initialization
  TCRepRule.RegisterEntityClassWithMappingToTable(ConstNeoPrefix + 'creprule');
  TCRepRule.RegisterToken(TCRepRule.Tok_Order, 'Order');

end.
