unit EntityXmlReader;

interface

uses
  Classes, Entity, OmniXML, EntityReader, EntityList, SkyIdList, SkyLists;

type
  TEntityXmlReaderContext = class;

  TEntityXmlReader = class(TEntityReader)
  private
    class function InternalReadEntity(AContext: TEntityXmlReaderContext;
      AnExpectedClassType: TEntityClass = nil): TEntity;
    class function InternalLoadEntityList(AContext: TEntityXmlReaderContext): TEntityList;
    class function InternalLoadSkyIdList(AContext: TEntityXmlReaderContext): TSkyIdList;
    class function InternalLoadSkyStringList(AContext: TEntityXmlReaderContext): TSkyStringList;
  protected
    class function GetClassNameFromXmlNode(ANode: IXmlNode): string; virtual;
  public
    class function ReadEntity(AStream: TStream; AnExpectedClassType: TEntityClass = nil): TEntity; override; deprecated;
  end;

  TEntityXmlReaderContext = class(TEntityReaderContext)
  private
    FXmlDoc: IXMLDocument;
    FXmNode: IXMLNode;
  public
    constructor Create(AStream: TStream; AXmlDoc: IXMLDocument); reintroduce;
    property XmlDoc: IXMLDocument read FXmlDoc write FXmlDoc;
    property XmNode: IXMLNode read FXmNode write FXmNode;
  end;

implementation

uses
  ExceptionClasses, TypesConsts, TypInfo, GenericEntity, OmniXMLUtils, JclMime,
  EntityManager, Variants, SkyException, MessageInfoData;

{ TEntityXmlReaderContext }

constructor TEntityXmlReaderContext.Create(AStream: TStream;
  AXmlDoc: IXMLDocument);
begin
  inherited Create(AStream);
  FXmlDoc := AXmlDoc;
  FXmNode := FXMLDoc.DocumentElement;
end;

{ TEntityXmlReader }

class function TEntityXmlReader.InternalLoadEntityList(
  AContext: TEntityXmlReaderContext): TEntityList;
var
  I: Integer;
  TheOldRoot: IXMLNode;
  TheEntity: TEntity;
begin
  Result := TEntityList.Create(False, True);
  try
    for I := 0 to AContext.XmNode.ChildNodes.Length - 1 do
    begin
      TheOldRoot := AContext.XmNode;
      try
        AContext.XmNode := AContext.XmNode.ChildNodes.Item[I];
        TheEntity := InternalReadEntity(AContext);
        try
          Result.Add(TheEntity);
        except
          TheEntity.Free;
          raise;
        end;
      finally
        AContext.XmNode := TheOldRoot;
      end;
    end;
  except
    Result.Free;
    raise;
  end;
end;

class function TEntityXmlReader.InternalLoadSkyIdList(AContext: TEntityXmlReaderContext): TSkyIdList;
var
  TheRowNode, TheNode, TheOldRoot: IXMLNode;
  TheEntity: TEntity;
  TheId: TId;
  I, J: Integer;
begin
  Result := TSkyIdList.Create(True);
  try
    for I := 0 to AContext.XmNode.ChildNodes.Length - 1 do
    begin
      TheRowNode := AContext.XmNode.ChildNodes.Item[I];
      if (TheRowNode.NodeName <> 'Row') or (not TheRowNode.HasChildNodes) then
        raise ESkyInvalidValueForField.Create(nil, 'TEntityXmlReader.InternalLoadSkyIdList',
          TheRowNode.NodeName, TheRowNode.Text);
      TheId := IdNil;
      TheEntity := nil;
      for J := 0 to TheRowNode.ChildNodes.Count - 1 do
      begin
        TheNode := TheRowNode.ChildNodes.Item[J];
        if TheNode.NodeName = 'Id' then
        begin
          if not XMLStrToInt64(TheNode.Text, Int64(TheId)) then
            raise ESkyInvalidValueForField.Create(nil, 'TEntityXmlReader.InternalLoadSkyIdList', TheNode.NodeName, TheNode.Text)
        end
        else if (TheNode.NodeName = 'Object') and (TheNode.HasChildNodes) then
        begin
          TheOldRoot := AContext.XmNode;
          try
            AContext.XmNode := TheNode.ChildNodes.Item[0];
            TheEntity := InternalReadEntity(AContext);
          finally
            AContext.XmNode := TheOldRoot;
          end;
        end
        else
          raise ESkyInvalidValueForField.Create(nil, 'TEntityXmlReader.InternalLoadSkyIdList', AContext.XmNode.NodeName, TheNode.Text)
      end;
      try
        Result.Add(TheId, TheEntity);
      except
        TheEntity.Free;
        raise;
      end;
    end;
  except
    Result.Free;
    raise;
  end;
end;

