unit MySQLServerConnection;

interface

uses
  Entity, TypesConsts, EntityMappingManager, DBSQLQuery, EntityMapping, BaseConnection, BaseQuery;

type
  TMySQLServerConnection = class(TBaseConnection)
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
    class function QuoteObject(const AnObject: string): string; override;
    class function QuoteValue(const AValue: string): string; override;

    function GetParameterDatabaseName: string; virtual;
    function GetParameterHost: string;
    function GetUserName: string; virtual;
    function GetPassword: string; virtual;
    procedure SetupConnection; override;
  public
    class function TableExistsSQL(ATableName: string): string;

    procedure AddForeignKey(const AKeyName: string; AFromClass: TEntityClass; AFromToken: TEntityFieldNamesToken;
      AToClass: TEntityClass; AToToken: TEntityFieldNamesToken; SomeCascadeOptions: TCascadeOptions); override;
    procedure AddIndex(const AKeyName: string; AEntityClass: TEntityClass; SomeTokens: array of TEntityFieldNamesToken); override;
    procedure AddUniqueKey(const AKeyName: string; AEntityClass: TEntityClass; SomeTokens: array of TEntityFieldNamesToken); override;
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

{ TMySQLServerConnection }

uses
  SysUtils, BaseData, Variants, EntityManager, Classes, TypesFunctions, ExceptionClasses, SkyLists, TypInfo,
  VersionableEntity, Windows, DB, MySQLUniProvider, StrUtils;

{TMySQLServerConnection}

function TMySQLServerConnection.GetUserName: string;
begin
  Result := FUser;
end;

class function TMySQLServerConnection.GetFieldTypeTBlobType: string;
begin
  Result := 'varbinary(max)';
end;

class function TMySQLServerConnection.GetFieldTypeTDate: string;
begin
  Result := 'date';
end;

class function TMySQLServerConnection.GetFieldTypeTPasswordString: string;
begin
  Result := 'varbinary(70)';
end;

class function TMySQLServerConnection.GetFieldTypeTTime: string;
begin
  Result := 'time';
end;

class function TMySQLServerConnection.GetFieldTypeUnicode: string;
begin
  Result := 'varchar' + '(50)';
end;

function TMySQLServerConnection.GetParameterDatabaseName: string;
begin
  Result := FDatabase;
end;

function TMySQLServerConnection.GetParameterHost: string;
begin
  Result := FHost;
end;

function TMySQLServerConnection.GetPassword: string;
begin
  Result := FPassword;
end;

procedure TMySQLServerConnection.SetConnectionInfo(const AHost, AUser, APassword, ADatabase: string);
begin
  FHost := AHost;
  FUser := AUser;
  FPassword := APassword;
  FDatabase := ADatabase;
  OpenConnection;
end;

procedure TMySQLServerConnection.SetIsolationLevelUsingQuery(AnIsolationLevel: TTransactionIsolationLevel);
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

procedure TMySQLServerConnection.SetupConnection;
begin
  Connection.ProviderName := 'MySQL';
  Connection.Server := FHost;
  Connection.Username := FUser;
  Connection.Password := FPassword;
  Connection.Database := FDatabase;
end;

procedure TMySQLServerConnection.StartTransaction(AnIsolationLevel: TTransactionIsolationLevel);
begin
  inherited;
  SetIsolationLevelUsingQuery(AnIsolationLevel);
end;

function TMySQLServerConnection.InsertEntity(AnEntity: TEntity; AForceId: Boolean): TId;
var
  TheFieldInfos: TFieldInfoArray;
  TheQuery: TBaseQuery;
begin
  TheQuery := TBaseQuery.Create(Self);
  try
    if AnEntity is TVersionableEntity then
      (AnEntity as TVersionableEntity).Version := (AnEntity as TVersionableEntity).Version + 1;
    TheQuery.SQL.Add('insert into `' + GetTableMapping(AnEntity.ClassType) + '`(' + DBFieldList(AnEntity.ClassType, not AForceId) + ')');
    TheQuery.SQL.Add(' values ( ' + DBValueList(AnEntity, not AForceId, TheFieldInfos) + ');');
    TheQuery.SetQueryParams(TheFieldInfos, AnEntity);
    TheQuery.ExecSQL;
    if AnEntity.HasField('Id') then
    begin
      TheQuery.SQL.Clear;
      TheQuery.SQL.Add('select LAST_INSERT_ID() LastId;');
      TheQuery.Open;
      Result := TheQuery.FieldByName('LastId').Value;
    end
    else
      Result := IdNil;
  finally
    TheQuery.Free;
  end;
end;

class function TMySQLServerConnection.QuoteObject(const AnObject: string): string;
begin
  Result := '`' + AnObject + '`';
end;

class function TMySQLServerConnection.QuoteValue(const AValue: string): string;
begin
  Result := AValue;
end;

class function TMySQLServerConnection.PwdEncryptMethod(const AString: string): string;
begin
  Result := 'PWDENCRYPT(' + AString + ')';
end;

class function TMySQLServerConnection.TableExistsSQL(ATableName: string): string;
begin
  Result := 'exists (select * from sys.objects where object_id = object_id(N''[dbo].[' + ATableName + ']'') and type in (N''U''))';
end;

procedure TMySQLServerConnection.AddForeignKey(const AKeyName: string; AFromClass: TEntityClass;
  AFromToken: TEntityFieldNamesToken; AToClass: TEntityClass; AToToken: TEntityFieldNamesToken;
  SomeCascadeOptions: TCascadeOptions);
var
  TheString: string;
