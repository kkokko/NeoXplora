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
    function GetDataFolder: string;
    function GetServicePort: Word;
    function GetLogAllOperations: Boolean;
    function GetSessionExpire: Integer;
    function GetTempFolder: string;
    procedure ReadDBConnectionSettings;
    procedure ReadTranslationFiles;
    procedure SetTranslationPath(const Value: string);
    procedure WriteDBConnectionSettings;
    procedure SetServicePort(const Value: Word);
    procedure SetLogAllOperations(const Value: Boolean);
    function GetTranslationPath: string;
    function GetUseRepValidator: Boolean;
    procedure SetUseRepValidator(const Value: Boolean);
  public
    constructor Create;
    destructor Destroy; override;

    class function GetInstance: TAppSettings;
    class procedure EndInstance;

    property AppFolder: string read GetAppFolder;
    property DataFolder: string read GetDataFolder;
    property DBConnectionSettings: TDBConnectionSettings read FDBConnectionSettings write FDBConnectionSettings;
    property LogAllOperations: Boolean read GetLogAllOperations write SetLogAllOperations;
    property ServicePort: Word read GetServicePort write SetServicePort;
    property SessionExpire: Integer read GetSessionExpire;
    property StartTime: TDateTime read FStartTime;
    property TempFolder: string read GetTempFolder;
    property TranslationPath: string read GetTranslationPath write SetTranslationPath;
    property UseRepValidator: Boolean read GetUseRepValidator write SetUseRepValidator;
  end;

implementation

uses
  TypesFunctions, SysUtils, Forms, Languages;

const
  APP_SECTION_SETTINGS = 'Settings';
  APP_SECTION_DATABASE = 'Database';
  APP_SECTION_TRANSLATIONS = 'Translations';
  APP_SECTION_APPLOGIC = 'ApplicationLogic';
  APP_SECTION_MAILSERVER = 'MailServer';

var
  _AppSettings: TAppSettings = nil;

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

function TAppSettings.GetDataFolder: string;
begin
  Result := GetAppFolder;
end;

function TAppSettings.GetLogAllOperations: Boolean;
begin
  Result := FIniFile.ReadBool(APP_SECTION_SETTINGS, 'LogAllOperations', True);
end;

function TAppSettings.GetServicePort: Word;
begin
  Result := FIniFile.ReadInteger(APP_SECTION_SETTINGS, 'ServicePort', 2586);
end;

function TAppSettings.GetSessionExpire: Integer;
begin
  Result := 86400;
end;

function TAppSettings.GetTempFolder: string;
begin
  Result := GetDataFolder + 'Log\'
end;

function TAppSettings.GetTranslationPath: string;
begin
  Result := IncludeTrailingPathDelimiter(FIniFile.ReadString(APP_SECTION_TRANSLATIONS, 'LanguageListPath', ''));
end;

function TAppSettings.GetUseRepValidator: Boolean;
begin
  Result := FIniFile.ReadBool(APP_SECTION_SETTINGS, 'UseRepValidator', True);
end;

procedure TAppSettings.ReadDBConnectionSettings;
begin
  FDBConnectionSettings.ServerName := FIniFile.ReadString(APP_SECTION_DATABASE, 'ServerName', '127.0.0.1');
  FDBConnectionSettings.UserName := FIniFile.ReadString(APP_SECTION_DATABASE, 'UserName', 'root');
  FDBConnectionSettings.Password := FIniFile.ReadString(APP_SECTION_DATABASE, 'Password', 'pw');
  FDBConnectionSettings.Database := FIniFile.ReadString(APP_SECTION_DATABASE, 'Database', 'db179668_ai2');
end;

procedure TAppSettings.ReadTranslationFiles;
begin
  TranslationPath := FIniFile.ReadString(APP_SECTION_TRANSLATIONS, 'LanguageListPath', GetAppFolder + 'Translations');
  TLanguages.Instance.LoadFromPath(TranslationPath);
end;

procedure TAppSettings.SetLogAllOperations(const Value: Boolean);
begin
  FIniFile.WriteBool(APP_SECTION_SETTINGS, 'LogAllOperations', Value);
end;

procedure TAppSettings.SetServicePort(const Value: Word);
begin
  FIniFile.WriteInteger(APP_SECTION_SETTINGS, 'ServicePort', Value);
end;

procedure TAppSettings.SetTranslationPath(const Value: string);
begin
  FIniFile.WriteString(APP_SECTION_TRANSLATIONS, 'LanguageListPath', Value);
end;

procedure TAppSettings.SetUseRepValidator(const Value: Boolean);
begin
  FIniFile.WriteBool(APP_SECTION_SETTINGS, 'UseRepValidator', Value);
end;

procedure TAppSettings.WriteDBConnectionSettings;
begin
  FIniFile.WriteString(APP_SECTION_DATABASE, 'ServerName', FDBConnectionSettings.ServerName);
  FIniFile.WriteString(APP_SECTION_DATABASE, 'UserName', FDBConnectionSettings.UserName);
  FIniFile.WriteString(APP_SECTION_DATABASE, 'Database', FDBConnectionSettings.Database);
  FIniFile.WriteString(APP_SECTION_DATABASE, 'Password', FDBConnectionSettings.Password);
end;

constructor TAppSettings.Create;
var
  TheGuid: TGUID;
begin
  inherited Create;
  FStartTime := Now;
  CreateGUID(TheGuid);
  FIniFile := TIniFile.Create(ChangeFileExt(Application.ExeName, '.ini'));
  TranslationPath := TranslationPath;
  UseRepValidator := UseRepValidator;
  SetLogAllOperations(GetLogAllOperations);
  ReadDBConnectionSettings;
  ReadTranslationFiles;
  WriteDBConnectionSettings;
  ServicePort := ServicePort;
  if not DirectoryExists(GetTempFolder) then
    ForceDirectories(GetTempFolder);
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

end.
