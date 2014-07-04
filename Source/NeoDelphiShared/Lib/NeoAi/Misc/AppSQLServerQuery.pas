unit AppSQLServerQuery;

interface

uses
  BaseQuery, TypesConsts, Entity, DBSQLQuery, SentenceBase;

type
  TAppSQLServerQuery = class(TBaseQuery)
  public
    class function GetFinishedStoriesCount: Integer;
    class function GetFullSentencesForPageId(APageId: TId): TEntities;
    class function GetHypernyms: TEntities;
    class function GetSearchPagesByOffset(ASearch: string; AnOffset: Integer): TEntities;
    class function GetSentenceBaseById(AnId: TId): TSentenceBase;
    class function GetSplitSentences: TEntities;
    class function GetPageReps(APageId, ASentenceId: TId): TEntities;
    class function GetTotalPageCount(const ASearch: string): Integer;
    class function GetUntrainedStories: TEntities;
    class procedure UpdateSentenceOrderForPage(APageId: TId; AnOrder, ACount: Integer);
  private
    class function QueryGetFinishedStoriesCount: TDBSQLQuery;
    class function QueryGetFullSentencesForPageId(APageId: TId): TDBSQLQuery;
    class function QueryGetHypernyms: TDBSQLQuery;
    class function QueryGetSearchPagesByOffset(AnOffset: Integer): TDBSQLQuery;
    class function QueryGetSentenceBaseById(AnId: TId): TDBSQLQuery;
    class function QueryGetSplitSentences: TDBSQLQuery;
    class function QueryGetPageReps(APageId, ASentenceId: TId): TDBSQLQuery;
    class function QueryGetTotalPageCount: TDBSQLQuery;
    class function QueryGetUntrainedStories: TDBSQLQuery;
    class function QueryUpdateSentenceOrderForPage(APageId: TId; AnOrder, ACount: Integer): TDBSQLQuery;
  end;

implementation

uses
  StringArray, AppUnit, EntityWithName, PageBase, TypesFunctions, SentenceWithGuesses, TypInfo, CountData, EntityWithId,
  SearchPage, SysUtils;

{ TAppSQLServerQuery }

class function TAppSQLServerQuery.GetFinishedStoriesCount: Integer;
var
  TheQuery: TBaseQuery;
begin
  TheQuery := TBaseQuery.TranslateDBSQLQuery(App.SQLConnection, QueryGetFinishedStoriesCount);
  try
    TheQuery.ParamByName('APageStatus1').AsString := GetEnumName(TypeInfo(TPageBase.TStatus), Integer(psReviewedRep));
    TheQuery.ParamByName('APageStatus2').AsString := GetEnumName(TypeInfo(TPageBase.TStatus), Integer(psReviewedCRep));
    TheQuery.Open;
    Result := TheQuery.ReadCountData;
  finally
    TheQuery.Free;
  end;
end;

class function TAppSQLServerQuery.GetFullSentencesForPageId(APageId: TId): TEntities;
begin
  Result := App.SQLConnection.SelectQuery([TSentenceWithGuesses], QueryGetFullSentencesForPageId(APageId));
end;

class function TAppSQLServerQuery.GetHypernyms: TEntities;
var
  TheQuery: TBaseQuery;
begin
  TheQuery := TBaseQuery.TranslateDBSQLQuery(App.SQLConnection, QueryGetHypernyms);
  try
    TheQuery.ParamByName('APageStatus1').AsString := GetEnumName(TypeInfo(TPageBase.TStatus), Integer(psReviewedRep));
    TheQuery.ParamByName('APageStatus2').AsString := GetEnumName(TypeInfo(TPageBase.TStatus), Integer(psReviewedCRep));
    TheQuery.Open;
    Result := TheQuery.ReadMappedEntities([TEntityWithName]);
  finally
    TheQuery.Free;
  end;
end;

class function TAppSQLServerQuery.GetSearchPagesByOffset(ASearch: string; AnOffset: Integer): TEntities;
var
  TheQuery: TBaseQuery;
begin
  TheQuery := TBaseQuery.TranslateDBSQLQuery(App.SQLConnection, QueryGetSearchPagesByOffset(AnOffset));
  try
    TheQuery.ParamByName('ASearch').AsString := '%' + ASearch + '%';
    TheQuery.Open;
    Result := TheQuery.ReadMappedEntities([TSearchPage]);
  finally
    TheQuery.Free;
  end;
end;

