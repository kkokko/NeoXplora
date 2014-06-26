unit BaseConnection;

interface

uses
  Entity, EntityMapping, TypesConsts, DBSQLQuery, Uni, BaseQuery;

type
  TCascadeOption = (coDelete, coUpdate);
  TCascadeOptions = set of TCascadeOption;
  TTransactionIsolationLevel =(
    tilUnknown,
    tilSerializable,
    tilRepeatableRead,
    tilReadCommitted,
    tilReadUncommitted,
    tilSnapshot
  );

  TBaseConnection = class
  private
    FConnection: TUniConnection;
  protected
    class function PwdEncryptMethod(const AString: string): string; virtual;
    function DBFieldValueList(AnEntity: TEntity; out SomeFieldInfos: TFieldInfoArray; IgnoreId: Boolean = False): string;
    function DBValueList(AnEntity: TEntity; IgnoreId: Boolean; out SomeFieldInfos: TFieldInfoArray): string;
    procedure DropTableDependencies(const ATableName: string); overload; virtual; abstract;
    function GetDBFieldTypes(AnEntityMapping: TEntityMapping; AnEntityClass: TEntityClass): TKeyStringValueArray; overload;
    function GetDBFieldTypes(SomeEntityClasses: array of TEntityClass): TKeyStringValueArray; overload;

    class function GetFieldTypeTBlobType: string; virtual; abstract;
    class function GetFieldTypeTDate: string; virtual; abstract;
    class function GetFieldTypeTPasswordString: string; virtual; abstract;
    class function GetFieldTypeTTime: string; virtual; abstract;
    class function GetFieldTypeUnicode: string; virtual; abstract;
    function GetTokenFieldNames(AEntityClass: TEntityClass; SomeTokens: array of TEntityFieldNamesToken): string;

    class function QuoteObject(const AnObject: string): string; virtual;
    class function QuoteValue(const AValue: string): string; virtual;

    procedure SetupConnection; virtual; abstract;
  public
    constructor Create; virtual;
    destructor Destroy; override;

    procedure AddForeignKey(const AKeyName: string; AFromClass: TEntityClass; AFromToken: TEntityFieldNamesToken;
      AToClass: TEntityClass; AToToken: TEntityFieldNamesToken; SomeCascadeOptions: TCascadeOptions); virtual;
    procedure AddIndex(const AKeyName: string; AEntityClass: TEntityClass; SomeTokens: array of TEntityFieldNamesToken); virtual;
    procedure AddUniqueKey(const AKeyName: string; AEntityClass: TEntityClass; SomeTokens: array of TEntityFieldNamesToken); virtual;
    procedure CloseConnection;
    procedure CreateDBCompositeTable(SomeEntityClasses: array of TEntityClass); virtual; abstract;
    procedure CreateDBTable(AnEntityClass: TEntityClass);
    procedure CommitTransaction;
    function DBFieldList(AnEntityClass: TClass; IgnoreId: Boolean = False): string; overload;
    function DBFieldList(SomeEntityClasses: array of TEntityClass; IgnoreId: Boolean = False): string; overload;
    procedure DeleteEntity(AnEntity: TEntity);
    procedure DropAndRecreateTable(AnEntityClass: TEntityClass);
    procedure DropDBTable(AnEntityClass: TEntityClass); overload;
    procedure DropDBTable(const ATableName: string); overload; virtual; abstract;
    procedure DropTableDependencies(AnEntityClass: TEntityClass); overload;
    function EstimateSQLDataType(AFieldInfo: TFieldInfo): string; virtual;
    procedure ExecuteQuery(AQuery: TDBSQLQuery);
    function GetQuotedTableMapping(AnEntityClass: TClass): string;
    class function GetTableMapping(AnEntityClass: TClass): string; overload;
    class function GetTableMapping(const AnEntityClassName: string): string; overload;
    function InsertEntity(AnEntity: TEntity; AForceId: Boolean = False): TId; virtual; abstract;
    procedure OpenConnection;
    procedure RollBackTransaction(LogTransactionRollback: Boolean = False);
    function SelectAll(AnEntityClass: TEntityClass): TEntities; overload;
    function SelectAll(SomeEntityClasses: array of TEntityClass): TEntities; overload;
    function SelectById(AnEntityClass: TEntityClass; AnId: TId): TEntity;
    function SelectCount(AQuery: TDBSQLQuery): Integer;
    function SelectQuery(SomeEntityClasses: array of TEntityClass; AQuery: TDBSQLQuery): TEntities;
    function SelectQuerySingle(SomeEntityClasses: array of TEntityClass; AQuery: TDBSQLQuery;
      AllowNullResult: Boolean): TEntity; overload;
    procedure StartTransaction(AnIsolationLevel: TTransactionIsolationLevel); virtual;
    procedure UpdateEntity(AnEntity: TEntity); virtual;
