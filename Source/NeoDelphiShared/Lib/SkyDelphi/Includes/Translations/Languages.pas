unit Languages;

interface

uses
  Classes, SkyLists, TypesConsts, StringArray;

const
  const_CHANGEDTEXT = '#CHANGED#';

type
  TLabelImportEvent = (lieImportStarted, lieImportLanguageSkipped, lieLabelAdded,
    lieLabelUpdated, lieLabelDeleted, lieLabelSkipped, lieImportFinished);
  TLabelImportNotifyEvent = procedure(Sender: TObject; AnEvent: TLabelImportEvent;
    SomeDetails: string) of object;

  TLanguage = TSkyStringStringList;
  TLanguages = class(TObject)
  private
    class var
      FInstance: TLanguages;
    var
      FTranslationFolder: string; // Folder where the .trns files are
      FTranslationsFile: string; // Translations.pas file
      FTranslations: TSkyStringList;
      FImports: TSkyStringStringList; // list of import sources(Alias = Path)
      FImportedLabels: TSkyStringStringList; // list of labels(Label = Import Source)
      FRegisteredLabels: TSkyStringStringList; // for keeping track of registered labels
      FOnLanguagesLoaded: TNotifyEvent;
      FOnLabelImportEvent: TLabelImportNotifyEvent;
      FLoaded: Boolean;
    procedure AddLanguage(const ALanguage: string);
    procedure AddLanguageFromResource(const ALanguage: string);
    function FirstLanguage: TLanguage;
    function GetLabelCount: Integer;
    function GetLanguageCount: Integer;
    function GetLanguageObject(Index: Integer): TLanguage;
    function GetLanguageName(Index: Integer): string;
    function GetAllItems: TStringArray;
    function GetLabel(Index: Integer): string;
    function GetTranslation(const ALabel, ALanguage: string): string;
    procedure SetTranslation(const ALabel, ALanguage, Value: string);
    function GetLabelSource(const ALabelName: string): string;
    procedure SetLabelSource(const ALabelName, Value: string);
    procedure ImportAddLabel(ASource: TLanguages; const AnAlias, ALabel: string);
    procedure ImportUpdateLabel(ASource: TLanguages; const AnAlias, ALabel: string);
    procedure ImportDeleteLabel(const ALabel: string);
    procedure GenerateImportsFile;
    procedure GenerateTranslationsFile;
  protected
 {$IFDEF VER210}
    class constructor Create;
    class destructor Destroy;
 {$ENDIF}
  public
    constructor Create; reintroduce;
    destructor Destroy; override;

    class property Instance: TLanguages read FInstance;

    function LanguageByName(const ALanguageName: string): TLanguage;
    procedure LoadFromPath(const AFilePath: string; LoadImportConfig: Boolean = True);
    procedure LoadFromResources;
    procedure SaveToPath(const AFilePath: string = '');
    procedure ImportLabelsFromSource(ASource: TLanguages; const AFolderPath, AnAlias: string);
    procedure ImportLabelsFromLocation(const AFolderPath, AnAlias: string);
    procedure RemoveImportSource(const ASourceAlias: string; KeepLabels: Boolean = False);
    procedure RemoveImportedLabels;
    procedure AddLabel(const AName: string);
    procedure DeleteLabel(const AName: string);
    procedure SetLabelChanged(const ALabelName: string);
    function LabelIndex(const ALabelName: string): Integer;
    function LabelOrder(const ALabelName: string): Integer;
    function Translate(const AName: string; ALanguage: Integer = -1): string;

    property AllItems: TStringArray read GetAllItems;
    property Count: Integer read GetLanguageCount; // Language count
    property Imports: TSkyStringStringList read FImports;
    property LabelCount: Integer read GetLabelCount;
    property Labels[Index: Integer]: string read GetLabel; // Labels
    property LabelSources[const ALabelName: string]: string read GetLabelSource write SetLabelSource; // Import source per translation
    property Loaded: Boolean read FLoaded write FLoaded;
    property Names[Index: Integer]: string read GetLanguageName; // Language Name
    property Objects[Index: Integer]: TLanguage read GetLanguageObject; default; // Language object
    property TranslationFolder: string read FTranslationFolder;
    property Translations[const ALabel, ALanguage: string]: string read GetTranslation write SetTranslation; // Translation values
    property OnLanguagesLoaded: TNotifyEvent read FOnLanguagesLoaded write FOnLanguagesLoaded;
    property OnLabelImportEvent: TLabelImportNotifyEvent read FOnLabelImportEvent write FOnLabelImportEvent;
 end;

