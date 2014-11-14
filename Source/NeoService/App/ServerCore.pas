unit ServerCore;

interface

uses
  Windows, EntityList, Entity, TypesConsts, SentenceList, GuessObject, SentenceWithGuesses, TimedLock, PosTagger, 
  Hypernym, SkyIdList, SkyLists, ApiRequest, ApiGeneratedSplit, SplitGuessAlgorithm;

type
  TServerCore = class(TObject)
  private
    type
      TLockType = (ltRead, ltWrite);
      TRuleCacheStatus = record
        IRepRulesChanged: Boolean;
        CRepRulesChanged: Boolean;
      end;
  private
    FHypernym: THypernym;
    FIRepList: TEntityList;
    FCRepList: TEntityList;
    FLock: TRTLCriticalSection;
    FLockCount: Integer;
    FTimedLock: TTimedLock;
    FMainThreadHandle: HWND;
    FPosTagger: TPosTagger;
    FSentenceList: TSentenceList;
    FStartDate: TDateTime;
    FIRepRulesChanged: Boolean;
    FCRepRulesChanged: Boolean;

    procedure GetCurrentRepGuess(AWordList: TSkyStringStringList; const ASentenceText: string; AGuessObject: TGuessObject);

    function LoadIRepRules: TSkyIdList;
    function LoadIRepRuleGroups(ARuleList: TSkyIdList): TSkyIdList;
    procedure LoadIRepRuleConditions(AGroupList: TSkyIdList);
    procedure LoadIRepRuleValues(ARuleList: TSkyIdList);

    function LoadCRepRules: TSkyIdList;
    function LoadCRepRuleGroups(ARuleList: TSkyIdList): TSkyIdList;
    procedure LoadCRepRuleConditions(AGroupList: TSkyIdList);

    procedure SentenceLockAquire(ALockType: TLockType);
    procedure SentenceLockRelease(ALockType: TLockType);
    function GetRuleCacheStatus: TRuleCacheStatus;
    procedure ReloadIRepRules;
    procedure ReloadCRepRules;
  public
    constructor Create;
    destructor Destroy; override;

    class function GetInstance: TServerCore;
    class procedure EndInstance;
    procedure ReloadSentences;

    // when the IRep rules have changed the server application must be notified and they will be reloaded
    // on next cache reload
    property RuleCacheStatus: TRuleCacheStatus read GetRuleCacheStatus;
    property MainThreadHandle: HWND read FMainThreadHandle write FMainThreadHandle;

    // api commands - please keep sorted
    function ApiGenerateProtoGuess(ARequest: TApiRequestGenerateProtoGuess): TApiGeneratedSplit;
    procedure ApiGenerateRep(const ASentenceText, AnApiKey: string; BOutputSentence: Boolean; out ARep, ASentence: string);
    function ApiSentenceMatch(ARequest: TApiRequestSentenceMatch): TApiGeneratedSplit;

    // user commands - please keep sorted
    function GetFullSentencesForPageId(APageId: TId): TEntities;
    function GetPosForPage(const APage: string; AnUseModifiedPos: Boolean): TObjects;
    function GetPosForSentences(SomeSentences: TEntities; AnUseModifiedPos: Boolean): TEntities;
    function GuessRepsForSentenceId(ASentenceId: TId; AGuessCRep: Boolean = False): TGuessObject;
    procedure PageAdd(ACategoryId: TId; const ATitle, ABody: string);
    procedure PageEdit(APageId, ACategoryId: TId; const ATitle, ABody: string);
    procedure PredictAfterSplit(SomeSentences: TEntities);
    procedure Search(const ASearchString: string; var AnOffset: Integer; out SomePages: TEntities; out APageCount: Integer);
    function SplitSentence(ASentenceId: TId; const ANewText: string; BCanCreateProto: Boolean): TEntities;
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
  SysUtils, AppUnit, AppSQLServerQuery, BaseConnection, EntityWithName, TypesFunctions, SentenceBase,
  SentenceSplitter, CRep, RepDecoder, SearchPage, RepGroup, IRepRuleGroup, LoggerUnit, IRepRule, IRepRuleValue,
  IRepRuleCondition, CRepRule, CRepRuleCondition, CRepRuleGroup, AppConsts, Proto, SentenceAlgorithm,
  SentenceListElement, Split, PageBase, OrderInPage, BaseQuery, CRepHighlight, SplitGuess, ApiGeneratedSplitFull;

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

