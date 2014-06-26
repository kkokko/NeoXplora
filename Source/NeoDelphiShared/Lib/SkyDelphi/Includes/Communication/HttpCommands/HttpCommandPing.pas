unit HttpCommandPing;

interface

uses
  IdCustomHTTPServer, HttpCommand;

type
  THttpCommandPing = class(THttpCommand)
  public
    class procedure Execute(AServer: TObject; var AReponseText: string); override;
  end;
  THttpCommandPingClass = class of THttpCommandPing;

implementation

uses
  Classes, Session;

{ THttpCommandPing }

class procedure THttpCommandPing.Execute(AServer: TObject; var AReponseText: string);
var
  TheString: AnsiString;
begin
  TheString := 'Pong';
  (Session.glbSession.HttpResponseInfo.ContentStream as TMemorystream).Write(
    TheString[1], Length(TheString));
  Session.glbSession.HttpResponseInfo.ContentType := 'text/plain';
end;

end.