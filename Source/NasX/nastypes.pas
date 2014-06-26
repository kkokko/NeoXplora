unit NASTypes;

{$mode objfpc}{$H+}

interface

type
  TRepGuessData = record
    RepGuessA, MatchSentenceA: string;
    RepGuessB, MatchSentenceB: string;
    RepGuessC, MatchSentenceC: string;
    RepGuessD, MatchSentenceD: string;
    SRepGuessA: string;
    SRepGuessB: string;
    SRepGuessC: string;
    SRepGuessD: string;
  end;

function EmptyRepGuessData: TRepGuessData;

implementation

function EmptyRepGuessData: TRepGuessData;
begin
  Result.RepGuessA := '';
  Result.MatchSentenceA := '';
  Result.RepGuessB := '';
  Result.MatchSentenceB := '';
  Result.RepGuessC := '';
  Result.MatchSentenceC := '';
  Result.RepGuessD := '';
  Result.MatchSentenceD := '';
  Result.SRepGuessA := '';
  Result.SRepGuessB := '';
  Result.SRepGuessC := '';
  Result.SRepGuessD := '';
end;

end.

