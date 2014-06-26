unit WikiPage;

interface

uses
  ParseResults;

type
  TWikiPage = class
  public
    procedure LoadPageFromString(const AName, AText: AnsiString);
  end;

implementation

uses
  Level0Prep, Level1ParseComments, Level2ParseTags, Level3Prep, Level4ParseLanguage, Level5Prep, SysUtils,
  DateUtils, TypesFunctions;

{ TWikiPage }

procedure TWikiPage.LoadPageFromString(const AName, AText: AnsiString);
var
  TheParseResults: TParseResults;
  TheResult: AnsiString;
begin
  TheParseResults := TParseResults.Create;
  try
    TheParseResults.Name := string(AName);
    TheResult := TLevel0Prep.Execute(AText + #13#10, TheParseResults);
    TheResult := TLevel1ParseComments.Execute(TheResult, TheParseResults);
    TheResult := TLevel2ParseTags.Execute(TheResult, TheParseResults);
    TheResult := TLevel3Prep.Execute(TheResult, TheParseResults);
    TheResult := TLevel4ParseLanguage.Execute(TheResult, TheParseResults);
    TheResult := TLevel5Prep.Execute(TheResult, TheParseResults);
    TheParseResults.SetResult(TheResult);
  except
    TheParseResults.Free;
    raise;
  end;
end;

end.
