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
    FMatchedRep: string;
    FGeneratedPos: string;
    FGeneratedSplit: string;
    FGeneratedRep: string;
  public
    constructor Create(const AGeneratedSplit, AGeneratedPos, AGeneratedRep, AMatchedProto,
      AMatchedSplit, AMatchedRep: string); reintroduce;
  published
    property GeneratedSplit: string read FGeneratedSplit write FGeneratedSplit;
    property GeneratedPos: string read FGeneratedPos write FGeneratedPos;
    property GeneratedRep: string read FGeneratedRep write FGeneratedRep;
    property MatchedProto: string read FMatchedProto write FMatchedProto;
    property MatchedSplit: string read FMatchedSplit write FMatchedSplit;
    property MatchedRep: string read FMatchedRep write FMatchedRep;
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

constructor TApiResponseGenerateProtoGuess.Create(const AGeneratedSplit, AGeneratedPos, AGeneratedRep, AMatchedProto,
  AMatchedSplit, AMatchedRep: string);
begin
  inherited Create;
  FGeneratedSplit := AGeneratedSplit;
  FGeneratedPos := AGeneratedPos;
  FGeneratedRep := AGeneratedRep;
  FMatchedProto := AMatchedProto;
  FMatchedSplit := AMatchedSplit;
  FMatchedRep := AMatchedRep;
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
