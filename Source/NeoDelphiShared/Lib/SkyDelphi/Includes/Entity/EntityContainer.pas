unit EntityContainer;

interface

uses
  EntityService;
type
  TEntityContainer = class(TObject)
  public
    procedure Add(const AEntityService: TEntityService); overload; virtual; abstract;
    procedure Add(const SomeEntityServices: TEntityServices); overload;
    procedure Lock; virtual; abstract;
    procedure CleanBackup; virtual; abstract;
    procedure BeforeCommit; virtual; abstract;
    procedure AfterCommit; virtual; abstract;
    procedure RollBack; virtual; abstract;
    procedure DeleteAll; virtual; abstract;
    procedure LoadFromDB; virtual; abstract;
    procedure SetExistsInDataBase(const Value: Boolean); virtual; abstract;
    procedure CopyFrom(AContainer: TEntityContainer); virtual; abstract;
  end;

implementation

{ TEntityContainer }

procedure TEntityContainer.Add(const SomeEntityServices: TEntityServices);
var
  I: Integer;
begin
  for I := 0 to High(SomeEntityServices) do
    Add(SomeEntityServices[I]);
end;

end.

