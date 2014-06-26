unit EntityWithId;

interface

uses
  Entity, TypesConsts;

type
  TEntityWithId = class(TEntity)
  public
    constructor Create(AnId: TId); overload;
    class function CreateMultiple(SomeIds: TIds): TEntities;
  published
    property Id;
  end;

implementation

{ TEntityWithId }

constructor TEntityWithId.Create(AnId: TId);
begin
  Create;
  Id := AnId;
end;

class function TEntityWithId.CreateMultiple(SomeIds: TIds): TEntities;
var
  I: Integer;
begin
  SetLength(Result, Length(SomeIds));
  for I := 0 to High(SomeIds) do
    Result[I] := TEntityWithId.Create(SomeIds[I]);
end;

initialization
  TEntityWithId.RegisterEntityClass;

end.
