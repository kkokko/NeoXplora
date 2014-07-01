unit ServerCore;

interface

uses
  Classes, Windows, EntityList, Entity, TypesConsts, VersionableEntity, SentenceList, GuessObject, DBAccess, Uni,
  SentenceWithGuesses, TimedLock, PosTagger, Hypernym;

type
  TServerCore = class(TObject)
  private
    type
      TLockType = (ltRead, ltWrite);
  private
    FHypernym: THypernym;
    FLock: TRTLCriticalSection;
    FLockCount: Integer;
    FTimedLock: TTimedLock;
    FMainThreadHandle: HWND;
    FPosTagger: TPosTagger;
    FSentenceList: TSentenceList;
    FStartDate: TDateTime;
    procedure SentenceLockAquire(ALockType: TLockType);
    procedure SentenceLockRelease(ALockType: TLockType);
  public
    constructor Create;
    destructor Destroy; override;

    class function GetInstance: TServerCore;
    class procedure EndInstance;
    procedure ReloadSentences;

    property MainThreadHandle: HWND read FMainThreadHandle write FMainThreadHandle;

    // user commands - please keep sorted
    function GetFullSentencesForStoryId(AStoryId: TId): TEntities;
    function GetPosForPage(const APage: string; AnUseModifiedPos: Boolean): TObjects;
    function GetPosForSentences(SomeSentences: TEntities; AnUseModifiedPos: Boolean): TEntities;
    function GuessRepsForSentenceId(ASentenceId: TId; AGuessCRep: Boolean = False): TGuessObject;
    procedure PredictAfterSplit(SomeSentences: TEntities);
    procedure Search(const ASearchString: string; var AnOffset: Integer; out SomePages: TEntities; out APageCount: Integer);
    procedure TrainUntrainedStories;
    procedure ValidateAllReps;
    procedure ValidateRep(const ARep: string);

    // methods called from threads
    procedure CacheReload;
  end;

function Core: TServerCore;
procedure FreeCore;

implementation

uses
  SysUtils, EntityService, AppUnit, AppClientSession, AppSQLServerQuery, ExceptionClasses, Session, ClientSession,
  AsyncSession, EntityManager, SkyIdList, AppExceptionClasses, BaseConnection, EntityWithName, TypesFunctions,
  EntityFieldNamesToken, SentenceBase, SentenceSplitter, DB, CRep, LoggerUnit, DateUtils, RepDecoder,
  SearchPage, SkyLists;

var
  _Core: TServerCore;

function Core: TServerCore;
begin
  Result := _Core;
end;

procedure FreeCore;
begin
  TServerCore.EndInstance;
end;

procedure TServerCore.CacheReload;
var
  TheCount: Integer;
begin
  TheCount := TAppSQLServerQuery.GetFinishedStoriesCount;
  if TheCount = FSentenceList.SentenceCount then
    Exit;
  ReloadSentences;
end;

procedure TServerCore.ReloadSentences;
var
  TheHyperNyms, TheSentences: TEntities;
  TheSentenceBase: TSentenceBase;
  TheSplitter: TSentenceSplitter;
  I: Integer;
begin
  TheSplitter := nil;
  TheSentences := nil;
  TheHyperNyms := TAppSQLServerQuery.GetHypernyms;
  try
    TheSentences := TAppSQLServerQuery.GetSplitSentences;
    TheSplitter := TSentenceSplitter.Create;
    SentenceLockAquire(ltWrite);
    try
      FHypernym.Clear;
      for I := 0 to High(TheHyperNyms) do
        FHypernym.LoadRepsFromString(TheHyperNyms[I].Name);
      for I := 0 to High(TheSentences) do
      begin
        TheSentenceBase := TheSentences[I] as TSentenceBase;
        TheSplitter.SentenceSplitWords(TheSentenceBase.Name);
        FSentenceList.AddSentence(TheSplitter.WordList, TheSentenceBase.Id, TheSentenceBase.Name,
          TheSentenceBase.Rep, TheSentenceBase.SRep, TheSentenceBase.Pos);
      end;
    finally
      SentenceLockRelease(ltWrite);
    end;
  finally
    TheSplitter.Free;
    TEntity.FreeEntities(TheSentences);
    TEntity.FreeEntities(TheHyperNyms);
  end;
end;

