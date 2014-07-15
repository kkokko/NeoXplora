unit EntityJsonWriter;

interface

uses
  Classes, Entity, EntityWriter, TypesConsts, EntityList, SkyException, superobject, SkyIdList, SkyLists;

type
  TEntityJsonWriter = class(TEntityWriter)
  private
    class procedure WriteEntityContent(AnEntity: TEntity; AContext: TEntityWriterContext);
    class procedure WriteSkyIdListContent(AList: TSkyIdList; AContext: TEntityWriterContext);
    class procedure WriteSkyStringListContent(AList: TSkyStringList; AContext: TEntityWriterContext);
    class procedure WriteSkyStringStringListContent(AList: TSkyStringStringList; AContext: TEntityWriterContext);
    class procedure WriteObjectContent(AnObject: TObject; AContext: TEntityWriterContext);
  protected
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
    class procedure WriteSkyStringStringListProperty(AFieldInfo: TFieldInfo;
      AList: TSkyStringStringList; AContext: TEntityWriterContext); override;
  public
    class procedure WriteEntity(AStream: TStream; AnEntity: TEntity); override;
    class function WriteEntityToString(AnEntity: TEntity): string;
  end;

  TEntityJsonWriterContext = class(TEntityWriterContext)
  private
    FObjectProp: TSuperObject;
  public
    constructor Create(AStream: TStream; AnObject: TSuperObject); reintroduce;
    property ObjectProp: TSuperObject read FObjectProp write FObjectProp;
  end;

implementation

uses
  TypInfo, OmniXmlUtils, Variants, JclMime, SysUtils, ExceptionClasses;

{ TEntityJsonWriter }

class procedure TEntityJsonWriter.WriteEntity(AStream: TStream; AnEntity: TEntity);
var
  TheContext: TEntityJsonWriterContext;
  TheObject: TSuperObject;
begin
  TheObject := TSuperObject.Create;
  TheContext := nil;
  try
    TheContext := TEntityJsonWriterContext.Create(AStream, TheObject);
    WriteEntityContent(AnEntity, TheContext);
    TheObject.SaveTo(AStream);
  finally
    TheContext.Free;
    TheObject.Free;
  end;
end;

class function TEntityJsonWriter.WriteEntityToString(AnEntity: TEntity): string;
var
  TheContext: TEntityJsonWriterContext;
  TheObject: TSuperObject;
begin
  TheObject := TSuperObject.Create;
  TheContext := nil;
  try
    TheContext := TEntityJsonWriterContext.Create(nil, TheObject);
    WriteEntityContent(AnEntity, TheContext);
    Result := TheObject.AsString;
  finally
    TheContext.Free;
    TheObject.Free;
  end;
end;

class procedure TEntityJsonWriter.WriteEntityContent(AnEntity: TEntity; AContext: TEntityWriterContext);
var
  TheContext: TEntityJsonWriterContext;
  TheObject: TSuperObject;
begin
  TheContext := AContext as TEntityJsonWriterContext;
  TheContext.ObjectProp.AsObject.S['ClassName'] := AnEntity.ClassName;
  TheObject := TSuperObject.Create;
  try
    TheContext.ObjectProp.AsObject.O['Properties'] := TheObject;
  except
    TheObject.Free;
    raise;
  end;
  TheContext.ObjectProp := TheObject;
  AddEntity(AnEntity, AContext);
end;

