unit RequestGetImportInfo;

{$mode objfpc}{$H+}

interface

uses
  Classes, Entity, EntityList;

type
  { TRequestGetImportInfo }
  TRequestGetImportInfo = class(TEntity)
  private
    FImportIds: string;
    FSendIdList: Boolean;
    FUserName: string;
    FUserPassword: string;
  published
    property SendIdList: Boolean read FSendIdList write FSendIdList;
    property ImportIds: string read FImportIds write FImportIds;
    property UserName: string read FUserName write FUserName;
    property UserPassword: string read FUserPassword write FUserPassword;
  end;

  { TResponseGetImportInfo }
  TResponseGetImportInfo = class(TEntity)
  private
    FIdList: TEntityList;
    FStoryInProgress: Integer;
    FStoryNew: Integer;
    FStoryTotal: Integer;
  published
    property IdList: TEntityList read FIdList write FIdList;
    property StoryTotal: Integer read FStoryTotal write FStoryTotal;
    property StoryInProgress: Integer read FStoryInProgress write FStoryInProgress;
    property StoryNew: Integer read FStoryNew write FStoryNew;
  end;

implementation

initialization
  TRequestGetImportInfo.RegisterEntityClass;
  TResponseGetImportInfo.RegisterEntityClass;

end.
