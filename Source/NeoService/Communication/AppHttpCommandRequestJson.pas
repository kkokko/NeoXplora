unit AppHttpCommandRequestJson;

interface

uses
  HttpCommandRequestJson;

type
  TAppHttpCommandRequestJson = class(THttpCommandRequestJson)
  protected
    class function LogErrors: Boolean; override;
    class function LogAllRequests: Boolean; override;
    class function TempFolder: string; override;
  end;

implementation

uses
  AppUnit;

{ TAppHttpCommandRequestJson }

class function TAppHttpCommandRequestJson.LogAllRequests: Boolean;
begin
  Result := App.Settings.LogAllOperations;
end;

class function TAppHttpCommandRequestJson.LogErrors: Boolean;
begin
  Result := True;
end;

class function TAppHttpCommandRequestJson.TempFolder: string;
begin
  Result := App.Settings.TempFolder;
end;

end.
