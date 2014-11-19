unit SplitterComponentTest;

interface

uses
  TestRunner;

type
  TSentenceSplitterComponentTest = class(TUnitTest)
  public
    procedure RegisterTestSteps; override;
  end;

implementation

uses
  TypesConsts, SysUtils, Interval, RepRecord, RepDecoder, SplitterComponent;

type
  TTestWordSplit = class(TTestStep) public
    procedure Run(AUnitTest: TUnitTest); override;
  end;
type
  TTestPageSplit = class(TTestStep) public
    procedure Run(AUnitTest: TUnitTest); override;
  end;

{ TSentenceSplitterComponentTest }

procedure TSentenceSplitterComponentTest.RegisterTestSteps;
begin
  Steps.Add(TTestWordSplit.Create);
  Steps.Add(TTestPageSplit.Create);
end;

procedure TTestWordSplit.Run(AUnitTest: TUnitTest);
var
  TheChain: TSplitterComponent.TWordChainWithInfoType;
  TheSplitter: TSplitterComponent;
  TheExpectedResult: string;
  TheResult: string;
begin
  TheSplitter := TSplitterComponent.Create;
  try
    TheSplitter.SentenceSplitWords('Mr O''Sullivan fell 1''st in the rarely-used stairwell in Easts Leagues Club');
    TheExpectedResult := 'Mr| |O''Sullivan| |fell| |1''st| |in| |the| |rarely|-|used| |stairwell| |in| |Easts| |Leagues| |Club|';
    TheResult := TheSplitter.WordChainAsString(TheSplitter.FirstElement, '|');
    Test(TheResult = TheExpectedResult, 'TTestWordSplit', 'Wrong result 1: ' + TheResult + #13#10'Expected:' + TheExpectedResult);
    TheChain := TheSplitter.NewWordChain(wctNoSpaces);
    try
      TheResult := TheSplitter.WordChainAsString(TheChain.Chain, '|');
      TheExpectedResult := 'Mr|O''Sullivan|fell|1''st|in|the|rarely|-|used|stairwell|in|Easts|Leagues|Club|';
      Test(TheResult = TheExpectedResult, 'TTestWordSplit', 'Wrong result 2: ' + TheResult + #13#10'Expected:' + TheExpectedResult);
    finally
      TheSplitter.FreeWordChain(TheChain.Chain);
    end;
  finally
    TheSplitter.Free;
  end;
end;

{ TTestPageSplit }

procedure TTestPageSplit.Run(AUnitTest: TUnitTest);
var
  TheChain: TSplitterComponent.TSentenceChainWithInfoType;
  TheSplitter: TSplitterComponent;
  TheExpectedResult: string;
  TheResult: string;
begin
  TheSplitter := TSplitterComponent.Create;
  try
    TheSplitter.PageSplitProtos(' "Mr O''Sullivan looked 1''st here. "This is sentence 2" said John? "Right!" said Jane!"  ');
    TheChain := TheSplitter.NewSentenceChain(wctAll);
    try
      TheResult := TheSplitter.SentenceChainAsString(TheChain.Chain, '|');
      TheExpectedResult :=
        'Mr| |O''Sullivan| |looked| |1''st| |here|.|' + ReturnLf +
        '"|This| |is| |sentence| |2|"| |said| |John|?|' + ReturnLf +
        '"|Right|!|"| |said| |Jane|!|' + ReturnLf;
      Test(TheResult = TheExpectedResult, 'TTestPageSplit', 'Wrong result 1: ' + TheResult + #13#10'Expected:' + TheExpectedResult);
    finally
      TheSplitter.FreeSentenceChain(TheChain.Chain);
    end;
  finally
    TheSplitter.Free;
  end;
end;

initialization
  TSentenceSplitterComponentTest.RegisterTest;

end.