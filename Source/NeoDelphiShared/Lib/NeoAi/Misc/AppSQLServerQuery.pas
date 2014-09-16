unit AppSQLServerQuery;

interface

uses
  BaseQuery, TypesConsts, Entity, DBSQLQuery, SentenceBase;

type
  TAppSQLServerQuery = class(TBaseQuery)
  public
    class function GetCRepRules: TEntities;
    class function GetFinishedStoriesCount: Integer;
    class function GetFullSentencesForPageId(APageId: TId): TEntities;
    class function GetHypernyms: TEntities;
    class function GetIRepRules: TEntities;
    class function GetSearchPagesByOffset(ASearch: string; AnOffset: Integer): TEntities;
    class function GetSentenceBaseById(AnId: TId): TSentenceBase;
    class function GetSplitSentences: TEntities;
    class function GetSplitProtos: TEntities;
    class function GetPageReps(APageId, ASentenceId: TId): TEntities;
    class function GetSentencesForProtoId(AProtoId: TId): TEntities;
    class function GetTotalPageCount(const ASearch: string): Integer;
    class function GetUntrainedStories: TEntities;
    class procedure UpdateSentenceOrderForPage(APageId: TId; AnOrder, ACount: Integer);
  private
    class function QueryGetCRepRules: TDBSQLQuery;
    class function QueryGetFinishedStoriesCount: TDBSQLQuery;
    class function QueryGetFullSentencesForPageId(APageId: TId): TDBSQLQuery;
    class function QueryGetHypernyms: TDBSQLQuery;
    class function QueryGetIRepRules: TDBSQLQuery;
    class function QueryGetSearchPagesByOffset(AnOffset: Integer): TDBSQLQuery;
    class function QueryGetSentenceBaseById(AnId: TId): TDBSQLQuery;
    class function QueryGetSentencesForProtoId(AnId: TId): TDBSQLQuery;
    class function QueryGetSplitSentences: TDBSQLQuery;
    class function QueryGetSplitProtos: TDBSQLQuery;
    class function QueryGetPageReps(APageId, ASentenceId: TId): TDBSQLQuery;
    class function QueryGetTotalPageCount: TDBSQLQuery;
    class function QueryGetUntrainedStories: TDBSQLQuery;
    class function QueryUpdateSentenceOrderForPage(APageId: TId; AnOrder, ACount: Integer): TDBSQLQuery;
  end;

implementation

uses
  StringArray, AppUnit, EntityWithName, PageBase, TypesFunctions, SentenceWithGuesses, TypInfo, CountData, EntityWithId,
  SearchPage, SysUtils, RepGroup, IRepRule, CRepRule, Proto;

{ TAppSQLServerQuery }

class function TAppSQLServerQuery.GetCRepRules: TEntities;
var
  TheQuery: TDBSQLQuery;
begin
  TheQuery := QueryGetCRepRules;
  Result := App.SQLConnection.SelectQuery([TCRepRule], TheQuery);
  TheQuery.Query.Count := 0;
end;

class function TAppSQLServerQuery.GetFinishedStoriesCount: Integer;
var
  TheQuery: TBaseQuery;
  TheDbQuery: TDBSQLQuery;
begin
  TheDbQuery := QueryGetFinishedStoriesCount;
  TheQuery := TBaseQuery.TranslateDBSQLQuery(App.SQLConnection, TheDbQuery);
  try
    TheDbQuery.Query.Count := 0;
    TheQuery.ParamByName('ASentenceStatus1').AsString := GetEnumName(TypeInfo(TSentenceBase.TStatus), Integer(ssReviewedRep));
    TheQuery.Open;
    Result := TheQuery.ReadCountData;
  finally
    TheQuery.Free;
  end;
end;

class function TAppSQLServerQuery.GetFullSentencesForPageId(APageId: TId): TEntities;
var
  TheDbQuery: TDBSQLQuery;
