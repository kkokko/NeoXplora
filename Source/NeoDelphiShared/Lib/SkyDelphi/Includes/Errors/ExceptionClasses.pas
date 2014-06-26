unit ExceptionClasses;

interface

uses
  SkyException;

// Warning message: Properties don't need to be publised because they are
// saved in Params in OnCreate, and loaded from Params in LoadFromStream
type
{$Region 'ESkyListParameterCountMismatch'}
  ESkyListParameterCountMismatch = class(ESkyException)
  public
    constructor Create(ARaisedBy: TObject; const ALocation: string); reintroduce;
  end; // 'Parameter count mismatch'

{$EndRegion}
{$Region 'ESkyListIndexError'}
  ESkyListIndexError = class(ESkyException)
  private
    function GetIndex: Integer;
    procedure SetIndex(const Value: Integer);
  protected
    function GetTranslatedMessage: string; override;
  public
    constructor Create(ARaisedBy: TObject; const ALocation: string; AIndex: Integer); reintroduce;
    property Index: Integer read GetIndex write SetIndex;
  end; // Format('List index out of bounds (%d)', [Index1])

{$EndRegion}
{$Region 'ESkyListCapacityError'}
  ESkyListCapacityError = class(ESkyException)
  private
    function GetCapacity: Integer;
    procedure SetCapacity(const Value: Integer);
  protected
    function GetTranslatedMessage: string; override;
  public
    constructor Create(ARaisedBy: TObject; const ALocation: string; ACapacity: Integer); reintroduce;
    property Capacity: Integer read GetCapacity write SetCapacity;
  end; // Format('List capacity out of bounds (%d)', [Capacity])

{$EndRegion}
{$Region 'ESkyListValueDoesNotExistError'}
  ESkyListValueDoesNotExistError = class(ESkyException)
  private
    function GetValue: string;
    procedure SetValue(const Value: string);
  protected
    function GetTranslatedMessage: string; override;
  public
    constructor Create(ARaisedBy: TObject; const ALocation: string; AValue: string); reintroduce;
    property Value: string read GetValue write SetValue;
  end; // Format('Value %s does not exist in the list', [Value])

{$EndRegion}
{$Region 'ESkyNoTranslationsError'}
  ESkyNoTranslationsError = class(ESkyException)
  private
    function GetLanguageId: Integer;
    procedure SetLanguageId(const Value: Integer);
  protected
    function GetTranslatedMessage: string; override;
  public
    constructor Create(const ARaisedBy, ALocation: string; ALanguageId: Integer); reintroduce;
    property LanguageId: Integer read GetLanguageId write SetLanguageId;
  end; // Format('Language %d not registered', [TheLanguageId])

{$EndRegion}
{$Region 'ESkyFieldDoesNotExist'}
  ESkyFieldDoesNotExist = class(ESkyException)
  private
    function GetFieldName: string;
    procedure SetFieldName(const Value: string);
  protected
    function GetTranslatedMessage: string; override;
  public
    constructor Create(ARaisedBy: TObject; const ALocation, AFieldName: string); reintroduce;
    property FieldName: string read GetFieldName write SetFieldName;
  end;// Format('Field does not exist: %s', [AFieldName])

{$EndRegion}
{$Region 'ESkyClassNotRegistered'}
  ESkyClassNotRegistered =  class(ESkyException)
  private
    function GetEntityTypeName: string;
    procedure SetEntityTypeName(const Value: string);
  protected
    function GetTranslatedMessage: string; override;
  public
    constructor Create(const ARaisedBy, ALocation, AEntityTypeName: string); reintroduce;
    property EntityTypeName: string read GetEntityTypeName write SetEntityTypeName;
  end; // Format(Class not registered: %s', [TheEntityTypeName])

{$EndRegion}
{$Region 'ESkyEntityFieldNameTokenNotFound'}
  ESkyEntityFieldNameTokenNotFound = class(ESkyException)
  private
    function GetTokenName: string;
    procedure SetTokenName(const Value: string);
  protected
    function GetTranslatedMessage: string; override;
  public
    constructor Create(ARaisedBy: TObject; const ALocation, ATokenName: string); reintroduce;
    property TokenName: string read GetTokenName write SetTokenName;
  end;// Format('Entity field name token %s not found', [SomeTokens[I].TokenString])

{$EndRegion}
{$Region 'ESkyInvalidPackageFile'}
  ESkyInvalidPackageFile = class(ESkyException)
  private
    function GetTranslatedMessageTranslation: string;
    procedure SetMessageTranslation(const Value: string);
  protected
    function GetTranslatedMessage: string; override;
  public
    constructor Create(ARaisedBy: TObject; const ALocation, AMessageTranslation: string); reintroduce;
    property MessageTranslation: string read GetTranslatedMessageTranslation write SetMessageTranslation;
  end; // Format('Invalid file %s', [AMessage])

{$EndRegion}
{$Region 'ESkyFileStoreFileDoesNotExist'}
  ESkyFileStoreFileDoesNotExist = class(ESkyException)
  private
    function GetFileName: string;
    procedure SetFileName(const Value: string);
  protected
    function GetTranslatedMessage: string; override;
  public
    constructor Create(ARaisedBy: TObject; const ALocation, AFileName: string); reintroduce;
    property FileName: string read GetFileName write SetFileName;
  end; // Format('File does not exist in the file storage: %s', [AFileName])

{$EndRegion}
{$Region 'ESkyInvalidFileStore'}
  ESkyInvalidFileStore = class(ESkyException)
  private
    function GetErrorMessageTranslation: string;
    procedure SetErrorMessageTranslation(const Value: string);
  protected
    function GetTranslatedMessage: string; override;
  public
    constructor Create(ARaisedBy: TObject; const ALocation, AErrorMessageTranslation: string); reintroduce;
    property ErrorMessageTranslation: string read GetErrorMessageTranslation write SetErrorMessageTranslation;
  end; // Format('Invalid file storage: %s', ['Invalid buffer'])

{$EndRegion}
{$Region 'ESkyInvalidCrcFileCannotBeExtracted'}
  ESkyInvalidCrcFileCannotBeExtracted = class(ESkyException)
  private
    function GetFileName: string;
    procedure SetFileName(const Value: string);
  protected
    function GetTranslatedMessage: string; override;
  public
    constructor Create(ARaisedBy: TObject; const ALocation, AFileName: string); reintroduce;
    property FileName: string read GetFileName write SetFileName;
  end;// Format('Invalid CRC, the file %s cannot be extracted', [AFileName])

{$EndRegion}
{$Region 'ESkyFileStoreIsInReadOnlyMode'}
  ESkyFileStoreIsInReadOnlyMode = class(ESkyException)
  private
    function GetFileName: string;
    procedure SetFileName(const Value: string);
    function GetActionTranslation: string;
    procedure SetActionTranslation(const Value: string);
  protected
    function GetTranslatedMessage: string; override;
  public
    constructor Create(ARaisedBy: TObject; const ALocation, AnActionTranslation, AFileName: string); reintroduce;
    property FileName: string read GetFileName write SetFileName;
    property ActionTranslation: string read GetActionTranslation write SetActionTranslation;
  end;// Format('File storage is readonly, cannot %s', ['add: '+ <AFileName>]) clear - no filename

{$EndRegion}
{$Region 'ESkyFileStoreTableDoesNotExist'}
  ESkyFileStoreTableDoesNotExist = class(ESkyException)
  private
    function GetTableName: string;
    procedure SetTableName(const Value: string);
  protected
    function GetTranslatedMessage: string; override;
  public
    constructor Create(ARaisedBy: TObject; const ALocation, ATableName: string); reintroduce;
    property TableName: string read GetTableName write SetTableName;
  end;// Format('Table: %s does not exist in the package', [AName])

