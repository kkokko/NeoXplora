unit Hypernym;

interface

uses
  Classes, SkyLists;

type
  PHyperNode = ^THyperNode;
  THyperNode = record
    Brother: PHyperNode;
    Parent: PHyperNode;
  end;

  // used for searching inside the graph without a matrix
  PPLevelNode = ^PLevelNode;
  PLevelNode = ^TLevelNode;
  TLevelNode = record
    Level: Integer;
    LevelBrother: PLevelNode;
    Node: PHyperNode;
  end;

  THyperNym = class
  private
    FSearch1, FSearch2: TSkyObjectList;
    FWordList: TStringList;
    function CalculateNodeDistance(ANode1, ANode2: PHyperNode): Integer;
    function CheckNodeHasParent(ANode, AParent: PHyperNode): Boolean;
    function CreateLevelNode(AHyperNode: PHyperNode; ALevel: Integer): PLevelNode;
    function CreateBrotherNode(ANode: PHyperNode): PHyperNode;
    function CreateNewNode(const AWord: string): PHyperNode;
    function ParseLevelNodeParents(AOwnList, AnotherList: TSkyObjectList; ALevel: Integer;
      AOwnLevel: PPLevelNode; AnOtherLevel: PLevelNode): Integer;
    function ParseLevelSearchParent(ALevel: PLevelNode; ANode: PHyperNode): Boolean;
    procedure ReleaseLevelNode(ANode: PLevelNode);
    procedure ReleaseNode(ANode: PHyperNode);
  public
    procedure Add(const AWord, AParent: string);
    procedure Clear;
    procedure LoadRepsFromString(const AString: string);

    function GetSimilarityScore(const AWord1, AWord2: string): Double;

    constructor Create;
    destructor Destroy; override;
  end;

implementation

uses
  Math, SysUtils, AppConsts;

{ THyperNym }

procedure THyperNym.Add(const AWord, AParent: string);
var
  TheParentNode: PHyperNode;
  TheWordNode: PHyperNode;
  TheIndex: Integer;
begin
  if FWordList.Find(AWord, TheIndex) then
    TheWordNode := PHyperNode(FWordList.Objects[TheIndex])
  else
    TheWordNode := nil;
  if FWordList.Find(AParent, TheIndex) then
    TheParentNode := PHyperNode(FWordList.Objects[TheIndex])
  else
    TheParentNode := nil;

  // if any of them is nil, no loop checks are needed
  if (TheWordNode = nil) or (TheParentNode = nil) then
  begin
    if TheParentNode = nil then
      TheParentNode := CreateNewNode(AParent);
    if TheWordNode = nil then
      TheWordNode := CreateNewNode(AWord)
    else if TheWordNode^.Parent <> nil then
      TheWordNode := CreateBrotherNode(TheWordNode);
    TheWordNode^.Parent := TheParentNode;
    Exit;
  end;

  // if a loop would be created then exit
  if CheckNodeHasParent(TheParentNode, TheWordNode) then
    Exit;

  TheWordNode := CreateBrotherNode(TheWordNode);
  TheWordNode^.Parent := TheParentNode;
end;

function THyperNym.ParseLevelSearchParent(ALevel: PLevelNode; ANode: PHyperNode): Boolean;
var
  TheElement: PLevelNode;
  TheNode: PHyperNode;
begin
  TheElement := ALevel;
  while (TheElement <> nil) do
  begin
    TheNode := TheElement^.Node;
    while TheNode <> nil do
    begin
      if TheNode = ANode then
      begin
        Result := True;
        Exit;
      end;
      TheNode := TheNode^.Brother;
    end;
    TheElement := TheElement^.LevelBrother;
  end;
  Result := False;
end;

function THyperNym.ParseLevelNodeParents(AOwnList, AnotherList: TSkyObjectList; ALevel: Integer; AOwnLevel: PPLevelNode; AnOtherLevel: PLevelNode): Integer;
var
  TheIndex: Integer;
  TheElement, TempElement: PLevelNode;
  TheNewList: PLevelNode;
  TheNode: PHyperNode;
  TheResult: Integer;
