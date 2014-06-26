unit PanelProto;

{$mode objfpc}{$H+}

interface

uses
  Controls, StdCtrls, ExtCtrls, ProtoObject, Classes;

type

  { TPanelProto }

  TPanelProto = class(TPanel)
  private
    const
      ConstPanelHeight = 24;
  private
    FProto: TProtoObject;
    FLabel: TLabel;
    FEdit: TEdit;

    procedure CreateLabel;
    procedure CreateEdit;

    procedure HandleEditExit(Sender: TObject);
    procedure HandleEditKeyUp(Sender: TObject; var Key: Word; Shift: TShiftState);

    procedure ShowLabel;
    procedure ShowEdit;
  public
    constructor Create(AParent: TWinControl; AnIndex: Integer; AProto: TProtoObject); reintroduce;

    procedure NotifyChildAdded;

    property Proto: TProtoObject read FProto write FProto;
  end;

implementation

uses
  Graphics;

{ TPanelProto }

procedure TPanelProto.CreateLabel;
begin
  FLabel := TLabel.Create(Self);
  FLabel.Parent := Self;
  FLabel.Left := 8;
  FLabel.Top := 8;
  FLabel.ShowHint := True;
  FLabel.Hint := FProto.Name;
  FLabel.Font.Style := [fsBold];
  FLabel.Caption := FProto.Name;
end;

procedure TPanelProto.CreateEdit;
begin
  FEdit := TEdit.Create(Self);
  FEdit.Parent := Self;
  FEdit.BorderStyle := bsNone;
  FEdit.Left := 5;
  FEdit.Top := 8;
  FEdit.OnExit := @HandleEditExit;
  FEdit.OnKeyUp := @HandleEditKeyUp;
end;

procedure TPanelProto.HandleEditExit(Sender: TObject);
begin
  FProto.Name := (Sender as TEdit).Text;
end;

procedure TPanelProto.HandleEditKeyUp(Sender: TObject; var Key: Word;
  Shift: TShiftState);
begin
  if Key <> 13 then
    Exit;
  HandleEditExit(Sender);
end;

procedure TPanelProto.ShowLabel;
begin
  FLabel.Caption := FProto.Name;
end;

procedure TPanelProto.ShowEdit;
begin
  FEdit.Width := Parent.Width - 8;
  FEdit.Text := FProto.Name;
end;

constructor TPanelProto.Create(AParent: TWinControl; AnIndex: Integer; AProto: TProtoObject);
begin
  inherited Create(AParent);
  Parent := AParent;
  FProto := AProto;
  Height := ConstPanelHeight;
  Left := (AProto.Level - 1) * 16;
  BevelOuter := bvNone;
  BevelInner := bvLowered;
  if AProto.Level = 1 then
  begin
    Width := AParent.Width - 24;
    CreateLabel;
    ShowLabel;
  end
  else
  begin
    Width := AParent.Width - Left;
    Anchors := [akTop, akLeft, akRight];
    CreateEdit;
    ShowEdit;
  end;
  Top := AnIndex * ConstPanelHeight;
end;

procedure TPanelProto.NotifyChildAdded;
begin
  Height := Height + ConstPanelHeight;
end;

end.

