unit ClientInterfaceXml;

interface

uses
  ClientInterface, Classes, Communication;

type
  TClientInterfaceXml = class(TClientInterface)
  protected
    function ReadRequestFromStream(AStream: TMemoryStream): TGenericResponse; override;
    function RequestPage: string; override;
    function UseBlockingRequest: Boolean; virtual;
    procedure WriteRequestToStream(AStream: TMemoryStream; ARequest: TRequest); override;
  end;

implementation

uses
  EntityXmlWriter, EntityXmlReader;

{ TClientInterfaceXml }

function TClientInterfaceXml.ReadRequestFromStream(AStream: TMemoryStream): TGenericResponse;
begin
  Result := TEntityXmlReader.ReadEntity(AStream, TGenericResponse) as TGenericResponse
end;

function TClientInterfaceXml.RequestPage: string;
begin
  if not UseBlockingRequest then
    Result := 'Request.xml'
  else
    Result := 'RequestBlocking.xml'
end;

function TClientInterfaceXml.UseBlockingRequest: Boolean;
begin
  Result := False;
end;

procedure TClientInterfaceXml.WriteRequestToStream(AStream: TMemoryStream; ARequest: TRequest);
begin
  TEntityXmlWriter.WriteEntity(AStream, ARequest)
end;

end.
