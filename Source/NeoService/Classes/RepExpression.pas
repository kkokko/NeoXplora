unit RepExpression;

interface

uses
  StringExpression;

type
  TRepExpression = record
  private
    function ReadEntityName(ASource: PStringExpression): Boolean;
    function ReadEntityProperties(ASource: PStringExpression): Boolean;
    function ReadOperationKind(ASource: PStringExpression): Boolean;
    function ReadOperationName(ASource: PStringExpression): Boolean;
    function ReadOperationProperties(ASource: PStringExpression): Boolean;
    function ReadOperand(ASource: PStringExpression): Boolean;
    function ReadValue(ASource: PStringExpression): Boolean;
    function ReadValueProperties(ASource: PStringExpression): Boolean;

    function ReadProperties(AParent: TStringExpression): Boolean;
  public
    LastError: string[255];

    // pointers
    Entity: PStringExpression;
    OperationNames: PStringExpression;
    OperationProperties: PStringExpression;
    Value: PStringExpression;
    ValueProperties: PStringExpression;

    // data
    EntityKind: Char;
    OperationKind: Char;
    Operand: string[2];

    class function Initialize: TRepExpression; static;
    procedure Finalize;

    function ReadExpression(ASource: PStringExpression): Boolean;
  end;

implementation

uses
  SysUtils;

{ TRepExpression }

procedure TRepExpression.Finalize;
begin
  if Entity <> nil then
    TStringExpression.DisposePtr(Entity);
  if OperationNames <> nil then
    TStringExpression.DisposePtr(OperationNames);
  if OperationProperties <> nil then
    TStringExpression.DisposePtr(OperationProperties);
  if Value <> nil then
    TStringExpression.DisposePtr(Value);
  if ValueProperties <> nil then
    TStringExpression.DisposePtr(ValueProperties);
end;

class function TRepExpression.Initialize: TRepExpression;
begin
  Result.Entity := nil;
  Result.OperationNames := nil;
  Result.Value := nil;
end;

function TRepExpression.ReadEntityName(ASource: PStringExpression): Boolean;
var
  TheStart: PChar;
begin
  while (ASource^.Position < ASource^.Finish) and (ASource^.Position^ = ' ') do
    Inc(ASource^.Position);
  if ASource^.Position >= ASource^.Finish then
    Exit(True);
  TheStart := ASource^.Position;
  EntityKind := UpCase(TheStart[0]);
  if not CharInSet(EntityKind, ['P', 'O', 'L', 'G']) then
  begin
    LastError := ShortString('Invalid entity kind: ' + EntityKind);
    Exit(False);
  end;
  Inc(ASource^.Position);
  while (ASource^.Position < ASource^.Finish) and CharInSet(ASource^.Position^, ['0'..'9']) do
    Inc(ASource^.Position);
  if ASource^.Position - TheStart < 2 then
  begin
    LastError := ShortString('Incomplete expression: ' + EntityKind);
    Exit(False);
  end;
  Entity := TStringExpression.CreateRecord(TheStart, ASource^.Position);
  Result := True;
end;

function TRepExpression.ReadProperties(AParent: TStringExpression): Boolean;
begin

end;

function TRepExpression.ReadEntityProperties(ASource: PStringExpression): Boolean;
var
  TheStart: PChar;
  TheLevel: Integer;
  TheLastProperty: TStringExpression;
begin
  while (ASource^.Position < ASource^.Finish) and (ASource^.Position^ = ' ') do
    Inc(ASource^.Position);
  if (ASource^.Position >= ASource^.Finish) or (ASource^.Position^ <> '(') then
    Exit(False);
  Inc(ASource^.Position);

