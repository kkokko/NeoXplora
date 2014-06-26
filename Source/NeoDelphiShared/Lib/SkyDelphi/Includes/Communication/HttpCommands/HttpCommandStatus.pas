unit HttpCommandStatus;

interface

uses
  IdCustomHTTPServer, HttpCommand;

type
  THttpCommandStatus = class(THttpCommand)
  private
    class function TDText(const AString: string): string;
  public
    class function GetStatusText(AServer: TObject): string;
    class procedure Execute(AServer: TObject; var AReponseText: string); override;
  end;
  THttpCommandStatusClass = class of THttpCommandStatus;

implementation

uses
  TypesConsts, Classes, SkyHttpLogLine, SkyHttpServer, SysUtils,
  Session, Windows;

{ THttpCommandStatus }

class procedure THttpCommandStatus.Execute(AServer: TObject; var AReponseText: string);
var
  TheString: AnsiString;
begin
  TheString := '<HTML>' + ReturnLF + AnsiString(GetStatusText(AServer)) + '</HTML>';
  (Session.glbSession.HttpResponseInfo.ContentStream as TMemorystream).Write(TheString[1], Length(TheString));
end;

class function THttpCommandStatus.GetStatusText(AServer: TObject): string;
var
  TheSessionList: TList;
  TheSession: TIdHTTPSession;
  TheEvent: TSkyHttpLogLine;
  TheServer: TSkyHttpServer;
  I: Integer;
begin
  TheServer := AServer as TSkyHttpServer;
  TheSessionList := TIdHTTPDefaultSessionList(TheServer.Https.SessionList).SessionList.LockList;
  try
    if Assigned(Session.glbSession.HttpRequestInfo.Session) then
      Result := 'Session: ' + Session.glbSession.HttpRequestInfo.Session.SessionID + ReturnLF
    else
      Result := 'Session: null' + '<BR><BR>' + ReturnLF;
    Result := Result +    'Client ip: ' + Session.glbSession.HttpRequestInfo.RemoteIP + '<BR><BR>' + ReturnLF;
    Result := Result + ' Sessions: ' + IntToStr(TheSessionList.Count) + '<BR><BR>' + ReturnLF;
    if TheSessionList.Count > 0 then
    begin
      Result := Result + '  <TABLE BORDER=1>' + ReturnLF;
      Result := Result + '<TR><TD>SESSION</TD><TD>LAST QUERY</TD><TD>Host</TD></TR>' + ReturnLF;
      for I := TheSessionList.Count - 1 downto 0 do
      begin
        TheSession := TheSessionList[I];
        Result := Result + '<TR><TD>' + TheSession.SessionID + '</TD><TD>' +
          DateTimeToStr(TheSession.LastTimeStamp) + '</TD><TD>' +
          TheSession.RemoteHost + '</TD></TR>' + ReturnLF;
      end;
      Result := Result + '</TABLE>' + ReturnLF;
    end;
  finally
    TIdHTTPDefaultSessionList(TheServer.Https.SessionList).SessionList.UnlockList;
  end;

  TheServer.Lock;
  try
    Result := Result + 'Log: ' + IntToStr(TheServer.Log.Count) + '/' + IntToStr(TheServer.MaxLogSize) + ReturnLf;
    if TheServer.Log.Count > 0 then
    begin
      Result := Result + '<TABLE BORDER=1>'#13#10;
      Result := Result + '<TR><TD>IP</TD><TD>DATE</TD><TD>REQUEST</TD><TD>SESSION</TD><TD>RESPONSE CODE</TD><TD>RESPONSE MESSAGE</TD></TR>'#13#10;
      for I := 0 to TheServer.Log.Count - 1 do
      begin
        TheEvent := TheServer.Log[I] as TSkyHttpLogLine;
        Result := Result + '<TR>';
        Result := Result + TDText(TheEvent.Ip);
        Result := Result + '<TD>' + DateTimeToStr(TheEvent.CreationDate) + '</TD>';
        Result := Result + TDText(TheEvent.Request);
        Result := Result + TDText(TheEvent.Session);
        Result := Result + '<TD>' + IntToStr(TheEvent.ResponseCode) + '</TD>';
        Result := Result + TDText(TheEvent.ResponseText);
        Result := Result + '</TR>' + ReturnLF;
      end;
      Result := Result + '</TABLE>' + ReturnLF;
    end;
  finally
    TheServer.UnLock;
  end;
  Session.glbSession.HttpResponseInfo.ContentType := 'text/html';
end;

class function THttpCommandStatus.TDText(const AString: string): string;
begin
  if AString = '' then
    Result := '<TD>&nbsp;</TD>'
  else
    Result := '<TD>' + AString + '</TD>';
end;

end.