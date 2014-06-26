unit MySQLData;

interface

uses
  TypesConsts;

type
  TMySQLData = class
  public
    class function VariantToString(AVariant: Variant; ADataType: string): string;
    class function DateToStr(ADate: TDate): string;
    class function DateTimeToStr(ADateTime: TDateTime): string;
    class function TimeToStr(ADateTime: TDateTime): string;
    class function DoubleToStr(ADouble: Double): string;
    class function EscapeString(AString: string): string;
  end;

implementation

uses
  Variants, SysUtils, Windows;

var
  _MySQLFormat: TFormatSettings;

{ TMySQLData }

class function TMySQLData.VariantToString(AVariant: Variant; ADataType: string): string;
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

class function TMySQLData.EscapeString(AString: string): string;
var
  I: Integer;
begin
  Result := '';
  for I := 1 to Length(AString) do
    if not {$IFDEF VER210}CharInSet(AString[I], {$ELSE}(AString[I] in{$ENDIF} [#0,
      #10, #13, #26, #34, #39, #92])
    then
      Result := Result + AString[i]
    else
    begin
      Result := Result + #92;
      case AString[i] of
        #0: Result := Result + #48;
        #10: Result := Result + #110;
        #13: Result := Result + #114;
        #26: Result := Result + #90;
        #34: Result := Result + #34;
        #39: Result := Result + #39;
        #92: Result := Result + #92;
      end;
    end;
end;

class function TMySQLData.DateToStr(ADate: TDate): string;
begin
  Result := SysUtils.DateToStr(ADate, _MySQLFormat);
end;

class function TMySQLData.DoubleToStr(ADouble: Double): string;
begin
  Result := SysUtils.FloatToStr(ADouble, _MySQLFormat);
end;

class function TMySQLData.DateTimeToStr(ADateTime: TDateTime): string;
begin
  Result := SysUtils.DateTimeToStr(ADateTime, _MySQLFormat);
end;

class function TMySQLData.TimeToStr(ADateTime: TDateTime): string;
begin
  Result := SysUtils.TimeToStr(ADateTime, _MySQLFormat);
end;

initialization
  GetLocaleFormatSettings(GetThreadLocale, _MySQLFormat);
  _MySQLFormat.DateSeparator := '-';
  _MySQLFormat.DecimalSeparator := '.';
  _MySQLFormat.ThousandSeparator := ',';
  _MySQLFormat.ShortDateFormat := 'yyyy-MM-dd';
  _MySQLFormat.ShortTimeFormat := 'HH:mm:ss';

end.
