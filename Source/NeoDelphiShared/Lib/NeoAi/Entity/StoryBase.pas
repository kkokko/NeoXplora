unit StoryBase;

interface

uses
  Entity, EntityFieldNamesToken;

type
  TStoryBase = class(TEntity)
  public
    type
      TStatus = (psFinishedGenerate, psTrainingSplit, psTrainedSplit, psReviewingSplit,
        psReviewedSplit, psTrainingRep, psTrainedRep, psReviewingRep, psReviewedRep, psTrainingCRep, psTrainedCRep,
        psReviewingCRep, psReviewedCRep);
  private
    FBody: string;
    FStatus: TStatus;
    FTitle: string;
    FSource: string;
  public
    constructor Create; override;
    class var
      Tok_Id: TEntityFieldNamesToken;
      Tok_Source: TEntityFieldNamesToken;
      Tok_Status: TEntityFieldNamesToken;
      Tok_Body: TEntityFieldNamesToken;
      Tok_Title: TEntityFieldNamesToken;
    class function EntityToken_Id: TEntityFieldNamesToken; override;
    class procedure RegisterFieldMappings;
  published
    property Id;
    property Body: string read FBody write FBody;
    property Source: string read FSource write FSource;
    property Status: TStatus read FStatus write FStatus;
    property Title: string read FTitle write FTitle;
  end;

implementation

uses
  EntityMappingManager, EntityMapping;

{ TStoryBase }

constructor TStoryBase.Create;
begin
  inherited;
  FStatus := psFinishedGenerate;
end;

class function TStoryBase.EntityToken_Id: TEntityFieldNamesToken;
begin
  Result := Tok_Id;
end;

class procedure TStoryBase.RegisterFieldMappings;
var
  TheManager: TEntityMapping;
begin
  TheManager := TEntityMappingManager.GetMapping(Self);
  TheManager.SetValueForField('Id', Tok_Id.PropertyName);
  TheManager.SetValueForField('Status', Tok_Status.PropertyName);
  TheManager.SetValueForField('Body', Tok_Body.PropertyName);
  TheManager.SetValueForField('Title', Tok_Title.PropertyName);
  TheManager.SetValueForField('Source', Tok_Source.PropertyName);
end;

initialization
  TStoryBase.RegisterEntityClassWithMappingToTable('page');
  TStoryBase.RegisterToken(TStoryBase.Tok_Id, 'pageId');
  TStoryBase.RegisterToken(TStoryBase.Tok_Status, 'pageStatus');
  TStoryBase.RegisterToken(TStoryBase.Tok_Body, 'body');
  TStoryBase.RegisterToken(TStoryBase.Tok_Title, 'title');
  TStoryBase.RegisterToken(TStoryBase.Tok_Source, 'source');
  TStoryBase.RegisterFieldMappings;

end.
