unit AppUnit;

interface

uses
  AppSettings, WebInterfaceHandler, MySQLServerConnection;

type
  TApp = class(TObject)
  private
    function GetNewConnection: TMySQLServerConnection;
    function GetSettings: TAppSettings;
    function GetSQLConnection: TMySQLServerConnection;
    function GetWebInterfaceHandler: TWebInterfaceHandler;
  public
    class function GetInstance: TApp;
    class procedure EndInstance;
    procedure CloseSession;
    procedure CreateDefaultDatabaseConnection;
    procedure RemoveDefaultDatabaseConnection;
    procedure CloseDefaultDatabaseConnection;
    procedure RecreateTables;
    property Settings: TAppSettings read GetSettings;
    property SQLConnection: TMySQLServerConnection read GetSQLConnection;
    property WebInterfaceHandler: TWebInterfaceHandler read GetWebInterfaceHandler;
  end;

var
  App: TApp;

implementation

uses
  TypesFunctions, Entity, Session, AppClientSession;

{ TApp }

class function TApp.GetInstance: TApp;
begin
  if not Assigned(App) then
    App := TApp.Create;
  Result := App;
end;

function TApp.GetNewConnection: TMySQLServerConnection;
begin
  Result := TMySQLServerConnection.Create;
  with Settings.DBConnectionSettings do
    Result.SetConnectionInfo(ServerName, UserName, Password, Database);
end;

procedure TApp.CloseDefaultDatabaseConnection;
begin
  if Session.glbSession.Data = nil then
    Exit;
  ((Session.glbSession.Data as TAppClientSession).DatabaseConnection as TMySQLServerConnection).CloseConnection;
end;

procedure TApp.CloseSession;
begin
  if Session.glbSession.HttpRequestInfo <> nil then
    WebInterfaceHandler.HandleSessionEnd(Session.glbSession.HttpRequestInfo.Session)
end;

procedure TApp.CreateDefaultDatabaseConnection;
begin
  if Session.glbSession.Data <> nil then
    Exit;
  Session.glbSession.Data := TAppClientSession.Create('');
  (Session.glbSession.Data as TAppClientSession).DatabaseConnection := GetNewConnection;
end;

procedure TApp.RemoveDefaultDatabaseConnection;
begin
  Session.glbSession.Data.Free;
  Session.glbSession.Data := nil;
end;

function TApp.GetSQLConnection: TMySQLServerConnection;
var
  TheSession: string;
begin
  if Session.glbSession.Data = nil then
  begin
    TheSession := WebInterfaceHandler.HttpS.CreateSession(Session.glbSession.HttpContext,
        Session.glbSession.HttpResponseInfo, Session.glbSession.HttpRequestInfo).SessionID;
    Session.glbSession.Data := App.WebInterfaceHandler.AddSession(TheSession);
    (Session.glbSession.Data as TAppClientSession).DatabaseConnection := GetNewConnection;
  end;
  Result := (Session.glbSession.Data as TAppClientSession).DatabaseConnection as TMySQLServerConnection;
end;

class procedure TApp.EndInstance;
begin
  TWebInterfaceHandler.EndInstance;
  TAppSettings.EndInstance;
  FreeAndNil(App);
end;

function TApp.GetSettings: TAppSettings;
begin
  Result := TAppSettings.GetInstance;
end;

function TApp.GetWebInterfaceHandler: TWebInterfaceHandler;
begin
  Result := TWebInterfaceHandler.GetInstance;
end;

procedure TApp.RecreateTables;
begin
//  SQLConnection.DropAndRecreateTable(TProjectXUser);
  TEntity.RegisterAllDatabaseKeys(SQLConnection);
end;

end.
