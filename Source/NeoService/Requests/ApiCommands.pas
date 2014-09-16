unit ApiCommands;

interface

uses
  ClientRequest, Command, Communication;

type
  TApiCommand = class(TCommand)
  public
    class procedure AfterExecute(ARequest: TRequest; AResponse: TGenericResponse); override;
    class function Execute(ARequest: TRequest; AnExtraData: TObject = nil): TGenericResponse; override;
    class function SessionRequired: Boolean; override;
  end;

  TApiCommandGenerateProtoGuess = class(TApiCommand)
  protected
    class function DoExecute(ARequest: TRequest): TGenericResponse; override;
  end;

  TApiCommandGenerateRep = class(TApiCommand)
  protected
    class function DoExecute(ARequest: TRequest): TGenericResponse; override;
  end;

implementation

uses
  ApiRequest, ServerCore, AppUnit, ActiveX;

{ TApiCommand }

class procedure TApiCommand.AfterExecute(ARequest: TRequest; AResponse: TGenericResponse);
begin
  App.CloseDefaultDatabaseConnection;
end;

class function TApiCommand.Execute(ARequest: TRequest; AnExtraData: TObject): TGenericResponse;
begin
  CoInitialize(nil);
  try
    Result := inherited Execute(ARequest, AnExtraData);
  finally
    CoUninitialize;
  end;
end;

class function TApiCommand.SessionRequired: Boolean;
begin
  Result := False;
end;

{ TApiCommandGenerateRep }

class function TApiCommandGenerateRep.DoExecute(ARequest: TRequest): TGenericResponse;
var
  TheRep: string;
  TheSentence: string;
  TheRequest: TApiRequestGenerateRep;
begin
  TheRequest := ARequest as TApiRequestGenerateRep;
  Core.ApiGenerateRep(TheRequest.SentenceText, TheRequest.ApiKey, TheRequest.OutputSentence, TheRep, TheSentence);
  Result := TApiResponseGenerateRep.Create(TheRep, TheSentence);
end;

{ TApiCommandGenerateProtoGuess }

class function TApiCommandGenerateProtoGuess.DoExecute(ARequest: TRequest): TGenericResponse;
var
  TheRequest: TApiRequestGenerateProtoGuess;
  TheResults: TServerCore.TGenerateProtoGuessRecord;
begin
  TheRequest := ARequest as TApiRequestGenerateProtoGuess;
  TheResults := Core.ApiGenerateProtoGuess(TheRequest.SentenceText, TheRequest.ApiKey);
  Result := TApiResponseGenerateProtoGuess.Create(TheResults.Split, TheResults.Pos, TheResults.Rep,
    TheResults.MatchedProto, TheResults.MatchedSplit, TheResults.MatchedRep);
end;

initialization
  // please keep these sorted
  TApiCommandGenerateProtoGuess.RegisterClass(TApiRequestGenerateProtoGuess);
  TApiCommandGenerateRep.RegisterClass(TApiRequestGenerateRep);

end.