begin
  TheString := Format('ALTER TABLE %s' + ReturnLf + //AFromClass
    'ADD CONSTRAINT %s FOREIGN KEY(%s)' + ReturnLf + // AKeyName, AFromToken
    'REFERENCES %s(%s) ON UPDATE %s ON DELETE %s;',[ // AToClass, AToToken, update action, delete action
    QuoteObject(GetTableMapping(AFromClass)),
    QuoteObject(AKeyName),
    QuoteObject(TEntityManager.GetEntityFieldInfo(AFromClass, AFromToken.PropertyName).FieldName),
    QuoteObject(GetTableMapping(AToClass)),
    QuoteObject(TEntityManager.GetEntityFieldInfo(AToClass, AToToken.PropertyName).FieldName),
    StrUtils.IfThen({$IFDEF UNICODE}TCascadeOption.{$ENDIF}coUpdate in SomeCascadeOptions, 'CASCADE', 'NO ACTION'),
    StrUtils.IfThen({$IFDEF UNICODE}TCascadeOption.{$ENDIF}coDelete in SomeCascadeOptions, 'CASCADE', 'NO ACTION')
  ]);
  TBaseQuery.DoExecuteQuery(Self, TheString);
end;

procedure TMySQLServerConnection.AddIndex(const AKeyName: string; AEntityClass: TEntityClass;
  SomeTokens: array of TEntityFieldNamesToken);
var
  TheString: string;
begin
  TheString := Format('CREATE INDEX %s ON %s' + ReturnLF + // AKeyName, AEntityClass
	  '(%s);', [
    QuoteObject(AKeyName),
    QuoteObject(GetTableMapping(AEntityClass)),
    GetTokenFieldNames(AEntityClass, SomeTokens)
  ]);
  TBaseQuery.DoExecuteQuery(Self, TheString);
end;

procedure TMySQLServerConnection.AddUniqueKey(const AKeyName: string; AEntityClass: TEntityClass;
  SomeTokens: array of TEntityFieldNamesToken);
var
  TheString: string;
begin
  TheString := Format('CREATE UNIQUE INDEX %s ON %s' + ReturnLF + // AKeyName, AEntityClass
	  '(%s);', [
    QuoteObject(AKeyName),
    QuoteObject(GetTableMapping(AEntityClass)),
    GetTokenFieldNames(AEntityClass, SomeTokens)
  ]);
  TBaseQuery.DoExecuteQuery(Self, TheString);
end;

procedure TMySQLServerConnection.CreateDatabase(const AName: string);
begin
  TBaseQuery.DoExecuteQuery(Self,
    'if not exists(select * from sys.databases where name = ''' + AName + ''')' +
    'create database `' + AName + '`;'
  );
  TBaseQuery.DoExecuteQuery(Self, 'use `' + AName + '`;');
  FDatabase := AName;
end;

procedure TMySQLServerConnection.CreateDBCompositeTable(SomeEntityClasses: array of TEntityClass);
var
  I, TheFieldCount: Integer;
  TheIdFound: Boolean;
  TheTableName, TheString: string;
  TheDBFieldTypes: TKeyStringValueArray;
begin
  if Length(SomeEntityClasses) = 0 then
    Exit;
  TheTableName := GetTableMapping(SomeEntityClasses[0]);
  TheDBFieldTypes := GetDBFieldTypes(SomeEntityClasses);
  TheString := 'create table IF NOT EXISTS `'+ TheTableName +'`(' + ReturnLF;
  TheFieldCount := TheDBFieldTypes.Count - 1;
  TheIdFound := False;
  for I := 0 to TheFieldCount do
  begin
    TheString := TheString + '    ' + QuoteObject(TheDBFieldTypes[I].Key);
    TheString := TheString + ' ' + TheDBFieldTypes[I].Value;
    if AnsiSameText(TheDBFieldTypes[I].Key, 'Id') then
    begin
      TheString := TheString + ' NOT NULL AUTO_INCREMENT';
      TheIdFound := True;
    end;
    if I <> TheFieldCount then
      TheString := TheString + ',' + ReturnLF;
  end;
  if TheIdFound then
    TheString := TheString + ', PRIMARY KEY (`Id`)';
  TheString := TheString + ');';
  TBaseQuery.DoExecuteQuery(Self, TheString);
end;

procedure TMySQLServerConnection.DropTableDependencies(const ATableName: string);
var
  TheQuery: TBaseQuery;
begin
  TheQuery := TBaseQuery.Create(Self);
  try
    TheQuery.SQL.Add(
      'select CONSTRAINT_NAME kName, TABLE_NAME tName from INFORMATION_SCHEMA.KEY_COLUMN_USAGE ' +
      'where TABLE_SCHEMA = :ADbName and REFERENCED_TABLE_NAME = :ATableName ' +
      'and referenced_column_name is not NULL;'
    );
    TheQuery.ParamByName('ADbName').Value := FDatabase;
    TheQuery.ParamByName('ATableName').Value := ATableName;
    TheQuery.Open;
    while not TheQuery.Eof do
    begin
      TBaseQuery.DoExecuteQuery(Self, 'ALTER TABLE `' + TheQuery.FieldByName('tName').AsString + '`' +
        ' DROP FOREIGN KEY `' + TheQuery.FieldByName('kName').AsString + '`;');
      TheQuery.Next;
    end;
  finally
    TheQuery.Free;
  end;
end;

procedure TMySQLServerConnection.DropDatabase(const AName: string);
begin
  TBaseQuery.DoExecuteQuery(Self, 'drop database `' + AName + '`;');
end;

procedure TMySQLServerConnection.DropDBTable(const ATableName: string);
begin
  DropTableDependencies(ATableName);
  TBaseQuery.DoExecuteQuery(Self, 'drop table IF EXISTS `'+ ATableName +'`;');
end;

end.
