unit BaseData;

interface

uses
  TypesConsts;

type
  TBaseData = class
  public
    class function VariantToString(AVariant: Variant; ADataType: string): string;
    class function DBVariantToVariant(AVariant: Variant; ADataType: string): Variant;

    class function DateToStr(ADate: TDate): string;
    class function DateTimeToStr(ADateTime: TDateTime): string;
    class function TimeToStr(ADateTime: TDateTime): string;
    class function StrToDate(ADate: string): TDate;
    class function StrToDateTime(ADateTime: string): TDateTime;
    class function StrToTime(ATime: string): TTime;
    class function DoubleToStr(ADouble: Double): string;
    class function EscapeString(AString: string): string;
  end;

implementation

uses
  Variants, TypInfo, SysUtils;

{ TBaseData }

class function TBaseData.VariantToString(AVariant: Variant; ADataType: string): string;
begin
  Result := '''''';
  if (VarIsNull(AVariant)) or (VarIsEmpty(AVariant)) then
    Exit;
  if VarIsStr(AVariant) then
    Result := '''' + EscapeString(AVariant) + ''''
  else if VarIsFloat(AVariant) then
  begin
    if ADataType = 'TDate' then
      Result := '''' + DateToStr(AVariant) + ''''
    else if ADataType = 'TDateTime' then
      Result := '''' + DateTimeToStr(AVariant) + ''''
    else if ADataType = 'TTime' then
      Result := '''' + TimeToStr(AVariant) + ''''
    else
      Result := FloatToStr(AVariant)
  end
  else
    Result := VarToStr(AVariant); // numeric type works fine
end;

class function TBaseData.DBVariantToVariant(AVariant: Variant;
  ADataType: string): Variant;
begin
  Result := AVariant;
  if (VarIsNull(AVariant)) or (VarIsEmpty(AVariant)) then
    Exit;
  if VarIsStr(AVariant) then
  begin
    if ADataType = 'TDate' then
      Result := StrToDate(AVariant)
    else if ADataType = 'TDateTime' then
      Result := StrToDateTime(AVariant)
    else if ADataType = 'TTime' then
      Result := StrToTime(AVariant);
  end;
end;

class function TBaseData.EscapeString(AString: string): string;
var
  I: Integer;
begin
  Result := '';
  for I := 1 to Length(AString) do
    if not {$IFDEF VER210} CharInSet(AString[I],{$ELSE}(AString[I] in{$ENDIF} [#39]) then
      Result := Result + AString[I]
    else
    begin
      Result := Result + #39;
      case AString[I] of
        #39: Result := Result + #39;
      end;
    end;
end;

class function TBaseData.DateToStr(ADate: TDate): string;
begin
  Result := SysUtils.DateToStr(ADate, _SQLServerFormat);
end;

class function TBaseData.DoubleToStr(ADouble: Double): string;
begin
  Result := SysUtils.FloatToStr(ADouble, _SQLServerFormat);
end;

class function TBaseData.DateTimeToStr(ADateTime: TDateTime): string;
begin
  Result := SysUtils.DateTimeToStr(ADateTime, _SQLServerFormat);
end;

class function TBaseData.TimeToStr(ADateTime: TDateTime): string;
begin
  Result := SysUtils.TimeToStr(ADateTime, _SQLServerFormat);
end;

class function TBaseData.StrToDate(ADate: string): TDate;
begin
  Result := SysUtils.StrToDate(ADate, _SQLServerFormat);
end;

class function TBaseData.StrToDateTime(ADateTime: string): TDateTime;
begin
  Result := SysUtils.StrToDateTime(ADateTime, _SQLServerFormat);
end;

class function TBaseData.StrToTime(ATime: string): TTime;
begin
  Result := SysUtils.StrToTime(ATime, _SQLServerFormat);
end;

initialization

end.
