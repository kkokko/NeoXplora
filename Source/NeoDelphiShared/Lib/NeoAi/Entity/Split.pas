unit Split;

interface

uses
  Entity, EntityFieldNamesToken, TypesConsts;

type
  TSplit = class(TEntity)
  public
    type
      TSplitType = (stSentence, stProto);
  private
    FName: string;
    FSplitType: TSplitType;
    FOrder: Integer;
  protected
    function GetName: string; override;
    procedure SetName(const AName: string); override;
  public
    class function EntityToken_Id: TEntityFieldNamesToken; override;
    class function EntityToken_Name: TEntityFieldNamesToken; override;
    class var
      Tok_Id: TEntityFieldNamesToken;
      Tok_Name: TEntityFieldNamesToken;
      Tok_Order: TEntityFieldNamesToken;
      Tok_SplitType: TEntityFieldNamesToken;
  published
    property Id;
    property Name;
    property Order: Integer read FOrder write FOrder;
    property SplitType: TSplitType read FSplitType write FSplitType;
  end;

implementation

uses
  AppConsts;

{ TSplit }

class function TSplit.EntityToken_Id: TEntityFieldNamesToken;
begin
  Result := Tok_Id;
end;

class function TSplit.EntityToken_Name: TEntityFieldNamesToken;
begin
  Result := Tok_Name;
end;

function TSplit.GetName: string;
begin
  Result := FName;
end;

procedure TSplit.SetName(const AName: string);
begin
  FName := AName;
end;

initialization
  TSplit.RegisterEntityClass;;
  TSplit.RegisterToken(TSplit.Tok_Id, 'Id');
  TSplit.RegisterToken(TSplit.Tok_Name, 'Name');
  TSplit.RegisterToken(TSplit.Tok_Order, 'Order');
  TSplit.RegisterToken(TSplit.Tok_SplitType, 'SplitType');

end.