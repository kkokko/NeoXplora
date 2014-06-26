unit SkyVioSecureCompatibility;

interface

type
  TVioSecure = class(TObject)
  private
  public
    class function application_open(path, name: string): integer;
  end;

implementation

uses
  Windows, SysUtils, PsAPI, TlHelp32;

class function TVioSecure.application_open(path, name: string): integer;
var
  fname, fnd: string;
  AProcessEntry32: TProcessEntry32;
  ASnapshotHandle: THandle;
  bContinue: BOOL;

  function ProcessFileName(PID: DWORD): string;
  var
    Handle: THandle;
  begin
    Result := '';
    Handle := OpenProcess(PROCESS_QUERY_INFORMATION or PROCESS_VM_READ, False, PID);
    if Handle <> 0 then
    try
      SetLength(Result, MAX_PATH);
      if GetModuleFileNameEx(Handle, 0, PChar(Result), MAX_PATH) > 0 then
        SetLength(Result, StrLen(PChar(Result)))
      else
        Result := '';
    finally
      CloseHandle(Handle);
    end;
  end;
begin
  result := -1;
  fname := path + name;
  aSnapshotHandle := CreateToolhelp32Snapshot(TH32CS_SNAPPROCESS, 0);
  aProcessEntry32.dwSize := SizeOf(aProcessEntry32);
  bContinue := Process32First(aSnapshotHandle, aProcessEntry32);
  while Integer(bContinue) <> 0 do
  begin
    if path <> '' then
      fnd := ProcessFileName(aProcessEntry32.th32ProcessID)
    else
      fnd := ExtractFileName(aProcessEntry32.szExeFile);
    if AnsiCompareText(fname, fnd) = 0 then
      result := aProcessEntry32.th32ProcessID;
    bContinue := Process32Next(aSnapshotHandle, aProcessEntry32);
  end;
  CloseHandle(aSnapshotHandle);
end;
end.
