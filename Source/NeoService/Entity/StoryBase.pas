unit StoryBase;

interface

uses
  Entity, EntityFieldNamesToken;

type
  TStoryBase = class(TEntity)
  public
    type
      TPageStatus = (psFinishedCrawl, psFinishedGenerate, psTrainingSplit, psTrainedSplit, psReviewingSplit,
        psReviewedSplit, psTrainingRep, psTrainedRep, psReviewingRep, psReviewedRep, psTrainingCRep, psTrainedCRep,
        psReviewingCRep, psReviewedCRep);
    class var
      Tok_Id: TEntityFieldNamesToken;
      Tok_PageStatus: TEntityFieldNamesToken;
      Tok_Body: TEntityFieldNamesToken;
      Tok_Title: TEntityFieldNamesToken;
    class function EntityToken_Id: TEntityFieldNamesToken; override;
    class procedure RegisterFieldMappings;
  published
    property Id;
  end;

implementation

uses
  EntityMappingManager, EntityMapping;

{ TStoryBase }

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
  TheManager.SetValueForField('PageStatus', Tok_PageStatus.PropertyName);
  TheManager.SetValueForField('Body', Tok_Body.PropertyName);
  TheManager.SetValueForField('Title', Tok_Title.PropertyName);
end;

initialization
  TStoryBase.RegisterEntityClassWithMappingToTable('page');
  TStoryBase.RegisterToken(TStoryBase.Tok_Id, 'pageId');
  TStoryBase.RegisterToken(TStoryBase.Tok_PageStatus, 'pageStatus');
  TStoryBase.RegisterToken(TStoryBase.Tok_Body, 'body');
  TStoryBase.RegisterToken(TStoryBase.Tok_Title, 'title');

end.
