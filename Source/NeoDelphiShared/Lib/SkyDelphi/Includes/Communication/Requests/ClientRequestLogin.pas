unit ClientRequestLogin;

interface

uses
  Communication, Entity, BasicUserData;

type
  TClientRequestLogin = class(TRequest)
  private
    FUser: TBasicUserData;
  public
    constructor Create(AUser: TBasicUserData); reintroduce;
  published
    property User: TBasicUserData read FUser write FUser;
  end;

  TClientResponseLogin = class(TResponse)
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

{ TClientRequestLogin }

{ TClientResponseLogin }

constructor TClientResponseLogin.Create(AUser: TEntity; const ASessionId: string);
begin
  inherited Create;
  FUser := AUser;
  FSessionId := ASessionId;
end;

{ TClientRequestLogin }

constructor TClientRequestLogin.Create(AUser: TBasicUserData);
begin
  inherited Create;
  User := AUser;
end;

initialization
  TEntityManager.RegisterEntityClasses
  ([
    TClientRequestLogin,

    TClientResponseLogin
  ]);
end.
