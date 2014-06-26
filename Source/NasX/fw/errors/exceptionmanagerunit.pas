unit ExceptionManagerUnit;

{$mode objfpc}{$H+}

interface

uses
  SkyException, SkyLists;

type
  TExceptionManager = class(TObject)
  private
    FExceptionClasses: TSkyClassTypeList;
    class function GetInstance: TExceptionManager;
  public
    constructor Create;
    destructor Destroy; override;

    class function GetExceptionClass(const AExceptionClassName: string): ESkyExceptionClass;
    class procedure RegisterStreamedExceptionClass(ASkyExceptionClass: ESkyExceptionClass);
    class procedure RegisterStreamedExceptionClasses(const SomeClasses: array of ESkyExceptionClass);
  end;

implementation

{ TExceptionManager }

constructor TExceptionManager.Create;
begin
  inherited Create;
  FExceptionClasses := TSkyClassTypeList.Create;
end;

destructor TExceptionManager.Destroy;
begin
  FExceptionClasses.Free;
  inherited;
end;

class function TExceptionManager.GetExceptionClass(const AExceptionClassName: string): ESkyExceptionClass;
begin
  Result := ESkyExceptionClass(GetInstance.FExceptionClasses.FindByName(AExceptionClassName));
end;

class function TExceptionManager.GetInstance: TExceptionManager;
begin
  Result := ESkyException.ExceptionManager as TExceptionManager;
end;

class procedure TExceptionManager.RegisterStreamedExceptionClass(ASkyExceptionClass: ESkyExceptionClass);
begin
  GetInstance.FExceptionClasses.Add(ASkyExceptionClass, ASkyExceptionClass.ClassName);
end;

class procedure TExceptionManager.RegisterStreamedExceptionClasses(const SomeClasses: array of ESkyExceptionClass);
var
  I: Integer;
begin
  for I := 0 to Length(SomeClasses) - 1 do
    GetInstance.FExceptionClasses.Add(SomeClasses[I], SomeClasses[I].ClassName);
end;

end.
