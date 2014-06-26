unit SkyException;

{$mode objfpc}{$H+}

interface

uses
  GenericEntity, SysUtils, MessageInfoData, TypesConsts;

type
  ESkyException = class;
  ESkyExceptionClass = class of ESkyException;

  { ESkyException }

  ESkyException = class(Exception)
  private
    class var
      FExceptionManager: TObject;
    function GetParams: TGenericEntity;
  protected
    FMessageInfo: TMessageInfoData;
 {$IFDEF VER210}
    class destructor Destroy;
 {$ENDIF}

    function GetMessage: string; virtual;
    procedure SetMessage(AMessage: string);
    function GetSeverity: TErrorSeverity; virtual;
    function GetTranslatedMessage: string; virtual;
  public
    constructor Create(AMessageInfo: TMessageInfoData); overload;

    class function CreateFromMessageInfo(AMessageInfo: TMessageInfoData): ESkyException;
    class function ExceptionManager: TObject;
    class procedure RegisterClass;
    class procedure RegisterClasses(const SomeClasses: array of ESkyExceptionClass);

    constructor Create(const ARaisedBy, ALocation, AMessage: string); overload;
    constructor Create(ARaisedBy: TObject; const ALocation, AMessage: string); overload;
    destructor Destroy; override;
    function CreateACopy: ESkyException;

    property Message: string read GetMessage write SetMessage;
    property Params: TGenericEntity read GetParams;
    property TranslatedMessage: string read GetTranslatedMessage;
    property Severity: TErrorSeverity read GetSeverity;
    property MessageInfo: TMessageInfoData read FMessageInfo;
  end;

  ESkyFatalException = class(ESkyException)
  protected
    function GetSeverity: TErrorSeverity; override;
  end;

implementation

uses
  FWTranslations, ExceptionManagerUnit;

{ EStreamedException }

constructor ESkyException.Create(ARaisedBy: TObject; const ALocation,
  AMessage: string);
var
  TheClassName: string;
begin
  if Assigned(ARaisedBy) then
    TheClassName := ARaisedBy.ClassName
  else
    TheClassName := '(nil)';
  Create(TheClassName, ALocation, AMessage);
end;

{$IFDEF VER210}
class destructor ESkyException.Destroy;
{$ELSE}
procedure ESkyExceptionDestroy;
 {$ENDIF}
begin
  ESkyException.FExceptionManager.Free;
end;

constructor ESkyException.Create(AMessageInfo: TMessageInfoData);
begin
  inherited Create(AMessageInfo.Params.GetValueForField('Message'));
  FMessageInfo := AMessageInfo;
end;

function ESkyException.CreateACopy: ESkyException;
begin
  Result := ESkyExceptionClass(ClassType).Create('', '', '');
  Result.MessageInfo.CopyFrom(FMessageInfo);
end;

class function ESkyException.CreateFromMessageInfo(AMessageInfo: TMessageInfoData): ESkyException;
var
  TheExceptionClass: ESkyExceptionClass;
begin
  TheExceptionClass := TExceptionManager.GetExceptionClass(AMessageInfo.EntityClassName);
  if not Assigned(TheExceptionClass) then
    raise ESkyException.Create('ESkyException', 'CreateFromMessageInfo', Translate(tlClassNotRegistered));
  Result := TheExceptionClass.Create(AMessageInfo);
end;

constructor ESkyException.Create(const ARaisedBy, ALocation, AMessage: string);
var
  TheMessageInfo: TMessageInfoData;
begin
  TheMessageInfo := TMessageInfoData.Create;
  TheMessageInfo.DateTime := Now;
  TheMessageInfo.MessageType := lmtError;
  TheMessageInfo.EntityClassName := ClassName;
  TheMessageInfo.Params.SetValueForField('RaisedBy', ARaisedBy);
  TheMessageInfo.Params.SetValueForField('Location', ALocation);
  TheMessageInfo.Params.SetValueForField('Message', AMessage);
  Create(TheMessageInfo);
end;

destructor ESkyException.Destroy;
begin
  FMessageInfo.Free;
  inherited;
end;

class function ESkyException.ExceptionManager: TObject;
begin
  if not Assigned(FExceptionManager) then
    FExceptionManager := TExceptionManager.Create;
  Result := FExceptionManager;
end;

function ESkyException.GetMessage: string;
begin
  Result := Params.GetValueForField('Message');
end;

function ESkyException.GetParams: TGenericEntity;
begin
  Result := MessageInfo.Params;
end;

function ESkyException.GetSeverity: TErrorSeverity;
begin
  Result := esERROR;
end;

function ESkyException.GetTranslatedMessage: string;
begin
  Result := Translate(Message);
end;

procedure ESkyException.SetMessage(AMessage: string);
begin
  Params.SetValueForField('Message', AMessage);
end;

class procedure ESkyException.RegisterClass;
begin
  TExceptionManager.RegisterStreamedExceptionClass(Self);
end;

class procedure ESkyException.RegisterClasses(const SomeClasses: array of ESkyExceptionClass);
begin
  TExceptionManager.RegisterStreamedExceptionClasses(SomeClasses);
end;

{ ESkyFatalException }

function ESkyFatalException.GetSeverity: TErrorSeverity;
begin
  Result := esFATAL;
end;

{$IFNDEF VER210}
initialization

finalization
  ESkyExceptionDestroy;
{$ENDIF}

end.
