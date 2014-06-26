unit TypedEntity;

interface

uses
  GenericEntity;

type
  TTypedEntity = class(TGenericEntity)
  public
    function GetBooleanProperty(const APropertyName: string): Boolean; overload;
    function GetBooleanProperty(const APropertyName: string; ADefaultValue: Boolean): Boolean; overload;
    procedure SetBooleanProperty(const APropertyName: string; AValue: Boolean);

    function GetStringProperty(const APropertyName: string): string; overload;
    function GetStringProperty(const APropertyName, ADefaultValue: string): string; overload;
    procedure SetStringProperty(const APropertyName: string; const AValue: string);

    function GetIntegerProperty(const APropertyName: string): Integer; overload;
    function GetIntegerProperty(const APropertyName: string; ADefaultValue: integer): Integer; overload;
    procedure SetIntegerProperty(const APropertyName: string; AValue: Integer);

    function GetFloatProperty(const APropertyName: string): Double; overload;
    function GetFloatProperty(const APropertyName: string; ADefaultValue: Double): Double; overload;
    procedure SetFloatProperty(const APropertyName: string; AValue: Double);
  end;

implementation

uses
  Variants;

{ TTypedEntity }

function TTypedEntity.GetBooleanProperty(const APropertyName: string): Boolean;
begin
  Result := GetValueForField(APropertyName);
end;

function TTypedEntity.GetBooleanProperty(const APropertyName: string; ADefaultValue: Boolean): Boolean;
var
  TheResult: Variant;
begin
  TheResult := GetValueForField(APropertyName);
  if TheResult <> Unassigned then
    try
      Result := TheResult;
      Exit;
    except
    end;
  Result := ADefaultValue;
end;

function TTypedEntity.GetFloatProperty(const APropertyName: string): Double;
begin
  Result := GetValueForField(APropertyName);
end;

function TTypedEntity.GetFloatProperty(const APropertyName: string; ADefaultValue: Double): Double;
var
  TheResult: Variant;
begin
  TheResult := GetValueForField(APropertyName);
  if TheResult <> Unassigned then
    try
      Result := TheResult;
      Exit;
    except
    end;
  Result := ADefaultValue;
end;

function TTypedEntity.GetIntegerProperty(const APropertyName: string): Integer;
begin
  Result := GetValueForField(APropertyName);
end;

function TTypedEntity.GetIntegerProperty(const APropertyName: string; ADefaultValue: Integer): Integer;
var
  TheResult: Variant;
begin
  TheResult := GetValueForField(APropertyName);
  if TheResult <> Unassigned then
    try
      Result := TheResult;
      Exit;
    except
    end;
  Result := ADefaultValue;
end;

function TTypedEntity.GetStringProperty(const APropertyName: string): string;
begin
  Result := GetValueForField(APropertyName);
end;

function TTypedEntity.GetStringProperty(const APropertyName, ADefaultValue: string): string;
var
  TheResult: Variant;
begin
  TheResult := GetValueForField(APropertyName);
  if TheResult <> Unassigned then
    try
      Result := TheResult;
      Exit;
    except
    end;
  Result := ADefaultValue;
end;

procedure TTypedEntity.SetBooleanProperty(const APropertyName: string; AValue: Boolean);
begin
  SetValueForField(APropertyName, AValue);
end;

procedure TTypedEntity.SetFloatProperty(const APropertyName: string; AValue: Double);
begin
  SetValueForField(APropertyName, AValue);
end;

procedure TTypedEntity.SetIntegerProperty(const APropertyName: string; AValue: Integer);
begin
  SetValueForField(APropertyName, AValue);
end;

procedure TTypedEntity.SetStringProperty(const APropertyName, AValue: string);
begin
  SetValueForField(APropertyName, AValue);
end;

end.
