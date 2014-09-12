unit EntityXmlWriter;

interface

uses
  Classes, Entity, EntityWriter, TypesConsts, EntityList, OmniXML, SkyException, SkyIdList, SkyLists;

type
  TEntityXmlWriter = class(TEntityWriter)
  private
    class procedure WriteEntityContent(AnEntity: TEntity; AContext: TEntityWriterContext);
  protected
    class function GetClassNameForEntity(AnEntity: TEntity): string; virtual;
    class procedure WriteProperty(AFieldInfo: TFieldInfo; AValue: Variant;
      AContext: TEntityWriterContext); override;
    class procedure WriteBlobProperty(AFieldInfo: TFieldInfo;  ABlob: TBlobType;
      AContext: TEntityWriterContext); override;
    class procedure WriteEntityProperty(AFieldInfo: TFieldInfo;
      AnEntity: TEntity; AContext: TEntityWriterContext); override;
    class procedure WriteESkyExceptionProperty(AFieldInfo: TFieldInfo;
      AnException: ESkyException; AContext: TEntityWriterContext); override;
    class procedure WriteEntityListProperty(AFieldInfo: TFieldInfo;
      AList: TEntityList; AContext: TEntityWriterContext); override;
    class procedure WriteSkyIdListProperty(AFieldInfo: TFieldInfo;
      AList: TSkyIdList; AContext: TEntityWriterContext); override;
    class procedure WriteSkyStringListProperty(AFieldInfo: TFieldInfo;
      AList: TSkyStringList; AContext: TEntityWriterContext); override;
  public
    class procedure WriteEntity(AStream: TStream; AnEntity: TEntity); override; deprecated;
  end;

  TEntityXmlWriterContext = class(TEntityWriterContext)
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
  TypInfo, OmniXMLUtils, Variants, JclMime, SysUtils;

{ TEntityXmlWriter }

class procedure TEntityXmlWriter.WriteEntity(AStream: TStream; AnEntity: TEntity);
var
  TheXmlDoc: IXMLDocument;
  TheContext: TEntityXmlWriterContext;
begin
  TheXmlDoc := CreateXMLDoc;
  TheXmlDoc.DocumentElement := TheXmlDoc.CreateElement(GetClassNameForEntity(AnEntity));
  TheContext := TEntityXmlWriterContext.Create(AStream, TheXmlDoc);
  try
    AddEntity(AnEntity, TheContext);
  finally
    TheContext.Free;
  end;
  TheXmlDoc.SaveToStream(AStream);
end;

class function TEntityXmlWriter.GetClassNameForEntity(AnEntity: TEntity): string;
begin
  Result := AnEntity.ClassName;
end;

class procedure TEntityXmlWriter.WriteBlobProperty(AFieldInfo: TFieldInfo;
  ABlob: TBlobType; AContext: TEntityWriterContext);
var
  TheDestStream: TMemoryStream;
  TheString: AnsiString;
begin
  TheDestStream := TMemoryStream.Create;
  try
    ABlob.Position := 0;
    MimeEncodeStreamNoCRLF(ABlob, TheDestStream);
    if TheDestStream.Size = 0 then
      TheString := ''
    else
    begin
      SetLength(TheString, TheDestStream.Size);
      TheDestStream.Position := 0;
      TheDestStream.Read(TheString[1], TheDestStream.Size);
    end;
  finally
    TheDestStream.Free;
  end;
  WriteProperty(AFieldInfo, TheString, AContext);
end;

class procedure TEntityXmlWriter.WriteEntityListProperty(AFieldInfo: TFieldInfo;
  AList: TEntityList; AContext: TEntityWriterContext);
var
  TheContext: TEntityXmlWriterContext;
  TheOldRoot, ThePropNode: IXMLNode;
  TheEntity: TEntity;
  I: Integer;
