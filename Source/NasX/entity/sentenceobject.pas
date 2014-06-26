unit SentenceObject;

{$mode objfpc}{$H+}

interface

uses
  Entity;

type
  { TSentenceObject }

  TSentenceObject = class(TEntity)
  private
    FContextRep: string;
    FPOS: string;
    FProto1Id: Integer;
    FProto1Object: TEntity;
    FProto2Id: Integer;
    FProto2Object: TEntity;
    FRepresentation: string;
    FSemanticRep: string;
    FName: string;
    FStoryId: Integer;
  protected
    function GetName: string; override;
    procedure SetName(const AName: string); override;
  public
    constructor Create(const AName: string; AProto1Object, AProto2Object: TEntity); overload;

    function CreateACopy: TEntity; override;

    // update ProtoIds using the protoObjects
    procedure UpdateProtoIds;

    property Proto1Object: TEntity read FProto1Object write FProto1Object;
    property Proto2Object: TEntity read FProto2Object write FProto2Object;
  published
    //serialized properties
    property ContextRep: string read FContextRep write FContextRep;
    property Id;
    property Name;
    property POS: string read FPOS write FPOS;
    property Proto1Id: Integer read FProto1Id write FProto1Id;
    property Proto2Id: Integer read FProto2Id write FProto2Id;
    property Representation: string read FRepresentation write FRepresentation;
    property SemanticRep: string read FSemanticRep write FSemanticRep;
    property StoryId: Integer read FStoryId write FStoryId;
  end;

implementation

{ TSentenceObject }

function TSentenceObject.GetName: string;
begin
  Result := FName;
end;

procedure TSentenceObject.SetName(const AName: string);
begin
  FName := AName;
end;

constructor TSentenceObject.Create(const AName: string; AProto1Object,
  AProto2Object: TEntity);
begin
  inherited Create;
  Name := AName;
  FProto1Object := AProto1Object;
  FProto2Object := AProto2Object;
end;

function TSentenceObject.CreateACopy: TEntity;
begin
  Result := inherited CreateACopy;
  (Result as TSentenceObject).Proto1Object := Proto1Object;
  (Result as TSentenceObject).Proto2Object := Proto2Object;
end;

procedure TSentenceObject.UpdateProtoIds;
begin
  FProto1Id := Proto1Object.Id;
  FProto2Id := Proto2Object.Id;
end;

initialization
  TSentenceObject.RegisterEntityClass;

end.

