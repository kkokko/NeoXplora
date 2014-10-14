unit CRepHighlight;

interface

uses
  Entity, EntityFieldNamesToken, TypesConsts;

type
  TCRepHighlight = class(TEntity)
  private
    FPageId: TId;
  public
    class var
      Tok_Id: TEntityFieldNamesToken;
      Tok_PageId: TEntityFieldNamesToken;
    class function EntityToken_Id: TEntityFieldNamesToken; override;
  published
    property Id;
    property PageId: TId read FPageId write FPageId;
  end;

implementation

uses
  EntityMappingManager, EntityMapping, AppConsts;

{ TCRepHighlight }

class function TCRepHighlight.EntityToken_Id: TEntityFieldNamesToken;
begin
  Result := Tok_Id;
end;

initialization
  TCRepHighlight.RegisterEntityClassWithMappingToTable(ConstNeoPrefix + 'CRepHighlight_highlight');
  TCRepHighlight.RegisterToken(TCRepHighlight.Tok_Id, 'Id');
  TCRepHighlight.RegisterToken(TCRepHighlight.Tok_PageId, 'PageId');

end.
