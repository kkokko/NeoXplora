object frmDebug: TfrmDebug
  Left = 467
  Top = 329
  Caption = 'frmDebug - v1'
  ClientHeight = 545
  ClientWidth = 907
  Color = clBtnFace
  Font.Charset = DEFAULT_CHARSET
  Font.Color = clWindowText
  Font.Height = -11
  Font.Name = 'MS Sans Serif'
  Font.Style = []
  OldCreateOrder = False
  OnDestroy = FormDestroy
  OnShow = FormShow
  DesignSize = (
    907
    545)
  PixelsPerInch = 96
  TextHeight = 13
  object lblScript: TLabel
    Left = 16
    Top = 60
    Width = 27
    Height = 13
    Caption = 'Script'
  end
  object lblResult: TLabel
    Left = 16
    Top = 300
    Width = 30
    Height = 13
    Caption = 'Result'
  end
  object btnClose: TButton
    Left = 564
    Top = 8
    Width = 96
    Height = 25
    Caption = 'Close'
    TabOrder = 0
    OnClick = btnCloseClick
  end
  object btnRunScript: TButton
    Left = 751
    Top = 77
    Width = 145
    Height = 25
    Caption = 'Run script'
    TabOrder = 1
    OnClick = btnRunScriptClick
  end
  object memoInputScript: TMemo
    Left = 8
    Top = 79
    Width = 729
    Height = 210
    Lines.Strings = (
      '!Request.php'
      
        '!{"ClassName":"TRequestGetPosForSentences","Properties":{"Senten' +
        'ces":[{"ClassName":"TEntityWithName","Properties":{"Id":"0","Nam' +
        'e":"At first he was running","Version":""}},{"ClassName":"TEntit' +
        'yWithName","Properties":{"Id":"1","Name":"The running hurt him",' +
        '"Version":""}},{"ClassName":"TEntityWithName","Properties":{"Id"' +
        ':"2","Name":"His legs hurt","Version":""}}]}}')
    ScrollBars = ssBoth
    TabOrder = 2
    WordWrap = False
  end
  object memoResult: TMemo
    Left = 8
    Top = 319
    Width = 891
    Height = 218
    Anchors = [akLeft, akTop, akRight, akBottom]
    Color = clBtnFace
    ReadOnly = True
    ScrollBars = ssBoth
    TabOrder = 3
    WordWrap = False
  end
  object btnRecreateTables: TButton
    Left = 8
    Top = 8
    Width = 145
    Height = 25
    Caption = 'Recreate Tables'
    TabOrder = 4
    OnClick = btnRecreateTablesClick
  end
end