function TServerCore.ApiGenerateProtoGuess(ARequest: TApiRequestGenerateProtoGuess): TApiGeneratedSplit;
var
  TheSplitAlgorithm: TSplitGuessAlgorithm;
  TheSplitGuess: TSplitGuess;
begin
  TheSplitGuess := nil;
  TheSplitAlgorithm := TSplitGuessAlgorithm.Create(FHypernym, FPosTagger);
  try
    TheSplitAlgorithm.LoadProtosFromDatabase;
    TheSplitAlgorithm.SepWeight := ARequest.SepWeight;
    TheSplitAlgorithm.SplitThreshold := ARequest.SplitThreshold;
    TheSplitAlgorithm.UseExact := ARequest.UseExact;
    TheSplitGuess := TheSplitAlgorithm.GenerateProtoGuess(ARequest.SentenceText, ARequest.MaxIterations);
    if ARequest.FullDetails then
      Result := TApiGeneratedSplitFull.CreateFromSplitGuess(TheSplitGuess)
    else
      Result := TApiGeneratedSplit.CreateFromSplitGuess(TheSplitGuess);
  finally
    TheSplitAlgorithm.Free;
    TheSplitGuess.Free;
  end;
end;

procedure TServerCore.GetCurrentRepGuess(AWordList: TSkyStringStringList; const ASentenceText: string; AGuessObject: TGuessObject);
var
  TheSentenceBase: TSentenceBase;
  TheSentenceList: TSentenceList;
  TheSentencePos: string;
  TheSplitter: TSentenceSplitter;
  TheSentences: TEntities;
  I: Integer;
begin
  TheSplitter := nil;
  TheSentenceList := TSentenceList.Create;
  try
    TheSplitter := TSentenceSplitter.Create;
    TheSentenceList.Hypernym := FHypernym;
    TheSplitter.SentenceSplitWords(ASentenceText);
    TheSentences := TAppSQLServerQuery.GetSplitSentences;
    for I := 0 to High(TheSentences) do
    begin
      TheSentenceBase := TheSentences[I] as TSentenceBase;
      TheSplitter.SentenceSplitWords(TheSentenceBase.Name);
      TheSentencePos := FPosTagger.GetTagsForWords(TheSplitter, True);
      TheSentenceList.AddSentence(TheSplitter.WordList, TheSentenceBase.Id, TheSentenceBase.Name,
        TheSentenceBase.Rep, TheSentenceBase.SRep, TheSentencePos);
    end;
    TheSentenceList.GetRepGuess(AWordList, ASentenceText,
      FPosTagger.GetTagsForString(ASentenceText), 1, False, AGuessObject, True);
  finally
    TheSentenceList.Free;
    TheSplitter.Free;
    TEntity.FreeEntities(TheSentences);
  end;
end;

procedure TServerCore.ApiGenerateRep(const ASentenceText, AnApiKey: string; BOutputSentence: Boolean; out ARep, ASentence: string);
var
  TheGuessObject: TGuessObject;
  TheSplitter: TSentenceSplitter;
begin
  TheGuessObject := nil;
  TheSplitter := TSentenceSplitter.Create;
  try
    TheSplitter.SentenceSplitWords(ASentenceText);
    TheGuessObject := TGuessObject.Create;
    if ConstUseCacheForRepGuess then
    begin
      SentenceLockAquire(ltRead);
      try
        FSentenceList.GetRepGuess(TheSplitter.WordList, ASentenceText,
          FPosTagger.GetTagsForString(ASentenceText), 1, False, TheGuessObject, True);
      finally
        SentenceLockRelease(ltRead);
      end;
    end else
      GetCurrentRepGuess(TheSplitter.WordList, ASentenceText, TheGuessObject);
    if BOutputSentence then
      ASentence := TheGuessObject.MatchSentenceD
    else
      ASentence := '';
    ARep := TheGuessObject.RepGuessD;
  finally
    TheGuessObject.Free;
    TheSplitter.Free;
  end;
