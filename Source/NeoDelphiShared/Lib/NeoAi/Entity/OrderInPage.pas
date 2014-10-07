unit OrderInPage;

interface

uses
  Entity, EntityFieldNamesToken, TypesConsts;

type
  TOrderInPage = class(TEntity)
  private
    FProtoId: TId;
    FPageId: TId;
    FOrder: Integer;
    FIndentation: Integer;
    FSentenceId: TId;
  public
    class function EntityToken_Id: TEntityFieldNamesToken; override;
    class var
      Tok_Id: TEntityFieldNamesToken;
      Tok_Order: TEntityFieldNamesToken;
      Tok_PageId: TEntityFieldNamesToken;
      Tok_ProtoId: TEntityFieldNamesToken;
      Tok_SentenceId: TEntityFieldNamesToken;
  published
    property Id;
    property Indentation: Integer read FIndentation write FIndentation;
    property Order: Integer read FOrder write FOrder;
    property PageId: TId read FPageId write FPageId;
    property ProtoId: TId read FProtoId write FProtoId;
    property SentenceId: TId read FSentenceId write FSentenceId;
  end;

implementation

uses
  AppConsts;

{ TOrderInPage }
class function TOrderInPage.EntityToken_Id: TEntityFieldNamesToken;
begin
  Result := Tok_Id;
end;
initialization
  TOrderInPage.RegisterEntityClassWithMappingToTable(ConstNeoPrefix + 'orderinpage');
  TOrderInPage.RegisterToken(TOrderInPage.Tok_Id, 'Id');
  TOrderInPage.RegisterToken(TOrderInPage.Tok_PageId, 'PageId');
  TOrderInPage.RegisterToken(TOrderInPage.Tok_ProtoId, 'ProtoId');
  TOrderInPage.RegisterToken(TOrderInPage.Tok_SentenceId, 'SentenceId');
  TOrderInPage.RegisterToken(TOrderInPage.Tok_Order, 'Order');

end.