//    function TranslateDBSQLQuery(ADBSQLQuery: TDBSQLQuery): TBaseQuery; deprecated;
    property Connection: TUniConnection read FConnection;
  end;

implementation

uses
  EntityMappingManager, EntityManager, KeyStringValue, BaseData, TypInfo, SkyLists, SysUtils, StrUtils, TypesFunctions,
  VersionableEntity, ExceptionClasses, DB;

{$IFNDEF UNICODE}
// the delphi 2010 way does not work for 2006
type
  TBlobHelper = class helper for TFieldWrapperField
  public
    procedure SaveBlobToStream(AStream: TStream);
  end;

// TBlobHelper
procedure TBlobHelper.SaveBlobToStream(AStream: TStream);
var
  TheBlobStream: TStream;
begin
  TheBlobStream := FField.DataSet.CreateBlobStream(FField, bmRead);
  try
    AStream.Size := 0;
    AStream.CopyFrom(TheBlobStream, 0);
  finally
    TheBlobStream.Free;
  end;
end;
{$ENDIF}

{ TBaseConnection }

procedure TBaseConnection.AddForeignKey(const AKeyName: string; AFromClass: TEntityClass;
  AFromToken: TEntityFieldNamesToken; AToClass: TEntityClass; AToToken: TEntityFieldNamesToken;
  SomeCascadeOptions: TCascadeOptions);
var
  TheString: string;
begin
  TheString := Format('ALTER TABLE %s ADD CONSTRAINT' + ReturnLf + //AFromClass
    '%s FOREIGN KEY' + ReturnLf + // AKeyName
    '(%s) REFERENCES %s' + ReturnLf + // AFromToken, AToClass
    '(%s) ON UPDATE %s ON DELETE %s;',[ //AToToken, update action, delete action
    QuoteObject(GetTableMapping(AFromClass)),
    QuoteObject(AKeyName),
    QuoteObject(TEntityManager.GetEntityFieldInfo(AFromClass, AFromToken.PropertyName).FieldName),
    QuoteObject(GetTableMapping(AToClass)),
    QuoteObject(TEntityManager.GetEntityFieldInfo(AToClass, AToToken.PropertyName).FieldName),
    StrUtils.IfThen({$IFDEF VER210}TCascadeOption.{$ENDIF}coUpdate in SomeCascadeOptions, 'CASCADE', 'NO ACTION'),
    StrUtils.IfThen({$IFDEF VER210}TCascadeOption.{$ENDIF}coDelete in SomeCascadeOptions, 'CASCADE', 'NO ACTION')
  ]);
  TBaseQuery.DoExecuteQuery(Self, TheString);
end;

procedure TBaseConnection.AddIndex(const AKeyName: string; AEntityClass: TEntityClass;
  SomeTokens: array of TEntityFieldNamesToken);
var
  TheString: string;
