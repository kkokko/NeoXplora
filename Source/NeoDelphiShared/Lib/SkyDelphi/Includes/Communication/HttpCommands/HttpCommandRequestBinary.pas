unit HttpCommandRequestBinary;

interface

uses
  IdContext, IdCustomHTTPServer, HttpCommandRequestBase;

type
  THttpCommandRequestBinary = class(THttpCommandRequestBase)
  public
    class procedure Execute(AServer: TObject; var AReponseText: string); override;
  end;
  THttpCommandRequestBinaryClass = class of THttpCommandRequestBinary;

implementation

uses
  Classes, EntityStreamReader, EntityStreamWriter, Session;

{ THttpCommandRequestBinary }

class procedure THttpCommandRequestBinary.Execute(AServer: TObject; var AReponseText: string);
begin
  RunCommand(AServer, 'bin', TEntityStreamReader, TEntityStreamWriter);
  Session.glbSession.HttpResponseInfo.ContentType := 'application/octet-stream';
end;

end.