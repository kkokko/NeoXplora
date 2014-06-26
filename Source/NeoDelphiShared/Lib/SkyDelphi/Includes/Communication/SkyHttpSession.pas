unit SkyHttpSession;

interface

uses
  Entity;

type
  TSkyHttpSession = class(TEntity)
  private
    FSessionId: string;
  public
    constructor Create(const ASessionId: string); reintroduce; virtual;
    destructor Destroy; override;
  published
    property SessionId: string read FSessionId;
  end;
  TSkyHttpSessionClass = class of TSkyHttpSession;

implementation

{ TSkyHttpSession }

constructor TSkyHttpSession.Create(const ASessionId: string);
begin
  inherited Create;
  FSessionId := ASessionId;
end;

destructor TSkyHttpSession.Destroy;
begin
  inherited;
end;

initialization
  TSkyHttpSession.RegisterEntityClass;

end.
