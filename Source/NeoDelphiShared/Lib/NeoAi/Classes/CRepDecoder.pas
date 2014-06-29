unit CRepDecoder;

interface

uses
  SkyLists;

type
  TPRecord = class;
  TNode = class;
  TExpression = record
    Path: string;
    KeyValue: string;
    Value: string;
  end;

  { TCRepDecoder }

  TCRepDecoder = class
  private
    const
      ConstNameMatchFound = 2;
      ConstNameIMatch = 1.8;
      ConstNameHeSheMatch = 1.4;
  private
    FMaxLevel: Integer;
    FWords: TSkyStringList;
    FPItems: TSkyStringList;
    procedure AdjustNodeDisplayValue(ANode: TNode);
    function DecodeExpression(AnExpression: TExpression): TExpression;
    function FindClosestNode(const APath: string): TNode;
    function FindPath(const APath: string): TNode;
    function GetPath(const APath: string): TNode;
    function ReadExpression(var AString: string; const APath: string): TExpression;
  public
    constructor Create;
    destructor Destroy; override;

    procedure AddCrep(const AContextRep: string);
    function CheckForMatchAndAdd(const AName, ATitle, AJob, ARef, APname: string): string;
    procedure Clear;
    procedure GetBestScoreForWord(const AWord: string; AWordList: TSkyStringList;
      var AResult: string; var AResultScore: Double);
    function GetFriendlyText(AMaxLevel: Integer): string;
    procedure PostProcess;

    property MaxLevel: Integer read FMaxLevel write FMaxLevel;
    property PItems: TSkyStringList read FPItems; // string: TPRecord
    property Words: TSkyStringList read FWords; // string: TPRecord
  end;

  TNode = class
  private
    const
      ConstLevelBonus = 0.2;
      ConstNameScore = 1.5;
      ConstRefScore = 1.1;
  private
    FParent: TNode;
    FSubKeys: TSkyStringList;
    FValue: string;
    FRepDecoder: TCRepDecoder;
    FReplacementValue: string;
    FScore: Double;
    FScoreBonus: Double;
    FKey: string;
    function GetKeyName(const AKeyName: string): string;
    function TopParent: TNode;
  protected
    function GetDisplayValue: string; virtual;
    function GetValue: string; virtual;
    function GetNiceValue: string;
  public
    constructor Create(AParent: TNode; const ANodeName: string; ARepDecoder: TCRepDecoder; const AValue: string = ''); reintroduce;
    destructor Destroy; override;

    procedure AdjustNodeBonusScore(const ALevel: Integer);
    function FindClosestNode(const APath: string): TNode;
    function GetFriendlyText(AMaxLevel: Integer): string; virtual;
    function GetPath(const APath: string): TNode;
    function GetSubKeyValue(const ASubKey: string): string;

    property DisplayValue: string read GetDisplayValue;
    property Key: string read FKey write FKey;
    property Parent: TNode read FParent;
    property ReplacementValue: string read FReplacementValue;
    property Score: Double read FScore write FScore;
    property ScoreBonus: Double read FScoreBonus;
    property SubKeys: TSkyStringList read FSubKeys; // KeyName: TNode
    property Value: string read GetValue write FValue;
  end;

  TPRecord = class(TNode)
  protected
    function GetValue: string; override;
    function GetDisplayValue: string; override;
  public
    constructor Create(const ANodeName: string; ARepDecoder: TCRepDecoder); reintroduce;

    procedure AdjustPValue;
    procedure AdjustNodeScores;

    function GetFriendlyText(AMaxLevel: Integer): string; override;
  end;

implementation

uses
  SysUtils;

{ TCRepDecoder }

procedure TCRepDecoder.AddCrep(const AContextRep: string);
var
  TheExpression: TExpression;
  TheString: string;
begin
  TheString := AContextRep;
  while TheString <> '' do
  begin
    TheExpression := ReadExpression(TheString, '');
    if TheExpression.Path = '' then
    begin
      GetPath('ZZError').Value := TheString;
      Exit;
    end;
    TheExpression := DecodeExpression(TheExpression);
    if TheExpression.Path = '' then
      Exit;
  end;
