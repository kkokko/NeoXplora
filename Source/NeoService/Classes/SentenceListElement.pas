unit SentenceListElement;

interface

uses
  Classes, SkyLists, TypesConsts;

type
  { TSentenceListElement }

  TSentenceListElement = class
  private
    FPosStr: string;
    FRepresentation: string;
    FSemRep: string;
    FSentence: string;
    FSentenceWords: TSkyStringStringList;
    FPosWords: TSkyStringList;
    FId: TId;
    function GetValid: Boolean;
  public
    constructor Create(ASentenceWords: TSkyStringStringList; AnId: TId; const ASentence, ARepresentation, ASemRep, APosStr: string);
    destructor Destroy; override;

    property Id: TId read FId write FId;
    property PosStr: string read FPosStr;
    property PosWords: TSkyStringList read FPosWords;
    property Representation: string read FRepresentation;
    property SemRep: string read FSemRep;
    property Sentence: string read FSentence;
    property SentenceWords: TSkyStringStringList read FSentenceWords;
    property Valid: Boolean read GetValid;
  end;

implementation

{ TSentenceListElement }

function TSentenceListElement.GetValid: Boolean;
begin
  Result := FSentenceWords.Count = FPosWords.Count;
end;

constructor TSentenceListElement.Create(ASentenceWords: TSkyStringStringList; AnId: TId; const ASentence,
  ARepresentation, ASemRep, APosStr: string);
begin
  FId := AnId;
  FSentenceWords := ASentenceWords.CreateACopy;
  FPosWords := TSkyStringList.Create;
  FPosWords.LineBreak := ' ';
  FPosWords.Text := APosStr;
  FRepresentation := ARepresentation;
  FSemRep := ASemRep;
  FPosStr := APosStr;
  FSentence := ASentence;
end;

destructor TSentenceListElement.Destroy;
begin
  FSentenceWords.Free;
  FPosWords.Free;
  inherited Destroy;
end;

end.