var
  glbLanguage: Integer = -1;

implementation

uses
  SysUtils, TypesFunctions, IniFiles, StringUtils, Types;

{ TLanguages }

procedure TLanguages.AddLabel(const AName: string);
var
  I: Integer;
begin
  if FirstLanguage.IndexOf(AName) <> -1 then
    raise Exception.Create('Translation allready exists');
  for I := 0 to FTranslations.Count - 1 do
    (FTranslations.Objects[I] as TLanguage).AddObject(AName, const_CHANGEDTEXT);
end;

procedure TLanguages.SaveToPath(const AFilePath: string);
var
  I: Integer;
begin
  if AFilePath <> '' then
    FTranslationFolder := AFilePath;
  for I := 0 to Count - 1 do
    Objects[I].SaveToFile(FTranslationFolder + Names[I] + '.trns');
  FImportedLabels.SaveToFile(FTranslationFolder + 'Imports.trns');
  GenerateImportsFile;
  GenerateTranslationsFile;
end;

procedure TLanguages.SetLabelSource(const ALabelName, Value: string);
begin
  if Value = '' then
  begin
    FImportedLabels.Delete(ALabelName);
    Exit;
  end;
  FImportedLabels.Delete(ALabelName);
  FImportedLabels.AddObject(ALabelName, Value);
end;

procedure TLanguages.SetLabelChanged(const ALabelName: string);
var
  I: Integer;
  TheLanguage: TLanguage;
begin
  for I := 0 to Count - 1 do
  begin
    TheLanguage := FTranslations.Objects[I] as TLanguage;
    if TheLanguage.ObjectOfValue[ALabelName] <> const_CHANGEDTEXT then
      TheLanguage.ObjectOfValue[ALabelName] := const_CHANGEDTEXT + TheLanguage.ObjectOfValue[ALabelName];
  end;
end;

procedure TLanguages.SetTranslation(const ALabel, ALanguage, Value: string);
var
  TheIndex: Integer;
  TheLanguage: TLanguage;
begin
  TheLanguage := LanguageByName(ALanguage);
  TheIndex := TheLanguage.IndexOf(ALabel);
  TheLanguage.Objects[TheIndex] := ReplaceEnter(Value);
end;

function TLanguages.Translate(const AName: string; ALanguage: Integer): string;
begin
  if ALanguage = -1 then
    ALanguage := glbLanguage;
  if (ALanguage = -1) or (FTranslations.Count = 0) then
    Result := AName
  else
    Result := Translations[AName, Names[ALanguage]];
end;

constructor TLanguages.Create;
begin
  inherited Create;
  FTranslations := TSkyStringList.Create(True);
  FImports := TSkyStringStringList.Create;
  FImports.Sorted := False;
  FImportedLabels := TSkyStringStringList.Create;
  FRegisteredLabels := TSkyStringStringList.Create;
  FLoaded := False;
end;

procedure TLanguages.DeleteLabel(const AName: string);
var
  I: Integer;
begin
  for I := 0 to Count - 1  do
    Objects[I].Delete(AName);
  FImportedLabels.Delete(AName);
end;

destructor TLanguages.Destroy;
begin
  FreeAndNil(FTranslations);
  FreeAndNil(FImports);
  FreeAndNil(FImportedLabels);
  FreeAndNil(FRegisteredLabels);
  inherited;