{$EndRegion}
{$Region 'ESkyDatabaseRecordDoesNotExist'}
  ESkyDatabaseRecordDoesNotExist = class(ESkyException)
  private
    function GetEntityClass: string;
    procedure SetEntityClass(const Value: string);
  public
    constructor Create(ARaisedBy: TObject; const ALocation, AEntityClass: string); reintroduce;
    function GetTranslatedMessage: string; override;
    property EntityClass: string read GetEntityClass write SetEntityClass;
  end;// Format('Record does not exist in the database: %s', [EntityClass])

{$EndRegion}
{$Region 'ESkyEntityPickerInvalidSelection'}
  ESkyEntityPickerInvalidSelection = class(ESkyException)
  public
    constructor Create(ARaisedBy: TObject; const ALocation: string); reintroduce;
  end; // 'Selected object does not exist in the list'

{$EndRegion}
{$Region 'ESkyInvalidValueForField'}
  ESkyInvalidValueForField = class(ESkyException)
  private
    function GetFieldName: string;
    procedure SetFieldName(const Value: string);
    function GetValidationText: string;
    procedure SetValidationText(const Value: string);
  protected
    function GetTranslatedMessage: string; override;
  public
    constructor Create(ARaisedBy: TObject; const ALocation, AFieldName: string;
      const AValidationText: string = ''); reintroduce;
    property FieldName: string read GetFieldName write SetFieldName;
    property ValidationText: string read GetValidationText write SetValidationText;
  end;// Format('Invalid value for field: %s', [AFieldName])

{$EndRegion}
{$Region 'ESkyFieldNameAllreadyExists'}
  ESkyFieldNameAllreadyExists = class(ESkyException)
  private
    function GetFieldName: string;
    procedure SetFieldName(const Value: string);
  protected
    function GetTranslatedMessage: string; override;
  public
    constructor Create(ARaisedBy: TObject; const ALocation, AFieldName: string); reintroduce;
    property FieldName: string read GetFieldName write SetFieldName;
  end;// Format('Invalid value for field: %s', [AFieldName])

{$EndRegion}
{$Region 'ESkyDupplicateUserName'}
  ESkyDupplicateUserName = class(ESkyException)
  private
    function GetUserName: string;
    procedure SetUserName(const Value: string);
  protected
    function GetTranslatedMessage: string; override;
  public
    constructor Create(ARaisedBy: TObject; const ALocation, AUserName: string); reintroduce;
    property UserName: string read GetUserName write SetUserName;
  end; // Format('The username allready exists: %s' , [AUserName])'

{$EndRegion}
{$Region 'ESkyObjectNotFound'}
  ESkyObjectNotFound = class(ESkyException)
  private
    function GetDataChanged: string;
    procedure SetDataChanged(const Value: string);
    function GetDataValue: string;
    procedure SetDataValue(const Value: string);
  protected
    function GetTranslatedMessage: string; override;
  public
    constructor Create(ARaisedBy: TObject; const ALocation, ADataChanged, AValue: string); reintroduce;
    property DataValue: string read GetDataValue write SetDataValue;
    property DataChanged: string read GetDataChanged write SetDataChanged;
  end; // Format('Object not found - %s : "%s"' , [ADataChanged, AValue])'

{$EndRegion}
{$Region 'ESkyPathDoesNotExist'}
  ESkyPathDoesNotExist = class(ESkyException)
  private
    function GetPath: string;
    procedure SetPath(const Value: string);
  protected
    function GetTranslatedMessage: string; override;
  public
    constructor Create(ARaisedBy: TObject; const ALocation, APath: string); reintroduce;
    property Path: string read GetPath write SetPath;
  end; // Format('Path does not exist: "%s"' , [APath])'

{$EndRegion}
{$Region 'ESkyFolderAllreadyExists'}
  ESkyFolderAllreadyExists = class(ESkyException)
  private
    function GetFolder: string;
    procedure SetFolder(const Value: string);
  protected
    function GetTranslatedMessage: string; override;
  public
    constructor Create(ARaisedBy: TObject; const ALocation, AFolder: string); reintroduce;
    property Folder: string read GetFolder write SetFolder;
  end; // Format('Folder does not exist: "%s"' , [AFolder])'

{$EndRegion}
{$Region 'ESkyCouldNotCreateObject'}
  ESkyCouldNotCreateObject = class(ESkyException)
  private
    function GetObjectType: string;
    function GetObjectValue: string;
    procedure SetObjectType(const Value: string);
    procedure SetObjectValue(const Value: string);
  protected
    function GetTranslatedMessage: string; override;
  public
    constructor Create(ARaisedBy: TObject; const ALocation, AnObjectType, AnObjectValue: string); reintroduce;
    property ObjectType: string read GetObjectType write SetObjectType;
    property ObjectValue: string read GetObjectValue write SetObjectValue;
  end; // Format('Could not create %s: "%s"' , [AnObjectType, AnObjectValue])'

{$EndRegion}
{$Region 'ESkyUserDoesNotHaveTheRightsToAccess'}
  ESkyUserDoesNotHaveTheRightsToAccess = class(ESkyException)
  private
    function GetObjectValue: string;
    procedure SetObjectValue(const Value: string);
  protected
    function GetTranslatedMessage: string; override;
  public
    constructor Create(ARaisedBy: TObject; const ALocation, AnObjectValue: string); reintroduce;
    property ObjectValue: string read GetObjectValue write SetObjectValue;
  end; // Format('User does not have the right(s) to access "%s"' , [ObjectValue])'

{$EndRegion}
{$Region 'ESkyExtraCharactersInStream'}
  ESkyExtraCharactersInStream = class(ESkyException)
  private
    function GetNumberOfChars: Int64;
    procedure SetNumberOfChars(const Value: Int64);
  protected
    function GetTranslatedMessage: string; override;
  public
    constructor Create(ARaisedBy: TObject; const ALocation: string; ANumberOfChars: Int64); reintroduce;
    property NumberOfChars: Int64 read GetNumberOfChars write SetNumberOfChars;
  end; // Format('Extra characters found at the end of the stream: %d, [NumberOfChars])'

{$EndRegion}
{$Region 'ESkyInvalidFile'}
  ESkyInvalidFile = class(ESkyException)
  private
    function GetFilename: string;
    procedure SetFilename(const Value: string);
  protected
    function GetTranslatedMessage: string; override;
  public
    constructor Create(ARaisedBy: TObject; const ALocation: string; AFilename: string); reintroduce;
    property Filename: string read GetFilename write SetFilename;
  end; // Format('Invalid file %s', [FileName])'

{$EndRegion}
{$Region 'ESkyCannotDeleteFile'}
  ESkyCannotDeleteFile = class(ESkyException)
  private
    function GetFilename: string;
    procedure SetFilename(const Value: string);
  protected
    function GetTranslatedMessage: string; override;
  public
    constructor Create(ARaisedBy: TObject; const ALocation: string; AFilename: string); reintroduce;
    property Filename: string read GetFilename write SetFilename;
  end; // Format('Could not delete the file: %s.', [FileName])'

{$EndRegion}
{$Region 'ESkyCumstomError'}
  ESkyCustomError = class(ESkyException)
  private
    procedure SetErrorMessage(const Value: string);
    function GetErrorMessage: string;
  protected
    function GetTranslatedMessage: string; override;
  public
    constructor Create(ARaisedBy: TObject; const ALocation, AnErrorMessage: string); reintroduce; virtual;
    property ErrorMessage: string read GetErrorMessage write SetErrorMessage;
  end; // Format('Error: %s', [ErrorMessage])'

