unit SkyIdList;

{$mode objfpc}{$H+}

interface

uses
  SkyLists, TypesConsts;

type
{$Region ' TSkyIdList'}
  TSkyIdList = class(TSkyInt64List)
  public
    procedure AddMultiple(const SomeItems: TIds; const SomeLinks: array of TObject); overload;
    procedure CopyFrom(AList: TSkyIdList);
  end;
{$EndRegion}

implementation

uses
  Entity;

{ TSkyIdList }

procedure TSkyIdList.AddMultiple(const SomeItems: TIds; const SomeLinks: array of TObject);
var
  I: Integer;
begin
  CheckAddMultipleLinksLength(Length(SomeItems), Length(SomeLinks));
  if Length(SomeLinks) > 0 then
    for I := 0 to Length(SomeItems) - 1do
      AddObject(SomeItems[I], SomeLinks[I])
  else
    for I := 0 to Length(SomeItems) - 1 do
      Add(SomeItems[I]);
end;

procedure TSkyIdList.CopyFrom(AList: TSkyIdList);
var
  I: Integer;
begin
  Clear;
  if AList = nil then
    Exit;
  for I := 0 to AList.Count - 1 do
  begin
     // if we own objects, do not copy them unless they are entitties
    if OwnsObjects then
      if AList.Objects[I] is TEntity then
        Add(AList.Items[I], (AList.Objects[I] as TEntity).CreateACopy)
      else
        // object value is not copy due to parenting :)
        // we don't want to free someone elses children
        Add(AList.Items[I], nil)
    else
      Add(AList.Items[I], AList.Objects[I]);
  end;
end;

end.
