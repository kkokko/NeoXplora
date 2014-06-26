unit SkyFileStore;

interface

uses
  Classes;

const
  SkyFileStoreVersion: Integer = $00000001;

type
  TFileInfo = packed record
    FileName: string[50];
    FileSize: Int64;
    CRC: Word;
  end;

  TSkyGenericStore = class(TObject)
  private
    FFiles: array of TFileInfo;

    function GetFiles(AIndex: Integer): TFileInfo;
    function GetFileCount: Integer;
    function GetSize: Integer;
    function FileCrcVersion0(AFile: TStream): Word;
    procedure InternalReadFile(const AFileName: string; AStream: TStream; IgnoreCrc: Boolean);
    procedure IndexOfFile(const AFileName: string; out AIndex: Integer; out AFilePosition: Int64);

    procedure UpdateStoreVersion;
    function GetVersion: Integer;
    procedure SetVersion;
  protected
    FFile: TStream;
    FBuffer: TMemoryStream;
    FReadOnly: Boolean;
    procedure ReloadContent;
  public
    constructor Create; virtual;
    destructor Destroy; override;

    procedure AddFile(const AFileName: string; AStream: TStream = nil);
    procedure RemoveFile(const AFileName: string);
    procedure ReadFile(const AFileName: string; AStream: TStream = nil);
    function ContainsFile(const AFileName: string): Boolean;
    procedure Clear;

    property Buffer: TMemoryStream read FBuffer;
    property FileCount: Integer read GetFileCount;
    property Files[AIndex: Integer]: TFileInfo read GetFiles;
    property ReadOnly: Boolean read FReadOnly;
    property Size: Integer read GetSize;
  end;

  TSkyMemoryStore = class(TSkyGenericStore)
  private
    function GetMemoryFile: TMemoryStream;
    property MemoryFile: TMemoryStream read GetMemoryFile;
  public
    constructor Create; override;
    procedure LoadFromFile(const AFileName: string);
    procedure LoadFromStream(AStream: TStream = nil);
    procedure SaveToFile(const AFileName: string);
    procedure SaveToStream(AStream: TStream = nil);
  end;

  TSkyFileStore = class(TSkyGenericStore)
  private
    procedure OpenFile(const AFileName: string; AFileMode: Cardinal);
  public
    procedure OpenFileForRead(const AFileName: string);
    procedure OpenFileForReadWrite(const AFileName: string);
  end;

implementation

uses
  SysUtils, ExceptionClasses, SkyVioBaseCompatibility, Translations, StreamUtils;

{ TSkyFileStore }

procedure TSkyGenericStore.AddFile(const AFileName: string; AStream: TStream);
var
  TheFileCount: integer;
  TheStream: TStream;
begin
  if ReadOnly then
    raise ESkyFileStoreIsInReadOnlyMode.Create(Self, 'AddFile', tlAddFollowing, AFileName);

  if Assigned(AStream) then
    TheStream := AStream
  else
    TheStream := Buffer;

  if ContainsFile(AFileName) then
    RemoveFile(AFileName);

  TheFileCount := Length(FFiles);
  SetLength(FFiles, TheFileCount + 1);

  FFiles[TheFileCount].FileName := ShortString(AFileName);
  FFiles[TheFileCount].FileSize := TheStream.Size;
  FFiles[TheFileCount].CRC := TStreamUtils.StreamCrc(TheStream);

  FFile.Position := FFile.Size;
  FFile.WriteBuffer(FFiles[TheFileCount], SizeOf(TFileInfo));
  TheStream.Position := 0;
  FFile.CopyFrom(TheStream, TheStream.Size);
  TheStream.Position := 0;
end;

procedure TSkyGenericStore.Clear;
begin
  if ReadOnly then
    raise ESkyFileStoreIsInReadOnlyMode.Create(Self, 'RemoveFile', tlClear, '');
  FFile.Size := 0;
  SetLength(FFiles, 0);
end;

function TSkyGenericStore.ContainsFile(const AFileName: string): Boolean;
var
  TheIndex: Integer;
  ThePosition: Int64;
begin
  IndexOfFile(AFileName, TheIndex, ThePosition);
  Result := TheIndex <> -1;
end;

constructor TSkyGenericStore.Create;
begin
  inherited Create;
  FReadOnly := True;
  FFile := nil; // will be created by inherited classes
  FBuffer := TMemoryStream.Create;
end;

destructor TSkyGenericStore.Destroy;
begin
  FreeAndNil(FBuffer);
  FreeAndNil(FFile);
  inherited;
end;

function TSkyGenericStore.FileCrcVersion0(AFile: TStream): Word;
var
  TheFileSize: Integer;
  TheCrcSize: Int64;
  TheTempStream: TMemoryStream;
  TheTempCrc: Cardinal;