{$EndRegion}
{$Region 'ESkyInvalidClassType'}
  ESkyInvalidClassType= class(ESkyException)
  private
    function GetExpected: string;
    function GetReceived: string;
    procedure SetExpected(const Value: string);
    procedure SetReceived(const Value: string);
  protected
    function GetTranslatedMessage: string; override;
  public
    constructor Create(ARaisedBy: TObject; const ALocation, AExpected, AReceived: string); reintroduce;
    property Expected: string read GetExpected write SetExpected;
    property Received: string read GetReceived write SetReceived;
  end; // Format('Invalid classtype. Expected "%s". Got "%s".' , [AExpected, AReceived])'

{$EndRegion}
{$Region 'ESkyServerCommunicationError'}
  ESkyServerCommunicationError = class(ESkyException)
  public
    constructor Create(ARaisedBy: TObject; const ALocation: string); reintroduce;
  end; // Format('Error while communicating with the server', [])'
{$EndRegion}
{$Region 'ESkyServerNotOnline'}
  ESkyServerNotOnline = class(ESkyException)
  public
    constructor Create(ARaisedBy: TObject; const ALocation: string); reintroduce;
  end; // Format('Server not online', [])'

{$EndRegion}
{$Region 'ESkyNumberOfElementsDiffers'}
  ESkyNumberOfElementsDiffers = class(ESkyException)
  private
    function GetFieldName: string;
    function GetObjectName: string;
    function GetObjectValue: string;
    procedure SetFieldName(const Value: string);
    procedure SetObjectName(const Value: string);
    procedure SetObjectValue(const Value: string);
  protected
    function GetTranslatedMessage: string; override;
  public
    constructor Create(ARaisedBy: TObject; const ALocation, AFieldName, AObjectName, AObjectValue: string); reintroduce;
    property FieldName: string read GetFieldName write SetFieldName;
    property ObjectName: string read GetObjectName write SetObjectName;
    property ObjectValue: string read GetObjectValue write SetObjectValue;
  end; // Format('Number of %s differs for %s %s' , [AFieldName, AObjectName, ObjectValue])'

{$EndRegion}
{$Region 'ESkyInvalidRequest'}
  ESkyInvalidRequest = class(ESkyException)
  private
    function GetRequest: string;
    procedure SetRequest(const Value: string);
  protected
    function GetTranslatedMessage: string; override;
  public
    constructor Create(ARaisedBy: TObject; const ALocation, ARequest: string); reintroduce;
    property Request: string read GetRequest write SetRequest;
  end; // Format('Invalid request("%s"). Closing connection', [ARequest])'

{$EndRegion}
{$Region 'ESkyRequiredXmlNodeNotFound'}
  ESkyRequiredXmlNodeNotFound = class(ESkyException)
  private
    function GetChildNode: string;
    function GetParentNode: string;
    procedure SetChildNode(const Value: string);
    procedure SetParentNode(const Value: string);
  protected
    function GetTranslatedMessage: string; override;
  public
    constructor Create(const ARaisedBy, ALocation, AParentNode, AChildNode: string); reintroduce;
    property ParentNode: string read GetParentNode write SetParentNode;
    property ChildNode: string read GetChildNode write SetChildNode;
  end; // Format('Invalid xml structure. Required node not found: %s.%s.', [AParentNode, AChildNode])'

{$EndRegion}
{$Region 'ESkyInvalidUserNameError'}
  ESkyInvalidUserNameError = class(ESkyException)
  public
    constructor Create(ARaisedBy: TObject; const ALocation: string); reintroduce;
  end; // Format('Invalid user name or password', [])'

{$EndRegion}
{$Region 'ESkyAuthenticationMissing'}
  ESkyAuthenticationMissing = class(ESkyFatalException)
  public
    constructor Create(ARaisedBy: TObject; const ALocation: string); reintroduce;
  end; // Format('Authentication missing')'

{$EndRegion}
{$Region 'ESkyServerUnknownException'}
  ESkyServerUnknownException = class(ESkyException)
  private
    function GetErrorMessage: string;
    procedure SetErrorMessage(const Value: string);
  protected
    function GetTranslatedMessage: string; override;
  public
    constructor Create(ARaisedBy: TObject; const ALocation, AnErrorMessage: string); reintroduce;
    property ErrorMessage: string read GetErrorMessage write SetErrorMessage;
  end; // Format('Unknown exception: %s', [AnErrorMessage])'

{$EndRegion}
{$Region 'ESkyInvalidSession'}
  ESkyInvalidSession = class(ESkyException)
  public
    constructor Create(ARaisedBy: TObject; const ALocation: string); reintroduce;
  end; // Format('Invalid session')'

{$EndRegion}
{$Region 'ESkyInvalidPropertyType'}
  ESkyInvalidPropertyType = class(ESkyException)
  private
    function GetPropertyName: string;
    procedure SetPropertyName(const Value: string);
  protected
    function GetTranslatedMessage: string; override;
  public
    constructor Create(ARaisedBy: TObject; const ALocation, APropertyName: string); reintroduce;
    property PropertyName: string read GetPropertyName write SetPropertyName;
  end; // Format('Invalid type for property: %s', AProperty)

{$EndRegion}
{$Region 'ESkyErrorLoadingXML'}
  ESkyErrorLoadingXML = class(ESkyException)
  private
    function GetErrorString: string;
    procedure SetErrorString(const Value: string);
  protected
    function GetTranslatedMessage: string; override;
  public
    constructor Create(ARaisedBy: TObject; const ALocation, AErrorString: string); reintroduce;
    property ErrorString: string read GetErrorString write SetErrorString;
  end; // Format('Error loading XML: %s', ErrorString)

{$EndRegion}
{$Region 'ESkyComplexGenericEntitiesNotSuported'}
  ESkyComplexGenericEntitiesNotSuported = class(ESkyException)
  private
    function GetPropertyName: string;
    procedure SetPropertyName(const Value: string);
  protected
    function GetTranslatedMessage: string; override;
  public
    constructor Create(ARaisedBy: TObject; const ALocation, APropertyName: string); reintroduce;
    property PropertyName: string read GetPropertyName write SetPropertyName;
  end; // Format('Loading complex generic entities is not suported, property: %s', AProperty)

{$EndRegion}
{$Region 'ESkyInvalidStream'}
  ESkyInvalidStream = class(ESkyException)
  private
    function GetReason: string;
    procedure SetReason(const Value: string);
  protected
    function GetTranslatedMessage: string; override;
  public
    constructor Create(ARaisedBy: TObject; const ALocation, AReason: string); reintroduce;
    property Reason: string read GetReason write SetReason;
  end; // Format('Invalid stream. Cannot decode: %s', [AReason])

{$EndRegion}
{$Region 'ESkyErrorLoadingJson'}
  ESkyErrorLoadingJson = class(ESkyException)
  private
    function GetErrorString: string;
    procedure SetErrorString(const Value: string);
  protected
    function GetTranslatedMessage: string; override;
  public
    constructor Create(ARaisedBy: TObject; const ALocation, AErrorString: string); reintroduce;
    property ErrorString: string read GetErrorString write SetErrorString;
  end; // Format('Error loading JSON: %s', ErrorString)

{$EndRegion}
{$Region 'ESkyInvalidUserOrPassword'}
  ESkyInvalidUserOrPassword = class(ESkyException)
  public
    constructor Create(ARaisedBy: TObject; const ALocation: string); reintroduce;
  end; // Format('Invalid user name or password.')

{$EndRegion}
{$Region 'ESkyDuplicateKey'}
  ESkyDuplicateKey = class(ESkyException)
  private
    function GetKeyName: string;
    procedure SetKeyName(const Value: string);
  protected
    function GetTranslatedMessage: string; override;
  public
    constructor Create(ARaisedBy: TObject; const ALocation, AKeyName: string); reintroduce;
    property KeyName: string read GetKeyName write SetKeyName;
  end; // Format('Another record with the same key(%s) allready exists.')

