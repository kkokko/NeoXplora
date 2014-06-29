program NasWikiParser;

uses
  ExceptionLog,
  Forms,
  MainForm in 'Forms\MainForm.pas' {frmMain},
  Level0Prep in 'Parsers\Level0Prep.pas',
  ParseResult in 'Parsers\ParseResult.pas',
  BaseParser in 'Parsers\BaseParser.pas',
  Level2ParseTags in 'Parsers\Level2ParseTags.pas',
  Level4ParseLanguage in 'Parsers\Level4ParseLanguage.pas',
  Level3Prep in 'Parsers\Level3Prep.pas',
  ElementParseHorizontalLine in 'Parsers\ElementParseHorizontalLine.pas',
  ElementParseTemplate in 'Parsers\ElementParseTemplate.pas',
  ElementParseInternalLink in 'Parsers\ElementParseInternalLink.pas',
  ElementParseComment in 'Parsers\ElementParseComment.pas',
  Level5Prep in 'Parsers\Level5Prep.pas',
  ElementParseExternalLink in 'Parsers\ElementParseExternalLink.pas',
  ElementParseTagRef in 'Parsers\ElementParseTagRef.pas',
  ElementParseBlockQuote in 'Parsers\ElementParseBlockQuote.pas',
  Level1ParseComments in 'Parsers\Level1ParseComments.pas',
  FileReadThread in 'Application\FileReadThread.pas',
  Scheduler in 'Application\Scheduler.pas',
  DatabaseWriterThread in 'Application\DatabaseWriterThread.pas',
  AppSettings in 'Application\AppSettings.pas',
  AppUtils in 'Application\AppUtils.pas',
  WikiPageProcessingThread in 'Application\WikiPageProcessingThread.pas',
  CRep in '..\NeoDelphiShared\Lib\NeoAi\Classes\CRep.pas',
  CRepDecoder in '..\NeoDelphiShared\Lib\NeoAi\Classes\CRepDecoder.pas',
  Hypernym in '..\NeoDelphiShared\Lib\NeoAi\Classes\Hypernym.pas',
  NasTypes in '..\NeoDelphiShared\Lib\NeoAi\Classes\NasTypes.pas',
  PosTagger in '..\NeoDelphiShared\Lib\NeoAi\Classes\PosTagger.pas',
  RepDecoder in '..\NeoDelphiShared\Lib\NeoAi\Classes\RepDecoder.pas',
  SentenceAlgorithm in '..\NeoDelphiShared\Lib\NeoAi\Classes\SentenceAlgorithm.pas',
  SentenceList in '..\NeoDelphiShared\Lib\NeoAi\Classes\SentenceList.pas',
  SentenceListElement in '..\NeoDelphiShared\Lib\NeoAi\Classes\SentenceListElement.pas',
  SentenceSplitter in '..\NeoDelphiShared\Lib\NeoAi\Classes\SentenceSplitter.pas',
  StringExpression in '..\NeoDelphiShared\Lib\NeoAi\Classes\StringExpression.pas',
  GuessObject in '..\NeoDelphiShared\Lib\NeoAi\Entity\GuessObject.pas',
  LexiconLine in '..\NeoDelphiShared\Lib\NeoAi\Entity\LexiconLine.pas',
  RepEntity in '..\NeoDelphiShared\Lib\NeoAi\Entity\RepEntity.pas',
  RepGroup in '..\NeoDelphiShared\Lib\NeoAi\Entity\RepGroup.pas',
  RepObject in '..\NeoDelphiShared\Lib\NeoAi\Entity\RepObject.pas',
  RepObjectBase in '..\NeoDelphiShared\Lib\NeoAi\Entity\RepObjectBase.pas',
  RepPerson in '..\NeoDelphiShared\Lib\NeoAi\Entity\RepPerson.pas',
  RepProperties in '..\NeoDelphiShared\Lib\NeoAi\Entity\RepProperties.pas',
  RepPropertyKey in '..\NeoDelphiShared\Lib\NeoAi\Entity\RepPropertyKey.pas',
  RepPropertyValue in '..\NeoDelphiShared\Lib\NeoAi\Entity\RepPropertyValue.pas',
  RepRecord in '..\NeoDelphiShared\Lib\NeoAi\Entity\RepRecord.pas',
  SearchPage in '..\NeoDelphiShared\Lib\NeoAi\Entity\SearchPage.pas',
  SentenceBase in '..\NeoDelphiShared\Lib\NeoAi\Entity\SentenceBase.pas',
  SentenceWithGuesses in '..\NeoDelphiShared\Lib\NeoAi\Entity\SentenceWithGuesses.pas',
  StoryBase in '..\NeoDelphiShared\Lib\NeoAi\Entity\StoryBase.pas',
  AppUnit in 'Application\AppUnit.pas',
  AppExceptionClasses in '..\NeoDelphiShared\Lib\NeoAi\Misc\AppExceptionClasses.pas',
  AppConsts in '..\NeoDelphiShared\Lib\NeoAi\Misc\AppConsts.pas',
  AppSQLServerQuery in '..\NeoDelphiShared\Lib\NeoAi\Misc\AppSQLServerQuery.pas',
  Proto in '..\NeoDelphiShared\Lib\NeoAi\Entity\Proto.pas';

{$R *.res}

begin
  Application.Initialize;
  Application.MainFormOnTaskbar := True;
  Application.CreateForm(TfrmMain, frmMain);
  Application.Run;
  TApp.EndInstance;
end.
