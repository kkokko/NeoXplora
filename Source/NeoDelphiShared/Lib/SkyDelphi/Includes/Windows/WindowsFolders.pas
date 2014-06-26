unit WindowsFolders;

interface

type
  TWindowsFolders = class
  private
    const
      CONST_CSIDL_COMMON_PROGRAMS = $0017; // All Users\Start Menu\Programs
      CONST_CSIDL_PROGRAM_FILES = $0026;
      CONST_CSIDL_DESKTOPDIRECTORY = $0010;
    class function GetSpecialFolder(AFolderId: Integer): string; static;
  public
    class function CommonFolder: string;
    class function DesktopFolder: string;
    class function ProgramFilesFolder: string;
    class function TempFolder: string;
  end;

implementation

uses
  Windows, SysUtils, ShlObj, ActiveX, ShellAPI;

{ TWindowsFolders }

class function TWindowsFolders.CommonFolder: string;
begin
  Result := GetSpecialFolder(CONST_CSIDL_COMMON_PROGRAMS);
end;

class function TWindowsFolders.DesktopFolder: string;
begin
  Result := GetSpecialFolder(CONST_CSIDL_DESKTOPDIRECTORY);
end;

class function TWindowsFolders.GetSpecialFolder(AFolderId: Integer): string;
var
  ThePidl: PItemIDList;
  TheBuffer : array[0 .. MAX_PATH] of char;
begin
  Result := '';
  if Succeeded(ShGetSpecialFolderLocation(GetActiveWindow, AFolderId, ThePidl) ) then
  try
    if ShGetPathfromIDList(ThePidl, TheBuffer) then
      Result := TheBuffer;
  finally
    CoTaskMemFree(ThePidl);
  end;
  Result := IncludeTrailingPathDelimiter(Result);
end;

class function TWindowsFolders.ProgramFilesFolder: string;
begin
  Result := GetSpecialFolder(CONST_CSIDL_PROGRAM_FILES);
end;

class function TWindowsFolders.TempFolder: string;
begin
  Result := IncludeTrailingPathDelimiter(GetEnvironmentVariable('TEMP'));
end;

end.
