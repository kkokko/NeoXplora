unit QAAlgorithm;

{$mode objfpc}{$H+}

interface

uses
  Classes, sqlite3conn, sqldb, CRepDecoder, SkyLists;

type

  { TQAAlgorithm }

  TQAAlgorithm = class
  public
    const
      ConstSplitChars = ['/', '\', '[', ']', '{', '}', '(', ')', ',', '?', '!',
        '_', '=', '+', '"', ':', ';', '$', '%', '^', '&', '*', '#'];
  private
    FConnection: TSQLite3Connection;
    FCRepDecoder: TCRepDecoder;
    FCues: TSkyStringList;
    FProperties: TSkyStringList;
    FStoryId: Integer;
    function InterpretCRep(const AContextRep: string): string;
    function ReadContextWord(const AContextRep, AWord: string): string;
  public
    constructor Create(AConnection: TSQLite3Connection; AStoryId: Integer; const ARule: string);
    destructor Destroy; override;

    class function FindAnswerInDB(AConnection: TSQLite3Connection; const AQueryString: string; AStoryId: Integer; AnUseQARules: Boolean): string;

    procedure GetPropertyAnswer(AWordList: TSkyStringList; out AResult: string; out AResultLevel: Double; AnUseQARules: Boolean);

    property Cues: TSkyStringList read FCues;
    property Properties: TSkyStringList read FProperties;
  end;

implementation

uses
  SysUtils, SentenceSplitter;

{ TQAAlgorithm }

constructor TQAAlgorithm.Create(AConnection: TSQLite3Connection; AStoryId: Integer; const ARule: string);
var
  TheIndex: Integer;
  I: Integer;
begin
  FCues := TSkyStringList.Create;
  FProperties := TSkyStringList.Create;
  FCues.LineBreak := ',';
  FProperties.LineBreak := ',';
  FCRepDecoder := TCRepDecoder.Create;

  TheIndex := Pos(':', ARule);
  if TheIndex <> 0 then
  begin
    FCues.LineBreak := Copy(ARule, 1, TheIndex - 1);
    FProperties.LineBreak := Copy(ARule, TheIndex + 1, Length(ARule) - TheIndex);
    for I := 0 to FCues.Count - 1 do
      FCues[I] := Trim(FCues[I]);
    for I := 0 to FProperties.Count - 1 do
      FProperties[I] := Trim(FProperties[I]);
  end;
  FConnection := AConnection;
  FStoryId := AStoryId;
end;

destructor TQAAlgorithm.Destroy;
begin
  FCues.Free;
  FProperties.Free;
  FCRepDecoder.Free;
  inherited Destroy;
end;

class function TQAAlgorithm.FindAnswerInDB(AConnection: TSQLite3Connection; const AQueryString: string;
  AStoryId: Integer; AnUseQARules: Boolean): string;
var
  TheQuery: TSQLQuery;
  TheQA: TQAAlgorithm;
  TheResult: string;
  TheResultLevel, TheBestLevel: Double;
  TheSplitter: TSentenceSplitter;
  I: Integer;