constructor TServerCore.Create;
begin
  inherited;
  App.CreateDefaultDatabaseConnection;
  FSentenceList := TSentenceList.Create;
  FHypernym := THypernym.Create;
  FSentenceList.Hypernym := FHypernym;
  FPosTagger := TPosTagger.Create;
  FStartDate := Now();
  FTimedLock := TTimedLock.Create('NasServerSentenceUpdate');
  FTimedLock.LockInterval := 10000; // 10 seconds
  FLockCount := 0;
  InitializeCriticalSection(FLock);
end;

destructor TServerCore.Destroy;
begin
  DeleteCriticalSection(FLock);
  FTimedLock.Free;
  FHypernym.Free;
  FSentenceList.Free;
  FPosTagger.Free;
  App.RemoveDefaultDatabaseConnection;
  inherited;
end;

class procedure TServerCore.EndInstance;
begin
  FreeAndNil(_Core);
end;

function TServerCore.GetFullSentencesForStoryId(AStoryId: TId): TEntities;
var
  TheSentence: TSentenceWithGuesses;
  TheSplitter: TSentenceSplitter;
  I: Integer;
begin
  Result := TAppSQLServerQuery.GetFullSentencesForStoryId(AStoryId);
  try
    TheSplitter := nil;
    SentenceLockAquire(ltRead);
    try
      TheSplitter := TSentenceSplitter.Create;
      for I := 0 to High(Result) do
      begin
        TheSentence := Result[I] as TSentenceWithGuesses;
        TheSplitter.SentenceSplitWords(TheSentence.Name);
        FSentenceList.RecalculateGuesses(TheSplitter.WordList, TheSentence);
      end;
    finally
      SentenceLockRelease(ltRead);
      TheSplitter.Free;
    end;
  except
    TEntity.FreeEntities(Result);
    raise;
  end;
end;

function TServerCore.GetPosForPage(const APage: string; AnUseModifiedPos: Boolean): TObjects;
var
  TheCount: Integer;
  TheSentences: TSkyStringList;
  TheSentenceSplitter: TSentenceSplitter;
  TheWordsSplitter: TSentenceSplitter;
  I: Integer;
begin
  TheSentences := nil;
  TheWordsSplitter := nil;
  TheSentenceSplitter := TSentenceSplitter.Create;
  try
    TheWordsSplitter := TSentenceSplitter.Create;
    TheSentenceSplitter.StorySplitProtos(APage);
    TheCount := TheSentenceSplitter.WordList.Count;
    SetLength(Result, TheCount);
    FillMemory(Result, TheCount * SizeOf(TObject), 0);
    try
      for I := 0 to TheCount - 1 do
      begin
        TheWordsSplitter.SentenceSplitWords(TheSentenceSplitter.WordList[I]);
        FPosTagger.GetTagsForWords(TheWordsSplitter, AnUseModifiedPos);
        Result[I] := TheWordsSplitter.WordList.CreateACopy;
      end;
    except
      for I := 0 to TheCount - 1 do
        Result[I].Free;
      raise;
    end;
  finally
    TheSentenceSplitter.Free;
    TheWordsSplitter.Free;
    TheSentences.Free;
  end;
end;

function TServerCore.GetPosForSentences(SomeSentences: TEntities; AnUseModifiedPos: Boolean): TEntities;
var
  I: Integer;
begin
  Result := TEntity.CreateAndCopyEntities(SomeSentences);
  try
    for I := 0 to High(Result) do
      Result[I].Name := FPosTagger.GetTagsForString(Result[I].Name, AnUseModifiedPos);
  except
    TEntity.FreeEntities(Result);
    raise;
  end;
end;

procedure TServerCore.TrainUntrainedStories;
var
  TheEntities: TEntities;
begin
  TheEntities := TAppSQLServerQuery.GetUntrainedStories;
  try
    PredictAfterSplit(TheEntities);
  finally
    TEntity.FreeEntities(TheEntities);
  end;
end;

procedure TServerCore.ValidateAllReps;
var
  TheSentenceBase: TSentenceBase;
  TheSentences: TEntities;
  I: Integer;
begin
  TheSentences := TAppSQLServerQuery.GetSplitSentences;
  try
    for I := 0 to High(TheSentences) do
    begin
      TheSentenceBase := TheSentences[I] as TSentenceBase;
      try
        ValidateRep(TheSentenceBase.Rep);
      except
        TheSentenceBase.Status := ssTrainedRep;
        App.SQLConnection.UpdateEntity(TheSentenceBase);
      end;
    end;
  finally
    TEntity.FreeEntities(TheSentences);
  end;
