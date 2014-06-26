unit EntityJSonReader;

{$mode objfpc}{$H+}

interface

uses
  Classes, Entity, EntityReader, EntityList, superobject, TypesConsts, SkyIdList;

type
  TEntityJSonReaderContext = class;

  TEntityJSonReader = class(TEntityReader)
  private
    class function InternalReadEntity(AContext: TEntityJSonReaderContext;
      AnExpectedClassType: TEntityClass = nil): TEntity;
    class procedure ReadEntityProperties(AnEntity: TEntity;
      SomeProperties: ISuperObject; AContext: TEntityJSonReaderContext);

    class function InternalReadBlobField(AProperty: TSuperAvlEntry): TBlobType;
    class function InternalReadEntityList(AContext: TEntityJSonReaderContext;
      AProperty: TSuperAvlEntry): TEntityList;
    class function InternalReadSkyIdList(AContext: TEntityJSonReaderContext;
      AProperty: TSuperAvlEntry): TSkyIdList;
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
  ExceptionClasses, TypInfo, GenericEntity, EntityManager, Variants, SkyException, MessageInfoData, SysUtils,
  TypesFunctions;

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
            TheObject := InternalReadBlobField(TheProperty)
          else if TEntityList.ClassName = TheFieldInfo.FieldType then
            TheObject := InternalReadEntityList(AContext, TheProperty)
          else if TSkyIdList.ClassName = TheFieldInfo.FieldType then
            TheObject := InternalReadSkyIdList(AContext, TheProperty)
          else
          // load a sub-entity or Exception
          begin
            if TheProperty.Value.DataType = stNull then
              TheObject := nil
            else
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
        tkBool:
          TheValue := CompareText(TheProperty.Value.AsString, 'True') = 0;
        tkAString, tkSString, tkLString, tkWString {$IFDEF UNICODE}, tkUString {$ENDIF}:
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
            if StrToDateTime(TheProperty.Value.AsString, TheDateTime) then
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
            else if not StrToExtended(TheProperty.Value.AsString, TheExtendedValue) then
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

class function TEntityJSonReader.InternalReadBlobField(AProperty: TSuperAvlEntry): TBlobType;
var
  TheString: AnsiString;
  TheStream: TMemoryStream;
begin
  // load blob from Base64 encoding
  TheStream := TMemoryStream.Create;
  try
    TheString := AnsiString(AProperty.Value.AsString);
    TheStream.Write(TheString[1], Length(TheString));
    TheStream.Position := 0;
    Result := TBlobType.Create;
    try
//      MimeDecodeStream(TheStream, Result as TStream);
    except
      Result.Free;
      raise;
    end;
  finally
    TheStream.Free;
  end;
end;

class function TEntityJSonReader.InternalReadEntityList(
  AContext: TEntityJSonReaderContext; AProperty: TSuperAvlEntry): TEntityList;
var
  TheEntity: TEntity;
  TheOldObject: ISuperObject;
  I: Integer;
begin
  // load a sub-entity list
  TheOldObject := AContext.ObjectProp;
  try
    AContext.ObjectProp := AProperty.Value;
    Result := TEntityList.Create(False, True);
    try
      if AProperty.Value.AsArray = nil then
        Exit;
      for I := 0 to AProperty.Value.AsArray.Length - 1 do
      begin
        AContext.ObjectProp := AProperty.Value.AsArray[I];
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

class function TEntityJSonReader.InternalReadSkyIdList(AContext: TEntityJSonReaderContext;
  AProperty: TSuperAvlEntry): TSkyIdList;
var
  TheEntity: TEntity;
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
    TheClassName := AProperty.Value.AsObject.S['ClassName'];
    if TheClassName <> TSkyIdList.ClassName then
      Exit;
    TheRowsObject := AProperty.Value.AsObject.O['Values'];
    if (TheRowsObject = nil) or (TheRowsObject.AsArray = nil) then
      Exit;
    Result := TSkyIdList.Create(True);
    for I := 0 to TheRowsObject.AsArray.Length - 1 do
    begin
      TheRowObject := TheRowsObject.AsArray.O[I];
      TheId := TheRowObject.AsObject.I['Id'];
      TheEntity := nil;
      TheObject := TheRowObject.AsObject.O['Object'];
      if TheObject <> nil then
      begin
        AContext.ObjectProp := TheObject;
        TheEntity := InternalReadEntity(AContext);
      end;
      Result.Add(TheId, TheEntity);
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
    TheWideString := UTF8Encode(TheAnsiString);
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
