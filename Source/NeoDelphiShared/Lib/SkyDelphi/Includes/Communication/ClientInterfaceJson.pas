unit ClientInterfaceJson;

interface

uses
  ClientInterface, Classes, Communication;

type
  TClientInterfaceJson = class(TClientInterface)
  protected
    class function GetKeepAlive: Boolean; override;
    function ReadRequestFromStream(AStream: TMemoryStream): TGenericResponse; override;
    function RequestPage: string; override;
    procedure WriteRequestToStream(AStream: TMemoryStream; ARequest: TRequest); override;
  end;

implementation

uses
  EntityJsonReader, EntityJSonWriter;

{ TClientInterfaceJson }

class function TClientInterfaceJson.GetKeepAlive: Boolean;
begin
  Result := False;
end;

function TClientInterfaceJson.ReadRequestFromStream(AStream: TMemoryStream): TGenericResponse;
begin
  Result := TEntityJSonReader.ReadEntity(AStream) as TGenericResponse;
end;

function TClientInterfaceJson.RequestPage: string;
begin
  Result := 'Request.php'
end;

procedure TClientInterfaceJson.WriteRequestToStream(AStream: TMemoryStream; ARequest: TRequest);
begin
  TEntityJsonWriter.WriteEntity(AStream, ARequest);
end;

end.