begin
  TheString := Format('CREATE NONCLUSTERED INDEX %s ON %s' + ReturnLF + // AKeyName, AEntityClass
	  '(%s);', [
    QuoteObject(AKeyName),
    QuoteObject(GetTableMapping(AEntityClass)),
    GetTokenFieldNames(AEntityClass, SomeTokens)
  ]);
  TBaseQuery.DoExecuteQuery(Self, TheString);
end;

procedure TBaseConnection.AddUniqueKey(const AKeyName: string; AEntityClass: TEntityClass;
  SomeTokens: array of TEntityFieldNamesToken);
var
  TheString: string;
begin
  TheString := Format('CREATE UNIQUE NONCLUSTERED INDEX %s ON %s' + ReturnLF + // AKeyName, AEntityClass
	  '(%s);', [
    QuoteObject(AKeyName),
    QuoteObject(GetTableMapping(AEntityClass)),
    GetTokenFieldNames(AEntityClass, SomeTokens)
  ]);
  TBaseQuery.DoExecuteQuery(Self, TheString);
end;

procedure TBaseConnection.CloseConnection;
begin
  FConnection.Close;
end;

procedure TBaseConnection.CommitTransaction;
begin
  FConnection.Commit;
end;

constructor TBaseConnection.Create;
begin
  inherited;
  FConnection := TUniConnection.Create(nil);
  FConnection.LoginPrompt := False;
end;

destructor TBaseConnection.Destroy;
begin
  FConnection.Close;
  FConnection.Free;
  inherited;
end;

procedure TBaseConnection.CreateDBTable(AnEntityClass: TEntityClass);
begin
  CreateDBCompositeTable([AnEntityClass]);
end;

function TBaseConnection.DBFieldList(AnEntityClass: TClass; IgnoreId: Boolean): string;
begin
  Result := DBFieldList([TEntityClass(AnEntityClass)], IgnoreId);
end;

function TBaseConnection.DBFieldList(SomeEntityClasses: array of TEntityClass; IgnoreId: Boolean): string;
var
  I, TheFieldCount: Integer;
  TheFieldTypes: {$IFDEF VER210}TKeyStringValue.TArray{$ELSE}TKeyStringValueArray{$ENDIF};
begin
  TheFieldTypes := GetDBFieldTypes(SomeEntityClasses);
  TheFieldCount := TheFieldTypes.Count - 1;
  for I := 0 to TheFieldCount do
    if not (IgnoreId and AnsiSameText(TheFieldTypes[I].Key, QuoteObject(SomeEntityClasses[0].EntityToken_Id.PropertyName))) then
    begin
      Result := Result + TheFieldTypes[I].Key;
      Result := Result + ', ';
    end;
  if Length(Result) > 1 then
    SetLength(Result, Length(Result) - 2);
end;

function TBaseConnection.DBFieldValueList(AnEntity: TEntity; out SomeFieldInfos: TFieldInfoArray;
  IgnoreId: Boolean): string;
var
  TheDBName: string;
  TheDBValue: string;
  I, TheFieldCount: Integer;
  TheEntityMapping: TEntityMapping;
