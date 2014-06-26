unit ClientInterfaceJsonEnc;

interface

uses
  ClientInterface, Classes, Communication;

type
  TClientInterfaceJsonEnc = class(TClientInterface)
  protected
    class function GetKeepAlive: Boolean; override;
    function GetPassword: ShortString; virtual; abstract;
    function ReadRequestFromStream(AStream: TMemoryStream): TGenericResponse; override;
    function RequestPage: string; override;
    procedure WriteRequestToStream(AStream: TMemoryStream; ARequest: TRequest); override;
  end;

implementation

uses
  EntityJsonReader, EntityJSonWriter, LbCipher, LbProc;

{ TClientInterfaceJsonEnc }

class function TClientInterfaceJsonEnc.GetKeepAlive: Boolean;
begin
  Result := False;
end;

function TClientInterfaceJsonEnc.ReadRequestFromStream(AStream: TMemoryStream): TGenericResponse;
var
  TheNewStream: TMemoryStream;
  TheKey: TKey64;
begin
  TheNewStream := TMemoryStream.Create;
  try
    GenerateLMDKey(TheKey, SizeOf(TheKey), GetPassword);
    DESEncryptStream(AStream, TheNewStream, TheKey, False);
    TheNewStream.Position := 0;
    Result := TEntityJSonReader.ReadEntity(TheNewStream) as TGenericResponse;
  finally
    TheNewStream.Free;
  end;
end;

function TClientInterfaceJsonEnc.RequestPage: string;
begin
  Result := 'RequestEnc.php'
end;

procedure TClientInterfaceJsonEnc.WriteRequestToStream(AStream: TMemoryStream; ARequest: TRequest);
var
  TheNewStream: TMemoryStream;
  TheKey: TKey64;
begin
  TheNewStream := TMemoryStream.Create;
  try
    TEntityJsonWriter.WriteEntity(TheNewStream, ARequest);
    GenerateLMDKey(TheKey, SizeOf(TheKey), GetPassword);
    TheNewStream.Position := 0;
    DESEncryptStream(TheNewStream, AStream, TheKey, True);
  finally
    TheNewStream.Free;
  end;
end;

end.
