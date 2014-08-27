unit RepDecoder;

interface

uses
  RepRecord, RepGroup, StringExpression, RepObjectBase, RepEntity, RepPropertyKey, RepPropertyValue;

type
  TRepDecoder = class
  private
    FRepRecord: TRepRecord;
    FRepString: PStringExpression;

    procedure ReadExpressions(AParent: TRepObjectBase);
    procedure ReadExpression(AParent: TRepObjectBase);
    function ReadEntity: TRepEntity;
    procedure ReadGroupChildren(AGroup: TRepGroup);
    function ReadPropertyName(AParent: TRepObjectBase; AKeyPropertyType: TRepPropertyKey.TKeyPropertyType): TRepPropertyKey;
    function ReadOperatorType: TRepPropertyValue.TOperatorType;
    function ReadPropertyValue(AnAttribute: TRepPropertyKey; AnOperatorType: TRepPropertyValue.TOperatorType): TRepPropertyValue;
    function ReadIdentifier: string;
    function ReadLink(AnAttribute: TRepPropertyKey; AnOperatorType: TRepPropertyValue.TOperatorType): TRepPropertyValue;

    function GetCurrentChar: Char; inline;
    procedure ParseSpaces; inline;

    procedure SetRepString(const ARep: string);
  public
    constructor Create;
    destructor Destroy; override;

    class function DecodeRep(const ARep: string): TRepRecord;
  end;

implementation

uses
  AppExceptionClasses, SysUtils;

{ TRepDecoder }

constructor TRepDecoder.Create;
begin
  FRepRecord := TRepRecord.Create;
  FRepString := nil;
end;

destructor TRepDecoder.Destroy;
begin
  FRepRecord.Free;
  TStringExpression.DisposePtr(FRepString);
end;

procedure TRepDecoder.ParseSpaces;
begin
  while GetCurrentChar = ' ' do
    Inc(FRepString^.Position);
end;

procedure TRepDecoder.ReadExpressions(AParent: TRepObjectBase);
begin
  ParseSpaces;
  while FRepString^.InString do
  begin
    if (FRepString^.Position^ = '(')
      or (
        (AParent = nil)
        and (FRepString^.Position^ =  ')')
      )
    then
      raise EAppRepDecoderException.Create(Self, 'ReadExpressions', 'Unexpected ' + FRepString^.Position^ + ' found', FRepString.Index);
    ReadExpression(AParent);
    if GetCurrentChar = ',' then
    begin
      Inc(FRepString^.Position);
      ParseSpaces;
    end;
    if GetCurrentChar = ')' then
    begin
      Inc(FRepString^.Position);
      Break;
    end;
  end;
end;

procedure TRepDecoder.ReadGroupChildren(AGroup: TRepGroup);
var
  TheEntity: TRepEntity;
begin
  repeat
    if GetCurrentChar = '[' then
      Inc(FRepString^.Position);
    ParseSpaces;
    TheEntity := ReadEntity;
    AGroup.Members.Add(TheEntity);
    ParseSpaces;
    if GetCurrentChar <> '+' then
      Exit;
    Inc(FRepString^.Position);
    ParseSpaces;
  until False;
end;

function TRepDecoder.ReadPropertyName(AParent: TRepObjectBase; AKeyPropertyType: TRepPropertyKey.TKeyPropertyType): TRepPropertyKey;
var
  TheName: string;
begin
  TheName := ReadIdentifier;
  Result := AParent.GetOrCreateKid(TheName, AKeyPropertyType) as TRepPropertyKey;
  if GetCurrentChar = '(' then
  begin
    Inc(FRepString^.Position);
    ReadExpressions(Result);
  end;
end;

function TRepDecoder.ReadPropertyValue(AnAttribute: TRepPropertyKey; AnOperatorType: TRepPropertyValue.TOperatorType): TRepPropertyValue;
var
  TheIsLink: Boolean;
  TheValue: string;
begin
  TheIsLink := GetCurrentChar = '[';
  if TheIsLink then
    Result := ReadLink(AnAttribute, AnOperatorType)
  else
  begin
    TheValue := ReadIdentifier;
    Result := AnAttribute.AddLiteralValue(AnOperatorType, TheValue);
  end;
  if GetCurrentChar = '(' then
  begin
    Inc(FRepString^.Position);
    ReadExpressions(Result);
  end;
end;

