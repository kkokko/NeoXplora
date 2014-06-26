unit ClientRequestCheckEvents;

interface

uses
  Communication, Entity, EntityList;

type
  TClientRequestCheckEvents = class(TRequest);

  TClientResponseCheckEvents = class(TResponse)
  private
    FEvents: TEntityList;
  public
    constructor Create(SomeEvents: TEntities); reintroduce;
  published
    property Events: TEntityList read FEvents write FEvents;
  end;

implementation

uses
  TypesConsts, EntityManager;

{ TClientResponseCheckEvents }

constructor TClientResponseCheckEvents.Create(SomeEvents: TEntities);
begin
  inherited Create;
  Events.AddMultiple(TObjects(SomeEvents), nil);
end;

initialization
  TEntityManager.RegisterEntityClasses
  ([
    TClientRequestCheckEvents,

    TClientResponseCheckEvents
  ]);
end.
