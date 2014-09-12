unit ServiceThread;

interface

uses
  Classes, Windows, Messages, ServerCore;

type
  TServiceThread = class(TThread)
  private
    FCloseMessage: Cardinal;
    FHwndHandle: HWND;
    procedure WndProc(var Message: TMessage);
  protected
    procedure Execute;override;
  public
    constructor Create; reintroduce;
    destructor Destroy; override;
    procedure Run;
    class function GetInstance: TServiceThread;
    class procedure ForceClose;
  end;

implementation

uses
  SysUtils, LoggerUnit, AppUnit, SkyException, ActiveX, TypesFunctions, Languages,
  AppExceptionClasses, ServMain, CacheReloadThread, AppConsts;

var
  _ServiceThread: TServiceThread;

{ TServiceThread }

constructor TServiceThread.Create;
begin
  inherited Create(True);
  TLogger.GetInstance;
//  FreeOnTerminate := True;
end;

destructor TServiceThread.Destroy;
begin
  DeallocateHWnd(FHwndHandle);
  TCacheReloadThread.EndInstance;
  TApp.EndInstance;
  FreeCore;
  inherited;
end;

class procedure TServiceThread.ForceClose;
var
  TheHandle: THandle;
begin
  if not Assigned(_ServiceThread) then
    Exit;
  TheHandle := _ServiceThread.Handle;
  PostMessage(_ServiceThread.FHwndHandle , _ServiceThread.FCloseMessage, 0, 0);
  WaitForSingleObject(TheHandle, INFINITE);
  FreeAndNil(_ServiceThread);
end;

class function TServiceThread.GetInstance: TServiceThread;
begin
  if not Assigned(_ServiceThread) then
    _ServiceThread := TServiceThread.Create;
  Result := _ServiceThread;
end;

procedure TServiceThread.Run;
begin
  Execute;
end;

procedure TServiceThread.WndProc(var Message: TMessage);
begin
  Dispatch(Message);
end;

procedure TServiceThread.Execute;
var
  Msg: TMsg;
  TheError: ESkyException;
  TheStartSuccessFull: Boolean;
  TheTranslationPath: string;
begin
  NameThreadForDebugging(ConstAppName + 'ServiceThread');
  // create the objects
  TApp.GetInstance;
  TServerCore.GetInstance;
  App.WebInterfaceHandler;

  FHwndHandle := AllocateHWnd(WndProc);
  Core.MainThreadHandle := FHwndHandle;
  CoInitialize(nil);
  try
    FCloseMessage := RegisterWindowMessage(ConstAppName + 'ServiceClose');
    try
      TheTranslationPath := App.Settings.TranslationPath;
      // if the application if run from delphi use the debug path
      if (TheTranslationPath = '')or (not FileExists(TheTranslationPath + 'Language.trnsconf')) then
        TheTranslationPath := App.Settings.AppFolder + 'Translations\';
      TLanguages.Instance.LoadFromPath(TheTranslationPath);
      glbLanguage := 0;
      Core.ReloadSentences;
      App.WebInterfaceHandler.BringOnline;
      TheStartSuccessFull := True;
      TCacheReloadThread.GetInstance;
    except on E: Exception do
    begin
      // Catastrophic failure. Errors should not be here.
      TheStartSuccessFull := False;
      TLogger.Error(Self, E);
      TheError := ESkyCatastrophicFailure.Create(Self, 'Execute');
      try
        TLogger.Error(TheError);
      finally
        FreeAndNil(TheError);
      end;
    end;
    end;
    if (ParamCount = 1) and (ParamStr(1) = '/debug') then
      Exit;
    ServMain.NeoXploraService.StartFinished(TheStartSuccessFull);
    while GetMessage(Msg, FHwndHandle, 0, 0) and (Msg.Message <> FCloseMessage) do
    begin
      TranslateMessage(Msg);
      DispatchMessage(Msg);
    end;
  finally
    CoUninitialize;
  end;
end;

end.
