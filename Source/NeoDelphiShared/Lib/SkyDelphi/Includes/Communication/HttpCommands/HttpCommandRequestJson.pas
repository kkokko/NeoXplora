unit HttpCommandRequestJson;

interface

uses
  HttpCommandRequestBase;

type
  THttpCommandRequestJson = class(THttpCommandRequestBase)
  public
    class procedure Execute(AServer: TObject; var AReponseText: string); override;
  end;
  THttpCommandRequestJsonClass = class of THttpCommandRequestJson;

implementation

uses
  Classes, EntityJsonReader, EntityJsonWriter, Session;

{ THttpCommandRequestJson }

class procedure THttpCommandRequestJson.Execute(AServer: TObject; var AReponseText: string);
begin
  RunCommand(AServer, 'json', TEntityJsonReader, TEntityJsonWriter);
  Session.glbSession.HttpResponseInfo.ContentType := 'application/json';
end;

end.