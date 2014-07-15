unit HttpCommand;

interface

uses
  Classes;

type
  THttpCommand = class
  protected
    class procedure ReturnError(AnErrorCode: Integer; var AReponseText: string; const TheResponseString: string);
    class procedure SaveStreamToFile(AStream: TMemoryStream; const AName: string);
  public
    class procedure Execute(AServer: TObject; var AReponseText: string); virtual; abstract;
  end;
  THttpCommandClass = class of THttpCommand;

implementation

uses
  Session;

{ THttpCommand }

class procedure THttpCommand.ReturnError(AnErrorCode: Integer;
  var AReponseText: string; const TheResponseString: string);
begin
  Session.glbSession.HttpResponseInfo.ResponseNo := 404;
  Session.glbSession.HttpResponseInfo.ContentStream.Free;
  Session.glbSession.HttpResponseInfo.ContentStream := nil;
  AReponseText := TheResponseString;
end;

class procedure THttpCommand.SaveStreamToFile(AStream: TMemoryStream; const AName: string);
begin
  AStream.Position := 0;
  try
    AStream.SaveToFile(AName);
  except // eat the error
  end;
end;

end.
