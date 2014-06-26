unit SkyPacketCompatibility;

interface

uses
  SkyPacket, Classes;

type
  TSkyPacketD7 = class(TObject)
  private
    FParent: TSkyPacket;
    FDictionary, FOriginalDictionary: ShortString;
    FEncodeDict, FDecodeDict: array[#0..#255] of AnsiChar;
    FUseHeaderEncription: Boolean;

    function ReadString(AStream: TStream): AnsiString;
    function ReadInteger(AStream: TStream): Integer;
    function ReadDouble(AStream: TStream): Double;

    procedure RandomDictionary;
    procedure SetEncodeDict;
    procedure LoadTable(AStream: TStream);
  public
    constructor Create(AParent: TSkyPacket);
    destructor Destroy; override;

    class function FieldTypeFromChar(AFieldType: AnsiChar): TPacketFieldType;
    procedure LoadD7PacketVersion(AVersion: Integer; AStream: TStream);
    property UseHeaderEncription: Boolean read FUseHeaderEncription write FUseHeaderEncription;
  end;

implementation

uses
  SkyVioBaseCompatibility;

type
  TPackHeader = packed record
    Ver: Integer;
    HeadSize: Integer;
    HeadCrc: Word;
    DataCr: Double;
    FileSize: Integer;
    NrComenzi: Integer;
    DictLen: Integer;
  end;
  TPackRecord = packed record
    TipComanda: Integer;
    FieldCount: Integer;
    RowCount: Integer;
    CmdSize: Integer;
    CmdCrc: Word;
  end;

{ TSkyPacketD7Helper }

constructor TSkyPacketD7.Create(AParent: TSkyPacket);
begin
  inherited Create;
  FParent := AParent;
  FUseHeaderEncription := True;
end;

destructor TSkyPacketD7.Destroy;
begin
  FParent := nil;
  inherited;
end;

class function TSkyPacketD7.FieldTypeFromChar(AFieldType: AnsiChar): TPacketFieldType;
begin
  case AFieldType of
    'I': Result := pftInteger;
    'D': Result := pftDateTime;
    'R': Result := pftFloat;
  else
    Result := pftString;
  end;
end;

procedure TSkyPacketD7.LoadD7PacketVersion(AVersion: Integer; AStream: TStream);
var
  TheHeader: TPackHeader;
  TheWord: PWord;
  I: Integer;
begin
  // Create orig dict
  RandomDictionary;

  // Read Packet header
  AStream.ReadBuffer(TheHeader, SizeOf(TheHeader));
  if UseHeaderEncription then
  begin
    // Decode the header
    TheWord := @(TheHeader.DataCr);
    for I := 1 to (SizeOf(TheHeader) - 10) div 2 do
    begin
      TheWord^ := TheWord^ xor TheHeader.HeadCrc;
      Inc(TheWord);
    end;
  end;

  // Read and set dictionary
  SetLength(FDictionary, Length(FOriginalDictionary));
  AStream.ReadBuffer(FDictionary[1], Length(FOriginalDictionary));
  if UseHeaderEncription then
  begin
    // Decode the dictionary
    TheWord := @FDictionary[1];
    for I := 1 to (Length(FDictionary) div 2) do
    begin
      TheWord^ := TheWord^ xor TheHeader.HeadCrc;
      Inc(TheWord);
    end;
  end;
  SetEncodeDict;
  // Read tables
  for I := 0 to TheHeader.NrComenzi - 1 do
    LoadTable(AStream);
end;

function TSkyPacketD7.ReadDouble(AStream: TStream): Double;
var
  TheValue: Double;
begin
  AStream.ReadBuffer(TheValue, SizeOf(TheValue));
  Result := TheValue;
end;

function TSkyPacketD7.ReadInteger(AStream: TStream): Integer;
var
  TheValue: Integer;
begin
  AStream.ReadBuffer(TheValue, SizeOf(TheValue));
  Result := TheValue;
end;

function TSkyPacketD7.ReadString(AStream: TStream): AnsiString;
var
  TheLength: Integer;
  TheString: AnsiString;
  I: Integer;
begin
  TheLength := ReadInteger(AStream);
  SetLength(TheString, TheLength);
  AStream.ReadBuffer(TheString[1], TheLength);
  for I := 1 to TheLength do
    TheString[I] := FDecodeDict[TheString[I]];
  Result := TheString;
end;

procedure TSkyPacketD7.LoadTable(AStream: TStream);
var
  TheHeader: TPackRecord;
  TheTable: TPacketTable;
  TheBuffer: array of Byte;
  TheBufferSize: Integer;
  TheCrc: Word;
  I, J: Integer;
  TheFieldName: AnsiString;
  TheFieldType: AnsiChar;
  TheValue: Variant;
begin
  // Read the header
  AStream.ReadBuffer(TheHeader, SizeOf(TheHeader));

  // Read the table buffer
  TheBufferSize := TheHeader.CmdSize - SizeOf(TheHeader);
  SetLength(TheBuffer, TheBufferSize);
  AStream.ReadBuffer(TheBuffer[0], TheBufferSize);

  // Check the buffer CRC
  TheCrc := TVioBase.CRCArr(@TheBuffer[0], 0, TheBufferSize);
  if TheCrc <> TheHeader.CmdCrc then
  begin
    TheCrc := TVioBase.WrongCRC(@TheBuffer[0], 0, TheBufferSize);
    if TheCrc <> TheHeader.CmdCrc then
      raise EReadError.Create('CRC error');
  end;
  AStream.Position := AStream.Position - TheBufferSize;

  // Read the table name and create the table
  TheTable := FParent.Tables.Add(string(ReadString(AStream)));

  for I := 0 to TheHeader.RowCount - 1 do
    TheTable.AddRow;

  TheTable.Columns.Clear;
  for I := 0 to TheHeader.FieldCount - 1 do
  begin
    // Read collumn name and type
    TheFieldName := ReadString(AStream);
    TheFieldType := TheFieldName[1];
    Delete(TheFieldName, 1, 1);
    TheTable.Columns.Add(TPacketField.Create(string(TheFieldName), FieldTypeFromChar(TheFieldType)));

    // Read field size - not used anymore
    ReadInteger(AStream);

    for J := 0 to TheHeader.RowCount - 1 do
    begin
      case TheFieldType of
        'I': TheValue := ReadInteger(AStream);
        'S': TheValue := string(ReadString(AStream));
        'D', 'R': TheValue := ReadDouble(AStream);
      end;
      TheTable.Rows[J].SetValueForField(string(TheFieldName), TheValue);
    end;
  end;
end;

procedure TSkyPacketD7.RandomDictionary;
var
  C: AnsiChar;
begin
  FDictionary := '';
  for C := 'a' to 'z' do
    FDictionary := FDictionary + C;
  for C := 'A' to 'Z' do
    FDictionary := FDictionary + C;
  for C := '0' to '9' do
    FDictionary := FDictionary + C;
  FDictionary := FDictionary + ' ._-/ÃÎªÞÂãîºþâ,*';
  FOriginalDictionary := FDictionary;
  Randomize;
  SetEncodeDict;
end;

procedure TSkyPacketD7.SetEncodeDict;
var
  C: AnsiChar;
  J: Integer;
begin
  for C := #0 to #255 do
  begin
    FEncodeDict[C] := AnsiChar(C);
    FDecodeDict[C] := AnsiChar(C);
  end;
  for J := 1 to length(FDictionary) do
  begin
    FEncodeDict[FOriginalDictionary[J]] := FDictionary[J];
    FDecodeDict[FDictionary[J]] := FOriginalDictionary[J];
  end;
end;

end.