end;

procedure TServerCore.ValidateRep(const ARep: string);
begin
  TRepDecoder.DecodeRep(ARep).Free;
end;

class function TServerCore.GetInstance: TServerCore;
begin
  if not Assigned(_Core) then
    _Core := TServerCore.Create;
  Result := _Core;
end;

function TServerCore.GuessRepsForSentenceId(ASentenceId: TId; AGuessCRep: Boolean): TGuessObject;
var
  TheEntities: TEntities;
  TheTCRep: TCRep;
  TheSentence: TSentenceBase;
  TheSplitter: TSentenceSplitter;
  I: Integer;
begin
  TheSplitter := nil;
  TheSentence := TAppSQLServerQuery.GetSentenceBaseById(ASentenceId);
  try
    TheSplitter := TSentenceSplitter.Create;
    TheSplitter.SentenceSplitWords(TheSentence.Name);
    Result := TGuessObject.Create;
    try
      Result.Id := ASentenceId;
      SentenceLockAquire(ltRead);
      try
        FSentenceList.GetRepGuess(TheSplitter.WordList, TheSentence.Name,
          FPosTagger.GetTagsForString(TheSentence.Name), 1, False, Result);
      finally
        SentenceLockRelease(ltRead);
      end;

      if (AGuessCRep) and (Trim(TheSentence.Rep) <> '') then
      begin
        TheTCRep := nil;
        TheEntities := TAppSQLServerQuery.GetStoryReps(TheSentence.StoryId, TheSentence.Id);
        try
          TheTCRep := TCRep.Create;
          for I := 0 to High(TheEntities) do
            TheTCRep.AddSentence(TheEntities[I].Name);
          Result.CRepGuessA := TheTCRep.AddSentence(TheSentence.Rep);
        finally
          TEntity.FreeEntities(TheEntities);
          TheTCRep.Free;
        end;
      end;
      App.SQLConnection.UpdateEntity(Result);
    except
      Result.Free;
      raise;
    end;
  finally
    TheSplitter.Free;
    TheSentence.Free;
  end;
end;

procedure TServerCore.PredictAfterSplit(SomeSentences: TEntities);
var
  TheIds: TIds;
  I: Integer;
begin
  TheIds := TEntity.GetIdsOfEntities(SomeSentences);
  for I := 0 to High(TheIds) do
    GuessRepsForSentenceId(TheIds[I]).Free;
end;

procedure TServerCore.Search(const ASearchString: string; var AnOffset: Integer; out SomePages: TEntities;
  out APageCount: Integer);
var
  ThePage: TSearchPage;
  TheBody: string;
  I: Integer;
begin
  APageCount := TAppSQLServerQuery.GetTotalPageCount(ASearchString);
  if AnOffset > APageCount then
    AnOffset := APageCount - 10;
  if AnOffset < 0 then
    AnOffset := 0;
  SomePages := TAppSQLServerQuery.GetSearchPagesByOffset(ASearchString, AnOffset);
  for I := 0 to High(SomePages) do
  begin
    ThePage := SomePages[I] as TSearchPage;
    if Length(ThePage.Body) > 250 then
    begin
      TheBody := Copy(ThePage.Body, 1, 247);
      ThePage.Body := TheBody + '...';
    end;
    ThePage.Link := 'http://dev2.neoxplora.com/index.php?searchtest=' + ThePage.Title;
  end;
end;

procedure TServerCore.SentenceLockAquire(ALockType: TLockType);
var
  TheCanContinue: Boolean;
begin
  repeat
    EnterCriticalSection(FLock);
    TheCanContinue := ((ALockType = ltRead) and (FLockCount >= 0)) or ((ALockType = ltWrite) and (FLockCount = 0));
    if TheCanContinue then
      if ALockType = ltRead then
        Inc(FLockCount)
      else
        FLockCount := -1;
    LeaveCriticalSection(FLock);
    if TheCanContinue then
      Break;
    if FTimedLock.WaitForLock then
      raise Exception.Create('Waiting for sentence lock failed.');
  until 1 = 0;
end;

procedure TServerCore.SentenceLockRelease(ALockType: TLockType);
begin
  EnterCriticalSection(FLock);
  if ALockType = ltRead then
    Dec(FLockCount)
  else
    FLockCount := 0;
  if FLockCount = 0 then
    FTimedLock.BreakLock;
  LeaveCriticalSection(FLock);
end;

end.