end;

procedure TCRepDecoder.AdjustNodeDisplayValue(ANode: TNode);
var
  TheNode: TNode;
  TheReplacementNode: TNode;
  I: Integer;
begin
  TheReplacementNode := FindClosestNode(ANode.Value);
  if TheReplacementNode <> nil then
    ANode.FReplacementValue := TheReplacementNode.Value;
  for I := 0 to ANode.SubKeys.Count - 1 do
  begin
    TheNode := ANode.SubKeys.Objects[I] as TNode;
    AdjustNodeDisplayValue(TheNode);
  end;
end;

function TCRepDecoder.CheckForMatchAndAdd(const AName, ATitle, AJob, ARef, APName: string): string;
var
  TheRef: string;
  TheJob: string;
  TheName: string;
  TheP: TPRecord;
  TheTitle: string;
  TheResult: string;
  // 0 - undefined
  // 1 - I
  // 2 - male
  // 3 - female
  TheAGenderRef, TheBGenderRef: Byte;
  I: Integer;
begin
  TheResult := '';
  if (APName = '') then
  begin
    Result := '';
    Exit;
  end;
  TheAGenderRef := 0;
  if (ARef = 'I') then
    TheAGenderRef := 1;
  if SameText(ARef, 'he') or SameText(ARef, 'him') or SameText(ARef, 'his') then
    TheAGenderRef := 2;
  if SameText(ARef, 'her') or SameText(ARef, 'she') or SameText(ARef, 'hers') then
    TheAGenderRef := 3;

  if (AName <> '') or (ATitle <> '') or (AJob <> '') or (ARef <> '') then
  begin
    for I := 0 to PItems.Count - 1 do
    begin
      TheP := PItems.Objects[I] as TPRecord;
      TheName := TheP.GetSubKeyValue('name');
      TheTitle := TheP.GetSubKeyValue('title');
      TheJob := TheP.GetSubKeyValue('job');
      TheRef := TheP.GetSubKeyValue('ref');
      TheBGenderRef := 0;
      if (TheRef = 'I') then
        TheBGenderRef := 1;
      if SameText(TheRef, 'he') or SameText(TheRef, 'him') or SameText(TheRef, 'his') then
        TheBGenderRef := 2;
      if SameText(TheRef, 'her') or SameText(TheRef, 'she') or SameText(TheRef, 'hers') then
        TheBGenderRef := 3;

      // if properties different, move on
      if (TheName <> '') and (AName <> '') and (TheName <> AName) then
        Continue;
      if (TheTitle <> '') and (ATitle <> '') and (TheTitle <> ATitle) then
        Continue;
      if (TheJob <> '') and (AJob <> '') and (TheJob <> AJob) then
        Continue;
      if (TheBGenderRef <> 0) and (TheAGenderRef <> 0) and (TheBGenderRef <> TheAGenderRef) then
        Continue;
      // do not mix P's with G's dogs and stuff
      if APName[1] <> PItems[I][1] then
        Continue;

      // if properties missing: add
      if (AName <> '') and (TheName = '') then
        TheP.SubKeys.AddObject('name', TNode.Create(TheP, 'name', Self, AName));
      if (ATitle <> '') and (TheTitle = '') then
        TheP.SubKeys.AddObject('title', TNode.Create(TheP, 'title', Self, ATitle));
      if (AJob <> '') and (TheJob = '') then
        TheP.SubKeys.AddObject('job', TNode.Create(TheP, 'job', Self, AJob));
      if TheBGenderRef = 0 then
      begin
        case TheAGenderRef of
          1: TheP.SubKeys.AddObject('ref', TNode.Create(TheP, 'ref', Self, 'I'));
          2: TheP.SubKeys.AddObject('ref', TNode.Create(TheP, 'ref', Self, 'He'));
          3: TheP.SubKeys.AddObject('ref', TNode.Create(TheP, 'ref', Self, 'Her'));
        end;
      end;

      TheResult := PItems[I];
      Break;
    end;
  end;

  // P name was correct, do nothing
  if TheResult = APName then
  begin
    Result := TheResult;
    Exit;
  end;

  if TheResult = '' then
  begin
    // if the name was not found, find a suitable name
    Result := APName;
    if FindPath(Result) <> nil then
    begin
      if CharInSet(Result[Length(Result)], ['0'..'9']) then
      begin
        I := StrToIntDef(Result[Length(Result)], 1);
        SetLength(Result, Length(Result) - 1);
      end
      else
        I := 1;

      while FindPath(Result + IntToStr(I)) <> nil do
        Inc(I);
      Result := Result + IntToStr(I);
    end;
  end
  else
    // if the node was found with a different p
    Result := TheResult;

  if (AName = '') and (ATitle = '') and (AJob = '') and (ARef = '') then
    Exit;

  // P name not found or was renamed, adding
  TheP := GetPath(Result) as TPRecord;
  TheName := TheP.GetSubKeyValue('name');
  TheTitle := TheP.GetSubKeyValue('title');
  TheJob := TheP.GetSubKeyValue('job');
  TheRef := TheP.GetSubKeyValue('ref');

  // if properties missing: add
  if (AName <> '') and (TheName = '') then
    TheP.SubKeys.AddObject('name', TNode.Create(TheP, 'name', Self, AName));
  if (ATitle <> '') and (TheTitle = '') then
    TheP.SubKeys.AddObject('title', TNode.Create(TheP, 'title', Self, ATitle));
  if (AJob <> '') and (TheJob = '') then
    TheP.SubKeys.AddObject('job', TNode.Create(TheP, 'job', Self, AJob));
  if (ARef <> '') and (TheRef = '') then
    TheP.SubKeys.AddObject('ref', TNode.Create(TheP, 'ref', Self, ARef));
