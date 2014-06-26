unit EntityStreamReader;

interface

uses
  Classes, Entity, EntityReader, EntityList;

type
  TEntityStreamReaderContext = class;

  TEntityStreamReader = class(TEntityReader)
  private
    class function InternalReadEntity(AContext: TEntityStreamReaderContext;
      AnExpectedClassType: TEntityClass = nil): TEntity;
  public
    const
      ObjectType_Simple = 0;
      ObjectType_BlobType = 1;
      ObjectType_Entity = 2;
      ObjectType_EntityList = 3;
      ObjectType_Exception = 4;
      ObjectType_StopSign = 255;
    class function ReadEntity(AStream: TStream; AnExpectedClassType: TEntityClass = nil): TEntity; override;
  end;

  TEntityStreamReaderContext = class(TEntityReaderContext);

implementation

uses
  ExceptionClasses, TypesConsts, TypInfo, 
  Variants, SkyException, MessageInfoData, VariantUtils, SysUtils;

{ TEntityStreamReader }

class function TEntityStreamReader.InternalReadEntity(AContext: TEntityStreamReaderContext;
  AnExpectedClassType: TEntityClass = nil): TEntity;
var
  TheClassName: string;
  TheFieldName: string;
  TheObject: TObject;
  TheObjectType: Byte;
  TheSize: Int64;
  TheValue: Variant;
  I: Integer;
begin
  Result := nil;
  try
    TheClassName := VarToStrDef(DeserializeVariant(AContext.Stream), '-InvalidString');
    Result := TEntity.CreateEntityOfClass(TheClassName, AnExpectedClassType);
    repeat
      TheObjectType := DeserializeVariant(AContext.Stream);
      if TEntityStreamReader.ObjectType_StopSign = TheObjectType then
        Exit;
      TheFieldName := DeserializeVariant(AContext.Stream);
      if TEntityStreamReader.ObjectType_Simple = TheObjectType then
      begin
        TheValue := DeserializeVariant(AContext.Stream);
        Result.SetValueForField(TheFieldName, TheValue);
        Continue;
      end;

      TheObject := nil;
      case TheObjectType of
        TEntityStreamReader.ObjectType_BlobType: begin
          TheSize := DeserializeVariant(AContext.Stream);
          TheObject := TBlobType.Create;
          try
            (TheObject as TBlobType).CopyFrom(AContext.Stream, TheSize);
          except
            TheObject.Free;
            raise;
          end;
        end;

        TEntityStreamReader.ObjectType_Entity:
          TheObject := InternalReadEntity(AContext);

        TEntityStreamReader.ObjectType_EntityList:
        begin
          TheSize := DeserializeVariant(AContext.Stream);
          TheObject := TEntityList.Create(False, True);
          try
            for I := 0 to TheSize - 1 do
              (TheObject as TEntityList).Add(InternalReadEntity(AContext));
          except
            TheObject.Free;
            raise;
          end;
        end;

        TEntityStreamReader.ObjectType_Exception:
        begin
          TheObject := InternalReadEntity(AContext);
          try
            TheObject := ESkyException.CreateFromMessageInfo(TheObject as TMessageInfoData);
          except
            TheObject.Free;
            raise;
          end;
        end;

        TEntityStreamReader.ObjectType_StopSign,
        TEntityStreamReader.ObjectType_Simple: {no action};
      else
        raise Exception.Create('Unknown objectType: ' + IntToStr(TheObjectType));
      end;
      GetObjectProp(Result, TheFieldName).Free;
      SetObjectProp(Result, TheFieldName, TheObject);
    until 1 = 0;
  except on E: exception do
    if (E is ESkyException) then
      raise (E as ESkyException).CreateACopy
    else
      ESkyInvalidStream.Create(nil, 'TEntityStreamReader.InternalReadEntity', E.Message);
  end;

end;

class function TEntityStreamReader.ReadEntity(AStream: TStream; AnExpectedClassType: TEntityClass): TEntity;
var
  TheContext: TEntityStreamReaderContext;
begin
  TheContext := TEntityStreamReaderContext.Create(AStream);
  try
    Result := InternalReadEntity(TheContext, AnExpectedClassType);
  finally
    TheContext.Free;
  end;
end;

end.