begin
  if AFile.Size < 65537 * 2 then
    TheFileSize := AFile.Size
  else
  begin
    TheFileSize := AFile.Size mod 131072;
    if TheFileSize < 2 then
      TheFileSize := 131072 + TheFileSize mod 2;
  end;
  AFile.Position := 0;
  TheTempStream := TMemoryStream.Create;
  try
    while AFile.Position < TheFileSize - 1 do
    begin
      if AFile.Position + 8192 > TheFileSize then
        TheCrcSize := TheFileSize - AFile.Position
      else
        TheCrcSize := 8192;

      TheTempStream.Position := 0;
      TheTempStream.CopyFrom(AFile, TheCrcSize);
      TVioBase.CRCArr2(TheTempStream.Memory, 0, TheCrcSize, False, TheTempCrc);
    end;
    Result := TVioBase.CRCArr2(TheTempStream.Memory, 0, 0, True, TheTempCrc);
  finally
    FreeAndNil(TheTempStream);
  end;
end;

function TSkyGenericStore.GetFileCount: Integer;
begin
  Result := Length(FFiles);
end;

function TSkyGenericStore.GetFiles(AIndex: Integer): TFileInfo;
begin
  Result := FFiles[AIndex];
end;

function TSkyGenericStore.GetSize: Integer;
begin
  if Assigned(FFile) then
    Result := FFile.Size
  else
    Result := 0;
end;

function TSkyGenericStore.GetVersion: Integer;
var
  TheVersion: Integer;
begin
  TheVersion := 0;
  try
    ReadFile('File_Store_Version');
    if Buffer.Size = SizeOf(TheVersion) then
      Buffer.Read(TheVersion, SizeOf(TheVersion));
  except
  end;
  Result := TheVersion;
end;

procedure TSkyGenericStore.ReadFile(const AFileName: string; AStream: TStream);
begin
  InternalReadFile(AFileName, AStream, False);
end;

procedure TSkyGenericStore.IndexOfFile(const AFileName: string; out AIndex: Integer; out AFilePosition: Int64);
var
  I: Integer;
begin
  AIndex := -1;
  AFilePosition := 0;
  for I := 0 to FileCount -1 do
    if SameText(AFileName, string(Files[I].FileName)) then
    begin
      AIndex := I;
      Exit;
    end
    else
      AFilePosition := AFilePosition + SizeOf(TFileInfo) + Files[I].FileSize;
end;

procedure TSkyGenericStore.InternalReadFile(const AFileName: string; AStream: TStream;
  IgnoreCrc: Boolean);
var
  TheFileIndex: Integer;
  TheFileStart: Int64;
  TheStream: TStream;
begin
  IndexOfFile(AFileName, TheFileIndex, TheFileStart);
  if TheFileIndex = -1 then
    raise ESkyFileStoreFileDoesNotExist.Create(Self, 'InternalReadFile', AFileName);
  TheFileStart := TheFileStart + SizeOf(TFileInfo);
  if Assigned(AStream) then
    TheStream := AStream
  else
    TheStream := Buffer;
  TheStream.Size := 0;
  FFile.Position := TheFileStart;
  TheStream.CopyFrom(FFile, FFiles[TheFileIndex].FileSize);

  if (not IgnoreCrc) and
    (TStreamUtils.StreamCrc(TheStream) <> FFiles[TheFileIndex].CRC) and
    (FileCrcVersion0(TheStream) <> FFiles[TheFileIndex].CRC) then
    raise ESkyInvalidCrcFileCannotBeExtracted.Create(Self, 'InternalReadFile', AFileName);
  Buffer.Position := 0;
end;

procedure TSkyGenericStore.ReloadContent;
var
  TheCount, TheNewSize: Integer;
begin
  try
    FFile.Position := 0;
    TheCount := 0;
    while FFile.Position + SizeOf(TFileInfo) < FFile.Size do
    begin
      TheNewSize := ((TheCount div 100) + 1) * 100; // allocate 100 files at a time
      if Length(FFiles) < TheNewSize then
        SetLength(FFiles, TheNewSize);

      FFile.Read(FFiles[TheCount], SizeOf(TFileInfo));
      if (FFile.Position + FFiles[TheCount].FileSize > FFile.Size) or
        (FFiles[TheCount].FileSize < 0) then
        raise ESkyInvalidFileStore.Create(Self, 'ReloadContent', tlInvalidBuffer);

      FFile.Position := FFile.Position + FFiles[TheCount].FileSize;
      Inc(TheCount);
    end;
    if FFile.Position <> FFile.Size then
      raise ESkyInvalidFileStore.Create(Self, 'ReloadContent', tlExtraBytesFoundAtEndOfFile);
    SetLength(FFiles, TheCount);
  except
    SetLength(FFiles, 0);
    raise;
  end;
  if not ReadOnly then
    UpdateStoreVersion;