begin
  TheEntityMapping := TEntityMappingManager.GetMapping(AnEntity.ClassType);
  SomeFieldInfos := AnEntity.GetFieldInfos(IgnoreId);
  Result := '';
  TheFieldCount := High(SomeFieldInfos);
  for I := 0 to TheFieldCount do
    if ('TPasswordString' = SomeFieldInfos[I].FieldType) and
      (AnEntity.GetValueForField(SomeFieldInfos[I].FieldName) = '') then
      Continue
    else
    begin
      TheDBName := TheEntityMapping.GetValueForField(SomeFieldInfos[I].FieldName);
      TheDBValue := ':' + TheDBName;
      if SomeFieldInfos[I].FieldType = 'TPasswordString' then
        TheDBValue := PwdEncryptMethod(TheDBValue);
      case SomeFieldInfos[I].FieldKind of
      tkFloat:
        if ((SomeFieldInfos[I].FieldType = 'TDate') or
          (SomeFieldInfos[I].FieldType = 'TDateTime') or
          (SomeFieldInfos[I].FieldType = 'TTime')) and
          (AnEntity.GetValueForField(SomeFieldInfos[I].FieldName) = 0) then
            TheDBValue := 'null';
      tkInteger, tkInt64:
        if (SomeFieldInfos[I].FieldType = 'TId') and
            (AnEntity.GetValueForField(SomeFieldInfos[I].FieldName) = 0) then
            TheDBValue := 'null';
      tkClass:
        if (TypesConsts.TBlobType.ClassName <> SomeFieldInfos[I].FieldType) then
          Continue;
      tkString{$IFDEF VER210}, tkUString{$ENDIF}:
        if (AnEntity.GetValueForField(SomeFieldInfos[I].FieldName) = '') then
          TheDBValue := 'null';
      end;
      if TheDBValue <> '' then // mapping exists
      begin
        Result := Result + QuoteObject(TheDBName) + '=' + TheDBValue;
        Result := Result + ', ';
      end;
    end;
  if Length(Result) > 2 then
    SetLength(Result, Length(Result) - 2);
end;

function TBaseConnection.DBValueList(AnEntity: TEntity; IgnoreId: Boolean;
  out SomeFieldInfos: TFieldInfoArray): string;
var
  TheDBValue: string;
  I, TheFieldCount: Integer;
begin
  SomeFieldInfos := AnEntity.GetFieldInfos(IgnoreId);
  Result := '';
  TheFieldCount := High(SomeFieldInfos);
  for I := 0 to TheFieldCount do
  begin
    case SomeFieldInfos[I].FieldKind of
      tkFloat:
        if ((SomeFieldInfos[I].FieldType = 'TDate') or
          (SomeFieldInfos[I].FieldType = 'TDateTime') or
          (SomeFieldInfos[I].FieldType = 'TTime')) and
          (AnEntity.GetValueForField(SomeFieldInfos[I].FieldName) = 0) then
          begin
            Result := Result + 'null';
            if I <> TheFieldCount then
              Result := Result + ', ';
            Continue; // null values cannot be specified in params
          end;
        tkInteger, tkInt64:
          if (SomeFieldInfos[I].FieldType = 'TId') and
            (AnEntity.GetValueForField(SomeFieldInfos[I].FieldName) = 0) then
          begin
            Result := Result + 'null';
            if I <> TheFieldCount then
              Result := Result + ', ';
            Continue; // null values cannot be specified in params
          end;
        tkString{$IFDEF VER210}, tkUString{$ENDIF}:
          if AnEntity.GetValueForField(SomeFieldInfos[I].FieldName) = '' then
          begin
            Result := Result + 'null';
            if I <> TheFieldCount then
              Result := Result + ', ';
            Continue; // null values cannot be specified in params
          end;
        tkClass: if not (TypesConsts.TBlobType.ClassName = SomeFieldInfos[I].FieldType) then
          Continue;
      end ;
    TheDBValue := ':' + SomeFieldInfos[I].FieldName;
    if SomeFieldInfos[I].FieldType = 'TPasswordString' then
      TheDBValue := PwdEncryptMethod(TheDBValue);
    Result := Result + TheDBValue;
    if I <> TheFieldCount then
      Result := Result + ', ';
  end;
end;

procedure TBaseConnection.DeleteEntity(AnEntity: TEntity);
var
  TheQuery: TBaseQuery;
begin
  TheQuery := TBaseQuery.Create(Self);
  try
    TheQuery.SQL.Add('delete from ' + QuoteObject(GetTableMapping(AnEntity.ClassType)) + ' where ');
    TheQuery.SQL.Add('Id=' + IdToStr(AnEntity.Id));
    if AnEntity is TVersionableEntity then
      TheQuery.SQL.Add(' and Version = '+ IntToStr((AnEntity as TVersionableEntity).Version));
    TheQuery.ExecSQL;
    if (AnEntity is TVersionableEntity) and (TheQuery.RowsAffected = 0 ) then
      raise ESkyDatabaseRecordDoesNotExist.Create(Self, 'DeleteEntity', GetTableMapping(AnEntity.ClassType));
  finally
    TheQuery.Free;
  end;
