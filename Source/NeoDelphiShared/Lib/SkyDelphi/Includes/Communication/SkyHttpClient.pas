unit SkyHttpClient;

interface

uses
  Classes, SysUtils, IdHTTP, IdComponent, ExtCtrls, IdHTTPHeaderInfo;

type
  TSkyHttpClientFileProgressProc = procedure(ASender: TObject; AWorkMode: TWorkMode; ACurrent, AMaximum: Int64) of object;

  TSkyHttpClient = class(TObject)
  private
    FSession: AnsiString;
    FSendStream, FReceivedStream: TMemoryStream;
    FOnDisconnectProc: TNotifyEvent;
    FOnFileProgressProc: TSkyHttpClientFileProgressProc;
    FServer: string;
    FLastFileSize: Int64;
    FKeepAlive: Boolean;

    procedure HandleWorkBegin(ASender: TObject; AWorkMode: TWorkMode; AWorkCountMax: Int64);
    procedure HandleWork(ASender: TObject; AWorkMode: TWorkMode; AWorkCount: Int64);
    procedure HandleWorkEnd(ASender: TObject; AWorkMode: TWorkMode);

    function GetActive: Boolean;
    function GetLastResponseCode: Integer;
    function GetProxyParams: TIdProxyConnectionInfo;
    function GetConnectTimeout: Integer;
    procedure SetConnectTimeout(const Value: Integer);
  protected
    FHttpC: TIdHTTP;
  public
    constructor Create; reintroduce;
    destructor Destroy; override;

    procedure DisConnect;

    procedure Clear;

    procedure GetStream(const Address: string);
    procedure PostStream(const Address: string);
    procedure RegisterSession(const ASessionId: string);

    property Active: Boolean read GetActive;
    property ConnectTimeout: Integer read GetConnectTimeout write SetConnectTimeout;
    property Server: string read FServer write FServer;
    property Session: AnsiString read FSession;
    property ProxyParams: TIdProxyConnectionInfo read GetProxyParams;
    property SendStream: TMemoryStream read FSendStream;
    property ReceivedStream: TMemoryStream read FReceivedStream;
    property OnDisconnect: TNotifyEvent read FOnDisconnectProc write FOnDisconnectProc;
    property OnFileProgress: TSkyHttpClientFileProgressProc read FOnFileProgressProc write FOnFileProgressProc;
    property LastResponseCode: Integer read GetLastResponseCode;
    property KeepAlive: Boolean read FKeepAlive write FKeepAlive;
  end;

implementation

uses
  IdGlobal{$IFNDEF VER230}, IdCookie{$ENDIF};

{ TSkyHttpClient }

procedure TSkyHttpClient.Clear;
begin
  SendStream.Clear;
  ReceivedStream.Clear;
end;

procedure TSkyHttpClient.RegisterSession(const ASessionId: string);
{$IFNDEF VER230}
var
  TheCookie: TIdCookieRFC2109;
{$ENDIF}
begin
  {$IFNDEF VER230}
  FSession := AnsiString(ASessionId);
  {$Region 'Work-around for Indy(RFC 2965) bug'}
  // work around for Indy(or RFC 2965) localhost-cookie bug (the HttpClient was not
  // allowed to receive cookies from hosts with no dots, or from 127.0.0.1)
  // Fix: force the session ID cookie to be created
  TheCookie := FHttpC.CookieManager.CookieCollection.Cookie['IDHTTPSESSIONID', '.' + FHttpC.URL.Host];
  if not Assigned(TheCookie) then
    TheCookie := FHttpC.CookieManager.CookieCollection.Cookie['IDHTTPSESSIONID', '.' + FHttpC.URL.Host + '.local'];
  if not Assigned(TheCookie) then
    TheCookie := FHttpC.CookieManager.CookieCollection.Cookie['IDHTTPSESSIONID', FHttpC.URL.Host];
  if not Assigned(TheCookie) then
  begin
    TheCookie := FHttpC.CookieManager.CookieCollection.Add;
    TheCookie.CookieText := 'IDHTTPSESSIONID=' + string(FSession) + '; path=/';
    if SameText(FHttpC.URL.Host, 'localhost') then
      TheCookie.Domain := '.' + FHttpC.URL.Host + '.local'
    else
      TheCookie.Domain := FHttpC.URL.Host;
    FHttpC.CookieManager.CookieCollection.AddCookie(TheCookie);
  end
  else
    TheCookie.CookieText := 'IDHTTPSESSIONID=' + string(FSession) + '; path=/';
  {$EndRegion}
  {$ENDIF}
end;

procedure TSkyHttpClient.SetConnectTimeout(const Value: Integer);
begin
  FHttpC.ConnectTimeout := Value;
end;

procedure TSkyHttpClient.DisConnect;
begin
  if not Active then
    Exit;
  FSession := '';
end;

constructor TSkyHttpClient.Create;
begin
  inherited Create;
  FSendStream := TMemoryStream.Create;
  FReceivedStream := TMemoryStream.Create;
  FKeepAlive := True;
  FHttpC := TIdHTTP.Create(nil);
  FHttpC.ConnectTimeout := 5000;
  FHttpC.AllowCookies := True;
  FHttpC.ReuseSocket := rsFalse;
  FHttpC.OnWorkBegin := HandleWorkBegin;
  FHttpC.OnWork := HandleWork;
  FHttpC.OnWorkEnd := HandleWorkEnd;
end;

destructor TSkyHttpClient.Destroy;
begin
  if Assigned(@FOnDisconnectProc) then
    FOnDisconnectProc(Self);
  FreeAndNil(FHttpC);
  FreeAndNil(FSendStream);
  FreeAndNil(FReceivedStream);
  inherited;
end;

function TSkyHttpClient.GetActive: Boolean;
begin
  Result := FSession <> '';
end;

function TSkyHttpClient.GetConnectTimeout: Integer;
begin
  Result := FHttpC.ConnectTimeout;
end;

function TSkyHttpClient.GetLastResponseCode: Integer;
begin
  Result := FHttpC.ResponseCode;
end;

function TSkyHttpClient.GetProxyParams: TIdProxyConnectionInfo;
begin
  Result := FHttpC.ProxyParams;
end;

procedure TSkyHttpClient.GetStream(const Address: string);
begin
  FReceivedStream.Clear;
  FHttpc.Get(FServer + '/' + Address, FReceivedStream);
  FReceivedStream.Position := 0;
end;

procedure TSkyHttpClient.HandleWorkBegin(ASender: TObject; AWorkMode: TWorkMode;
  AWorkCountMax: Int64);
begin
  FLastFileSize := AWorkCountMax;
  if Assigned(FOnFileProgressProc) then
    FOnFileProgressProc(ASender, AWorkMode, 0, FLastFileSize);
end;

procedure TSkyHttpClient.HandleWork(ASender: TObject; AWorkMode: TWorkMode;
  AWorkCount: Int64);
begin
  if Assigned(FOnFileProgressProc) then
    FOnFileProgressProc(ASender, AWorkMode, AWorkCount, FLastFileSize);
end;

procedure TSkyHttpClient.HandleWorkEnd(ASender: TObject; AWorkMode: TWorkMode);
begin
  if Assigned(FOnFileProgressProc) then
    FOnFileProgressProc(ASender, AWorkMode, FLastFileSize, FLastFileSize);
end;

procedure TSkyHttpClient.PostStream(const Address: string);
begin
  FReceivedStream.Clear;
  FSendStream.Position := 0;
  FHttpc.Post(FServer + '/' + Address , FSendStream, FReceivedStream);
  FReceivedStream.Position := 0;
end;

end.
