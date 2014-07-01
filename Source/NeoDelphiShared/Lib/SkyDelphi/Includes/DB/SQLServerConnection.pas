unit SQLServerConnection;

interface

uses
  Entity, TypesConsts, EntityMappingManager, DBSQLQuery, EntityMapping, BaseConnection, ADODB, BaseQuery;

type
  TSQLServerConnection= class(TBaseConnection)
  private
    FHost, FUser, FPassword, FDatabase: string;
    procedure SetIsolationLevelUsingQuery(AnIsolationLevel: TTransactionIsolationLevel);
  protected
    class function PwdEncryptMethod(const AString: string): string; override;
    procedure DropTableDependencies(const ATableName: string); overload; override;
    class function GetFieldTypeTBlobType: string; override;
    class function GetFieldTypeTDate: string; override;
    class function GetFieldTypeTPasswordString: string; override;
    class function GetFieldTypeTTime: string; override;
    class function GetFieldTypeUnicode: string; override;

    function GetParameterDatabaseName: string; virtual;
    function GetParameterHost: string;
    function GetUserName: string; virtual;
    function GetPassword: string; virtual;
    procedure SetupConnection; override;
  public
    class function TableExistsSQL(ATableName: string): string;

    procedure CreateDatabase(const AName: string);
    procedure CreateDBCompositeTable(SomeEntityClasses: array of TEntityClass); override;
    procedure DropDatabase(const AName: string = '');
    procedure DropDBTable(const ATableName: string); overload; override;
    function InsertEntity(AnEntity: TEntity; AForceId: Boolean = False): TId; override;
    procedure SetConnectionInfo(const AHost, AUser, APassword, ADatabase: string);
    procedure StartTransaction(AnIsolationLevel: TTransactionIsolationLevel); override;

    property Database: string read FDatabase write FDatabase;
    property Host: string read FHost write FHost;
    property Password: string read FPassword write FPassword;
    property User: string read FUser write FUser;
  end;

implementation

{ TSQLServerConnection }

uses
  SysUtils, BaseData, Variants, EntityManager, Classes, TypesFunctions, ExceptionClasses, SkyLists, TypInfo,
  VersionableEntity, Windows, DB, SQLServerUniProvider;

{TSQLServerConnection}

function TSQLServerConnection.GetUserName: string;
begin
  Result := FUser;
end;

class function TSQLServerConnection.GetFieldTypeTBlobType: string;
begin
  Result := '[varbinary](max)';
end;

class function TSQLServerConnection.GetFieldTypeTDate: string;
begin
  Result := '[date]';
end;

class function TSQLServerConnection.GetFieldTypeTPasswordString: string;
begin
  Result := '[varbinary](70)';
end;

class function TSQLServerConnection.GetFieldTypeTTime: string;
begin
  Result := '[time]';
end;

class function TSQLServerConnection.GetFieldTypeUnicode: string;
begin
  Result := '[nvarchar]' + '(50)';
end;

function TSQLServerConnection.GetParameterDatabaseName: string;
begin
  Result := FDatabase;
end;

function TSQLServerConnection.GetParameterHost: string;
begin
  Result := FHost;
end;

function TSQLServerConnection.GetPassword: string;
begin
  Result := FPassword;
end;

procedure TSQLServerConnection.SetConnectionInfo(const AHost, AUser, APassword, ADatabase: string);
begin
  FHost := AHost;
  FUser := AUser;
  FPassword := APassword;
  FDatabase := ADatabase;
  OpenConnection;
end;

procedure TSQLServerConnection.SetIsolationLevelUsingQuery(AnIsolationLevel: TTransactionIsolationLevel);
var
  TheQueryString: string;
begin
  TheQueryString := '';
  case AnIsolationLevel of
    tilSerializable: TheQueryString := 'set transaction isolation level serializable';
    tilRepeatableRead: TheQueryString := 'set transaction isolation level repeatable read';
    tilReadCommitted: TheQueryString := 'set transaction isolation level read committed';
    tilReadUncommitted: TheQueryString := 'set transaction isolation level read uncommitted';
    tilSnapshot: TheQueryString := 'set transaction isolation level snapshot';
  else
    Assert(False, 'Invalid transaction isolation level');
  end;
  TBaseQuery.DoExecuteQuery(Self, TheQueryString);
end;

procedure TSQLServerConnection.SetupConnection;
begin
  Connection.ProviderName := 'SQL SERVER';
  Connection.SpecificOptions.Values['OLEDBProvider'] := 'prNativeClient';
  Connection.Server := FHost;
  Connection.Username := FUser;
  Connection.Password := FPassword;
  Connection.Database := FDatabase;
end;

procedure TSQLServerConnection.StartTransaction(AnIsolationLevel: TTransactionIsolationLevel);
begin
  inherited;
  SetIsolationLevelUsingQuery(AnIsolationLevel);
end;

function TSQLServerConnection.InsertEntity(AnEntity: TEntity; AForceId: Boolean): TId;
var
  TheFieldInfos: TFieldInfoArray;
  TheQuery: TBaseQuery;