end;

function TLanguages.FirstLanguage: TLanguage;
begin
  Result := FTranslations.Objects[0] as TLanguage;
end;

procedure TLanguages.GenerateImportsFile;
var
  TheImportConfigFileName: string;
  TheImportsIniFile: TIniFile;
  I: Integer;
begin
  TheImportConfigFileName := FTranslationFolder + 'Imports.trnsconf';
  TheImportsIniFile := TIniFile.Create(ExpandEnviromentString(TheImportConfigFileName));
  try
    TheImportsIniFile.WriteInteger('Imports', 'Count', FImports.Count);

    for I := 1 to FImports.Count do
    begin
      TheImportsIniFile.WriteString('Import' + IntToStr(I), 'Name', FImports.Items[I - 1]);
      TheImportsIniFile.WriteString('Import' + IntToStr(I), 'Path', FImports.Objects[I - 1]);
    end;
  finally
    FreeAndNil(TheImportsIniFile);
  end;
end;

procedure TLanguages.GenerateTranslationsFile;
var
  TheFileContent: TSkyStringList;
  I: Integer;
  TheUnitName: string;
  TheFileText: AnsiString;
  TheFile: TFileStream;
begin
  TheFile := nil;
  TheFileContent := TSkyStringList.Create;
  try
    TheFileContent.Sorted := False;
    TheUnitName := ExtractFileName(FTranslationsFile);
    TheUnitName := ChangeFileExt(TheUnitName, '');
    TheFileContent.Add('unit ' + TheUnitName + ';' + ReturnLF
      + ReturnLF
      + 'interface' + ReturnLF
      + ReturnLF
      + 'const');
    for I := 0 to LabelCount - 1 do
      TheFileContent.Add('  ' + Labels[I] + ' = ''' + Labels[I] + ''';');
    TheFileContent.Add(ReturnLf +
      'function Translate(const AName: WideString;' +
      'ALanguage: Integer = -1): WideString;' + ReturnLf + ReturnLf +
      'implementation' + ReturnLf + ReturnLf +
      'uses' + ReturnLf +
      '  Languages;' + ReturnLf + ReturnLf +
      'function Translate(const AName: WideString; ALanguage: Integer = -1): WideString;' + ReturnLf +
      'begin'+ ReturnLf +
      '  Result := TLanguages.Instance.Translate(AName, ALanguage);' + ReturnLf +
      'end;' + ReturnLf + ReturnLf +
      'end.' + ReturnLf);
    TheFileText := AnsiString(TheFileContent.GetTextStr);
    TheFile := TFileStream.Create(FTranslationsFile, fmCreate);
    TheFile.Write(TheFileText[1], Length(TheFileText));
  finally
    TheFileContent.Free;
    TheFile.Free;
  end;
end;

function TLanguages.GetAllItems: TStringArray;
begin
  Result := FTranslations.GetAllItems;
end;

function TLanguages.GetLabel(Index: Integer): string;
begin
  Result := FirstLanguage[Index];
end;

function TLanguages.GetLabelCount: Integer;
begin
  Result := FirstLanguage.Count;
end;

function TLanguages.GetLanguageObject(Index: Integer): TLanguage;
begin
  Result := FTranslations.Objects[Index] as TLanguage;
end;

function TLanguages.GetLanguageCount: Integer;
begin
  Result := FTranslations.Count;
end;

function TLanguages.GetLanguageName(Index: Integer): string;
begin
  Result := FTranslations[Index];
end;

function TLanguages.GetTranslation(const ALabel, ALanguage: string): string;
var
  TheLanguage: TLanguage;
begin
  TheLanguage := LanguageByName(ALanguage);
  Result := RestoreEnter(TheLanguage.ObjectOfValueDefault[ALabel, ALabel]);
end;

procedure TLanguages.ImportAddLabel(ASource: TLanguages; const AnAlias,
  ALabel: string);
var
  I: Integer;
  TheSourceLanguage: TLanguage;
begin
  AddLabel(ALabel);
  FImportedLabels.AddObject(ALabel, AnAlias);
  for I := 0 to Count - 1 do
  begin
    TheSourceLanguage := ASource.LanguageByName(Names[I]);
    if Assigned(TheSourceLanguage) then
      Objects[I].ObjectOfValue[ALabel] := TheSourceLanguage.ObjectOfValue[ALabel];
  end;
  if Assigned(FOnLabelImportEvent) then
    FOnLabelImportEvent(Self, lieLabelAdded, ALabel);
end;

procedure TLanguages.ImportDeleteLabel(const ALabel: string);
begin
  DeleteLabel(ALabel);
  if Assigned(FOnLabelImportEvent) then
    FOnLabelImportEvent(Self, lieLabelDeleted, ALabel);
end;

procedure TLanguages.ImportUpdateLabel(ASource: TLanguages; const AnAlias,
  ALabel: string);
var
  TheChangedLanguages: string;
  TheSourceValue, TheLocalValue: string;
  TheSourceLanguage: TLanguage;
  I: Integer;
begin
  TheChangedLanguages := '';

  for I := 0 to Count - 1 do
  begin
    TheSourceLanguage := ASource.LanguageByName(Names[I]);
    if not Assigned(TheSourceLanguage) then
      Continue;
    TheSourceValue := TheSourceLanguage.ObjectOfValue[ALabel];
    TheLocalValue := Objects[I].ObjectOfValue[ALabel];
    if TheSourceValue = TheLocalValue then
      Continue;
    if TheChangedLanguages <> '' then
      TheChangedLanguages := TheChangedLanguages + ', ';
    TheChangedLanguages := TheChangedLanguages + Names[I];
    Objects[I].ObjectOfValue[ALabel] := TheSourceValue;
  end;

  if (TheChangedLanguages <> '') and Assigned(FOnLabelImportEvent) then
    FOnLabelImportEvent(Self, lieLabelUpdated, ALabel + '(' + TheChangedLanguages + ')');
end;

procedure TLanguages.ImportLabelsFromLocation(const AFolderPath, AnAlias: string);
var
  TheSource: TLanguages;
begin
  TheSource := TLanguages.Create;
  try
    TheSource.LoadFromPath(AFolderPath, True);
    TheSource.RemoveImportedLabels;
    ImportLabelsFromSource(TheSource, AFolderPath, AnAlias);
  finally
    FreeAndNil(TheSource);
  end;
end;

procedure TLanguages.ImportLabelsFromSource(ASource: TLanguages; const AFolderPath, AnAlias: string);
var
  I: Integer;
  TheLabel: string;
begin
  if Assigned(FOnLabelImportEvent) then
    FOnLabelImportEvent(Self, lieImportStarted, AnAlias);

  // notify the user if there are extra languages in the source
  if Assigned(FOnLabelImportEvent) then
    for I := 0 to ASource.Count - 1 do
      if LanguageByName(ASource.Names[I]) = nil then
        FOnLabelImportEvent(Self, lieImportLanguageSkipped, ASource.Names[I]);

  // loop thru the labels
  for I := 0 to ASource.LabelCount - 1 do
  begin
    TheLabel := ASource.Labels[I];
    // if we don't have the label then add it
    if LabelIndex(TheLabel) = -1 then
    begin
      ImportAddLabel(ASource, AnAlias, TheLabel);
      Continue;
    end;
    // if we have it imported from this source then update it
    if LabelSources[TheLabel] = AnAlias then
    begin
      ImportUpdateLabel(ASource, AnAlias, TheLabel);
      Continue;
    end;
    // if the label is local or imported from a different source then report it
    if Assigned(FOnLabelImportEvent) then
      FOnLabelImportEvent(Self, lieLabelSkipped, TheLabel);
  end;

  // check for translations that were deleted
  I := 0;
  while I < LabelCount do
  begin
    TheLabel := Labels[I];
    if (LabelSources[TheLabel] = AnAlias) and (ASource.LabelIndex(TheLabel) = -1) then
      ImportDeleteLabel(TheLabel);
    Inc(I);
  end;

  // if the import source does not exist then add it
  if FImports.IndexOf(AnAlias) = -1 then
    FImports.Add(AnAlias, AFolderPath);

  if Assigned(FOnLabelImportEvent) then
    FOnLabelImportEvent(Self, lieImportFinished, AnAlias);
end;

function TLanguages.GetLabelSource(const ALabelName: string): string;
begin
  Result := FImportedLabels.ObjectOfValueDefault[ALabelName, ''];
end;

procedure TLanguages.AddLanguage(const ALanguage: string);
var
  TheLanguage: TLanguage;
begin
  TheLanguage := TLanguage.Create;
  FTranslations.AddObject(ALanguage, TheLanguage);
  TheLanguage.LoadFromFile(ExpandEnviromentString(FTranslationFolder) + ALanguage + '.trns');
end;

procedure TLanguages.AddLanguageFromResource(const ALanguage: string);
var
  TheLanguage: TLanguage;
  TheResStream: TResourceStream;
begin
  TheLanguage := TLanguage.Create;
  FTranslations.AddObject(ALanguage, TheLanguage);
  TheResStream := TResourceStream.Create(HInstance, ALanguage + '_trns', RT_RCDATA);
  try
    TheLanguage.LoadFromStream(TheResStream);
  finally
    TheResStream.Free;
  end;
end;

{$IFDEF VER210}
class constructor TLanguages.Create;
{$ELSE}
procedure TLanguagesCreate;
 {$ENDIF}
begin
  TLanguages.FInstance := TLanguages.Create;
end;

{$IFDEF VER210}
class destructor TLanguages.Destroy;
{$ELSE}
procedure TLanguagesDestroy;
 {$ENDIF}
begin
  TLanguages.FInstance.Free;
end;

function TLanguages.LabelIndex(const ALabelName: string): Integer;
begin
  Result := FirstLanguage.IndexOf(ALabelName);
end;

function TLanguages.LabelOrder(const ALabelName: string): Integer;
begin
  FirstLanguage.Find(ALabelName, Result);
end;

function TLanguages.LanguageByName(const ALanguageName: string): TLanguage;
var
  TheIndex: Integer;
begin
  TheIndex := FTranslations.IndexOf(ALanguageName);
  if TheIndex <> -1 then
    Result := FTranslations.Objects[TheIndex] as TLanguage
  else
    Result := nil;
end;

procedure TLanguages.LoadFromPath(const AFilePath: string; LoadImportConfig: Boolean);
var
  TheFilePath: string;
  TheIniFile: TIniFile;
  TheImportsIniFile: TIniFile;
  TheImportConfigFileName, TheImportFileName, TheLanguage, TheName, ThePath: string;
  I, TheCount: Integer;
begin
  TheFilePath := IncludeTrailingPathDelimiter(AFilePath);
  FTranslationFolder := '';
  TheImportConfigFileName := TheFilePath + 'Imports.trnsconf';
  TheImportFileName := TheFilePath + 'Imports.trns';
  TheIniFile := TIniFile.Create(ExpandEnviromentString(TheFilePath) + 'Language.trnsconf');

  TheImportsIniFile := TIniFile.Create(ExpandEnviromentString(TheImportConfigFileName));
  try
    FTranslations.Clear;
    if (not FileExists(ExpandEnviromentString(TheFilePath) + 'Language.trnsconf')) then
      raise Exception.Create(Format('File does not exist %s', [TheFilePath + 'Language.trnsconf']));
    FTranslationFolder := TheFilePath;

    // read the language files
    TheCount := 1;
    repeat
      TheLanguage := TheIniFile.ReadString('Languages', 'Language' + IntToStr(TheCount), '');
      if TheLanguage <> '' then
        AddLanguage(TheLanguage);
      Inc(TheCount);
    until (TheLanguage = '');

    if LoadImportConfig then
    begin
      // read the imports files
      FImports.Clear;
      FImportedLabels.Clear;
      if FileExists(ExpandEnviromentString(TheImportConfigFileName)) then
      begin
        FTranslationsFile := ExpandEnviromentString(TheImportsIniFile.ReadString('Config', 'GeneratedFilePath', ''));
        TheCount := TheImportsIniFile.ReadInteger('Imports', 'Count', 0);
        for I := 1 to TheCount do
        begin
          TheName := TheImportsIniFile.ReadString('Import' + IntToStr(I), 'Name', '');
          ThePath := TheImportsIniFile.ReadString('Import' + IntToStr(I), 'Path', '');
          if (TheName = '') or (ThePath = '') then
            raise Exception.Create(Format('Invalid import(%d) name or path in file Imports.trnsConf', [I]));
          FImports.AddObject(TheName, ThePath);
        end;

        // read imported labels ( if a label exists in this file then it is imported)
        if FileExists(ExpandEnviromentString(TheImportFileName)) then
          FImportedLabels.LoadFromFile(ExpandEnviromentString(TheImportFileName));
      end;
    end;
    FLoaded := True;
    if Assigned(FOnLanguagesLoaded) then
      FOnLanguagesLoaded(Self);
  finally
    TheImportsIniFile.Free;
    TheIniFile.Free;
  end;
end;

procedure TLanguages.LoadFromResources;
var
  TheCount: Integer;
  TheIniFile: TMemIniFile;
  TheIniString: TSkyString;
  TheLanguage: string;
  TheResStream: TResourceStream;
  TheStrings: TStringList;
begin
  TheResStream := TResourceStream.Create(HInstance, 'Language_trnsconf', RT_RCDATA);
  try
    TheIniString := TSkyStringList.LoadTextFromStream(TheResStream);
  finally
    TheResStream.Free;
  end;

  TheStrings := nil;
  TheIniFile := TMemIniFile.Create('');
  try
    TheStrings := TStringList.Create;
    TheStrings.Text := TheIniString;
    TheIniFile.SetStrings(TheStrings);
    FTranslations.Clear;
    // read the language files
    TheCount := 1;
    repeat
      TheLanguage := TheIniFile.ReadString('Languages', 'Language' + IntToStr(TheCount), '');
      if TheLanguage <> '' then
        AddLanguageFromResource(TheLanguage);
      Inc(TheCount);
    until (TheLanguage = '');
  finally
    TheStrings.Free;
    TheIniFile.Free;
  end;
end;

procedure TLanguages.RemoveImportedLabels;
var
  I: Integer;
begin
  for I := 0 to FImportedLabels.Count - 1 do
    DeleteLabel(FImportedLabels[I]);
  FImportedLabels.Clear;
  FImports.Clear;
end;

procedure TLanguages.RemoveImportSource(const ASourceAlias: string; KeepLabels: Boolean);
var
  TheIndex: Integer;
begin
  if not KeepLabels then
  begin
    // delete the labels
    repeat
      TheIndex := FImportedLabels.IndexOfObject(ASourceAlias);
      if TheIndex <> -1 then
        DeleteLabel(FImportedLabels[TheIndex]);
    until TheIndex = -1;
  end;
  // delete source from Imports.trnsconf
  FImports.Delete(ASourceAlias);
  // delete source from Imports.trns
  repeat
    TheIndex := FImportedLabels.IndexOfObject(ASourceAlias);
    FImportedLabels.DeleteFromIndex(TheIndex);
  until TheIndex = -1;
end;

{$IFNDEF VER210}
initialization
  TLanguagesCreate;

finalization
  TLanguagesDestroy;
{$ENDIF}

end.
