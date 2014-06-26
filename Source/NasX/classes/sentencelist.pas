unit SentenceList;

{$mode objfpc}{$H+}

interface

uses
  Classes, contnrs, NASTypes, SentenceAlgorithm, sqlite3conn, SentenceListElement,
  SkyLists;

type

  { TSentenceList }

  TSentenceList = class
  private
    FSentenceAlgorithm: TSentenceAlgorithm;
    FSentences: TObjectList;
    function CheckSmallPosMatch(ASentence1, ASentence2: TSentenceListElement): Boolean;
  public
    constructor Create(AConnection: TSQLite3Connection);
    destructor Destroy; override;
    procedure AddSentence(SentenceWords: TSkyStringList; const ASentence, ARepresentation, ASemRep, APos: string);
    function GetRepGuess(SentenceWords: TSkyStringList; const ASentence, APos: string; AStep: Integer; AIsTurboMode: Boolean): TRepGuessData;
  end;

implementation

{ TSentenceList }

function TSentenceList.CheckSmallPosMatch(ASentence1, ASentence2: TSentenceListElement): Boolean;
var
  ThePos13, ThePos24, ThePos35: string;
  TheIndex: Integer;
begin
  Result := True;
  if ASentence1.PosWords.Count > 4 then
    ThePos35 := ASentence1.PosWords[2] + ' ' + ASentence1.PosWords[3] + ASentence1.PosWords[4]
  else
    ThePos35 := '';
  if ASentence1.PosWords.Count > 3 then
    ThePos24 := ASentence1.PosWords[1] + ' ' + ASentence1.PosWords[2] + ASentence1.PosWords[3]
  else
    ThePos24 := '';

  ThePos13 := '';
  TheIndex := 0;
  while (TheIndex < 3) and (TheIndex < ASentence1.PosWords.Count) do
  begin
    if TheIndex > 0 then
      ThePos13 := ThePos13 + ' ';
    ThePos13 := ThePos13 + ASentence1.PosWords[TheIndex];
    TheIndex := TheIndex + 1;
  end;
  if ThePos13 = '' then
    Exit;
  Result := (Pos(ThePos13, ASentence2.PosStr) <> 0) or
    ((ThePos24 <> '') and (Pos(ThePos24, ASentence2.PosStr) <> 0)) or
    ((ThePos35 <> '') and (Pos(ThePos35, ASentence2.PosStr) <> 0));
end;

constructor TSentenceList.Create(AConnection: TSQLite3Connection);
begin
  FSentences := TObjectList.Create;
  FSentences.OwnsObjects := True;
  FSentenceAlgorithm := TSentenceAlgorithm.Create(AConnection);
end;

destructor TSentenceList.Destroy;
begin
  FSentences.Free;
  FSentenceAlgorithm.Free;
end;

procedure TSentenceList.AddSentence(SentenceWords: TSkyStringList; const ASentence, ARepresentation, ASemRep, APos: string);
begin
  FSentences.Add(TSentenceListElement.Create(SentenceWords, ASentence, ARepresentation, ASemRep, APos));
end;

function TSentenceList.GetRepGuess(SentenceWords: TSkyStringList; const ASentence, APos: string; AStep: Integer;
  AIsTurboMode: Boolean): TRepGuessData;
var
  TheBestScoreA, TheBestScoreB, TheBestScoreC, TheBestScoreD: Double;
  TheCurrentSentence: TSentenceListElement;
  I: Integer;
begin
  Result := EmptyRepGuessData;
  TheBestScoreA := 0;
  TheBestScoreB := 0;
  TheBestScoreC := 0;
  TheBestScoreD := 0;
  TheCurrentSentence := TSentenceListElement.Create(SentenceWords, ASentence, '', '', APos);
  try
    FSentenceAlgorithm.Element1 := TheCurrentSentence;
    for I := 0 to FSentences.Count - 1 do
      if I mod AStep = 0 then
      begin
        FSentenceAlgorithm.Element2 := FSentences[I] as TSentenceListElement;
        if AIsTurboMode and not CheckSmallPosMatch(TheCurrentSentence, FSentenceAlgorithm.Element2) then
          Continue;
        if FSentenceAlgorithm.Element1.Sentence = FSentenceAlgorithm.Element2.Sentence then
          Continue;
        FSentenceAlgorithm.RunTextMatch(TheBestScoreA, Result.RepGuessA, Result.SRepGuessA, Result.MatchSentenceA);
        FSentenceAlgorithm.RunPosMatch(TheBestScoreB, Result.RepGuessB, Result.SRepGuessB, Result.MatchSentenceB);
        FSentenceAlgorithm.RunHybridPosMatch(TheBestScoreC, Result.RepGuessC, Result.SRepGuessC, Result.MatchSentenceC);
        FSentenceAlgorithm.RunHybridSemMatch(TheBestScoreD, Result.RepGuessD, Result.SRepGuessD, Result.MatchSentenceD);
      end;
  finally
    TheCurrentSentence.Free;
  end;

end;

end.

