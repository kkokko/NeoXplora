unit CRep;

interface

uses
  Classes, CRepDecoder;

type
  TCRep = class
  private
    FOwnTree: TCRepDecoder;
    FNewTree: TCRepDecoder;
  public
    constructor Create;
    destructor Destroy; override;

    function AddSentence(const ASentence: string): string;
    procedure Clear;
  end;

implementation

uses
  SysUtils;

{ TCRep }

function TCRep.AddSentence(const ASentence: string): string;
var
  TheNewP: TPRecord;
  TheRef: string;
  TheJob: string;
  TheName: string;
  ThePName: string;
  TheTitle: string;
  TheNames: TStringList;
  I: Integer;
begin
  Result := ASentence;
  FNewTree.Clear;
  FNewTree.AddCrep(ASentence);
  TheNames := TStringList.Create;
  try
    for I := 0 to FNewTree.PItems.Count - 1 do
    begin
      TheNewP := FNewTree.PItems.Objects[I] as TPRecord;
      TheName := TheNewP.GetSubKeyValue('name');
      TheTitle := TheNewP.GetSubKeyValue('title');
      TheJob := TheNewP.GetSubKeyValue('job');
      TheRef := TheNewP.GetSubKeyValue('ref');
      ThePName := FOwnTree.CheckForMatchAndAdd(TheName, TheTitle, TheJob, TheRef, FNewTree.PItems[I]);
      if ThePName = FNewTree.PItems[I] then
        Continue;
      Result := StringReplace(Result, FNewTree.PItems[I], '_' + IntToStr(TheNames.Count) + '_', [rfReplaceAll]);
      TheNames.Add(ThePName);
    end;
    for I := 0 to TheNames.Count - 1 do
      Result := StringReplace(Result, '_' + IntToStr(I) + '_', TheNames[I], [rfReplaceAll]);
  finally
    TheNames.Free;
  end;
end;

constructor TCRep.Create;
begin
  FOwnTree := TCRepDecoder.Create;
  FNewTree := TCRepDecoder.Create;
  FOwnTree.PItems.Sorted := True;
  Clear;
end;

destructor TCRep.Destroy;
begin
  FOwnTree.Free;
  FNewTree.Free;
  inherited;
end;

procedure TCRep.Clear;
begin
  FNewTree.Clear;
  FOwnTree.Clear;
end;

end.
