unit CRep;

interface

uses
  Classes, Rep, RepRecord, RepEntity, EntityList, RepPropertyKey, RepObjectBase, RepPropertyValue;

type
  TCRep = class(TRep)
  private
    FRepEntityMapping: TEntityList;
    FGlobalRepRecord: TRepRecord;

    procedure MergeRepEntityToGlobal(AnEntity: TRepEntity);
    procedure MergeRepPropertyKeyByNameToGlobal(const AKeyName: string; AKey: TRepPropertyKey; AGlobalObject: TRepObjectBase);
    procedure MergeRepPropertyKeyToGlobal(AKey, AGlobalKey: TRepPropertyKey);
    procedure MergeRepPropertyValueByNameToGlobal(const AValueName: string; AValue: TRepPropertyValue; AGlobalKey: TRepPropertyKey);
  public
    constructor Create;
    destructor Destroy; override;

    // does not change the P' name, does not add IRep's, adds the sentence to the global record
    procedure AddSentence(ARepRecord: TRepRecord);

    // tries to find the best matching P', add the sentence to the global record
    // returns altered P' numbers
    procedure GuessSentence(ARepRecord: TRepRecord);
    procedure Clear;
    function GetGlobalRepRecordWithIReps: TRepRecord;
  end;

implementation

uses
  SysUtils;

{ TCRep }

constructor TCRep.Create;
begin
  FGlobalRepRecord := TRepRecord.Create;
  FGlobalRepRecord.SentenceNumber := 0;
  FRepEntityMapping := TEntityList.Create(False, False);
end;

destructor TCRep.Destroy;
begin
  FRepEntityMapping.Free;
  FGlobalRepRecord.Free;
  inherited;
end;

procedure TCRep.AddSentence(ARepRecord: TRepRecord);
var
  TheGlobalRepEntity: TRepEntity;
  TheRepEntity: TRepEntity;
  I: Integer;
begin
  for I := 0 to ARepRecord.RepEntities.Count - 1 do
  begin
    TheRepEntity := ARepRecord.RepEntities.Objects[I] as TRepEntity;
    TheGlobalRepEntity := FGlobalRepRecord.GetOrCreateEntity(TheRepEntity.EntityType, TheRepEntity.EntityNumber);
    FRepEntityMapping.AddObject(TheRepEntity, TheGlobalRepEntity);
  end;
  for I := 0 to ARepRecord.RepEntities.Count - 1 do
    MergeRepEntityToGlobal(ARepRecord.RepEntities.Objects[I] as TRepEntity);
end;

function TCRep.GetGlobalRepRecordWithIReps: TRepRecord;
begin

end;

procedure TCRep.GuessSentence(ARepRecord: TRepRecord);
begin

end;

procedure TCRep.MergeRepEntityToGlobal(AnEntity: TRepEntity);
var
  TheGlobalEntity: TRepEntity;
  TheKey: TRepPropertyKey;
  TheKeyName: string;
  I: Integer;
begin
  TheGlobalEntity := FRepEntityMapping.ObjectOfValue[AnEntity] as TRepEntity;
  for I := 0 to AnEntity.Kids.Count - 1 do
  begin
    TheKeyName := AnEntity.Kids[I];
    TheKey := AnEntity.Kids.Objects[I] as TRepPropertyKey;
    MergeRepPropertyKeyByNameToGlobal(TheKeyName, TheKey, TheGlobalEntity);
  end;
end;

procedure TCRep.MergeRepPropertyKeyByNameToGlobal(const AKeyName: string; AKey: TRepPropertyKey; AGlobalObject: TRepObjectBase);
var
  TheGlobalKey: TRepPropertyKey;
begin
  TheGlobalKey := AGlobalObject.Kids.ObjectOfValueDefault[AKeyName, nil] as TRepPropertyKey;
  if TheGlobalKey = nil then
  begin
    TheGlobalKey := TRepPropertyKey.Create(AGlobalObject, AKey.PropertyType, AKeyName);
    AGlobalObject.Kids.AddObject(AKeyName, TheGlobalKey);
  end;
  MergeRepPropertyKeyToGlobal(AKey, TheGlobalKey);
end;

procedure TCRep.MergeRepPropertyKeyToGlobal(AKey, AGlobalKey: TRepPropertyKey);
var
  TheKey: TRepPropertyKey;
  TheKeyName: string;
  TheValue: TRepPropertyValue;
  TheValueName: string;
  I: Integer;
begin
  for I := 0 to AKey.Kids.Count - 1 do
  begin
    TheKeyName := AKey.Kids[I];
    TheKey := AKey.Kids.Objects[I] as TRepPropertyKey;
    MergeRepPropertyKeyByNameToGlobal(TheKeyName, TheKey, AGlobalKey);
  end;
  for I := 0 to AKey.Values.Count - 1 do
  begin
    TheValueName := AKey.Values[I];
    TheValue := AKey.Values.Objects[I] as TRepPropertyValue;
    MergeRepPropertyValueByNameToGlobal(TheValueName, TheValue, AGlobalKey);
  end;
end;

procedure TCRep.MergeRepPropertyValueByNameToGlobal(const AValueName: string; AValue: TRepPropertyValue;
  AGlobalKey: TRepPropertyKey);
var
  TheGlobalValue: TRepPropertyValue;
begin
  TheGlobalValue := AGlobalKey.Kids.ObjectOfValueDefault[AValueName, nil] as TRepPropertyValue;
  // if value already exists - do nothing
  if TRepPropertyValue <> nil then
    Exit;
  TheGlobalValue := TRepPropertyValue.Create(AGlobalKey, AValue.OperatorType, AValue.Value);
  // to check how these are done ..
//  AGlobalKey.AddLiteralValue
//  AGlobalKey.AddLinkValue

//  TheGlobalKey := TRepPropertyKey.Create(AGlobalObject, AKey.PropertyType, AKeyName);
//  AGlobalObject.Kids.AddObject(AKeyName, TheGlobalKey);
//
//  AGlobalKey
end;

procedure TCRep.Clear;
begin
  FGlobalRepRecord.Clear;
end;

end.
