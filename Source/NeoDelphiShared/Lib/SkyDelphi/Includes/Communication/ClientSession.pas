unit ClientSession;

interface

uses
  SkyHttpSession, TypesConsts, BasicUserData;

type
  TClientSession = class(TSkyHttpSession)
  private
    FAsyncSession: string;
    FUser: TBasicUserData;
  published
    property AsyncSession: string read FAsyncSession write FAsyncSession;
    property User: TBasicUserData read FUser write FUser;
  end;

implementation

{ TClientSession }

initialization
  TClientSession.RegisterEntityClass;

end.
