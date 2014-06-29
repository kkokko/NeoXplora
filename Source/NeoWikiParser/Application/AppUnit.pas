unit AppUnit;

interface

uses
  AppSettings, MySQLServerConnection, PosTagger, Hypernym, SentenceList;

type
  TApp = class(TObject)
  private
    FHypernym: THypernym;
    FPosTagger: TPosTagger;
    FSentenceList: TSentenceList;
    FSQLConnection: TMySQLServerConnection;
    function GetSQLConnection: TMySQLServerConnection;
    function GetPosTagger: TPosTagger;
    function GetSentenceList: TSentenceList;
  public
    destructor Destroy; override;

    class function GetInstance: TApp;
    class procedure EndInstance;

    property PosTagger: TPosTagger read GetPosTagger;
    property SentenceList: TSentenceList read GetSentenceList;
    property SQLConnection: TMySQLServerConnection read GetSQLConnection;
  end;

var
  App: TApp;

implementation

uses
  Entity, SentenceSplitter, AppSQLServerQuery, SentenceBase;

{ TApp }

class function TApp.GetInstance: TApp;
begin
  if not Assigned(App) then
    App := TApp.Create;
  Result := App;
end;

destructor TApp.Destroy;
begin
  FSentenceList.Free;
  FHypernym.Free;
  FSQLConnection.Free;
  FPosTagger.Free;
  inherited;
end;

function TApp.GetPosTagger: TPosTagger;
begin
  if FPosTagger = nil then
    FPosTagger := TPosTagger.Create;
  Result := FPosTagger;
end;

class procedure TApp.EndInstance;
begin
  App.Free;
  App := nil;
end;

function TApp.GetSentenceList: TSentenceList;
var
  TheHyperNyms: TEntities;
  TheSentenceBase: TSentenceBase;
  TheSentences: TEntities;
  TheSplitter: TSentenceSplitter;
  I: Integer;
begin
  if FSentenceList = nil then
  begin
    FSentenceList := TSentenceList.Create;
    FHypernym := THypernym.Create;
    FSentenceList.Hypernym := FHypernym;
    TheSplitter := nil;
    TheSentences := nil;
    TheHyperNyms := TAppSQLServerQuery.GetHypernyms;
    try
      TheSentences := TAppSQLServerQuery.GetSplitSentences;
      TheSplitter := TSentenceSplitter.Create;
      for I := 0 to High(TheHyperNyms) do
        FHypernym.LoadRepsFromString(TheHyperNyms[I].Name);
      for I := 0 to High(TheSentences) do
      begin
        TheSentenceBase := TheSentences[I] as TSentenceBase;
        TheSplitter.SentenceSplitWords(TheSentenceBase.Name);
        FSentenceList.AddSentence(TheSplitter.WordList, TheSentenceBase.Id, TheSentenceBase.Name,
          TheSentenceBase.Rep, TheSentenceBase.SRep, TheSentenceBase.Pos);
      end;
    finally
      TheSplitter.Free;
      TEntity.FreeEntities(TheSentences);
      TEntity.FreeEntities(TheHyperNyms);
    end;
  end;
  Result := FSentenceList;
end;

function TApp.GetSQLConnection: TMySQLServerConnection;
begin
  if FSQLConnection = nil then
  begin
    FSQLConnection := TMySQLServerConnection.Create;
    with Settings.DBConnectionSettings do
      FSQLConnection.SetConnectionInfo(ServerName, UserName, Password, Database);
  end;
  Result := FSQLConnection;
end;

end.