{$EndRegion}
{$Region 'ESkyRecordIsUsed'}
  ESkyRecordIsUsed = class(ESkyException)
  public
    constructor Create(ARaisedBy: TObject; const ALocation: string); reintroduce;
  end; // Format('The record cannot be deleted because it is in use.')

{$EndRegion}
{$Region 'ESkyDataNoLongerValid'}
  ESkyDataNoLongerValid = class(ESkyException)
  private
    function GetData: string;
    procedure SetData(const Value: string);
  protected
    function GetTranslatedMessage: string; override;
  public
    constructor Create(ARaisedBy: TObject; const ALocation, AData: string); reintroduce;
    property Data: string read GetData write SetData;
  end; // Format('Data no longer valid: %s.', [Translate(Data)])

{$EndRegion}
{$Region 'ESkyBeginGreaterThenEndDate'}
  ESkyBeginGreaterThenEndDate = class(ESkyException)
  public
    constructor Create(ARaisedBy: TObject; const ALocation: string); reintroduce;
  end; // Format('The start date cannot be after the end date.')

{$EndRegion}
{$Region 'ESkyInvalidInterval'}
  ESkyInvalidInterval = class(ESkyException)
  public
    constructor Create(ARaisedBy: TObject; const ALocation: string); reintroduce;
  end; // Format('The interval is invalid.')

{$EndRegion}
{$Region 'ESkyMainSessionNotFound'}
  ESkyMainSessionNotFound = class(ESkyException)
  public
    constructor Create(ARaisedBy: TObject; const ALocation: string); reintroduce;
  end; // Format('The main session for the asyncronous thread was not found.')

{$EndRegion}
{$Region 'ESkyDatabaseReferenceConstraint'}
  ESkyDatabaseReferenceConstraint = class(ESkyException)
  private
    function GetAction: string;
    function GetTable: string;
    procedure SetAction(const Value: string);
    procedure SetTable(const Value: string);
  protected
    function GetTranslatedMessage: string; override;
  public
    constructor Create(ARaisedBy: TObject; const ALocation, AnAction, ATable: string); reintroduce;
    property Action: string read GetAction write SetAction;
    property Table: string read GetTable write SetTable;
  end; // Format('The %s action could not be completed because the record is beeing userd by one or more %s.', [Translate(AnAction), Translate(ATable)])

{$EndRegion}

implementation

uses
  Translations, SysUtils, TypesConsts;

{ ESkyListParameterCountMismatch }

constructor ESkyListParameterCountMismatch.Create(ARaisedBy: TObject;
  const ALocation: string);
begin
  inherited Create(ARaisedBy, ALocation, tlParameterCountMismatch);
end;

{ ESkyListIndexError }

constructor ESkyListIndexError.Create(ARaisedBy: TObject;
  const ALocation: string; AIndex: Integer);
begin
  inherited Create(ARaisedBy, ALocation, tlListIndexOutOfBounds);
  Index := AIndex;
end;

function ESkyListIndexError.GetIndex: Integer;
begin
  Result := Params.GetValueForField('Index');
end;

function ESkyListIndexError.GetTranslatedMessage: string;
begin
  Result := Format(Translate(Message), [Index]);
end;

procedure ESkyListIndexError.SetIndex(const Value: Integer);
begin
  Params.SetValueForField('Index', Value);
end;

{ ESkyListCapacityError }

constructor ESkyListCapacityError.Create(ARaisedBy: TObject;
  const ALocation: string; ACapacity: Integer);
begin
  inherited Create(ARaisedBy, ALocation, tlListCapacityOutOfBounds);
  Capacity := ACapacity;
end;

function ESkyListCapacityError.GetCapacity: Integer;
begin
  Result := Params.GetValueForField('Capacity');
end;

function ESkyListCapacityError.GetTranslatedMessage: string;
begin
  Result := Format(Translate(Message), [Capacity]);
end;

procedure ESkyListCapacityError.SetCapacity(const Value: Integer);
begin
  Params.SetValueForField('Capacity', Value);
end;

{ ESkyNoTranslationsError }

constructor ESkyNoTranslationsError.Create(const ARaisedBy, ALocation: string;
  ALanguageId: Integer);
begin
  inherited Create(ARaisedBy, ALocation, tlLanguageNotRegistered);
  LanguageId := ALanguageId;
end;

function ESkyNoTranslationsError.GetLanguageId: Integer;
begin
  Result := Params.GetValueForField('LanguageId');
end;

function ESkyNoTranslationsError.GetTranslatedMessage: string;
begin
  Result := Format(Translate(Message), [LanguageId]);
end;

procedure ESkyNoTranslationsError.SetLanguageId(const Value: Integer);
begin
  Params.SetValueForField('LanguageId', Value);
end;

{ ESkyFieldDoesNotExist }

constructor ESkyFieldDoesNotExist.Create(ARaisedBy: TObject; const ALocation,
  AFieldName: string);
begin
  inherited Create(ARaisedBy, ALocation, tlFieldDoesNotExist);
  FieldName := AFieldName;
end;

function ESkyFieldDoesNotExist.GetFieldName: string;
begin
  Result := Params.GetValueForField('FieldName');
end;

function ESkyFieldDoesNotExist.GetTranslatedMessage: string;
begin
  Result := Format(Translate(Message), [FieldName]);
end;

procedure ESkyFieldDoesNotExist.SetFieldName(const Value: string);
begin
  Params.SetValueForField('FieldName', Value);
end;

{ ESkyClassNotRegistered }

constructor ESkyClassNotRegistered.Create(const ARaisedBy, ALocation,
  AEntityTypeName: string);
begin
  inherited Create(ARaisedBy, ALocation, tlClassNotRegistered);
  EntityTypeName := AEntityTypeName;
end;

function ESkyClassNotRegistered.GetEntityTypeName: string;
begin
  Result := Params.GetValueForField('EntityTypeName');
end;

function ESkyClassNotRegistered.GetTranslatedMessage: string;
begin
  Result := Format(Translate(Message), [EntityTypeName]);
end;

procedure ESkyClassNotRegistered.SetEntityTypeName(const Value: string);
begin
  Params.SetValueForField('EntityTypeName', Value);
end;

{ ESkyEntityFieldNameTokenNotFound }

constructor ESkyEntityFieldNameTokenNotFound.Create(ARaisedBy: TObject;
  const ALocation, ATokenName: string);
begin
  inherited Create(ARaisedBy, ALocation, tlEntityFieldNameTokenNotFound);
  TokenName := ATokenName;
end;

function ESkyEntityFieldNameTokenNotFound.GetTranslatedMessage: string;
begin
  Result := Format(Translate(Message), [TokenName]);
end;

function ESkyEntityFieldNameTokenNotFound.GetTokenName: string;
begin
  Result := Params.GetValueForField('TokenName');
end;

procedure ESkyEntityFieldNameTokenNotFound.SetTokenName(const Value: string);
begin
  Params.SetValueForField('TokenName', Value);
end;

{ ESkyInvalidPackageFile }

constructor ESkyInvalidPackageFile.Create(ARaisedBy: TObject; const ALocation,
  AMessageTranslation: string);
begin
  inherited Create(ARaisedBy, ALocation, tlInvalidPackageFile);
  MessageTranslation := AMessageTranslation;
end;

function ESkyInvalidPackageFile.GetTranslatedMessage: string;
begin
  Result := Format(Translate(Message), [MessageTranslation]);
end;

function ESkyInvalidPackageFile.GetTranslatedMessageTranslation: string;
begin
  Result := Params.GetValueForField('MessageTranslation');
end;

procedure ESkyInvalidPackageFile.SetMessageTranslation(const Value: string);
begin
  Params.SetValueForField('MessageTranslation', Value);
end;

{ ESkyFileStoreFileDoesNotExist }

constructor ESkyFileStoreFileDoesNotExist.Create(ARaisedBy: TObject;
  const ALocation, AFileName: string);
