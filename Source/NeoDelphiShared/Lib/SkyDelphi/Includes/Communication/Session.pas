unit Session;

interface

uses
  IdContext, IdCustomHTTPServer, SkyHttpServer;

type
  TSession = record
    Name: string;
    Data: TObject;
    HttpContext: TIdContext;
    HttpRequestInfo: TIdHTTPRequestInfo;
    HttpResponseInfo: TIdHTTPResponseInfo;
    HttpServer: TSkyHttpServer;
  end;

threadvar
  glbSession: TSession;

implementation

end.
