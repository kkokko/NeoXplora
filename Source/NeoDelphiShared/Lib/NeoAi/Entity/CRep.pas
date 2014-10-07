unit CRep;

interface

uses
  Entity, EntityFieldNamesToken, TypesConsts;

type
  TCRep = class(TEntity)
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

{ TCRep }

class function TCRep.EntityToken_Id: TEntityFieldNamesToken;
begin
  Result := Tok_Id;
end;

initialization
  TCRep.RegisterEntityClassWithMappingToTable(ConstNeoPrefix + 'CRep');
  TCRep.RegisterToken(TCRep.Tok_Id, 'Id');
  TCRep.RegisterToken(TCRep.Tok_PageId, 'PageId');

end.
