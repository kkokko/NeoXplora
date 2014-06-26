unit ProtoContainer;

{$mode objfpc}{$H+}

interface

uses
  StoryObject, Forms, ProtoObject, SentenceSplitter;

type

  { TProtoContainer }

  TProtoContainer = class
  private
    FSplitter: TSentenceSplitter;
    FStory: TStoryObject;
    FScrollBox: TScrollBox;
    // check empty proto and delete, if the proto is still one sentence
    // ignore and parse sentences, else split proto and insert in list
    procedure SplitProto(AnIndex: Integer; AProtoLvl1, AProtoLvl2: TProtoObject);

    // check empty sentence and delete, otherwise recreate/split the sentence
    // recreate is needed for keeping the Story.Sentences list sorted
    procedure SplitSentence(AnIndex: Integer; AProtoLvl2: TProtoObject);
  public
    constructor Create(AScrollBox: TScrollBox; AStory: TStoryObject); reintroduce;
    destructor Destroy; override;

    procedure SplitAll;
    procedure ReCreateProtoPanels;
    procedure UpdateProtoFromBody;

    property Story: TStoryObject read FStory write FStory;
  end;

implementation

uses
  SentenceObject, PanelProto, PanelSentence;

{ TProtoContainer }

constructor TProtoContainer.Create(AScrollBox: TScrollBox;
  AStory: TStoryObject);
begin
  FSplitter := TSentenceSplitter.Create;
  FScrollBox := AScrollBox;
  FStory := AStory;
end;

destructor TProtoContainer.Destroy;
begin
  FSplitter.Free;
  inherited Destroy;
end;

procedure TProtoContainer.SplitProto(AnIndex: Integer; AProtoLvl1, AProtoLvl2: TProtoObject);
var
  TheSentence: TSentenceObject;
  TheNewSentence: TSentenceObject;
  TheSentenceStr: string;
  I: Integer;
begin
  FSplitter.StorySplitProtos(AProtoLvl2.Name);
  // if empty proto text, delete
  if FSplitter.WordList.Count = 0 then
  begin
    for I := 0 to AProtoLvl2.Sentences.Count - 1 do
      FStory.Sentences.Delete(AProtoLvl2.Sentences[I]);
    FStory.Protos.DeleteFromIndex(AnIndex);
    Exit;
  end;
  // if the proto is still one sentence, process sentences
  if FSplitter.WordList.Count = 1 then
  begin
    AProtoLvl2.Name := FSplitter.WordList[0];
    for I := 0 to AProtoLvl2.Sentences.Count - 1 do
      SplitSentence(I, AProtoLvl2);
    // clear the sentence list to get rid of invalid pointers
    AProtoLvl2.Sentences.Clear;
    Exit;
  end;

  TheSentence := nil;
  try
    if AProtoLvl2.Sentences.Count > 0 then
    begin
      TheSentence := AProtoLvl2.Sentences[0].CreateACopy as TSentenceObject;
      TheSentence.Id := 0;
      TheSentence.ExistsInDatabase := False;
    end;
    // if the proto was modified: delete sentences, proto,
    // create new protos, create sentences
    for I := 0 to AProtoLvl2.Sentences.Count - 1 do
      FStory.Sentences.Delete(AProtoLvl2.Sentences[I]);
    FStory.Protos.DeleteFromIndex(AnIndex);
    for I := 0 to FSplitter.WordList.Count - 1 do
    begin
      TheSentenceStr := FSplitter.WordList[I];
      AProtoLvl2 := TProtoObject.Create(TheSentenceStr, 2);
      FStory.Protos.InsertItem(AnIndex + I, AProtoLvl2, nil);
      if TheSentence = nil then
        TheNewSentence := TSentenceObject.Create(TheSentenceStr, AProtoLvl1, AProtoLvl2)
      else
      begin
        TheNewSentence := TheSentence.CreateACopy as TSentenceObject;
        TheNewSentence.Name := TheSentenceStr;
      end;
      FStory.Sentences.Add(TheNewSentence);
    end;
  finally
    TheSentence.Free;
  end;