begin
  TheContext := AContext as TEntityXmlWriterContext;
  ThePropNode := TheContext.XmlDoc.CreateElement(AFieldInfo.FieldName);
  TheOldRoot := TheContext.XmNode;
  try
    TheOldRoot.AppendChild(ThePropNode);
    TheContext.XmNode := ThePropNode;
    for I := 0 to AList.Count - 1 do
    begin
      TheEntity := AList[I];
      WriteEntityContent(TheEntity, AContext);
    end;
    TheOldRoot.AppendChild(ThePropNode);
  finally
    TheContext.XmNode := TheOldRoot;
  end;
end;

class procedure TEntityXmlWriter.WriteSkyIdListProperty(AFieldInfo: TFieldInfo; AList: TSkyIdList;
  AContext: TEntityWriterContext);
var
  TheContext: TEntityXmlWriterContext;
  TheOldRoot, ThePropNode, TheValueNode: IXMLNode;
  TheFieldInfo: TFieldInfo;
  TheObject: TObject;
  I: Integer;
begin
  TheContext := AContext as TEntityXmlWriterContext;
  ThePropNode := TheContext.XmlDoc.CreateElement(AFieldInfo.FieldName);
  TheOldRoot := TheContext.XmNode;
  try
    TheOldRoot.AppendChild(ThePropNode);

    for I := 0 to AList.Count - 1 do
    begin
      TheValueNode := TheContext.XmlDoc.CreateElement('Row');
      TheContext.XmNode := TheValueNode;

      // write Id property
      TheFieldInfo.FieldName := 'Id';
      TheFieldInfo.FieldType := 'TId';
      TheFieldInfo.FieldKind := tkInt64;
      WriteProperty(TheFieldInfo, AList[I], TheContext);

      // write the object property
      TheObject := AList.Objects[I];
      if (TheObject <> nil) and (TheObject is TEntity) then
      begin
        TheFieldInfo.FieldName := 'Object';
        TheFieldInfo.FieldType := GetClassNameForEntity(TheObject as TEntity);
        TheFieldInfo.FieldKind := tkClass;
        WriteEntityProperty(TheFieldInfo, TheObject as TEntity, TheContext);
      end;

      ThePropNode.AppendChild(TheValueNode);
    end;

    TheOldRoot.AppendChild(ThePropNode);
  finally
    TheContext.XmNode := TheOldRoot;
  end;
end;

class procedure TEntityXmlWriter.WriteSkyStringListProperty(AFieldInfo: TFieldInfo; AList: TSkyStringList;
  AContext: TEntityWriterContext);
var
  TheContext: TEntityXmlWriterContext;
  TheOldRoot, ThePropNode, TheValueNode: IXMLNode;
  TheFieldInfo: TFieldInfo;
  TheObject: TObject;
  I: Integer;
begin
  TheContext := AContext as TEntityXmlWriterContext;
  ThePropNode := TheContext.XmlDoc.CreateElement(AFieldInfo.FieldName);
  TheOldRoot := TheContext.XmNode;
  try
    TheOldRoot.AppendChild(ThePropNode);

    for I := 0 to AList.Count - 1 do
    begin
      TheValueNode := TheContext.XmlDoc.CreateElement('Row');
      TheContext.XmNode := TheValueNode;

      // write String property
      TheFieldInfo.FieldName := 'Key';
      TheFieldInfo.FieldType := 'string';
      TheFieldInfo.FieldKind := tkUString;
      WriteProperty(TheFieldInfo, AList[I], TheContext);

      // write the object property
      TheObject := AList.Objects[I];
      if (TheObject <> nil) and (TheObject is TEntity) then
      begin
        TheFieldInfo.FieldName := 'Object';
        TheFieldInfo.FieldType := GetClassNameForEntity(TheObject as TEntity);
        TheFieldInfo.FieldKind := tkClass;
        WriteEntityProperty(TheFieldInfo, TheObject as TEntity, TheContext);
      end;

      ThePropNode.AppendChild(TheValueNode);
    end;

    TheOldRoot.AppendChild(ThePropNode);
  finally
    TheContext.XmNode := TheOldRoot;
  end;