begin
  inherited Create(ARaisedBy, ALocation, tlFileDoesNotExistInTheFileStore);
  FileName := AFileName;
end;

function ESkyFileStoreFileDoesNotExist.GetFileName: string;
begin
  Result := Params.GetValueForField('FileName');
end;

function ESkyFileStoreFileDoesNotExist.GetTranslatedMessage: string;
begin
  Result := Format(Translate(Message), [FileName]);
end;

procedure ESkyFileStoreFileDoesNotExist.SetFileName(const Value: string);
begin
  Params.SetValueForField('FileName', Value);
end;

{ ESkyInvalidFileStore }

constructor ESkyInvalidFileStore.Create(ARaisedBy: TObject; const ALocation,
  AErrorMessageTranslation: string);
begin
  inherited Create(ARaisedBy, ALocation, tlInvalidFileStore);
  ErrorMessageTranslation := AErrorMessageTranslation;
end;

function ESkyInvalidFileStore.GetErrorMessageTranslation: string;
begin
  Result := Params.GetValueForField('ErrorMessageTranslation');
end;

function ESkyInvalidFileStore.GetTranslatedMessage: string;
begin
  Result := Format(Translate(Message), [ErrorMessageTranslation]);
end;

procedure ESkyInvalidFileStore.SetErrorMessageTranslation(const Value: string);
begin
  Params.SetValueForField('ErrorMessageTranslation', Value);
end;

{ ESkyInvalidCrcFileCannotBeExtracted }

constructor ESkyInvalidCrcFileCannotBeExtracted.Create(ARaisedBy: TObject; const ALocation,
  AFileName: string);
begin
  inherited Create(ARaisedBy, ALocation, tlInvalidCrcFileCannotBeExtracted);
  FileName := AFileName;
end;

function ESkyInvalidCrcFileCannotBeExtracted.GetFileName: string;
begin
  Result := Params.GetValueForField('FileName');
end;

function ESkyInvalidCrcFileCannotBeExtracted.GetTranslatedMessage: string;
begin
  Result := Format(Translate(Message), [FileName]);
end;

procedure ESkyInvalidCrcFileCannotBeExtracted.SetFileName(const Value: string);
begin
  Params.SetValueForField('FileName', Value);
end;

{ ESkyFileStoreIsInReadOnlyMode }

constructor ESkyFileStoreIsInReadOnlyMode.Create(ARaisedBy: TObject;
  const ALocation, AnActionTranslation, AFileName: string);
begin
  inherited Create(ARaisedBy, ALocation, tlFileStoreReadOnlyCannotExecute);
  FileName := AFileName;
  ActionTranslation := AnActionTranslation;
end;

function ESkyFileStoreIsInReadOnlyMode.GetActionTranslation: string;
begin
  Result := Params.GetValueForField('ActionTranslation');
end;

function ESkyFileStoreIsInReadOnlyMode.GetFileName: string;
begin
  Result := Params.GetValueForField('FileName');
end;

function ESkyFileStoreIsInReadOnlyMode.GetTranslatedMessage: string;
begin
  Result := Format(Translate(Message), [ActionTranslation, FileName]);
end;

procedure ESkyFileStoreIsInReadOnlyMode.SetActionTranslation(
  const Value: string);
begin
  Params.SetValueForField('ActionTranslation', Value);
end;

procedure ESkyFileStoreIsInReadOnlyMode.SetFileName(const Value: string);
begin
  Params.SetValueForField('FileName', Value);
end;

{ ESkyFileStoreTableDoesNotExist }

constructor ESkyFileStoreTableDoesNotExist.Create(ARaisedBy: TObject;
  const ALocation, ATableName: string);
begin
  inherited Create(ARaisedBy, ALocation, tlTableDoesNotExistInThePackage);
  TableName := ATableName;
end;

function ESkyFileStoreTableDoesNotExist.GetTranslatedMessage: string;
begin
  Result := Format(Translate(Message), [TableName]);
end;

function ESkyFileStoreTableDoesNotExist.GetTableName: string;
begin
  Result := Params.GetValueForField('TableName');
end;

procedure ESkyFileStoreTableDoesNotExist.SetTableName(const Value: string);
begin
  Params.SetValueForField('TableName', Value);
end;

{ ESkyListValueDoesNotExistError }

constructor ESkyListValueDoesNotExistError.Create(ARaisedBy: TObject;
  const ALocation: string; AValue: string);
begin
  inherited Create(ARaisedBy, ALocation, tlListValueDoesNotExist);
  Value := AValue;
end;

function ESkyListValueDoesNotExistError.GetTranslatedMessage: string;
begin
  Result := Format(Translate(Message), [Value]);
end;

function ESkyListValueDoesNotExistError.GetValue: string;
begin
  Result := Params.GetValueForField('Value');
end;

procedure ESkyListValueDoesNotExistError.SetValue(const Value: string);
begin
  Params.SetValueForField('Value', Value);
end;

{ ESkyDatabaseRecordDoesNotExist }

constructor ESkyDatabaseRecordDoesNotExist.Create(ARaisedBy: TObject; const ALocation, AEntityClass: string);
begin
  inherited Create(ARaisedBy, ALocation, tlDatabaseRecordDoesNotExist);
  EntityClass := AEntityClass;
end;

function ESkyDatabaseRecordDoesNotExist.GetEntityClass: string;
begin
  Result := Params.GetValueForField('EntityClass');
end;

function ESkyDatabaseRecordDoesNotExist.GetTranslatedMessage: string;
begin
  Result := Format(Translate(Message), [EntityClass]);
end;

procedure ESkyDatabaseRecordDoesNotExist.SetEntityClass(const Value: string);
begin
  Params.SetValueForField('EntityClass', Value);
end;

{ ESkyEntityPickerInvalidSelection }

constructor ESkyEntityPickerInvalidSelection.Create(ARaisedBy: TObject;
  const ALocation: string);
begin
  inherited Create(ARaisedBy, ALocation, tlSelectedObjectMissing);
end;

{ ESkyInvalidValueForField }

constructor ESkyInvalidValueForField.Create(ARaisedBy: TObject; const ALocation,
  AFieldName, AValidationText: string);
begin
  inherited Create(ARaisedBy, ALocation, tlInvalidValueForField);
  FieldName := AFieldName;
  ValidationText := AValidationText;
end;

function ESkyInvalidValueForField.GetFieldName: string;
begin
  Result := Params.GetValueForField('FieldName');
end;

function ESkyInvalidValueForField.GetTranslatedMessage: string;
begin
  Result := Format(Translate(Message), [FieldName]);
  if ValidationText <> '' then
    Result := Result + ReturnLf + Translate(ValidationText);
end;

function ESkyInvalidValueForField.GetValidationText: string;
begin
  Result := Params.GetValueForField('ValidationText');
end;


procedure ESkyInvalidValueForField.SetFieldName(const Value: string);
begin
  Params.SetValueForField('FieldName', Value);
end;

procedure ESkyInvalidValueForField.SetValidationText(const Value: string);
begin
  Params.SetValueForField('ValidationText', Value);
end;

{ ESkyFieldNameAllreadyExists }

constructor ESkyFieldNameAllreadyExists.Create(ARaisedBy: TObject; const ALocation,
  AFieldName: string);
begin
  inherited Create(ARaisedBy, ALocation, tlFieldNameAllreadyExists);
  FieldName := AFieldName;
end;

function ESkyFieldNameAllreadyExists.GetFieldName: string;
begin
  Result := Params.GetValueForField('FieldName');
end;

function ESkyFieldNameAllreadyExists.GetTranslatedMessage: string;
begin
  Result := Format(Translate(Message), [FieldName]);
end;

