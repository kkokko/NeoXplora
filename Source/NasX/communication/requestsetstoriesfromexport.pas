unit RequestSetStoriesFromExport;

{$mode objfpc}{$H+}

interface

uses
  Entity, EntityList;

type
  { TRequestSetStoriesFromExport }

  TRequestSetStoriesFromExport = class(TEntity)
  private
    FStories: TEntityList;
    FUserName: string;
    FUserPassword: string;
  published
    property Stories: TEntityList read FStories write FStories; // array of TStoryObject
    property UserName: string read FUserName write FUserName;
    property UserPassword: string read FUserPassword write FUserPassword;
  end;

  { TResponseSetStoriesFromExport }

  TResponseSetStoriesFromExport = class(TEntity)
  private
    FStoryIds: TEntityList;
      FUpdatedCount: Integer;
    FAddedCount: Integer;
  published
    property StoryIds: TEntityList read FStoryIds write FStoryIds;  // array of TStoryIdObject
    property AddedCount: Integer read FAddedCount write FAddedCount;
    property UpdatedCount: Integer read FUpdatedCount write FUpdatedCount;
  end;

implementation

initialization
  TRequestSetStoriesFromExport.RegisterEntityClass;
  TResponseSetStoriesFromExport.RegisterEntityClass;

end.
