unit AppEntityXmlWriter2;

interface

uses
  EntityXmlWriter, Entity;

type
  TAppEntityXmlWriter2 = class(TEntityXmlWriter)
  protected
    class function GetClassNameForEntity(AnEntity: TEntity): string; override;
  end;

implementation

{ TAppEntityXmlWriter2 }

class function TAppEntityXmlWriter2.GetClassNameForEntity(AnEntity: TEntity): string;
begin
  Result := inherited GetClassNameForEntity(AnEntity);
  Delete(Result, 1, 1);
end;

end.
