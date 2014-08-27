unit Rep;

interface

uses
  RepEntity, BaseRuleCondition, BaseRuleGroup;

type
  TRep = class
  protected
    class function CheckRuleCondition(AnEntity: TRepEntity; ACondition: TBaseRuleCondition): Boolean;
    class function CheckRuleGroup(AnEntity: TRepEntity; ARuleGroup: TBaseRuleGroup): Boolean;
  end;

implementation

uses
  Entity, RepPropertyKey, RepPropertyValue, SysUtils, BaseRule;

{ TRep }

class function TRep.CheckRuleCondition(AnEntity: TRepEntity; ACondition: TBaseRuleCondition): Boolean;
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
  if (TheKey = nil) or (TheKey.PropertyType <> ACondition.KeyPropertyType) then
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

class function TRep.CheckRuleGroup(AnEntity: TRepEntity; ARuleGroup: TBaseRuleGroup): Boolean;
var
  TheCheck: Boolean;
  TheMember: TBaseRule;
  I: Integer;
begin
  Result := False;
  for I := 0 to ARuleGroup.Members.Count - 1 do
  begin
    TheMember := ARuleGroup.Members[I] as TBaseRule;
    if TheMember is TBaseRuleGroup then
      TheCheck := CheckRuleGroup(AnEntity, TheMember as TBaseRuleGroup)
    else
      TheCheck := CheckRuleCondition(AnEntity, TheMember as TBaseRuleCondition);
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

end.
