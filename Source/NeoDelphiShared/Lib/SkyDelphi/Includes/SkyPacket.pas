unit
  SkyPacket;

interface

uses
  Classes, GenericEntity, SkyLists, Entity, EntityList;

const
  SkyPacketVersion: Integer = $20120503; //version 2010.04.30

type
  TPacketTable = class;
  TPacketTables = class;
  TPacketField = class;
  TPacketFieldType = (pftBoolean, pftInteger, pftFloat, pftString, pftDate, pftTime, pftDateTime);

  TSkyPacket = class(TEntity)
  private
    FTables: TPacketTables;
    FVersion: Integer;
  public
    constructor Create; override;
    class function LoadFromFile(const AFileName: string): TSkyPacket;
    class function LoadFromStream(AStream: TStream): TSkyPacket;
    procedure SaveToFile(const AFileName: string);
    procedure SaveToStream(AStream: TStream);
  published
    property Tables: TPacketTables read FTables write FTables;
    property Version: Integer read FVersion write FVersion;
  end;
  
  TPacketTables = class(TEntity)
  private
    FItems: TEntityList;
    function GetPacketTable(AIndex: Integer): TPacketTable;
  public
    function Add(const ATableName: string): TPacketTable;
    procedure Clear;
    procedure Delete(const ATableName: string);
    function ItemByName(const AName: string): TPacketTable;
    property Item[AIndex: Integer]: TPacketTable read GetPacketTable; default;
  published
    property Items: TEntityList read FItems write FItems;
  end;

  TPacketTable = class(TEntity)
  private
    FColumns: TEntityList;
    FName: string;
    FPosition: Integer;
    FRows: TEntityList;
    function GetField(AIndex: Integer): TPacketField;
  protected
    function GetName: string; override;
    procedure SetName(const AName: string); override;
  public
    function AddRow: TGenericEntity;
    function FindProperty(const APropertyName: string; const AValue: Variant): Integer;
    function Selected: TGenericEntity;

    property Fields[AIndex: Integer]: TPacketField read GetField;
    property Position: Integer read FPosition write FPosition;
  published
    property Columns: TEntityList read FColumns write FColumns;
    property Name;
    property Rows: TEntityList read FRows write FRows;
  end;

  TPacketField = class(TEntity)
  private
    FName: string;
    FFieldType: TPacketFieldType;
  protected
    function GetName: string; override;
    procedure SetName(const AName: string); override;
  public
    constructor Create(const AFieldName: string; AFieldType: TPacketFieldType); reintroduce;
  published
    property Name;
    property FieldType: TPacketFieldType read FFieldType write FFieldType;
  end;

implementation

uses
  SysUtils, ExceptionClasses, Variants,
  EntityXmlReader, EntityXmlWriter;

{ TPacket }

constructor TSkyPacket.Create;
begin
  inherited;
  FTables := TPacketTables.Create;
end;

class function TSkyPacket.LoadFromFile(const AFileName: string): TSkyPacket;
var
  TheStream: TFileStream;
begin
  TheStream := TFileStream.Create(AFileName, fmOpenRead or fmShareDenyNone);
  try
    TheStream.Position := 0;
    Result := LoadFromStream(TheStream);
  finally
    FreeAndNil(TheStream);
  end;
end;

class function TSkyPacket.LoadFromStream(AStream: TStream): TSkyPacket;
begin
  Result := TEntityXmlReader.ReadEntity(AStream, TSkyPacket) as TSkyPacket;
end;

procedure TSkyPacket.SaveToFile(const AFileName: string);
var
  TheFileStream: TFileStream;
begin
  if not FileExists(AFileName) then
    TheFileStream := TFileStream.Create(AFileName, fmCreate)
  else
    TheFileStream := TFileStream.Create(AFileName, fmOpenWrite or fmShareDenyNone);
  try
    SaveToStream(TheFileStream);
  finally
    FreeAndNil(TheFileStream);
  end;
end;

procedure TSkyPacket.SaveToStream(AStream: TStream);
begin
  TEntityXmlWriter.WriteEntity(AStream, Self);
end;

{ TPacketTables }

function TPacketTables.Add(const ATableName: string): TPacketTable;
begin
  Result := TPacketTable.Create;
  Result.Name := ATableName;
  FItems.Add(Result);
end;

procedure TPacketTables.Clear;
begin
  FItems.Clear;
end;

procedure TPacketTables.Delete(const ATableName: string);
var
  TheEntity: TEntity;
begin
  TheEntity := FItems.FindFirstWithProperty(TPacketTable.EntityToken_Name, ATableName);
  FItems.Delete(TheEntity);
end;

function TPacketTables.GetPacketTable(AIndex: Integer): TPacketTable;
begin
  Result := Items[AIndex] as TPacketTable;
end;

function TPacketTables.ItemByName(const AName: string): TPacketTable;
begin
  Result := Items.FindFirstWithProperty(TPacketTable.EntityToken_Name, AName) as TPacketTable;
  if Result = nil then
    raise ESkyFileStoreTableDoesNotExist.Create(Self, 'ItemByName', AName);
end;

{ TPacketTable }

function TPacketTable.AddRow: TGenericEntity;
begin
  Result := TGenericEntity.Create;
  Rows.Add(Result);
end;

function TPacketTable.FindProperty(const APropertyName: string;
  const AValue: Variant): Integer;
var
  I: Integer;
begin
  Result := -1;
  for I := 0 to Rows.Count - 1 do
    if VarSameValue(Rows[I].GetValueForField(APropertyName), AValue) then
    begin
      Result := I;
      Exit;
    end;
end;

function TPacketTable.GetField(AIndex: Integer): TPacketField;
begin
  Result := Columns[AIndex] as TPacketField;
end;

function TPacketTable.GetName: string;
begin
  Result := FName;
end;

function TPacketTable.Selected: TGenericEntity;
begin
  if (FPosition < Rows.Count) and (FPosition > -1)then
    Result := Rows[FPosition] as TGenericEntity
  else
    Result := nil;
end;

procedure TPacketTable.SetName(const AName: string);
begin
  FName := AName;
end;

{ TPacketField }

constructor TPacketField.Create(const AFieldName: string;
  AFieldType: TPacketFieldType);
begin
  inherited Create;
  Name := AFieldName;
  FieldType := AFieldType;
end;

function TPacketField.GetName: string;
begin
  Result := FName;
end;

procedure TPacketField.SetName(const AName: string);
begin
  FName := AName;
end;

initialization
  TSkyPacket.RegisterEntityClass;
  TPacketTables.RegisterEntityClass;
  TPacketTable.RegisterEntityClass;
  TPacketField.RegisterEntityClass;

end.
