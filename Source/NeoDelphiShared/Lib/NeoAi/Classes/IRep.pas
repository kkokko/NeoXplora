unit IRep;

interface

uses
  RepRecord, EntityList, IRepRule, RepEntity, IRepRuleGroup, IRepRuleCondition, Rep;

type
  TIRep = class(TRep)
  private
    class procedure ApplyRuleValues(AnEntity: TRepEntity; ARule: TIRepRule);
  public
    class procedure ApplyIRepRules(ARepRecord: TRepRecord; ARulesList: TEntityList);
  end;

implementation

uses
  BaseRule, RepPropertyKey, RepPropertyValue, SysUtils, Entity, IRepRuleValue;

{ TIRep }

class procedure TIRep.ApplyIRepRules(ARepRecord: TRepRecord; ARulesList: TEntityList);
var
  TheEntity: TRepEntity;
  TheRule: TIRepRule;
  I, J: Integer;
begin
  for I := 0 to ARepRecord.RepEntities.Count - 1 do
  begin
    TheEntity := ARepRecord.RepEntities.Objects[I] as TRepEntity;
    for J := 0 to ARulesList.Count - 1 do
    begin
      TheRule := ARulesList[I] as TIRepRule;
      if not CheckRuleGroup(TheEntity, TheRule.MainRuleGroup) then
        Continue;
      ApplyRuleValues(TheEntity, TheRule);
    end;
  end;
end;

class procedure TIRep.ApplyRuleValues(AnEntity: TRepEntity; ARule: TIRepRule);
var
  TheEntityKey: TRepPropertyKey;
  TheValue: TIRepRuleValue;
  I: Integer;
begin
  for I := 0 to ARule.Values.Count - 1 do
  begin
    TheValue := ARule.Values[I] as TIRepRuleValue;
    TheEntityKey := AnEntity.GetOrCreateKid(TheValue.Key, TheValue.KeyPropertyType) as TRepPropertyKey;
    if TheEntityKey = nil then
      Continue;
    TheEntityKey.AddLiteralValue(TheValue.OperatorType, TheValue.Value);
  end;
end;

end.