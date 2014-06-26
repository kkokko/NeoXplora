unit SkyBaseForm;

interface

uses
  Classes, Controls, Forms;

type
  TfrmSkyBasePointer = ^TfrmSkyBase;
  TfrmSkyBase = class(TForm)
    procedure FormClose(Sender: TObject; var Action: TCloseAction);
  private
  protected
    procedure Initialize; virtual;
  public
    constructor Create(AOwner: TComponent); override;
    procedure Execute; virtual;
    procedure TranslateForm; virtual;
    class procedure TranslateAllForms;
    class function GetInstance(AOwner: TComponent): TfrmSkyBase;
  end;

var
  frmSkyBase: TfrmSkyBase;

implementation

{$R *.dfm}

{ TfrmSkyBase }

constructor TfrmSkyBase.Create(AOwner: TComponent);
begin
  inherited;
  Initialize;
end;

procedure TfrmSkyBase.Execute;
begin
  Show;
end;

procedure TfrmSkyBase.FormClose(Sender: TObject; var Action: TCloseAction);
begin
  Action := caFree;
end;

class function TfrmSkyBase.GetInstance(AOwner: TComponent): TfrmSkyBase;
var
  TheMainForm: TForm;
  I: Integer;
begin
  TheMainForm := AOwner as TForm;
  for I := 0 to TheMainForm.MDIChildCount - 1 do
    if (TheMainForm.MDIChildren[I] is Self) then
    begin
      Result := TheMainForm.MDIChildren[I] as TfrmSkyBase;
      Exit;
    end;
  Result := Create(AOwner);;
end;

procedure TfrmSkyBase.Initialize;
begin
  TranslateForm;
end;

procedure TfrmSkyBase.TranslateForm;
begin
  // to implement in inherited
end;

class procedure TfrmSkyBase.TranslateAllForms;
var
  I: Integer;
  TheForm: TForm;
begin
  for I := 0 to Screen.FormCount - 1 do
  begin
    TheForm := Screen.Forms[I];
    if (TheForm is TfrmSkyBase) then
      (TheForm as TfrmSkyBase).TranslateForm;
  end;
end;

end.
