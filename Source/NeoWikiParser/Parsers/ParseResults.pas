unit ParseResults;

interface

uses
  Entity;

type
  TParseResults = class(TEntity)
  private
    FTags: string;
    FInternalLinks: string;
    FTemplates: string;
    FName: string;
  public
    procedure AddInternalLink(const ALink, ALabel: string);
    procedure AddExternalLink(const ALink, ALabel: string);
    procedure AddRef(const AParams, AValue: string);
    procedure AddUnknownTag(const ATagName: string);

    procedure SetResult(const AResult: AnsiString);
    class function GetUnknownTags: string;
  published
    property Name: string read FName write FName;
  end;

implementation

uses
  Skylists;

var
  _UnknownTags: TSkyStringStringList;
{ TParseResults }

procedure TParseResults.AddExternalLink(const ALink, ALabel: string);
begin
end;

procedure TParseResults.AddInternalLink(const ALink, ALabel: string);
begin
end;

procedure TParseResults.AddRef(const AParams, AValue: string);
begin
end;

procedure TParseResults.AddUnknownTag(const ATagName: string);
begin
end;

class function TParseResults.GetUnknownTags: string;
begin
end;

procedure TParseResults.SetResult(const AResult: AnsiString);
begin

end;

end.

