unit SentenceListElement;

{$mode objfpc}{$H+}

interface

uses
  Classes, SkyLists;

type
  { TSentenceListElement }

  TSentenceListElement = class
  private
    FPosStr: string;
    FRepresentation: string;
    FSemRep: string;
    FSentence: string;
    FSentenceWords: TSkyStringList;
    FPosWords: TSkyStringList;
    function GetValid: Boolean;
  public
    constructor Create(SentenceWords: TSkyStringList; const ASentence, ARepresentation, ASemRep, APosStr: string);
    destructor Destroy; override;

    property PosStr: string read FPosStr;
    property PosWords: TSkyStringList read FPosWords;
    property Representation: string read FRepresentation;
    property SemRep: string read FSemRep;
    property Sentence: string read FSentence;
    property SentenceWords: TSkyStringList read FSentenceWords;
    property Valid: Boolean read GetValid;
  end;

implementation

{ TSentenceListElement }

function TSentenceListElement.GetValid: Boolean;
begin
  Result := FSentenceWords.Count = FPosWords.Count;
end;

constructor TSentenceListElement.Create(SentenceWords: TSkyStringList; const ASentence, ARepresentation, ASemRep, APosStr: string);
begin
  FSentenceWords := TSkyStringList.Create;
  FSentenceWords.LineBreak := ' ';
  FSentenceWords.Text := ASentence;
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