end;

function TServerCore.ApiSentenceMatch(ARequest: TApiRequestSentenceMatch): TApiGeneratedSplit;
var
  TheSplitAlgorithm: TSplitGuessAlgorithm;
  TheSplitGuess: TSplitGuess;
begin
  TheSplitGuess := nil;
  TheSplitAlgorithm := TSplitGuessAlgorithm.Create(FHypernym, FPosTagger);
  try
    TheSplitAlgorithm.SepWeight := ARequest.SepWeight;
    TheSplitAlgorithm.SplitThreshold := 0;
    TheSplitAlgorithm.UseExact := True;
    TheSplitGuess := TheSplitAlgorithm.GetSentenceMatch(ARequest.Sentence1Text, ARequest.Sentence2Text);
    Result := TApiGeneratedSplitFull.CreateFromSplitGuess(TheSplitGuess);
  finally
    TheSplitAlgorithm.Free;
    TheSplitGuess.Free;
  end;
end;

procedure TServerCore.CacheReload;
var
  TheCount: Integer;
  TheStatus: TRuleCacheStatus;
begin
  TheCount := TAppSQLServerQuery.GetFinishedStoriesCount;
  if(TheCount <> TheCount) then;// prevent delphi warning when const below is false
  if ConstUseCacheForRepGuess and (TheCount <> FSentenceList.SentenceCount) then
    ReloadSentences;
  TheStatus := RuleCacheStatus;
  if TheStatus.IRepRulesChanged then
//    ReloadIRepRules;
  if TheStatus.CRepRulesChanged then
//    ReloadCRepRules;
end;

procedure TServerCore.ReloadCRepRules;
var
  TheGroupList: TSkyIdList;
  TheCRepList: TSkyIdList;
begin
  Exit;
  TheCRepList := LoadCRepRules;
  try
    TheGroupList := LoadCRepRuleGroups(TheCRepList);
    try
      LoadCRepRuleConditions(TheGroupList);
    finally
      TheGroupList.Free;
    end;
    SentenceLockAquire(ltWrite);
    try
      FCRepList.Sorted := False;
      FCRepList.Clear;
      FCRepList.AddMultiple(TheCRepList.GetAllObjects, nil);
      TheCRepList.OwnsObjects := False;
      FCRepList.Sort(TCRepRule.Tok_Order);
      FCRepRulesChanged := False;
    finally
      SentenceLockRelease(ltWrite);
    end;
  finally
    TheCRepList.Free;
  end;
end;

procedure TServerCore.ReloadSentences;
var
  TheHyperNyms, TheSentences: TEntities;
  TheSentenceBase: TSentenceBase;
  TheSentencePos: string;
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
        TheSentencePos := FPosTagger.GetTagsForWords(TheSplitter, True);
        FSentenceList.AddSentence(TheSplitter.WordList, TheSentenceBase.Id, TheSentenceBase.Name,
          TheSentenceBase.Rep, TheSentenceBase.SRep, TheSentencePos);
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
  FTimedLock := TTimedLock.Create(ConstAppName + 'SentenceUpdate');
  FTimedLock.LockInterval := 10000; // 10 seconds
  FLockCount := 0;
  FIRepRulesChanged := True;
  FCRepRulesChanged := True;
  FIRepList := TEntityList.Create(False, True);
  FCRepList := TEntityList.Create(False, True);
  InitializeCriticalSection(FLock);
end;

destructor TServerCore.Destroy;
begin
  DeleteCriticalSection(FLock);
  FTimedLock.Free;
  FHypernym.Free;
  FSentenceList.Free;
  FPosTagger.Free;
  FIRepList.Free;
  FCRepList.Free;
  App.RemoveDefaultDatabaseConnection;
  inherited;
end;

class procedure TServerCore.EndInstance;
begin
  FreeAndNil(_Core);
end;

function TServerCore.GetFullSentencesForPageId(APageId: TId): TEntities;
var
  TheSentence: TSentenceWithGuesses;
  TheSplitter: TSentenceSplitter;
  I: Integer;
