unit ClientRequestLogout;

interface

uses
  Communication, BasicUserData;

type
  TClientRequestLogout = class(TRequest)
  end;

implementation

uses
  EntityManager;

initialization
  TEntityManager.RegisterEntityClasses
  ([
    TClientRequestLogout
  ]);
end.