unit Command;

interface

uses
  Communication, SkyLists, SkyHttpSession, SysUtils;

type
  TCommand = class;
  TCommandClass = class of TCommand;
  TCommand = class
  private
    class var FCommandList: TSkyObjectList;
 {$IFDEF VER210}
  protected
    class constructor Create;
    class destructor Destroy;
 {$ENDIF}
  protected
    class procedure AfterExecute(ARequest: TRequest; AResponse: TGenericResponse); virtual;
    class procedure BeforeExecute(ARequest: TRequest); virtual;
    class function DoExecute(ARequest: TRequest): TGenericResponse; virtual; abstract;
    class procedure RegisterClass(ARequestClass: TRequestClass);
    class function SessionRequired: Boolean; virtual;
  public
    class function Execute(ARequest: TRequest; AnExtraData: TObject = nil): TGenericResponse; virtual;
    class function GetClassForRequest(ARequest: TRequest; AShouldInheritFrom: TCommandClass): TCommandClass;
    class function HandleException(ARequest: TRequest; AnException: Exception): TGenericResponse;
  end;

implementation

uses
  ExceptionClasses, SkyException, Session;

{$IFDEF VER210}
class constructor TCommand.Create;
{$ELSE}
procedure TCommandCreate;
 {$ENDIF}
begin
  TCommand.FCommandList := TSkyObjectList.Create(False, False);
end;

{$IFDEF VER210}
class constructor TCommand.Destroy;
{$ELSE}
procedure TCommandDestroy;
 {$ENDIF}
begin
  TCommand.FCommandList.Free;
end;

class procedure TCommand.AfterExecute(ARequest: TRequest; AResponse: TGenericResponse);
begin
  // override in inherited
end;

class procedure TCommand.BeforeExecute(ARequest: TRequest);
begin
  // override in inherited
end;

class function TCommand.Execute(ARequest: TRequest; AnExtraData: TObject): TGenericResponse;
begin
  try
    if (Session.glbSession.Data = nil) and SessionRequired then
      raise ESkyInvalidSession.Create(nil, 'TCommand.Execute');
    BeforeExecute(ARequest);
    Result := DoExecute(ARequest);
  except on E: Exception do
    Result := HandleException(ARequest, E);
  end;
  try
    AfterExecute(ARequest, Result);
  except on E: Exception do
    Result := HandleException(ARequest, E);
  end;
end;

class function TCommand.GetClassForRequest(ARequest: TRequest; AShouldInheritFrom: TCommandClass): TCommandClass;
begin
  Result := Pointer(FCommandList.ObjectOfValueDefault[Pointer(ARequest.ClassType), nil]);
  if (not Assigned(Result)) or not (Result.InheritsFrom(AShouldInheritFrom)) then
    raise ESkyInvalidRequest.Create(nil, 'TCommand.GetClassForRequest', ARequest.ClassName);
end;

class function TCommand.HandleException(ARequest: TRequest; AnException: Exception): TGenericResponse;
var
  TheException: ESkyException;
begin
  if (AnException is ESkyException) then
    TheException := (AnException as ESkyException)
  else
    TheException := ESkyServerUnknownException.Create(nil, Self.ClassName + '.HandleException', AnException.Message);
  Result := TResponseServerException.Create(TheException);
  if not (AnException is ESkyException) then
    FreeAndNil(TheException);
end;

class procedure TCommand.RegisterClass(ARequestClass: TRequestClass);
begin
  FCommandList.Add(Pointer(ARequestClass), Pointer(Self));
end;

class function TCommand.SessionRequired: Boolean;
begin
  Result := True;
end;

{$IFNDEF VER210}
initialization
  TCommandCreate;

finalization
  TCommandDestroy;
{$ENDIF}

end.
