unit BasicUserData;

interface

uses
  EntityWithName, TypesConsts;

type
  TBasicUserData = class(TEntityWithName)
  private
    FName: string;
    FPassword: TPasswordString;
    FUserName: string;
  protected
    function GetName: string; override;
    procedure SetName(const AName: string); override;
  public
    constructor Create(const AUserName, APassword: string); overload;
    class var
      EntityToken_UserName: TEntityFieldNamesToken;
      EntityToken_Password: TEntityFieldNamesToken;
  published
    property Id;
    property Name;
    property Password: TPasswordString read FPassword write FPassword;
    property UserName: string read FUserName write FUserName;
  end;

implementation

{ TBasicUserData }

constructor TBasicUserData.Create(const AUserName, APassword: string);
begin
  Create;
  UserName := AUserName;
  Password := APassword;
end;

function TBasicUserData.GetName: string;
begin
  Result := FName;
end;

procedure TBasicUserData.SetName(const AName: string);
begin
  FName := AName;
end;

initialization
  TBasicUserData.RegisterEntityClass;
  TBasicUserData.RegisterToken(TBasicUserData.EntityToken_Name,
    'Name', '[varchar](50) NOT NULL');
  TBasicUserData.RegisterToken(TBasicUserData.EntityToken_UserName,
    'UserName', '[varchar](50) NOT NULL');
  TBasicUserData.RegisterToken(TBasicUserData.EntityToken_Password,
    'Password');
end.
