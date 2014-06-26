unit HttpCommandGetFile;

interface

uses
  IdCustomHTTPServer, HttpCommand;

type
  THttpCommandGetFile = class(THttpCommand)
  protected
    class function FindFile(const AWebSiteFolder, AFileName: string): string; virtual;
    class function UseCaching: Boolean; virtual;
    class function WebSiteFolder: string; virtual; abstract;
  public
    class procedure Execute(AServer: TObject; var AReponseText: string); override;
  end;
  THttpCommandGetFileClass = class of THttpCommandGetFile;

implementation

uses
  Classes, ExceptionClasses, SysUtils, Session, IdGlobalProtocols, TypesFunctions;

{ THttpCommandGetFile }

class function THttpCommandGetFile.FindFile(const AWebSiteFolder, AFileName: string): string;
begin
  Result := ExpandFileName(AWebSiteFolder + AFileName);
  if StrLComp(PChar(AWebSiteFolder), PChar(Result), Length(AWebSiteFolder)) <> 0 then
    Result := '';
end;

class procedure THttpCommandGetFile.Execute(AServer: TObject; var AReponseText: string);
var
  TheDontCache: Boolean;
  TheExt: string;
  TheFileDate: TDateTime;
  TheFileName: string;
  TheRawModifiedSince: string;
  TheBrowserDate: TDateTime;
  TheWebSiteFolder: string;
begin
  TheWebSiteFolder := WebSiteFolder;
  if not DirectoryExists(TheWebSiteFolder) then
    raise ESkyInvalidRequest.Create(nil, 'THttpCommandGetFile.Execute',
      'The WebSiteFolder in the .ini file is not configured properly');
  TheFileName := FindFile(TheWebSiteFolder, StringReplace(Session.glbSession.HttpRequestInfo.Document, '/', '\', [rfReplaceAll]));
  if TheFileName = '' then
  begin
    Session.glbSession.HttpResponseInfo.ResponseNo := 404;
    Session.glbSession.HttpResponseInfo.ContentStream.Free;
    Session.glbSession.HttpResponseInfo.ContentStream := nil;
    AReponseText := 'Bounds violation: ' + TheFileName;
    Exit;
  end;
  if DirectoryExists(TheFileName) then
    TheFileName := IncludeTrailingPathDelimiter(TheFileName) + 'index.html';
  if FileAge(TheFileName, TheFileDate) then
  begin
    TheRawModifiedSince := Session.glbSession.HttpRequestInfo.RawHeaders.Values['if-Modified-Since'];
    if UseCaching  and (TheRawModifiedSince <> '') and (TheRawModifiedSince <> '-1') then
    begin
      TheBrowserDate := GMTToLocalDateTime(TheRawModifiedSince);
      if CompareTimes(TheBrowserDate, TheFileDate) = 0 then
      begin
        Session.glbSession.HttpResponseInfo.ResponseNo := 304;
        Session.glbSession.HttpResponseInfo.ContentStream.Free;
        Session.glbSession.HttpResponseInfo.ContentStream := nil;
        AReponseText := 'Sending from cache: ' + TheFileName;
        Exit;
      end;
    end;

    (Session.glbSession.HttpResponseInfo.ContentStream as TMemoryStream).LoadFromFile(TheFileName);
    TheDontCache := not UseCaching;
    TheExt := ExtractFileExt(TheFileName);
    if SameText(TheExt, '.js') then
    begin
      Session.glbSession.HttpResponseInfo.ContentType := 'application/x-javascript';
      TheDontCache := True;
    end
    else if SameText(TheExt, '.html') then
    begin
      Session.glbSession.HttpResponseInfo.ContentType := 'text/html';
      TheDontCache := True;;
    end
    else if SameText(TheExt, '.ico') then
      Session.glbSession.HttpResponseInfo.ContentType := 'image/x-icon';
    if not TheDontCache then
    begin
      Session.glbSession.HttpResponseInfo.LastModified := TheFileDate;
      Session.glbSession.HttpResponseInfo.Expires := Now() + 365; // one year from now
    end;
    AReponseText := 'File sent';
  end
  else
  begin
    Session.glbSession.HttpResponseInfo.ResponseNo := 404;
    Session.glbSession.HttpResponseInfo.ContentStream.Free;
    Session.glbSession.HttpResponseInfo.ContentStream := nil;
    AReponseText := 'File not found: ' + TheFileName;
  end;
end;

class function THttpCommandGetFile.UseCaching: Boolean;
begin
  Result := True;
end;

end.