procedure ESkyFieldNameAllreadyExists.SetFieldName(const Value: string);
begin
  Params.SetValueForField('FieldName', Value);
end;

{ ESkyDupplicateUserName }

constructor ESkyDupplicateUserName.Create(ARaisedBy: TObject; const ALocation,
  AUserName: string);
begin
  inherited Create(ARaisedBy, ALocation, tlUserNameAllreadyExists);
  UserName := AUserName;
end;

function ESkyDupplicateUserName.GetUserName: string;
begin
  Result := Params.GetValueForField('UserName');
end;

function ESkyDupplicateUserName.GetTranslatedMessage: string;
begin
  Result := Format(Translate(Message), [UserName]);
end;

procedure ESkyDupplicateUserName.SetUserName(const Value: string);
begin
  Params.SetValueForField('UserName', Value);
end;

{ ESkyObjectNotFound }

constructor ESkyObjectNotFound.Create(ARaisedBy: TObject; const ALocation,
  ADataChanged, AValue: string);
begin
  inherited Create(ARaisedBy, ALocation, tlObjectNotFound);
  DataChanged := ADataChanged;
  DataValue  := AValue;
end;

function ESkyObjectNotFound.GetDataChanged: string;
begin
  Result := Params.GetValueForField('DataChanged');
end;

function ESkyObjectNotFound.GetTranslatedMessage: string;
begin
  Result := Format(Translate(Message), [Translate(DataChanged), DataValue]);
end;

function ESkyObjectNotFound.GetDataValue: string;
begin
  Result := Params.GetValueForField('DataValue');
end;

procedure ESkyObjectNotFound.SetDataChanged(const Value: string);
begin
  Params.SetValueForField('DataChanged', Value);
end;

procedure ESkyObjectNotFound.SetDataValue(const Value: string);
begin
  Params.SetValueForField('DataValue', Value);
end;

{ ESkyPathDoesNotExist }

constructor ESkyPathDoesNotExist.Create(ARaisedBy: TObject; const ALocation,
  APath: string);
begin
  inherited Create(ARaisedBy, ALocation, tlPathDoesNotExist);
  Path := APath;
end;

function ESkyPathDoesNotExist.GetPath: string;
begin
  Result := Params.GetValueForField('Path');
end;

function ESkyPathDoesNotExist.GetTranslatedMessage: string;
begin
  Result := Format(Translate(Message), [Path]);
end;

procedure ESkyPathDoesNotExist.SetPath(const Value: string);
begin
  Params.SetValueForField('Path', Value);
end;

{ ESkyFolderAllreadyExists }

constructor ESkyFolderAllreadyExists.Create(ARaisedBy: TObject; const ALocation,
  AFolder: string);
begin
  inherited Create(ARaisedBy, ALocation, tlFolderAllreadyExists);
  Folder := AFolder;
end;

function ESkyFolderAllreadyExists.GetFolder: string;
begin
  Result := Params.GetValueForField('Folder');
end;

function ESkyFolderAllreadyExists.GetTranslatedMessage: string;
begin
  Result := Format(Translate(Message), [Folder]);
end;

procedure ESkyFolderAllreadyExists.SetFolder(const Value: string);
begin
  Params.SetValueForField('Folder', Value);
end;

{ ESkyCouldNotCreateObject }

constructor ESkyCouldNotCreateObject.Create(ARaisedBy: TObject; const ALocation,
  AnObjectType, AnObjectValue: string);
begin
  inherited Create(ARaisedBy, ALocation, tlCouldNotCreateObject);
  ObjectType := AnObjectType;
  ObjectValue := AnObjectValue;
end;

function ESkyCouldNotCreateObject.GetObjectType: string;
begin
  Result := Params.GetValueForField('ObjectType');
end;

function ESkyCouldNotCreateObject.GetObjectValue: string;
begin
  Result := Params.GetValueForField('ObjectValue');
end;

function ESkyCouldNotCreateObject.GetTranslatedMessage: string;
begin
  Result := Format(Translate(Message), [Translate(ObjectType), ObjectValue]);
end;

procedure ESkyCouldNotCreateObject.SetObjectType(const Value: string);
begin
  Params.SetValueForField('ObjectType', Value);
end;

procedure ESkyCouldNotCreateObject.SetObjectValue(const Value: string);
begin
  Params.SetValueForField('ObjectValue', Value);
end;

{ ESkyUserDoesNotHaveTheRightsToAccess }

constructor ESkyUserDoesNotHaveTheRightsToAccess.Create(ARaisedBy: TObject;
  const ALocation, AnObjectValue: string);
begin
  inherited Create(ARaisedBy, ALocation, tlUserDoesNotHaveTheRightsToAccess);
  ObjectValue := AnObjectValue;
end;

function ESkyUserDoesNotHaveTheRightsToAccess.GetObjectValue: string;
begin
  Result := Params.GetValueForField('ObjectValue');
end;

function ESkyUserDoesNotHaveTheRightsToAccess.GetTranslatedMessage: string;
begin
  Result := Format(Translate(Message), [ObjectValue]);
end;

procedure ESkyUserDoesNotHaveTheRightsToAccess.SetObjectValue(
  const Value: string);
begin
  Params.SetValueForField('ObjectValue', Value);
end;

{ ESkyExtraCharactersInStream }

constructor ESkyExtraCharactersInStream.Create(ARaisedBy: TObject;
  const ALocation: string; ANumberOfChars: Int64);
begin
  inherited Create(ARaisedBy, ALocation, tlExtraCharactersInStream);
  NumberOfChars := ANumberOfChars;
end;

function ESkyExtraCharactersInStream.GetNumberOfChars: Int64;
begin
  Result := Params.GetValueForField('NumberOfChars');
end;

function ESkyExtraCharactersInStream.GetTranslatedMessage: string;
begin
  Result := Format(Translate(Message), [NumberOfChars]);
end;

procedure ESkyExtraCharactersInStream.SetNumberOfChars(const Value: Int64);
begin
  Params.SetValueForField('NumberOfChars', Value);
end;

{ ESkyInvalidFile }

constructor ESkyInvalidFile.Create(ARaisedBy: TObject; const ALocation: string;
  AFilename: string);
begin
  inherited Create(ARaisedBy, ALocation, tlInvalidFile);
  Filename := AFilename;
end;

function ESkyInvalidFile.GetFilename: string;
begin
  Result := Params.GetValueForField('Filename');
end;

function ESkyInvalidFile.GetTranslatedMessage: string;
begin
  Result := Format(Translate(Message), [Filename]);
end;

procedure ESkyInvalidFile.SetFilename(const Value: string);
begin
  Params.SetValueForField('Filename', Value);
end;

{ ESkyCustomError }

constructor ESkyCustomError.Create(ARaisedBy: TObject; const ALocation, AnErrorMessage: string);
begin
  inherited Create(ARaisedBy, ALocation, tlCustomError);
  ErrorMessage := AnErrorMessage;
end;

function ESkyCustomError.GetErrorMessage: string;
begin
  Result := Params.GetValueForField('ErrorMessage');
end;

function ESkyCustomError.GetTranslatedMessage: string;
begin
  Result := Format(Translate(Message), [ErrorMessage]);
end;

procedure ESkyCustomError.SetErrorMessage(const Value: string);
begin
  Params.SetValueForField('ErrorMessage', Value);
end;

{ ESkyInvalidClassType }

constructor ESkyInvalidClassType.Create(ARaisedBy: TObject; const ALocation, AExpected, AReceived: string);
begin
  inherited Create(ARaisedBy, ALocation, tlInvalidClassType);
  Expected := AExpected;
  Received := AReceived;
end;

function ESkyInvalidClassType.GetExpected: string;
begin
  Result := Params.GetValueForField('Expected');
end;

function ESkyInvalidClassType.GetReceived: string;
begin
  Result := Params.GetValueForField('Received');
