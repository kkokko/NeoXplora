unit EntityJSonReader;

interface

uses
  Classes, Entity, EntityReader, EntityList, superobject, TypesConsts, SkyIdList, SkyLists;

type
  TEntityJSonReaderContext = class;

  TEntityJSonReader = class(TEntityReader)
  private
    class function InternalReadEntity(AContext: TEntityJSonReaderContext;
      AnExpectedClassType: TEntityClass = nil): TEntity;
    class function InternalReadObject(AContext: TEntityJSonReaderContext): TObject;
    class procedure ReadEntityProperties(AnEntity: TEntity;
      SomeProperties: ISuperObject; AContext: TEntityJSonReaderContext);
    class function InternalReadBlobField(AProperty: ISuperObject): TBlobType;
    class function InternalReadEntityList(AContext: TEntityJSonReaderContext;
      AProperty: ISuperObject): TEntityList;
    class function InternalReadSkyIdList(AContext: TEntityJSonReaderContext;
      AProperty: ISuperObject): TSkyIdList;
    class function InternalReadSkyStringList(AContext: TEntityJSonReaderContext;
      AProperty: ISuperObject): TSkyStringList;
    class function InternalReadSkyStringStringList(AContext: TEntityJSonReaderContext;
      AProperty: ISuperObject): TSkyStringStringList;
  public
    class function ReadEntity(AStream: TStream; AnExpectedClassType: TEntityClass = nil): TEntity; override;
  end;

  TEntityJSonReaderContext = class(TEntityReaderContext)
  private
    FObjectProp: ISuperObject;
  public
    constructor Create(AStream: TStream; AnObject: ISuperObject); reintroduce;
    property ObjectProp: ISuperObject read FObjectProp write FObjectProp;
  end;

implementation

uses
  ExceptionClasses, TypInfo, GenericEntity, EntityManager, Variants, SkyException, MessageInfoData, JclMime,
  OmniXMLUtils, SysUtils;

{ TEntityJSonReaderContext }

class function TEntityJSonReader.InternalReadEntity(AContext: TEntityJSonReaderContext;
  AnExpectedClassType: TEntityClass): TEntity;
var
  TheProperties: ISuperObject;
  TheClassName: string;
begin
  TheClassName := AContext.ObjectProp.S['ClassName'];
  TheProperties := AContext.ObjectProp.O['Properties'];
  Result := TEntity.CreateEntityOfClass(TheClassName, AnExpectedClassType);
  try
    ReadEntityProperties(Result, TheProperties, AContext);
  except
    Result.Free;
    raise;
  end;
end;

class procedure TEntityJSonReader.ReadEntityProperties(AnEntity: TEntity;
  SomeProperties: ISuperObject; AContext: TEntityJSonReaderContext);
var
  TheDateTime: TDateTime;
  TheExtendedValue: Extended;
  TheFieldInfo: TFieldInfo;
  TheObject: TObject;
  TheOldObject: ISuperObject;
  TheProperty: TSuperAvlEntry;
  TheValue: Variant;
