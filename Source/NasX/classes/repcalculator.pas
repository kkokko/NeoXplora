unit RepCalculator;

{$mode objfpc}{$H+}

interface

uses
  Classes;

type

  { TRepCalculator }

  TRepCalculator = class
  private
    FSentenceCount: Integer;
    FGuessRepTotalPrc1: Double;
    FGuessRepTotalPrc2: Double;
    FGuessRepTotalPrc3: Double;
    FGuessRepTotalPrc4: Double;
    function ExplodeReps(const ARep: string): TStringList;
    function GetRepProcent(ASentenceList, AGuessList: TStringList): Double;

    function GetRepAcc1: Double;
    function GetRepAcc2: Double;
    function GetRepAcc3: Double;
    function GetRepAcc4: Double;
  public
    constructor Create;
    procedure AddSentence(const ASentenceRep, AGuessRep1, AGuessRep2, AGuessRep3, AGuessRep4: string);

    property RepAcc1: Double read GetRepAcc1;
    property RepAcc2: Double read GetRepAcc2;
    property RepAcc3: Double read GetRepAcc3;
    property RepAcc4: Double read GetRepAcc4;
  end;

implementation

uses
  SysUtils;

{ TRepCalculator }

function TRepCalculator.ExplodeReps(const ARep: string): TStringList;
var
  TheString: string;
begin
  TheString := StringReplace(ARep, ',', #13#10, [rfReplaceAll]);
  //TheString := StringReplace(TheString, '(', #13#10, [rfReplaceAll]);
  //TheString := StringReplace(TheString, ')', #13#10, [rfReplaceAll]);
  Result := TStringList.Create;
  Result.Text := TheString;
end;

function TRepCalculator.GetRepProcent(ASentenceList, AGuessList: TStringList): Double;
var
  // number of found reps
  TheCount: Integer;
  Thestring: string;
  I: Integer;
begin
  if ASentenceList.Count = 0 then
  begin
    Result := 100;
    Exit;
  end;
  TheCount := 0;
  for I := 0 to AGuessList.Count - 1 do
    begin
    if ASentenceList.IndexOf(Trim(AGuessList[I])) <> -1 then
      TheCount := TheCount + 1;
    Thestring := AGuessList[I];
    if Thestring = '' then;
    end;
  Result := TheCount / ASentenceList.Count;
end;

function TRepCalculator.GetRepAcc1: Double;
begin
  if FSentenceCount = 0 then
    Result := 0
  else
    Result := FGuessRepTotalPrc1 / FSentenceCount * 100;
end;

function TRepCalculator.GetRepAcc2: Double;
begin
  if FSentenceCount = 0 then
    Result := 0
  else
    Result := FGuessRepTotalPrc2 / FSentenceCount * 100;
end;

function TRepCalculator.GetRepAcc3: Double;
begin
  if FSentenceCount = 0 then
    Result := 0
  else
    Result := FGuessRepTotalPrc3 / FSentenceCount * 100;
end;

function TRepCalculator.GetRepAcc4: Double;
begin
  if FSentenceCount = 0 then
    Result := 0
  else
    Result := FGuessRepTotalPrc4 / FSentenceCount * 100;
end;

constructor TRepCalculator.Create;
begin
  FSentenceCount := 0;
  FGuessRepTotalPrc1 := 0;
  FGuessRepTotalPrc2 := 0;
  FGuessRepTotalPrc3 := 0;
  FGuessRepTotalPrc4 := 0;
end;

procedure TRepCalculator.AddSentence(const ASentenceRep, AGuessRep1, AGuessRep2,
  AGuessRep3, AGuessRep4: string);
var
  TheSentenceRep: TStringList;
  TheGuessRep: TStringList;
  I: Integer;
begin
  if Trim(ASentenceRep) = '' then
    Exit;
  FSentenceCount := FSentenceCount + 1;
  TheGuessRep := nil;
  TheSentenceRep := ExplodeReps(ASentenceRep);
  try
    for I := 0 to TheSentenceRep.Count - 1 do
      TheSentenceRep[I] := Trim(TheSentenceRep[I]);
    TheGuessRep := ExplodeReps(AGuessRep1);
    FGuessRepTotalPrc1 := FGuessRepTotalPrc1 + GetRepProcent(TheSentenceRep, TheGuessRep);
    FreeAndNil(TheGuessRep);

    TheGuessRep := ExplodeReps(AGuessRep2);
    FGuessRepTotalPrc2 := FGuessRepTotalPrc2 + GetRepProcent(TheSentenceRep, TheGuessRep);
    FreeAndNil(TheGuessRep);

    TheGuessRep := ExplodeReps(AGuessRep3);
    FGuessRepTotalPrc3 := FGuessRepTotalPrc3 + GetRepProcent(TheSentenceRep, TheGuessRep);
    FreeAndNil(TheGuessRep);

    TheGuessRep := ExplodeReps(AGuessRep4);
    FGuessRepTotalPrc4 := FGuessRepTotalPrc4 + GetRepProcent(TheSentenceRep, TheGuessRep);
    FreeAndNil(TheGuessRep);
  finally
    TheSentenceRep.Free;
    TheGuessRep.Free;
  end;
end;

end.

