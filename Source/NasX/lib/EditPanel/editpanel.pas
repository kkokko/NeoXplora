{ This file was automatically created by Lazarus. Do not edit!
  This source is only used to compile and install the package.
 }

unit EditPanel;

interface

uses
  uEditPanel, LazarusPackageIntf;

implementation

procedure Register;
begin
  RegisterUnit('uEditPanel', @uEditPanel.Register);
end;

initialization
  RegisterPackage('EditPanel', @Register);
end.