begin
  if SomeProperties.AsObject = nil then
    Exit;
  for TheProperty in SomeProperties.AsObject do
  begin
    if TheProperty.Value.DataType = stNull then
      Continue;
    if not AnEntity.InheritsFrom(TGenericEntity) then
    begin
      TheFieldInfo := AnEntity.GetFieldInfo(TheProperty.Name);
      case TheFieldInfo.FieldKind of
        tkClass: begin
          if TBlobType.ClassName = TheFieldInfo.FieldType then
            TheObject := InternalReadBlobField(TheProperty.Value)
          else if TEntityList.ClassName = TheFieldInfo.FieldType then
            TheObject := InternalReadEntityList(AContext, TheProperty.Value)
          else if TSkyIdList.ClassName = TheFieldInfo.FieldType then
            TheObject := InternalReadSkyIdList(AContext, TheProperty.Value)
          else if TSkyStringList.ClassName = TheFieldInfo.FieldType then
            TheObject := InternalReadSkyStringList(AContext, TheProperty.Value)
          else if TSkyStringStringList.ClassName = TheFieldInfo.FieldType then
            TheObject := InternalReadSkyStringStringList(AContext, TheProperty.Value)
          else
          // load a sub-entity or Exception
          begin
            if (TheProperty.Value.DataType <> stObject) or (TheProperty.Value.AsObject.count = 0) then
              raise ESkyInvalidValueForField.Create(nil, 'TEntityJSonReader.InternalReadEntity',
                TheProperty.Name, TheProperty.Value.AsString);
            TheOldObject := AContext.ObjectProp;
            try
              AContext.ObjectProp := TheProperty.Value;
              if ESkyException.ClassName = TheFieldInfo.FieldType then
              begin
                TheObject := TMessageInfoData.Create;
                try
                  ReadEntityProperties(TheObject as TMessageInfoData,
                    AContext.ObjectProp.O['Properties'], AContext);
                  TheObject := ESkyException.CreateFromMessageInfo(TheObject as TMessageInfoData);
                except
                  TheObject.Free;
                  raise;
                end;
              end
              else
                TheObject := InternalReadEntity(AContext,
                  TEntityManager.GetEntityClassForName(TheFieldInfo.FieldType));
            finally
              AContext.ObjectProp := TheOldObject;
            end;
          end;
          GetObjectProp(AnEntity, TheProperty.Name).Free;
          SetObjectProp(AnEntity, TheProperty.Name, TheObject);
        end;

        tkInteger, tkInt64:
          TheValue := TheProperty.Value.AsInteger;

        tkChar:
        begin
          {$IFDEF UNICODE}
          TheValue := TheProperty.Value.AsString[1];
          {$ELSE}
          TheValue := Char(Utf8ToAnsi(UTF8Encode(TheProperty.Value.AsString))[1]);
          {$ENDIF}  // UNICODE
        end;

        tkWChar:
          TheValue := Cardinal(TheProperty.Value.AsString[1]);

        tkSet:
          TheValue := TheProperty.Value.AsString;

        tkEnumeration:
          if 'Boolean' = TheFieldInfo.FieldType then
            TheValue := CompareText(TheProperty.Value.AsString, 'True') = 0
          else
            TheValue := TheProperty.Value.AsString;

        tkString, tkLString, tkWString {$IFDEF UNICODE}, tkUString {$ENDIF}:
          // the codepage should be treated in the future
          // see OmniXML persistent for an example
          if TheProperty.Value.DataType = stNull then
            TheValue := ''
          else
            TheValue := TheProperty.Value.AsString;

        tkFloat:
        begin
          if ('TDate' = TheFieldInfo.FieldType) or ('TDateTime' =
            TheFieldInfo.FieldType) or ('TTime' = TheFieldInfo.FieldType) then
            if XMLStrToDateTime(TheProperty.Value.AsString, TheDateTime) then
              if 'TDate' = TheFieldInfo.FieldType then
                TheValue := Trunc(TheDateTime)
              else if 'TDateTime' = TheFieldInfo.FieldType then
                TheValue := TheDateTime
              else
                TheValue := Frac(TheDateTime)
            else
              raise ESkyInvalidValueForField.Create(nil, 'TEntityXmlReader.InternalReadEntity',
                TheProperty.Name, TheProperty.Value.AsString)
          else begin
            if '' = TheProperty.Value.AsString then
              TheExtendedValue := 0
            else if not XMLStrToExtended(TheProperty.Value.AsString, TheExtendedValue) then
              raise ESkyInvalidValueForField.Create(nil, 'TEntityXmlReader.InternalReadEntity', TheProperty.Name,
                TheProperty.Value.AsString);
            TheValue := TheExtendedValue;
          end;
        end;
      end;
    end;
    // tkClass sets its own fieldvalue
    if TheFieldInfo.FieldKind <> tkClass then
      AnEntity.SetValueForField(TheProperty.Name, TheValue);
  end;
