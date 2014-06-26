unit WindowsProcess;

interface

uses
  Windows;

type
  TWindowsProcess = class
  private
    FPid: Integer;
    function ProcessFileName(AProcessId: DWORD): string;
  public
    constructor Create(const AFolder, AProcessName: string);

    class function CloseProcess(const AFolder, AProcessName: string): Boolean; overload;
    function CloseProcess: Boolean; overload;
    class function WinExecAndWait32(const FileName: string;
      Visibility: Integer): Longword; static;
  end;

implementation

uses
  SysUtils, PsAPI, TlHelp32;

{ TWindowsProcess }

function TWindowsProcess.CloseProcess: Boolean;
var
  TheHandle: THandle;
begin
  Result := FPid <> -1;
  if FPid = -1 then
    Exit;
  TheHandle := OpenProcess(1, BOOL(0), FPid);
  Result := Integer(TerminateProcess(TheHandle, 0)) <> 0;
end;

class function TWindowsProcess.CloseProcess(const AFolder,
  AProcessName: string): Boolean;
var
  TheInstance: TWindowsProcess;
begin
  TheInstance := TWindowsProcess.Create(AFolder, AProcessName);
  try
    Result := TheInstance.CloseProcess;
  finally
    TheInstance.Free
  end;
end;

constructor TWindowsProcess.Create(const AFolder, AProcessName: string);
var
  TheContinue: BOOL;
  TheFileName: string;
  TheFound: string;
  TheProcessEntry32: TProcessEntry32;
  TheSnapshotHandle: THandle;
begin
  inherited Create;
  FPid := -1;
  TheFileName := IncludeTrailingPathDelimiter(AFolder) + AProcessName;

  TheSnapshotHandle := CreateToolhelp32Snapshot(TH32CS_SNAPPROCESS, 0);
  try
    TheProcessEntry32.dwSize := SizeOf(TheProcessEntry32);
    TheContinue := Process32First(TheSnapshotHandle, TheProcessEntry32);
    while Integer(TheContinue) <> 0 do
    begin
      if AFolder <> '' then
        TheFound := ProcessFileName(TheProcessEntry32.th32ProcessID)
      else
        TheFound := ExtractFileName(TheProcessEntry32.szExeFile);
      if AnsiCompareText(TheFileName, TheFound) = 0 then
        FPid := TheProcessEntry32.th32ProcessID;
      TheContinue := Process32Next(TheSnapshotHandle, TheProcessEntry32);
    end;
  finally
    CloseHandle(TheSnapshotHandle);
  end;
end;

function TWindowsProcess.ProcessFileName(AProcessId: DWORD): string;
var
  TheHandle: THandle;
begin
  Result := '';
  TheHandle := OpenProcess(PROCESS_QUERY_INFORMATION or PROCESS_VM_READ, False, AProcessId);
  if TheHandle <> 0 then
  try
    SetLength(Result, MAX_PATH);
    if GetModuleFileNameEx(TheHandle, 0, PChar(Result), MAX_PATH) > 0 then
      SetLength(Result, StrLen(PChar(Result)))
    else
      Result := '';
  finally
    CloseHandle(TheHandle);
  end;
end;

class function TWindowsProcess.WinExecAndWait32(const FileName: string;
  Visibility: Integer): Longword;
var { by Pat Ritchey }
  zAppName: array[0..512] of Char;
  zCurDir: array[0..255] of Char;
  WorkDir: string;
  StartupInfo: TStartupInfo;
  ProcessInfo: TProcessInformation;
begin
  StrPCopy(zAppName, FileName);
  GetDir(0, WorkDir);
  StrPCopy(zCurDir, WorkDir);
  FillChar(StartupInfo, SizeOf(StartupInfo), #0);
  StartupInfo.cb          := SizeOf(StartupInfo);
  StartupInfo.dwFlags     := STARTF_USESHOWWINDOW;
  StartupInfo.wShowWindow := Visibility;
  if not CreateProcess(nil,
    zAppName, // pointer to command line string
    nil, // pointer to process security attributes
    nil, // pointer to thread security attributes
    False, // handle inheritance flag
    CREATE_NEW_CONSOLE or // creation flags
    NORMAL_PRIORITY_CLASS,
    nil, //pointer to new environment block
    nil, // pointer to current directory name
    StartupInfo, // pointer to STARTUPINFO
    ProcessInfo) // pointer to PROCESS_INF
    then Result := WAIT_FAILED
  else
  begin
    WaitForSingleObject(ProcessInfo.hProcess, INFINITE);
    GetExitCodeProcess(ProcessInfo.hProcess, Result);
    CloseHandle(ProcessInfo.hProcess);
    CloseHandle(ProcessInfo.hThread);
  end;
end;

end.