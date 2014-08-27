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
  published
    property ApiKey: string read FApiKey write FApiKey;
    property SentenceText: string read FSentenceText write FSentenceText;
  end;
  TApiResponseGenerateRep = class(TResponse)
  private
    FRepText: string;
  public
    constructor Create(const ARepText: string); reintroduce;
  published
    property RepText: string read FRepText write FRepText;
  end;

{$EndRegion}

implementation

uses
  EntityManager;

{ TApiResponseGenerateRep }

constructor TApiResponseGenerateRep.Create(const ARepText: string);
begin
  inherited Create;
  FRepText := ARepText;
end;

initialization
  // please keep these sorted
  TEntityManager.RegisterEntityClasses([
    TApiRequestGenerateRep,

    TApiResponseGenerateRep
  ]);

end.
