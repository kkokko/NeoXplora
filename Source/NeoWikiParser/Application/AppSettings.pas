unit AppSettings;

interface

uses
  IniFiles;

type
{$Region '  TDBConnectionSettings'}
  TDBConnectionSettings = record
    ServerName: string;
    UserName: string;
    Password: string;
    Database: string;
  end;
{$EndRegion}
  TAppSettings = class(TObject)
  private
    FDBConnectionSettings: TDBConnectionSettings;
    FIniFile: TIniFile;
    FStartTime: TDateTime;
    function GetAppFolder: string;
    procedure ReadDBConnectionSettings;
    procedure WriteDBConnectionSettings;
    function GetWikiFile: string;
    procedure SetWikiFile(const Value: string);
    function GetProcessingThreads: Integer;
    procedure SetProcessingThreads(const Value: Integer);
  public
    constructor Create;
    destructor Destroy; override;
    class function GetInstance: TAppSettings;
    class procedure EndInstance;

    property AppFolder: string read GetAppFolder;
    property DBConnectionSettings: TDBConnectionSettings read FDBConnectionSettings write FDBConnectionSettings;
    property ProcessingThreads: Integer read GetProcessingThreads write SetProcessingThreads;
    property StartTime: TDateTime read FStartTime;
    property WikiFile: string read GetWikiFile write SetWikiFile;
  end;

function Settings: TAppSettings;

implementation

uses
  TypesFunctions, SysUtils, Forms, Languages;

const
  APP_SECTION_SETTINGS = 'Settings';
  APP_SECTION_FILES = 'Files';
  APP_SECTION_DATABASE = 'Database';

var
  _AppSettings: TAppSettings = nil;

function Settings: TAppSettings;
begin
  if _AppSettings = nil then
    _AppSettings := TAppSettings.Create;
  Result := _AppSettings;
end;

{ TAppSettings }

function TAppSettings.GetAppFolder: string;
begin
  Result := ExtractFilePath(Application.ExeName);
end;

class function TAppSettings.GetInstance: TAppSettings;
begin
  if not Assigned(_AppSettings) then
    _AppSettings :=  Create;
  Result := _AppSettings;
end;

function TAppSettings.GetProcessingThreads: Integer;
begin
  Result := FIniFile.ReadInteger(APP_SECTION_SETTINGS, 'ProcessingThreads', 4);
end;

function TAppSettings.GetWikiFile: string;
begin
  Result := FIniFile.ReadString(APP_SECTION_FILES, 'WikiFile', AppFolder + 'enwiki-latest-pages-articles.xml');
end;

procedure TAppSettings.ReadDBConnectionSettings;
begin
  FDBConnectionSettings.ServerName := FIniFile.ReadString(APP_SECTION_DATABASE, 'ServerName', '127.0.0.1');
  FDBConnectionSettings.UserName := FIniFile.ReadString(APP_SECTION_DATABASE, 'UserName', 'root');
  FDBConnectionSettings.Password := 'login141';
  FDBConnectionSettings.Database := FIniFile.ReadString(APP_SECTION_DATABASE, 'Database', 'db179668_ai2_dev');
end;

procedure TAppSettings.SetProcessingThreads(const Value: Integer);
begin
  FIniFile.WriteInteger(APP_SECTION_SETTINGS, 'ProcessingThreads', Value);
end;

procedure TAppSettings.SetWikiFile(const Value: string);
begin
  FIniFile.WriteString(APP_SECTION_FILES, 'WikiFile', Value);
end;

procedure TAppSettings.WriteDBConnectionSettings;
begin
  FIniFile.WriteString(APP_SECTION_DATABASE, 'ServerName', FDBConnectionSettings.ServerName);
  FIniFile.WriteString(APP_SECTION_DATABASE, 'UserName', FDBConnectionSettings.UserName);
  FIniFile.WriteString(APP_SECTION_DATABASE, 'Database', FDBConnectionSettings.Database);
end;

constructor TAppSettings.Create;
var
  TheGuid: TGUID;
begin
  inherited Create;
  FStartTime := Now;
  CreateGUID(TheGuid);
  FIniFile := TIniFile.Create(ChangeFileExt(Application.ExeName, '.ini'));
  ProcessingThreads := ProcessingThreads;
  WikiFile := WikiFile;
  ReadDBConnectionSettings;
  WriteDBConnectionSettings;
end;

destructor TAppSettings.Destroy;
begin
  FIniFile.Free;
  inherited;
end;

class procedure TAppSettings.EndInstance;
begin
  TypesFunctions.FreeAndNil(_AppSettings);
end;

initialization

finalization
  _AppSettings.Free;

end.
