unit ApiRequest;

interface

uses
  Communication, EntityList, Entity, TypesConsts, GuessObject, SkyIdList, ApiGeneratedSplit, ApiGeneratedSplitFull;

type
{$Region 'TApiRequestGenerateProtoGuess'}
  TApiRequestGenerateProtoGuess = class(TRequest)
  private
    FApiKey: string;
    FSentenceText: string;
    FSplitThreshold: Double;
    FUseExact: Boolean;
    FSepWeight: Integer;
    FMaxIterations: Integer;
    FFullDetails: Boolean;
  published
    property ApiKey: string read FApiKey write FApiKey;
    property MaxIterations: Integer read FMaxIterations write FMaxIterations;
    property SentenceText: string read FSentenceText write FSentenceText;
    property SepWeight: Integer read FSepWeight write FSepWeight;
    property SplitThreshold: Double read FSplitThreshold write FSplitThreshold;
    property UseExact: Boolean read FUseExact write FUseExact;
    property FullDetails: Boolean read FFullDetails write FFullDetails;
  end;
  TApiResponseGenerateProtoGuess = class(TResponse)
  private
    FDataSimple: TApiGeneratedSplit;
    FDataFull: TApiGeneratedSplitFull;
  public
    constructor Create(AData: TApiGeneratedSplit); reintroduce;
  published
    property DataSimple: TApiGeneratedSplit read FDataSimple write FDataSimple;
    property DataFull: TApiGeneratedSplitFull read FDataFull write FDataFull;
  end;

{$EndRegion}
{$Region 'TApiRequestGenerateRep'}
  TApiRequestGenerateRep = class(TRequest)
  private
    FApiKey: string;
    FSentenceText: string;
    FOutputSentence: Boolean;
  published
    property ApiKey: string read FApiKey write FApiKey;
    property SentenceText: string read FSentenceText write FSentenceText;
    property OutputSentence: Boolean read FOutputSentence write FOutputSentence;
  end;
  TApiResponseGenerateRep = class(TResponse)
  private
    FRepText: string;
    FMatchedSentence: string;
  public
    constructor Create(const ARepText, AMatchedSentence: string); reintroduce;
  published
    property RepText: string read FRepText write FRepText;
    property MatchedSentence: string read FMatchedSentence write FMatchedSentence;
  end;

{$EndRegion}

implementation

uses
  EntityManager;

{ TApiResponseGenerateRep }

constructor TApiResponseGenerateRep.Create(const ARepText, AMatchedSentence: string);
begin
  inherited Create;
  FRepText := ARepText;
  FMatchedSentence := AMatchedSentence;
end;

{ TApiResponseGenerateProtoGuess }

constructor TApiResponseGenerateProtoGuess.Create(AData: TApiGeneratedSplit);
begin
  inherited Create;
  if AData is TApiGeneratedSplitFull then
    FDataFull := AData as TApiGeneratedSplitFull
  else
    FDataSimple := AData;
end;

initialization
  // please keep these sorted
  TEntityManager.RegisterEntityClasses([
    TApiRequestGenerateProtoGuess,
    TApiRequestGenerateRep,

    TApiResponseGenerateProtoGuess,
    TApiResponseGenerateRep
  ]);

end.