begin
  Result := '';
  TheSplitter := nil;
  TheQuery := TSQLQuery.Create(nil);
  try
    TheSplitter := TSentenceSplitter.Create;
    TheQuery.DataBase := AConnection;
    TheSplitter.SentenceSplitWords(AQueryString);
    if TheSplitter.WordList.Count = 0 then
      Exit;
    if AnUseQARules then
    begin
      TheQuery.SQL.Add('select storyId, qarule from qaTBL where 1=0');
      for I := 0 to TheSplitter.WordList.Count - 1 do
        TheQuery.SQL.Add(' or qarule like :AWord' + IntToStr(I));
      for I := 0 to TheSplitter.WordList.Count - 1 do
        TheQuery.ParamByName('AWord' + IntToStr(I)).AsString := '%' + TheSplitter.WordList[I] + '%';
    end else
      TheQuery.SQL.Add('select '''' qarule;');
    TheQuery.Open;
    TheBestLevel := 0;
    while not TheQuery.EOF do
    begin
      TheQA := TQAAlgorithm.Create(AConnection, AStoryId, TheQuery.FieldByName('qarule').AsString);
      try
        for I := 0 to TheSplitter.WordList.Count - 1 do
        begin
          if AnUseQARules and (TheQA.Cues.IndexOf(TheSplitter.WordList[I]) = -1) then
            Continue;
          TheQA.GetPropertyAnswer(TheSplitter.WordList, TheResult, TheResultLevel, AnUseQARules);
          if TheBestLevel < TheResultLevel then
          begin
            TheBestLevel := TheResultLevel;
            Result := TheResult;
          end;
        end;
      finally
        TheQA.Free;
      end;
      TheQuery.Next;
    end;
  finally
    TheQuery.Free;
    TheSplitter.Free;
  end;
end;

function TQAAlgorithm.ReadContextWord(const AContextRep, AWord: string): string;
var
  TheIndex: Integer;
begin
  Result := '';
  TheIndex := Pos(AWord + ' ', AContextRep);
  if TheIndex = 0 then
    Exit;
  while (TheIndex <= Length(AContextRep)) and (AContextRep[TheIndex] <> '=') do
    TheIndex := TheIndex + 1;
  TheIndex := TheIndex + 2;
  while TheIndex <= Length(AContextRep) do
  begin
    if (AContextRep[TheIndex] in ConstSplitChars) then
      Break;
    Result := Result + AContextRep[TheIndex];
    Inc(TheIndex);
  end;
end;

function TQAAlgorithm.InterpretCRep(const AContextRep: string): string;
var
  TheLastP: string;
  TheNewP: string;
  TheWord: string;
  TheWordLength: Integer;
  TheIndex: Integer;
begin
  Result := '';
  TheIndex := 1;
  TheLastP := '';
  while TheIndex <= Length(AContextRep) do
  begin
    TheWord := '';
    while TheIndex <= Length(AContextRep) do
    begin
      if AContextRep[TheIndex] <> '.' then
        TheWord := TheWord + AContextRep[TheIndex]
      else
        Break;
      TheIndex := TheIndex + 1;
    end;
    TheIndex := TheIndex + 1;

    if TheWord = '' then
      Continue;
    TheWordLength := Length(TheWord);

    if (TheWordLength = 2) and (TheWord[1] = 'p') then
    begin
      if (TheWord[2] = '1') or (TheWord[2] = '2') then
      begin
        TheLastP := TheWord;
        Continue;
      end
      else if (TheWord[2] in ['3' .. '9']) then
        TheLastP := '';
    end;

    TheNewP := TheLastP;
    if (TheWordLength > 2) and (TheWord[TheWordLength - 1] = 'p') then
    begin
      if (TheWord[TheWordLength] = '1') then
      begin
        if TheIndex <= Length(AContextRep) then
          SetLength(TheWord, TheWordLength - 2);
        TheNewP := 'p1';
      end
      else if (TheWord[TheWordLength] = '2') then
      begin
        if TheIndex <= Length(AContextRep) then
          SetLength(TheWord, TheWordLength - 2);
        TheNewP := 'p2';
      end
    end;
    if TheLastP <> '' then
      Result := Result + TheLastP + '.';
    Result := Result + TheWord;
    TheLastP := TheNewP;
  end;
  if TheLastP = TheWord then
    Result := Result + TheLastP;
end;

procedure TQAAlgorithm.GetPropertyAnswer(AWordList: TSkyStringList; out AResult: string; out AResultLevel: Double; AnUseQARules: Boolean);
var
  TheQuery: TSQLQuery;
  I: Integer;
begin
  AResult := '';
  AResultLevel := 0;
  TheQuery := TSQLQuery.Create(nil);
  try
    TheQuery.DataBase := FConnection;
    TheQuery.SQL.Text := 'select context_rep from sentenceTBL where storyId=:storyId';
    if AnUseQARules then
    begin
      TheQuery.SQL.Add(' and (0 = 1');
      for I := 0 to FProperties.Count - 1 do
        TheQuery.SQL.Add(' or context_rep like :PName' + IntToStr(I));
      TheQuery.SQL.Add(');');
      for I := 0 to FProperties.Count - 1 do
        TheQuery.ParamByName('PName' + IntToStr(I)).AsString := '%.' + FProperties[I] + ' = %';
    end;
    TheQuery.ParamByName('storyId').AsInteger := FStoryId;
    FCRepDecoder.Clear;
    TheQuery.Open;
    while not TheQuery.EOF do
    begin
      FCRepDecoder.AddCrep(TheQuery.FieldByName('context_rep').AsString);
      FCRepDecoder.PostProcess;
      TheQuery.Next;
    end;
    for I := 0 to AWordList.Count - 1 do
      FCRepDecoder.GetBestScoreForWord(AWordList[I], AWordList, AResult, AResultLevel);
    for I := 0 to FProperties.Count - 1 do
      FCRepDecoder.GetBestScoreForWord(FProperties[I], AWordList, AResult, AResultLevel);
  finally
    TheQuery.Free;
  end;
end;

end.

