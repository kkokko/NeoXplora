unit PageBase;

interface

uses
  Entity, EntityFieldNamesToken, TypesConsts;

type
  TPageBase = class(TEntity)
  public
    type
      TStatus = (psFinishedGenerate, psReviewedCRep);
  private
    FBody: string;
    FStatus: TStatus;
    FName: string;
    FSource: string;
    FCategoryId: TId;
  protected
    function GetName: string; override;
    procedure SetName(const AName: string); override;
  public
    class function EntityToken_Id: TEntityFieldNamesToken; override;
    class function EntityToken_Name: TEntityFieldNamesToken; override;
    constructor Create; override;
    class var
      Tok_Body: TEntityFieldNamesToken;
      Tok_Id: TEntityFieldNamesToken;
      Tok_Name: TEntityFieldNamesToken;
      Tok_Source: TEntityFieldNamesToken;
      Tok_Status: TEntityFieldNamesToken;
  published
    property CategoryId: TId read FCategoryId write FCategoryId;
    property Id;
    property Body: string read FBody write FBody;
    property Name;
    property Source: string read FSource write FSource;
    property Status: TStatus read FStatus write FStatus;
  end;

implementation

uses
  AppConsts;

{ TPageBase }

constructor TPageBase.Create;
begin
  inherited;
  FStatus := psFinishedGenerate;
end;

class function TPageBase.EntityToken_Id: TEntityFieldNamesToken;
begin
  Result := Tok_Id;
end;

class function TPageBase.EntityToken_Name: TEntityFieldNamesToken;
begin
  Result := Tok_Name;
end;

function TPageBase.GetName: string;
begin
  Result := FName;
end;

procedure TPageBase.SetName(const AName: string);
begin
  FName := AName;
end;

initialization
  TPageBase.RegisterEntityClassWithMappingToTable(ConstNeoPrefix + 'page');
  TPageBase.RegisterToken(TPageBase.Tok_Body, 'Body');
  TPageBase.RegisterToken(TPageBase.Tok_Id, 'Id');
  TPageBase.RegisterToken(TPageBase.Tok_Name, 'Name');
  TPageBase.RegisterToken(TPageBase.Tok_Source, 'Source');
  TPageBase.RegisterToken(TPageBase.Tok_Status, 'Status');

end.