end;

procedure TCRepDecoder.Clear;
begin
  FWords.Clear;
  FPItems.Clear;
  FMaxLevel := 0;
end;

constructor TCRepDecoder.Create;
begin
  FPItems := TSkyStringList.Create;
  FPItems.OwnsObjects := True;
  FWords := TSkyStringList.Create;
  FWords.OwnsObjects := False;
end;

destructor TCRepDecoder.Destroy;
begin
  FPItems.Free;
  FWords.Free;
  inherited;
end;

function TCRepDecoder.FindClosestNode(const APath: string): TNode;
var
  ThePath: string;
  ThePName: string;
  TheIndex: Integer;
begin
  ThePath := APath;
  ThePName := '';
  TheIndex := 1;
  // read the path
  while TheIndex <= Length(ThePath) do
  begin
    if (ThePath[TheIndex] = '.') then
      Break;
    ThePName := ThePName + ThePath[TheIndex];
    TheIndex := TheIndex + 1;
  end;
  Delete(ThePath, 1, TheIndex);
  TheIndex := PItems.IndexOf(string(ThePName));
  if TheIndex <> -1 then
    Result := (PItems.Objects[TheIndex] as TPRecord).FindClosestNode(ThePath)
  else
    Result := nil;
end;

function TCRepDecoder.FindPath(const APath: string): TNode;
var
  ThePath: string;
  ThePName: string;
  TheIndex: Integer;
  ThePIndex: Integer;
begin
  ThePath := APath;
  ThePName := '';
  TheIndex := 1;
  // read the path
  while TheIndex <= Length(ThePath) do
  begin
    if (ThePath[TheIndex] = '.') then
      Break;
    ThePName := ThePName + ThePath[TheIndex];
    TheIndex := TheIndex + 1;
  end;
  Delete(ThePath, 1, TheIndex);
  ThePIndex := PItems.IndexOf(string(ThePName));
  if ThePIndex = -1 then
    Result := nil
  else
    Result := (PItems.Objects[ThePIndex] as TPRecord).GetPath(ThePath);
end;

procedure TCRepDecoder.GetBestScoreForWord(const AWord: string; AWordList: TSkyStringList; var AResult: string;
  var AResultScore: Double);
var
  TheFoundValue, TheFoundKey, TheLastFoundKey, TheFoundGap, TheBestHasGap: Boolean;
  TheInitialNode: TNode;
  TheMaxScore, TheScore: Double;
  TheNode, TheBestNode, TheResultNode: TNode;
  TheHits, TheLevels: Integer;
  I: Integer;
