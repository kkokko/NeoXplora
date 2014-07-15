unit HttpCommandRequestBase;

interface

uses
  IdContext, IdCustomHTTPServer, HttpCommand, EntityReader, EntityWriter,
  Communication, SkyHttpSession;

type
  // abstract class, implemented in Application
  THttpCommandRequestBase = class(THttpCommand)
  protected
    class procedure RunCommand(AServer: TObject; const AFileExt: string;
      AnEntityReader: TEntityReaderClass; AnEntityWriter: TEntityWriterClass); virtual;

    class function ExecuteRequest(ARequest: TRequest): TGenericResponse; virtual;
    class function LogErrors: Boolean; virtual; abstract;
    class function LogAllRequests: Boolean; virtual; abstract;
    class function TempFolder: string; virtual; abstract;
  end;

implementation

uses
  Classes, SkyHttpServer, SysUtils, LoggerUnit, Command, Windows,
  StrUtils, Session, ClientSession, SkyException, TypesConsts;

{ THttpCommandRequestBase }

class function THttpCommandRequestBase.ExecuteRequest(ARequest: TRequest): TGenericResponse;
begin
  Result := TCommand.GetClassForRequest(ARequest, TCommand).Execute(ARequest);
end;

class procedure THttpCommandRequestBase.RunCommand(AServer: TObject;
  const AFileExt: string; AnEntityReader: TEntityReaderClass;
  AnEntityWriter: TEntityWriterClass);
var
  TheRequest: TRequest;
  TheResponse: TGenericResponse;
  TheRequestName, TheResponseName: string;
  TheErrorString: AnsiString;
  TheServer: TSkyHttpServer;
  TheRequestStream: TMemoryStream;
  TheResponseStream: TMemoryStream;
  TheTime: Integer;
  TheTick: Cardinal;
begin
  TheServer := AServer as TSkyHttpServer;
  TheRequestStream := Session.glbSession.HttpRequestInfo.PostStream as TMemoryStream;
  if TheRequestStream = nil then
    Exit;
  TheResponse := nil;
  TheTime := Trunc(Now);
  TheTick := GetTickCount;
  try
    TheRequest := AnEntityReader.ReadEntity(TheRequestStream, TRequest) as TRequest;
  except on E: exception do
  begin
    if LogErrors then
    begin
      TheRequestName := Format('%s%s_%s_Request_Unknown.%s', [
        TempFolder,
        IntToStr(TheTime),
        IntToStr(TheTick),
        AFileExt
      ]);

      if (E is ESkyException) then
        TheErrorString := AnsiString((E as ESkyException).TranslatedMessage)
      else
        TheErrorString := AnsiString(E.Message);
      TheRequestStream.Seek(0, soFromEnd);
      TheErrorString := ReturnLF + TheErrorString;
      TheRequestStream.Write(TheErrorString[1], Length(TheErrorString));
      TheRequestStream.Position := 0;
      SaveStreamToFile(TheRequestStream, TheRequestName);
    end;
    raise;
  end;
  end;

  TheRequestName := Format('%s%s_%s_Request_%s.%s', [
    TempFolder,
    IntToStr(TheTime),
    IntToStr(TheTick),
    TheRequest.ClassName,
    AFileExt
  ]);

  TheResponseStream := Session.glbSession.HttpResponseInfo.ContentStream as TMemoryStream;
  try
    Session.glbSession.Data := TheServer.GetSessionObject(
      Session.glbSession.HttpRequestInfo.Session);

    if LogAllRequests then
      SaveStreamToFile(TheRequestStream, TheRequestName);

    try
      TheResponse := ExecuteRequest(TheRequest);
    except on E: Exception do
    begin
      TLogger.Error(AServer, E);
      TheResponse := TCommand.HandleException(TheRequest, E);
    end;
    end;

    if not Assigned(TheResponse) then
      TheResponse := TResponse.Create; // empty response
    AnEntityWriter.WriteEntity(TheResponseStream, TheResponse);

    TheResponseName := Format('%s%s_%s_%s_%s.%s', [
      TempFolder,
      IntToStr(TheTime),
      IntToStr(TheTick),
      IfThen(TheResponse is TResponseError, 'Error', 'Response'),
      TheResponse.ClassName,
      AFileExt
    ]);
    if (LogErrors and (TheResponse is TResponseError)) or (LogAllRequests) then
    begin
      if not LogAllRequests then
        SaveStreamToFile(TheRequestStream, TheRequestName);
      SaveStreamToFile(TheResponseStream, TheResponseName);
    end;
    TheResponseStream.Position := 0;
  finally
    FreeAndNil(TheRequest);
    FreeAndNil(TheResponse);
  end;
end;

end.