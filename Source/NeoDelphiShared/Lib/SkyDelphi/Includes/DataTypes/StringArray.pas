unit StringArray;

interface

uses
  TypesConsts;

type
  TStringArray = record
  private
    FItems: array of TSkyString;
    function GetItem(AIndex: Integer): TSkyString; inline;
    procedure SetItem(AIndex: Integer; const Value: TSkyString); inline;
    function GetCount: Integer; inline;
    procedure SetCount(const Value: Integer); inline;
  public
    class function Empty: TStringArray; static;
    class function FromArray(SomeArray: array of TSkyString): TStringArray; static;

    procedure Add(const AValue: TSkyString); overload; inline;
    procedure Add(const AStringArray: TStringArray); overload; inline;
    function IsEmpty: Boolean; inline;

    property Count: Integer read GetCount write SetCount;
    property Items[AIndex: Integer]: TSkyString read GetItem write SetItem; default;
  end;

implementation

{ TStringArray }

procedure TStringArray.Add(const AValue: TSkyString);
begin
  Count := Count + 1;
  SetItem(Count - 1, AValue);
end;

procedure TStringArray.Add(const AStringArray: TStringArray);
var
  TheCount: Integer;
  I: Integer;
begin
  TheCount := Count;
  Count := Count + AStringArray.Count;
  for I := 0 to AStringArray.Count - 1 do
    Items[TheCount + I] := AStringArray[I];
end;

class function TStringArray.Empty: TStringArray;
begin
  Result.Count := 0;
end;

class function TStringArray.FromArray(SomeArray: array of TSkyString): TStringArray;
var
  I: Integer;
begin
  Result.Count := Length(SomeArray);
  for I := 0 to Result.Count - 1 do
    Result.SetItem(I, SomeArray[I]);
end;

function TStringArray.GetCount: Integer;
begin
  Result := Length(FItems);
end;

function TStringArray.GetItem(AIndex: Integer): TSkyString;
begin
  Result := FItems[AIndex];
end;

function TStringArray.IsEmpty: Boolean;
begin
  Result := FItems = nil;
end;

procedure TStringArray.SetCount(const Value: Integer);
begin
  SetLength(FItems, Value);
end;

procedure TStringArray.SetItem(AIndex: Integer; const Value: TSkyString);
begin
  FItems[AIndex] := Value;
end;

end.