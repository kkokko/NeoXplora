unit RequestGetStoriesForImport;

{$mode objfpc}{$H+}

interface

uses
  Entity, StoryObject, EntityList;

type
  { TRequestGetStoriesForImport }

  TRequestGetStoriesForImport = class(TEntity)
  private
    FIgnoredIds: string;
    FSpecifiedIds: string;
    FUserName: string;
    FUserPassword: string;
  published
    property IgnoredIds: string read FIgnoredIds write FIgnoredIds;
    property SpecifiedIds: string read FSpecifiedIds write FSpecifiedIds;
    property UserName: string read FUserName write FUserName;
    property UserPassword: string read FUserPassword write FUserPassword;
  end;

  { TResponseGetStoriesForImport }

  TResponseGetStoriesForImport = class(TEntity)
  private
    FStories: TEntityList;
  published
    property Stories: TEntityList read FStories write FStories; // array of TStoryObject
  end;

implementation

{ TRequestGetStoriesForImport }

initialization
  TRequestGetStoriesForImport.RegisterEntityClass;
  TResponseGetStoriesForImport.RegisterEntityClass;

end.
