unit MessageInfoData;

interface

uses
  Entity, GenericEntity, TypesConsts;

type
  TMessageInfoData = class(TEntity)
  private
    FEntityClassName: string;
    FDateTime: TDateTime;
    FMessageType: TLogMessageType;
    FParams: TGenericEntity;
  public
    constructor Create; override;
  published
    property DateTime: TDateTime read FDateTime write FDateTime;
    property EntityClassName: string read FEntityClassName write FEntityClassName;
    property MessageType: TLogMessageType read FMessageType write FMessageType;
    property Params: TGenericEntity read FParams write FParams;
  end;

implementation

uses
  Classes;

{ TMessageInfoData }

constructor TMessageInfoData.Create;
begin
  inherited;
  FParams := TGenericEntity.Create;
end;

initialization
  TMessageInfoData.RegisterEntityClass;

end.
