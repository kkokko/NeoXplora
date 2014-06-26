unit ClientRequestLoginCheck;

interface

uses
  Communication, Entity;

type
  TClientRequestLoginCheck = class(TRequest)
  end;

  TClientResponseLoginCheck = class(TResponse)
  private
    FUser: TEntity;
    FSessionId: string;
  public
    constructor Create(AUser: TEntity; const ASessionId: string); reintroduce;
  published
    property SessionId: string read FSessionId write FSessionId;
    property User: TEntity read FUser write FUser;
  end;

implementation

uses
  EntityManager;

{ TClientResponseLoginCheck }

constructor TClientResponseLoginCheck.Create(AUser: TEntity; const ASessionId: string);
begin
  inherited Create;
  FSessionId := ASessionId;
  FUser := AUser;
end;

initialization
  TEntityManager.RegisterEntityClasses
  ([
    TClientRequestLoginCheck,

    TClientResponseLoginCheck
  ]);
end.