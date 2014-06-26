unit HttpCommandRoot;

interface

uses
  IdCustomHTTPServer, HttpCommand;

type
  THttpCommandRoot = class(THttpCommand)
  public
    class procedure Execute(AServer: TObject; var AReponseText: string); override;
  end;
  THttpCommandRootClass = class of THttpCommandRoot;

implementation

uses
  TypesConsts, Classes, HttpCommandStatus, Session;
{ THttpCommandRoot }

class procedure THttpCommandRoot.Execute(AServer: TObject; var AReponseText: string);
var
  TheString: AnsiString;
begin
  TheString :=             '<HTML>' + ReturnLF;
  TheString := TheString + '  <TABLE>' + ReturnLF;
  TheString := TheString + '    <TR><TD><a href="/status">Status</a></TD></TR>' + ReturnLF;
  TheString := TheString + '    <TR><TD><a href="/auto/ver">Version</a></TD></TR>' + ReturnLF;
  TheString := TheString + '    <TR><TD>&nbsp;</TD></TR>' + ReturnLF;
  TheString := TheString + '  </TABLE>' + ReturnLF;
  TheString := TheString + AnsiString(THttpCommandStatus.GetStatusText(AServer));
  TheString := TheString + '</HTML>';
  (Session.glbSession.HttpResponseInfo.ContentStream as TMemorystream).Write(TheString[1], Length(TheString));
  Session.glbSession.HttpResponseInfo.ContentType := 'text/html';
end;

end.