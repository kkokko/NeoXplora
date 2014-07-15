unit BaseQuery;

interface

uses
  TypesConsts, Entity, DBSQLQuery, Uni;

type
  TBaseQuery = class(TUniQuery)
  private
    FLastQuery: string;
    function HandleUniError(E: EUniError): Boolean;
  protected
    procedure SetActive(Value: Boolean); override;
  public
    constructor Create(AConnection: TObject); reintroduce;

    class procedure DeleteOneToMany(AConnection: TObject; ADeleteFromClass: TEntityClass;
      AManyLinkField: string; AnId: TId);
    class procedure DoExecuteQuery(AConnection: TObject; const ASql: string);
    class procedure ExecuteQuery(AConnection: TObject; AQuery: TDBSQLQuery);
    class function LoadOneToMany(AConnection: TObject; AResultClass: TEntityClass;
      AManyLinkField: string; AnId: TId): TEntities; overload;
    class function LoadOneToMany(AConnection: TObject; SomeResultClasses: array of TEntityClass;
      AManyLinkField: string; AnId: TId): TEntities; overload;
    class function LoadOneToManyCount(AConnection: TObject; AResultClass: TEntityClass;
      AManyLinkField: string; AnId: TId): Integer;
    class function LoadManyToMany(AConnection: TObject; AResultClass, AXClass: TEntityClass;
      AXSourceField, AXDestinationField: string; AnId: TId): TEntities;
    class function LoadAllOrderByName(AConnection: TObject; AResultClass: TEntityClass): TEntities;
    class function SelectAll(AConnection: TObject; SomeEntityClasses: array of TEntityClass): TEntities;
    class function SelectById(AConnection: TObject; AnEntityClass: TEntityClass; AnId: TId): TEntity;
    class function SelectField(AConnection: TObject; AQuery: TDBSQLQuery; const AFieldName: string): Variant;
    class function SelectCount(AConnection: TObject; AQuery: TDBSQLQuery): Integer;
    // be carefull that the values are escaped properly in AQuery
    class function SelectQuery(AConnection: TObject; SomeEntityClasses: array of TEntityClass;
      AQuery: TDBSQLQuery): TEntities; overload;
    class function SelectQuerySingle(AConnection: TObject; SomeEntityClasses: array of TEntityClass;
      AQuery: TDBSQLQuery; AllowNullResult: Boolean): TEntity;
    class function TranslateDBSQLQuery(AConnection: TObject; AQuery: TDBSQLQuery): TBaseQuery;

    procedure Execute; override;
    function ParamByName(const AName: WideString): TUniParam;
    function ReadCountData: Integer;
    function ReadMappedEntity(AnEntityClass: TEntityClass): TEntity;
    function ReadMappedEntities(SomeEntityClasses: array of TEntityClass): TEntities;
    function ReadSingleValue(SomeEntityClasses: array of TEntityClass;AllowNullResult: Boolean): TEntity;
    procedure SetQueryParams(SomeFieldInfos: TFieldInfoArray; AnEntity: TEntity);
  end;

function QuerySelectManyToManyForId: TDBSQLQuery;

implementation

uses
  Classes, TypInfo, DB, Variants, EntityMapping, BaseData, EntityMappingManager, ExceptionClasses, CountData,
  TypesFunctions, StringArray, SysUtils, BaseConnection, SkyException;

