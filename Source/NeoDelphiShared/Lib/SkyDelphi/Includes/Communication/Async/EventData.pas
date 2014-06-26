unit EventData;

interface

uses
  Entity, TypesConsts;

type
  TEventData = class(TEntity)
  private
    FEventListenerId: TId;
  published
    property EventListenerId: TId read FEventListenerId write FEventListenerId; // Event listener Id
  end;

implementation

initialization
  TEventData.RegisterEntityClass;

end.
