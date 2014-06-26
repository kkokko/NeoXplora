unit ApplicationModule;

interface

uses
  Classes, Forms;

type
  { TODO : To implement modules. }
  TApplicationModule = class(TObject)
  private
    FMainForm: TForm;
  protected
    function GetMainFormClass: TComponentClass; virtual;
  public
    constructor Create;
    destructor Destroy; override;
    procedure Display;
    class function GetInstance: TApplicationModule;
    class procedure Unload;
    property MainForm: TForm read FMainForm write FMainForm;
  end;
  TApplicationModuleClass = class of TApplicationModule;

implementation

uses
  TypesFunctions, ApplicationModuleManager;

{ TApplicationModule }

constructor TApplicationModule.Create;
begin
  inherited Create;
  FMainForm := nil;
end;

destructor TApplicationModule.Destroy;
begin
  FreeAndNil(FMainForm);
  inherited;
end;

procedure TApplicationModule.Display;
var
  TheFormClass: TComponentClass;
begin
  if FMainForm = nil then
  begin
    TheFormClass := GetMainFormClass;
    if TheFormClass <> nil then
      Application.CreateForm(TheFormClass, FMainForm);
  end;
  if FMainForm = nil then
    Exit;
  FMainForm.Show;
end;

class function TApplicationModule.GetInstance: TApplicationModule;
begin
  Result := TApplicationModuleManager.GetInstance.CreateModule(Self);
end;

class procedure TApplicationModule.Unload;
begin
  TApplicationModuleManager.GetInstance.CloseModule(Self);
end;

function TApplicationModule.GetMainFormClass: TComponentClass;
begin
  Result := nil;
end;

end.
