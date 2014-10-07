unit SentenceSplitterTest;

interface

uses
  TestRunner;

type
  TSentenceSplitterTest = class(TUnitTest)
  public
    procedure RegisterTestSteps; override;
  end;

implementation

uses
  TypesConsts, SysUtils, Interval, RepRecord, RepDecoder, SentenceSplitter;

type
  TTestHideDotsForExpressions = class(TTestStep) public
    procedure Run(AUnitTest: TUnitTest); override;
  end;

{ TSentenceSplitterTest }

procedure TSentenceSplitterTest.RegisterTestSteps;
begin
  Steps.Add(TTestHideDotsForExpressions.Create);
end;

procedure TTestHideDotsForExpressions.Run(AUnitTest: TUnitTest);
var
  TheExpectedResult: string;
  TheResult: string;
  TheSplitter: TSentenceSplitter;
begin
  TheSplitter := TSentenceSplitter.Create;
  try
    TheResult := TheSplitter.HideDotsForExpressions('mr. a is amr. 6 mrs. b is not mr.', ['Mr.', 'Mrs.']);
    TheExpectedResult := 'Mr%&^ a is amr. 6 Mrs%&^ b is not Mr%&^';
    Test(TheResult = TheExpectedResult, 'HideDotsForExpressions1', 'Wrong result: ' + TheResult + #13#10'Expected:' + TheExpectedResult);

    TheExpectedResult := 'Reportedly falling down stairs in Sydney''s east.' +
      ' And becoming trapped until he was found five days later.';

    TheResult := TheSplitter.HideDotsForExpressions(
      TheExpectedResult,
      ['Mr.', 'Mrs.', 'Ms.', 'e.g.', 'etc.', 'i.e.', 'Dr.', 'Prof.', 'Sr.', 'Jr.', 'No.', 'St.', 'p.m.', 'a.m.']
    );
    Test(TheResult = TheExpectedResult, 'HideDotsForExpressions2', 'Wrong result: ' + TheResult + #13#10'Expected:' + TheExpectedResult);
  finally
    TheSplitter.Free;
  end;
end;

initialization
  TSentenceSplitterTest.RegisterTest;

end.