begin
  TheDbQuery := QueryGetFullSentencesForPageId(APageId);
  Result := App.SQLConnection.SelectQuery([TSentenceWithGuesses], TheDbQuery);
  TheDbQuery.Query.Count := 0;
end;

class function TAppSQLServerQuery.GetIRepRules: TEntities;
var
  TheDbQuery: TDBSQLQuery;
begin
  TheDbQuery := QueryGetIRepRules;
  Result := App.SQLConnection.SelectQuery([TIRepRule], TheDbQuery);
  TheDbQuery.Query.Count := 0;
end;

class function TAppSQLServerQuery.GetHypernyms: TEntities;
var
  TheQuery: TBaseQuery;
  TheDbQuery: TDBSQLQuery;
begin
  TheDbQuery := QueryGetHypernyms;
  TheQuery := TBaseQuery.TranslateDBSQLQuery(App.SQLConnection, TheDbQuery);
  try
    TheDbQuery.Query.Count := 0;
    TheQuery.ParamByName('ASentenceStatus1').AsString := GetEnumName(TypeInfo(TSentenceBase.TStatus), Integer(ssReviewedRep));
    TheQuery.Open;
    Result := TheQuery.ReadMappedEntities([TEntityWithName]);
  finally
    TheQuery.Free;
  end;
end;

class function TAppSQLServerQuery.GetSearchPagesByOffset(ASearch: string; AnOffset: Integer): TEntities;
var
  TheQuery: TBaseQuery;
  TheDbQuery: TDBSQLQuery;
begin
  TheDbQuery := QueryGetSearchPagesByOffset(AnOffset);
  TheQuery := TBaseQuery.TranslateDBSQLQuery(App.SQLConnection, TheDbQuery);
  try
    TheDbQuery.Query.Count := 0;
    TheQuery.ParamByName('ASearch').AsString := '%' + ASearch + '%';
    TheQuery.Open;
    Result := TheQuery.ReadMappedEntities([TSearchPage]);
  finally
    TheQuery.Free;
  end;
end;

class function TAppSQLServerQuery.GetSentenceBaseById(AnId: TId): TSentenceBase;
var
  TheDbQuery: TDBSQLQuery;
begin
  TheDbQuery := QueryGetSentenceBaseById(AnId);
  Result := App.SQLConnection.SelectQuerySingle([TSentenceBase], TheDbQuery, False) as TSentenceBase;
  TheDbQuery.Query.Count := 0;
end;

class function TAppSQLServerQuery.GetSentencesForProtoId(AProtoId: TId): TEntities;
var
  TheDbQuery: TDBSQLQuery;
begin
  TheDbQuery := QueryGetSentencesForProtoId(AProtoId);
  Result := App.SQLConnection.SelectQuery([TSentenceBase], TheDbQuery);
  TheDbQuery.Query.Count := 0;
end;

class function TAppSQLServerQuery.GetSplitProtos: TEntities;
var
  TheQuery: TBaseQuery;
  TheDbQuery: TDBSQLQuery;
begin
  TheDbQuery := QueryGetSplitProtos;
  TheQuery := TBaseQuery.TranslateDBSQLQuery(App.SQLConnection, TheDbQuery);
  try
    TheDbQuery.Query.Count := 0;
    TheQuery.ParamByName('ASentenceStatus1').AsString := GetEnumName(TypeInfo(TSentenceBase.TStatus), Integer(ssReviewedSplit));
    TheQuery.ParamByName('ASentenceStatus2').AsString := GetEnumName(TypeInfo(TSentenceBase.TStatus), Integer(ssTrainedRep));
    TheQuery.ParamByName('ASentenceStatus3').AsString := GetEnumName(TypeInfo(TSentenceBase.TStatus), Integer(ssReviewedRep));
    TheQuery.Open;
    Result := TheQuery.ReadMappedEntities([TProto]);
  finally
    TheQuery.Free;
  end;
end;

