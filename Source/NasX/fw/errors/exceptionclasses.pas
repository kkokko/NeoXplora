unit ExceptionClasses;

{$mode objfpc}{$H+}

interface

uses
  SkyException;

type
  // Warning message: Properties don't need to be publised because they are
  // saved in Params in OnCreate, and loaded from Params in LoadFromStream

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
  {$Region 'ESkyListParameterCountMismatch'}
    ESkyListParameterCountMismatch = class(ESkyException)
    public
      constructor Create(ARaisedBy: TObject; const ALocation: string); reintroduce;
    end; // 'Parameter count mismatch'

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

implementation

uses
  FWTranslations, sysutils, TypesConsts;

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

{ ESkyListParameterCountMismatch }

constructor ESkyListParameterCountMismatch.Create(ARaisedBy: TObject;
  const ALocation: string);
begin
  inherited Create(ARaisedBy, ALocation, tlParameterCountMismatch);
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



initialization
  ESkyException.RegisterClasses([
    ESkyClassNotRegistered,
    ESkyEntityFieldNameTokenNotFound,
    ESkyErrorLoadingJson,
    ESkyFieldDoesNotExist,
    ESkyInvalidClassType,
    ESkyInvalidPropertyType,
    ESkyInvalidValueForField,
    ESkyListCapacityError,
    ESkyListIndexError,
    ESkyListParameterCountMismatch,
    ESkyListValueDoesNotExistError
  ]);

end.

