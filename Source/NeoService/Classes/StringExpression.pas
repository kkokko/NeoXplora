unit StringExpression;

interface

type
  PStringExpression = ^TStringExpression;
  TStringExpression = record
    Start: PChar;
    Finish: PChar;
    Position: PChar;
    Brother: PStringExpression;
    FirstChild: PStringExpression;

    class function CreateRecord(AStart, AFinish: PChar): PStringExpression; static;
    class procedure DisposePtr(APointer: PStringExpression); static;
    class function SetString(const AString: string): PStringExpression; static;
    function GetValue: string; overload;
    function GetValue(AnIgnoreLeft, AnIgnoreRight: Integer): string; overload;
    class function GetValue(AStart, AFinish: PChar): string; overload; static;
    // Returns true if not end of string
    function InString: Boolean; inline;
    function Index: Integer;
  end;

implementation

uses
  SysUtils;

{ TStringExpression }

function TStringExpression.GetValue: string;
var
  TheSize: Integer;
begin
  TheSize := Finish - Start;
  if TheSize < 1 then
  begin
    Result := '';
    Exit;
  end;
  SetLength(Result, TheSize);
  StrLCopy(PChar(Result), Start, TheSize);
end;

class function TStringExpression.CreateRecord(AStart, AFinish: PChar): PStringExpression;
begin
  New(Result);
  Result^.Start := AStart;
  Result^.Finish := AFinish;
  Result^.Position := AStart;
  Result^.Brother := nil;
  Result^.FirstChild := nil;
end;

class procedure TStringExpression.DisposePtr(APointer: PStringExpression);
begin
  if APointer = nil then
    Exit;
  if APointer^.Brother <> nil then
    DisposePtr(APointer^.Brother);
  if APointer^.FirstChild <> nil then
    DisposePtr(APointer^.FirstChild);
  Dispose(APointer);
end;

function TStringExpression.GetValue(AnIgnoreLeft, AnIgnoreRight: Integer): string;
var
  TheSize: Integer;
begin
  TheSize := (Finish - AnIgnoreRight) - (Start + AnIgnoreLeft);
  if TheSize < 1 then
  begin
    Result := '';
    Exit;
  end;
  SetLength(Result, TheSize);
  StrLCopy(PChar(Result), Start + AnIgnoreLeft, TheSize);
end;

function TStringExpression.Index: Integer;
begin
  Result := Position - Start
end;

function TStringExpression.InString: Boolean;
begin
  Result := Position < Finish;
end;

class function TStringExpression.SetString(const AString: string): PStringExpression;
begin
  New(Result);
  Result^.Start := Pointer(AString);
  Result^.Finish := Result.Start + Length(AString);
  Result^.Position := Result.Start;
  Result^.Brother := nil;
  Result^.FirstChild := nil;
end;

class function TStringExpression.GetValue(AStart, AFinish: PChar): string;
var
  TheSize: Integer;
begin
  TheSize := AFinish - AStart;
  if TheSize < 1 then
  begin
    Result := '';
    Exit;
  end;
  SetLength(Result, TheSize);
  StrLCopy(PChar(Result), AStart, TheSize);
end;

end.