begin
  TheQuery := TBaseQuery.Create(Self);
  try
    if AnEntity is TVersionableEntity then
      (AnEntity as TVersionableEntity).Version := (AnEntity as TVersionableEntity).Version + 1;
    TheQuery.SQL.Add('insert into [' + GetTableMapping(AnEntity.ClassType) + '](' + DBFieldList(AnEntity.ClassType, not AForceId) + ')');
    if AnEntity.HasField('Id') then
      TheQuery.SQL.Add('OUTPUT INSERTED.Id as LastId');
    TheQuery.SQL.Add(' values ( ' + DBValueList(AnEntity, not AForceId, TheFieldInfos) + ');');
    TheQuery.SetQueryParams(TheFieldInfos, AnEntity);
    if AnEntity.HasField('Id') then
    begin
      TheQuery.Open;
      Result := TheQuery.FieldByName('LastId').Value;
    end
    else
    begin
      TheQuery.ExecSQL;
      Result := IdNil;
    end;
  finally
    TheQuery.Free;
  end;
end;

class function TSQLServerConnection.PwdEncryptMethod(const AString: string): string;
begin
  Result := 'PWDENCRYPT(' + AString + ')';
end;

class function TSQLServerConnection.TableExistsSQL(ATableName: string): string;
begin
  Result := 'exists (select * from sys.objects where object_id = object_id(N''[dbo].[' + ATableName + ']'') and type in (N''U''))';
end;

procedure TSQLServerConnection.CreateDatabase(const AName: string);
begin
  TBaseQuery.DoExecuteQuery(Self,
    'if not exists(select * from sys.databases where name = ''' + AName + ''')' +
    'create database [' + AName + '];'
  );
  TBaseQuery.DoExecuteQuery(Self, 'use [' + AName + '];');
  FDatabase := AName;
end;

procedure TSQLServerConnection.CreateDBCompositeTable(SomeEntityClasses: array of TEntityClass);
var
  I, TheFieldCount: Integer;
  TheTableName, TheString: string;
  TheDBFieldTypes: TKeyStringValueArray;
begin
  if Length(SomeEntityClasses) = 0 then
    Exit;
  TheTableName := GetTableMapping(SomeEntityClasses[0]);
  TheDBFieldTypes := GetDBFieldTypes(SomeEntityClasses);
  TheString := 'if not ' + TableExistsSQL(TheTableName) + ReturnLf;
  TheString := TheString + 'create table ['+ TheTableName +'](' + ReturnLF;
  TheFieldCount := TheDBFieldTypes.Count - 1;
  for I := 0 to TheFieldCount do
  begin
    TheString := TheString + '    ' + QuoteObject(TheDBFieldTypes[I].Key);
    TheString := TheString + ' ' + TheDBFieldTypes[I].Value;
    if AnsiSameText(TheDBFieldTypes[I].Key, 'Id') then
      TheString := TheString + ' identity(1,1) primary key not null';
    if I <> TheFieldCount then
      TheString := TheString + ',' + ReturnLF;
  end;
  TheString := TheString + ');';
  TBaseQuery.DoExecuteQuery(Self, TheString);
end;

procedure TSQLServerConnection.DropTableDependencies(const ATableName: string);
var
  TheQuery: TBaseQuery;
begin
  TheQuery := TBaseQuery.Create(Self);
  try
    TheQuery.SQL.Add('SELECT OBJECT_NAME(parent_object_id) as tName, name as kName');
    TheQuery.SQL.Add('FROM sys.foreign_keys');
    TheQuery.SQL.Add('WHERE referenced_object_id = object_id(:objName);');
    TheQuery.ParamByName('objName').Value := ATableName;
    TheQuery.Open;
    while not TheQuery.Eof do
    begin
      TBaseQuery.DoExecuteQuery(Self, 'ALTER TABLE [' + TheQuery.FieldByName('tName').AsString + ']' +
        ' DROP CONSTRAINT [' + TheQuery.FieldByName('kName').AsString + '];');
      TheQuery.Next;
    end;
  finally
    TheQuery.Free;
  end;
end;

procedure TSQLServerConnection.DropDatabase(const AName: string);
var
  TheName: string;
begin
  TheName := AName;
  if AName = FDatabase then
    TBaseQuery.DoExecuteQuery(Self, 'use [master];');
  if AName = '' then
  begin
    TheName := FDatabase;
    FDatabase := '';
  end;
  TBaseQuery.DoExecuteQuery(Self,
    'if exists(select * from sys.databases where name = ''' + TheName + ''')' +
    'alter database [' + TheName + '] set single_user with rollback immediate;'
  );
  TBaseQuery.DoExecuteQuery(Self,
    'if exists(select * from sys.databases where name = ''' + TheName + ''')' +
    'drop database [' + AName + '];'
  );
end;

procedure TSQLServerConnection.DropDBTable(const ATableName: string);
var
  TheString: string;
begin
  DropTableDependencies(ATableName);
  TheString := 'if ' + TableExistsSQL(ATableName) + ReturnLf;
  TheString := TheString + 'drop table ['+ ATableName +'];';
  TBaseQuery.DoExecuteQuery(Self, TheString);
end;

end.
