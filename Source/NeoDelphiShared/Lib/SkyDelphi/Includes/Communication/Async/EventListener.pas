unit EventListener;

interface

uses
  EventData, Entity;

type
  TEventListener = class(TEntity)
  public
    function EventIsRelevant(AnEvent: TEventData): Boolean; virtual; abstract;
  published
    property Id;
  end;

implementation

{ TEventListener }

initialization
  TEventListener.RegisterEntityClass;

end.