begin
  Result := TAppSQLServerQuery.GetFullSentencesForPageId(APageId);
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
    TheSentenceSplitter.PageSplitProtos(APage);
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

function TServerCore.GetRuleCacheStatus: TRuleCacheStatus;
begin
  SentenceLockAquire(ltRead);
  try
    Result.IRepRulesChanged := FIRepRulesChanged;
    Result.CRepRulesChanged := FCRepRulesChanged;
  finally
    SentenceLockRelease(ltRead);
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
  if App.Settings.UseRepValidator then
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
  TheSentence: TSentenceBase;
  TheSplitter: TSentenceSplitter;
begin
  TheSplitter := nil;
  TheSentence := TAppSQLServerQuery.GetSentenceBaseById(ASentenceId);
  try
    TheSplitter := TSentenceSplitter.Create;
    TheSplitter.SentenceSplitWords(TheSentence.Name);
    Result := TGuessObject.Create;
    try
      Result.Id := ASentenceId;
      if ConstUseCacheForRepGuess then
      begin
        SentenceLockAquire(ltRead);
        try
          FSentenceList.GetRepGuess(TheSplitter.WordList, TheSentence.Name,
            FPosTagger.GetTagsForString(TheSentence.Name), 1, False, Result);
        finally
          SentenceLockRelease(ltRead);
        end;
      end else
        GetCurrentRepGuess(TheSplitter.WordList, TheSentence.Name, Result);
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

function TServerCore.LoadCRepRules: TSkyIdList;
var
  TheEntities: TEntities;
  I: Integer;
begin
  Result := TSkyIdList.Create;
  try
    Result.Sorted := False;
    TheEntities := TAppSQLServerQuery.GetCRepRules;
    for I := 0 to High(TheEntities) do
      Result.AddObject(TheEntities[I].Id, TheEntities[I]);
    Result.Sorted := True;
  except
    Result.Free;
    raise;
  end;
end;

function TServerCore.LoadCRepRuleGroups(ARuleList: TSkyIdList): TSkyIdList;
var
  TheEntities: TEntities;
  TheGroup: TCRepRuleGroup;
  TheParentGroup: TCRepRuleGroup;
  TheRule: TCRepRule;
  I: Integer;
begin
  Result := TSkyIdList.Create(False);
  try
    Result.Sorted := False;
    TheEntities := App.SQLConnection.SelectAll(TCRepRuleGroup);
    for I := 0 to High(TheEntities) do
      Result.AddObject(TheEntities[I].Id, TheEntities[I]);
    Result.Sorted := True;
    I := 0;
    while I < Result.Count do
    begin
      TheGroup := Result.Objects[I] as TCRepRuleGroup;
      if (TheGroup.ParentId = IdNil) or (TheGroup.ParentId = TheGroup.Id) then
      begin
        TheRule := ARuleList.ObjectOfValueDefault[TheGroup.RuleId, nil] as TCRepRule;
        if TheRule = nil then
        begin
          TLogger.Warn(Self, ['LoadCRepRuleGroups', 'Orpaned CRepRuleGroup', IdToStr(TheGroup.Id)]);
          TheGroup.Free;
          Result.DeleteFromIndex(I);
          Continue;
        end;
        if TheRule.MainRuleGroup <> nil then
        begin
          TLogger.Warn(Self, ['LoadCRepRuleGroups', 'Duplicate main CRepRuleGroup for CRepRule', IdToStr(TheRule.Id)]);
          TheGroup.Free;
          Result.DeleteFromIndex(I);
          Continue;
        end;
        TheRule.MainRuleGroup := TheGroup
      end else
      begin
        TheParentGroup := Result.ObjectOfValueDefault[TheGroup.ParentId, nil] as TCRepRuleGroup;
        if TheParentGroup = nil then
        begin
          TLogger.Warn(Self, ['LoadCRepRuleGroups', 'Orpaned CRepRuleGroup', IdToStr(TheGroup.Id)]);
          TheGroup.Free;
          Result.DeleteFromIndex(I);
          Continue;
        end;
        TheParentGroup.Members.Add(TheGroup);
      end;
      Inc(I);
    end;
  except
    Result.Free;
    raise;
  end;
