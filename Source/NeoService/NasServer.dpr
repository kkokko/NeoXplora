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
  StoryBase in '..\NeoDelphiShared\Lib\NeoAi\Entity\StoryBase.pas',
  CRep in '..\NeoDelphiShared\Lib\NeoAi\Classes\CRep.pas',
  CRepDecoder in '..\NeoDelphiShared\Lib\NeoAi\Classes\CRepDecoder.pas',
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
  RepObject in '..\NeoDelphiShared\Lib\NeoAi\Entity\RepObject.pas' {/  , LoggerUnit},
  AppConsts in '..\NeoDelphiShared\Lib\NeoAi\Misc\AppConsts.pas';

//var
//  TheRec: TRepRecord;
{$R *.res}

begin
//  TheRec := TRepDecoder.DecodeRep('p123(.name = John'' Smith, .ref = "abc \" . , asd"):"event 1 ,."(:subevent = [p2], .subattribute = [p1]."asd asd")>19+18+"abc ,"(.attr1 = value), g1([p1]+[p2]+[p3])');
//  TLogger.Info(nil, [TheRec.ToString]);
//  TheRec.Free;

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