end;

procedure TBaseConnection.DropAndRecreateTable(AnEntityClass: TEntityClass);
begin
  DropDBTable(AnEntityClass);
  CreateDBTable(AnEntityClass);
end;

procedure TBaseConnection.DropDBTable(AnEntityClass: TEntityClass);
begin
  DropDBTable(GetTableMapping(AnEntityClass));
end;

procedure TBaseConnection.DropTableDependencies(AnEntityClass: TEntityClass);
begin
  DropTableDependencies(GetTableMapping(AnEntityClass));
end;

function TBaseConnection.EstimateSQLDataType(AFieldInfo: TFieldInfo): string;
var
  TheDelphiType: string;
begin
  TheDelphiType := AFieldInfo.FieldType;

  Result := '';

  case AFieldInfo.FieldKind of
    tkFloat:
      if TheDelphiType = 'Double' then
        Result := QuoteValue('float')
      else if TheDelphiType = 'TDate' then
        Result := GetFieldTypeTDate
      else if TheDelphiType = 'TDateTime' then
        Result := QuoteValue('datetime')
      else if TheDelphiType = 'TTime' then
        Result := GetFieldTypeTTime;
    tkInteger, tkInt64:
      if (TheDelphiType = 'TId') or (TheDelphiType = 'Cardinal') or (TheDelphiType = 'Int64') then
        Result := QuoteValue('bigint')
      else if (TheDelphiType = 'Byte') then
        Result := QuoteValue('smallint')
      else
        Result := QuoteValue('int');
    tkEnumeration:
      if (TheDelphiType = 'Boolean') then
        Result := QuoteValue('bit');
    tkString, tkWString{$IFDEF VER210}, tkUString{$ENDIF}:
    begin
      if (TheDelphiType = 'TPasswordString') then
        Result := GetFieldTypeTPasswordString
      else {$IFNDEF VER210} if tkString = AFieldInfo.FieldKind then
        Result := GetFieldTypeUnicode
      else {$ENDIF}
        Result := QuoteValue('nvarchar') + '(50)'
    end;
    tkClass:
      if (TheDelphiType = 'TBlobType') then
        Result := GetFieldTypeTBlobType;
  end;
  if Result = '' then
    Result := GetFieldTypeUnicode;
end;

procedure TBaseConnection.ExecuteQuery(AQuery: TDBSQLQuery);
begin
  TBaseQuery.ExecuteQuery(Self, AQuery);
end;

class function TBaseConnection.GetTableMapping(AnEntityClass: TClass): string;
var
  TheMapping: TEntityMapping;
begin
  TheMapping := TEntityMappingManager.GetMapping(AnEntityClass);
  if not Assigned(TheMapping) then
    Result := ''
  else
    Result := TheMapping.TableName;
end;

function TBaseConnection.GetDBFieldTypes(AnEntityMapping: TEntityMapping;
  AnEntityClass: TEntityClass): TKeyStringValueArray;
var
  TheFieldInfo: TFieldInfo;
  TheFieldInfos: {$IFDEF VER210}TFieldInfo.TArray{$ELSE}TFieldInfoArray{$ENDIF};
  I, TheIndex: Integer;
  TheDBName: string;
  TheValue: string;
