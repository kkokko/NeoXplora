unit Proto;

interface

uses
  Entity, EntityFieldNamesToken, TypesConsts;

type
  TProto = class(TEntity)
  private
    FLevel: Integer;
    FName: string;
    FStoryId: TId;
  protected
    function GetName: string; override;
    procedure SetName(const AName: string); override;
  public
    constructor Create; override;
    class var
      Tok_Id: TEntityFieldNamesToken;
      Tok_Level: TEntityFieldNamesToken;
      Tok_Name: TEntityFieldNamesToken;
      Tok_StoryId: TEntityFieldNamesToken;
    class function EntityToken_Id: TEntityFieldNamesToken; override;
    class procedure RegisterFieldMappings;
  published
    property Id;
    property Level: Integer read FLevel write FLevel;
    property Name;
    property StoryId: TId read FStoryId write FStoryId;
  end;

implementation

uses
  EntityMappingManager, EntityMapping;

{ TProto }

constructor TProto.Create;
begin
  inherited;
  FLevel := 1;
end;

class function TProto.EntityToken_Id: TEntityFieldNamesToken;
begin
  Result := Tok_Id;
end;

function TProto.GetName: string;
begin
  Result := FName;
end;

class procedure TProto.RegisterFieldMappings;
var
  TheManager: TEntityMapping;
begin
  TheManager := TEntityMappingManager.GetMapping(Self);
  TheManager.SetValueForField('Id', Tok_Id.PropertyName);
  TheManager.SetValueForField('Level', Tok_Level.PropertyName);
  TheManager.SetValueForField('Name', Tok_Name.PropertyName);
  TheManager.SetValueForField('StoryId', Tok_StoryId.PropertyName);
end;

procedure TProto.SetName(const AName: string);
begin
  FName := AName;
end;

initialization
  TProto.RegisterEntityClassWithMappingToTable('proto');
  TProto.RegisterToken(TProto.Tok_Id, 'prID');
  TProto.RegisterToken(TProto.Tok_Level, 'level');
  TProto.RegisterToken(TProto.Tok_Name, 'name');
  TProto.RegisterToken(TProto.Tok_StoryId, 'pageId');
  TProto.RegisterFieldMappings;

end.
