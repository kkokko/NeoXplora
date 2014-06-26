unit InputDate;

interface

uses
  Windows, Messages, SysUtils, Variants, Classes, Graphics, Controls, Forms,
  Dialogs, StdCtrls, Mask, JvExMask, JvToolEdit, JvMaskEdit, JvCheckedMaskEdit,
  JvDatePickerEdit;

type
  TInputDateForm = class(TForm)
    lblPrompt: TLabel;
    dpDate: TJvDatePickerEdit;
    btnOk: TButton;
    btnCancel: TButton;
    procedure btnOkClick(Sender: TObject);
  private
    FResult: TDate;
  public
    function InstanceExecute(const ACaption, APrompt: string; AValue: TDate): TDate;
    class function Execute(const ACaption, APrompt: string; AValue: TDate): TDate;
  end;

var
  InputDateForm: TInputDateForm;

implementation

{$R *.dfm}

{ TTInputDateForm }

procedure TInputDateForm.btnOkClick(Sender: TObject);
begin
  FResult := dpDate.Date;
end;

class function TInputDateForm.Execute(const ACaption, APrompt: string;
  AValue: TDate): TDate;
var
  TheForm: TInputDateForm;
begin
  TheForm := TInputDateForm.Create(nil);
  try
    Result := TheForm.InstanceExecute(ACaption, APrompt, AValue);
  finally
    TheForm.Free;
  end;
end;

function TInputDateForm.InstanceExecute(const ACaption, APrompt: string;
  AValue: TDate): TDate;
begin
  FResult := AValue;
  Caption := ACaption;
  lblPrompt.Caption := APrompt;
  dpDate.Date := AValue;
  ShowModal;
  Result := FResult;
end;

end.
