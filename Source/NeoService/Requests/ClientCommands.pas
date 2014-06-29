unit ClientCommands;

interface

uses
  ClientRequest, Command, Communication;

type
  TClientCommand = class(TCommand)
  public
    class procedure AfterExecute(ARequest: TRequest; AResponse: TGenericResponse); override;
    class function Execute(ARequest: TRequest; AnExtraData: TObject = nil): TGenericResponse; override;
    class function SessionRequired: Boolean; override;
  end;

  TCommandGuessRepsForSentenceId = class(TClientCommand)
  protected
    class function DoExecute(ARequest: TRequest): TGenericResponse; override;
  end;

  TCommandGetFullSentencesForStoryId = class(TClientCommand)
  protected
    class function DoExecute(ARequest: TRequest): TGenericResponse; override;
  end;

  TCommandGetPosForSentences = class(TClientCommand)
  protected
    class function DoExecute(ARequest: TRequest): TGenericResponse; override;
  end;

  TCommandPredictAfterSplit = class(TClientCommand)
  protected
    class function DoExecute(ARequest: TRequest): TGenericResponse; override;
  end;

  TCommandTrainUntrainedStories = class(TClientCommand)
  protected
    class function DoExecute(ARequest: TRequest): TGenericResponse; override;
  end;

  TCommandValidateRep = class(TClientCommand)
  protected
    class function DoExecute(ARequest: TRequest): TGenericResponse; override;
  end;

  TCommandSearch = class(TClientCommand)
  protected
    class function DoExecute(ARequest: TRequest): TGenericResponse; override;
  end;

  TCommandGetPosForPage = class(TClientCommand)
  protected
    class function DoExecute(ARequest: TRequest): TGenericResponse; override;
  end;

implementation

uses
  ActiveX, Entity, ServerCore, GuessObject, AppUnit, TypesConsts;

{ TClientCommand }

class procedure TClientCommand.AfterExecute(ARequest: TRequest; AResponse: TGenericResponse);
begin
  App.CloseDefaultDatabaseConnection;
end;

class function TClientCommand.Execute(ARequest: TRequest; AnExtraData: TObject = nil): TGenericResponse;
begin
  CoInitialize(nil);
  try
    Result := inherited Execute(ARequest, AnExtraData);
  finally
    CoUninitialize;
  end;
end;

class function TClientCommand.SessionRequired: Boolean;
begin
  Result := False;
end;

{ TCommandDataGetById }

class function TCommandGuessRepsForSentenceId.DoExecute(ARequest: TRequest): TGenericResponse;
var
  TheGuessObject: TGuessObject;
  TheRequest: TRequestGuessRepsForSentenceId;
begin
  TheRequest := ARequest as TRequestGuessRepsForSentenceId;
  TheGuessObject := Core.GuessRepsForSentenceId(TheRequest.SentenceId);
  Result := TResponseGuessRepsForSentenceId.Create(TheGuessObject);
end;

{ TCommandGetFullSentencesForStoryId }

class function TCommandGetFullSentencesForStoryId.DoExecute(ARequest: TRequest): TGenericResponse;
var
  TheSentences: TEntities;
  TheRequest: TRequestGetFullSentencesForStoryId;
begin
  TheRequest := ARequest as TRequestGetFullSentencesForStoryId;
  TheSentences := Core.GetFullSentencesForStoryId(TheRequest.StoryId);
  Result := TResponseGetFullSentencesForStoryId.Create(TheSentences);
end;

{ TCommandGetPosForSentences }

class function TCommandGetPosForSentences.DoExecute(ARequest: TRequest): TGenericResponse;
var
  TheSentences: TEntities;
  TheRequest: TRequestGetPosForSentences;
begin
  TheRequest := ARequest as TRequestGetPosForSentences;
  TheSentences := Core.GetPosForSentences(TheRequest.Sentences.GetAllEntities, TheRequest.UseModifiedPos);
  Result := TResponseGetPosForSentences.Create(TheSentences);
end;

{ TCommandPredictAfterSplit }

class function TCommandPredictAfterSplit.DoExecute(ARequest: TRequest): TGenericResponse;
var
  TheRequest: TRequestPredictAfterSplit;
begin
  TheRequest := ARequest as TRequestPredictAfterSplit;
  Core.PredictAfterSplit(TheRequest.Sentences.GetAllEntities);
  Result := nil;
end;

{ TCommandTrainUntrainedStories }

class function TCommandTrainUntrainedStories.DoExecute(ARequest: TRequest): TGenericResponse;
begin
  Core.TrainUntrainedStories;
  Result := nil;
end;

{ TCommandValidateRep }

class function TCommandValidateRep.DoExecute(ARequest: TRequest): TGenericResponse;
var
  TheRequest: TRequestValidateRep;
begin
  TheRequest := ARequest as TRequestValidateRep;
  Core.ValidateRep(TheRequest.Rep);
  Result := nil;
end;

{ TCommandSearch }

class function TCommandSearch.DoExecute(ARequest: TRequest): TGenericResponse;
var
  TheOffset: Integer;
  ThePageCount: Integer;
  ThePages: TEntities;
  TheRequest: TRequestSearch;
begin
  TheRequest := ARequest as TRequestSearch;
  TheOffset := TheRequest.Offset;
  Core.Search(TheRequest.SearchString, TheOffset, ThePages, ThePageCount);
  Result := TResponseSearch.Create(ThePages, TheOffset, ThePageCount);
end;

{ TCommandGetPosForPage }

class function TCommandGetPosForPage.DoExecute(ARequest: TRequest): TGenericResponse;
var
  TheSentences: TObjects;
  TheRequest: TRequestGetPosForPage;
begin
  TheRequest := ARequest as TRequestGetPosForPage;
  TheSentences := Core.GetPosForPage(TheRequest.Page, TheRequest.UseModifiedPos);
  Result := TResponseGetPosForPage.Create(TheSentences);
end;

initialization
  // please keep these sorted
  TCommandGuessRepsForSentenceId.RegisterClass(TRequestGuessRepsForSentenceId);
  TCommandGetPosForPage.RegisterClass(TRequestGetPosForPage);
  TCommandGetPosForSentences.RegisterClass(TRequestGetPosForSentences);
  TCommandGetFullSentencesForStoryId.RegisterClass(TRequestGetFullSentencesForStoryId);
  TCommandPredictAfterSplit.RegisterClass(TRequestPredictAfterSplit);
  TCommandSearch.RegisterClass(TRequestSearch);
  TCommandTrainUntrainedStories.RegisterClass(TRequestTrainUntrainedStories);
  TCommandValidateRep.RegisterClass(TRequestValidateRep);

end.