begin
  Result := 0;
  TheElement := AOwnLevel^;
  TheNewList := nil;
  while (TheElement <> nil) do
  begin
    TheNode := TheElement^.Node;
    while TheNode <> nil do
    begin
      if (TheNode^.Parent <> nil) and (AOwnList.IndexOf(TObject(TheNode^.Parent)) = -1) then
      begin
        TheIndex := AnotherList.IndexOf(TObject(TheNode^.Parent));
        // record found, stop search
        if TheIndex <> -1 then
        begin
          TheResult := Integer(AnotherList.Objects[TheIndex]) + ALevel + 1;
          if (Result = 0) or (TheResult < Result) then
            Result := TheResult;
          TheNode := TheNode^.Brother;
          Continue;
        end;
        if ParseLevelSearchParent(AnOtherLevel, TheNode^.Parent) then
        begin
          TheResult := (ALevel + 1) * 2;
          if (Result = 0) or (TheResult < Result) then
            Result := TheResult;
          TheNode := TheNode^.Brother;
          Continue;
        end;
        TempElement := CreateLevelNode(TheNode^.Parent, ALevel + 1);
        TempElement^.LevelBrother := TheNewList;
        TheNewList := TempElement;
        AOwnList.Add(TObject(TheNode^.Parent), TObject(ALevel + 1));
      end;
      TheNode := TheNode^.Brother;
    end;
    TheElement := TheElement^.LevelBrother;
  end;
  ReleaseLevelNode(AOwnLevel^);
  AOwnLevel^ := TheNewList;
end;

function THyperNym.CalculateNodeDistance(ANode1, ANode2: PHyperNode): Integer;
var
  TheLevel: Integer;
  TheList1: PLevelNode;
  TheList2: PLevelNode;
begin
  Result := 0;
  TheLevel := 0;

  FSearch1.Clear;
  FSearch2.Clear;

  TheList1 := CreateLevelNode(ANode1, TheLevel);
  TheList2 := CreateLevelNode(ANode2, TheLevel);

  FSearch1.AddObject(TObject(ANode1), TObject(0));
  FSearch2.AddObject(TObject(ANode2), TObject(0));

  while (TheList1 <> nil) or (TheList2 <> nil) do
  begin
    if TheList1 <> nil then
    begin
      Result := ParseLevelNodeParents(FSearch1, FSearch2, TheLevel, @TheList1, TheList2);
      if Result <> 0 then
        Break;
    end;

    if TheList2 <> nil then
    begin
      Result := ParseLevelNodeParents(FSearch2, FSearch1, TheLevel, @TheList2, nil);
      if Result <> 0 then
        Break;
    end;

    Inc(TheLevel);
  end;
  ReleaseLevelNode(TheList1);
  ReleaseLevelNode(TheList2);
end;

function THyperNym.CheckNodeHasParent(ANode, AParent: PHyperNode): Boolean;
begin
  Result := ANode^.Parent = AParent;
  if (not Result) and (ANode^.Brother <> nil) then
    Result := CheckNodeHasParent(ANode^.Brother, AParent);
  if (not Result) and (ANode^.Parent <> nil) then
    Result := CheckNodeHasParent(ANode^.Parent, AParent);
end;

procedure THyperNym.Clear;
var
  I: Integer;
begin
  for I := 0 to FWordList.Count - 1 do
    ReleaseNode(PHyperNode(FWordList.Objects[I]));
  FWordList.Clear;
end;

function THyperNym.CreateBrotherNode(ANode: PHyperNode): PHyperNode;
begin
  New(Result);
  Result^.Brother := ANode^.Brother;
  ANode^.Brother := Result;
  Result^.Parent := nil;
end;

function THyperNym.CreateLevelNode(AHyperNode: PHyperNode; ALevel: Integer): PLevelNode;
begin
  New(Result);
  Result^.Level := ALevel;
  Result^.Node := AHyperNode;
  Result^.LevelBrother := nil;