end;

procedure TSkyGenericStore.RemoveFile(const AFileName: string);
var
  TheFileStart, TheFileSize, TheDataSize: Int64;
  I, TheFileIndex: Integer;
  TheStream: TMemoryStream;
begin
  if ReadOnly then
    raise ESkyFileStoreIsInReadOnlyMode.Create(Self, 'RemoveFile', tlRemoveFollowing, AFileName);
  IndexOfFile(AFileName, TheFileIndex, TheFileStart);
  if TheFileIndex = -1 then
    raise ESkyFileStoreFileDoesNotExist.Create(Self, 'RemoveFile', AFileName);

  TheFileSize := SizeOf(TFileInfo) + Files[TheFileIndex].FileSize;
  FFile.Position := TheFileStart + TheFileSize;
  TheDataSize := FFile.Size - FFile.Position;

  if TheDataSize > 0 then
  begin
    TheStream := TMemoryStream.Create;
    try
      TheStream.CopyFrom(FFile, TheDataSize);
      FFile.Size := TheFileStart;
      TheStream.Position := 0;
      FFile.CopyFrom(TheStream, TheDataSize);
    finally
      TheStream.Free;
    end;
  end
  else
    FFile.Size := TheFileStart;

  for I := TheFileIndex to FileCount - 2 do
    FFiles[I] := FFiles[I + 1];
  SetLength(FFiles, Length(FFiles) - 1);
end;

procedure TSkyGenericStore.SetVersion;
var
  TheVersion: Integer;
begin
  Buffer.Size := 0;
  TheVersion := SkyFileStoreVersion;
  Buffer.Write(TheVersion, SizeOf(TheVersion));
  AddFile('File_Store_Version');
end;

procedure TSkyGenericStore.UpdateStoreVersion;
var
  I, TheOldVersion: Integer;
begin
  TheOldVersion := GetVersion;
  if TheOldVersion = SkyFileStoreVersion then
    Exit;

  {Version 0 was using an invalid CRC calculation routine
    we must update the CRC}
  if TheOldVersion = 0 then
  begin
    for I := 0 to FileCount - 1 do
    begin
      InternalReadFile(string(Files[I].FileName), nil, True);
      if FileCrcVersion0(Buffer) = Files[I].CRC then //only update non-corrupted files
        FFiles[I].CRC := TStreamUtils.StreamCrc(Buffer);
    end;
  end;
  // more to come :).. i hope not
  SetVersion;
end;

{ TSkyFileStore }

procedure TSkyFileStore.OpenFile(const AFileName: string; AFileMode: Cardinal);
begin
  if Assigned(FFile) then
    FreeAndNil(FFile);
  FFile := TFileStream.Create(AFileName, AFileMode);
  ReloadContent;
end;

procedure TSkyFileStore.OpenFileForRead(const AFileName: string);
begin
  if not FileExists(AFileName) then
    raise ESkyFileStoreFileDoesNotExist.Create(Self, 'OpenFileForRead', AFileName);
  OpenFile(AFileName, fmShareDenyNone);
  FReadOnly := True;
end;

procedure TSkyFileStore.OpenFileForReadWrite(const AFileName: string);
begin
  if FileExists(AFileName) then
    OpenFile(AFileName, fmOpenReadWrite or fmShareDenyWrite)
  else
    OpenFile(AFileName, fmCreate or fmShareDenyWrite);
  FReadOnly := False;
end;

{ TSkyMemoryStore }
constructor TSkyMemoryStore.Create;
begin
  inherited;
  FFile := TMemoryStream.Create;
end;

function TSkyMemoryStore.GetMemoryFile: TMemoryStream;
begin
  Result := FFile as TMemoryStream;
end;

procedure TSkyMemoryStore.LoadFromFile(const AFileName: string);
begin
  if not FileExists(AFileName) then
    raise ESkyFileStoreFileDoesNotExist.Create(Self, 'LoadFromFile', AFileName);
  MemoryFile.LoadFromFile(AFileName);
  ReloadContent;
end;

procedure TSkyMemoryStore.LoadFromStream(AStream: TStream = nil);
begin
  MemoryFile.SetSize(0);
  if Assigned(AStream) then
    MemoryFile.LoadFromStream(AStream)
  else
    MemoryFile.LoadFromStream(Buffer);
  ReloadContent;
end;

procedure TSkyMemoryStore.SaveToFile(const AFileName: string);
begin
  MemoryFile.SaveToFile(AFileName);
end;

procedure TSkyMemoryStore.SaveToStream(AStream: TStream = nil);
begin
  if Assigned(AStream) then
    MemoryFile.SaveToStream(AStream)
  else
    MemoryFile.SaveToStream(Buffer);
end;

end.