end;

procedure TServerCore.LoadCRepRuleConditions(AGroupList: TSkyIdList);
var
  TheCondition: TCRepRuleCondition;
  TheGroup: TCRepRuleGroup;
  TheEntities: TEntities;
  I: Integer;
begin
  TheEntities := App.SQLConnection.SelectAll(TCRepRuleCondition);
  for I := 0 to High(TheEntities) do
  begin
    TheCondition := TheEntities[I] as TCRepRuleCondition;
    TheGroup := AGroupList.ObjectOfValueDefault[TheCondition.GroupId, nil] as TCRepRuleGroup;
    if TheGroup = nil then
    begin
      TLogger.Warn(Self, ['LoadCRepRuleConditions', 'Orpaned CRepRuleCondition', IdToStr(TheCondition.Id)]);
      TheCondition.Free;
      Continue;
    end;
    TheGroup.Members.Add(TheCondition);
  end;
end;

function TServerCore.LoadIRepRules: TSkyIdList;
var
  TheEntities: TEntities;
  I: Integer;
begin
  Result := TSkyIdList.Create;
  try
    Result.Sorted := False;
    TheEntities := TAppSQLServerQuery.GetIRepRules;
    for I := 0 to High(TheEntities) do
      Result.AddObject(TheEntities[I].Id, TheEntities[I]);
    Result.Sorted := True;
  except
    Result.Free;
    raise;
  end;
end;

function TServerCore.LoadIRepRuleGroups(ARuleList: TSkyIdList): TSkyIdList;
var
  TheEntities: TEntities;
  TheGroup: TIRepRuleGroup;
  TheParentGroup: TIRepRuleGroup;
  TheRule: TIRepRule;
  I: Integer;
begin
  Result := TSkyIdList.Create(False);
  try
    Result.Sorted := False;
    TheEntities := App.SQLConnection.SelectAll(TIRepRuleGroup);
    for I := 0 to High(TheEntities) do
      Result.AddObject(TheEntities[I].Id, TheEntities[I]);
    Result.Sorted := True;
    I := 0;
    while I < Result.Count do
    begin
      TheGroup := Result.Objects[I] as TIRepRuleGroup;
      if TheGroup.ParentId = IdNil then
      begin
        TheRule := ARuleList.ObjectOfValueDefault[TheGroup.RuleId, nil] as TIRepRule;
        if TheRule = nil then
        begin
          TLogger.Warn(Self, ['LoadIRepRuleGroups', 'Orpaned IRepRuleGroup', IdToStr(TheGroup.Id)]);
          TheGroup.Free;
          Result.DeleteFromIndex(I);
          Continue;
        end;
        if TheRule.MainRuleGroup <> nil then
        begin
          TLogger.Warn(Self, ['LoadIRepRuleGroups', 'Duplicate main IRepRuleGroup for IRepRule', IdToStr(TheRule.Id)]);
          TheGroup.Free;
          Result.DeleteFromIndex(I);
          Continue;
        end;
        TheRule.MainRuleGroup := TheGroup;
      end else
      begin
        TheParentGroup := Result.ObjectOfValueDefault[TheGroup.ParentId, nil] as TIRepRuleGroup;
        if TheParentGroup = nil then
        begin
          TLogger.Warn(Self, ['LoadIRepRuleGroups', 'Orpaned IRepRuleGroup', IdToStr(TheGroup.Id)]);
          TheGroup.Free;
          Result.DeleteFromIndex(I);
          Continue;
        end;
        TheParentGroup.Members.Add(TheGroup);
      end;
      Inc(I);
    end;
  except
    Result.Free;
    raise;
  end;
end;

procedure TServerCore.LoadIRepRuleConditions(AGroupList: TSkyIdList);
var
  TheCondition: TIRepRuleCondition;
  TheGroup: TIRepRuleGroup;
  TheEntities: TEntities;
  I: Integer;