class function TAppSQLServerQuery.GetSplitSentences: TEntities;
var
  TheQuery: TBaseQuery;
  TheDbQuery: TDBSQLQuery;
begin
  TheDbQuery := QueryGetSplitSentences;
  TheQuery := TBaseQuery.TranslateDBSQLQuery(App.SQLConnection, TheDbQuery);
  try
    TheDbQuery.Query.Count := 0;
    TheQuery.ParamByName('ASentenceStatus1').AsString := GetEnumName(TypeInfo(TSentenceBase.TStatus), Integer(ssReviewedRep));
    TheQuery.Open;
    Result := TheQuery.ReadMappedEntities([TSentenceBase]);
  finally
    TheQuery.Free;
  end;
end;

class function TAppSQLServerQuery.GetPageReps(APageId, ASentenceId: TId): TEntities;
var
  TheDbQuery: TDBSQLQuery;
begin
  TheDbQuery := QueryGetPageReps(APageId, ASentenceId);
  Result := App.SQLConnection.SelectQuery([TEntityWithName], TheDbQuery);
  TheDbQuery.Query.Count := 0;
end;

class function TAppSQLServerQuery.GetTotalPageCount(const ASearch: string): Integer;
var
  TheDbQuery: TDBSQLQuery;
  TheQuery: TBaseQuery;
  TheCountData: TCountData;
begin
  TheCountData := nil;
  TheDbQuery := QueryGetTotalPageCount;
  TheQuery := TBaseQuery.TranslateDBSQLQuery(App.SQLConnection, TheDbQuery);
  try
    TheDbQuery.Query.Count := 0;
    TheQuery.ParamByName('ASearch').AsString := '%' + ASearch + '%';
    TheQuery.Open;
    TheCountData := TheQuery.ReadSingleValue([TCountData], False) as TCountData;
    Result := TheCountData.Number;
  finally
    TheQuery.Free;
    TheCountData.Free;
  end;
end;

class function TAppSQLServerQuery.GetUntrainedStories: TEntities;
var
  TheDbQuery: TDBSQLQuery;
begin
  TheDbQuery := QueryGetUntrainedStories;
  Result := App.SQLConnection.SelectQuery([TEntityWithId], TheDbQuery);
  TheDbQuery.Query.Count := 0;
end;

class procedure TAppSQLServerQuery.UpdateSentenceOrderForPage(APageId: TId; AnOrder, ACount: Integer);
var
  TheDbQuery: TDBSQLQuery;
begin
  TheDbQuery := QueryUpdateSentenceOrderForPage(APageId, AnOrder, ACount);
  App.SQLConnection.ExecuteQuery(TheDbQuery);
  TheDbQuery.Query.Count := 0;
end;

class function TAppSQLServerQuery.QueryGetUntrainedStories: TDBSQLQuery;
begin
  Result.Name := 'QueryGetUntrainedStories';
  Result.Query := TStringArray.FromArray([
    'select `', TSentenceBase.EntityToken_Id.SQLToken, '` as `', TEntityWithId.EntityToken_Id.SQLToken, '` from `',
    TSentenceBase.SQLToken, '`;'
  ]);
end;

class function TAppSQLServerQuery.QueryUpdateSentenceOrderForPage(APageId: TId; AnOrder, ACount: Integer): TDBSQLQuery;
begin
  Result.Name := 'QueryUpdateSentenceOrderForPage';
  Result.Query := TStringArray.FromArray([
    'update `',
    TSentenceBase.SQLToken,
    '` set `',
    TSentenceBase.Tok_Order.SQLToken,
    '` = `',
    TSentenceBase.Tok_Order.SQLToken,
    '` + ' + IntToStr(ACount) + ' where `',
    TSentenceBase.Tok_PageId.SQLToken,
    '` = ' + IdToStr(APageId) + ' and `',
    TSentenceBase.Tok_Order.SQLToken,
    '` > ' + IntToStr(AnOrder) + ';'
  ]);
