unit ApiRequest;

interface

uses
  Communication, EntityList, Entity, TypesConsts, GuessObject, SkyIdList;

type
{$Region 'TApiRequestGenerateProtoGuess'}
  TApiRequestGenerateProtoGuess = class(TRequest)
  private
    FApiKey: string;
    FSentenceText: string;
  published
    property ApiKey: string read FApiKey write FApiKey;
    property SentenceText: string read FSentenceText write FSentenceText;
  end;
  TApiResponseGenerateProtoGuess = class(TResponse)
  private
    FMatchedSplit: string;
    FMatchedProto: string;
    FGeneratedPos: string;
    FGeneratedSplit: string;
  public
    constructor Create(const AGeneratedSplit, AGeneratedPos, AMatchedProto, AMatchedSplit: string); reintroduce;
  published
    property GeneratedSplit: string read FGeneratedSplit write FGeneratedSplit;
    property GeneratedPos: string read FGeneratedPos write FGeneratedPos;
    property MatchedProto: string read FMatchedProto write FMatchedProto;
    property MatchedSplit: string read FMatchedSplit write FMatchedSplit;
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

constructor TApiResponseGenerateProtoGuess.Create(const AGeneratedSplit, AGeneratedPos, AMatchedProto, AMatchedSplit: string);
begin
  inherited Create;
  FGeneratedSplit := AGeneratedSplit;
  FGeneratedPos := AGeneratedPos;
  FMatchedProto := AMatchedProto;
  FMatchedSplit := AMatchedSplit;
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
