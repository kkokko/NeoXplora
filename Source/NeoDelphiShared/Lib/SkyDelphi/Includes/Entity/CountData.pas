unit CountData;

interface

uses
  Entity, TypesConsts;

type
  TCountData = class(TEntity)
  private
    FNumber: Integer;
  public
    class var
      EntityToken_Number: TEntityFieldNamesToken;
  published
    property Number: Integer read FNumber write FNumber;
  end;

implementation

initialization
  TCountData.RegisterEntityClass;
  TCountData.RegisterToken(TCountData.EntityToken_Number, 'Number');

end.
