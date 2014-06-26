unit Upgrade;

{$mode objfpc}{$H+}

interface

uses
  sqldb;

type
  TUpgrade = class
  private
    const
      ConstDatabaseVersion = 1;
  private
    class procedure ApplyVersionUpdates1;
  public
    class function CheckDatabaseIsCorrectVersion: Boolean;
    class procedure UpgradeDatabase;
  end;

implementation

uses
  CoreUnit, SysUtils;

{ TUpgrade }

class procedure TUpgrade.ApplyVersionUpdates1;
var
  TheQuery1, TheQuery2, TheQuery3: TSQLQuery;
  TheSentenceId, TheStoryId: Integer;
  TheSentence: string;
  TheProto1Id, TheProto2Id: Integer;
begin
  Core.ExecuteSQL('create table zapp(`key` varchar(30) primary key not null, `value` varchar(30));');
  Core.ExecuteSQL('insert into zapp(`key`, `value`) values(''version'', ''1'');');
  Core.ExecuteSQL('CREATE TABLE `protoTBL`(`prID` INTEGER PRIMARY KEY,`name` VARCHAR(400) NOT NULL, ' +
    '`level` INT NOT NULL, `storyID` INT(11) NOT NULL);');
  Core.ExecuteSQL('alter table sentenceTBL add proto1Id int;');
  Core.ExecuteSQL('alter table sentenceTBL add proto2Id int;');

  TheQuery3 := nil;
  TheQuery2 := nil;
  TheQuery1 := TSQLQuery.Create(nil);
  try
    TheQuery2 := TSQLQuery.Create(nil);
    TheQuery3 := TSQLQuery.Create(nil);
    TheQuery1.DataBase := Core.Database;
    TheQuery2.DataBase := Core.Database;
    TheQuery3.DataBase := Core.Database;

    TheQuery1.SQL.Text := 'select storyId, sentenceId, ifnull(parentSentence, sentence) ' +
      'as sentence from sentenceTBL order by sentenceId';
    TheQuery2.SQL.Text := 'insert into `protoTBL`(name, level, storyId) values(:AName, :ALevel, :AStoryId);';
    TheQuery3.SQL.Text := 'update sentenceTBL set proto1Id = :AProto1, proto2Id = :AProto2 where sentenceId = :ASentenceId;';

    TheQuery1.Open;
    while not TheQuery1.EOF do
    begin
      TheSentenceId := TheQuery1.FieldByName('sentenceId').AsInteger;
      TheStoryId := TheQuery1.FieldByName('storyId').AsInteger;
      TheSentence:= TheQuery1.FieldByName('sentence').AsString;

      TheQuery2.ParamByName('AName').AsString := TheSentence;
      TheQuery2.ParamByName('ALevel').AsInteger := 1;
      TheQuery2.ParamByName('AStoryId').AsInteger := TheStoryId;
      TheQuery2.ExecSQL;
      TheProto1Id := Core.GetLastInsertedId;

      TheQuery2.ParamByName('ALevel').AsInteger := 2;
      TheQuery2.ExecSQL;
      TheProto2Id := Core.GetLastInsertedId;

      TheQuery3.ParamByName('AProto1').AsInteger := TheProto1Id;
      TheQuery3.ParamByName('AProto2').AsInteger := TheProto2Id;
      TheQuery3.ParamByName('ASentenceId').AsInteger := TheSentenceId;
      TheQuery3.ExecSQL;
      TheQuery1.Next;
    end;
    TheQuery1.Close;
  finally
    TheQuery1.Free;
    TheQuery2.Free;
    TheQuery3.Free;
  end;
  Core.Database.Transaction.CommitRetaining;
end;

class function TUpgrade.CheckDatabaseIsCorrectVersion: Boolean;
begin
  Result := Core.DatabaseVersion = ConstDatabaseVersion;
end;

class procedure TUpgrade.UpgradeDatabase;
begin
  if Core.DatabaseVersion > ConstDatabaseVersion then
    raise Exception.Create('Database is newer than the application. Aborting.');
  if Core.DatabaseVersion < 1 then
    ApplyVersionUpdates1;
//  if Core.DatabaseVersion < 2 then
//    ApplyVersionUpdates2
// etc..
end;

end.

