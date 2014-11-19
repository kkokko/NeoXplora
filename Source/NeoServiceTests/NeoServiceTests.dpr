program NeoServiceTests;

{$APPTYPE CONSOLE}

uses
  SysUtils,
  Classes,
  Windows,
  EntityXmlWriter,
  TestRunner in 'App\TestRunner.pas',
  RepDecoder in '..\NeoDelphiShared\Lib\NeoAi\Classes\RepDecoder.pas',
  RepGroup in '..\NeoDelphiShared\Lib\NeoAi\Entity\RepGroup.pas',
  RepObjectBase in '..\NeoDelphiShared\Lib\NeoAi\Entity\RepObjectBase.pas',
  RepPropertyKey in '..\NeoDelphiShared\Lib\NeoAi\Entity\RepPropertyKey.pas',
  RepPropertyValue in '..\NeoDelphiShared\Lib\NeoAi\Entity\RepPropertyValue.pas',
  RepRecord in '..\NeoDelphiShared\Lib\NeoAi\Entity\RepRecord.pas',
  RepEntity in '..\NeoDelphiShared\Lib\NeoAi\Entity\RepEntity.pas',
  RepPerson in '..\NeoDelphiShared\Lib\NeoAi\Entity\RepPerson.pas',
  RepObject in '..\NeoDelphiShared\Lib\NeoAi\Entity\RepObject.pas',
  StringExpression in '..\NeoDelphiShared\Lib\NeoAi\Classes\StringExpression.pas',
  AppExceptionClasses in '..\NeoDelphiShared\Lib\NeoAi\Misc\AppExceptionClasses.pas',
  SentenceSplitter in '..\NeoDelphiShared\Lib\NeoAi\Classes\SentenceSplitter.pas',
  SentenceSplitterTest in 'Includes\SentenceSplitterTest.pas',
  SplitterComponentTest in 'Includes\SplitterComponentTest.pas',
  SplitterComponent in '..\NeoDelphiShared\Lib\NeoAi\Classes\SplitterComponent.pas';

//  RepSerializeTest in 'Includes\RepSerializeTest.pas'
{$R *.res}

var
  TheResult: TFileStream;

begin
  try
    if not FileExists('UnitTestsResult.xml') then
      TheResult := TFileStream.Create('UnitTestsResult.xml', fmCreate)
    else
      TheResult := TFileStream.Create('UnitTestsResult.xml', fmOpenWrite);
    try
      TheResult.Size := 0;
      TEntityXmlWriter.WriteEntity(TheResult, TTestRunner.RunTests);
    finally
      TheResult.Free;
    end;
    WinExec('explorer UnitTestsResult.xml', SW_SHOWNORMAL);
    if not TTestRunner.GetInstance.Passed then
      ExitCode := 1;
    TTestRunner.EndInstance;
  except on E: Exception do
  begin
    Writeln(E.ClassName, ': ', E.Message);
    ExitCode := 2;
  end;
  end;
end.
