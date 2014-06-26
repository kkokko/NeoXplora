unit StreamUtils;

interface

uses
  Classes;

type
  TStreamUtils = class
  public
    class function StreamCrc(AFile: TStream): Word;
  end;

implementation

uses
  SkyVioBaseCompatibility, SysUtils;

{ TStreamUtils }

class function TStreamUtils.StreamCrc(AFile: TStream): Word;
var
  TheTempStream: TMemoryStream;
  TheCrcSize: Int64;
  TheTempCrc: Cardinal;
begin
  AFile.Position := 0;
  TheTempCrc := 0;
  TheTempStream := TMemoryStream.Create;
  try
    while AFile.Position < AFile.Size - 1 do
    begin
      if AFile.Position + 8192 > AFile.Size then
        TheCrcSize := AFile.Size - AFile.Position
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

end.