begin
  TheBestNode := nil;
  TheMaxScore := 0;
  TheFoundGap := False;
  TheBestHasGap := False;
  for I := 0 to Words.Count - 1 do
  begin
    if not SameText(AWord, Words[I]) then
      Continue;
    TheHits := 0;
    TheLevels := 0;

    TheInitialNode := Words.Objects[I] as TNode;

    TheNode := TheInitialNode;
    TheResultNode := TheInitialNode;
    TheFoundValue := False;
    TheLastFoundKey := True;
    while TheNode.Parent <> nil do
    begin
      TheLevels := TheLevels + 1;
      TheFoundKey := AWordList.IndexOf(TheNode.Key) <> -1;
      TheFoundGap := (not TheLastFoundKey) and (TheFoundKey);
      TheLastFoundKey := TheFoundKey;
      if (not TheFoundValue) then
      begin
        TheFoundValue := TheFoundValue or (AWordList.IndexOf(TheNode.Value) = -1);
        TheResultNode := TheNode;
      end;
      if TheFoundKey then
        TheHits := TheHits + 1;
      TheNode := TheNode.Parent;
    end;
    if TheLevels > 0 then
      TheScore := TheInitialNode.Score + TheInitialNode.ScoreBonus * (TheHits / TheLevels)
    else
      TheScore := TheInitialNode.Score;

    TheNode := FindPath(TheResultNode.TopParent.Key + '.name');
    if (TheNode <> nil) and ((AWordList.IndexOf(TheNode.Value) <> -1)) then
      TheScore := TheScore * ConstNameMatchFound
    else begin
      TheNode := FindPath(TheResultNode.TopParent.Key + '.ref');
      if (TheNode <> nil) and (TheNode.Value = 'I') and (
        (AWordList.IndexOf('speaker') <> -1) or
        (AWordList.IndexOf('writer') <> -1) or
        (AWordList.IndexOf('author') <> -1)
      ) then
        TheScore := TheScore * ConstNameIMatch
      else
      begin
        if (TheNode <> nil) and (TheNode.Value = 'He') and (
          (AWordList.IndexOf('he') <> -1) or
          (AWordList.IndexOf('him') <> -1) or
          (AWordList.IndexOf('his') <> -1)
        ) then
          TheScore := TheScore * ConstNameHeSheMatch
        else       if (TheNode <> nil) and (TheNode.Value = 'She') and (
          (AWordList.IndexOf('she') <> -1) or
          (AWordList.IndexOf('her') <> -1) or
          (AWordList.IndexOf('hers') <> -1)
        ) then
          TheScore := TheScore * ConstNameHeSheMatch;
      end;
    end;

    if TheScore < TheMaxScore then
      Continue;
    TheBestNode := TheResultNode;
    TheMaxScore := TheScore;
    TheBestHasGap := TheFoundGap;
  end;

  if TheBestNode = nil then
    Exit;

  if TheMaxScore <= AResultScore then
    Exit;

  AResultScore := TheMaxScore;
  if TheBestHasGap then
    AResult := 'Yes'
  else
    AResult := TheBestNode.GetNiceValue;
end;

function TCRepDecoder.GetFriendlyText(AMaxLevel: Integer): string;
var
  TheString: string;
  I: Integer;
begin
  Result := '';
  for I := 0 to PItems.Count - 1 do
    if (PItems[I] <> '') and (PItems[I] <> 'ZZError') then
    begin
      if AMaxLevel < 1 then
        Result := Result + #13#10#13#10 + (PItems.Objects[I] as TNode).Value
      else
      begin
        TheString := (PItems.Objects[I] as TPRecord).GetFriendlyText(AMaxLevel);
        if TheString <> '' then
          Result := Result + #13#10#13#10 + TheString;
      end;
    end;
  Result := Trim(Result);
end;

function TCRepDecoder.GetPath(const APath: string): TNode;
var
  ThePath: string;
  ThePName: string;
  TheIndex: Integer;
  ThePIndex: Integer;