begin
  if TEntityManager.GetFieldInfoClassForEntityClass(AnEntityClass).FindFieldByName('Id', TheFieldInfo) then
  begin
    Result.Count := AnEntityMapping.GetFieldNameCount + 1;
    Result.Keys[0] := QuoteObject(AnEntityMapping.GetValueForField('Id'));
    if (not AnEntityClass.GetFieldTypeFromTokens(AnEntityClass, 'Id', TheValue)) and
      not AnEntityClass.GetDBFieldType('Id', 'SQLDB', TheValue) then
      TheValue := EstimateSQLDataType(TEntityManager.GetEntityFieldInfo(AnEntityClass, 'Id'));
    Result.Values[0] := TheValue;
    TheIndex := 1;
  end
  else
  begin
    Result.Count := AnEntityMapping.GetFieldNameCount(True);
    TheIndex := 0;
  end;
  TheFieldInfos := TEntityManager.GetEntityFieldInfos(AnEntityClass, True);
  for I := 0 to High(TheFieldInfos) do
  begin
    if (TheFieldInfos[I].FieldKind = tkClass) and (TypesConsts.TBlobType.ClassName <> TheFieldInfos[I].FieldType) then
      Continue;
    TheDBName := AnEntityMapping.GetValueForField(TheFieldInfos[I].FieldName);
    if TheDBName <> '' then
    begin
      Result.Keys[TheIndex] := QuoteValue(TheDBName);
      if (not AnEntityClass.GetFieldTypeFromTokens(AnEntityClass, TheFieldInfos[I].FieldName, TheValue)) and
        not AnEntityClass.GetDBFieldType(TheFieldInfos[I].FieldName, 'SQLDB', TheValue) then
        TheValue := EstimateSQLDataType(TheFieldInfos[I]);
      Result.Values[TheIndex] := TheValue;
      Inc(TheIndex);
    end;
  end;
  Result.Count := TheIndex;
end;

function TBaseConnection.GetDBFieldTypes(SomeEntityClasses: array of TEntityClass): TKeyStringValueArray;
var
  TheFieldList: TSkyStringStringList;
  TheEntityMapping: TEntityMapping;
  TheDBFieldTypes: TKeyStringValueArray;
  I, J: Integer;
begin
  TheFieldList := TSkyStringStringList.Create;
  try
    for I := 0 to High(SomeEntityClasses) do
    begin
      TheEntityMapping := TEntityMappingManager.GetMapping(SomeEntityClasses[I]);
      TheDBFieldTypes := GetDBFieldTypes(TheEntityMapping, SomeEntityClasses[I]);
      for J := 0 to TheDBFieldTypes.Count - 1 do
        if TheFieldList.IndexOf(TheDBFieldTypes[J].Key) = -1 then
          TheFieldList.Add(TheDBFieldTypes[J].Key, TheDBFieldTypes[J].Value);
    end;
    TheFieldList.Sorted := True;
    Result.Count := TheFieldList.Count;
    for I := 0 to TheFieldList.Count - 1 do
    begin
      Result.Keys[I] := TheFieldList.Items[I];
      Result.Values[I] := TheFieldList.Objects[I];
    end;
  finally
    TheFieldList.Free;
  end;
end;

function TBaseConnection.GetQuotedTableMapping(AnEntityClass: TClass): string;
begin
  Result := QuoteObject(GetTableMapping(AnEntityClass));
end;

class function TBaseConnection.GetTableMapping(const AnEntityClassName: string): string;
begin
  Result := GetTableMapping(TEntityManager.GetEntityClassForName(AnEntityClassName));
end;

function TBaseConnection.GetTokenFieldNames(AEntityClass: TEntityClass; SomeTokens: array of TEntityFieldNamesToken): string;
var
  I: Integer;
begin
  Result := '';
  for I := 0 to High(SomeTokens) do
    Result := Result + QuoteObject(TEntityManager.GetEntityFieldInfo(
      AEntityClass, SomeTokens[I].PropertyName).FieldName) + ', ';
  if Length(Result) > 2 then
    SetLength(Result, Length(Result) - 2);
end;

class function TBaseConnection.QuoteObject(const AnObject: string): string;
begin
  Result := '[' + AnObject + ']';
