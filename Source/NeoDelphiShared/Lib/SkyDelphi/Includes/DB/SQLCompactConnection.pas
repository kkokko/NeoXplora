unit SQLCompactConnection;

interface

uses
  BaseConnection, Entity, TypesConsts;

type
  TSQLCompactConnection= class(TBaseConnection)
  private
    FDatabase: string;
    FPassword: string;
  protected
    procedure DropTableDependencies(const ATableName: string); overload; override;
    class function GetFieldTypeTBlobType: string; override;
    class function GetFieldTypeTDate: string; override;
    class function GetFieldTypeTPasswordString: string; override;
    class function GetFieldTypeTTime: string; override;
    class function GetFieldTypeUnicode: string; override;

    procedure SetupConnection; override;
  public
    procedure CreateDatabase(const AName: string);
    procedure CreateDBCompositeTable(SomeEntityClasses: array of TEntityClass); override;
    procedure DropDatabase(const AName: string = '');
    procedure DropDBTable(const ATableName: string); overload; override;
    function InsertEntity(AnEntity: TEntity; AForceId: Boolean = False): TId; override;
    procedure SetConnectionInfo(const ADatabase, APassword: string);

    property Database: string read FDatabase write FDatabase;
    property Password: string read FPassword write FPassword;
  end;

implementation

uses
  ActiveX, ComObj, SysUtils, BaseQuery, VersionableEntity, DBSQLQuery, StringArray, CountData, SQLServerUniProvider,
  ExceptionClasses, Windows, DB;

