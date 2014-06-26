unit PanelSentence;

{$mode objfpc}{$H+}

interface

uses
  Controls, StdCtrls, ExtCtrls, SentenceObject, Classes;

type

  { TPanelSentence }

  TPanelSentence = class(TPanel)
  private
    const
      ConstPanelHeight = 24;
  private
    FSentence: TSentenceObject;
    FEdit: TEdit;

    procedure CreateEdit;

    procedure HandleEditExit(Sender: TObject);
    procedure HandleEditKeyUp(Sender: TObject; var Key: Word; Shift: TShiftState);

    procedure ShowEdit;
  public
    constructor Create(AParent: TWinControl; AnIndex: Integer; ASentence: TSentenceObject); reintroduce;

    procedure NotifyChildAdded;

    property Sentence: TSentenceObject read FSentence write FSentence;
  end;

implementation

uses
  Graphics;

{ TPanelSentence }

procedure TPanelSentence.CreateEdit;
begin
  FEdit := TEdit.Create(Self);
  FEdit.Parent := Self;
  FEdit.BorderStyle := bsNone;
  FEdit.Left := 5;
  FEdit.Top := 8;
  FEdit.OnExit := @HandleEditExit;
  FEdit.OnKeyUp := @HandleEditKeyUp;
end;

procedure TPanelSentence.HandleEditExit(Sender: TObject);
begin
  FSentence.Name := (Sender as TEdit).Text;
end;

procedure TPanelSentence.HandleEditKeyUp(Sender: TObject; var Key: Word;
  Shift: TShiftState);
begin
  if Key <> 13 then
    Exit;
  HandleEditExit(Sender);
end;

procedure TPanelSentence.ShowEdit;
begin
  FEdit.Width := Parent.Width - 8;
  FEdit.Text := FSentence.Name;
end;

constructor TPanelSentence.Create(AParent: TWinControl; AnIndex: Integer; ASentence: TSentenceObject);
begin
  inherited Create(AParent);
  Parent := AParent;
  FSentence := ASentence;
  Height := ConstPanelHeight;
  Left := 32;
  Width := AParent.Width - Left;
  BevelOuter := bvNone;
  BevelInner := bvLowered;
  Anchors := [akTop, akLeft, akRight];
  CreateEdit;
  Top := AnIndex * ConstPanelHeight;
  ShowEdit;
end;

procedure TPanelSentence.NotifyChildAdded;
begin
  Height := Height + ConstPanelHeight;
end;

end.

