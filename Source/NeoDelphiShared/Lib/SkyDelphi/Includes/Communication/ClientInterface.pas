unit ClientInterface;

interface

uses
  Classes, SkyHttpClient, Communication, ProxyInformation;

type
  TClientInterface = class
  private
    FHttpClient: TSkyHttpClient;
    FResponse: TGenericResponse;
    FMaintainSession: Boolean;

    function GetConnected: Boolean;
    procedure SetRequest(const Value: TRequest);
    procedure SetResponse(const Value: TGenericResponse);
    function GetSession: string;

  protected
    FRequest: TRequest;
    property Request: TRequest write SetRequest;
    property Response: TGenericResponse read FResponse write SetResponse;

    class function GetKeepAlive: Boolean; virtual;
    class function ServerAddress: string; virtual; abstract;
    class function ServerPort: Word; virtual;
    procedure SetConnectTimeout(AnInterval: Integer);
    function ReadRequestFromStream(AStream: TMemoryStream): TGenericResponse; virtual; abstract;
    function RequestPage: string; virtual; abstract;
    procedure SetProxyInformation(const Value: TProxyInformation);
    procedure WriteRequestToStream(AStream: TMemoryStream; ARequest: TRequest); virtual; abstract;
  public
    constructor Create; reintroduce; virtual;
    destructor Destroy; override;

    procedure Disconnect;
    function ExecuteRequest(ARequest: TRequest): TGenericResponse; virtual;
    procedure ExecuteCustomRequest(const ARequestPage: string; ARequestStream, AResponseStream: TStream);
    procedure RegisterSession(const ASessionId: string);
    procedure SetConnectionInfo;
    property Connected: Boolean read GetConnected;
    property MaintainSession: Boolean read FMaintainSession write FMaintainSession;
    property Session: string read GetSession;
  end;
  TClientInterfaceClass = class of TClientInterface;
  PClientInterface = ^TClientInterface;

implementation

uses
  ExceptionClasses, Entity, SysUtils;

{ TClientInterface }

constructor TClientInterface.Create;
begin
  inherited Create;
  FHttpClient := TSkyHttpClient.Create;
  FHttpClient.KeepAlive := GetKeepAlive;
  FMaintainSession := True;
  SetConnectionInfo;
end;

destructor TClientInterface.Destroy;
begin
  FHttpClient.Free;
  FRequest.Free;
  FResponse.Free;
  inherited;
end;

procedure TClientInterface.Disconnect;
begin
  FHttpClient.Disconnect;
end;

function TClientInterface.ExecuteRequest(ARequest: TRequest): TGenericResponse;
var
  TheResponseCode: Integer;
begin
  Request := ARequest;
  FHttpClient.SendStream.Clear;
  WriteRequestToStream(FHttpClient.SendStream, ARequest);
  try
    FHttpClient.PostStream(RequestPage);
  except
    raise ESkyServerCommunicationError.Create(Self, 'ExecuteRequest');
  end;
  TheResponseCode := FHttpClient.LastResponseCode;
  if TheResponseCode <> 200 then
  begin
    Disconnect;
    raise ESkyServerCommunicationError.Create(Self, 'ExecuteRequest');
  end;

  Response := ReadRequestFromStream(FHttpClient.ReceivedStream);
  if (TEntity(Response) is TResponseError) then
    if (TEntity(Response) is TResponseServerException) then
      raise ((TEntity(Response) as TResponseServerException).Exception.CreateACopy)
    else
      raise ESkyCustomError.Create(Self, 'ExecuteRequest', TResponseError(Response).GetTranslatedMessage);
  Result := Response as TResponse;
end;

procedure TClientInterface.ExecuteCustomRequest(const ARequestPage: string;
  ARequestStream, AResponseStream: TStream);
var
  TheResponseCode: Integer;
begin
  Request := nil;
  Response := nil;
  FHttpClient.SendStream.Clear;
  if ARequestStream <> nil then
  begin
    FHttpClient.SendStream.CopyFrom(ARequestStream, 0);
    FHttpClient.PostStream(ARequestPage);
  end
  else
    FHttpClient.GetStream(ARequestPage);

  TheResponseCode := FHttpClient.LastResponseCode;
  if TheResponseCode <> 200 then
  begin
    Disconnect;
    raise ESkyServerCommunicationError.Create(Self, 'ExecuteCustomRequest');
  end;

  AResponseStream.Size := 0;
  AResponseStream.CopyFrom(FHttpClient.ReceivedStream, 0);
end;


function TClientInterface.GetConnected: Boolean;
begin
  Result := FHttpClient.Active;
end;

class function TClientInterface.GetKeepAlive: Boolean;
begin
  Result := True;
end;

function TClientInterface.GetSession: string;
begin
  Result := string(FHttpClient.Session);
end;

procedure TClientInterface.RegisterSession(const ASessionId: string);
begin
  FHttpClient.RegisterSession(ASessionId);
end;

class function TClientInterface.ServerPort: Word;
begin
  Result := 80;
end;

procedure TClientInterface.SetConnectionInfo;
begin
  if ServerPort <> 80 then
    FHttpClient.Server:= 'http://' + ServerAddress + ':' + IntToStr(ServerPort)
  else
    FHttpClient.Server := 'http://' + ServerAddress;
end;

procedure TClientInterface.SetConnectTimeout(AnInterval: Integer);
begin
  FHttpClient.ConnectTimeout := AnInterval;
end;

procedure TClientInterface.SetProxyInformation(const Value: TProxyInformation);
begin
  if Value.UseProxy then
  begin
    FHttpClient.ProxyParams.ProxyServer := Value.ProxyServer;
    FHttpClient.ProxyParams.ProxyPort := Value.ProxyPort;
    FHttpClient.ProxyParams.ProxyUsername := Value.ProxyUser;
    FHttpClient.ProxyParams.ProxyPassword := Value.ProxyPassword;
    FHttpClient.ProxyParams.BasicAuthentication := FHttpClient.ProxyParams.ProxyUsername <> '';
  end
  else
    FHttpClient.ProxyParams.ProxyServer := '';
end;

procedure TClientInterface.SetRequest(const Value: TRequest);
begin
  if FRequest = Value then
    Exit;
  FRequest.Free;
  FRequest := Value;
end;

procedure TClientInterface.SetResponse(const Value: TGenericResponse);
begin
  FResponse.Free;
  FResponse := Value;
end;

end.
