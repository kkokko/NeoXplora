unit ClientRequestRegisterEventThread;

interface

uses
  Communication, BasicUserData;

type
  TClientRequestRegisterEventThread = class(TRequest)
  private
    FMainSessionId: string;
  public
    constructor Create(const AMainSessionId: string); reintroduce;
  published
    property MainSessionId: string read FMainSessionId write FMainSessionId;
  end;

  TClientResponseRegisterEventThread = class(TResponse)
  private
    FSessionId: string;
  public
    constructor Create(const ASessionId: string); reintroduce;
  published
    property SessionId: string read FSessionId write FSessionId;
  end;

implementation

uses
  EntityManager;

{ TClientRequestRegisterEventThread }

constructor TClientRequestRegisterEventThread.Create(const AMainSessionId: string);
begin
  inherited Create;
  MainSessionId := AMainSessionId;
end;

{ TClientResponseRegisterEventThread }

constructor TClientResponseRegisterEventThread.Create(const ASessionId: string);
begin
  inherited Create;
  SessionId := ASessionId;
end;

initialization
  TEntityManager.RegisterEntityClasses
  ([
    TClientRequestRegisterEventThread,
    TClientResponseRegisterEventThread
  ]);

end.
