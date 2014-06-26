unit uEditPanel;

{$mode objfpc}{$H+}

interface

uses
  Classes, SysUtils, LResources, Forms, Controls, Graphics, Dialogs, ExtCtrls, StdCtrls;

type

  TEditState = (esDisplay, esEdit);

  { TEditPanel }

  TEditPanel = class(TCustomPanel)
  private
    FEditState: TEditState;
    FEventsActive: boolean;

    FEdit0OnChangeEvent: TNotifyEvent;
    FLabel1OnClickEvent: TNotifyEvent;
    FLabel2OnClickEvent: TNotifyEvent;
    FLabel3OnClickEvent: TNotifyEvent;
    FLabel4OnClickEvent: TNotifyEvent;

    Edit0: TEdit;
    Label0: TLabel;
    Label1: TLabel;
    Label2: TLabel;
    Label3: TLabel;
    Label4: TLabel;
    { Private declarations }

    function GetEdit0OnChange: TNotifyEvent;
    procedure HandleEditKeyUp(Sender: TObject; var Key: Word; Shift: TShiftState
      );
    procedure SetEdit0OnChange(const Value: TNotifyEvent);

    function GetLabelOnClick(const Index: integer): TNotifyEvent;
    procedure SetLabelOnClick(const Index: integer; const Value: TNotifyEvent);

    procedure Label0OnClick(Sender: TObject);
    procedure Label1OnClick(Sender: TObject);
    procedure Label2OnClick(Sender: TObject);
    procedure Label3OnClick(Sender: TObject);
    procedure Label4OnClick(Sender: TObject);
    procedure Edit0Exit(Sender: TObject);

    function GetEventsActive: boolean;
    procedure SetEventsActive(AActive: boolean);

    procedure InitEdit(AEdit: TEdit; ATop, ATabOrder: integer; AVisible: boolean = False);
    procedure InitLabel(ALabel: TLabel; ATop: integer; AColor: TColor);

  protected
    { Protected declarations }
  public
    { Public declarations }
    constructor Create(AOwner: TComponent); override;

    function GetEditText: string;
    procedure SetEditText(const Value: string);

    function GetLabelText(const Index: integer): string;
    procedure SetLabelText(const Index: integer; const Value: string);

    procedure AdjustControlWidth(AWidth: Integer);
  published
    { Published declarations }
    property EventsActive: boolean read GetEventsActive write SetEventsActive;

    property Edit0Text: string read GetEditText write SetEditText;
    property Label0Text: string index 0 read GetLabelText write SetLabelText;
    property Label1Text: string index 1 read GetLabelText write SetLabelText;
    property Label2Text: string index 2 read GetLabelText write SetLabelText;
    property Label3Text: string index 3 read GetLabelText write SetLabelText;
    property Label4Text: string index 4 read GetLabelText write SetLabelText;

    property OnEditChange: TNotifyEvent read GetEdit0OnChange write SetEdit0OnChange;
    property OnLabel1Click: TNotifyEvent index 1 read GetLabelOnClick write SetLabelOnClick;
    property OnLabel2Click: TNotifyEvent index 2 read GetLabelOnClick write SetLabelOnClick;
    property OnLabel3Click: TNotifyEvent index 3 read GetLabelOnClick write SetLabelOnClick;
    property OnLabel4Click: TNotifyEvent index 4 read GetLabelOnClick write SetLabelOnClick;

  end;

procedure Register;

implementation

procedure Register;
begin
  RegisterComponents('Standard', [TEditPanel]);
end;

{ TEditPanel }

function TEditPanel.GetEdit0OnChange: TNotifyEvent;
begin
  Result := FEdit0OnChangeEvent;
end;

procedure TEditPanel.HandleEditKeyUp(Sender: TObject; var Key: Word; Shift: TShiftState);
begin
  if Key <> 13 then
    Exit;
  if @FEdit0OnChangeEvent <> nil then
    FEdit0OnChangeEvent(Edit0);
  Edit0.Visible := False;
end;

procedure TEditPanel.SetEdit0OnChange(const Value: TNotifyEvent);
begin
  FEdit0OnChangeEvent := Value;

  Edit0.OnEditingDone := FEdit0OnChangeEvent;
  Edit0.OnKeyUp := @HandleEditKeyUp;
end;

procedure TEditPanel.Label0OnClick(Sender: TObject);
begin
  TLabel(Sender).Visible := False;

  Edit0.Visible := True;
  Edit0.SetFocus;
  Edit0Text := TLabel(Sender).Caption;

end;

procedure TEditPanel.Label1OnClick(Sender: TObject);
begin
  if Assigned(FLabel1OnClickEvent) then
    FLabel1OnClickEvent(Self);

end;

procedure TEditPanel.Label2OnClick(Sender: TObject);
begin
  if Assigned(FLabel2OnClickEvent) then
    FLabel2OnClickEvent(Self);

end;

procedure TEditPanel.Label3OnClick(Sender: TObject);
begin
  if Assigned(FLabel3OnClickEvent) then
    FLabel3OnClickEvent(Self);

end;

