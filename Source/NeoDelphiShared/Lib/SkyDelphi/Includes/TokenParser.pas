unit TokenParser;

interface

uses
  Classes;

type
  TTokenParser = class
  private
    FFirstChar, 
    FLastChar: PAnsiChar;
    FStreamEnd: PAnsiChar;
    FOutStream: TStream;
    function GetToken: string;
    function FindOpening: Boolean;
    function FindEnding: Boolean;
  public
    constructor Create(AInStream: TMemoryStream; AOutStream: TStream = nil);
    
    function FindNextToken: Boolean;

    property Token: string read GetToken;
  end;

implementation

{ TTokenParser }

function TTokenParser.FindOpening: Boolean;
begin
  Result := True;
  if (FLastChar <> nil) and (FOutStream <> nil) and (FFirstChar < FStreamEnd) then
    FOutStream.Write(FFirstChar^, SizeOf(AnsiChar));
  FLastChar := nil;
  while FFirstChar < FStreamEnd do
  begin
    if FFirstChar^ = '[' then
      Exit;
    if FOutStream <> nil then
      FOutStream.Write(FFirstChar^, SizeOf(AnsiChar));
    Inc(FFirstChar);
  end;
  Result := False;
end;

function TTokenParser.FindEnding: Boolean;
begin
  Result := False;
  FLastChar := FFirstChar;
  repeat
    Inc(FLastChar);
    if (FLastChar = FStreamEnd) or (FLastChar^ = '[') then
    begin
      if FOutStream <> nil then
        FOutStream.Write(FFirstChar^, SizeOf(AnsiChar));
      Inc(FFirstChar);
      Exit;
    end;
  until FLastChar^ = ']';
  Result := True;
end;

function TTokenParser.FindNextToken: Boolean;
begin
  Result := False;
  repeat
    if not FindOpening then
      Exit;
    if FindEnding then
    begin
      Result := True;
      Exit;
    end;
  until 1 = 0;
end;

function TTokenParser.GetToken: string;
begin
  SetString(Result, FFirstChar + 1, FLastChar - FFirstChar - 1);
  FFirstChar := FLastChar + 1;
  FLastChar := nil;
end;

constructor TTokenParser.Create(AInStream: TMemoryStream; AOutStream: TStream = nil);
begin
  FFirstChar := AInStream.Memory;
  FOutStream := AOutStream;
  FStreamEnd := FFirstChar + AInStream.Size;
  FLastChar := nil;
end;

end.