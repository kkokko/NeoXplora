program NasWikiParser;

uses
  Forms,
  MainForm in 'Forms\MainForm.pas' {frmMain},
  WikiPage in 'Application\WikiPage.pas',
  Level0Prep in 'Parsers\Level0Prep.pas',
  ParseResults in 'Parsers\ParseResults.pas',
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
  ProcessingThread in 'Application\ProcessingThread.pas';

{$R *.res}

begin
  Application.Initialize;
  Application.MainFormOnTaskbar := True;
  Application.CreateForm(TfrmMain, frmMain);
  Application.Run;
end.
