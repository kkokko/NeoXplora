unit SkyHttpLogLine;

interface

uses
  Entity, IdCustomHTTPServer;

type
  TSkyHttpLogLine = class(TEntity)
  private
    FCreationDate: TDateTime;
    FRequest: string;
    FSession: string;
    FIp: string;
    FResponseCode: Integer;
    FResponseText: string;
  public
    constructor Create(ARequestInfo: TIdHTTPRequestInfo); reintroduce;
  published
    property CreationDate: TDateTime read FCreationDate write FCreationDate;
    property Request: string read FRequest write FRequest;
    property Ip: string read FIp write FIp;
    property Session: string read FSession write FSession;
    property ResponseCode: Integer read FResponseCode write FResponseCode;
    property ResponseText: string read FResponseText write FResponseText;
  end;

implementation

uses
  SysUtils;
{ TSkyHttpLogLine }

constructor TSkyHttpLogLine.Create;
begin
  inherited Create;
  FCreationDate := Now();
  FRequest :=  ARequestInfo.Document + ' ' + ARequestInfo.Params.Text;
  FIp := ARequestInfo.RemoteIP;
  if ARequestInfo.Session = nil then
    FSession := '-'
  else
    FSession := ARequestInfo.Session.SessionID;
end;

end.