end;

procedure TProtoContainer.SplitSentence(AnIndex: Integer; AProtoLvl2: TProtoObject);
var
  TheSentence, TheNewSentence: TSentenceObject;
  I: Integer;
begin
  TheSentence := AProtoLvl2.Sentences[AnIndex] as TSentenceObject;
  FSplitter.StorySplitProtos(TheSentence.Name);
  for I := 0 to FSplitter.WordList.Count - 1 do
  begin
    TheNewSentence := TheSentence.CreateACopy as TSentenceObject;
    TheNewSentence.Id := 0;
    TheNewSentence.ExistsInDatabase := False;
    TheNewSentence.Name := FSplitter.WordList[I];
    FStory.Sentences.Add(TheNewSentence);
  end;
  FStory.Sentences.Delete(TheSentence);
end;

procedure TProtoContainer.SplitAll;
var
  TheIndex: Integer;
  TheProto: TProtoObject;
  TheProtoLvl1: TProtoObject;
begin
  TheProtoLvl1 := nil;
  TheIndex := 0;
  while TheIndex < FStory.Protos.Count do
  begin
    TheProto := FStory.Protos[TheIndex] as TProtoObject;
    if TheProto.Level = 1 then
    begin
      TheProtoLvl1 := TheProto;
      FSplitter.StorySplitProtos(TheProto.Name);
      if FSplitter.WordList.Count = 1 then
        TheProto.Name := FSplitter.WordList[0];
    end
    else
      SplitProto(TheIndex, TheProtoLvl1, TheProto);
    Inc(TheIndex);
  end;
end;

procedure TProtoContainer.ReCreateProtoPanels;
var
  TheProtoPanel: TPanelProto;
  TheProtoPanelLevel1: TPanelProto;
  TheLevel1Index, TheLevel2Index, TheLevel3Index: Integer;
  TheProto: TProtoObject;
  TheSentence: TSentenceObject;
  I, J: Integer;
begin
  FStory.UpdateSentenceLinks;
  for I := FScrollBox.ControlCount - 1 downto 0 do
    FScrollBox.Controls[I].Free;
  TheLevel1Index := 0;
  TheLevel2Index := 1;
  for I := 0 to FStory.Protos.Count - 1 do
  begin
    TheProto := FStory.Protos[I] as TProtoObject;
    if TheProto.Level = 1 then
    begin
      TheProtoPanel := TPanelProto.Create(FScrollBox, TheLevel1Index, TheProto);
      Inc(TheLevel1Index);
      TheLevel2Index := 1;
      TheProtoPanelLevel1 := TheProtoPanel;
    end
    else
    begin
      TheProtoPanelLevel1.NotifyChildAdded;
      TheProtoPanel := TPanelProto.Create(TheProtoPanelLevel1, TheLevel2Index, TheProto);
      Inc(TheLevel1Index);
      Inc(TheLevel2Index);
      TheLevel3Index := 1;
      for J := 0 to TheProto.Sentences.Count - 1 do
      begin
        TheSentence := TheProto.Sentences[J] as TSentenceObject;
        TheProtoPanelLevel1.NotifyChildAdded;
        TheProtoPanel.NotifyChildAdded;
        TPanelSentence.Create(TheProtoPanel, TheLevel3Index, TheSentence);
        Inc(TheLevel1Index);
        Inc(TheLevel2Index);
        Inc(TheLevel3Index);
      end;
    end;
  end;
end;

procedure TProtoContainer.UpdateProtoFromBody;
var
  I: Integer;
begin
  FStory.Sentences.Clear;
  FSplitter.StorySplitProtos(FStory.Body);
  FStory.Protos.Clear;
  for I := 0 to FSplitter.WordList.Count -1 do
    FStory.CreateNewProtoSentence(FSplitter.WordList[I]);
end;

end.