end;

function ESkyInvalidClassType.GetTranslatedMessage: string;
begin
  Result := Format(Translate(Message), [Expected, Received]);
end;

procedure ESkyInvalidClassType.SetExpected(const Value: string);
begin
  Params.SetValueForField('Expected', Value);
end;

procedure ESkyInvalidClassType.SetReceived(const Value: string);
begin
  Params.SetValueForField('Received', Value);
end;

{ ESkyServerCommunicationError }

constructor ESkyServerCommunicationError.Create(ARaisedBy: TObject;
  const ALocation: string);
begin
  inherited Create(ARaisedBy, ALocation, tlErrorWhileCommunicatingWithServer);
end;

{ ESkyServerNotOnline }

constructor ESkyServerNotOnline.Create(ARaisedBy: TObject;
  const ALocation: string);
begin
  inherited Create(ARaisedBy, ALocation, tlServerNotOnline);
end;

{ ESkyNumberOfElementsDiffers }

constructor ESkyNumberOfElementsDiffers.Create(ARaisedBy: TObject;
  const ALocation, AFieldName, AObjectName, AObjectValue: string);
begin
  inherited Create(ARaisedBy, ALocation, tlNumberOfElementsDiffers);
  FieldName := AFieldName;
  ObjectName := AObjectName;
  ObjectValue := AObjectValue;
end;

function ESkyNumberOfElementsDiffers.GetFieldName: string;
begin
  Result := Params.GetValueForField('FieldName');
end;

function ESkyNumberOfElementsDiffers.GetObjectName: string;
begin
  Result := Params.GetValueForField('ObjectName');
end;

function ESkyNumberOfElementsDiffers.GetObjectValue: string;
begin
  Result := Params.GetValueForField('ObjectValue');
end;

function ESkyNumberOfElementsDiffers.GetTranslatedMessage: string;
begin
  Result := Format(Translate(Message), [Translate(FieldName), Translate(ObjectName), ObjectValue]);
end;

procedure ESkyNumberOfElementsDiffers.SetFieldName(const Value: string);
begin
  Params.SetValueForField('FieldName', Value);
end;

procedure ESkyNumberOfElementsDiffers.SetObjectName(const Value: string);
begin
  Params.SetValueForField('ObjectName', Value);
end;

procedure ESkyNumberOfElementsDiffers.SetObjectValue(const Value: string);
begin
  Params.SetValueForField('ObjectValue', Value);
end;

{ ESkyInvalidRequest }

constructor ESkyInvalidRequest.Create(ARaisedBy: TObject;
  const ALocation, ARequest: string);
begin
  inherited Create(ARaisedBy, ALocation, tlInvalidRequest);
  Request := ARequest;
end;

function ESkyInvalidRequest.GetRequest: string;
begin
  Result := Params.GetValueForField('Request');
end;

function ESkyInvalidRequest.GetTranslatedMessage: string;
begin
  Result := Format(Translate(Message), [Request]);
end;

procedure ESkyInvalidRequest.SetRequest(const Value: string);
begin
  Params.SetValueForField('Request', Value);
end;

{ ESkyRequiredXmlNodeNotFound }

constructor ESkyRequiredXmlNodeNotFound.Create(const ARaisedBy, ALocation,
  AParentNode, AChildNode: string);
begin
  inherited Create(ARaisedBy, ALocation, tlRequiredXmlNodeNotFound);
  ParentNode := AParentNode;
  ChildNode := AChildNode;
end;

function ESkyRequiredXmlNodeNotFound.GetChildNode: string;
begin
  Result := Params.GetValueForField('ChildNode');
end;

function ESkyRequiredXmlNodeNotFound.GetParentNode: string;
begin
  Result := Params.GetValueForField('ParentNode');
end;

function ESkyRequiredXmlNodeNotFound.GetTranslatedMessage: string;
begin
  Result := Format(Translate(Message), [ParentNode, ChildNode]);
end;

procedure ESkyRequiredXmlNodeNotFound.SetChildNode(const Value: string);
begin
  Params.SetValueForField('ChildNode', Value);
end;

procedure ESkyRequiredXmlNodeNotFound.SetParentNode(const Value: string);
begin
  Params.SetValueForField('ParentNode', Value);
end;

{ ESkyInvalidUserNameError }

constructor ESkyInvalidUserNameError.Create(ARaisedBy: TObject;
  const ALocation: string);
begin
  inherited Create(ARaisedBy, ALocation, tlInvalidUserNameOrPassword);
end;

{ ESkyAuthenticationMissing }

constructor ESkyAuthenticationMissing.Create(ARaisedBy: TObject;
  const ALocation: string);
begin
  inherited Create(ARaisedBy, ALocation, tlAuthenticationMissing);
end;

{ ESkyServerUnknownException }

constructor ESkyServerUnknownException.Create(ARaisedBy: TObject; const ALocation,
  AnErrorMessage: string);
begin
  inherited Create(ARaisedBy, ALocation, tlUnknownException);
  ErrorMessage := AnErrorMessage;
end;

function ESkyServerUnknownException.GetErrorMessage: string;
begin
  Result := Params.GetValueForField('ErrorMessage');
end;

function ESkyServerUnknownException.GetTranslatedMessage: string;
begin
  Result := Format(Translate(Message), [ErrorMessage]);
end;

procedure ESkyServerUnknownException.SetErrorMessage(const Value: string);
begin
  Params.SetValueForField('ErrorMessage', Value);
end;

{ ESkyInvalidSession }

constructor ESkyInvalidSession.Create(ARaisedBy: TObject; const ALocation: string);
begin
  inherited Create(ARaisedBy, ALocation, tlInvalidSession);
end;

{ ESkyInvalidPropertyType }

constructor ESkyInvalidPropertyType.Create(ARaisedBy: TObject;
  const ALocation, APropertyName: string);
begin
  inherited Create(ARaisedBy, ALocation, tlInvalidPropertyType);
  PropertyName := APropertyName;
end;

function ESkyInvalidPropertyType.GetPropertyName: string;
begin
  Result := Params.GetValueForField('PropertyName');
end;

function ESkyInvalidPropertyType.GetTranslatedMessage: string;
begin
  Result := Format(Translate(Message), [PropertyName]);
end;

procedure ESkyInvalidPropertyType.SetPropertyName(const Value: string);
begin
  Params.SetValueForField('PropertyName', Value);
end;

{ ESkyErrorLoadingXML }

constructor ESkyErrorLoadingXML.Create(ARaisedBy: TObject; const ALocation,
  AErrorString: string);
begin
  inherited Create(ARaisedBy, ALocation, tlErrorLoadingXML);
  ErrorString := AErrorString;
end;

function ESkyErrorLoadingXML.GetErrorString: string;
begin
  Result := Params.GetValueForField('ErrorString');
end;

function ESkyErrorLoadingXML.GetTranslatedMessage: string;
begin
  Result := Format(Translate(Message), [ErrorString]);
end;

procedure ESkyErrorLoadingXML.SetErrorString(const Value: string);
begin
  Params.SetValueForField('ErrorString', Value);
end;

{ ESkyComplexGenericEntitiesNotSuported }

constructor ESkyComplexGenericEntitiesNotSuported.Create(ARaisedBy: TObject;
  const ALocation, APropertyName: string);
begin
  inherited Create(ARaisedBy, ALocation, tlComplexGenericEntitiesNotSuported);
  PropertyName := APropertyName;
end;

function ESkyComplexGenericEntitiesNotSuported.GetPropertyName: string;
begin
  Result := Params.GetValueForField('PropertyName');
end;

function ESkyComplexGenericEntitiesNotSuported.GetTranslatedMessage: string;
begin
  Result := Format(Translate(Message), [PropertyName]);
end;

