program NasServer;

uses
  Forms,
  SvcMgr,
  Windows,
  ServMain in 'Forms\ServMain.pas' {NasService},
  DebugForm in 'Forms\DebugForm.pas' {frmDebug},
  AppSettings in 'App\AppSettings.pas',
  AppUnit in 'App\AppUnit.pas',
  ServerCore in 'App\ServerCore.pas',
  ServiceThread in 'App\ServiceThread.pas',
  AppClientSession in 'Communication\AppClientSession.pas',
  AppHttpCommandRequestJson in 'Communication\AppHttpCommandRequestJson.pas',
  WebInterfaceHandler in 'Communication\WebInterfaceHandler.pas',
  AppTranslations in 'App\AppTranslations.pas',
  AppExceptionClasses in '..\NeoDelphiShared\Lib\NeoAi\Misc\AppExceptionClasses.pas',
  ClientCommands in 'Requests\ClientCommands.pas',
  ClientRequest in 'Requests\ClientRequest.pas',
  AppSQLServerQuery in '..\NeoDelphiShared\Lib\NeoAi\Misc\AppSQLServerQuery.pas',
  SentenceList in '..\NeoDelphiShared\Lib\NeoAi\Classes\SentenceList.pas',
  NasTypes in '..\NeoDelphiShared\Lib\NeoAi\Classes\NasTypes.pas',
  SentenceAlgorithm in '..\NeoDelphiShared\Lib\NeoAi\Classes\SentenceAlgorithm.pas',
  SentenceListElement in '..\NeoDelphiShared\Lib\NeoAi\Classes\SentenceListElement.pas',
  Hypernym in '..\NeoDelphiShared\Lib\NeoAi\Classes\Hypernym.pas',
  SentenceBase in '..\NeoDelphiShared\Lib\NeoAi\Entity\SentenceBase.pas',
  SentenceSplitter in '..\NeoDelphiShared\Lib\NeoAi\Classes\SentenceSplitter.pas',
  GuessObject in '..\NeoDelphiShared\Lib\NeoAi\Entity\GuessObject.pas',
  PageBase in '..\NeoDelphiShared\Lib\NeoAi\Entity\PageBase.pas',
  CRep in '..\NeoDelphiShared\Lib\NeoAi\Classes\CRep.pas',
  SentenceWithGuesses in '..\NeoDelphiShared\Lib\NeoAi\Entity\SentenceWithGuesses.pas',
  CacheReloadThread in 'App\CacheReloadThread.pas',
  PosTagger in '..\NeoDelphiShared\Lib\NeoAi\Classes\PosTagger.pas',
  LexiconLine in '..\NeoDelphiShared\Lib\NeoAi\Entity\LexiconLine.pas',
  RepDecoder in '..\NeoDelphiShared\Lib\NeoAi\Classes\RepDecoder.pas',
  RepRecord in '..\NeoDelphiShared\Lib\NeoAi\Entity\RepRecord.pas',
  StringExpression in '..\NeoDelphiShared\Lib\NeoAi\Classes\StringExpression.pas',
  SearchPage in '..\NeoDelphiShared\Lib\NeoAi\Entity\SearchPage.pas',
  RepEntity in '..\NeoDelphiShared\Lib\NeoAi\Entity\RepEntity.pas',
  RepPropertyKey in '..\NeoDelphiShared\Lib\NeoAi\Entity\RepPropertyKey.pas',
  RepObjectBase in '..\NeoDelphiShared\Lib\NeoAi\Entity\RepObjectBase.pas',
  RepGroup in '..\NeoDelphiShared\Lib\NeoAi\Entity\RepGroup.pas',
  RepPropertyValue in '..\NeoDelphiShared\Lib\NeoAi\Entity\RepPropertyValue.pas',
  RepPerson in '..\NeoDelphiShared\Lib\NeoAi\Entity\RepPerson.pas',
  AppConsts in '..\NeoDelphiShared\Lib\NeoAi\Misc\AppConsts.pas',
  IRepRuleGroup in '..\NeoDelphiShared\Lib\NeoAi\Entity\IRepRuleGroup.pas',
  IRepRule in '..\NeoDelphiShared\Lib\NeoAi\Entity\IRepRule.pas',
  IRepRuleCondition in '..\NeoDelphiShared\Lib\NeoAi\Entity\IRepRuleCondition.pas',
  IRepRuleValue in '..\NeoDelphiShared\Lib\NeoAi\Entity\IRepRuleValue.pas',
  RepObject in '..\NeoDelphiShared\Lib\NeoAi\Entity\RepObject.pas',
  BaseRule in '..\NeoDelphiShared\Lib\NeoAi\Entity\BaseRule.pas',
  IRep in '..\NeoDelphiShared\Lib\NeoAi\Classes\IRep.pas',
  CRepRuleGroup in '..\NeoDelphiShared\Lib\NeoAi\Entity\CRepRuleGroup.pas',
  CRepRuleCondition in '..\NeoDelphiShared\Lib\NeoAi\Entity\CRepRuleCondition.pas',
  CRepRule in '..\NeoDelphiShared\Lib\NeoAi\Entity\CRepRule.pas',
  Rep in '..\NeoDelphiShared\Lib\NeoAi\Classes\Rep.pas',
  BaseRuleCondition in '..\NeoDelphiShared\Lib\NeoAi\Entity\BaseRuleCondition.pas',
  BaseRuleGroup in '..\NeoDelphiShared\Lib\NeoAi\Entity\BaseRuleGroup.pas',
  AppHttpCommandRequestApiXml in 'Communication\AppHttpCommandRequestApiXml.pas',
  AppEntityXmlReader2 in 'Communication\AppEntityXmlReader2.pas',
  AppEntityXmlWriter2 in 'Communication\AppEntityXmlWriter2.pas',
  ApiRequest in 'Requests\ApiRequest.pas',
  ApiCommands in 'Requests\ApiCommands.pas';

{$R *.res}

function AttachConsole(dwProcessId: DWORD): BOOL; stdcall; external kernel32 name 'AttachConsole';

begin
  if (ParamCount = 1) and ((ParamStr(1) = '/?') or (ParamStr(1) = '?'))  and AttachConsole($FFFFFFFF) then
  begin
    Writeln('Usage: ' + Forms.Application.ExeName + ' /install [ServiceName] [ServiceDescription]');
    Writeln('Press enter to continue.');
    FreeConsole;
  end;
  if (ParamCount = 1) and (ParamStr(1) = '/debug') then
  begin
    Forms.Application.Initialize;
    Forms.Application.CreateForm(TfrmDebug, frmDebug);
  Forms.Application.Run;
  end
  else
  begin
    SvcMgr.Application.Initialize;
    SvcMgr.Application.CreateForm(TNasService, ServMain.NasService);
    SvcMgr.Application.Run;
  end;
end.
