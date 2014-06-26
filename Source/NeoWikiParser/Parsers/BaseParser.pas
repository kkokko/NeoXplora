unit BaseParser;

interface

uses
  ParseResults;

type
  TBaseParser = class
  protected
    FContent: AnsiString;
    FCursor: PAnsiChar;
    FStart: PAnsiChar;
    FLastCursor: PAnsiChar;
    FEnd: PAnsiChar;
    FResultsObject: TParseResults;
    procedure AdjustBoundaries; virtual;
    function DoExecute: AnsiString; virtual; abstract;
    function ReadContentWord(const ASeparator: AnsiString): AnsiString; overload;
    function ReadContentWord(var ASource: AnsiString; const ASeparator: AnsiString): AnsiString; overload;
  public
    constructor Create(const AString: AnsiString; AResultsObject: TParseResults); overload;
    class function Execute(const AString: AnsiString; AResultsObject: TParseResults): AnsiString; overload; virtual;
    class function Execute(ACursor: PPAnsiChar; AnEnd: PAnsiChar; AResultsObject: TParseResults): AnsiString; overload; virtual;
  end;

  TBaseParserClass = class of TBaseParser;

implementation

uses
  AppUtils;

{ TBaseParser }

procedure TBaseParser.AdjustBoundaries;
begin
  // override in inherited
end;

constructor TBaseParser.Create(const AString: AnsiString; AResultsObject: TParseResults);
begin
  FContent := AString;
  FCursor := PAnsiChar(FContent);
  FStart := FCursor;
  FLastCursor := FCursor;
  FEnd := FCursor + Length(FContent);
  FResultsObject := AResultsObject;
end;

class function TBaseParser.Execute(const AString: AnsiString; AResultsObject: TParseResults): AnsiString;
var
  TheObject: TBaseParser;
begin
  TheObject := Create(AString, AResultsObject);
  try
    Result := TheObject.DoExecute;
  finally
    TheObject.Free;
  end;
end;

class function TBaseParser.Execute(ACursor: PPAnsiChar; AnEnd: PAnsiChar; AResultsObject: TParseResults): AnsiString;
var
  TheObject: TBaseParser;
begin
  TheObject := Create;
  try
    TheObject.FCursor := ACursor^;
    TheObject.FStart := TheObject.FCursor;
    TheObject.FLastCursor := TheObject.FCursor;
    TheObject.FEnd :=  AnEnd;
    TheObject.FResultsObject := AResultsObject;
    TheObject.AdjustBoundaries;
    ACursor^ := TheObject.FEnd;
    Result := TheObject.DoExecute;
  finally
    TheObject.Free;
  end;
end;

function TBaseParser.ReadContentWord(var ASource: AnsiString; const ASeparator: AnsiString): AnsiString;
var
  TheStart, TheEnd: PAnsiChar;
  TheIndex: Integer;
begin
  TheStart := PAnsiChar(ASource);
  TheEnd := FastAnsiPos(TheStart, PAnsiChar(ASeparator));
  if TheEnd = nil then
    TheIndex := 0
  else
    TheIndex := TheEnd - TheStart + 1;
  if TheIndex > 0 then
  begin
    Result := Copy(ASource, 1, TheIndex - 1);
    ASource := Copy(ASource, TheIndex + 1, Length(ASource) - TheIndex);
  end
  else
  begin
    Result := ASource;
    ASource := '';
  end;
end;

function TBaseParser.ReadContentWord(const ASeparator: AnsiString): AnsiString;
begin
  Result := ReadContentWord(FContent, ASeparator);
end;

end.
