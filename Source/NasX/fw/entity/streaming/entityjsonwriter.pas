unit EntityJsonWriter;

{$mode objfpc}{$H+}

interface

uses
  Classes, Entity, EntityWriter, TypesConsts, EntityList, superobject, SkyIdList;

type
  TEntityJsonWriter = class(TEntityWriter)
  private
    class procedure WriteEntityContent(AnEntity: TEntity; AContext: TEntityWriterContext);
  protected
    class procedure WriteProperty(AFieldInfo: TFieldInfo; AValue: Variant;
      AContext: TEntityWriterContext); override;
    class procedure WriteEntityProperty(AFieldInfo: TFieldInfo;
      AnEntity: TEntity; AContext: TEntityWriterContext); override;
    class procedure WriteEntityListProperty(AFieldInfo: TFieldInfo;
      AList: TEntityList; AContext: TEntityWriterContext); override;
    class procedure WriteSkyIdListProperty(AFieldInfo: TFieldInfo;
      AList: TSkyIdList; AContext: TEntityWriterContext); override;
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
  TypInfo, TypesFunctions, Variants, SysUtils;

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

class procedure TEntityJsonWriter.WriteSkyIdListProperty(AFieldInfo: TFieldInfo; AList: TSkyIdList;
  AContext: TEntityWriterContext);
var
  TheContext: TEntityJsonWriterContext;
  TheListObject, TheRowsObject, TheRowObject, TheOldObject, TheObject: TSuperObject;
  I: Integer;
begin
  TheContext := AContext as TEntityJsonWriterContext;

  TheOldObject := TheContext.ObjectProp;
  try
    TheListObject := TSuperObject.Create;
    TheOldObject.AsObject.O[AFieldInfo.FieldName] := TheListObject;
    TheListObject.AsObject.S['ClassName'] := AList.ClassName;
    TheRowsObject := TSuperObject.Create(stArray);
    TheListObject.AsObject.O['Values'] := TheRowsObject;
    for I := 0 to AList.Count - 1 do
    begin
      TheRowObject := TSuperObject.Create;
      TheRowsObject.AsArray.Add(TheRowObject);
      TheRowObject.AsObject.I['Id'] := AList[I];
      if AList.Objects[I] = nil then
        Continue;
      TheObject := TSuperObject.Create;
      TheRowObject.AsObject.O['Object'] := TheObject;
      TheContext.ObjectProp := TheObject;
      WriteEntityContent(AList.Objects[I] as TEntity, AContext);
    end;
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
        TheValue := DateTimeToStrEx(Trunc(AValue))
      else if 'TDateTime' = AFieldInfo.FieldType then
        TheValue := DateTimeToStrEx(AValue)
      else if 'TTime' = AFieldInfo.FieldType then
        TheValue := DateTimeToStrEx(Frac(AValue))
      else
        TheValue := FloatToStr(Int64(AValue), _SQLFormat);
    tkEnumeration:
      if 'Boolean' = AFieldInfo.FieldType then
        TheValue := TypesFunctions.BoolToStr(AValue);
    tkBool:
      TheValue := TypesFunctions.BoolToStr(AValue);
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