procedure ESkyComplexGenericEntitiesNotSuported.SetPropertyName(
  const Value: string);
begin
  Params.SetValueForField('PropertyName', Value);
end;

{ ESkyInvalidStream }

constructor ESkyInvalidStream.Create(ARaisedBy: TObject; const ALocation, AReason: string);
begin
  inherited Create(ARaisedBy, ALocation, tlInvalidStreamCannotDecode);
  Reason := AReason;
end;

function ESkyInvalidStream.GetReason: string;
begin
  Result := Params.GetValueForField('Reason');
end;

function ESkyInvalidStream.GetTranslatedMessage: string;
begin
  Result := Format(Translate(Message), [Reason]);
end;

procedure ESkyInvalidStream.SetReason(const Value: string);
begin
  Params.SetValueForField('Reason', Value);
end;

{ ESkyErrorLoadingJson }

constructor ESkyErrorLoadingJson.Create(ARaisedBy: TObject; const ALocation,
  AErrorString: string);
begin
  inherited Create(ARaisedBy, ALocation, tlErrorLoadingJson);
  ErrorString := AErrorString;
end;

function ESkyErrorLoadingJson.GetErrorString: string;
begin
  Result := Params.GetValueForField('ErrorString');
end;

function ESkyErrorLoadingJson.GetTranslatedMessage: string;
begin
  Result := Format(Translate(Message), [ErrorString]);
end;

procedure ESkyErrorLoadingJson.SetErrorString(const Value: string);
begin
  Params.SetValueForField('ErrorString', Value);
end;

{ ESkyInvalidUserOrPassword }

constructor ESkyInvalidUserOrPassword.Create(ARaisedBy: TObject; const ALocation: string);
begin
  inherited Create(ARaisedBy, ALocation, tlInvalidUserNameOrPassword);
end;

{ ESkyDuplicateKey }

constructor ESkyDuplicateKey.Create(ARaisedBy: TObject;
  const ALocation, AKeyName: string);
begin
  inherited Create(ARaisedBy, ALocation, tlDuplicateKey);
  KeyName := AKeyName;
end;

function ESkyDuplicateKey.GetKeyName: string;
begin
  Result := Params.GetValueForField('KeyName');
end;

function ESkyDuplicateKey.GetTranslatedMessage: string;
begin
  Result := Format(Translate(Message), [KeyName]);
end;

procedure ESkyDuplicateKey.SetKeyName(const Value: string);
begin
  Params.SetValueForField('KeyName', Value);
end;

{ ESkyRecordIsUsed }

constructor ESkyRecordIsUsed.Create(ARaisedBy: TObject;
  const ALocation: string);
begin
  inherited Create(ARaisedBy, ALocation, tlRecordIsUsed);
end;

{ ESkyDataNoLongerValid }

constructor ESkyDataNoLongerValid.Create(ARaisedBy: TObject; const ALocation,
  AData: string);
begin
  inherited Create(ARaisedBy, ALocation, tlDataNoLongerValid);
  Data := AData;
end;

function ESkyDataNoLongerValid.GetData: string;
begin
  Result := Params.GetValueForField('Data');
end;

function ESkyDataNoLongerValid.GetTranslatedMessage: string;
begin
  Result := Format(Translate(Message), [Data]);
end;

procedure ESkyDataNoLongerValid.SetData(const Value: string);
begin
  Params.SetValueForField('Data', Value);
end;

{ ESkyBeginGreaterThenEndDate }

constructor ESkyBeginGreaterThenEndDate.Create(ARaisedBy: TObject;
  const ALocation: string);
begin
  inherited Create(ARaisedBy, ALocation, tlStartDateGreater);
end;

{ ESkyInvalidInterval }

constructor ESkyInvalidInterval.Create(ARaisedBy: TObject; const ALocation: string);
begin
  inherited Create(ARaisedBy, ALocation, tlInvalidInterval);
end;

{ ESkyMainSessionNotFound }

constructor ESkyMainSessionNotFound.Create(ARaisedBy: TObject;
  const ALocation: string);
begin
  inherited Create(ARaisedBy, ALocation, tlMainSessionNotFound);
end;

{ ESkyCannotDeleteFile }

constructor ESkyCannotDeleteFile.Create(ARaisedBy: TObject; const ALocation: string; AFilename: string);
begin
  inherited Create(ARaisedBy, ALocation, tlCannotDeleteFile);
  Filename := AFilename;
end;

function ESkyCannotDeleteFile.GetFilename: string;
begin
  Result := Params.GetValueForField('Filename');
end;

function ESkyCannotDeleteFile.GetTranslatedMessage: string;
begin
  Result := Format(Translate(Message), [Filename]);
end;

procedure ESkyCannotDeleteFile.SetFilename(const Value: string);
begin
  Params.SetValueForField('Filename', Value);
end;

{ ESkyDatabaseReferenceConstraint }

constructor ESkyDatabaseReferenceConstraint.Create(ARaisedBy: TObject; const ALocation, AnAction, ATable: string);
begin
  inherited Create(ARaisedBy, ALocation, tlDatabaseReferenceConstraint);
  Action := AnAction;
  Table := ATable;
end;

function ESkyDatabaseReferenceConstraint.GetAction: string;
begin
  Result := Params.GetValueForField('Action');
end;

function ESkyDatabaseReferenceConstraint.GetTable: string;
begin
  Result := Params.GetValueForField('Table');
end;

function ESkyDatabaseReferenceConstraint.GetTranslatedMessage: string;
begin
  Result := Format(Translate(Message), [Action, Table]);
end;

procedure ESkyDatabaseReferenceConstraint.SetAction(const Value: string);
begin
  Params.SetValueForField('Action', Value);
end;

procedure ESkyDatabaseReferenceConstraint.SetTable(const Value: string);
begin
  Params.SetValueForField('Table', Value);
end;

initialization
  ESkyException.RegisterClasses([
    ESkyAuthenticationMissing,
    ESkyBeginGreaterThenEndDate,
    ESkyClassNotRegistered,
    ESkyCouldNotCreateObject,
    ESkyCustomError,
    ESkyDatabaseRecordDoesNotExist,
    ESkyDatabaseReferenceConstraint,
    ESkyDataNoLongerValid,
    ESkyDuplicateKey,
    ESkyDupplicateUserName,
    ESkyEntityFieldNameTokenNotFound,
    ESkyErrorLoadingJson,
    ESkyErrorLoadingXML,
    ESkyExtraCharactersInStream,
    ESkyFieldDoesNotExist,
    ESkyFieldNameAllreadyExists,
    ESkyFileStoreFileDoesNotExist,
    ESkyFileStoreIsInReadOnlyMode,
    ESkyFileStoreTableDoesNotExist,
    ESkyFolderAllreadyExists,
    ESkyInvalidClassType,
    ESkyInvalidCrcFileCannotBeExtracted,
    ESkyInvalidFile,
    ESkyInvalidFileStore,
    ESkyInvalidInterval,
    ESkyInvalidPackageFile,
    ESkyInvalidPropertyType,
    ESkyInvalidRequest,
    ESkyInvalidSession,
    ESkyInvalidStream,
    ESkyInvalidUserOrPassword,
    ESkyInvalidValueForField,
    ESkyListCapacityError,
    ESkyListIndexError,
    ESkyListParameterCountMismatch,
    ESkyListValueDoesNotExistError,
    ESkyMainSessionNotFound,
    ESkyNoTranslationsError,
    ESkyNumberOfElementsDiffers,
    ESkyObjectNotFound,
    ESkyPathDoesNotExist,
    ESkyRecordIsUsed,
    ESkyRequiredXmlNodeNotFound,
    ESkyServerCommunicationError,
    ESkyServerNotOnline,
    ESkyServerUnknownException,
    ESkyUserDoesNotHaveTheRightsToAccess
  ]);

end.
