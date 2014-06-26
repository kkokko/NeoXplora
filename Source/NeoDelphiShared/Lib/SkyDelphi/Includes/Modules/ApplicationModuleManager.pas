unit ApplicationModuleManager;

interface

uses
  SkyLists, ApplicationModule;

type
  TApplicationModuleManager = class(TObject)
  private
    FActiveModules: TSkyObjectList;
  public
    class function GetInstance: TApplicationModuleManager;
    class procedure EndInstance;
    function CreateModule(AModuleClass: TApplicationModuleClass): TApplicationModule;
    procedure CloseModule(AModuleClass: TApplicationModuleClass);
    constructor Create;
    destructor Destroy; override;
    property ActiveModules: TSkyObjectList read FActiveModules write FActiveModules;
  end;

implementation

uses
  TypesFunctions;

var
  _ApplicationModuleManager: TApplicationModuleManager;

{ TApplicationModuleManager }

constructor TApplicationModuleManager.Create;
begin
  FActiveModules := TSkyObjectList.Create(True); // owns objects
end;

destructor TApplicationModuleManager.Destroy;
begin
  FActiveModules.Free;
  inherited;
end;

function TApplicationModuleManager.CreateModule(AModuleClass: TApplicationModuleClass): TApplicationModule;
var
  TheIndex: Integer;
begin
  TheIndex := FActiveModules.IndexOf(Pointer(AModuleClass));
  if TheIndex = -1 then
  begin
    Result := AModuleClass.Create;
    FActiveModules.Add(Pointer(AModuleClass), Result)
  end
  else
    Result := FActiveModules.Objects[TheIndex] as TApplicationModule;
end;

procedure TApplicationModuleManager.CloseModule(AModuleClass: TApplicationModuleClass);
begin
  FActiveModules.Delete(Pointer(AModuleClass));
end;

class procedure TApplicationModuleManager.EndInstance;
begin
  TypesFunctions.FreeAndNil(_ApplicationModuleManager);
end;

class function TApplicationModuleManager.GetInstance: TApplicationModuleManager;
begin
  if not Assigned(_ApplicationModuleManager) then
    _ApplicationModuleManager := TApplicationModuleManager.Create;
  Result := _ApplicationModuleManager;
end;

end.