end;

class function TEntityJSonReader.InternalReadBlobField(AProperty: ISuperObject): TBlobType;
var
  TheString: AnsiString;
  TheStream: TMemoryStream;
begin
  // load blob from Base64 encoding
  TheStream := TMemoryStream.Create;
  try
    TheString := AnsiString(AProperty.AsString);
    TheStream.Write(TheString[1], Length(TheString));
    TheStream.Position := 0;
    Result := TBlobType.Create;
    try
      MimeDecodeStream(TheStream, Result as TStream);
    except
      Result.Free;
      raise;
    end;
  finally
    TheStream.Free;
  end;
end;

class function TEntityJSonReader.InternalReadEntityList(
  AContext: TEntityJSonReaderContext; AProperty: ISuperObject): TEntityList;
var
  TheEntity: TEntity;
  TheOldObject: ISuperObject;
  I: Integer;
begin
  // load a sub-entity list
  TheOldObject := AContext.ObjectProp;
  try
    AContext.ObjectProp := AProperty;
    Result := TEntityList.Create(False, True);
    try
      if AProperty.AsArray = nil then
        Exit;
      for I := 0 to AProperty.AsArray.Length - 1 do
      begin
        AContext.ObjectProp := AProperty.AsArray[I];
        TheEntity := InternalReadEntity(AContext);
        try
          Result.Add(TheEntity);
        except
          TheEntity.Free;
          raise;
        end;
      end;
    except
      Result.Free;
      raise;
    end;
  finally
    AContext.ObjectProp := TheOldObject;
  end;
end;

class function TEntityJSonReader.InternalReadObject(AContext: TEntityJSonReaderContext): TObject;
var
  TheClassName: string;
begin
  TheClassName := AContext.ObjectProp.S['ClassName'];
  if TheClassName = TSkyIdList.ClassName then
    Result := InternalReadSkyIdList(AContext, AContext.ObjectProp)
  else if TheClassName = TSkyStringList.ClassName then
    Result := InternalReadSkyStringList(AContext, AContext.ObjectProp)
  else if TheClassName = TSkyStringStringList.ClassName then
    Result := InternalReadSkyStringStringList(AContext, AContext.ObjectProp)
  else
    Result := InternalReadEntity(AContext);
end;

class function TEntityJSonReader.InternalReadSkyIdList(AContext: TEntityJSonReaderContext;
  AProperty: ISuperObject): TSkyIdList;
var
  TheListObject: TObject;
  TheOldObject: ISuperObject;
  TheClassName: string;
  TheRowsObject, TheRowObject, TheObject: ISuperObject;
  TheId: TId;
  I: Integer;
begin
  Result := nil;
  // load a sub-entity list
  TheOldObject := AContext.ObjectProp;
  try
    TheClassName := AProperty.AsObject.S['ClassName'];
    if TheClassName <> TSkyIdList.ClassName then
      Exit;
    TheRowsObject := AProperty.AsObject.O['Values'];
    if (TheRowsObject = nil) or (TheRowsObject.AsArray = nil) then
      Exit;
    Result := TSkyIdList.Create(True);
    for I := 0 to TheRowsObject.AsArray.Length - 1 do
    begin
      TheRowObject := TheRowsObject.AsArray.O[I];
      TheId := TheRowObject.AsObject.I['Id'];
      TheListObject := nil;
      TheObject := TheRowObject.AsObject.O['Object'];
      if TheObject <> nil then
      begin
        AContext.ObjectProp := TheObject;
        TheListObject := InternalReadObject(AContext);
      end;
      Result.Add(TheId, TheListObject);
    end;
  finally
    AContext.ObjectProp := TheOldObject;
  end;
end;

class function TEntityJSonReader.InternalReadSkyStringList(AContext: TEntityJSonReaderContext;
  AProperty: ISuperObject): TSkyStringList;