class function TEntityXmlReader.InternalLoadSkyStringList(AContext: TEntityXmlReaderContext): TSkyStringList;
var
  TheRowNode, TheNode, TheOldRoot: IXMLNode;
  TheEntity: TEntity;
  TheString: string;
  I, J: Integer;
begin
  Result := TSkyStringList.Create(True);
  try
    for I := 0 to AContext.XmNode.ChildNodes.Length - 1 do
    begin
      TheRowNode := AContext.XmNode.ChildNodes.Item[I];
      if (TheRowNode.NodeName <> 'Row') or (not TheRowNode.HasChildNodes) then
        raise ESkyInvalidValueForField.Create(nil, 'TEntityXmlReader.InternalLoadSkyStringList',
          TheRowNode.NodeName, TheRowNode.Text);
      TheString := '';
      TheEntity := nil;
      for J := 0 to TheRowNode.ChildNodes.Count - 1 do
      begin
        TheNode := TheRowNode.ChildNodes.Item[J];
        if TheNode.NodeName = 'Key' then
          TheString := TheNode.Text
        else if (TheNode.NodeName = 'Object') and (TheNode.HasChildNodes) then
        begin
          TheOldRoot := AContext.XmNode;
          try
            AContext.XmNode := TheNode.ChildNodes.Item[0];
            TheEntity := InternalReadEntity(AContext);
          finally
            AContext.XmNode := TheOldRoot;
          end;
        end
        else
          raise ESkyInvalidValueForField.Create(nil, 'TEntityXmlReader.InternalLoadSkyStringList', AContext.XmNode.NodeName, TheNode.Text)
      end;
      try
        Result.Add(TheString, TheEntity);
      except
        TheEntity.Free;
        raise;
      end;
    end;
  except
    Result.Free;
    raise;
  end;
end;

class function TEntityXmlReader.GetClassNameFromXmlNode(ANode: IXmlNode): string;
begin
  Result := ANode.NodeName;
end;

class function TEntityXmlReader.InternalReadEntity(AContext: TEntityXmlReaderContext;
  AnExpectedClassType: TEntityClass = nil): TEntity;
var
  TheBoolValue: Boolean;
  TheDateTime: TDateTime;
  TheExtendedValue: Extended;
  TheFieldInfo: TFieldInfo;
  TheInt64Value: Int64;
  TheIntValue: Integer;
  TheNode: IXMLNode;
  TheObject: TObject;
  TheOldRoot: IXMLNode;
  TheStream: TMemoryStream;
  TheString: AnsiString;
  TheValue: Variant;
  I: Integer;
