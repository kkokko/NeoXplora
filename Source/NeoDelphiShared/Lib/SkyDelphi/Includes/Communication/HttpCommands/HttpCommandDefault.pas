unit HttpCommandDefault;

interface

uses
  IdCustomHTTPServer, HttpCommand;

type
  THttpCommandDefault = class(THttpCommand)
  public
    class procedure Execute(AServer: TObject; var AReponseText: string); override;
  end;
  THttpCommandDefaultClass = class of THttpCommandDefault;

implementation

uses
  ExceptionClasses, Session;

{ THttpCommandDefault }

class procedure THttpCommandDefault.Execute(AServer: TObject; var AReponseText: string);
begin
  raise ESkyInvalidRequest.Create(nil, 'THttpCommandDefault.Execute',
    Session.glbSession.HttpRequestInfo.Document);
end;

end.
