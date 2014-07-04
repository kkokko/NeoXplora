unit ClientRequest;

interface

uses
  Communication, EntityList, Entity, TypesConsts, GuessObject, SkyIdList;

type
{$Region 'TRequestGetFullSentencesForPageId'}
  TRequestGetFullSentencesForPageId = class(TRequest)
  private
    FPageId: TId;
  published
    property PageId: TId read FPageId write FPageId;
  end;
  TResponseGetFullSentencesForPageId = class(TResponse)
  private
    FSentences: TEntityList;
  public
    constructor Create(SomeSentences: TEntities); reintroduce;
  published
    property Sentences: TEntityList read FSentences write FSentences; // array of TSentenceWithGuesses
  end;

{$EndRegion}
{$Region 'TRequestGetPosForPage'}
  TRequestGetPosForPage = class(TRequest)
  private
    FUseModifiedPos: Boolean;
    FPage: string;
  published
    property Page: string read FPage write FPage;
    property UseModifiedPos: Boolean read FUseModifiedPos write FUseModifiedPos;
  end;
  TResponseGetPosForPage = class(TResponse)
  private
    FSentences: TSkyIdList;
  public
    constructor Create(SomeSentences: TObjects); reintroduce;
  published
    property Sentences: TSkyIdList read FSentences write FSentences; // array of Id - TSkyStringStringList
  end;

{$EndRegion}
{$Region 'TRequestGetPosForSentences'}
  TRequestGetPosForSentences = class(TRequest)
  private
    FSentences: TEntityList;
    FUseModifiedPos: Boolean;
  published
    property Sentences: TEntityList read FSentences write FSentences; // array of TEntityWithName
    property UseModifiedPos: Boolean read FUseModifiedPos write FUseModifiedPos;
  end;
  TResponseGetPosForSentences = class(TResponse)
  private
    FSentences: TEntityList;
  public
    constructor Create(SomeSentences: TEntities); reintroduce;
  published
    property Sentences: TEntityList read FSentences write FSentences; // array of TEntityWithName
  end;

{$EndRegion}
{$Region 'TRequestGuessRepsForSentenceId'}
  TRequestGuessRepsForSentenceId = class(TRequest)
  private
    FSentenceId: TId;
  published
    property SentenceId: TId read FSentenceId write FSentenceId;
  end;

  TResponseGuessRepsForSentenceId = class(TResponse)
  private
    FGuessObject: TGuessObject;
  public
    constructor Create(AGuessObject: TGuessObject); reintroduce;
  published
    property GuessObject: TGuessObject read FGuessObject write FGuessObject;
  end;

{$EndRegion}
{$Region 'TRequestTrainUntrainedStories'}
  TRequestTrainUntrainedStories = class(TRequest);

{$EndRegion}
{$Region 'TRequestPredictAfterSplit'}
  TRequestPredictAfterSplit = class(TRequest)
  private
    FSentences: TEntityList;
  published
    property Sentences: TEntityList read FSentences write FSentences;
  end;

{$EndRegion}
{$Region 'TRequestSearch'}
  TRequestSearch = class(TRequest)
  private
    FSearchString: string;
    FOffset: Integer;
  published
    property SearchString: string read FSearchString write FSearchString;
    property Offset: Integer read FOffset write FOffset;
  end;

  TResponseSearch = class(TResponse)
  private
    FPageCount: Integer;
    FPages: TEntityList;
    FOffset: Integer;
  public
    constructor Create(SomePages: TEntities; AnOffset, APageCount: Integer); reintroduce;
  published
    property Pages: TEntityList read FPages write FPages; // list of TSearchPage
    property PageCount: Integer read FPageCount write FPageCount;
    property Offset: Integer read FOffset write FOffset;
  end;

{$EndRegion}
{$Region 'TRequestSplitSentence'}
  TRequestSplitSentence = class(TRequest)
  private
    FNewText: string;
  published
    property Id;
    property NewText: string read FNewText write FNewText;
  end;

  TResponseSplitSentence= class(TResponse)
  private
    FNewSentences: TEntityList;
  public
    constructor Create(SomeNewSentences: TEntities); reintroduce;
  published
    property NewSentences: TEntityList read FNewSentences write FNewSentences; // array of TEntityWithName
  end;

{$EndRegion}
{$Region 'TRequestValidateAllReps'}
  TRequestValidateAllReps = class(TRequest);

{$EndRegion}
{$Region 'TRequestValidateRep'}
  TRequestValidateRep = class(TRequest)
  private
    FRep: string;
  published
    property Rep: string read FRep write FRep;
  end;

{$EndRegion}

implementation

uses
  EntityManager;

{ TResponseGuessRepsForSentenceId }

constructor TResponseGuessRepsForSentenceId.Create(AGuessObject: TGuessObject);
begin
  inherited Create;
  FGuessObject := AGuessObject;
end;

{ TResponseGetFullSentencesForPageId }

constructor TResponseGetFullSentencesForPageId.Create(SomeSentences: TEntities);
begin
  inherited Create;
  FSentences.AddMultiple(TObjects(SomeSentences), nil);
end;

{ TResponseGetPosForSentences }

constructor TResponseGetPosForSentences.Create(SomeSentences: TEntities);
begin
  inherited Create;
  FSentences.AddMultiple(TObjects(SomeSentences), nil);
end;

{ TResponseSearch }

constructor TResponseSearch.Create(SomePages: TEntities; AnOffset, APageCount: Integer);
begin
  inherited Create;
  FPages.AddMultiple(TObjects(SomePages), nil);
  FPageCount := APageCount;
  FOffset := AnOffset;
end;

{ TResponseGetPosForPage }

constructor TResponseGetPosForPage.Create(SomeSentences: TObjects);
var
  I: Integer;
begin
  inherited Create;
  for I := 0 to High(SomeSentences) do
    FSentences.AddObject(I, SomeSentences[I]);
end;

{ TResponseSplitSentence }

constructor TResponseSplitSentence.Create(SomeNewSentences: TEntities);
begin
  inherited Create;
  FNewSentences.AddMultiple(TObjects(SomeNewSentences), nil);
end;

initialization
  // please keep these sorted
  TEntityManager.RegisterEntityClasses([
    TRequestGetFullSentencesForPageId,
    TRequestGetPosForPage,
    TRequestGetPosForSentences,
    TRequestGuessRepsForSentenceId,
    TRequestPredictAfterSplit,
    TRequestSearch,
    TRequestSplitSentence,
    TRequestTrainUntrainedStories,
    TRequestValidateAllReps,
    TRequestValidateRep,

    TResponseGetFullSentencesForPageId,
    TResponseGetPosForPage,
    TResponseGetPosForSentences,
    TResponseSearch,
    TResponseSplitSentence,
    TResponseGuessRepsForSentenceId
  ]);

end.