end;

class procedure TEntityXmlWriter.WriteEntityProperty(AFieldInfo: TFieldInfo;
  AnEntity: TEntity; AContext: TEntityWriterContext);
var
  TheContext: TEntityXmlWriterContext;
  TheOldRoot, ThePropNode: IXMLNode;
begin
  TheContext := AContext as TEntityXmlWriterContext;
  TheOldRoot := TheContext.XmNode;
  ThePropNode := TheContext.XmlDoc.CreateElement(AFieldInfo.FieldName);
  TheOldRoot.AppendChild(ThePropNode);
  try
    TheContext.XmNode := ThePropNode;
    WriteEntityContent(AnEntity, AContext)
  finally
    TheContext.XmNode := TheOldRoot;
  end;
end;

class procedure TEntityXmlWriter.WriteESkyExceptionProperty(
  AFieldInfo: TFieldInfo; AnException: ESkyException;
  AContext: TEntityWriterContext);
var
  TheContext: TEntityXmlWriterContext;
  TheOldRoot, ThePropNode: IXMLNode;
begin
  TheContext := AContext as TEntityXmlWriterContext;
  TheOldRoot := TheContext.XmNode;
  ThePropNode := TheContext.XmlDoc.CreateElement(AFieldInfo.FieldName);
  TheOldRoot.AppendChild(ThePropNode);
  try
    TheContext.XmNode := ThePropNode;
    WriteEntityContent(AnException.MessageInfo, AContext);
  finally
    TheContext.XmNode := TheOldRoot;
  end;
end;

class procedure TEntityXmlWriter.WriteEntityContent(
  AnEntity: TEntity; AContext: TEntityWriterContext);
var
  TheContext: TEntityXmlWriterContext;
  TheOldRoot, ThePropNode: IXMLNode;
begin
  TheContext := AContext as TEntityXmlWriterContext;
  TheOldRoot := TheContext.XmNode;
  ThePropNode := TheContext.XmlDoc.CreateElement(GetClassNameForEntity(AnEntity));
  TheOldRoot.AppendChild(ThePropNode);
  try
    TheContext.XmNode := ThePropNode;
    AddEntity(AnEntity, AContext);
  finally
    TheContext.XmNode := TheOldRoot;
  end;
end;

class procedure TEntityXmlWriter.WriteProperty(AFieldInfo: TFieldInfo;
  AValue: Variant; AContext: TEntityWriterContext);
var
  TheContext: TEntityXmlWriterContext;
  ThePropNode: IXMLNode;
  TheValue: string;
begin
  TheValue := VarToStr(AValue);
  case AFieldInfo.FieldKind of
    tkFloat:
      if 'TDate' = AFieldInfo.FieldType then
        TheValue := XMLDateTimeToStrEx(Trunc(AValue))
      else if 'TDateTime' = AFieldInfo.FieldType then
        TheValue := XMLDateTimeToStrEx(AValue)
      else if 'TTime' = AFieldInfo.FieldType then
        TheValue := XMLDateTimeToStrEx(Frac(AValue))
      else
        TheValue := FloatToStr(AValue, _SQLFormat);
    tkEnumeration:
      if 'Boolean' = AFieldInfo.FieldType then
        TheValue := XMLBoolToStr(AValue);
  end;
  TheContext := AContext as TEntityXmlWriterContext;
  ThePropNode := TheContext.XmlDoc.CreateElement(AFieldInfo.FieldName);
  ThePropNode.Text := TheValue;
  TheContext.XmNode.AppendChild(ThePropNode);
end;

{ TEntityXmlWriterContext }

constructor TEntityXmlWriterContext.Create(AStream: TStream; AXmlDoc: IXMLDocument);
begin
  inherited Create(AStream);
  FXmlDoc := AXmlDoc;
  FXmNode := FXMLDoc.DocumentElement;
end;

end.