{$Region 'TDBSQLQuery defs'}
function QueryDeleteOneToManyForId: TDBSQLQuery;
begin
  Result.Name := 'QueryDeleteOneToManyForId';
  Result.Query := TStringArray.FromArray
  ([
      'delete from ', '!B',
      ' where ', '!B.IdA', '=', '!TheId', ';'
  ]);
{$IFDEF UNICODE}
  Result.Params := TKeyStringValue.MakeArray(
{$ELSE}
  Result.Params := TKeyStringValueArray.MakeArray(
{$ENDIF}
    ['B', 'B.IdA', 'TheId'],
    ['B', 'B.IdA', '0']);
end;

function QuerySelectOneToManyForId: TDBSQLQuery;
begin
  Result.Name := 'QuerySelectOneToManyForId';
  Result.Query := TStringArray.FromArray
  ([
      'select * from ', '!B',
      ' where ', '!B.IdA', '=', '!TheId', ';'
  ]);
{$IFDEF UNICODE}
  Result.Params := TKeyStringValue.MakeArray(
{$ELSE}
  Result.Params := TKeyStringValueArray.MakeArray(
{$ENDIF}
    ['B', 'B.IdA', 'TheId'],
    ['B', 'B.IdA', '0']);
end;

function QuerySelectOneToManyCountForId: TDBSQLQuery;
begin
  Result.Name := 'QuerySelectOneToManyForId';
  Result.Query := TStringArray.FromArray
  ([
      'select COUNT(*) Number from ', '!B',
      ' where ', '!B.IdA', '=', '!TheId', ';'
  ]);
{$IFDEF UNICODE}
  Result.Params := TKeyStringValue.MakeArray(
{$ELSE}
  Result.Params := TKeyStringValueArray.MakeArray(
{$ENDIF}
    ['B', 'B.IdA', 'TheId'],
    ['B', 'B.IdA', '0']);
end;

function QuerySelectManyToManyForId: TDBSQLQuery;
begin
  Result.Name := 'QuerySelectManyToManyForId';
  Result.Query := TStringArray.FromArray
  ([
      'select B.* from [',
      '!AxB', '] X inner join [', '!B', '] B on X.[', '!AxB.IdB', ']=B.[', '!B.Id',
      '] where X.[', '!AxB.IdA', ']=', '!TheId', ';'
  ]);
{$IFDEF UNICODE}
  Result.Params := TKeyStringValue.MakeArray(
{$ELSE}
  Result.Params := TKeyStringValueArray.MakeArray(
{$ENDIF}
    ['AxB', 'B', 'AxB.IdA', 'AxB.IdB','B.Id', 'TheId'],
    ['AxB', 'B', 'AxB.IdA', 'AxB.IdB','B.Id', '0']);
end;

function QuerySelectAllOrderByName: TDBSQLQuery;
begin
  Result.Name := 'QuerySelectAllOrderByName';
  Result.Query := TStringArray.FromArray
  ([
      'select .* from ', '!B',
      ' order by ', '!B', '.Name;'
  ]);
{$IFDEF UNICODE}
  Result.Params := TKeyStringValue.MakeArray(
{$ELSE}
  Result.Params := TKeyStringValueArray.MakeArray(
{$ENDIF}
    ['B'],
    ['B']);
end;
{$endregion}

{ TBaseQuery }

constructor TBaseQuery.Create(AConnection: TObject);
begin
  inherited Create(nil);
  Connection := (AConnection as TBaseConnection).Connection;
end;

class procedure TBaseQuery.DeleteOneToMany(AConnection: TObject; ADeleteFromClass: TEntityClass;
  AManyLinkField: string; AnId: TId);
var
  TheQuery: TDBSQLQuery;
  TheResultEntityMapping: TEntityMapping;
begin
  TheQuery := QueryDeleteOneToManyForId;
  TheResultEntityMapping := TEntityMappingManager.GetMapping(ADeleteFromClass);
  TheQuery.Params.ValueForKey['B'] := (AConnection as TBaseConnection).GetTableMapping(ADeleteFromClass);
  TheQuery.Params.ValueForKey['B.IdA'] := TheResultEntityMapping.GetValueForField(AManyLinkField);
  TheQuery.Params.ValueForKey['TheId'] := IntToStr(AnId);
  (AConnection as TBaseConnection).ExecuteQuery(TheQuery);
end;

class procedure TBaseQuery.DoExecuteQuery(AConnection: TObject; const ASql: string);
var
  TheQuery: TBaseQuery;
begin
  TheQuery := TBaseQuery.Create(AConnection);
  try
    TheQuery.SQL.Add(ASql);
    TheQuery.ExecSQL;
  finally
    TheQuery.Free;
  end;
end;

procedure TBaseQuery.Execute;
begin
  try
    inherited;
  except on E: EUniError do
    if not HandleUniError(E) then
      raise;
  end;
end;

class procedure TBaseQuery.ExecuteQuery(AConnection: TObject; AQuery: TDBSQLQuery);
var
  TheQuery: TBaseQuery;
begin
  TheQuery := TranslateDBSQLQuery(AConnection, AQuery);
  try
    TheQuery.ExecSQL;
  finally
    TheQuery.Free;
  end;
end;

function TBaseQuery.HandleUniError(E: EUniError): Boolean;
var
  TheConflictTable: string;
  TheIndex: Integer;
  TheMessage: string;
  TheError: ESkyException;
begin
  TheError := nil;

  TheMessage := E.Message;
  if Pos('The DELETE statement conflicted with the REFERENCE constraint', TheMessage) > 0 then
    TheError := ESkyDatabaseReferenceConstraint.Create(Self, 'HandleUniError', 'tlDelete', '');
  if Pos('The INSERT statement conflicted with the REFERENCE constraint', TheMessage) > 0 then
    TheError := ESkyDatabaseReferenceConstraint.Create(Self, 'HandleUniError', 'tlInsert', '');
  if Pos('The UPDATE statement conflicted with the REFERENCE constraint', TheMessage) > 0 then
    TheError := ESkyDatabaseReferenceConstraint.Create(Self, 'HandleUniError', 'tlUpdate', '');

  if (TheError is ESkyDatabaseReferenceConstraint) then
  begin
    TheConflictTable := '';
    TheIndex := Pos('table "', TheMessage);
    if (TheIndex > 0) and (TheIndex + 6 < Length(TheMessage)) then
    begin
      System.Delete(TheMessage, 1, TheIndex + 6);
      TheIndex := Pos('"', TheMessage);
      if TheIndex > 0 then
      begin
        System.Delete(TheMessage, TheIndex, Length(TheMessage) - TheIndex + 1);
        TheIndex := Pos('.', TheMessage);
        if TheIndex > 0 then
          System.Delete(TheMessage, 1, TheIndex);
        TheConflictTable := TheMessage;
      end;
    end;
    TheConflictTable := TEntityMappingManager.GetClassTypeForTableName(TheConflictTable).FriendlyClassName;
    (TheError as ESkyDatabaseReferenceConstraint).Table := TheConflictTable;
  end;

  Result := TheError <> nil;
  if Result then
    raise TheError;
end;

class function TBaseQuery.LoadAllOrderByName(AConnection: TObject; AResultClass: TEntityClass): TEntities;
var
  TheQuery: TDBSQLQuery;
begin
  TheQuery := QuerySelectAllOrderByName;
  TheQuery.Params.ValueForKey['B'] := (AConnection as TBaseConnection).GetTableMapping(AResultClass);
  Result := (AConnection as TBaseConnection).SelectQuery(AResultClass, TheQuery);
end;

class function TBaseQuery.LoadManyToMany(AConnection: TObject; AResultClass, AXClass: TEntityClass; AXSourceField, AXDestinationField: string;
  AnId: TId): TEntities;
var
  TheQuery: TDBSQLQuery;
  TheResultEntityMapping: TEntityMapping;
  TheXEntityMapping: TEntityMapping;
begin
  TheQuery := QuerySelectManyToManyForId;
  TheResultEntityMapping := TEntityMappingManager.GetMapping(AResultClass);
  TheXEntityMapping := TEntityMappingManager.GetMapping(AXClass);
  TheQuery.Params.ValueForKey['AxB'] := (AConnection as TBaseConnection).GetTableMapping(AXClass);
  TheQuery.Params.ValueForKey['B'] := (AConnection as TBaseConnection).GetTableMapping(AResultClass);
  TheQuery.Params.ValueForKey['AxB.IdA'] := TheXEntityMapping.GetValueForField(AXSourceField);
  TheQuery.Params.ValueForKey['AxB.IdB'] := TheXEntityMapping.GetValueForField(AXDestinationField);
  TheQuery.Params.ValueForKey['B.Id'] := TheResultEntityMapping.GetValueForField('Id');
  TheQuery.Params.ValueForKey['TheId'] := IntToStr(AnId);
  Result := (AConnection as TBaseConnection).SelectQuery(AResultClass, TheQuery);
end;

class function TBaseQuery.LoadOneToMany(AConnection: TObject; SomeResultClasses: array of TEntityClass;
  AManyLinkField: string; AnId: TId): TEntities;
var
  TheQuery: TDBSQLQuery;
  TheResultEntityMapping: TEntityMapping;
begin
  TheQuery := QuerySelectOneToManyForId;
  TheResultEntityMapping := TEntityMappingManager.GetMapping(SomeResultClasses[0]);
  TheQuery.Params.ValueForKey['B'] := (AConnection as TBaseConnection).GetTableMapping(SomeResultClasses[0]);
  TheQuery.Params.ValueForKey['B.IdA'] := TheResultEntityMapping.GetValueForField(AManyLinkField);
  TheQuery.Params.ValueForKey['TheId'] := IntToStr(AnId);
  Result := (AConnection as TBaseConnection).SelectQuery(SomeResultClasses, TheQuery);
end;

class function TBaseQuery.LoadOneToMany(AConnection: TObject; AResultClass: TEntityClass;
  AManyLinkField: string; AnId: TId): TEntities;
begin
  Result := LoadOneToMany(AConnection, [AResultClass], AManyLinkField, AnId);
end;

class function TBaseQuery.LoadOneToManyCount(AConnection: TObject; AResultClass: TEntityClass;
  AManyLinkField: string; AnId: TId): Integer;
var
  TheQuery: TDBSQLQuery;
  TheResultEntityMapping: TEntityMapping;
  TheResults: TEntities;
begin
  TheQuery := QuerySelectOneToManyCountForId;
  TheResultEntityMapping := TEntityMappingManager.GetMapping(AResultClass);
  TheQuery.Params.ValueForKey['B'] := (AConnection as TBaseConnection).GetTableMapping(AResultClass);
  TheQuery.Params.ValueForKey['B.IdA'] := TheResultEntityMapping.GetValueForField(AManyLinkField);
  TheQuery.Params.ValueForKey['TheId'] := IntToStr(AnId);
  TheResults := (AConnection as TBaseConnection).SelectQuery([TCountData], TheQuery);
  try
    if Length(TheResults) = 0 then
      Assert(False, 'Wrong query in LoadOneToManyCount');
    Result := (TheResults[0] as TCountData).Number;
  finally
    TEntity.FreeEntities(TheResults);
  end;
end;

function TBaseQuery.ParamByName(const AName: WideString): TUniParam;
begin
  Result := Params.ParamByName(AName);
end;

function TBaseQuery.ReadCountData: Integer;
var
  TheData: TCountData;
begin
  if not Active then
    Open;
  TheData := ReadSingleValue([TCountData], False) as TCountData;
  try
    Result := TheData.Number;
  finally
    TheData.Free;
  end;
end;

function TBaseQuery.ReadMappedEntities(SomeEntityClasses: array of TEntityClass): TEntities;
var
  TheEntityClass: TEntityClass;
  TheIndex: Integer;
  I: Integer;
begin
  TheIndex := 0;
  Self.First;
  while not Self.Eof do
  begin
    if (TheIndex mod 100) = 0 then
      SetLength(Result, TheIndex + 100);
    for I := 0 to High(SomeEntityClasses) do
    begin
      TheEntityClass := SomeEntityClasses[I].GetRelevantClassForRow(Self);
      if  TheEntityClass.RowIsRelevantForClass(Self) then
      begin
        Result[TheIndex] := ReadMappedEntity(TheEntityClass);
        Inc(TheIndex);
        Continue;
      end;
    end;
    Self.Next;
  end;
  SetLength(Result, TheIndex);
end;

function TBaseQuery.ReadMappedEntity(AnEntityClass: TEntityClass): TEntity;
var
  TheFieldInfos: TFieldInfoArray;
  TheFieldName, TheDBName: string;
  TheValue: Variant;
  TheField: TField;
  I, TheFieldCount: Integer;
  TheEntityMapping: TEntityMapping;
  TheDataBlobStream: TMemoryStream;
  TheVariant: Variant;
  ThePointer: Pointer;
begin
  if Self.Eof or (not AnEntityClass.RowIsRelevantForClass(Self)) then
    raise ESkyDatabaseRecordDoesNotExist.Create(Self, 'ReadMappedEntity', TBaseConnection.GetTableMapping(AnEntityClass));

  Result := AnEntityClass.Create;
  Result.Initialize;
  Result.ExistsInDatabase := True;
  TheFieldInfos := Result.GetFieldInfos;
  TheFieldCount := High(TheFieldInfos);
  TheEntityMapping := TEntityMappingManager.GetMapping(AnEntityClass);
  for I := 0 to TheFieldCount do
  begin
    TheFieldName := TheFieldInfos[I].FieldName;
    TheDBName := TheFieldName;
    if Assigned(TheEntityMapping) then
      TheDBName :=  TheEntityMapping.GetValueForField(TheFieldName);
    if TheDBName = '' then
      TheDBName :=  TheFieldName;
    if (TheDBName = '') or (Self.FindField(TheDBName) = nil) then
      Continue;
    if (TheFieldInfos[I].FieldKind = tkClass) then
     if (TypesConsts.TBlobType.ClassName = TheFieldInfos[I].FieldType) then
      begin
        TheDataBlobStream := TypesConsts.TBlobType.Create;
        try
          TheField := Self.FieldByName(TheDBName);

          {$IFDEF UNICODE}
          TheVariant := TheField.AsVariant;
          if not (TheField.IsNull or VarIsEmpty(TheVariant))  then
          begin
            ThePointer := VarArrayLock(TheVariant);
            try
              TheDataBlobStream.Write(ThePointer^, VarArrayHighBound(TheVariant, 1) + 1);
            finally
              VarArrayUnlock(TheVariant);
            end;
          end;
          {$ELSE}
          // delphi 2006 TBlobHelper :)
          (TheField as TFieldWrapperField).SaveBlobToStream(TheDataBlobStream);
          {$ENDIF}
        except
          TheDataBlobStream.Free;
          raise;
        end;
        GetObjectProp(Result, TheFieldName).Free;
        SetObjectProp(Result, TheFieldName, TheDataBlobStream);
      end
      else
        // to do: add exceptions and classes saved as XML here ?
        // ignore unknown types for now :)
    else if {$IFDEF UNICODE}(TheFieldInfos[I].FieldKind = tkUString)
      {$ELSE}(TheFieldInfos[I].FieldKind = tkString){$ENDIF}
      and (TheFieldInfos[I].FieldType = 'TPasswordString') then
      Result.SetValueForField(TheFieldName, '') // reading passwords from the database is not allowed
    else begin
      TheValue := TBaseData.DBVariantToVariant(Self.FieldByName(TheDBName).AsVariant, TheFieldInfos[I].FieldType);
      if TheValue <> Null then
        Result.SetValueForField(TheFieldName, TheValue);
    end;
  end;
end;

function TBaseQuery.ReadSingleValue(SomeEntityClasses: array of TEntityClass; AllowNullResult: Boolean): TEntity;
var
  TheResults: TEntities;
begin
  TheResults := ReadMappedEntities(SomeEntityClasses);
  if (Length(TheResults) = 0) and AllowNullResult then
    Result := nil
  else if Length(TheResults) = 1 then
    Result := TheResults[0]
  else
    raise ESkyDatabaseRecordDoesNotExist.Create(Self, 'SelectQuerySingle', SomeEntityClasses[0].ClassName);
end;

class function TBaseQuery.SelectAll(AConnection: TObject; SomeEntityClasses: array of TEntityClass): TEntities;
var
  TheQuery: TBaseQuery;
begin
  Assert(Length(SomeEntityClasses) > 0, 'Select all requires at least one entity class');
  TheQuery := TBaseQuery.Create(AConnection);
  try
    TheQuery.SQL.Add('select ' + (AConnection as TBaseConnection).DBFieldList(SomeEntityClasses) +
      ' from ' + (AConnection as TBaseConnection).GetQuotedTableMapping(SomeEntityClasses[0]) + ';');
    TheQuery.Open;
    Result := TheQuery.ReadMappedEntities(SomeEntityClasses);
  finally
    TheQuery.Free;
  end;
end;

class function TBaseQuery.SelectById(AConnection: TObject; AnEntityClass: TEntityClass; AnId: TId): TEntity;
var
  TheQuery: TBaseQuery;
begin
  TheQuery := TBaseQuery.Create(AConnection);
  try

    TheQuery.SQL.Add('select * from ' +(AConnection as TBaseConnection).GetQuotedTableMapping(AnEntityClass));
    TheQuery.SQL.Add('where Id = ' + IdToStr(AnId) + ';');
    TheQuery.Open;
    Result := TheQuery.ReadMappedEntity(AnEntityClass.GetRelevantClassForRow(TheQuery));
  finally
    TheQuery.Free;
  end;
end;

class function TBaseQuery.SelectCount(AConnection: TObject; AQuery: TDBSQLQuery): Integer;
var
  TheQuery: TBaseQuery;
begin
  TheQuery := TranslateDBSQLQuery(AConnection, AQuery);
  try
    TheQuery.Open;
    Result := TheQuery.ReadCountData;
  finally
    TheQuery.Free;
  end;
end;

class function TBaseQuery.SelectField(AConnection: TObject; AQuery: TDBSQLQuery; const AFieldName: string): Variant;
var
  TheQuery: TBaseQuery;
begin
  TheQuery := TranslateDBSQLQuery(AConnection, AQuery);
  try
    TheQuery.Open;
    if TheQuery.Eof then
      raise ESkyDatabaseRecordDoesNotExist.Create(nil, 'TBaseQuery.SelectField', AFieldName);
    Result := TheQuery.Fields.FieldByName(AFieldName).AsVariant;
  finally
    TheQuery.Free;
  end;
end;

class function TBaseQuery.SelectQuery(AConnection: TObject; SomeEntityClasses: array of TEntityClass;
  AQuery: TDBSQLQuery): TEntities;
var
  TheQuery: TBaseQuery;
begin
  TheQuery := TranslateDBSQLQuery(AConnection, AQuery);
  try
    TheQuery.Open;
    Result := TheQuery.ReadMappedEntities(SomeEntityClasses);
  finally
    TheQuery.Free;
  end;
end;

class function TBaseQuery.SelectQuerySingle(AConnection: TObject; SomeEntityClasses: array of TEntityClass;
  AQuery: TDBSQLQuery; AllowNullResult: Boolean): TEntity;
var
  TheQuery: TBaseQuery;
begin
  TheQuery := TranslateDBSQLQuery(AConnection, AQuery);
  try
    TheQuery.Open;
    Result := TheQuery.ReadSingleValue(SomeEntityClasses, AllowNullResult);
  finally
    TheQuery.Free;
  end;
end;

procedure TBaseQuery.SetActive(Value: Boolean);
begin
  if Value then
  begin
    FLastQuery := SQL.Text;
    try
      inherited;
    except on E: Exception do
    begin
      FLastQuery := '';
      if (not (E is EUniError)) or (not HandleUniError(E as EUniError)) then
        raise;
    end;
    end;
  end;
  inherited;
end;

procedure TBaseQuery.SetQueryParams(SomeFieldInfos: TFieldInfoArray; AnEntity: TEntity);
var
  I: Integer;
  TheBlobStream: TMemoryStream;
  TheParam: TUniParam;
  TheValue: Variant;
begin
  for I := 0 to High(SomeFieldInfos) do
  begin
    TheParam := Params.FindParam(SomeFieldInfos[I].FieldName);
    if TheParam = nil then
      Continue;
    TheValue := AnEntity.GetValueForField(SomeFieldInfos[I].FieldName);
    if (AnEntity.Id <> IdNil) and ('TPasswordString' = SomeFieldInfos[I].FieldType) and
      (TheValue = '') then
      Continue
    else if (SomeFieldInfos[I].FieldKind = tkClass) then
    begin
      if(TypesConsts.TBlobType.ClassName = SomeFieldInfos[I].FieldType) then
      begin
        TheBlobStream := TMemoryStream(TVarData(TheValue).VPointer);
        if (not Assigned(TheBlobStream)) or (TheBlobStream.Size = 0) then
        begin
          TheParam.DataType := ftBlob;
          TheParam.Value := Unassigned;
        end
        else
        begin
          TheBlobStream.Position := 0;
          TheParam.LoadFromStream(TheBlobStream, ftBlob);
        end;
      end
    end
    else
      case SomeFieldInfos[I].FieldKind of
        tkFloat:
          if (SomeFieldInfos[I].FieldType = 'TDate') or
            (SomeFieldInfos[I].FieldType = 'TDateTime') or
            (SomeFieldInfos[I].FieldType = 'TTime') then
            if TheValue = 0 then
              Continue
            else
            begin
              TheParam.DataType := ftDateTime;
              TheParam.Value := TheValue;
            end
          else
          begin
            TheParam.DataType := ftFloat;
            TheParam.Value := TheValue;
          end;
        tkInteger, tkInt64:
          if (SomeFieldInfos[I].FieldType = 'TId') and (TheValue = 0) then
            Continue
          else
            TheParam.Value := TheValue;
        tkString{$IFDEF UNICODE}, tkUString{$ENDIF}, tkEnumeration:
          if TheValue = '' then
          begin
            TheParam.DataType := ftUnknown;
            Continue;
          end
          else
            TheParam.AsString := TheValue;
        else
          TheParam.Value := TheValue;
      end;
  end;
end;

class function TBaseQuery.TranslateDBSQLQuery(AConnection: TObject; AQuery: TDBSQLQuery): TBaseQuery;
var
  TheEntityStrings: TStrings;
  TheSQL, TheText, TheEntityName, TheFieldName: string;
  TheMappedFieldName: string;
  I, TheIndex: Integer;
  TheMapping: TEntityMapping;
begin
  TheSQL := '';
  TheEntityStrings := TStringList.Create;
  try
    for I := 0 to AQuery.Query.Count - 1 do
    begin
      if AQuery.Query[I] = '' then
        Continue;
      if AQuery.Query[I][1] <> '!' then
        TheSQL := TheSQL + AQuery.Query[I]
      else
      begin
        TheText := AQuery.Query[I];
        TheText := Copy(TheText, 2, Length(TheText) - 1);
        TheIndex := AQuery.Params.IndexOfKey(TheText);
        if TheIndex <> -1 then
          TheSQL := TheSQL + TBaseData.EscapeString(AQuery.Params[TheIndex].Value)
        else
        begin
          TheIndex := Pos('.', TheText);
          if TheIndex = 0 then
          begin
            TheEntityName := TheText;
            TheFieldName := '';
          end
          else
          begin
            TheEntityName := Copy(TheText, 1, TheIndex - 1);
            TheFieldName := Copy(TheText, TheIndex + 1, Length(TheText) - TheIndex);
          end;

          TBaseConnection.GetTableMapping(TheEntityName);
          if TheFieldName = '' then
            TheSQL := TheSQL + TBaseConnection.GetTableMapping(TheEntityName)
          else
          begin
            TheIndex := TheEntityStrings.IndexOf(TheEntityName);
            if TheIndex = -1 then
            begin
              TheMapping := TEntityMappingManager.GetMapping(TheEntityName);
              TheEntityStrings.AddObject(TheEntityName, Pointer(TheMapping));
            end
            else
              TheMapping := TEntityMapping(TheEntityStrings.Objects[TheIndex]);
            TheMappedFieldName := '';
            if Assigned(TheMapping) then
              TheMappedFieldName := TheMapping.GetValueForField(TheFieldName);
            if TheMappedFieldName = '' then
              TheMappedFieldName := TheFieldName;
            TheSQL := TheSQL + TBaseData.EscapeString(TheMappedFieldName);
          end;
        end;
      end;
    end;
  finally
    TheEntityStrings.Free;
  end;
  Result := TBaseQuery.Create(AConnection);
  Result.Sql.Add(TheSQL);
end;

initialization
  RegisterDBSQLQuery(QuerySelectOneToManyForId);
  RegisterDBSQLQuery(QuerySelectOneToManyCountForId);
  RegisterDBSQLQuery(QuerySelectManyToManyForId);

end.
