unit ProxyInformation;

interface

uses
  Entity;

type
  TProxyInformation = class(TEntity)
  private
    FProxyPort: Word;
    FProxyPassword: string;
    FProxyUser: string;
    FProxyServer: string;
    FUseProxy: Boolean;
  published
    property UseProxy: Boolean read FUseProxy write FUseProxy;
    property ProxyServer: string read FProxyServer write FProxyServer;
    property ProxyPort: Word read FProxyPort write FProxyPort;
    property ProxyUser: string read FProxyUser write FProxyUser;
    property ProxyPassword: string read FProxyPassword write FProxyPassword;
  end;

implementation

initialization
  TProxyInformation.RegisterEntityClass;

end.