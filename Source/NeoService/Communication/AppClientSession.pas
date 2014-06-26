unit AppClientSession;

interface

uses
  ClientSession;

type
  TAppClientSession = class(TClientSession)
  private
    FDatabaseConnection: TObject;
  published
    property DatabaseConnection: TObject read FDatabaseConnection write FDatabaseConnection;
  end;

implementation

{ TAppClientSession }

end.