end;

class function TAppSQLServerQuery.QueryGetHypernyms: TDBSQLQuery;
begin
  Result.Name := 'QueryGetHypernyms';
  Result.Query := TStringArray.FromArray([
    'select `', TSentenceBase.Tok_SRep.SQLToken, '` as `',
    TEntityWithName.EntityToken_Name.SQLToken,'` from `', TSentenceBase.SQLToken,
    '` where `', TSentenceBase.Tok_Status.SQLToken, '` = :ASentenceStatus1 and (`',
    TSentenceBase.Tok_SRep.SQLToken,'` like ''%eg(%'' or',
    ' `', TSentenceBase.Tok_SRep.SQLToken,'` like ''%part(%'' or `',
    TSentenceBase.Tok_SRep.SQLToken,'` like ''%property(%'');'
  ]);
end;

class function TAppSQLServerQuery.QueryGetIRepRules: TDBSQLQuery;
begin
  Result.Name := 'QueryGetIRepRules';
  Result.Query := TStringArray.FromArray([
    'select * from `',
    TIRepRule.SQLToken,
    '` order by `', TIRepRule.Tok_Order.SQLToken, '`;'
  ]);
end;

class function TAppSQLServerQuery.QueryGetSearchPagesByOffset(AnOffset: Integer): TDBSQLQuery;
begin
  Result.Name := 'QueryGetSearchPagesByOffset';
  Result.Query := TStringArray.FromArray([
    'select `', TPageBase.Tok_Id.SQLToken, '` as `', TSearchPage.EntityToken_Id.SQLToken,
    '`, `', TPageBase.Tok_Name.SQLToken, '` as `', TSearchPage.Tok_Title.SQLToken,
    '`, `', TPageBase.Tok_Body.SQLToken, '` as `', TSearchPage.Tok_Body.SQLToken,
    '` from `', TPageBase.SQLToken,
    '` where `', TPageBase.Tok_Body.SQLToken, '` like :ASearch limit ' + IntToStr(AnOffset) + ',10;'
  ]);
end;

class function TAppSQLServerQuery.QueryGetSentenceBaseById(AnId: TId): TDBSQLQuery;
begin
  Result.Name := 'QueryGetSentenceBaseById';
  Result.Query := TStringArray.FromArray([
    'select * from `', TSentenceBase.SQLToken,
    '` where `', TSentenceBase.EntityToken_Id.SQLToken,'` = ' + IdToStr(AnId)
  ]);
end;

class function TAppSQLServerQuery.QueryGetSentencesForProtoId(AnId: TId): TDBSQLQuery;
begin
  Result.Name := 'QueryGetSentencesForProtoId';
  Result.Query := TStringArray.FromArray([
    'select `', TSentenceBase.EntityToken_Id.SQLToken, '`, `', TSentenceBase.Tok_Name.SQLToken,
    '`, `', TSentenceBase.Tok_Rep.SQLToken, '`, `', TSentenceBase.Tok_SRep.SQLToken,
    '`, `', TSentenceBase.Tok_Pos.SQLToken, '`',
    ' from `', TSentenceBase.SQLToken, '` where `', TSentenceBase.Tok_ProtoId.SQLToken,
    '` = ' + IdToStr(AnId) + ' order by `', TSentenceBase.Tok_Order.SQLToken, '`'
  ]);
end;

