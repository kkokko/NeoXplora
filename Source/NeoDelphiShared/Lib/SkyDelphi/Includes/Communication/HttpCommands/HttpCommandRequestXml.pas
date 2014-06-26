unit HttpCommandRequestXml;

interface

uses
  IdContext, IdCustomHTTPServer, HttpCommandRequestBase;

type
  THttpCommandRequestXml = class(THttpCommandRequestBase)
  public
    class procedure Execute(AServer: TObject; var AReponseText: string); override;
  end;
  THttpCommandRequestXmlClass = class of THttpCommandRequestXml;

implementation

uses
  Classes, EntityXmlReader, EntityXmlWriter, Session;

{ THttpCommandRequestXml }

class procedure THttpCommandRequestXml.Execute(AServer: TObject; var AReponseText: string);
begin
  RunCommand(AServer, 'xml', TEntityXmlReader, TEntityXmlWriter);
  Session.glbSession.HttpResponseInfo.ContentType := 'text/xml';
end;

end.