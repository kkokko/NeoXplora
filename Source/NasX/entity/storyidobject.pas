unit StoryIdObject;

{$mode objfpc}{$H+}

interface

uses
  Entity;

type
  { TStoryIdObject }
  TStoryIdObject = class(TEntity)
  private
    FMySQLId: Integer;
    FSQLiteId: Integer;
  published
    property SQLiteId: Integer read FSQLiteId write FSQLiteId;
    property MySQLId: Integer read FMySQLId write FMySQLId;
  end;

implementation

{ TStoryIdObject }

initialization
  TStoryIdObject.RegisterEntityClass;

end.