function TRepDecoder.ReadIdentifier: string;
var
  TheIsQuoted: Boolean;
  TheLastChar: Char;
  TheStart: PChar;
begin
  TheIsQuoted := GetCurrentChar = '"';
  if TheIsQuoted then
  begin
    Inc(FRepString^.Position);
    TheStart := FRepString^.Position;
    TheLastChar := #0;
    //Read quoted string;
    while FRepString^.InString do
    begin
      if (TheLastChar <> '\') and (FRepString^.Position^ = '"') then
        Break;
      TheLastChar := FRepString^.Position^;
      Inc(FRepString^.Position);
    end;
    if not FRepString^.InString then
    begin
      FRepString^.Position := TheStart;
      raise EAppRepDecoderException.Create(Self, 'ReadIdentifier', 'Unterminated string', FRepString.Index);
    end;
  end else
  begin
    TheStart := FRepString^.Position;
    while not CharInSet(FRepString^.Position^, [#0, '=', '!', '>', '<', '(', ')', ',', '{', ']', '.', ':', '+']) do
      Inc(FRepString^.Position);
  end;
  Result := TrimRight(TStringExpression.GetValue(TheStart, FRepString^.Position));
  if Result = '' then
    raise EAppRepDecoderException.Create(Self, 'ReadIdentifier', 'Invalid identifier', FRepString.Index);
  if TheIsQuoted then
  begin
    Inc(FRepString^.Position);
    ParseSpaces;
  end;
end;

function TRepDecoder.ReadLink(AnAttribute: TRepPropertyKey; AnOperatorType: TRepPropertyValue.TOperatorType): TRepPropertyValue;
var
  TheObject, TheNewObject: TRepObjectBase;
  TheKeyPropertyType: TRepPropertyKey.TKeyPropertyType;
  TheKeyName: string;
begin
  TheKeyPropertyType := ptAttribute; // to prevent warning..
  Inc(FRepString^.Position);
  TheObject := ReadEntity;
  repeat
    case GetCurrentChar of
      '.':
        TheKeyPropertyType := ptAttribute;
      ':':
        TheKeyPropertyType := ptEvent;
    else
      Break;
    end;
    Inc(FRepString^.Position);
    TheKeyName := ReadIdentifier;
    TheNewObject := TheObject.Kids.ObjectOfValueDefault[TheKeyName, nil] as TRepObjectBase;
    if TheNewObject = nil then
    begin
      TheNewObject := TRepPropertyKey.Create(TheObject, TheKeyPropertyType, TheKeyName);
      TheObject.Kids.AddObject(TheKeyName, TheNewObject);
    end;
    TheObject := TheNewObject;
  until 1 = 0;
  if TheObject is TRepEntity then
    Result := TRepPropertyValue.Create(AnAttribute, AnOperatorType, ltEntity, TheObject)
  else if TheKeyPropertyType = ptAttribute then
    Result := TRepPropertyValue.Create(AnAttribute, AnOperatorType, ltAttrKey, TheObject)
  else // ptEvent
    Result := TRepPropertyValue.Create(AnAttribute, AnOperatorType, ltEventKey, TheObject);
end;

function TRepDecoder.ReadOperatorType: TRepPropertyValue.TOperatorType;
begin
  case GetCurrentChar of
    '=':
    begin
      Result := otEquals;
      Inc(FRepString^.Position);
    end;
    '!': begin
      Inc(FRepString^.Position);
      if GetCurrentChar <> '=' then
        raise EAppRepDecoderException.Create(Self, 'ReadOperatorType', 'Invalid Operator', FRepString.Index - 1);
      Result := otDiffers;
      Inc(FRepString^.Position);
    end;
    '<': begin
      Inc(FRepString^.Position);
      if GetCurrentChar <> '=' then
        Result := otSmaller
      else
      begin
        Result := otSmallerOrEqual;
        Inc(FRepString^.Position);
      end;
    end;
    '>': begin
      Inc(FRepString^.Position);
      if GetCurrentChar <> '=' then
        Result := otGreater
      else
      begin
        Result := otGreaterOrEqual;
        Inc(FRepString^.Position);
      end;
    end;
    else
      Result := otNone;
  end;
  ParseSpaces;
end;

function TRepDecoder.ReadEntity: TRepEntity;
var
  TheChar: Char;
  TheEntityType: Char;
  TheNumber: Integer;
  TheNumberString: string;
begin
  TheEntityType := LowerCase(string(GetCurrentChar))[1];
  if not CharInSet(TheEntityType, ['p', 'o', 'g']) then
    raise EAppRepDecoderException.Create(Self, 'ReadEntity', 'Unknown entity type: ' + TheEntityType, FRepString^.Index);
  Inc(FRepString^.Position);
  TheNumberString := '';
  repeat
    TheChar := GetCurrentChar;
    if not CharInSet(TheChar, ['0'..'9']) then
      Break;
    TheNumberString := TheNumberString + TheChar;
    Inc(FRepString^.Position);
  until False;
  TheNumber := StrToIntDef(TheNumberString, 0);
  if (TheNumberString = '') or (TheNumber = 0) then
    raise EAppRepDecoderException.Create(Self, 'ReadEntity', 'Invalid entity number', FRepString^.Index);
  Result := FRepRecord.GetOrCreateEntity(TheEntityType, TheNumber);
  ParseSpaces;
  if GetCurrentChar = ']' then
  begin
    Inc(FRepString^.Position);
    ParseSpaces;
  end;
  if GetCurrentChar <> '(' then
    Exit;
  Inc(FRepString^.Position);
  ReadExpressions(Result);
end;

procedure TRepDecoder.ReadExpression(AParent: TRepObjectBase);
var
  TheAttribute: TRepPropertyKey;
  TheEntity: TRepEntity;
  TheEvent: TRepPropertyKey;
  TheMoreValues: Boolean;
  TheOperator: TRepPropertyValue.TOperatorType;
begin
  if (AParent = nil) then
  begin
    ParseSpaces;
    TheEntity := ReadEntity; // read P1123(asd)
    ReadExpression(TheEntity);
    Exit;
  end;
  ParseSpaces;
  case GetCurrentChar of
    '[':
    begin
      if (not (AParent is TRepEntity)) or (not ((AParent as TRepEntity).EntityType = etGroup)) then
        raise EAppRepDecoderException.Create(Self, 'ReadExpression', 'Expected operator found entity', FRepString^.Index);
      ReadGroupChildren(AParent as TRepGroup);
      Exit;
    end;
    '.':
    begin
      Inc(FRepString^.Position);
      TheAttribute := ReadPropertyName(AParent, ptAttribute); // .color(.type=asd)
      ParseSpaces;
      TheOperator := ReadOperatorType;
      if TheOperator = otNone then
        Exit;
      repeat
        // check if the attribute has an Operator + value
        // and add them
        ReadPropertyValue(TheAttribute, TheOperator); // = red(.type=dark)
        TheMoreValues := GetCurrentChar = '+';
        if TheMoreValues then
        begin
          Inc(FRepString^.Position);
          ParseSpaces;
        end;
      until not TheMoreValues;
      Exit;
    end;
    ':':
    begin
      Inc(FRepString^.Position);
      TheEvent := ReadPropertyName(AParent, ptEvent); // :runs(.to=home)
      TheOperator := ReadOperatorType;
      if TheOperator = otNone then
        Exit;
      repeat
        // check if the Event has an Operator + value
        // and add them
        ReadPropertyValue(TheEvent, TheOperator); // = fast(.when=now, :keeping speed < 30 kph)
        TheMoreValues := GetCurrentChar = '+';
        if TheMoreValues then
        begin
          Inc(FRepString^.Position);
          ParseSpaces;
        end;
      until not TheMoreValues;
      Exit;
    end;
    #0, ',':
      Exit;
    else
      raise EAppRepDecoderException.Create(Self, 'ReadExpression', 'Expected attribute/event', FRepString^.Index);
  end;
end;

function TRepDecoder.GetCurrentChar: Char;
begin
  if FRepString^.InString then
    Result := FRepString^.Position^
  else
    Result := #0;
end;

class function TRepDecoder.DecodeRep(const ARep: string): TRepRecord;
var
  TheInstance: TRepDecoder;
begin
  TheInstance := TRepDecoder.Create;
  try
    TheInstance.SetRepString(ARep);
    TheInstance.ReadExpressions(nil);
    Result := TheInstance.FRepRecord.CreateACopy as TRepRecord;
  finally
    TheInstance.Free;
  end;
end;

procedure TRepDecoder.SetRepString(const ARep: string);
begin
  TStringExpression.DisposePtr(FRepString);
  FRepString := TStringExpression.SetString(ARep);
end;

end.