end;

class function TBaseConnection.QuoteValue(const AValue: string): string;
begin
  Result := '[' + AValue + ']';
end;

procedure TBaseConnection.OpenConnection;
begin
  FConnection.Close;
  FConnection.LoginPrompt := False;
  SetupConnection;
  FConnection.Open;
end;

class function TBaseConnection.PwdEncryptMethod(const AString: string): string;
begin
  Result := AString;
end;

procedure TBaseConnection.RollBackTransaction(LogTransactionRollback: Boolean);
begin
  FConnection.Rollback;
end;

function TBaseConnection.SelectAll(AnEntityClass: TEntityClass): TEntities;
begin
  Result := SelectAll([AnEntityClass]);
end;

function TBaseConnection.SelectAll(SomeEntityClasses: array of TEntityClass): TEntities;
begin
  Result := TBaseQuery.SelectAll(Self, SomeEntityClasses);
end;

function TBaseConnection.SelectById(AnEntityClass: TEntityClass; AnId: TId): TEntity;
begin
  Result := TBaseQuery.SelectById(Self, AnEntityClass, AnId);
end;

function TBaseConnection.SelectCount(AQuery: TDBSQLQuery): Integer;
begin
  Result := TBaseQuery.SelectCount(Self, AQuery);
end;

function TBaseConnection.SelectQuery(SomeEntityClasses: array of TEntityClass; AQuery: TDBSQLQuery): TEntities;
begin
  Result := TBaseQuery.SelectQuery(Self, SomeEntityClasses, AQuery);
end;

function TBaseConnection.SelectQuerySingle(SomeEntityClasses: array of TEntityClass; AQuery: TDBSQLQuery;
  AllowNullResult: Boolean): TEntity;
begin
  Result := TBaseQuery.SelectQuerySingle(Self, SomeEntityClasses, AQuery, AllowNullResult);
end;

procedure TBaseConnection.StartTransaction(AnIsolationLevel: TTransactionIsolationLevel);
begin
  FConnection.StartTransaction;
end;

//function TBaseConnection.TranslateDBSQLQuery(ADBSQLQuery: TDBSQLQuery): TBaseQuery;
//begin
//  Result := TBaseQuery.TranslateDBSQLQuery(Self, ADBSQLQuery);
//end;

procedure TBaseConnection.UpdateEntity(AnEntity: TEntity);
var
  TheFieldInfos: TFieldInfoArray;
  TheQuery: TBaseQuery;
  I: Integer;
begin
  TheQuery := TBaseQuery.Create(Self);
  try
    TheQuery.SQL.Add('update ' + QuoteObject(GetTableMapping(AnEntity.ClassType)) + ' set');
    TheQuery.SQL.Add(DBFieldValueList(AnEntity, TheFieldInfos, True) + ' where');
    TheQuery.SQL.Add(QuoteObject(AnEntity.MappedFieldName('Id')) + ' = :id');
    if AnEntity is TVersionableEntity then
    begin
      TheQuery.SQL.Add(Format(' and ' + QuoteObject('%s') +' = %d', [AnEntity.MappedFieldName('Version'),
        (AnEntity as TVersionableEntity).Version]));
      (AnEntity as TVersionableEntity).Version := (AnEntity as TVersionableEntity).Version + 1;
    end;
    for I := 0 to TheQuery.Params.Count - 1 do
      TheQuery.Params.Items[I].DataType := ftVariant;
    TheQuery.SetQueryParams(TheFieldInfos, AnEntity);
    TheQuery.ParamByName('id').Value := AnEntity.Id;
    TheQuery.ExecSQL;
    if (AnEntity is TVersionableEntity) and (TheQuery.RowsAffected = 0 ) then
      raise ESkyDatabaseRecordDoesNotExist.Create(Self, 'DeleteEntity', GetTableMapping(AnEntity.ClassType));
  finally
    TheQuery.Free;
  end;
end;

end.