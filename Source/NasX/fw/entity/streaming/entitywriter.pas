unit EntityWriter;

{$mode objfpc}{$H+}

interface

uses
  Classes, Entity, TypesConsts, EntityList, SkyIdList;

type
  TEntityWriterContext = class;

  TEntityWriter = class
  protected
    class procedure WriteProperty(AFieldInfo: TFieldInfo; AValue: Variant;
      AContext: TEntityWriterContext); virtual; abstract;
    class procedure WriteBlobProperty(AFieldInfo: TFieldInfo; ABlob: TBlobType;
      AContext: TEntityWriterContext); virtual; abstract;
    class procedure WriteEntityProperty(AFieldInfo: TFieldInfo;
      AnEntity: TEntity; AContext: TEntityWriterContext); virtual; abstract;
    class procedure WriteEntityListProperty(AFieldInfo: TFieldInfo;
      AList: TEntityList; AContext: TEntityWriterContext); virtual; abstract;
    class procedure WriteSkyIdListProperty(AFieldInfo: TFieldInfo;
      AList: TSkyIdList; AContext: TEntityWriterContext); virtual; abstract;
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
  TypInfo, SysUtils;

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
      if TheObject = nil then
        Continue;
      if TheObject.InheritsFrom(TBlobType) then
        WriteBlobProperty(TheFieldInfos[I], TheObject as TBlobType, AContext)
      else if TheObject.InheritsFrom(TEntity) then
        WriteEntityProperty(TheFieldInfos[I], TheObject as TEntity, AContext)
      else if TheObject.InheritsFrom(TEntityList) then
        WriteEntityListProperty(TheFieldInfos[I], TheObject as TEntityList, AContext)
      else if TheObject.InheritsFrom(TSkyIdList) then
        WriteSkyIdListProperty(TheFieldInfos[I], TheObject as TSkyIdList, AContext)
      else
        raise Exception.Create('TEntityWriter.AddEntity');
    end;
  end;
end;

{ TEntityWriterContext }

constructor TEntityWriterContext.Create(AStream: TStream);
begin
  inherited Create;
  FStream := AStream;
end;

end.
