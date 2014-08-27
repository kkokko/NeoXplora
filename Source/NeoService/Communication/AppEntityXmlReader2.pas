unit AppEntityXmlReader2;

interface

uses
  EntityXmlReader, OmniXML;

type
  TAppEntityXmlReader2 = class(TEntityXmlReader)
  protected
    class function GetClassNameFromXmlNode(ANode: IXmlNode): string; override;
  end;

implementation

{ TAppEntityXmlReader2 }

class function TAppEntityXmlReader2.GetClassNameFromXmlNode(ANode: IXmlNode): string;
begin
  Result := 'T' + (inherited GetClassNameFromXmlNode(ANode));
end;

end.