class function TAppSQLServerQuery.QueryGetSplitSentences: TDBSQLQuery;
begin
  Result.Name := 'QueryGetSplitSentences';
  Result.Query := TStringArray.FromArray([
    'select `', TSentenceBase.EntityToken_Id.SQLToken, '`, `', TSentenceBase.Tok_Name.SQLToken,
    '`, `', TSentenceBase.Tok_Rep.SQLToken, '`, `', TSentenceBase.Tok_SRep.SQLToken,
    '`, `', TSentenceBase.Tok_Pos.SQLToken, '`',
    ' from `', TSentenceBase.SQLToken, '` where `', TSentenceBase.Tok_Status.SQLToken,
    '` = :ASentenceStatus1 and trim(`',
    TSentenceBase.Tok_Rep.SQLToken, '`) <> '''''
  ]);
end;

class function TAppSQLServerQuery.QueryGetSplitProtos: TDBSQLQuery;
begin
  Result.Name := 'QueryGetSplitProtos';
  Result.Query := TStringArray.FromArray([
    'select pr.`', TProto.Tok_Id.SQLToken, '`, pr.`', TProto.Tok_Name.SQLToken,
    '` from `', TSentenceBase.SQLToken, '` se inner join `',
    TProto.SQLToken, '` pr on pr.`', TProto.Tok_Id.SQLToken, '` = se.`',
    TSentenceBase.Tok_ProtoId.SQLToken, '` where se.`', TSentenceBase.Tok_Status.SQLToken,
    '` in (:ASentenceStatus1, :ASentenceStatus2, :ASentenceStatus3)'
  ]);
end;

class function TAppSQLServerQuery.QueryGetCRepRules: TDBSQLQuery;
begin
  Result.Name := 'QueryGetCRepRules';
  Result.Query := TStringArray.FromArray([
    'select * from `',
    TCRepRule.SQLToken,
    '` order by `', TCRepRule.Tok_Order.SQLToken, '`;'
  ]);
end;

class function TAppSQLServerQuery.QueryGetFinishedStoriesCount: TDBSQLQuery;
var
  TheQuery: TDBSQLQuery;
begin
  Result.Name := 'QueryGetFinishedStoriesCount';
  Result.Query := TStringArray.FromArray([
    'select count(*) as `',
    TCountData.EntityToken_Number.SQLToken,
    '` from ('
  ]);
  TheQuery := QueryGetSplitSentences;
  Result.Query.Add(TheQuery.Query);
  TheQuery.Query.Count := 0;
  Result.Query.Add(TStringArray.FromArray([
    ')a;'
  ]));
end;

class function TAppSQLServerQuery.QueryGetFullSentencesForPageId(APageId: TId): TDBSQLQuery;
begin
  Result.Name := 'QueryGetFullSentencesForPageId';
  Result.Query := TStringArray.FromArray([
    'select * from `', TSentenceWithGuesses.SQLToken,
    '` where `', TSentenceWithGuesses.Tok_PageId.SQLToken, '` = ' + IdToStr(APageId) + ' order by `',
    TSentenceWithGuesses.EntityToken_Id.SQLToken
  ]);
end;

class function TAppSQLServerQuery.QueryGetPageReps(APageId, ASentenceId: TId): TDBSQLQuery;
begin
  Result.Name := 'QueryGetPageReps';
  Result.Query := TStringArray.FromArray([
    'select `', TSentenceBase.Tok_Rep.SQLToken, '` as `',
    TEntityWithName.EntityToken_Name.SQLToken,'` from `', TSentenceBase.SQLToken,
    '` where `', TSentenceBase.Tok_PageId.SQLToken, '` = ' + IdToStr(APageId),
    ' and `', TSentenceBase.Tok_Id.SQLToken, '` < ' + IdToStr(ASentenceId),
    ' order by `', TSentenceBase.Tok_Id.SQLToken, '`;'
  ]);
end;

class function TAppSQLServerQuery.QueryGetTotalPageCount: TDBSQLQuery;
begin
  Result.Name := 'QueryGetPageReps';
  Result.Query := TStringArray.FromArray([
    'select count(`',
    TPageBase.Tok_Id.SQLToken,
    '`) as `',
    TCountData.EntityToken_Number.SQLToken,
    '` from `',
    TPageBase.SQLToken,
    '` where `',
    TPageBase.Tok_Body.SQLToken,
    '` like :ASearch;'
  ]);
end;

end.
