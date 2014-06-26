unit ProtoObject;

{$mode objfpc}{$H+}

interface

uses
  Entity, EntityList;

type
  { TProtoObject }

  TProtoObject = class(TEntity)
  private
    FLevel: Integer;
    FName: string;
    FSentences: TEntityList;
    FStoryId: Integer;
  protected
    function GetName: string; override;
    procedure SetName(const AName: string); override;
  public
    constructor Create; override;
    constructor Create(const AName: string; ALevel: Integer); overload;
    destructor Destroy; override;

    // do not assume the Sentences property is populated
    // it is here as a helper only
    property Sentences: TEntityList read FSentences;
  published
    //serialized properties
    property Id;
    property Level: Integer read FLevel write FLevel;
    property Name;
    property StoryId: Integer read FStoryId write FStoryId;
  end;

implementation

{ TProtoObject }

function TProtoObject.GetName: string;
begin
  Result := FName;
end;

procedure TProtoObject.SetName(const AName: string);
begin
  FName := AName;
end;

constructor TProtoObject.Create;
begin
  inherited Create;
  FSentences := TEntityList.Create;
  FSentences.OwnsItems := False;
end;

constructor TProtoObject.Create(const AName: string; ALevel: Integer);
begin
  Create;
  FName := AName;
  FLevel := ALevel;
end;

destructor TProtoObject.Destroy;
begin
  FSentences.Free;
  inherited Destroy;
end;

initialization
  TProtoObject.RegisterEntityClass;

end.