var
  TheListObject: TObject;
  TheOldObject: ISuperObject;
  TheClassName: string;
  TheRowsObject, TheRowObject, TheObject: ISuperObject;
  TheString: string;
  I: Integer;
begin
  Result := nil;
  // load a sub-entity list
  TheOldObject := AContext.ObjectProp;
  try
    TheClassName := AProperty.AsObject.S['ClassName'];
    if TheClassName <> TSkyStringList.ClassName then
      Exit;
    TheRowsObject := AProperty.AsObject.O['Values'];
    if (TheRowsObject = nil) or (TheRowsObject.AsArray = nil) then
      Exit;
    Result := TSkyStringList.Create(True);
    for I := 0 to TheRowsObject.AsArray.Length - 1 do
    begin
      TheRowObject := TheRowsObject.AsArray.O[I];
      TheString := TheRowObject.AsObject.S['Key'];
      TheListObject := nil;
      TheObject := TheRowObject.AsObject.O['Object'];
      if TheObject <> nil then
      begin
        AContext.ObjectProp := TheObject;
        TheListObject := InternalReadObject(AContext);
      end;
      Result.Add(TheString, TheListObject);
    end;
  finally
    AContext.ObjectProp := TheOldObject;
  end;
end;

class function TEntityJSonReader.InternalReadSkyStringStringList(AContext: TEntityJSonReaderContext;
  AProperty: ISuperObject): TSkyStringStringList;
var
  TheOldObject: ISuperObject;
  TheClassName: string;
  TheRowsObject, TheRowObject: ISuperObject;
  TheObject, TheString: string;
  I: Integer;
begin
  Result := nil;
  // load a sub-entity list
  TheOldObject := AContext.ObjectProp;
  try
    TheClassName := AProperty.AsObject.S['ClassName'];
    if TheClassName <> TSkyStringStringList.ClassName then
      Exit;
    TheRowsObject := AProperty.AsObject.O['Values'];
    if (TheRowsObject = nil) or (TheRowsObject.AsArray = nil) then
      Exit;
    Result := TSkyStringStringList.Create;
    for I := 0 to TheRowsObject.AsArray.Length - 1 do
    begin
      TheRowObject := TheRowsObject.AsArray.O[I];
      TheString := TheRowObject.AsObject.S['Key'];
      TheObject := TheRowObject.AsObject.S['Object'];
      Result.Add(TheString, TheObject);
    end;
  finally
    AContext.ObjectProp := TheOldObject;
  end;
end;

class function TEntityJSonReader.ReadEntity(AStream: TStream; AnExpectedClassType: TEntityClass): TEntity;
var
  TheAnsiString: AnsiString;
  TheBom: Word;
  TheContext: TEntityJSonReaderContext;
  TheObject: ISuperObject;
  TheWideString: WideString;
begin
  AStream.Read(TheBom, 2);
  AStream.Position := AStream.Position - 2;
  if(TheBom = $FFFE) then
    TheObject := TSuperObject.ParseStream(AStream, True)
  else
  begin
    // utf 8
    SetLength(TheAnsiString, AStream.Size - AStream.Position);
    AStream.Read(TheAnsiString[1], AStream.Size - AStream.Position);
    TheWideString := UTF8ToWideString(TheAnsiString);
    TheObject := superobject.SO(TheWideString);
  end;
  if not Assigned(TheObject) then
    raise ESkyErrorLoadingJson.Create(nil, 'TEntityJSonReader.ReadEntity', 'Invalid content');
  TheContext := TEntityJSonReaderContext.Create(AStream, TheObject);
  try
    Result := InternalReadEntity(TheContext, AnExpectedClassType);
  finally
    TheContext.Free;
  end;
end;

{ TEntityJSonReaderContext }

constructor TEntityJSonReaderContext.Create(AStream: TStream;
  AnObject: ISuperObject);
begin
  inherited Create(AStream);
  FObjectProp := AnObject;
end;

end.