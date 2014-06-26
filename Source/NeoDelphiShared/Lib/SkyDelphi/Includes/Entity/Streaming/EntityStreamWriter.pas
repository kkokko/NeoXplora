unit EntityStreamWriter;

interface

uses
  Classes, Entity, EntityWriter, TypesConsts, EntityList, SkyException;

type
  TEntityStreamWriter = class(TEntityWriter)
  private
    class procedure InternalAddEntity(AnEntity: TEntity; AContext: TEntityWriterContext);
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
  public
    class procedure WriteEntity(AStream: TStream; AnEntity: TEntity); override;
  end;

  TEntityStreamWriterContext = class(TEntityWriterContext);

implementation

uses
  Variants, VariantUtils, EntityStreamReader;

{ TEntityStreamWriter }

class procedure TEntityStreamWriter.WriteEntity(AStream: TStream; AnEntity: TEntity);
var
  TheContext: TEntityStreamWriterContext;
begin
  TheContext := TEntityStreamWriterContext.Create(AStream);
  try
    InternalAddEntity(AnEntity, TheContext);
  finally
    TheContext.Free;
  end;
end;

class procedure TEntityStreamWriter.InternalAddEntity(AnEntity: TEntity;
  AContext: TEntityWriterContext);
begin
  SerializeVariant(AnEntity.ClassName, AContext.Stream);
  AddEntity(AnEntity, AContext);
  // write a stop sign to signal the entity has ended
  SerializeVariant(TEntityStreamReader.ObjectType_StopSign, AContext.Stream);
end;

class procedure TEntityStreamWriter.WriteBlobProperty(AFieldInfo: TFieldInfo;
  ABlob: TBlobType; AContext: TEntityWriterContext);
begin
  SerializeVariant(TEntityStreamReader.ObjectType_BlobType, AContext.Stream);
  SerializeVariant(AFieldInfo.FieldName, AContext.Stream);
  // make sure we serialize int 64
  SerializeVariant(Int64(ABlob.Size), AContext.Stream);
  AContext.Stream.CopyFrom(ABlob, 0); //sets ABlob.Position to 0 and copies everything
end;

class procedure TEntityStreamWriter.WriteEntityListProperty(AFieldInfo: TFieldInfo;
  AList: TEntityList; AContext: TEntityWriterContext);
var
  I: Integer;
begin
  SerializeVariant(TEntityStreamReader.ObjectType_EntityList, AContext.Stream);
  SerializeVariant(AFieldInfo.FieldName, AContext.Stream);
  SerializeVariant(AList.Count, AContext.Stream);
  for I := 0 to AList.Count - 1 do
    InternalAddEntity(AList[I], AContext);
end;

class procedure TEntityStreamWriter.WriteEntityProperty(AFieldInfo: TFieldInfo;
  AnEntity: TEntity; AContext: TEntityWriterContext);
begin
  SerializeVariant(TEntityStreamReader.ObjectType_Entity, AContext.Stream);
  SerializeVariant(AFieldInfo.FieldName, AContext.Stream);
  InternalAddEntity(AnEntity, AContext);
end;

class procedure TEntityStreamWriter.WriteESkyExceptionProperty(
  AFieldInfo: TFieldInfo; AnException: ESkyException;
  AContext: TEntityWriterContext);
begin
  SerializeVariant(TEntityStreamReader.ObjectType_Exception, AContext.Stream);
  SerializeVariant(AFieldInfo.FieldName, AContext.Stream);
  InternalAddEntity(AnException.MessageInfo, AContext);
end;

class procedure TEntityStreamWriter.WriteProperty(AFieldInfo: TFieldInfo;
  AValue: Variant; AContext: TEntityWriterContext);
begin
  SerializeVariant(TEntityStreamReader.ObjectType_Simple, AContext.Stream);
  SerializeVariant(AFieldInfo.FieldName, AContext.Stream);
  SerializeVariant(AValue, AContext.Stream);
end;

end.
