unit ServerInterface;

{$mode objfpc}{$H+}

interface

uses
  Classes, fphttpclient, RequestGetImportInfo, RequestGetStoriesForImport,
  RequestSetStoriesFromExport;

type

  { TServerInterface }

  TServerInterface = class
  private
    FHttpClient: TFPHTTPClient;
    FRequest: TMemoryStream;
    FResponse: TMemoryStream;
    FPassword: string;
    FServerAddress: string;
    FUserName: string;
  public
    constructor Create;
    destructor Destroy; override;

    function GetImportInfo(ARequest: TRequestGetImportInfo): TResponseGetImportInfo;
    function GetStoriesForImport(ARequest: TRequestGetStoriesForImport): TResponseGetStoriesForImport;
    function SetStoriesFromExport(ARequest: TRequestSetStoriesFromExport): TResponseSetStoriesFromExport;

    property ServerAddress: string read FServerAddress write FServerAddress;
    property UserName: string read FUserName write FUserName;
    property Password: string read FPassword write FPassword;
  end;

implementation

uses
  Dialogs, SysUtils, EntityJsonWriter, EntityJsonReader;

{ TServerInterface }

constructor TServerInterface.Create;
begin
  FHttpClient := TFPHTTPClient.Create(nil);
  FRequest := TMemoryStream.Create;
  FHttpClient.RequestBody := FRequest;
  FResponse := TMemoryStream.Create;
end;

destructor TServerInterface.Destroy;
begin
  FHttpClient.Free;
  FResponse.Free;
  FRequest.Free;
  inherited Destroy;
end;

function TServerInterface.GetImportInfo(ARequest: TRequestGetImportInfo): TResponseGetImportInfo;
begin
  Result := nil;
  ARequest.UserName := UserName;
  ARequest.UserPassword := Password;
  FRequest.Clear;
  TEntityJsonWriter.WriteEntity(FRequest, ARequest);
  FRequest.Position := 0;
  FResponse.Clear;
  FHttpClient.RequestHeaders.Clear;
  try
    FHttpClient.Post(ServerAddress, FResponse);
    if (FHttpClient.ResponseStatusCode <> 200) then
      raise Exception.Create('Response code: ' + IntToStr(FHttpClient.ResponseStatusCode) + FHttpClient.ResponseStatusText);
    FResponse.Position := 0;;
    Result := TEntityJSonReader.ReadEntity(FResponse, TResponseGetImportInfo)as TResponseGetImportInfo
  except on E: Exception do
    MessageDlg('Error: ' + E.Message, mtError, [mbOK], -1);
  end;
end;

function TServerInterface.GetStoriesForImport(ARequest: TRequestGetStoriesForImport): TResponseGetStoriesForImport;
begin
  Result := nil;
  ARequest.UserName := UserName;
  ARequest.UserPassword := Password;
  FRequest.Clear;
  TEntityJsonWriter.WriteEntity(FRequest, ARequest);
  FRequest.Position := 0;
  FResponse.Clear;
  FHttpClient.RequestHeaders.Clear;
  try
    FHttpClient.Post(ServerAddress, FResponse);
    if (FHttpClient.ResponseStatusCode <> 200) then
      raise Exception.Create('Response code: ' + IntToStr(FHttpClient.ResponseStatusCode) + FHttpClient.ResponseStatusText);
    FResponse.Position := 0;
    Result := TEntityJsonReader.ReadEntity(FResponse) as TResponseGetStoriesForImport;
  except on E: Exception do
    MessageDlg('Error: ' + E.Message, mtError, [mbOK], -1);
  end;
end;

function TServerInterface.SetStoriesFromExport(ARequest: TRequestSetStoriesFromExport): TResponseSetStoriesFromExport;
begin
  ARequest.UserName := UserName;
  ARequest.UserPassword := Password;
  FRequest.Clear;
  TEntityJsonWriter.WriteEntity(FRequest, ARequest);
  FRequest.Position := 0;
  Result := nil;
  FResponse.Clear;
  FHttpClient.RequestHeaders.Clear;
  try
    FHttpClient.Post(ServerAddress, FResponse);
    if (FHttpClient.ResponseStatusCode <> 200) then
      raise Exception.Create('Response code: ' + IntToStr(FHttpClient.ResponseStatusCode) + FHttpClient.ResponseStatusText);
    FResponse.Position := 0;
    Result := TEntityJSonReader.ReadEntity(FResponse) as TResponseSetStoriesFromExport;
  except on E: Exception do
    MessageDlg('Error: ' + E.Message, mtError, [mbOK], -1);
  end;
end;

end.

