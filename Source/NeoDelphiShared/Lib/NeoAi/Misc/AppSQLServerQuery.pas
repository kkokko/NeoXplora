unit AppSQLServerQuery;

interface

uses
  BaseQuery, TypesConsts, Entity, DBSQLQuery, SentenceBase, OrderInPage;

type
  TAppSQLServerQuery = class(TBaseQuery)
  public
    class procedure DeleteCRepForPageId(APageId: TId);
    class procedure DeleteOrderInPageForProtoId(AnId: TId);
    class procedure DeleteOrderInPageForSentenceId(AnId: TId);
    class procedure DeleteProtoForPageId(APageId: TId);
    class procedure DeleteSentenceForPageId(APageId: TId);
    class function GetCRepRules: TEntities;
    class function GetFinishedStoriesCount: Integer;
    class function GetFullSentencesForPageId(APageId: TId): TEntities;
    class function GetHypernyms: TEntities;
    class function GetIRepRules: TEntities;
    class function GetOrderInPageForPageAndSentenceId(APageId, ASentenceId: TId): TOrderInPage;
    class function GetSearchPagesByOffset(ASearch: string; AnOffset: Integer): TEntities;
    class function GetSentenceBaseById(AnId: TId): TSentenceBase;
    class function GetSplitSentences: TEntities;
    class function GetSplitProtos: TEntities;
    class function GetPageReps(APageId, ASentenceId: TId): TEntities;
    class function GetProtoChildCount(AProtoId: Integer): Integer;
    class function GetSplitsForProtoId(AProtoId: TId): TEntities;
    class function GetTotalPageCount(const ASearch: string): Integer;
    class function GetUntrainedStories: TEntities;
    class procedure IncreasePageOrderForPageId(APageId: TId; AStartingFrom, AnIncrease: Integer);
    class procedure UpdateProtoOrderForPage(APageId: TId; AnOrder, ACount: Integer);
    class procedure UpdateSentenceOrderForPage(APageId: TId; AnOrder, ACount: Integer);
  private
    class function QueryDeleteCRepForPageId(APageId: TId): TDBSQLQuery;
    class function QueryDeleteOrderInPageForProtoId(AnId: TId): TDBSQLQuery;
    class function QueryDeleteOrderInPageForSentenceId(AnId: TId): TDBSQLQuery;
    class function QueryDeleteProtoForPageId(APageId: TId): TDBSQLQuery;
    class function QueryDeleteSentenceForPageId(APageId: TId): TDBSQLQuery;
    class function QueryGetCRepRules: TDBSQLQuery;
    class function QueryGetFinishedStoriesCount: TDBSQLQuery;
    class function QueryGetFullSentencesForPageId(APageId: TId): TDBSQLQuery;
    class function QueryGetHypernyms: TDBSQLQuery;
    class function QueryGetIRepRules: TDBSQLQuery;
    class function QueryGetOrderInPageForPageAndSentenceId(APageId, ASentenceId: TId): TDBSQLQuery;
    class function QueryGetSearchPagesByOffset(AnOffset: Integer): TDBSQLQuery;
    class function QueryGetSentenceBaseById(AnId: TId): TDBSQLQuery;
    class function QueryGetSplitsForProtoId(AnId: TId): TDBSQLQuery;
    class function QueryGetSplitSentences: TDBSQLQuery;
    class function QueryGetSplitProtos: TDBSQLQuery;
    class function QueryGetPageReps(APageId, ASentenceId: TId): TDBSQLQuery;
    class function QueryGetProtoChildCount(AProtoId: TId): TDBSQLQuery;
    class function QueryGetTotalPageCount: TDBSQLQuery;
    class function QueryGetUntrainedStories: TDBSQLQuery;
    class function QueryIncreasePageOrderForPageId(APageId: TId; AStartingFrom, AnIncrease: Integer): TDBSQLQuery;

    class function QueryUpdateProtoOrderForPage(APageId: TId; AnOrder, ACount: Integer): TDBSQLQuery;
    class function QueryUpdateSentenceOrderForPage(APageId: TId; AnOrder, ACount: Integer): TDBSQLQuery;
  end;

