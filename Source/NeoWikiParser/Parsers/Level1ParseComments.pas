unit Level1ParseComments;

interface

uses
  BaseParser;

type
  TLevel1ParseComments = class(TBaseParser)
  private
    procedure FindCommentStart;
    function ReadComment: AnsiString;
  protected
    function DoExecute: AnsiString; override;
  end;

implementation

uses
  ElementParseComment;

{ TLevel1ParseComments }

function TLevel1ParseComments.DoExecute: AnsiString;
begin
  Result := '';
  while FCursor < FEnd do
  begin
    FindCommentStart;
    while FLastCursor < FCursor do
    begin
      Result := Result + FLastCursor^;
      Inc(FLastCursor);
    end;
    if FCursor < FEnd then
      Result := Result + ReadComment;
    FLastCursor := FCursor;
  end;
end;

procedure TLevel1ParseComments.FindCommentStart;
begin
  repeat
    while (FCursor < FEnd) and (FCursor^ <> '<') do
      Inc(FCursor);
    if not (FCursor < FEnd) then
      Exit;
    if (FCursor + 1 <= FEnd) and ((FCursor + 1)^ = '!') then
      if (FCursor + 2 <= FEnd) and ((FCursor + 2)^ = '-') then
        if (FCursor + 3 <= FEnd) and ((FCursor + 3)^ = '-') then
          Exit;
    Inc(FCursor);
  until FCursor >= FEnd;
end;

function TLevel1ParseComments.ReadComment: AnsiString;
begin
  Result := TElementParseComment.Execute(@FCursor, FEnd, FResultsObject);
end;

end.
