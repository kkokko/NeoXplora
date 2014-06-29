object frmMain: TfrmMain
  Left = 0
  Top = 0
  Caption = 'frmMain'
  ClientHeight = 300
  ClientWidth = 636
  Color = clBtnFace
  Font.Charset = DEFAULT_CHARSET
  Font.Color = clWindowText
  Font.Height = -11
  Font.Name = 'Tahoma'
  Font.Style = []
  OldCreateOrder = False
  OnCreate = FormCreate
  OnDestroy = FormDestroy
  DesignSize = (
    636
    300)
  PixelsPerInch = 96
  TextHeight = 13
  object Label1: TLabel
    Left = 8
    Top = 72
    Width = 62
    Height = 13
    Caption = 'Parsed(Gb.):'
  end
  object Label2: TLabel
    Left = 128
    Top = 48
    Width = 61
    Height = 13
    Caption = 'Read pages:'
  end
  object lblParsedMb: TLabel
    Left = 96
    Top = 72
    Width = 6
    Height = 13
    Caption = '0'
  end
  object lblReadPages: TLabel
    Left = 216
    Top = 48
    Width = 6
    Height = 13
    Caption = '0'
  end
  object Label3: TLabel
    Left = 8
    Top = 96
    Width = 53
    Height = 13
    Caption = 'Total(Gb.):'
  end
  object lblTotalMb: TLabel
    Left = 96
    Top = 96
    Width = 6
    Height = 13
    Caption = '0'
  end
  object Label4: TLabel
    Left = 8
    Top = 48
    Width = 74
    Height = 13
    Caption = 'Time(Seconds):'
  end
  object lblParsedSeconds: TLabel
    Left = 96
    Top = 48
    Width = 6
    Height = 13
    Caption = '0'
  end
  object Label5: TLabel
    Left = 8
    Top = 13
    Width = 46
    Height = 13
    Caption = 'Filename:'
  end
  object lblFileName: TLabel
    Left = 72
    Top = 13
    Width = 474
    Height = 13
    Anchors = [akLeft, akTop, akRight]
    AutoSize = False
  end
  object lblParsedPagesC: TLabel
    Left = 128
    Top = 72
    Width = 69
    Height = 13
    Caption = 'Parsed pages:'
  end
  object lblParsedPages: TLabel
    Left = 216
    Top = 72
    Width = 6
    Height = 13
    Caption = '0'
  end
  object Label8: TLabel
    Left = 128
    Top = 120
    Width = 77
    Height = 13
    Caption = 'Inserted pages:'
  end
  object lblInsertedPages: TLabel
    Left = 216
    Top = 120
    Width = 6
    Height = 13
    Caption = '0'
  end
  object Label6: TLabel
    Left = 128
    Top = 96
    Width = 66
    Height = 13
    Caption = 'Insert queue:'
  end
  object lblInserteQueuePages: TLabel
    Left = 216
    Top = 96
    Width = 6
    Height = 13
    Caption = '0'
  end
  object btnStartStop: TButton
    Left = 552
    Top = 8
    Width = 75
    Height = 25
    Anchors = [akTop, akRight]
    Caption = 'Start'
    TabOrder = 0
    OnClick = btnStartStopClick
  end
  object Timer1: TTimer
    Enabled = False
    Interval = 200
    OnTimer = Timer1Timer
    Left = 240
    Top = 32
  end
end