begin
  TheEntities := App.SQLConnection.SelectAll(TIRepRuleCondition);
  for I := 0 to High(TheEntities) do
  begin
    TheCondition := TheEntities[I] as TIRepRuleCondition;      
    TheGroup := AGroupList.ObjectOfValueDefault[TheCondition.GroupId, nil] as TIRepRuleGroup;
    if TheGroup = nil then
    begin
      TLogger.Warn(Self, ['LoadIRepRuleConditions', 'Orpaned IRepRuleCondition', IdToStr(TheCondition.Id)]);
      TheCondition.Free;
      Continue;
    end;
    TheGroup.Members.Add(TheCondition);
  end;    
end;

procedure TServerCore.LoadIRepRuleValues(ARuleList: TSkyIdList);
var
  TheEntities: TEntities;
  TheRule: TIRepRule;
  TheValue: TIRepRuleValue;
  I: Integer;
begin
  TheEntities := App.SQLConnection.SelectAll(TIRepRuleValue);
  for I := 0 to High(TheEntities) do
  begin
    TheValue := TheEntities[I] as TIRepRuleValue;      
    TheRule := ARuleList.ObjectOfValueDefault[TheValue.RuleId, nil] as TIRepRule;
    if TheRule = nil then
    begin
      TLogger.Warn(Self, ['LoadIRepRuleValues', 'Orpaned IRepRuleValue', IdToStr(TheValue.Id)]);
      TheValue.Free;
      Continue;
    end;
    TheRule.Values.Add(TheValue);
  end;    
end;

procedure TServerCore.ReloadIRepRules;
var
  TheGroupList: TSkyIdList;
  TheIRepList: TSkyIdList;
begin
  Exit;
  TheIRepList := LoadIRepRules;
  try
    TheGroupList := LoadIRepRuleGroups(TheIRepList);
    try
      LoadIRepRuleConditions(TheGroupList);
    finally
      TheGroupList.Free;
    end;
    LoadIRepRuleValues(TheIRepList);
    SentenceLockAquire(ltWrite);
    try
      FIRepList.Sorted := False;
      FIRepList.Clear;
      FIRepList.AddMultiple(TheIRepList.GetAllObjects, nil);
      TheIRepList.OwnsObjects := False;
      FIRepList.Sort(TIRepRule.Tok_Order);
      FIRepRulesChanged := False;
    finally
      SentenceLockRelease(ltWrite);
    end;
  finally
    TheIRepList.Free;
  end;
end;

procedure TServerCore.PageAdd(ACategoryId: TId; const ATitle, ABody: string);
var
  ThePage: TPageBase;
begin
  ThePage := TPageBase.Create;
  try
    ThePage.CategoryId := ACategoryId;
    ThePage.Name := ATitle;
    ThePage.Body := ABody;
    ThePage.Source := 'http://neoxplora.com';
    App.SQLConnection.InsertEntity(ThePage);
  finally
    ThePage.Free;
  end;
end;

procedure TServerCore.PageEdit(APageId, ACategoryId: TId; const ATitle, ABody: string);
var
  TheOrderInPage: TOrderInPage;
  ThePage: TPageBase;
  TheProto: TProto;
  TheSentence:TSentenceBase;
  TheSplitter: TSentenceSplitter;
  I: Integer;
