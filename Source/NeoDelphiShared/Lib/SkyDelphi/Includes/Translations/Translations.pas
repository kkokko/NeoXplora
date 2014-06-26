unit Translations;

interface

const
  tlAbout = 'tlAbout';
  tlAdd = 'tlAdd';
  tlAddFollowing = 'tlAddFollowing';
  tlApply = 'tlApply';
  tlAuthenticationMissing = 'tlAuthenticationMissing';
  tlAuthorityNameValidationText = 'tlAuthorityNameValidationText';
  tlCancel = 'tlCancel';
  tlCannotDeleteFile = 'tlCannotDeleteFile';
  tlCD = 'tlCD';
  tlCheckUpdate = 'tlCheckUpdate';
  tlClassNotRegistered = 'tlClassNotRegistered';
  tlClear = 'tlClear';
  tlCode = 'tlCode';
  tlComplexGenericEntitiesNotSuported = 'tlComplexGenericEntitiesNotSuported';
  tlConfirmPassword = 'tlConfirmPassword';
  tlCouldNotCreateObject = 'tlCouldNotCreateObject';
  tlCreateUser = 'tlCreateUser';
  tlCurrentFile = 'tlCurrentFile';
  tlCustomError = 'tlCustomError';
  tlDatabaseRecordDoesNotExist = 'tlDatabaseRecordDoesNotExist';
  tlDatabaseReferenceConstraint = 'tlDatabaseReferenceConstraint';
  tlDataNoLongerValid = 'tlDataNoLongerValid';
  tlDelete = 'tlDelete';
  tlDeleteSelectedConfirmation = 'tlDeleteSelectedConfirmation';
  tlDeleteUser = 'tlDeleteUser';
  tlDepartment = 'tlDepartment';
  tlDetails = 'tlDetails';
  tlDevelopedBySkyProject = 'tlDevelopedBySkyProject';
  tlDuplicateKey = 'tlDuplicateKey';
  tlElementsLC = 'tlElementsLC';
  tlEnglish = 'tlEnglish';
  tlEnterANewName = 'tlEnterANewName';
  tlEntityFieldNameTokenNotFound = 'tlEntityFieldNameTokenNotFound';
  tlError = 'tlError';
  tlErrorLoadingJson = 'tlErrorLoadingJson';
  tlErrorLoadingXML = 'tlErrorLoadingXML';
  tlErrorWhileCommunicatingWithServer = 'tlErrorWhileCommunicatingWithServer';
  tlExit = 'tlExit';
  tlExtraBytesFoundAtEndOfFile = 'tlExtraBytesFoundAtEndOfFile';
  tlExtraCharactersInStream = 'tlExtraCharactersInStream';
  tlFieldDoesNotExist = 'tlFieldDoesNotExist';
  tlFieldNameAllreadyExists = 'tlFieldNameAllreadyExists';
  tlFile = 'tlFile';
  tlFileDoesNotExistInTheFileStore = 'tlFileDoesNotExistInTheFileStore';
  tlFileStoreReadOnlyCannotExecute = 'tlFileStoreReadOnlyCannotExecute';
  tlFolder = 'tlFolder';
  tlFolderAllreadyExists = 'tlFolderAllreadyExists';
  tlForMoreInformationSee = 'tlForMoreInformationSee';
  tlFrench = 'tlFrench';
  tlHelp = 'tlHelp';
  tlInternetConnection = 'tlInternetConnection';
  tlInvalidBuffer = 'tlInvalidBuffer';
  tlInvalidClassType = 'tlInvalidClassType';
  tlInvalidCrcFileCannotBeExtracted = 'tlInvalidCrcFileCannotBeExtracted';
  tlInvalidFile = 'tlInvalidFile';
  tlInvalidFileStore = 'tlInvalidFileStore';
  tlInvalidInterval = 'tlInvalidInterval';
  tlInvalidOrPassword = 'tlInvalidOrPassword';
  tlInvalidPackageFile = 'tlInvalidPackageFile';
  tlInvalidPropertyType = 'tlInvalidPropertyType';
  tlInvalidRequest = 'tlInvalidRequest';
  tlInvalidSession = 'tlInvalidSession';
  tlInvalidStreamCannotDecode = 'tlInvalidStreamCannotDecode';
  tlInvalidUserName = 'tlInvalidUserName';
  tlInvalidUserNameOrPassword = 'tlInvalidUserNameOrPassword';
  tlInvalidValueForField = 'tlInvalidValueForField';
  tlLanguageNotRegistered = 'tlLanguageNotRegistered';
  tlLastLogin = 'tlLastLogin';
  tlLastUpdate = 'tlLastUpdate';
  tlListCapacityOutOfBounds = 'tlListCapacityOutOfBounds';
  tlListIndexOutOfBounds = 'tlListIndexOutOfBounds';
  tlListValueDoesNotExist = 'tlListValueDoesNotExist';
  tlLogin = 'tlLogin';
  tlLoginFailed = 'tlLoginFailed';
  tlLoginRetryExceeded = 'tlLoginRetryExceeded';
  tlMainSessionNotFound = 'tlMainSessionNotFound';
  tlName = 'tlName';
  tlNo = 'tlNo';
  tlNr = 'tlNr';
  tlNumberOfElementsDiffers = 'tlNumberOfElementsDiffers';
  tlObjectNotFound = 'tlObjectNotFound';
  tlObjects = 'tlObjects';
  tlOk = 'tlOk';
  tlOptions = 'tlOptions';
  tlParameterCountMismatch = 'tlParameterCountMismatch';
  tlPassword = 'tlPassword';
  tlPathDoesNotExist = 'tlPathDoesNotExist';
  tlPort = 'tlPort';
  tlQueryReturnedMoreThenOneRow = 'tlQueryReturnedMoreThenOneRow';
  tlRecordIsUsed = 'tlRecordIsUsed';
  tlRemarks = 'tlRemarks';
  tlRemoveFollowing = 'tlRemoveFollowing';
  tlRename = 'tlRename';
  tlRequiredXmlNodeNotFound = 'tlRequiredXmlNodeNotFound';
  tlRevertChanges = 'tlRevertChanges';
  tlRomanian = 'tlRomanian';
  tlSave = 'tlSave';
  tlSaveChanges = 'tlSaveChanges';
  tlSelectedLanguage = 'tlSelectedLanguage';
  tlSelectedObjectMissing = 'tlSelectedObjectMissing';
  tlServerNotOnline = 'tlServerNotOnline';
  tlSetFavourite = 'tlSetFavourite';
  tlSetting = 'tlSetting';
  tlStart = 'tlStart';
  tlStartDateGreater = 'tlStartDateGreater';
  tlStop = 'tlStop';
  tlTableDoesNotExistInThePackage = 'tlTableDoesNotExistInThePackage';
  tlTErrorSeverity_esDEBUG = 'tlTErrorSeverity_esDEBUG';
  tlTErrorSeverity_esERROR = 'tlTErrorSeverity_esERROR';
  tlTErrorSeverity_esFATAL = 'tlTErrorSeverity_esFATAL';
  tlTErrorSeverity_esINFO = 'tlTErrorSeverity_esINFO';
  tlTErrorSeverity_esUNKNOWN = 'tlTErrorSeverity_esUNKNOWN';
  tlTErrorSeverity_esWARN = 'tlTErrorSeverity_esWARN';
  tlTest = 'tlTest';
  tlTheObjectLC = 'tlTheObjectLC';
  tlTMessageType_mtError = 'tlTMessageType_mtError';
  tlTMessageType_mtInfo = 'tlTMessageType_mtInfo';
  tlTMessageType_mtWarning = 'tlTMessageType_mtWarning';
  tlUnknownException = 'tlUnknownException';
  tlUpdateOptions = 'tlUpdateOptions';
  tlUpdateStatus = 'tlUpdateStatus';
  tlUser = 'tlUser';
  tlUserDoesNotHaveTheRightsToAccess = 'tlUserDoesNotHaveTheRightsToAccess';
  tlUserName = 'tlUserName';
  tlUserNameAllreadyExists = 'tlUserNameAllreadyExists';
  tlUsers = 'tlUsers';
  tlYes = 'tlYes';

function Translate(const AName: WideString;ALanguage: Integer = -1): WideString;

implementation

uses
  Languages;

function Translate(const AName: WideString; ALanguage: Integer = -1): WideString;
begin
  Result := TLanguages.Instance.Translate(AName, ALanguage);
end;

end.