begin
  ThePath := APath;
  ThePName := '';
  TheIndex := 1;
  // read the path
  while TheIndex <= Length(ThePath) do
  begin
    if (ThePath[TheIndex] = '.') then
      Break;
    ThePName := ThePName + ThePath[TheIndex];
    TheIndex := TheIndex + 1;
  end;
  Delete(ThePath, 1, TheIndex);
  ThePIndex := PItems.IndexOf(string(ThePName));
  if ThePIndex = -1 then
    ThePIndex := PItems.AddObject(string(ThePName), TPRecord.Create(string(ThePName), self));
  if ThePath = '' then
    Result := PItems.Objects[ThePIndex] as TNode
  else
    Result := (PItems.Objects[ThePIndex] as TPRecord).GetPath(ThePath);
end;

procedure TCRepDecoder.PostProcess;
var
  TheNode: TPRecord;
  I: Integer;
begin
  for I := 0 to PItems.Count - 1 do
  begin
    TheNode := PItems.Objects[I] as TPRecord;
    TheNode.AdjustPValue;
    Words.AddObject(TheNode.ReplacementValue, TheNode);
    TheNode.AdjustNodeScores;
  end;

  for I := 0 to PItems.Count - 1 do
  begin
    TheNode := PItems.Objects[I] as TPRecord;
    AdjustNodeDisplayValue(TheNode);
  end;
end;

function TCRepDecoder.DecodeExpression(AnExpression: TExpression): TExpression;
var
  TheNode: TNode;
  TheValue: string;
begin
  TheNode := GetPath(AnExpression.Path);
  if (TheNode.Value = '') or (Length(TheNode.Value) < Length(AnExpression.KeyValue)) then
    TheNode.Value := AnExpression.KeyValue;

  TheValue := AnExpression.Value;
  while TheValue <> '' do
  begin
    Result := DecodeExpression(ReadExpression(TheValue, AnExpression.Path));
    if Result.Path = '' then
    begin
      GetPath('ZZError').Value := AnExpression.Value;
      Exit;
    end;
  end;
  Result.Path := AnExpression.Path;
end;

function TCRepDecoder.ReadExpression(var AString: string; const APath: string): TExpression;
var
  TheIndex: Integer;
  ThePath: string;
  TheKeyValue: string;
  TheValue: string;
  TheLevel: Integer;
begin
  Result.Value := '';
  Result.KeyValue := '';
  Result.Path := '';

  TheIndex := 1;
  ThePath := '';
  // read the path
  while TheIndex <= Length(AString) do
  begin
    if (AString[TheIndex] = '(') or (AString[TheIndex] = '=') then
      Break;
    ThePath := ThePath + AString[TheIndex];
    TheIndex := TheIndex + 1;
  end;
  ThePath := Trim(ThePath);
  // invalid result
  if (TheIndex > Length(AString)) or (ThePath = '') or ((ThePath[1] = '.') and (APath = '')) or (Pos('.', ThePath) = 0) then
    Exit;

  // read the keyvalue
  TheKeyValue := '';
  if (AString[TheIndex] = '=') then
  begin
    TheIndex := TheIndex + 1;
    while TheIndex <= Length(AString) do
    begin
      if (AString[TheIndex] = '(') or (AString[TheIndex] = ',') then
        Break;
      TheKeyValue := TheKeyValue + AString[TheIndex];
      TheIndex := TheIndex + 1;
    end;
    TheKeyValue := Trim(TheKeyValue);
    // invalid result
    if TheKeyValue = '' then
      Exit;
  end;

  TheValue := '';
  if (TheIndex <= Length(AString)) and (AString[TheIndex] = '(')then
  begin
    TheLevel := 1;
    TheIndex := TheIndex + 1;
    while (TheIndex <= Length(AString)) do
    begin
      if AString[TheIndex] = ')' then
        TheLevel := TheLevel - 1
      else if (AString[TheIndex] = ',') and (TheLevel = 0) then
        Break;

      if TheLevel > 0 then
        TheValue := TheValue + AString[TheIndex];

      if AString[TheIndex] = '(' then
        TheLevel := TheLevel + 1;
      TheIndex := TheIndex + 1;
    end;
    TheValue := Trim(TheValue);
  end;

  if (TheKeyValue = '') and (TheValue = '') then
    Exit;

  Delete(AString, 1, TheIndex);
  Result.Value := TheValue;
  Result.KeyValue := TheKeyValue;
  Result.Path := APath + ThePath;