{  EntityProperties := TStringExpression.CreateRecord(ASource^.Position, ASource^.Finish);
  ReadProperties(EntityProperties, EntityKind = 'G', True, True);

  while (ASource^.Position < ASource^.Finish) and (ASource^.Position^ = ' ') do
    Inc(ASource^.Position);

  TheLastProperty := nil;
  while (ASource^.Position < ASource^.Finish) and (ASource^.Position^ <> ')') do


  TheStart := ASource^.Position;
  TheLevel := 1;
  while (ASource^.Position < ASource^.Finish) and (TheLevel > 0) do
  begin
    if ASource^.Position^ = '(' then
      Inc(TheLevel);
    if ASource^.Position^ = ')' then
      Dec(TheLevel);
    Inc(ASource^.Position);
  end;
  if TheLevel <> 0 then
  begin
    ASource^.Position := TheStart;
    LastError := 'Missing )';
    Exit(False);
  end;
                }
  Result := True;
end;

function TRepExpression.ReadOperationKind(ASource: PStringExpression): Boolean;
var
  TheStart: PChar;
begin
  OperationKind := #0;
  while (ASource^.Position < ASource^.Finish) and (ASource^.Position^ = ' ') do
    Inc(ASource^.Position);
  if ASource^.Position >= ASource^.Finish then
    Exit(True);
  TheStart := ASource^.Position;
  OperationKind := TheStart[0];
  if not CharInSet(OperationKind, ['.', ':']) then
  begin
    LastError := ShortString('Invalid operation kind: ' + OperationKind);
    Exit(False);
  end;
  Inc(ASource^.Position);
  Result := True;
end;

function TRepExpression.ReadOperationName(ASource: PStringExpression): Boolean;
var
  TheStart: PChar;
  TheInQuotes: Boolean;
begin
  while (ASource^.Position < ASource^.Finish) and (ASource^.Position^ = ' ') do
    Inc(ASource^.Position);
  if ASource^.Position >= ASource^.Finish then
  begin
    LastError := 'Operation with no name';
    Exit(False);
  end;
  TheStart := ASource^.Position;
  TheInQuotes := False;
  while (ASource^.Position < ASource^.Finish) and (TheInQuotes or (not CharInSet(ASource^.Position^, ['=', '!', '>', '<', '(']))) do
  begin
    if (ASource^.Position^ = '"') then
      if TheInQuotes = False then
        TheInQuotes := True
      else if (ASource^.Position - 1 )^ <> '\' then
        TheInQuotes := False;
    Inc(ASource^.Position);
  end;
  if TheInQuotes then
  begin
    ASource^.Position := TheStart;
    LastError := 'Invalid operation name';
    Exit(False);
  end;
  OperationNames := TStringExpression.CreateRecord(TheStart, ASource^.Position);
  Result := True;
end;

function TRepExpression.ReadOperationProperties(ASource: PStringExpression): Boolean;
var
  TheStart: PChar;
  TheLevel: Integer;
begin
  while (ASource^.Position < ASource^.Finish) and (ASource^.Position^ = ' ') do
    Inc(ASource^.Position);
  if (ASource^.Position >= ASource^.Finish) or (ASource^.Position^ <> '(') then
    Exit(True);
  Inc(ASource^.Position);
  TheStart := ASource^.Position;
  TheLevel := 1;
  while (ASource^.Position < ASource^.Finish) and (TheLevel > 0) do
  begin
    if ASource^.Position^ = '(' then
      Inc(TheLevel);
    if ASource^.Position^ = ')' then
      Dec(TheLevel);
    Inc(ASource^.Position);
  end;
  if TheLevel <> 0 then
  begin
    ASource^.Position := TheStart;
    LastError := 'Missing )';
    Exit(False);
  end;
  OperationProperties := TStringExpression.CreateRecord(TheStart, ASource^.Position - 1);
  Result := True;
end;

function TRepExpression.ReadOperand(ASource: PStringExpression): Boolean;
var
  TheStart: PChar;
