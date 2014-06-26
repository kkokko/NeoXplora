unit PosTagger;

{$mode objfpc}{$H+}

interface

uses
  sqlite3conn, sqldb, SentenceSplitter;

type

  { TPosTagger }

  TPosTagger = class
  private
    FDbConnection: TSQLite3Connection;
    FDbQuery: TSQLQuery;
    FDbTransaction: TSQLTransaction;
    FSentenceSplitter: TSentenceSplitter;
  public
    constructor Create;
    destructor Destroy; override;

    function DoGetTagsForString(const AString: string): string;
    class function GetTagsForString(const AString: string): string;
  end;

implementation

uses
  Classes, sysutils;

{ TPosTagger }

constructor TPosTagger.Create;
begin
  FDbConnection := TSQLite3Connection.Create(nil);
  FDbTransaction := TSQLTransaction.Create(nil);
  FDbConnection.Transaction := FDbTransaction;
  FDbQuery := TSQLQuery.Create(nil);
  FDbQuery.DataBase := FDbConnection;
  FDbConnection.DatabaseName := 'posLexiconDB';
  FSentenceSplitter := TSentenceSplitter.Create;
end;

destructor TPosTagger.Destroy;
begin
  FDbQuery.Free;
  FDbTransaction.Free;
  FDbConnection.Free;
  FSentenceSplitter.Free;
  inherited Destroy;
end;

class function TPosTagger.GetTagsForString(const AString: string): string;
var
  TheTagger: TPosTagger;
begin
  TheTagger := TPosTagger.Create;
  try
    Result := TheTagger.DoGetTagsForString(AString);
  finally
    TheTagger.Free;
  end;
end;

function TPosTagger.DoGetTagsForString(const AString: string): string;
var
  TheLastWord: string;
  TheListIndex: Integer;
  ThePossiblePOS: string;
  TheTag, TheLastTag: string;
  TheIndex: Integer;
  TheWord: string;
begin
  FSentenceSplitter.SentenceSplitWords(AString);
  Result := '';
  TheLastTag := '';
  TheLastWord := '';
  //connecting to the SQLite database that contains the lexicon
  FDbConnection.Connected := True;
  try
    TheIndex := 0;
    while TheIndex < FSentenceSplitter.WordList.Count do
    begin
      TheWord := FSentenceSplitter.WordList[TheIndex];
      Inc(TheIndex);

      //get the possible parts of speech for that word from the SQLite database
      FDbQuery.SQL.Clear;
      FDbQuery.SQL.Text := 'select word, pos from posLexiconTBL where word=:TheWord;';
      FDbQuery.ParamByName('TheWord').AsString := TheWord;
      FDbQuery.Open;
      FDbQuery.First;

      ThePossiblePOS := '';
      ThePossiblePOS := FDbQuery.FieldByName('pos').AsString;
      FDbQuery.Close;

      // get from dict if set
      if (ThePossiblePOS = '') then
        TheTag := 'NN'
      else
      begin
        TheListIndex := Pos(' ', ThePossiblePOS);
        if TheListIndex = 0 then
          TheTag := ThePossiblePOS
        else
          TheTag := Copy(ThePossiblePOS, 1, TheListIndex - 1);
      end;

      // Converts verbs after 'the' to nouns
      if (TheLastTag = 'DT') and ((Pos('VBD', TheTag) > 0) or (Pos('VBP', TheTag) > 0) or (Pos('VB', TheTag) > 0)) then
        TheTag  := 'NN';

      // Convert noun to number if . appears
      if ((TheTag[1] = 'N') and (Pos('.', TheWord)>0)) then
        TheTag := 'CD';

      // Convert noun to past particle if ends with 'ed'
      if ((TheTag[1] = 'N') and (Length(TheWord) > 2) and (LowerCase(Copy(TheWord, Length(TheWord) - 1, 2)) = 'ed')) then
        TheTag := 'VBN';

      // Anything that ends 'ly' is an adverb
      if ((Length(TheWord) > 2) and (LowerCase(Copy(TheWord, Length(TheWord) - 1, 2)) = 'ly')) then
        TheTag := 'RB';

      // Common noun to adjective if it ends with al
      if ((TheTag = 'NN') and (Length(TheWord) > 2) and (LowerCase(Copy(TheWord, Length(TheWord) - 1, 2)) = 'al')) then
        TheTag := 'JJ';

      // Noun to verb if the word before is 'would'
      if ((TheTag = 'NN') and (TheLastWord = 'would')) then
        TheTag := 'VB';

      // Convert noun to plural if it ends with an s
      if ((TheTag = 'NN') and (TheWord[Length(TheWord)] = 's')) and (
        (Length(TheWord) < 2) or (TheWord[Length(TheWord) - 1] <> 's'))then
        TheTag := 'NNS';

      // Convert common noun to gerund
      if ((TheTag='NN') or (TheTag='NNS')) and (Length(TheWord) > 3) and (LowerCase(Copy(TheWord, Length(TheWord) - 2, 3)) = 'ing') then
        TheTag := 'VBG';

      // If we get noun noun, and the second can be a verb, convert to verb
      if (TheLastTag <> '') and ((TheTag[1]='N') and (TheLastTag[1] = 'N')) then
      begin
        if (Pos('VBN', ThePossiblePOS) > 0) then
          TheTag:='VBN'
        else if (Pos('VBZ', ThePossiblePOS) > 0) then
          TheTag:='VBZ';
      end;
      TheLastTag := TheTag;
      TheLastWord := TheWord;

      Result := Result + TheTag + ' ';
    end;
  finally
    FDbConnection.Connected := True;
  end;
  Result := Trim(Result);
end;

end.