implementation

uses
  StringArray, AppUnit, EntityWithName, PageBase, TypesFunctions, SentenceWithGuesses, TypInfo, CountData, EntityWithId,
  SearchPage, SysUtils, RepGroup, IRepRule, CRepRule, Proto, Split, CRep;

{ TAppSQLServerQuery }

class procedure TAppSQLServerQuery.DeleteCRepForPageId(APageId: TId);
var
  TheQuery: TDBSQLQuery;
begin
  TheQuery := QueryDeleteCRepForPageId(APageId);
  App.SQLConnection.ExecuteQuery(TheQuery);
  TheQuery.Query.Count := 0;
end;

class procedure TAppSQLServerQuery.DeleteOrderInPageForProtoId(AnId: TId);
var
  TheQuery: TDBSQLQuery;
begin
  TheQuery := QueryDeleteOrderInPageForProtoId(AnId);
  App.SQLConnection.ExecuteQuery(TheQuery);
  TheQuery.Query.Count := 0;
end;

class procedure TAppSQLServerQuery.DeleteOrderInPageForSentenceId(AnId: TId);
var
  TheQuery: TDBSQLQuery;
begin
  TheQuery := QueryDeleteOrderInPageForSentenceId(AnId);
  App.SQLConnection.ExecuteQuery(TheQuery);
  TheQuery.Query.Count := 0;
end;

class procedure TAppSQLServerQuery.DeleteProtoForPageId(APageId: TId);
var
  TheQuery: TDBSQLQuery;
begin
  TheQuery := QueryDeleteProtoForPageId(APageId);
  App.SQLConnection.ExecuteQuery(TheQuery);
  TheQuery.Query.Count := 0;
end;

class procedure TAppSQLServerQuery.DeleteSentenceForPageId(APageId: TId);
var
  TheQuery: TDBSQLQuery;
begin
  TheQuery := QueryDeleteSentenceForPageId(APageId);
  App.SQLConnection.ExecuteQuery(TheQuery);
  TheQuery.Query.Count := 0;
end;

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

class function TAppSQLServerQuery.GetOrderInPageForPageAndSentenceId(APageId, ASentenceId: TId): TOrderInPage;
var
  TheDbQuery: TDBSQLQuery;
begin
  TheDbQuery := QueryGetOrderInPageForPageAndSentenceId(APageId, ASentenceId);
  Result := App.SQLConnection.SelectQuerySingle([TOrderInPage], TheDbQuery, True) as TOrderInPage;
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

class function TAppSQLServerQuery.GetSplitsForProtoId(AProtoId: TId): TEntities;
var
  TheQuery: TBaseQuery;
  TheDbQuery: TDBSQLQuery;
begin
  TheDbQuery := QueryGetSplitsForProtoId(AProtoId);;
  TheQuery := TBaseQuery.TranslateDBSQLQuery(App.SQLConnection, TheDbQuery);
  try
    TheDbQuery.Query.Count := 0;
    TheQuery.ParamByName('aProtoSplitType').AsString := GetEnumName(TypeInfo(TSplit.TSplitType), Integer(stProto));
    TheQuery.ParamByName('aSentenceSplitType').AsString := GetEnumName(TypeInfo(TSplit.TSplitType), Integer(stSentence));
    TheQuery.Open;
    Result := TheQuery.ReadMappedEntities([TSplit]);
  finally
    TheQuery.Free;
  end;
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

class function TAppSQLServerQuery.GetProtoChildCount(AProtoId: Integer): Integer;
var
  TheDbQuery: TDBSQLQuery;
  TheCountData: TCountData;
