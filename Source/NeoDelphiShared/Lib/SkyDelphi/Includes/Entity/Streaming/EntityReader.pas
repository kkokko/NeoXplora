unit EntityReader;

interface

uses
  Classes, Entity;

type
  TEntityReaderContext = class;

  TEntityReader = class
  public
    class function ReadEntity(AStream: TStream; AnExpectedClassType: TEntityClass = nil): TEntity; virtual; abstract;
  end;
  TEntityReaderClass = class of TEntityReader;

  TEntityReaderContext = class
  private
    FStream: TStream;
  public
    constructor Create(AStream: TStream); reintroduce;
    property Stream: TStream read FStream write FStream;
  end;

implementation

{ TEntityReaderContext }

constructor TEntityReaderContext.Create(AStream: TStream);
begin
  inherited Create;
  FStream := AStream;
end;

end.
