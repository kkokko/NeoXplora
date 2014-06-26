unit ElementParseHorizontalLine;

interface

uses
  BaseParser;

type
  TElementParseHorizontalLine = class(TBaseParser)
  protected
    procedure AdjustBoundaries; override;
    function DoExecute: AnsiString; override;
  end;

implementation

{ TElementParseHorizontalLine }

procedure TElementParseHorizontalLine.AdjustBoundaries;
begin
  while (FCursor < FEnd) and (FCursor^ = '-') do
    Inc(FCursor);
  FEnd :=  FCursor;
end;

function TElementParseHorizontalLine.DoExecute: AnsiString;
begin
  Result := '';
end;

end.