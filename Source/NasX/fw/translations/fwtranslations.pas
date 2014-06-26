unit FWTranslations;

{$mode objfpc}{$H+}

interface

const
  tlClassNotRegistered = 'tlClassNotRegistered';
  tlEntityFieldNameTokenNotFound = 'tlEntityFieldNameTokenNotFound';
  tlErrorLoadingJson = 'tlErrorLoadingJson';
  tlFieldDoesNotExist = 'tlFieldDoesNotExist';
  tlInvalidClassType = 'tlInvalidClassType';
  tlInvalidPropertyType = 'tlInvalidPropertyType';
  tlInvalidValueForField = 'tlInvalidValueForField';
  tlListCapacityOutOfBounds = 'tlListCapacityOutOfBounds';
  tlListIndexOutOfBounds = 'tlListIndexOutOfBounds';
  tlListValueDoesNotExist = 'tlListValueDoesNotExist';
  tlParameterCountMismatch = 'tlParameterCountMismatch';

function Translate(const AName: WideString;ALanguage: Integer = -1): WideString;

implementation

function Translate(const AName: WideString; ALanguage: Integer = -1): WideString;
begin
  Result := AName; // should add read translations
end;

end.
