unit SentenceBase;

interface

uses
  Entity, EntityFieldNamesToken, TypesConsts;

type
  TSentenceBase = class(TEntity)
  public
    type
      TStatus = (ssFinishedGenerate, ssTrainedSplit, ssReviewedSplit, ssTrainedRep, ssReviewedRep);
  private
    FName: string;
    FPos: string;
    FSRep: string;
    FRep: string;
    FPageId: TId;
    FCRep: string;
    FGuessAId: TId;
    FGuessBId: TId;
    FGuessCId: TId;
    FGuessDId: TId;
    FStatus: TStatus;
    FProtoId: TId;
    FOrder: Integer;
    FMainProtoId: TId;
    FIsFixed: Boolean;
  protected
    function GetName: string; override;
    procedure SetName(const AName: string); override;
  public
    class function EntityToken_Id: TEntityFieldNamesToken; override;
    class function EntityToken_Name: TEntityFieldNamesToken; override;

    constructor Create; override;
    class var
      Tok_CRep: TEntityFieldNamesToken;
      Tok_GuessAId: TEntityFieldNamesToken;
      Tok_GuessBId: TEntityFieldNamesToken;
      Tok_GuessCId: TEntityFieldNamesToken;
      Tok_GuessDId: TEntityFieldNamesToken;
      Tok_Id: TEntityFieldNamesToken;
      Tok_Name: TEntityFieldNamesToken;
      Tok_Order: TEntityFieldNamesToken;
      Tok_Pos: TEntityFieldNamesToken;
      Tok_ProtoId: TEntityFieldNamesToken;
      Tok_Rep: TEntityFieldNamesToken;
      Tok_Status: TEntityFieldNamesToken;
      Tok_SRep: TEntityFieldNamesToken;
      Tok_PageId: TEntityFieldNamesToken;
    property GuessAId: TId read FGuessAId write FGuessAId;
    property GuessBId: TId read FGuessBId write FGuessBId;
    property GuessCId: TId read FGuessCId write FGuessCId;
    property GuessDId: TId read FGuessDId write FGuessDId;
  published
    property CRep: string read FCRep write FCRep;
    property Id;
    property IsFixed: Boolean read FIsFixed write FIsFIxed;
    property MainProtoId: TId read FMainProtoId write FMainProtoId;
    property Name;
    property Order: Integer read FOrder write FOrder;
    property Pos: string read FPos write FPos;
    property ProtoId: TId read FProtoId write FProtoId;
    property Rep: string read FRep write FRep;
    property SRep: string read FSRep write FSRep;
    property Status: TStatus read FStatus write FStatus;
    property PageId: TId read FPageId write FPageId;
  end;

implementation

uses
  AppConsts;

{ TSentenceBase }

constructor TSentenceBase.Create;
begin
  inherited;
  Status := ssFinishedGenerate;
end;

class function TSentenceBase.EntityToken_Id: TEntityFieldNamesToken;
begin
  Result := Tok_Id;
end;

class function TSentenceBase.EntityToken_Name: TEntityFieldNamesToken;
begin
  Result := Tok_Name;
end;

function TSentenceBase.GetName: string;
begin
  Result := FName;
end;

procedure TSentenceBase.SetName(const AName: string);
begin
  FName := AName;
end;

initialization
  TSentenceBase.RegisterEntityClassWithMappingToTable(ConstNeoPrefix + 'sentence');
  TSentenceBase.RegisterToken(TSentenceBase.Tok_Id, 'Id');
  TSentenceBase.RegisterToken(TSentenceBase.Tok_Name, 'Name');
  TSentenceBase.RegisterToken(TSentenceBase.Tok_Rep, 'Rep');
  TSentenceBase.RegisterToken(TSentenceBase.Tok_CRep, 'CRep');
  TSentenceBase.RegisterToken(TSentenceBase.Tok_SRep, 'SRep');
  TSentenceBase.RegisterToken(TSentenceBase.Tok_Pos, 'Pos');
  TSentenceBase.RegisterToken(TSentenceBase.Tok_PageId, 'PageId');
  TSentenceBase.RegisterToken(TSentenceBase.Tok_GuessAId, 'GuessAId');
  TSentenceBase.RegisterToken(TSentenceBase.Tok_GuessBId, 'GuessBId');
  TSentenceBase.RegisterToken(TSentenceBase.Tok_GuessCId, 'GuessCId');
  TSentenceBase.RegisterToken(TSentenceBase.Tok_GuessDId, 'GuessDId');
  TSentenceBase.RegisterToken(TSentenceBase.Tok_Status, 'Status');
  TSentenceBase.RegisterToken(TSentenceBase.Tok_ProtoId, 'ProtoId');
  TSentenceBase.RegisterToken(TSentenceBase.Tok_Order, 'Order');

end.
