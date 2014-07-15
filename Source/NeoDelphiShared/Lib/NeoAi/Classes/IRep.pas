unit IRep;

interface

uses
  RepRecord, EntityList, IRepRule, RepEntity, IRepRuleGroup, IRepRuleCondition;

type
  TIRep = class
  private
    class function CheckRuleCondition(AnEntity: TRepEntity; ACondition: TIRepRuleCondition): Boolean;
    class function CheckRuleGroup(AnEntity: TRepEntity; ARuleGroup: TIRepRuleGroup): Boolean;
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

class function TIRep.CheckRuleCondition(AnEntity: TRepEntity; ACondition: TIRepRuleCondition): Boolean;
var
  TheConditionValue: Extended;
  TheEntities: TEntities; // TRepPropertyValues
  TheKey: TRepPropertyKey;
  TheValueFloat: Extended;
  TheValueStr: string;
  I: Integer;
begin
  Result := False;
  TheConditionValue := StrToFloatDef(ACondition.Value, 0);
  if ((ACondition.Value <> FloatToStr(TheConditionValue)) and (ACondition.OperatorType in [otEquals, otDiffers])) then
    Exit;
  TheKey := AnEntity.Kids.ObjectOfValueDefault[ACondition.Key, nil] as TRepPropertyKey;
  if (TheKey = nil) or (TheKey.PropertyType <> ACondition.PropertyType) then
    Exit;
  TheEntities := TheKey.GetValuesWithOperatorTypes([otEquals]);
  if (TheEntities = nil) then
  begin
    case ACondition.OperatorType of
      otGreater, otGreaterOrEqual:
        TheEntities := TheKey.GetValuesWithOperatorTypes([otGreater, otGreaterOrEqual]);
      otSmaller, otSmallerOrEqual:
        TheEntities := TheKey.GetValuesWithOperatorTypes([otSmaller, otSmallerOrEqual]);
    end;
  end;
  for I := 0 to High(TheEntities) do
  begin
    TheValueStr := (TheEntities[I] as TRepPropertyValue).Value;
    TheValueFloat := StrToFloatDef(TheValueStr, 0);
    if (not (ACondition.OperatorType in [otEquals, otDiffers])) and (TheValueStr <> FloatToStr(TheValueFloat)) then
      Continue;
    case ACondition.OperatorType of
      otGreater:
        Result := Result or (TheValueFloat > TheConditionValue);
      otGreaterOrEqual:
        Result := Result or (TheValueFloat >= TheConditionValue);
      otSmaller:
        Result := Result or (TheValueFloat < TheConditionValue);
      otSmallerOrEqual:
        Result := Result or (TheValueFloat <= TheConditionValue);
      otDiffers:
        Result := Result or SameText(TheValueStr, ACondition.Value);
      otEquals:
        Result := Result or (not SameText(TheValueStr, ACondition.Value));
    end;
  end;
end;

class function TIRep.CheckRuleGroup(AnEntity: TRepEntity; ARuleGroup: TIRepRuleGroup): Boolean;
var
  TheCheck: Boolean;
  TheMember: TBaseRule;
  I: Integer;
begin
  Result := False;
  for I := 0 to ARuleGroup.Members.Count - 1 do
  begin
    TheMember := ARuleGroup.Members[I] as TBaseRule;
    if TheMember is TIRepRuleGroup then
      TheCheck := CheckRuleGroup(AnEntity, TheMember as TIRepRuleGroup)
    else
      TheCheck := CheckRuleCondition(AnEntity, TheMember as TIRepRuleCondition);
    case ARuleGroup.ConjunctionType of
      ctAnd:
        if not TheCheck then
          Exit;
      ctOr:
      begin
        Result := Result or TheCheck;
        if Result then
          Exit;
      end;
    end;
  end;
  Result := ARuleGroup.ConjunctionType = ctAnd;
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
    TheEntityKey := AnEntity.GetOrCreateKid(TheValue.Key, TheValue.PropertyType) as TRepPropertyKey;
    if TheEntityKey = nil then
      Continue;
    TheEntityKey.AddLiteralValue(TheValue.OperatorType, TheValue.Value);
  end;
end;

end.