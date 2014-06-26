unit VersionableEntity;

interface

uses
  EntityWithId, TypesConsts;

type
  TVersionableEntity = class(TEntityWithId)
  private
    FVersion: Integer;
  public
    class var
      EntityToken_Version: TEntityFieldNamesToken;
    constructor Create(AnId: TId; AVersion: Integer); overload;
  published
    property Id;
    property Version: Integer read FVersion write FVersion;
  end;
  TVersionableEntityClass = class of TVersionableEntity;

implementation

{ TVersionableEntity }

constructor TVersionableEntity.Create(AnId: TId; AVersion: Integer);
begin
  Create(AnId);
  FVersion := AVersion;
end;

initialization
  TVersionableEntity.RegisterToken(TVersionableEntity.EntityToken_Version,
    'Version', '[int] NOT NULL');
  TVersionableEntity.RegisterEntityClass;
end.