procedure TEditPanel.Label4OnClick(Sender: TObject);
begin
  if Assigned(FLabel4OnClickEvent) then
    FLabel4OnClickEvent(Self);

end;


procedure TEditPanel.Edit0Exit(Sender: TObject);
begin
  TEdit(Sender).Visible := False;
  Label0Text := TEdit(Sender).Text;
  Label0.Visible := True;
end;

function TEditPanel.GetEventsActive: boolean;
begin
  Result := FEventsActive;
end;

procedure TEditPanel.SetEventsActive(AActive: boolean);
begin
  FEventsActive := AActive;

  if FEventsActive then
  begin
    Edit0.OnExit := @Edit0Exit;
    Label0.OnClick := @Label0OnClick;
    Label1.OnClick := @Label1OnClick;
    Label2.OnClick := @Label2OnClick;
    Label3.OnClick := @Label3OnClick;
    Label4.OnClick := @Label4OnClick;
  end;

end;

function TEditPanel.GetEditText: string;
begin

  Result := Edit0.Text;

end;

procedure TEditPanel.SetEditText(const Value: string);
begin

  Edit0.Text := Value;

end;

function TEditPanel.GetLabelText(const Index: integer): string;
begin
  case index of
    0:
      Result := Label0.Caption;
    1:
      Result := Label1.Caption;
    2:
      Result := Label2.Caption;
    3:
      Result := Label3.Caption;
    4:
      Result := Label4.Caption;
  end;
end;

procedure TEditPanel.SetLabelText(const Index: integer; const Value: string);
begin
  case index of
    0:
      Label0.Caption := Value;
    1:
      Label1.Caption := Value;
    2:
      Label2.Caption := Value;
    3:
      Label3.Caption := Value;
    4:
      Label4.Caption := Value;
  end;
end;

procedure TEditPanel.AdjustControlWidth(AWidth: Integer);
begin
  Width := AWidth;
  Edit0.Width := AWidth - 1;
  Label0.Width := AWidth - 1;
  Label1.Width := AWidth - 1;
  Label2.Width := AWidth - 1;
  Label3.Width := AWidth - 1;
  Label4.Width := AWidth - 1;
end;

function TEditPanel.GetLabelOnClick(const Index: integer): TNotifyEvent;
begin
  case index of
    1:
      Result := Label1.OnClick;
    2:
      Result := Label2.OnClick;
    3:
      Result := Label3.OnClick;
    4:
      Result := Label4.OnClick;
  end;

end;

procedure TEditPanel.SetLabelOnClick(const Index: integer; const Value: TNotifyEvent);
begin
  case index of
    1:
    begin
      FLabel1OnClickEvent := Value;
      Label1.OnClick := Value;
    end;
    2:
    begin
      FLabel2OnClickEvent := Value;
      Label2.OnClick := Value;
    end;
    3:
    begin
      FLabel3OnClickEvent := Value;
      Label3.OnClick := Value;
    end;
    4:
    begin
      FLabel4OnClickEvent := Value;
      Label4.OnClick := Value;
    end;
  end;

end;

procedure TEditPanel.InitEdit(AEdit: TEdit; ATop, ATabOrder: integer; AVisible: boolean = False);
begin
  AEdit.Parent := Self;
  AEdit.SetSubComponent(True);
  AEdit.Left := 0;
  AEdit.Top := ATop;
  AEdit.Height := 21;
  AEdit.Width := Width - 1;
  // AEdit.BorderStyle := bsNone;
  AEdit.TabOrder := ATabOrder;
  AEdit.Visible := AVisible;
  // AEdit.Font.Color := clGray;
end;

procedure TEditPanel.InitLabel(ALabel: TLabel; ATop: integer; AColor: TColor);
begin
  ALabel.Parent := Self;
  ALabel.SetSubComponent(True);
  ALabel.Left := 0;
  ALabel.Top := ATop;
  ALabel.Height := 21;
  ALabel.Width := Width - 1;
  ALabel.Font.Color := AColor;
end;

constructor TEditPanel.Create(AOwner: TComponent);
begin
  inherited Create(AOwner);

  Caption := EmptyStr;
  Height := 117;
  Width := 289;
  BevelOuter := bvNone;
  ClientHeight := 117;
  ClientWidth := 289;

  FEditState := esDisplay;

  Edit0 := TEdit.Create(Self);
  Label0 := TLabel.Create(Self);
  Label1 := TLabel.Create(Self);
  Label2 := TLabel.Create(Self);
  Label3 := TLabel.Create(Self);
  Label4 := TLabel.Create(Self);

  InitEdit(Edit0, 0, 0, False);
  InitLabel(Label0, 0, clBlack);
  InitLabel(Label1, 24, clGray);
  InitLabel(Label2, 48, clGray);
  InitLabel(Label3, 72, clGray);
  InitLabel(Label4, 96, clGray);

  if FEventsActive then
  begin
    Edit0.OnExit := @Edit0Exit;
    Label0.OnClick := @Label0OnClick;
    Label1.OnClick := @Label1OnClick;
    Label2.OnClick := @Label2OnClick;
    Label3.OnClick := @Label3OnClick;
    Label4.OnClick := @Label4OnClick;
  end;

end;

end.
