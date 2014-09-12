unit ApiRequest;

interface

uses
  Communication, EntityList, Entity, TypesConsts, GuessObject, SkyIdList;

type
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

initialization
  // please keep these sorted
  TEntityManager.RegisterEntityClasses([
    TApiRequestGenerateRep,

    TApiResponseGenerateRep
  ]);

end.