begin
  TheDbQuery := QueryGetProtoChildCount(AProtoId);
  TheCountData := App.SQLConnection.SelectQuerySingle([TCountData], TheDbQuery, True) as TCountData;
  TheDbQuery.Query.Count := 0;
  Result := TheCountData.Number;
  TheCountData.Free;
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

class procedure TAppSQLServerQuery.IncreasePageOrderForPageId(APageId: TId; AStartingFrom, AnIncrease: Integer);
var
  TheDbQuery: TDBSQLQuery;
begin
  TheDbQuery := QueryIncreasePageOrderForPageId(APageId, AStartingFrom, AnIncrease);
  App.SQLConnection.ExecuteQuery(TheDbQuery);
  TheDbQuery.Query.Count := 0;
end;

class procedure TAppSQLServerQuery.UpdateProtoOrderForPage(APageId: TId; AnOrder, ACount: Integer);
var
  TheDbQuery: TDBSQLQuery;
begin
  TheDbQuery := QueryUpdateProtoOrderForPage(APageId, AnOrder, ACount);
  App.SQLConnection.ExecuteQuery(TheDbQuery);
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

class function TAppSQLServerQuery.QueryIncreasePageOrderForPageId(APageId: TId; AStartingFrom,
  AnIncrease: Integer): TDBSQLQuery;
begin
  Result.Name := 'QueryIncreasePageOrderForPageId';
  Result.Query := TStringArray.FromArray([
    'update `', TOrderInPage.SQLToken,
    '` set `', TOrderInPage.Tok_Order.SQLToken, '` = `', TOrderInPage.Tok_Order.SQLToken, '` + ' + IntToStr(AnIncrease) +
    ' where `', TOrderInPage.Tok_PageId.SQLToken, '` = ' + IdToStr(APageId) +
    ' and `', TOrderInPage.Tok_Order.SQLToken, '` > ' + IntToStr(AStartingFrom)
  ]);
end;