end;

{ TNode }

procedure TNode.AdjustNodeBonusScore(const ALevel: Integer);
var
  I: Integer;
begin
  FScoreBonus := ALevel * ConstLevelBonus;
  if FRepDecoder.MaxLevel < ALevel then
    FRepDecoder.MaxLevel := ALevel;
  for I := 0 to SubKeys.Count - 1 do
    (SubKeys.Objects[I] as TNode).AdjustNodeBonusScore(ALevel + 1);
end;

constructor TNode.Create(AParent: TNode; const ANodeName: string; ARepDecoder: TCRepDecoder; const AValue: string);
begin
  FSubKeys := TSkyStringList.Create;
  FSubKeys.OwnsObjects := True;
  FScore := 1;
  FScoreBonus := 0;
  FParent := AParent;
  FRepDecoder := ARepDecoder;
  FKey := ANodeName;
  ARepDecoder.Words.AddObject(ANodeName, Self);
  FValue := AValue;
end;

destructor TNode.Destroy;
begin
  FSubKeys.Free;
  inherited;
end;

function TNode.FindClosestNode(const APath: string): TNode;
var
  ThePath: string;
  ThePName: string;
  TheIndex: Integer;
  TheNIndex: Integer;
begin
  ThePath := APath;
  ThePName := '';
  TheIndex := 1;
  // read the path
  while TheIndex <= Length(ThePath) do
  begin
    if (APath[TheIndex] = '.') then
      Break;
    ThePName := ThePName + ThePath[TheIndex];
    TheIndex := TheIndex + 1;
  end;
  Delete(ThePath, 1, TheIndex);
  TheNIndex := SubKeys.IndexOf(ThePName);
  if TheNIndex = -1 then
    Result := Self
  else
  begin
    Result := SubKeys.Objects[TheNIndex] as TNode;
    if ThePath <> '' then
      Result := Result.FindClosestNode(ThePath);
  end;
end;

function TNode.GetDisplayValue: string;
begin
  if FReplacementValue <> '' then
    Result := FReplacementValue + '[' + Value + ']'
  else
    Result := Value;
end;

function TNode.GetKeyName(const AKeyName: string): string;
begin
  if SameText(AKeyName, 'where') or SameText(AKeyName, 'gender') or SameText(AKeyName, 'phase') then
    Result := ' is'
  else if SameText(AKeyName, 'what') or SameText(AKeyName, 'when') or SameText(AKeyName, 'why') or
    SameText(AKeyName, 'how much')then
    Result := ''
  else
   Result := ' ' + AKeyName;
end;

function TNode.GetNiceValue: string;
begin
  if FReplacementValue <> '' then
    Result := FReplacementValue
  else
    Result := FValue;
end;

function TNode.GetFriendlyText(AMaxLevel: Integer): string;
var
  TheKey: string;
  TheValue: string;
  I: Integer;
begin
  if FReplacementValue <> '' then
    Result := FReplacementValue
  else
    Result := FValue;
  if AMaxLevel < 1 then
    Exit;
  for I := 0 to SubKeys.Count - 1 do
  begin
    TheKey := SubKeys[I];
    TheValue := (SubKeys.Objects[I] as TNode).GetFriendlyText(AMaxLevel - 1);

    if SameText(TheKey, 'how') then
      if (Parent = nil) or (Parent.Value = TheValue) then
        Result := TheValue + ' ' + Result
      else
    else
      Result := Result + GetKeyName(TheKey) + ' ' + TheValue;
  end;
  Result := Trim(Result);
end;

function TNode.GetPath(const APath: string): TNode;
var
  ThePath: string;
  ThePName: string;
  TheIndex: Integer;
  TheNIndex: Integer;
