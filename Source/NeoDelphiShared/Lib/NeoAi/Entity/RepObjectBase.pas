unit RepObjectBase;

interface

uses
  Entity, SkyLists;

type
  TRepObjectBase = class(TEntity)
  private
    FKids: TSkyStringList;
  public
    constructor Create; override;
  published
    property Kids: TSkyStringList read FKids write FKids;
  end;

implementation

{ TRepObjectBase }

{ TRepObjectBase }

constructor TRepObjectBase.Create;
begin
  inherited;
  FKids.Sorted := True;
end;

end.
