unit TypesConsts;

{$mode objfpc}{$H+}

interface

uses
  TypInfo, SysUtils, Classes, KeyValue, KeyStringValue, FieldInfo, EntityFieldNamesToken;

type
 {$IFNDEF VER210}
  TDate = type TDateTime;
  TTime = type TDateTime;
  TBytes = array of Byte;
  TKeyValueArray = KeyValue.TKeyValueArray;
  TKeyStringValueArray = KeyStringValue.TKeyStringValueArray;
  TFieldInfoArray = FieldInfo.TFieldInfoArray;
  TEntityFieldNamesTokenArray = EntityFieldNamesToken.TEntityFieldNamesTokenArray;
  TSkyString = WideString;
 {$ELSE}
  TSkyString = string;
  TEntityFieldNamesTokenArray = EntityFieldNamesToken.TEntityFieldNamesToken.TArray;
  TKeyStringValueArray = KeyStringValue.TKeyStringValue.TArray;
  TFieldInfoArray = TFieldInfo.TArray;
 {$ENDIF}
  TBlobData = TBytes;
  PTSkyString = ^TSkyString;
  TKeyValue = KeyValue.TKeyValue;
  TKeyStringValue = KeyStringValue.TKeyStringValue;
  TFieldInfo = FieldInfo.TFieldInfo;
  TEntityFieldNamesToken = EntityFieldNamesToken.TEntityFieldNamesToken;

  TId = type Int64;
  TIds = array of TId;
  TBlobType = class(TMemoryStream);
  TObjects = array of TObject;
  TPointers = array of pointer;
  TClasses = array of TClass;
  TIntegers = array of Integer;
  TDoubles = array of Double;
  TInt64s = array of Int64;
  TBooleans = array of Boolean;
  TPasswordString = type string;

  TErrorInformation = (eiFullInfo, eiProgrammer, eiUser);
  TLogMessageType = (lmtInfo, lmtWarning, lmtError);
  TErrorSeverity = (esDEBUG, esINFO, esWARN, esERROR, esFATAL, esUNKNOWN);
  TTimePrecision = (dpYEAR, dpMONTH, dpWEEK, dpDAY, dpHOUR, dpMINUTE, dpSECOND);
  TVariants = array of Variant;

const
  IdNil = 0;
  ReturnLF = #13#10;
  TabSeparator = #9;
  InfiniteDate = 999999999;
  OneSecond = 1 / 86400;
  OneMinute = 1 / 1440;
  OneHour = 1/24;
  OneDay = 1;
  OneWeek = 7;
  DEFAULT_DECIMALSEPARATOR = '.';

var
  _SQLServerFormat: TFormatSettings;
  _SQLFormat: TFormatSettings;
  _ROFormat: TFormatSettings;

implementation

initialization
  _SQLFormat.DateSeparator := '-';
  _SQLFormat.DecimalSeparator := '.';
  _SQLFormat.ThousandSeparator := ',';
  _SQLFormat.TimeSeparator  :=':';
  _SQLFormat.ShortDateFormat := 'yyyy-MM-dd';
  _SQLFormat.ShortTimeFormat := 'HH:mm:ss';
  _SQLServerFormat := _SQLFormat;

  _ROFormat.DateSeparator := '-';
  _ROFormat.DecimalSeparator := ',';
  _ROFormat.ThousandSeparator := '.';
  _ROFormat.TimeSeparator  :=':';
  _ROFormat.ShortDateFormat := 'dd-MM-YYYY';
  _ROFormat.ShortTimeFormat := 'HH:mm:ss';

finalization

end.
