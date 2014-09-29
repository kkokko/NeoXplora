unit Proto;

interface

uses
  Entity, EntityFieldNamesToken, TypesConsts;

type
  TProto = class(TEntity)
  private
    FName: string;
    FPageId: TId;
    FOrder: Integer;
    FMainProtoId: TId;
    FParentId: TId;
  protected
    function GetName: string; override;
    procedure SetName(const AName: string); override;
  public
    class var
      Tok_Id: TEntityFieldNamesToken;
      Tok_Name: TEntityFieldNamesToken;
      Tok_Order: TEntityFieldNamesToken;
      Tok_PageId: TEntityFieldNamesToken;
      Tok_ParentId: TEntityFieldNamesToken;
    class function EntityToken_Id: TEntityFieldNamesToken; override;
    class function EntityToken_Name: TEntityFieldNamesToken; override;
  published
    property Id;
    property MainProtoId: TId read FMainProtoId write FMainProtoId;
    property Name;
    property Order: Integer read FOrder write FOrder;
    property PageId: TId read FPageId write FPageId;
    property ParentId: TId read FParentId write FParentId;
  end;

implementation

uses
  EntityMappingManager, EntityMapping, AppConsts;

{ TProto }

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
  TProto.RegisterEntityClassWithMappingToTable(ConstNeoPrefix + 'proto');
  TProto.RegisterToken(TProto.Tok_Id, 'Id');
  TProto.RegisterToken(TProto.Tok_Name, 'Name');
  TProto.RegisterToken(TProto.Tok_PageId, 'PageId');
  TProto.RegisterToken(TProto.Tok_ParentId, 'ParentId');
  TProto.RegisterToken(TProto.Tok_Order, 'Order');

end.
