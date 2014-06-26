unit ExplorerUtils;

interface

type
  TExplorerUtils = class
  public
    class function FormatByteSize(const TheBytes: Int64): string;
    class procedure CreateShortcut(const AFileName, AnArgument, AShortCutName,
      AnIconName: string; AnIconIndex: Integer = 0);
  end;

implementation

uses
  Windows, SysUtils, ShlObj, ActiveX, ComObj;

function StrFormatByteSize(dw: DWORD; szBuf: PChar; uiBufSize: UINT):PChar;
  stdcall; external 'shlwapi.dll' name 'StrFormatByteSizeA';
function StrFormatKBSize(qdw: LONGLONG; szBuf: PChar; uiBufSize: UINT):PChar;
  stdcall; external 'shlwapi.dll' name 'StrFormatKBSizeA';

class procedure TExplorerUtils.CreateShortcut(const AFileName, AnArgument,
  AShortCutName, AnIconName: string; AnIconIndex: Integer = 0);
var
  TheObject: IUnknown;
  TheSLink: IShellLink;
  ThePFile: IPersistFile;
begin
  TheObject := CreateComObject(CLSID_ShellLink);
  TheSLink := TheObject as IShellLink;
  ThePFile := TheObject as IPersistFile;
  TheSLink.SetArguments(PChar(AnArgument));
  TheSLink.SetPath(PChar(AFileName));
  if AnIconName <> '' then
    TheSLink.SetIconLocation(PChar(AnIconName), AnIconIndex);
  TheSLink.SetWorkingDirectory(PChar(ExtractFilePath(AFileName)));
  ThePFile.Save(PWChar(WideString(ChangeFileExt(AShortCutName, '.lnk'))), False);
end;

class function TExplorerUtils.FormatByteSize(const TheBytes: Int64): string;
var
  TheString: AnsiString;
begin
  SetLength(TheString, 1000);
  StrFormatKBSize(TheBytes, @TheString[1], 1000);
  Result := Trim(string(PAnsiChar(TheString)));
end;

end.