begin
  Result := nil;
  if AContext.XmNode = nil then
    Exit;

  Result := TEntity.CreateEntityOfClass(GetClassNameFromXmlNode(AContext.XmNode), AnExpectedClassType);
  if not AContext.XmNode.HasChildNodes then
    Exit;
  try
    for I := 0 to AContext.XmNode.ChildNodes.Length - 1 do
    begin
      TheNode := AContext.XmNode.ChildNodes.Item[I];
      if not Result.InheritsFrom(TGenericEntity) then
      begin
        TheFieldInfo := Result.GetFieldInfo(TheNode.NodeName);
        case TheFieldInfo.FieldKind of
          tkClass: begin
            if TBlobType.ClassName = TheFieldInfo.FieldType then
            // load blob from Base64 encoding
            begin
              TheStream := TMemoryStream.Create;
              try
                TheString := AnsiString(TheNode.Text);
                TheStream.Write(TheString[1], Length(TheString));
                TheStream.Position := 0;
                TheObject := TBlobType.Create;
                try
                  MimeDecodeStream(TheStream, TheObject as TStream);
                except
                  TheObject.Free;
                  raise;
                end;
              finally
                TheStream.Free;
              end;
            end
            else if TEntityList.ClassName = TheFieldInfo.FieldType then
            // load a sub-entity list
            begin
              TheOldRoot := AContext.XmNode;
              try
                AContext.XmNode := TheNode;
                TheObject := InternalLoadEntityList(AContext);
              finally
                AContext.XmNode := TheOldRoot;
              end;
            end
            else if TSkyIdList.ClassName = TheFieldInfo.FieldType then
            // load a sub-entity list
            begin
              TheOldRoot := AContext.XmNode;
              try
                AContext.XmNode := TheNode;
                TheObject := InternalLoadSkyIdList(AContext);
              finally
                AContext.XmNode := TheOldRoot;
              end;
            end
            else
            // load a sub-entity or Exception
            begin
              if (not TheNode.HasChildNodes) or (TheNode.ChildNodes.Length <> 1) then
                raise ESkyInvalidValueForField.Create(nil, 'TEntityXmlReader.InternalReadEntity',
                  AContext.XmNode.NodeName, TheNode.Text);
              TheOldRoot := AContext.XmNode;
              try
                AContext.XmNode := TheNode.ChildNodes.Item[0];
                if ESkyException.ClassName = TheFieldInfo.FieldType then
                begin
                  TheObject := InternalReadEntity(AContext,
                    TEntityManager.GetEntityClassForName(TMessageInfoData.ClassName));
                  TheObject := ESkyException.CreateFromMessageInfo(TheObject as TMessageInfoData);
                end
                else
                  TheObject := InternalReadEntity(AContext,
                    TEntityManager.GetEntityClassForName(TheFieldInfo.FieldType));
              finally
                AContext.XmNode := TheOldRoot;
              end;
            end;
            GetObjectProp(Result, TheNode.NodeName).Free;
            SetObjectProp(Result, TheNode.NodeName, TheObject);
          end;

          tkInteger:
            if XMLStrToInt(TheNode.Text, TheIntValue) then
              TheValue := TheIntValue
            else
              raise ESkyInvalidValueForField.Create(nil, 'TEntityXmlReader.InternalReadEntity',
                  AContext.XmNode.NodeName, TheNode.Text);
          tkChar:
          begin
            {$IFDEF UNICODE}
            TheValue := Char(TheNode.Text[1]);
            {$ELSE}
            TheValue := Char(Utf8ToAnsi(UTF8Encode(TheNode.Text))[1]);
            {$ENDIF}  // UNICODE
          end;

          tkWChar:
            TheValue := Cardinal(TheNode.Text[1]);

          tkSet:
            TheValue := TheNode.Text;

          tkEnumeration:
            if 'Boolean' = TheFieldInfo.FieldType then
            begin
              if XMLStrToBool(TheNode.Text, TheBoolValue) then
                TheValue := TheBoolValue
              else
                raise ESkyInvalidValueForField.Create(nil, 'TEntityXmlReader.InternalReadEntity',
                  AContext.XmNode.NodeName, TheNode.Text)
            end
            else
              TheValue := TheNode.Text;

          tkString, tkLString, tkWString {$IFDEF UNICODE}, tkUString {$ENDIF}:
            // the codepage should be treated in the future
            // see OmniXML persistent for an example
            TheValue := TheNode.Text;

          tkInt64:
            if XMLStrToInt64(TheNode.Text, TheInt64Value) then
              TheValue := TheInt64Value
            else
              raise ESkyInvalidValueForField.Create(nil, 'TEntityXmlReader.InternalReadEntity',
                AContext.XmNode.NodeName, TheNode.Text);

          tkFloat:
          begin
            if ('TDate' = TheFieldInfo.FieldType) or ('TDateTime' =
              TheFieldInfo.FieldType) or ('TTime' = TheFieldInfo.FieldType) then
              if XMLStrToDateTime(TheNode.Text, TheDateTime) then
                if 'TDate' = TheFieldInfo.FieldType then
                  TheValue := Trunc(TheDateTime)
                else if 'TDateTime' = TheFieldInfo.FieldType then
                  TheValue := TheDateTime
                else
                  TheValue := Frac(TheDateTime)
              else
                raise ESkyInvalidValueForField.Create(nil, 'TEntityXmlReader.InternalReadEntity',
                  AContext.XmNode.NodeName, TheNode.Text)
            else begin
              if not XMLStrToExtended(TheNode.Text, TheExtendedValue) then
                raise ESkyInvalidValueForField.Create(nil, 'TEntityXmlReader.InternalReadEntity', TheNode.NodeName,
                  TheNode.Text);
              TheValue := TheExtendedValue;
            end;
          end
          else
            TheValue := TheNode.Text;
        end;
      end
      else
      begin
        if VarIsNull(TheNode.Text) then
          TheValue := ''
        else
          TheValue := TheNode.Text;
      end;
      // tkClass sets its own fieldvalue
      if TheFieldInfo.FieldKind <> tkClass then
        Result.SetValueForField(TheNode.NodeName, TheValue);
    end;
  except
    Result.Free;
    raise;
  end;
end;

class function TEntityXmlReader.ReadEntity(AStream: TStream; AnExpectedClassType: TEntityClass): TEntity;
var
  TheXmlDoc: IXMLDocument;
  TheContext: TEntityXmlReaderContext;
begin
  TheXmlDoc := CreateXMLDoc;
  if not TheXmlDoc.LoadFromStream(AStream) then
    raise ESkyErrorLoadingXML.Create(nil, 'TEntityXmlReader.ReadEntity', TheXmlDoc.ParseError.Reason);
  TheContext := TEntityXmlReaderContext.Create(AStream, TheXmlDoc);
  try
    Result := InternalReadEntity(TheContext, AnExpectedClassType);
  finally
    TheContext.Free;
  end;
end;

end.