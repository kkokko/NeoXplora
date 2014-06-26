unit ElementParseComment;

interface

uses
  BaseParser;

{ <!--value--> }
type
  TElementParseComment = class(TBaseParser)
  private
    procedure FindCommentEnd;
  protected
    procedure AdjustBoundaries; override;
    function DoExecute: AnsiString; override;
  end;

implementation

{ TElementParseComment }

procedure TElementParseComment.AdjustBoundaries;
begin
  Inc(FCursor, 4);
  FStart := FCursor;
  FindCommentEnd;
  FEnd := FCursor;
  FCursor := FStart;
  FLastCursor := FStart;
end;

function TElementParseComment.DoExecute: AnsiString;
begin
  Result := '';
end;

procedure TElementParseComment.FindCommentEnd;
begin
  repeat
    while (FCursor < FEnd) and (FCursor^ <> '-') do
      Inc(FCursor);
    // if proper Tag
    if (FCursor + 2 < FEnd) and ((FCursor + 1)^ = '-') and ((FCursor + 2)^ = '>') then
    begin
      FCursor := FCursor + 3;
      Exit;
    end;
    Inc(FCursor);
  until FCursor >= FEnd;
end;

end.