begin
  while (ASource^.Position < ASource^.Finish) and (ASource^.Position^ = ' ') do
    Inc(ASource^.Position);
  if ASource^.Position >= ASource^.Finish then
  begin
    Operand := '';
    Exit(True);
  end;
  TheStart := ASource^.Position;
  Inc(ASource^.Position);
  if TheStart^ = '=' then
    Operand := '='
  else if TheStart^ = '<' then
    if (ASource^.Position >= ASource^.Finish) or (ASource^.Position^ <> '=') then
      Operand := '<'
    else
    begin
      Operand := '<=';
      Inc(ASource^.Position);
    end
  else if TheStart^ = '>' then
    if (ASource^.Position >= ASource^.Finish) or (ASource^.Position^ <> '=') then
      Operand := '>'
    else
    begin
      Operand := '>=';
      Inc(ASource^.Position);
    end
  else if TheStart^ = '!' then
    if (ASource^.Position >= ASource^.Finish) or (ASource^.Position^ <> '=') then
    begin
      LastError := 'Invalid operand type: !';
      Exit(False);
    end
    else
    begin
      Operand := '>=';
      Inc(ASource^.Position);
    end
  else
  begin
    LastError := ShortString('Invalid operand type: ' + TheStart^);
    Exit(False);
  end;
  Result := True;
end;

function TRepExpression.ReadValue(ASource: PStringExpression): Boolean;
var
  TheStart: PChar;
  TheInQuotes: Boolean;
begin
  while (ASource^.Position < ASource^.Finish) and (ASource^.Position^ = ' ') do
    Inc(ASource^.Position);
  if ASource^.Position >= ASource^.Finish then
  begin
    LastError := 'Empty value';
    Exit(False);
  end;
  TheStart := ASource^.Position;
  TheInQuotes := False;
  while (ASource^.Position < ASource^.Finish) and (not TheInQuotes) and CharInSet(ASource^.Position^, [',', '(']) do
  begin
    if (ASource^.Position^ = '"') then
      if TheInQuotes = False then
        TheInQuotes := True
      else if (ASource^.Position - 1 )^ <> '\' then
        TheInQuotes := False;
    Inc(ASource^.Position);
  end;
  if TheInQuotes then
  begin
    ASource^.Position := TheStart;
    LastError := 'Invalid value name';
    Exit(False);
  end;
  Value := TStringExpression.CreateRecord(TheStart, ASource^.Position);
  Result := True;
end;

function TRepExpression.ReadValueProperties(ASource: PStringExpression): Boolean;
var
  TheStart: PChar;
  TheLevel: Integer;
begin
  while (ASource^.Position < ASource^.Finish) and (ASource^.Position^ = ' ') do
    Inc(ASource^.Position);
  if (ASource^.Position >= ASource^.Finish) or (ASource^.Position^ <> '(') then
    Exit(True);
  Inc(ASource^.Position);
  TheStart := ASource^.Position;
  TheLevel := 1;
  while (ASource^.Position < ASource^.Finish) and (TheLevel > 0) do
  begin
    if ASource^.Position^ = '(' then
      Inc(TheLevel);
    if ASource^.Position^ = ')' then
      Dec(TheLevel);
    Inc(ASource^.Position);
  end;
  if TheLevel <> 0 then
  begin
    ASource^.Position := TheStart;
    LastError := 'Missing )';
    Exit(False);
  end;
  ValueProperties := TStringExpression.CreateRecord(TheStart, ASource^.Position - 1);
  Result := True;
end;

function TRepExpression.ReadExpression(ASource: PStringExpression): Boolean;
var
  TheStart: PChar;
begin
  TheStart := ASource^.Position;
  if not ReadEntityName(ASource) then
    Exit(False);
  if Entity = nil then
    Exit(True);
  if not ReadEntityProperties(ASource) then
    Exit(False);
  if not ReadOperationKind(ASource) then
    Exit(False);
  if OperationKind <> #0 then
  begin
    if not ReadOperationName(ASource) then
      Exit(False);
    if not ReadOperationProperties(ASource) then
      Exit(False);
    if not ReadOperand(ASource) then
      Exit(False);
    if Operand <> '' then
    begin
      if not ReadValue(ASource) then
        Exit(False);
      if not ReadValueProperties(ASource) then
        Exit(False);
    end;
  end;
  Result := True;
end;

end.
