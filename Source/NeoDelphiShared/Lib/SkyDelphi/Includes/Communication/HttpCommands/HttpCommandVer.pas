unit HttpCommandVer;

interface

uses
  HttpCommand;

type
  THttpCommandVer = class(THttpCommand)
  public
    class procedure Execute(AServer: TObject; var AReponseText: string); override;
  end;
  THttpCommandVerClass = class of THttpCommandVer;

implementation

uses
  SysUtils, SkyHttpServer, Classes, Session;

{ THttpCommandVer }

class procedure THttpCommandVer.Execute(AServer: TObject; var AReponseText: string);
var
  TheString: AnsiString;
begin
  TheString := AnsiString(Format('<HTML>Welcome %s<br>Version: %s <br>Running since: %s<br></HTML>',
    [Session.glbSession.HttpRequestInfo.RemoteIP, IntToHex($20120805, 8), DateTimeToStr((AServer as TSkyHttpServer).CreatedOn)]));
  (Session.glbSession.HttpResponseInfo.ContentStream as TMemorystream).Write(TheString[1], Length(TheString));
  Session.glbSession.HttpResponseInfo.ContentType := 'text/html';
end;

end.