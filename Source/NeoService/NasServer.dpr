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
  AppExceptionClasses in 'App\AppExceptionClasses.pas',
  ClientCommands in 'Requests\ClientCommands.pas',
  ClientRequest in 'Requests\ClientRequest.pas',
  AppSQLServerQuery in 'App\AppSQLServerQuery.pas',
  SentenceList in 'Classes\SentenceList.pas',
  NasTypes in 'Classes\NasTypes.pas',
  SentenceAlgorithm in 'Classes\SentenceAlgorithm.pas',
  SentenceListElement in 'Classes\SentenceListElement.pas',
  Hypernym in 'Classes\Hypernym.pas',
  SentenceBase in 'Entity\SentenceBase.pas',
  SentenceSplitter in 'Classes\SentenceSplitter.pas',
  GuessObject in 'Entity\GuessObject.pas',
  StoryBase in 'Entity\StoryBase.pas',
  CRep in 'Classes\CRep.pas',
  CRepDecoder in 'Classes\CRepDecoder.pas',
  SentenceWithGuesses in 'Entity\SentenceWithGuesses.pas',
  CacheReloadThread in 'App\CacheReloadThread.pas',
  PosTagger in 'Classes\PosTagger.pas',
  LexiconLine in 'Entity\LexiconLine.pas',
  RepDecoder in 'Classes\RepDecoder.pas',
  RepRecord in 'Entity\RepRecord.pas',
  StringExpression in 'Classes\StringExpression.pas',
  SearchPage in 'Entity\SearchPage.pas',
  RepEntity in 'Entity\RepEntity.pas',
  RepPropertyKey in 'Entity\RepPropertyKey.pas',
  RepObjectBase in 'Entity\RepObjectBase.pas',
  RepGroup in 'Entity\RepGroup.pas',
  RepPropertyValue in 'Entity\RepPropertyValue.pas',
  RepPerson in 'Entity\RepPerson.pas',
  RepObject in 'Entity\RepObject.pas'
//  , LoggerUnit
  ;

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