begin
  ThePath := APath;
  ThePName := '';
  TheIndex := 1;
  // read the path
  while TheIndex <= Length(ThePath) do
  begin
    if (APath[TheIndex] = '.') then
      Break;
    ThePName := ThePName + ThePath[TheIndex];
    TheIndex := TheIndex + 1;
  end;
  Delete(ThePath, 1, TheIndex);
  TheNIndex := SubKeys.IndexOf(ThePName);
  if TheNIndex = -1 then
    TheNIndex := SubKeys.AddObject(ThePName, TNode.Create(Self, ThePName, FRepDecoder));
  Result := SubKeys.Objects[TheNIndex] as TNode;
  if ThePath <> '' then
   Result := Result.GetPath(ThePath);
end;

function TNode.GetSubKeyValue(const ASubKey: string): string;
var
  TheIndex: Integer;
begin
  Result := '';
  TheIndex := SubKeys.IndexOf(ASubKey);
  if TheIndex = -1 then
    Exit;
  Result := (SubKeys.Objects[TheIndex] as TNode).Value;
end;

function TNode.GetValue: string;
begin
  if (FValue = '') and (SubKeys.Count > 0) then
    Result := (SubKeys.Objects[0] as TNode).Value
  else
    Result := FValue;
end;

function TNode.TopParent: TNode;
begin
  Result := Self;
  while Result.Parent <> nil do
    Result := Result.Parent;
end;

{ TPRecord }

procedure TPRecord.AdjustNodeScores;
var
  TheNode: TNode;
  I: Integer;
begin
  for I := 0 to SubKeys.Count - 1 do
  begin
    TheNode := SubKeys.Objects[I] as TNode;
    TheNode.AdjustNodeBonusScore(1);
    if SubKeys[I] = 'name' then
      TheNode.Score := ConstNameScore
    else if SubKeys[I] = 'ref' then
      TheNode.Score := ConstRefScore;
  end;
end;

procedure TPRecord.AdjustPValue;
begin
  if SubKeys.Count = 0 then
    Exit;
  FReplacementValue := GetSubKeyValue('name');
  if FReplacementValue <> '' then
    Exit;
  FReplacementValue := GetSubKeyValue('title');
  if FReplacementValue <> '' then
    Exit;
  FReplacementValue := GetSubKeyValue('job');
  if FReplacementValue <> '' then
    Exit;
  FReplacementValue := GetSubKeyValue('ref');
  if FReplacementValue <> '' then
    Exit;
  FReplacementValue := (SubKeys.Objects[0] as TNode).Value;
end;

constructor TPRecord.Create(const ANodeName: string; ARepDecoder: TCRepDecoder);
begin
  inherited Create(nil, ANodeName, ARepDecoder);
end;

function TPRecord.GetDisplayValue: string;
begin
  Result := FReplacementValue;
end;

function TPRecord.GetFriendlyText(AMaxLevel: Integer): string;
var
  TheFirst: Boolean;
  TheRef: string;
  TheValue1: string;
  TheValue2: string;
  I: Integer;
begin
  TheRef := GetSubKeyValue('ref');
  if TheRef = 'I' then
  begin
    TheValue1 := GetSubKeyValue('name');
    if TheValue1 = '' then
      TheValue1 := 'Author';
    TheValue2 := 'He/She';
  end
  else
  begin
    TheValue1 := Value;
    TheValue2 := TheRef;
  end;
  Result := '';
  TheFirst := True;
  for I := 0 to SubKeys.Count - 1 do
    if (SubKeys[I] <> 'ref') and (SubKeys[I] <> 'name') then
    begin
      if TheFirst then
        Result := Result + ' ' + TheValue1 + ' ' + SubKeys[I] + ' ' + (SubKeys.Objects[I] as TNode).GetFriendlyText(AMaxLevel - 1) + '.'
      else
        Result := Result + ' ' + TheValue2 + ' ' + SubKeys[I] + ' ' + (SubKeys.Objects[I] as TNode).GetFriendlyText(AMaxLevel - 1) + '.';
      TheFirst := False;
    end;
  Result := Trim(Result);
end;

function TPRecord.GetValue: string;
begin
  Result := FReplacementValue;
end;

end.
