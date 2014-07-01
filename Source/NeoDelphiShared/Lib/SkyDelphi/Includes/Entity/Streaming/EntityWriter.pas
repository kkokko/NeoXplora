unit EntityWriter;

interface

uses
  Classes, Entity, TypesConsts, EntityList, SkyIdList, SkyException, SkyLists;

type
  TEntityWriterContext = class;

  TEntityWriter = class
  protected
    class function CheckObjectEmpty(AnObject: TObject): Boolean;
    class procedure WriteProperty(AFieldInfo: TFieldInfo; AValue: Variant;
      AContext: TEntityWriterContext); virtual; abstract;
    class procedure WriteBlobProperty(AFieldInfo: TFieldInfo; ABlob: TBlobType;
      AContext: TEntityWriterContext); virtual; abstract;
    class procedure WriteEntityProperty(AFieldInfo: TFieldInfo;
      AnEntity: TEntity; AContext: TEntityWriterContext); virtual; abstract;
    class procedure WriteESkyExceptionProperty(AFieldInfo: TFieldInfo;
      AnException: ESkyException; AContext: TEntityWriterContext); virtual; abstract;
    class procedure WriteEntityListProperty(AFieldInfo: TFieldInfo;
      AList: TEntityList; AContext: TEntityWriterContext); virtual; abstract;
    class procedure WriteSkyIdListProperty(AFieldInfo: TFieldInfo;
      AList: TSkyIdList; AContext: TEntityWriterContext); virtual; abstract;
    class procedure WriteSkyStringListProperty(AFieldInfo: TFieldInfo;
      AList: TSkyStringList; AContext: TEntityWriterContext); virtual; abstract;
    class procedure WriteSkyStringStringListProperty(AFieldInfo: TFieldInfo;
      AList: TSkyStringStringList; AContext: TEntityWriterContext); virtual; abstract;
    class procedure AddEntity(AnEntity: TEntity; AContext: TEntityWriterContext);
  public
    class procedure WriteEntity(AStream: TStream; AnEntity: TEntity); virtual; abstract;
  end;
  TEntityWriterClass = class of TEntityWriter;

  TEntityWriterContext = class
  private
    FStream: TStream;
  public
    constructor Create(AStream: TStream); reintroduce;
    property Stream: TStream read FStream write FStream;
  end;

implementation

uses
  ExceptionClasses, TypInfo;

{ TEntityWriter }

class procedure TEntityWriter.AddEntity(AnEntity: TEntity; AContext: TEntityWriterContext);
var
  TheFieldInfos: {$IFDEF VER210}TFieldInfo.TArray{$ELSE}TFieldInfoArray{$ENDIF};
  TheValue: Variant;
  TheObject: TObject;
  I: Integer;
begin
  TheFieldInfos := AnEntity.GetFieldInfos;
  for I := 0 to High(TheFieldInfos) do
  begin
    TheValue := AnEntity.GetValueForField(TheFieldInfos[I].FieldName);
    if TheFieldInfos[I].FieldKind <> tkClass then
      WriteProperty(TheFieldInfos[I], TheValue, AContext)
    else
    begin
      TheObject := TObject(Integer(TheValue));
      if not CheckObjectEmpty(TheObject) then
        Continue;
      if TheObject.InheritsFrom(TBlobType) then
        WriteBlobProperty(TheFieldInfos[I], TheObject as TBlobType, AContext)
      else if TheObject.InheritsFrom(TEntity) then
        WriteEntityProperty(TheFieldInfos[I], TheObject as TEntity, AContext)
      else if TheObject.InheritsFrom(TEntityList) then
        WriteEntityListProperty(TheFieldInfos[I], TheObject as TEntityList, AContext)
      else if TheObject.InheritsFrom(TSkyIdList) then
        WriteSkyIdListProperty(TheFieldInfos[I], TheObject as TSkyIdList, AContext)
      else if TheObject.InheritsFrom(TSkyStringList) then
        WriteSkyStringListProperty(TheFieldInfos[I], TheObject as TSkyStringList, AContext)
      else if TheObject.InheritsFrom(TSkyStringStringList) then
        WriteSkyStringStringListProperty(TheFieldInfos[I], TheObject as TSkyStringStringList, AContext)
      else if TheObject.InheritsFrom(ESkyException) then
        WriteESkyExceptionProperty(TheFieldInfos[I], TheObject as ESkyException, AContext)
      else
        raise ESkyInvalidPropertyType.Create(nil, 'TEntityWriter.AddEntity', TheFieldInfos[I].FieldName);
    end;
  end;
end;

class function TEntityWriter.CheckObjectEmpty(AnObject: TObject): Boolean;
begin
  Result := (AnObject <> nil) and (
    (AnObject is ESkyException) or
    (AnObject is TEntity) or
    (AnObject is TEntityList) or
    ((AnObject is TSkyIdList) and ((AnObject as TSkyIdList).Count > 0)) or
    ((AnObject is TSkyStringList) and ((AnObject as TSkyStringList).Count > 0)) or
    ((AnObject is TSkyStringStringList) and ((AnObject as TSkyStringStringList).Count > 0))
  );
end;

{ TEntityWriterContext }

constructor TEntityWriterContext.Create(AStream: TStream);
begin
  inherited Create;
  FStream := AStream;
end;

end.
