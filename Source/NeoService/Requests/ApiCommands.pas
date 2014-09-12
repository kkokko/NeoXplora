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

  TApiCommandGenerateRep = class(TApiCommand)
  protected
    class function DoExecute(ARequest: TRequest): TGenericResponse; override;
  end;

implementation

uses
  ApiRequest, ServerCore, AppUnit, ActiveX;

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

initialization
  // please keep these sorted
  TApiCommandGenerateRep.RegisterClass(TApiRequestGenerateRep);

end.
