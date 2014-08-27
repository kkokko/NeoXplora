unit WikiPageProcessingThread;

interface

uses
  Classes, SentenceSplitter;

type
  TWikiPageProcessingThread = class(TThread)
  private
    FSplitter: TSentenceSplitter;
    FWordSplitter: TSentenceSplitter;
    procedure LoadPageFromString(const AName, AText: AnsiString);
  protected
    procedure Execute; override;
  public
    constructor Create; reintroduce;
    destructor Destroy; override;
  end;

implementation

uses
  Scheduler, SysUtils, LoggerUnit, ParseResult, Level0Prep, Level1ParseComments, Level2ParseTags, Level3Prep,
  Level4ParseLanguage, Level5Prep, SkyLists, SentenceWithGuesses, AppUnit, PageBase;

{ TWikiPageProcessingThread }

constructor TWikiPageProcessingThread.Create;
begin
  inherited Create(True);
  FreeOnTerminate := True;
  FSplitter := TSentenceSplitter.Create;
  FWordSplitter := TSentenceSplitter.Create;
  Suspended := False;
end;

destructor TWikiPageProcessingThread.Destroy;
begin
  FSplitter.Free;
  FWordSplitter.Free;
  inherited;
end;

procedure TWikiPageProcessingThread.Execute;
var
  TheRecord: TScheduler.TParseRecord;
begin
  TScheduler.NotifyWorkerStarted;
  try
    repeat
      if TScheduler.ProcessingOver then
      begin
        Terminate;
        Exit;
      end;
      try
        TheRecord := TScheduler.ReadFromQueue;
        if TheRecord.Name <> '' then
          LoadPageFromString(TheRecord.Name, TheRecord.Text)
        else
          Sleep(10);
      except on E: Exception do // eat all exceptions
        TLogger.Error(Self, E);
      end;
    until Terminated;
  finally
    TScheduler.NotifyWorkerFinished;
  end;
end;

procedure TWikiPageProcessingThread.LoadPageFromString(const AName, AText: AnsiString);
var
  TheAResult: AnsiString;
  TheResult: string;
  TheString: string;
  TheList: TSkyStringList;
  TheObject: TParseResult;
  TheSentence: TSentenceWithGuesses;
  I: Integer;
begin
  TheObject := TParseResult.Create;
  try
    TheObject.Name := string(AName);

    if (AName <> 'Albedo') and
      (AName <> 'An American in Paris') and
      (AName <> 'Academy Award for Best Production Design') and
      (AName <> 'International Atomic Time') and
      (AName <> 'List of Atlas Shrugged characters') then
      Exit;

    TheAResult := TLevel0Prep.Execute(AText + #13#10, TheObject);
    TheAResult := TLevel1ParseComments.Execute(TheAResult, TheObject);
    TheAResult := TLevel2ParseTags.Execute(TheAResult, TheObject);
    TheAResult := TLevel3Prep.Execute(TheAResult, TheObject);
    TheAResult := TLevel4ParseLanguage.Execute(TheAResult, TheObject);
    TheAResult := TLevel5Prep.Execute(TheAResult, TheObject);

    // adds some missing . and removes empty lines
    TheList := TSkyStringList.Create;
    try
      TheResult := '';
      TheList.Text := string(TheAResult);
      for I := 0 to TheList.Count - 1 do
      begin
        TheString := Trim(TheList[I]);
        if TheString = '' then
          Continue;
        if CharInSet(TheString[Length(TheString)], ['.', '?', '!']) then
          TheResult := TheResult + TheString
        else
          TheResult := TheResult + TheString + '.';
      end;
    finally
      TheList.Free;
    end;

    // set the Page
    TheObject.Page := TPageBase.Create;
    TheObject.Page.Body := TheResult;
    TheObject.Page.Source := 'http://en.wikipedia.org/wiki/' + string(AName);
    TheObject.Page.Name := string(AName);

    // split sentences, add pos, guesses
    FSplitter.PageSplitProtos(TheResult);
    for I := 0 to FSplitter.WordList.Count - 1 do
    begin
      TheSentence := TSentenceWithGuesses.Create;
      TheSentence.Name := FSplitter.WordList[I];
      FWordSplitter.SentenceSplitWords(TheSentence.Name);
      TheSentence.Pos := App.PosTagger.GetTagsForWords(FWordSplitter, False);
      TheSentence.Order := I;
//    Guessing reps - disabled for now
      App.SentenceList.GetRepGuess(FWordSplitter.WordList, TheSentence.Name, TheSentence.Pos, 1, False, TheSentence.Guesses);
      TheSentence.Rep := TheSentence.Guesses.RepGuessD;
      TheSentence.SRep := TheSentence.Guesses.SRepGuessD;
      TheSentence.CRep := TheSentence.Rep; // no CRep guess yet
      TheObject.Sentences.Add(TheSentence);
    end;
  except
    TheObject.Free;
    raise;
  end;
  TScheduler.AddToInsertQueue(TheObject);
end;

end.
