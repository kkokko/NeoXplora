unit AppHttpCommandRequestApiXml;

interface

uses
  HttpCommandRequestBase;

type
  TAppHttpCommandRequestApiXml = class(THttpCommandRequestBase)
  protected
    class function LogErrors: Boolean; override;
    class function LogAllRequests: Boolean; override;
    class function TempFolder: string; override;
  public
    class procedure Execute(AServer: TObject; var AReponseText: string); override;
  end;
  TAppHttpCommandRequestApiXmlClass = class of TAppHttpCommandRequestApiXml;

implementation

uses
  AppUnit, Session, AppEntityXmlReader2, AppEntityXmlWriter2;

{ TAppHttpCommandRequestJson }

class procedure TAppHttpCommandRequestApiXml.Execute(AServer: TObject; var AReponseText: string);
begin
  RunCommand(AServer, 'xml', TAppEntityXmlReader2, TAppEntityXmlWriter2);
  Session.glbSession.HttpResponseInfo.ContentType := 'application/json';
end;

class function TAppHttpCommandRequestApiXml.LogAllRequests: Boolean;
begin
  Result := App.Settings.LogAllOperations;
end;

class function TAppHttpCommandRequestApiXml.LogErrors: Boolean;
begin
  Result := True;
end;

class function TAppHttpCommandRequestApiXml.TempFolder: string;
begin
  Result := App.Settings.TempFolder;
end;

end.
