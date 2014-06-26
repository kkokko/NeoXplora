object InputDateForm: TInputDateForm
  Left = 0
  Top = 0
  BorderIcons = [biSystemMenu]
  Caption = 'Caption'
  ClientHeight = 102
  ClientWidth = 214
  Color = clBtnFace
  Font.Charset = DEFAULT_CHARSET
  Font.Color = clWindowText
  Font.Height = -11
  Font.Name = 'Tahoma'
  Font.Style = []
  OldCreateOrder = False
  Position = poMainFormCenter
  PixelsPerInch = 96
  TextHeight = 13
  object lblPrompt: TLabel
    Left = 8
    Top = 16
    Width = 38
    Height = 13
    Caption = 'Prompt:'
  end
  object dpDate: TJvDatePickerEdit
    Left = 8
    Top = 31
    Width = 198
    Height = 21
    AllowNoDate = False
    Checked = True
    DateFormat = 'dd/MM/yyyy'
    DateSeparator = '/'
    StoreDateFormat = True
    TabOrder = 0
  end
  object btnOk: TButton
    Left = 39
    Top = 66
    Width = 64
    Height = 25
    Caption = 'OK'
    ModalResult = 1
    TabOrder = 1
    OnClick = btnOkClick
  end
  object btnCancel: TButton
    Left = 109
    Top = 66
    Width = 64
    Height = 25
    Caption = 'Cancel'
    ModalResult = 2
    TabOrder = 2
  end
end