begin
  ThePage := nil;
  TheProto := nil;
  TheSentence := nil;
  TheSplitter := TSentenceSplitter.Create;
  try
    TheSplitter.PageSplitProtos(ABody);
    if TheSplitter.WordList.Count = 0 then
      raise Exception.Create('Page body must contain at least one sentence');

    ThePage := App.SQLConnection.SelectById(TPageBase, APageId) as TPageBase;
    TBaseQuery.DeleteOneToMany(App.SQLConnection, TCRep, TCRep.Tok_PageId.PropertyName, APageId);
    TBaseQuery.DeleteOneToMany(App.SQLConnection, TProto, TProto.Tok_PageId.PropertyName, APageId);
    TBaseQuery.DeleteOneToMany(App.SQLConnection, TSentenceBase, TSentenceBase.Tok_PageId.PropertyName, APageId);

    ThePage.Status := psFinishedGenerate;
    ThePage.CategoryId := ACategoryId;
    ThePage.Name := ATitle;
    ThePage.Body := ABody;
    App.SQLConnection.UpdateEntity(ThePage);

    TheProto := TProto.Create;
    TheSentence := TSentenceBase.Create;
    TheOrderInPage := TOrderInPage.Create;
    TheProto.PageId := APageId;
    TheSentence.PageId := APageId;
    TheOrderInPage.PageId := APageId;
    TheOrderInPage.Order := 0;

    for I := 0 to TheSplitter.WordList.Count - 1 do
    begin
      TheProto.Name := TheSplitter.WordList[I];
      TheProto.Order := I + 1;

      TheOrderInPage.Order := TheOrderInPage.Order + 1;
      TheOrderInPage.SentenceId := IdNil;
      TheProto.Id := App.SQLConnection.InsertEntity(TheProto);
      TheProto.MainProtoId := TheProto.Id;
      App.SQLConnection.UpdateEntity(TheProto);
      TheOrderInPage.ProtoId := TheProto.Id;
      TheOrderInPage.Indentation := 0;
      App.SQLConnection.InsertEntity(TheOrderInPage);

      TheSentence.MainProtoId := TheOrderInPage.ProtoId;
      TheSentence.ProtoId := TheSentence.MainProtoId;
      TheSentence.Name := TheProto.Name;
      TheSentence.Order := 1;

      TheOrderInPage.Order := TheOrderInPage.Order + 1;
      TheOrderInPage.SentenceId := App.SQLConnection.InsertEntity(TheSentence);
      TheOrderInPage.ProtoId := IdNil;
      TheOrderInPage.Indentation := 1;
      App.SQLConnection.InsertEntity(TheOrderInPage);
    end;
  finally
    TheSplitter.Free;
    ThePage.Free;
    TheProto.Free;
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

function TServerCore.SplitSentence(ASentenceId: TId; const ANewText: string; BCanCreateProto: Boolean): TEntities;
var
  TheNewSentence: TSentenceBase;
  TheOrderInPage: TOrderInPage;
  TheProto: TProto;
  TheSentence: TSentenceBase;
  TheSplitter: TSentenceSplitter;
  TheResults: TEntityList;
  TheWordsSplitter: TSentenceSplitter;
  I: Integer;