class procedure TEntityJsonWriter.WriteBlobProperty(AFieldInfo: TFieldInfo;
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

class procedure TEntityJsonWriter.WriteEntityListProperty(AFieldInfo: TFieldInfo;
  AList: TEntityList; AContext: TEntityWriterContext);
var
  TheArray: TSuperObject;
  TheContext: TEntityJsonWriterContext;
  TheObject: TSuperObject;
  TheOldObject: TSuperObject;
  I: Integer;
begin
  TheContext := AContext as TEntityJsonWriterContext;
  TheArray := TSuperObject.Create(stArray);
  try
    TheContext.ObjectProp.AsObject.O[AFieldInfo.FieldName] := TheArray;
  except
    TheArray.free;
    raise;
  end;

  TheOldObject := TheContext.ObjectProp;
  try
    for I := 0 to AList.Count - 1 do
    begin
      TheObject := TSuperObject.Create;
      try
        TheArray.AsArray.Add(TheObject);
      except
        TheObject.Free;
        raise;
      end;
      TheContext.ObjectProp := TheObject;
      WriteEntityContent(AList[I] as TEntity, AContext);
    end;
  finally
    TheContext.ObjectProp := TheOldObject;
  end;
end;

class procedure TEntityJsonWriter.WriteSkyIdListContent(AList: TSkyIdList; AContext: TEntityWriterContext);
var
  TheContext: TEntityJsonWriterContext;
  TheOldObject, TheObject, TheRowsObject, TheRowObject: TSuperObject;
  I: Integer;
begin
  TheContext := AContext as TEntityJsonWriterContext;
  TheOldObject := TheContext.ObjectProp;
  try
    TheContext.ObjectProp.AsObject.S['ClassName'] := AList.ClassName;
    TheRowsObject := TSuperObject.Create(stArray);
    TheContext.ObjectProp.AsObject.O['Values'] := TheRowsObject;
    for I := 0 to AList.Count - 1 do
    begin
      TheRowObject := TSuperObject.Create;
      TheRowsObject.AsArray.Add(TheRowObject);
      TheRowObject.AsObject.I['Id'] := AList[I];
      if not CheckObjectEmpty(AList.Objects[I]) then
        Continue;
      TheObject := TSuperObject.Create;
      TheRowObject.AsObject.O['Object'] := TheObject;
      TheContext.ObjectProp := TheObject;
      WriteObjectContent(AList.Objects[I], AContext);
    end;
  finally
    TheContext.ObjectProp := TheOldObject;
  end;
end;

class procedure TEntityJsonWriter.WriteSkyIdListProperty(AFieldInfo: TFieldInfo; AList: TSkyIdList;
  AContext: TEntityWriterContext);
var
  TheContext: TEntityJsonWriterContext;
  TheListObject, TheOldObject: TSuperObject;
begin
  TheContext := AContext as TEntityJsonWriterContext;
  TheOldObject := TheContext.ObjectProp;
  try
    TheListObject := TSuperObject.Create;
    TheOldObject.AsObject.O[AFieldInfo.FieldName] := TheListObject;
    TheContext.ObjectProp := TheListObject;
    WriteSkyIdListContent(AList, AContext);
  finally
    TheContext.ObjectProp := TheOldObject;
  end;
end;

class procedure TEntityJsonWriter.WriteSkyStringListContent(AList: TSkyStringList; AContext: TEntityWriterContext);
var
  TheContext: TEntityJsonWriterContext;
  TheOldObject, TheObject, TheRowsObject, TheRowObject: TSuperObject;
  I: Integer;
begin
  TheContext := AContext as TEntityJsonWriterContext;
  TheOldObject := TheContext.ObjectProp;
  try
    TheContext.ObjectProp.AsObject.S['ClassName'] := AList.ClassName;
    TheRowsObject := TSuperObject.Create(stArray);
    TheContext.ObjectProp.AsObject.O['Values'] := TheRowsObject;
    for I := 0 to AList.Count - 1 do
    begin
      TheRowObject := TSuperObject.Create;
      TheRowsObject.AsArray.Add(TheRowObject);
      TheRowObject.AsObject.S['Key'] := AList[I];
      if not CheckObjectEmpty(AList.Objects[I]) then
        Continue;
      TheObject := TSuperObject.Create;
      TheRowObject.AsObject.O['Object'] := TheObject;
      TheContext.ObjectProp := TheObject;
      WriteObjectContent(AList.Objects[I], AContext);
    end;
  finally
    TheContext.ObjectProp := TheOldObject;
  end;
end;

class procedure TEntityJsonWriter.WriteSkyStringListProperty(AFieldInfo: TFieldInfo; AList: TSkyStringList;
  AContext: TEntityWriterContext);
var
  TheContext: TEntityJsonWriterContext;
  TheListObject, TheOldObject: TSuperObject;
begin
  TheContext := AContext as TEntityJsonWriterContext;
  TheOldObject := TheContext.ObjectProp;
  try
    TheListObject := TSuperObject.Create;
    TheOldObject.AsObject.O[AFieldInfo.FieldName] := TheListObject;
    TheContext.ObjectProp := TheListObject;
    WriteSkyStringListContent(AList, AContext);
  finally
    TheContext.ObjectProp := TheOldObject;
  end;
end;

class procedure TEntityJsonWriter.WriteSkyStringStringListContent(AList: TSkyStringStringList; AContext: TEntityWriterContext);
var
  TheContext: TEntityJsonWriterContext;
  TheOldObject, TheRowsObject, TheRowObject: TSuperObject;
  I: Integer;
begin
  TheContext := AContext as TEntityJsonWriterContext;
  TheOldObject := TheContext.ObjectProp;
  try
    TheContext.ObjectProp.AsObject.S['ClassName'] := AList.ClassName;
    TheRowsObject := TSuperObject.Create(stArray);
    TheContext.ObjectProp.AsObject.O['Values'] := TheRowsObject;
    for I := 0 to AList.Count - 1 do
    begin
      TheRowObject := TSuperObject.Create;
      TheRowsObject.AsArray.Add(TheRowObject);
      TheRowObject.AsObject.S['Key'] := AList[I];
      if AList.Objects[I] <> '' then
        TheRowObject.AsObject.S['Object'] := AList.Objects[I];
    end;
  finally
    TheContext.ObjectProp := TheOldObject;
  end;
end;

class procedure TEntityJsonWriter.WriteSkyStringStringListProperty(AFieldInfo: TFieldInfo; AList: TSkyStringStringList;
  AContext: TEntityWriterContext);
var
  TheContext: TEntityJsonWriterContext;
  TheListObject, TheOldObject: TSuperObject;
begin
  TheContext := AContext as TEntityJsonWriterContext;
  TheOldObject := TheContext.ObjectProp;
  try
    TheListObject := TSuperObject.Create;
    TheOldObject.AsObject.O[AFieldInfo.FieldName] := TheListObject;
    TheContext.ObjectProp := TheListObject;
    WriteSkyStringStringListContent(AList, AContext);
  finally
    TheContext.ObjectProp := TheOldObject;
  end;
end;

class procedure TEntityJsonWriter.WriteEntityProperty(AFieldInfo: TFieldInfo;
  AnEntity: TEntity; AContext: TEntityWriterContext);
var
  TheContext: TEntityJsonWriterContext;
  TheObject: TSuperObject;
  TheOldObject: TSuperObject;
begin
  TheContext := AContext as TEntityJsonWriterContext;
  TheOldObject := TheContext.ObjectProp;
  try
    TheObject := TSuperObject.Create;
    try
      TheContext.ObjectProp.AsObject.O[AFieldInfo.FieldName] := TheObject;
    except
      TheObject.Free;
      raise;
    end;
    TheContext.ObjectProp := TheObject;
    WriteEntityContent(AnEntity, AContext);
  finally
    TheContext.ObjectProp := TheOldObject;
  end;
end;

class procedure TEntityJsonWriter.WriteESkyExceptionProperty(
  AFieldInfo: TFieldInfo; AnException: ESkyException;
  AContext: TEntityWriterContext);
var
  TheContext: TEntityJsonWriterContext;
  TheObject: TSuperObject;
  TheObjectProperties: TSuperObject;
  TheOldObject: TSuperObject;
begin
  TheContext := AContext as TEntityJsonWriterContext;
  TheOldObject := TheContext.ObjectProp;
  try
    TheObject := TSuperObject.Create;
    try
      TheContext.ObjectProp.AsObject.O[AFieldInfo.FieldName] := TheObject;
    except
      TheObject.Free;
      raise;
    end;
    TheContext.ObjectProp := TheObject;
    TheObject.S['ClassName'] := AnException.ClassName;
    TheObjectProperties := TSuperObject.Create;
    try
      TheContext.ObjectProp.AsObject.O['Properties'] := TheObjectProperties;
    except
      TheObjectProperties.Free;
      raise;
    end;
    TheContext.ObjectProp := TheObjectProperties;
    AddEntity(AnException.MessageInfo, AContext);
  finally
    TheContext.ObjectProp := TheOldObject;
  end;
end;

class procedure TEntityJsonWriter.WriteObjectContent(AnObject: TObject; AContext: TEntityWriterContext);
begin
  if AnObject is TEntity then
    WriteEntityContent(AnObject as TEntity, AContext)
  else if AnObject is TSkyIdList then
    WriteSkyIdListContent(AnObject as TSkyIdList, AContext)
  else if AnObject is TSkyStringList then
    WriteSkyStringListContent(AnObject as TSkyStringList, AContext)
  else if AnObject is TSkyStringStringList then
    WriteSkyStringStringListContent(AnObject as TSkyStringStringList, AContext)
  else
    raise ESkyInvalidPropertyType.Create(nil, 'WriteObjectContent', AnObject.ClassName);
end;

class procedure TEntityJsonWriter.WriteProperty(AFieldInfo: TFieldInfo;
  AValue: Variant; AContext: TEntityWriterContext);
var
  TheContext: TEntityJsonWriterContext;
  TheValue: string;
begin
  TheContext := AContext as TEntityJsonWriterContext;
  TheValue := VarToStr(AValue);
  case AFieldInfo.FieldKind of
    tkFloat:
      if AValue = 0 then
        TheValue := ''
      else if 'TDate' = AFieldInfo.FieldType then
        TheValue := XMLDateToStr(Trunc(AValue))
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
  TheContext.ObjectProp.AsObject.S[AFieldInfo.FieldName] := TheValue;
end;

{ TEntityJsonWriterContext }

constructor TEntityJsonWriterContext.Create(AStream: TStream; AnObject: TSuperObject);
begin
  inherited Create(AStream);
  FObjectProp := AnObject;
end;

end.