end;

function THyperNym.CreateNewNode(const AWord: string): PHyperNode;
begin
  New(Result);
  Result^.Brother := nil;
  Result^.Parent := nil;

  FWordList.AddObject(AWord, TObject(Result));
end;

constructor THyperNym.Create;
begin
  FWordList := TStringList.Create;
  FWordList.OwnsObjects := False;
  FWordList.Sorted := True;
  FSearch1 := TSkyObjectList.Create;
  FSearch1.OwnsObjects := False;
  FSearch2 := TSkyObjectList.Create;
  FSearch2.OwnsObjects := False
end;

destructor THyperNym.Destroy;
var
  I: Integer;
begin
  for I := 0 to FWordList.Count - 1 do
    ReleaseNode(PHyperNode(FWordList.Objects[I]));
  FSearch1.Free;
  FSearch2.Free;
  FWordList.Free;
  inherited;
end;

function THyperNym.GetSimilarityScore(const AWord1, AWord2: string): Double;
var
  TheIndex: Integer;
  TheNode1, TheNode2: PHyperNode;
  TheNodeDistance: Integer;
begin
  Result := 0;
  if FWordList.Find(AWord1, TheIndex) then
    TheNode1 := PHyperNode(FWordList.Objects[TheIndex])
  else
    TheNode1 := nil;
  if FWordList.Find(AWord2, TheIndex) then
    TheNode2 := PHyperNode(FWordList.Objects[TheIndex])
  else
    TheNode2 := nil;
  if (TheNode1 = nil) or (TheNode2 = nil) then
    Exit;

  TheNodeDistance := CalculateNodeDistance(TheNode1, TheNode2);
  if TheNodeDistance = 0 then
    Exit;
  Result := TheNodeDistance;
end;

procedure THyperNym.LoadRepsFromString(const AString: string);
var
  TheIndex: Integer;
  TheIndex1: Integer;
  TheIndex2: Integer;
  TheIndex3: Integer;
  TheKey, TheValue: string;
  TheString: string;
begin
  TheString := AString;
  TheIndex := 1;
  while TheIndex <= Length(TheString) do
  begin
    TheIndex1 := Pos('eg(', AString) + 3;
    TheIndex2 := Pos('part(', AString) + 5;
    TheIndex3 := Pos('property(', AString) + 9;

    if (TheIndex1 = 3) and (TheIndex2 = 5) and (TheIndex3 = 9) then
      Exit;

    TheIndex := Length(TheString);
    if (TheIndex1 <> 3) then
      TheIndex := Min(TheIndex, TheIndex1);
    if (TheIndex2 <> 5) then
      TheIndex := Min(TheIndex, TheIndex2);
    if (TheIndex3 <> 9) then
      TheIndex := Min(TheIndex, TheIndex3);

    TheValue := '';
    while TheString[TheIndex] <> ')'  do
    begin
      TheValue := TheValue + TheString[TheIndex];
      Inc(TheIndex);
      if TheIndex > Length(TheString) then
        Exit;
    end;
    TheIndex := TheIndex + 4;
    TheKey := '';
    while (TheIndex <= Length(TheString)) and not CharInSet(TheString[TheIndex], ConstSplitChars) do
    begin
      TheKey := TheKey + TheString[TheIndex];
      Inc(TheIndex);
    end;
    self.Add(TheKey, TheValue);
    Delete(TheString, 1, TheIndex);
  end;
end;

procedure THyperNym.ReleaseLevelNode(ANode: PLevelNode);
begin
  if ANode = nil then
    Exit;
  if ANode^.LevelBrother <> nil then
    ReleaseLevelNode(ANode^.LevelBrother);
  Dispose(ANode);
end;

procedure THyperNym.ReleaseNode(ANode: PHyperNode);
begin
  if ANode^.Brother <> nil then
    ReleaseNode(ANode^.Brother);
  Dispose(ANode);
end;

end.