begin
  TheSplitter := nil;
  TheOrderInPage := nil;
  TheSentence := App.SQLConnection.SelectById(TSentenceBase, ASentenceId) as TSentenceBase;
  try
    TheSplitter := TSentenceSplitter.Create;
    TheSplitter.PageSplitProtos(ANewText);

    TBaseQuery.DeleteOneToMany(App.SQLConnection, TCRep, TCRep.Tok_PageId.PropertyName, TheSentence.PageId);
    TBaseQuery.DeleteOneToMany(App.SQLConnection, TCRepHighlight, TCRepHighlight.Tok_PageId.PropertyName, TheSentence.PageId);

    // Delete from CREp, CRepHighlight
    case TheSplitter.WordList.Count of
      0: begin
        if TAppSQLServerQuery.GetProtoChildCount(TheSentence.ProtoId) = 1 then
        begin
          TheProto := App.SQLConnection.SelectById(TProto, TheSentence.ProtoId) as TProto;
          try
            TheSentence.Name := TheProto.Name;
            if TheProto.ParentId <> IdNil then
            begin
              App.SQLConnection.DeleteEntity(TheProto);
              TBaseQuery.DeleteOneToMany(App.SQLConnection, TOrderInPage, TOrderInPage.Tok_ProtoId.PropertyName, TheProto.Id);
              TheSentence.ProtoId := TheProto.ParentId;
              TheSentence.Order := TheProto.Order;
              TheOrderInPage := TAppSQLServerQuery.GetOrderInPageForPageAndSentenceId(TheSentence.PageId, TheSentence.Id);
              TheOrderInPage.Indentation := TheOrderInPage.Indentation - 1;
              App.SQLConnection.UpdateEntity(TheOrderInPage);
            end;
          finally
            TheProto.Free;
          end;
          App.SQLConnection.UpdateEntity(TheSentence);
          Result := TEntityWithName.Create(TheSentence.Id, TheSentence.Name).GetAsArray;
        end else
        begin
          App.SQLConnection.DeleteEntity(TheSentence);
          TBaseQuery.DeleteOneToMany(App.SQLConnection, TOrderInPage, TOrderInPage.Tok_SentenceId.PropertyName, TheSentence.Id);
        end;
        Result := nil;
      end;
      1: begin
        if (Trim(TheSentence.Name) <> Trim(TheSplitter.WordList[0])) and (TheSentence.Status > ssTrainedSplit) then
          TheSentence.Status := ssTrainedSplit;
        TheSentence.Name := TheSplitter.WordList[0];
        App.SQLConnection.UpdateEntity(TheSentence);
        Result := TEntityWithName.Create(TheSentence.Id, TheSentence.Name).GetAsArray;
      end;
      else begin
        TheOrderInPage := TAppSQLServerQuery.GetOrderInPageForPageAndSentenceId(TheSentence.PageId, TheSentence.Id);
        App.SQLConnection.DeleteEntity(TheSentence);
        App.SQLConnection.DeleteEntity(TheOrderInPage);

        if BCanCreateProto then
          TAppSQLServerQuery.IncreasePageOrderForPageId(TheSentence.PageId, TheOrderInPage.Order, TheSplitter.WordList.Count)
        else
          TAppSQLServerQuery.IncreasePageOrderForPageId(TheSentence.PageId, TheOrderInPage.Order, TheSplitter.WordList.Count - 1);

        if BCanCreateProto then
        begin
          TheProto := TProto.Create;
          try
            TheProto.MainProtoId := TheSentence.MainProtoId;
            TheProto.Name := TheSentence.Name;
            TheProto.Order := TheSentence.Order;
            TheProto.PageId := TheSentence.PageId;
            TheProto.ParentId := TheSentence.ProtoId;
            TheSentence.ProtoId := App.SQLConnection.InsertEntity(TheProto);
            TheSentence.Order := 1;
            TheOrderInPage.SentenceId := IdNil;
            TheOrderInPage.ProtoId := TheSentence.ProtoId;
            App.SQLConnection.InsertEntity(TheOrderInPage);
            TheOrderInPage.ProtoId := IdNil;
            TheOrderInPage.Order := TheOrderInPage.Order + 1;
            TheOrderInPage.Indentation := TheOrderInPage.Indentation + 1;
          finally
            TheProto.Free;
          end;
        end else
        begin
          TAppSQLServerQuery.UpdateSentenceOrderForPage(TheSentence.PageId, TheSentence.Order, TheSplitter.WordList.Count - 1);
          TAppSQLServerQuery.UpdateProtoOrderForPage(TheSentence.PageId, TheSentence.Order, TheSplitter.WordList.Count - 1);
        end;

        TheResults := nil;
        TheWordsSplitter := TSentenceSplitter.Create;
        try
          TheResults := TEntityList.Create(False, True);
          for I := 0 to TheSplitter.WordList.Count - 1 do
          begin
            TheNewSentence := TheSentence.CreateACopy as TSentenceBase;
            try
              TheNewSentence.Name := TheSplitter.WordList[I];
              if TheNewSentence.Status > ssTrainedSplit then
                TheNewSentence.Status := ssTrainedSplit;
              TheWordsSplitter.SentenceSplitWords(TheNewSentence.Name);
              TheNewSentence.Pos := FPosTagger.GetTagsForWords(TheWordsSplitter, True);
              TheNewSentence.Id := App.SQLConnection.InsertEntity(TheNewSentence);
              TheResults.Add(TEntityWithName.Create(TheNewSentence.Id, TheNewSentence.Name));
              TheOrderInPage.SentenceId := TheNewSentence.Id;
              App.SQLConnection.InsertEntity(TheOrderInPage);
              TheOrderInPage.Order := TheOrderInPage.Order + 1;
            finally
              TheNewSentence.Free;
            end;
            TheSentence.Order := TheSentence.Order + 1;
          end;
          Result := TheResults.GetAllEntities;
          // if there was an exception before this points the results
          // would be freed also which is correct
          TheResults.OwnsItems := False;
        finally
          TheResults.Free;
          TheWordsSplitter.Free;
        end;
      end;
    end;
  finally
    TheSentence.Free;
    TheSplitter.Free;
    TheOrderInPage.Free;
  end;
end;

end.
