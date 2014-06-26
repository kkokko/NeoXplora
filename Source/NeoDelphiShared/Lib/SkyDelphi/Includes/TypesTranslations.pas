unit TypesTranslations;

interface

uses
  TypesConsts;

function MessageTypeToStr(AMessageType: TLogMessageType): string;
function ErrorSeverityToStr(AErrorSeverity: TErrorSeverity): string;

implementation

uses
  Translations, TypInfo;

function MessageTypeToStr(AMessageType: TLogMessageType): string;
var
  TheName: string;
begin
  TheName := GetEnumName(TypeInfo(TLogMessageType), Integer(AMessageType));
  Result := Translate('tlTLogMessageType' + '_' + TheName);
end;

function ErrorSeverityToStr(AErrorSeverity: TErrorSeverity): string;
var
  TheName: string;
begin
  TheName := GetEnumName(TypeInfo(TErrorSeverity), Integer(AErrorSeverity));
  Result := Translate('tlTErrorSeverity' + '_' + TheName);
end;

end.
