unit Proto;

interface

uses
  Entity, EntityFieldNamesToken, TypesConsts;

type
  TProto = class(TEntity)
  private
    FLevel: Integer;
    FName: string;
    FPageId: TId;
    FOrder: Integer;
  protected
    function GetName: string; override;
    procedure SetName(const AName: string); override;
  public
    class var
      Tok_Id: TEntityFieldNamesToken;
      Tok_Level: TEntityFieldNamesToken;
      Tok_Name: TEntityFieldNamesToken;
      Tok_Order: TEntityFieldNamesToken;
      Tok_PageId: TEntityFieldNamesToken;
    class function EntityToken_Id: TEntityFieldNamesToken; override;
    class function EntityToken_Name: TEntityFieldNamesToken; override;
    constructor Create; override;
  published
    property Id;
    property Level: Integer read FLevel write FLevel;
    property Name;
    property Order: Integer read FOrder write FOrder;
    property PageId: TId read FPageId write FPageId;
  end;

implementation

uses
  EntityMappingManager, EntityMapping;

{ TProto }

constructor TProto.Create;
begin
  inherited;
  FLevel := 1;
end;

class function TProto.EntityToken_Id: TEntityFieldNamesToken;
begin
  Result := Tok_Id;
end;

class function TProto.EntityToken_Name: TEntityFieldNamesToken;
begin
  Result := Tok_Name;
end;

function TProto.GetName: string;
begin
  Result := FName;
end;

procedure TProto.SetName(const AName: string);
begin
  FName := AName;
end;

initialization
  TProto.RegisterEntityClassWithMappingToTable('proto');
  TProto.RegisterToken(TProto.Tok_Id, 'Id');
  TProto.RegisterToken(TProto.Tok_Level, 'Level');
  TProto.RegisterToken(TProto.Tok_Name, 'Name');
  TProto.RegisterToken(TProto.Tok_PageId, 'PageId');
  TProto.RegisterToken(TProto.Tok_Order, 'Order');

end.
