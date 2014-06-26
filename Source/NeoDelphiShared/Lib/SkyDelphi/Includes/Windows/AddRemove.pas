unit AddRemove;

interface

type
  TAddRemove = class
  private
    const
      CONST_UnInstallPath = '\SOFTWARE\Microsoft\Windows\CurrentVersion\Uninstall';
    var
      FDisplayVersion: string;
      FDisplayName: string;
      FPublisher: string;
      FURLUpdateInfo: string;
      FUnInstallFile: string;
      FReadMe: string;
      FDisplayIcon: string;
      FProductID: string;
      FModifyPath: string;
      FHelpLink: string;
      FRegOwner: string;
      FRegCompany: string;
      FContact: string;
      FComments: string;
  public
    procedure AddUninstallInformation;
    procedure RemoveUninstallInformation;
    function ApplicationInStalled: Boolean;
    function ReadUnInstallFile: string;

    property Comments: string read FComments write FComments;
    property Contact: string read FContact write FContact;
    property DisplayIcon: string read FDisplayIcon write FDisplayIcon;
    property DisplayName: string read FDisplayName write FDisplayName;
    property DisplayVersion: string read FDisplayVersion write FDisplayVersion;
    property HelpLink: string read FHelpLink write FHelpLink;
    property ModifyPath: string read FModifyPath write FModifyPath;
    property ProductID: string read FProductID write FProductID;
    property Publisher: string read FPublisher write FPublisher;
    property ReadMe: string read FReadMe write FReadMe;
    property RegCompany: string read FRegCompany write FRegCompany;
    property RegOwner: string read FRegOwner write FRegOwner;
    property UnInstallFile: string read FUnInstallFile write FUnInstallFile;
    property URLUpdateInfo: string read FURLUpdateInfo write FURLUpdateInfo;
  end;

implementation

uses
  Windows, Registry, SysUtils;

{ TAddRemove }

procedure TAddRemove.AddUninstallInformation;
var
  TheRegistry: TRegistry;
begin
  if (Trim(DisplayName) = '') or ((Trim(UnInstallFile) = '') and (Trim(ModifyPath) = '')) then
    raise Exception.Create('Informatie insuficienta pentru a inregistra aplicatia.');

  TheRegistry := TRegistry.Create;
  try
    TheRegistry.RootKey := HKEY_LOCAL_MACHINE;
    if (TheRegistry.OpenKey(CONST_UnInstallPath, False)) then
    begin
      TheRegistry.CreateKey(DisplayName);
      if (TheRegistry.OpenKey(CONST_UnInstallPath + '\' + DisplayName, False)) then
      begin
        TheRegistry.WriteString('DisplayName', DisplayName);
        if (Trim(UnInstallFile) <> '') then
          TheRegistry.WriteString('Uninstallstring', UnInstallFile);
        if (Trim(DisplayIcon) <> '') then
          TheRegistry.WriteString('DisplayIcon', DisplayIcon);
        if (Trim(ModifyPath) <> '') then
          TheRegistry.WriteString('ModifyPath', ModifyPath);
        if (Trim(ProductID) <> '') then
          TheRegistry.WriteString('ProductID', ProductID);
        if (Trim(Publisher) <> '') then
          TheRegistry.WriteString('Publisher', Publisher);
        if (Trim(DisplayVersion) <> '') then
          TheRegistry.WriteString('DisplayVersion', DisplayVersion);
        if (Trim(Contact) <> '') then
          TheRegistry.WriteString('Contact', Contact);
        if (Trim(HelpLink) <> '') then
          TheRegistry.WriteString('HelpLink', HelpLink);
        if (Trim(RegCompany) <> '') then
          TheRegistry.WriteString('RegCompany', RegCompany);
        if (Trim(RegOwner) <> '') then
          TheRegistry.WriteString('RegOwner', RegOwner);
        if (Trim(ReadMe) <> '') then
          TheRegistry.WriteString('ReadMe', ReadMe);
        if (Trim(URLUpdateInfo) <> '') then
          TheRegistry.WriteString('URLUpdateInfo', URLUpdateInfo);
        if (Trim(Comments) <> '') then
          TheRegistry.WriteString('Comments', Comments);
      end;
    end;
  finally
    TheRegistry.Free;
  end;
end;

function TAddRemove.ApplicationInStalled: Boolean;
var
  TheRegistry: TRegistry;
begin
  TheRegistry := TRegistry.Create;
  try
    TheRegistry.RootKey := HKEY_LOCAL_MACHINE;
    Result := TheRegistry.OpenKey(CONST_UnInstallPath, False) and
      TheRegistry.KeyExists(DisplayName);
  finally
    TheRegistry.Free;
  end;
end;

function TAddRemove.ReadUnInstallFile: string;
var
  TheRegistry: TRegistry;
begin
  Result := '';
  TheRegistry := TRegistry.Create;
  try
    TheRegistry.RootKey := HKEY_LOCAL_MACHINE;
    if not TheRegistry.OpenKey(CONST_UnInstallPath, False) then
      Exit;
    if not TheRegistry.KeyExists(DisplayName) then
      Exit;
    if not TheRegistry.OpenKey(CONST_UnInstallPath + '\' + DisplayName, False) then
      Exit;
    Result := TheRegistry.ReadString('Uninstallstring');
  finally
    TheRegistry.Free;
  end;
end;

procedure TAddRemove.RemoveUninstallInformation;
var
  TheRegistry: TRegistry;
begin
  TheRegistry := TRegistry.Create;
  try
    TheRegistry.RootKey := HKEY_LOCAL_MACHINE;
    if TheRegistry.OpenKey(CONST_UnInstallPath, False) and TheRegistry.KeyExists(DisplayName) then
      TheRegistry.DeleteKey(DisplayName);
  finally
    TheRegistry.Free;
  end;
end;

end.
