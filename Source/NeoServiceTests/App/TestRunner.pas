                                                                          unit TestRunner;

interface

uses
  Entity, EntityList;

type
  TUnitTest = class;
  TUnitTestClass = class of TUnitTest;
  TTestRunner = class(TEntity)
  private
    class var
      FInstance: TTestRunner;
    function GetPassed: Boolean;
    procedure SetPassed(const Value: Boolean);
    function GetTests: Integer;
    procedure SetTests(const Value: Integer);
    var
      FTestList: TEntityList;
  public
    class function GetInstance: TTestRunner;
    class procedure EndInstance;
    class function RunTests: TEntity;

    procedure RegisterTest(ATest: TUnitTestClass);
  published
    property Passed: Boolean read GetPassed write SetPassed;
    property Tests: Integer read GetTests write SetTests;
    property TestList: TEntityList read FTestList write FTestList;
  end;

  TUnitTest = class(TEntity)
  private
    FSteps: TEntityList;
    FErrorMessage: string;
    FTestsTotal: Integer;
    FTestsPassed: Integer;
  public
    class procedure RegisterTest;
    procedure Initialize; override;
    procedure Execute;
    procedure Finalize; virtual;
    procedure RegisterTestSteps; virtual; abstract;
  published
    property ErrorMessage: string read FErrorMessage write FErrorMessage;
    property Name;
    property Steps: TEntityList read FSteps write FSteps;
    property TestsTotal: Integer read FTestsTotal write FTestsTotal;
    property TestsPassed: Integer read FTestsPassed write FTestsPassed;
  end;

  TTestStatus = (TestNotRun, TestOk, TestError, TestWarning);
  TTestStep = class(TEntity)
  private
    FStatus: TTestStatus;
    FErrorMessage: string;
    FInfo: TEntityList;
  public
    procedure Run(AUnitTest: TUnitTest); virtual; abstract;
    procedure Test(ACondition: Boolean; const AText: string; const AnError: string = '');
  published
    property Info: TEntityList read FInfo write FInfo;
    property Name;
    property Status: TTestStatus read FStatus write FStatus;
    property ErrorMessage: string read FErrorMessage write FErrorMessage;
  end;

implementation

uses
  SysUtils, EntityXmlWriter, GenericEntity;

{ TTestRunner }

class procedure TTestRunner.EndInstance;
begin
  FreeAndNil(FInstance);
end;

class function TTestRunner.GetInstance: TTestRunner;
begin
  if FInstance = nil then
    FInstance := TTestRunner.Create;
  Result := FInstance;
end;

function TTestRunner.GetPassed: Boolean;
var
  I: Integer;
begin
  Result := True;
  for I := 0 to FTestList.Count - 1 do
  begin
    Result := (FTestList[I] as TUnitTest).FTestsTotal = (FTestList[I] as TUnitTest).FTestsPassed;
    if not Result then
      Exit;
  end;
end;

function TTestRunner.GetTests: Integer;
begin
  Result := FTestList.Count;
end;

procedure TTestRunner.RegisterTest(ATest: TUnitTestClass);
begin
  FTestList.Add(ATest.Create);
end;

class function TTestRunner.RunTests: TEntity;
var
  TheRunner: TTestRunner;
  I: Integer;
begin
  TheRunner := GetInstance;
  for I := 0 to TheRunner.FTestList.Count - 1 do
    (TheRunner.FTestList[I] as TUnitTest).Execute;
  Result := TheRunner;
end;

procedure TTestRunner.SetPassed(const Value: Boolean);
begin

end;

procedure TTestRunner.SetTests(const Value: Integer);
begin

end;

{ TUnitTest }

procedure TUnitTest.Execute;
var
  TheStep: TTestStep;
  ThePassed: Integer;
  I: Integer;
begin
  try
    RegisterTestSteps;
    Initialize;
    try
      ThePassed := 0;
      for I := 0 to FSteps.Count - 1 do
      begin
        TheStep :=  FSteps[I] as TTestStep;
        try
          TheStep.Run(Self);
          Inc(ThePassed);
          if TheStep.Status = TestNotRun then
            TheStep.Status := TestOk;
        except on E: Exception do
        begin
          TheStep.Status := TestError;
          TheStep.ErrorMessage := E.Message;
        end;
        end;
      end;
      TestsTotal := FSteps.Count;
      TestsPassed:= ThePassed;
    finally
      Finalize;
    end;
  except on E: Exception do
    ErrorMessage := 'Catastrophic error in Initialize or Finalize';
  end;
end;

procedure TUnitTest.Finalize;
begin
  // override in inherited
end;

procedure TUnitTest.Initialize;
begin
  inherited;
  // override in inherited
end;

class procedure TUnitTest.RegisterTest;
begin
  TTestRunner.GetInstance.RegisterTest(Self);
end;

{ TTestStep }

procedure TTestStep.Test(ACondition: Boolean; const AText: string; const AnError: string = '');
var
  TheEntity: TGenericEntity;
begin
  TheEntity := TGenericEntity.Create;
  TheEntity.SetValueForField('Test', AText);
  if ACondition then
    TheEntity.SetValueForField('TestResult', 'OK')
  else
    if AnError <> '' then
      TheEntity.SetValueForField('TestResult', 'Error: '+ AnError)
    else
      TheEntity.SetValueForField('TestResult', 'Error: Invalid result');
  Info.Add(TheEntity);
  if not ACondition then
    raise Exception.Create(TheEntity.GetValueForField('TestResult'));
end;

end.