class function TAppSQLServerQuery.QueryUpdateProtoOrderForPage(APageId: TId; AnOrder, ACount: Integer): TDBSQLQuery;
begin
  Result.Name := 'QueryUpdateProtoOrderForPage';
  Result.Query := TStringArray.FromArray([
    'update `',
    TProto.SQLToken,
    '` set `',
    TProto.Tok_Order.SQLToken,
    '` = `',
    TProto.Tok_Order.SQLToken,
    '` + ' + IntToStr(ACount) + ' where `',
    TProto.Tok_PageId.SQLToken,
    '` = ' + IdToStr(APageId) + ' and `',
    TProto.Tok_Order.SQLToken,
    '` > ' + IntToStr(AnOrder) + ';'
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

class function TAppSQLServerQuery.QueryGetOrderInPageForPageAndSentenceId(APageId, ASentenceId: TId): TDBSQLQuery;
begin
  Result.Name := 'QueryGetOrderInPageForPageAndSentenceId';
  Result.Query := TStringArray.FromArray([
    'select * from `',
    TOrderInPage.SQLToken,
    '` where `', TOrderInPage.Tok_PageId.SQLToken, '` = ' + IdToStr(APageId) +
    ' and `', TOrderInPage.Tok_SentenceId.SQLToken, '` = ' + IdToStr(ASentenceId)
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

class function TAppSQLServerQuery.QueryGetSplitsForProtoId(AnId: TId): TDBSQLQuery;
begin
  Result.Name := 'QueryGetSplitsForProtoId';
  Result.Query := TStringArray.FromArray([
    'select * from (' +
    'select `', TProto.Tok_Id.SQLToken,'` as `', TSplit.Tok_Id.SQLToken,
    '`, `', TProto.Tok_Name.SQLToken, '` as `', TSplit.Tok_Name.SQLToken,
    '`, :aProtoSplitType as `', TSplit.Tok_SplitType.SQLToken,
    '`, `', TProto.Tok_Order.SQLToken, '` as`', TSplit.Tok_Order.SQLToken,
    '` from `', TProto.SQLToken,
    '` where `', TProto.Tok_ParentId.SQLToken, '` = ' + IntToStr(AnId) + ' union ' +
    'select `', TSentenceBase.Tok_Id.SQLToken,'`, `', TSentenceBase.Tok_Name.SQLToken,
    '`, :aSentenceSplitType, `', TSentenceBase.Tok_Order.SQLToken,
    '` from `', TSentenceBase.SQLToken,
    '` where `', TSentenceBase.Tok_ProtoId.SQLToken, '` = ' + IntToStr(AnId),
    ')a order by `', TSplit.Tok_Order.SQLToken, '`'
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
    TSentenceBase.Tok_ProtoId.SQLToken,
    '` where se.`', TSentenceBase.Tok_Status.SQLToken,
    '` in (:ASentenceStatus1, :ASentenceStatus2, :ASentenceStatus3) AND se.`',
    TSentenceBase.Tok_Name.SQLToken, '` <> pr.`', TProto.Tok_Name.SQLToken, '`'
  ]);
end;

class function TAppSQLServerQuery.QueryDeleteCRepForPageId(APageId: TId): TDBSQLQuery;
begin
  Result.Name := 'QueryDeleteCRepForPageId';
  Result.Query := TStringArray.FromArray([
    'delete from `',
    TCRep.SQLToken,
    '` where `', TCRep.Tok_PageId.SQLToken, '` = ' + IdToStr(APageId) + ';'
  ]);
end;

class function TAppSQLServerQuery.QueryDeleteOrderInPageForProtoId(AnId: TId): TDBSQLQuery;
begin
  Result.Name := 'QueryDeleteOrderInPageForProtoId';
  Result.Query := TStringArray.FromArray([
    'delete from `',
    TOrderInPage.SQLToken,
    '` where `', TOrderInPage.Tok_ProtoId.SQLToken, '` = ' + IdToStr(AnId) + ';'
  ]);
end;

class function TAppSQLServerQuery.QueryDeleteOrderInPageForSentenceId(AnId: TId): TDBSQLQuery;
begin
  Result.Name := 'QueryDeleteOrderInPageForProtoId';
  Result.Query := TStringArray.FromArray([
    'delete from `',
    TOrderInPage.SQLToken,
    '` where `', TOrderInPage.Tok_SentenceId.SQLToken, '` = ' + IdToStr(AnId) + ';'
  ]);
end;

class function TAppSQLServerQuery.QueryDeleteProtoForPageId(APageId: TId): TDBSQLQuery;
begin
  Result.Name := 'QueryDeleteProtoForPageId';
  Result.Query := TStringArray.FromArray([
    'delete from `',
    TProto.SQLToken,
    '` where `', TProto.Tok_PageId.SQLToken, '` = ' + IdToStr(APageId) + ';'
  ]);
end;

class function TAppSQLServerQuery.QueryDeleteSentenceForPageId(APageId: TId): TDBSQLQuery;
begin
  Result.Name := 'QueryDeleteSentenceForPageId';
  Result.Query := TStringArray.FromArray([
    'delete from `',
    TSentenceBase.SQLToken,
    '` where `', TSentenceBase.Tok_PageId.SQLToken, '` = ' + IdToStr(APageId) + ';'
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

class function TAppSQLServerQuery.QueryGetProtoChildCount(AProtoId: TId): TDBSQLQuery;
begin
  Result.Name := 'QueryGetProtoChildCount';
  Result.Query := TStringArray.FromArray([
    'select count(`', TProto.Tok_Id.SQLToken,
    '`) + (select count(`', TSentenceBase.Tok_Id.SQLToken, '`) from `', TSentenceBase.SQLToken,
    '` where `', TSentenceBase.Tok_ProtoId.SQLToken, '` = ' + IdToStr(AProtoId) + ') `',
    TCountData.EntityToken_Number.SQLToken, '` from `', TProto.SQLToken,
    '` where `', TProto.Tok_ParentId.SQLToken, '` = ' + IdToStr(AProtoId)
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