class function TAppSQLServerQuery.GetSentenceBaseById(AnId: TId): TSentenceBase;
begin
  Result := App.SQLConnection.SelectQuerySingle([TSentenceBase], QueryGetSentenceBaseById(AnId), False) as TSentenceBase;
end;

class function TAppSQLServerQuery.GetSplitSentences: TEntities;
var
  TheQuery: TBaseQuery;
begin
  TheQuery := TBaseQuery.TranslateDBSQLQuery(App.SQLConnection, QueryGetSplitSentences);
  try
    TheQuery.ParamByName('APageStatus1').AsString := GetEnumName(TypeInfo(TPageBase.TStatus), Integer(psReviewedRep));
    TheQuery.ParamByName('APageStatus2').AsString := GetEnumName(TypeInfo(TPageBase.TStatus), Integer(psReviewedCRep));
    TheQuery.Open;
    Result := TheQuery.ReadMappedEntities([TSentenceBase]);
  finally
    TheQuery.Free;
  end;
end;

class function TAppSQLServerQuery.GetPageReps(APageId, ASentenceId: TId): TEntities;
begin
  Result := App.SQLConnection.SelectQuery([TEntityWithName], QueryGetPageReps(APageId, ASentenceId));
end;

class function TAppSQLServerQuery.GetTotalPageCount(const ASearch: string): Integer;
var
  TheQuery: TBaseQuery;
  TheCountData: TCountData;
begin
  TheCountData := nil;
  TheQuery := TBaseQuery.TranslateDBSQLQuery(App.SQLConnection, QueryGetTotalPageCount);
  try
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
begin
  Result := App.SQLConnection.SelectQuery([TEntityWithId], QueryGetUntrainedStories);
end;

class procedure TAppSQLServerQuery.UpdateSentenceOrderForPage(APageId: TId; AnOrder, ACount: Integer);
var
  TheQuery: TDBSQLQuery;
begin
  TheQuery := QueryUpdateSentenceOrderForPage(APageId, AnOrder, ACount);
  App.SQLConnection.ExecuteQuery(TheQuery);
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
    'select se.`', TSentenceBase.Tok_SRep.SQLToken, '` as `',
    TEntityWithName.EntityToken_Name.SQLToken,'` from `', TSentenceBase.SQLToken,
    '` se inner join `', TPageBase.SQLToken, '` st on se.`',
    TSentenceBase.Tok_PageId.SQLToken, '` = st.`', TPageBase.EntityToken_Id.SQLToken,
    '` where st.`', TPageBase.Tok_Status.SQLToken, '` in (:APageStatus1, :APageStatus2) and (se.`',
    TSentenceBase.Tok_SRep.SQLToken,'` like ''%eg(%'' or',
    ' se.`', TSentenceBase.Tok_SRep.SQLToken,'` like ''%part(%'' or se.`',
    TSentenceBase.Tok_SRep.SQLToken,'` like ''%property(%'');'
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

class function TAppSQLServerQuery.QueryGetSplitSentences: TDBSQLQuery;
begin
  Result.Name := 'QueryGetSplitSentences';
  Result.Query := TStringArray.FromArray([
    'select se.`', TSentenceBase.EntityToken_Id.SQLToken, '`, se.`', TSentenceBase.Tok_Name.SQLToken,
    '`, se.`', TSentenceBase.Tok_Rep.SQLToken, '`, se.`', TSentenceBase.Tok_SRep.SQLToken,
    '`, se.`', TSentenceBase.Tok_Pos.SQLToken, '`',
    ' from `', TSentenceBase.SQLToken, '` se inner join `', TPageBase.SQLToken, '` st on se.`',
    TSentenceBase.Tok_PageId.SQLToken, '` = st.`', TPageBase.EntityToken_Id.SQLToken,
    '` where st.`', TPageBase.Tok_Status.SQLToken, '` in (:APageStatus1, :APageStatus2) and trim(se.`',
    TSentenceBase.Tok_Rep.SQLToken, '`) <> '''''
  ]);
end;

class function TAppSQLServerQuery.QueryGetFinishedStoriesCount: TDBSQLQuery;
begin
  Result.Name := 'QueryGetFinishedStoriesCount';
  Result.Query := TStringArray.FromArray([
    'select count(*) as `',
    TCountData.EntityToken_Number.SQLToken,
    '` from ('
  ]);
  Result.Query.Add(QueryGetSplitSentences.Query);
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