function QuerySelectTableCount(const ATableName: string): TDBSQLQuery;
begin
  Result.Name := 'QuerySelectOneToManyForId';
  Result.Query := TStringArray.FromArray([
    'SELECT COUNT(*) as [',
    TCountData.EntityToken_Number.SQLToken,
    '] FROM INFORMATION_SCHEMA.TABLES where TABLE_NAME = ''' + ATableName + ''';'
  ]);
end;

procedure TSQLCompactConnection.CreateDatabase(const AName: string);
var
  TheCatalog: OleVariant;
  TheString: string;
begin
  if not FileExists(AName) then
  begin
    TheCatalog := CreateOleObject('ADOX.Catalog');
    TheString := 'Provider=Microsoft.SQLSERVER.CE.OLEDB.4.0;Data Source=' + AName + ';';
    if FPassword <> '' then
      TheString := TheString + 'ssce:database password=' + FPassword;
    TheCatalog.Create(TheString);
  end;
  FDatabase := AName;
  OpenConnection;
end;

procedure TSQLCompactConnection.CreateDBCompositeTable(SomeEntityClasses: array of TEntityClass);
var
  I, TheFieldCount: Integer;
  TheTableName, TheString: string;
  TheDBFieldTypes: TKeyStringValueArray;
begin
  if Length(SomeEntityClasses) = 0 then
    Exit;
  TheTableName := GetTableMapping(SomeEntityClasses[0]);
  if TBaseQuery.SelectCount(Self, QuerySelectTableCount(TheTableName)) > 0 then
    Exit;
  TheDBFieldTypes := GetDBFieldTypes(SomeEntityClasses);
  TheString := 'create table ['+ TheTableName +'](' + ReturnLF;
  TheFieldCount := TheDBFieldTypes.Count - 1;
  for I := 0 to TheFieldCount do
  begin
    TheString := TheString + '    ' + TheDBFieldTypes[I].Key;
    TheString := TheString + ' ' + TheDBFieldTypes[I].Value;
    if AnsiSameText(TheDBFieldTypes[I].Key, '[Id]') then
      TheString := TheString + ' identity(1,1) primary key not null';
    if I <> TheFieldCount then
      TheString := TheString + ',' + ReturnLF;
  end;
  TheString := TheString + ');';
  TBaseQuery.DoExecuteQuery(Self, TheString);
end;

procedure TSQLCompactConnection.DropDatabase(const AName: string);
var
  TheName: string;
begin
  TheName := AName;
  if AName = '' then
    TheName := FDatabase;
  if TheName = FDatabase then
  begin
    Connection.Close;
    FDatabase := '';
  end;
  if not DeleteFile(PChar(AName)) then
    raise ESkyCannotDeleteFile.Create(Self, 'DropDatabase', AName);
end;

procedure TSQLCompactConnection.DropDBTable(const ATableName: string);
begin
  DropTableDependencies(ATableName);
  if TBaseQuery.SelectCount(Self, QuerySelectTableCount(ATableName)) = 0 then
    Exit;
  TBaseQuery.DoExecuteQuery(Self, 'drop table ['+ ATableName +'];');
end;

procedure TSQLCompactConnection.DropTableDependencies(const ATableName: string);
var
  TheQuery: TBaseQuery;
begin
  TheQuery := TBaseQuery.Create(Self);
  try
    TheQuery.SQL.Add('SELECT CONSTRAINT_TABLE_NAME as tName, CONSTRAINT_NAME as kName');
    TheQuery.SQL.Add('FROM INFORMATION_SCHEMA.REFERENTIAL_CONSTRAINTS');
    TheQuery.SQL.Add('WHERE UNIQUE_CONSTRAINT_TABLE_NAME = ''' + ATableName + '''');
    TheQuery.Open;
    while not TheQuery.Eof do
    begin
      TBaseQuery.DoExecuteQuery(Self, 'ALTER TABLE [' + TheQuery.FieldByName('tName').AsString  + ']' +
        ' DROP CONSTRAINT [' + TheQuery.FieldByName('kName').AsString + '];');
      TheQuery.Next;
    end;
  finally
    TheQuery.Free;
  end;
end;

class function TSQLCompactConnection.GetFieldTypeTBlobType: string;
begin
  Result := '[image]';
end;

class function TSQLCompactConnection.GetFieldTypeTDate: string;
begin
  Result := '[datetime]';
end;

class function TSQLCompactConnection.GetFieldTypeTPasswordString: string;
begin
  Result := '[nvarchar](50)';
end;

class function TSQLCompactConnection.GetFieldTypeTTime: string;
begin
  Result := '[datetime]';
end;

class function TSQLCompactConnection.GetFieldTypeUnicode: string;
begin
  Result := '[nvarchar](50)';
end;

procedure TSQLCompactConnection.SetupConnection;
begin
  Connection.ProviderName := 'SQL SERVER';
  Connection.SpecificOptions.Values['OLEDBProvider'] := 'prCompact';
  Connection.Database := FDatabase;
  Connection.Password := FPassword;
end;

function TSQLCompactConnection.InsertEntity(AnEntity: TEntity; AForceId: Boolean): TId;
var
  TheFieldInfos: TFieldInfoArray;
  TheQuery: TBaseQuery;
  I: Integer;
begin
  TheQuery := TBaseQuery.Create(Self);
  try
    if AnEntity is TVersionableEntity then
      (AnEntity as TVersionableEntity).Version := (AnEntity as TVersionableEntity).Version + 1;
    TheQuery.SQL.Add('insert into [' + GetTableMapping(AnEntity.ClassType) + '](' + DBFieldList(AnEntity.ClassType, not AForceId) + ')');
    if AnEntity.HasField('Id') then
    TheQuery.SQL.Add(' values ( ' + DBValueList(AnEntity, not AForceId, TheFieldInfos) + ');');
    for I := 0 to TheQuery.Params.Count - 1 do
      TheQuery.Params.Items[I].DataType := ftVariant;
    TheQuery.SetQueryParams(TheFieldInfos, AnEntity);
    TheQuery.ExecSQL;
    if AnEntity.HasField('Id') then
    begin
      TheQuery.SQL.Text := 'select @@IDENTITY as LastId;';
      TheQuery.Open;
      Result := TheQuery.FieldByName('LastId').Value;
    end
    else
      Result := IdNil;
  finally
    TheQuery.Free;
  end;
end;

procedure TSQLCompactConnection.SetConnectionInfo(const ADatabase, APassword: string);
begin
  FPassword := APassword;
  CreateDatabase(ADatabase);
end;

end.
