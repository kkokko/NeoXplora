unit SQLInBuilder;

interface

type
  TSQLInBuilder = class
  private
    FMaxLength: Integer;
    FString: string;
    FIncludeQuotes: Boolean;
    FMakeSqlString: Boolean;
    FIncludeComma: Boolean;
    FLines: Integer;
    FMaxLines: Integer;
    function FixString(const AString: string): string;
  public
    constructor Create; reintroduce;

    procedure Reset;
    function TryAddString(const AString: string): Boolean;

    property IncludeComma: Boolean read FIncludeComma write FIncludeComma;
    property IncludeQuotes: Boolean read FIncludeQuotes write FIncludeQuotes;
    property Lines: Integer read FLines write FLines;
    property MaxLength: Integer read FMaxLength write FMaxLength;
    property MaxLines: Integer read FMaxLines write FMaxLines;
    property MakeSqlString: Boolean read FMakeSqlString write FMakeSqlString;
    property QueryString: string read FString write FString;
  end;


implementation

uses
  SQLUtils;

{ TSQLInBuilder }

constructor TSQLInBuilder.Create;
begin
  inherited;
  FMaxLength := 63000;
  FString := '';
  FIncludeComma := True;
  FIncludeQuotes := True;
  FMakeSqlString := True;
end;

function TSQLInBuilder.FixString(const AString: string): string;
begin
  if FMakeSqlString then
    Result := TSQLUtils.SqlStr(AString)
  else
    Result := AString;
  if FIncludeQuotes then
    Result := '''' + Result + '''';
  if FIncludeComma and (FString <> '') then
    Result := ',' + Result;
end;

procedure TSQLInBuilder.Reset;
begin
  FString := '';
  FLines := 0;
end;

function TSQLInBuilder.TryAddString(const AString: string): Boolean;
var
  TheString: string;
begin
  Result := False;
  if (0 <> MaxLines) and (Lines = MaxLines) then
    Exit;
  TheString := FixString(AString);
  if Length(FString) + Length(TheString) > MaxLength then
    Exit;
  FString := FString + TheString;
  FLines := FLines + 1;
  Result := True;
end;

end.
