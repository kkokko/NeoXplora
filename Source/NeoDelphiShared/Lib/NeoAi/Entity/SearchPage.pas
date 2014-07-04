unit SearchPage;

interface

uses
  Entity, TypesConsts;

type
  TSearchPage = class(TEntity)
  private
    FLink: string;
    FBody: string;
    FTitle: string;
  public
    class var
      Tok_Title: TEntityFieldNamesToken;
      Tok_Body: TEntityFieldNamesToken;
      Tok_Link: TEntityFieldNamesToken;
  published
    property Id;
    property Title: string read FTitle write FTitle;
    property Body: string read FBody write FBody;
    property Link: string read FLink write FLink;
  end;

implementation

uses
  AppConsts;

initialization
  TSearchPage.RegisterEntityClassWithMappingToTable(ConstNeoPrefix + 'page');
  TSearchPage.RegisterToken(TSearchPage.Tok_Title, 'Title');
  TSearchPage.RegisterToken(TSearchPage.Tok_Body, 'Body');
  TSearchPage.RegisterToken(TSearchPage.Tok_Link, 'Link');

end.
