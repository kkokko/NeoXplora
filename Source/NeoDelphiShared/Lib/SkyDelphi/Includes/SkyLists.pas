unit SkyLists;

interface

uses
  Classes, TypesConsts, SysUtils, StringArray;

const
  MaxDoubleListSize = MaxInt div 20;
  MaxDoubleDoubleListSize = MaxInt div 20;
  MaxInt64ListSize = MaxInt div 20;
  MaxVariantListSize = MaxInt div 50;
  MaxListSize = MaxInt div 32;

type
{$Region ' Data structures'}
  PListItem = ^TListItem;
  TListItem = record
    FData: Pointer;
    FLink: TObject;
  end;
  PItemList = ^TItemList;
  TItemList = array[0 .. MaxListSize] of TListItem;

  TSkyListSortCompare = function(Index1, Index2: Integer): Integer of object;

  PDoubleListItem = ^TDoubleListItem;
  TDoubleListItem = record
    FData: Double;
    FLink: TObject;
  end;
  PDoubleItemList = ^TDoubleItemList;
  TDoubleItemList = array[0 .. MaxDoubleListSize] of TDoubleListItem;

  PInt64ListItem = ^TInt64ListItem;
  TInt64ListItem = record
    FData: Int64;
    FLink: TObject;
  end;
  PInt64ItemList = ^TInt64ItemList;
  TInt64ItemList = array[0 .. MaxInt64ListSize] of TInt64ListItem;

  TStringListItem = record
    FString: TSkyString;
    FObject: TObject;
  end;
  TStringListItemArray = array[0..MaxListSize div 2] of TStringListItem;
  PStringListItem = ^TStringListItemArray;

  PStringStringListItem = ^TStringStringListItem;
  TStringStringListItem = record
    FData: TSkyString;
    FLink: TSkyString;
  end;
  PStringStringItemList = ^TStringStringItemList;
  TStringStringItemList = array[0 .. MaxListSize] of TStringStringListItem;

  PStringVariantListItem = ^TStringVariantListItem;
  TStringVariantListItem = record
    FData: TSkyString;
    FLink: Variant;
  end;
  PStringVariantItemList = ^TStringVariantItemList;
  TStringVariantItemList = array[0 .. MaxVariantListSize] of TStringVariantListItem;

  TSkyCompareStringsMethod = function(const S1, S2: string): Integer;
{$EndRegion}
{$Region ' TSkyList'}
  TSkyList = class(TObject)
  private
    FList: PItemList;
    FCount: Integer;
    FCapacity: Integer;
    FOnChange: TNotifyEvent;
    FOnChanging: TNotifyEvent;
    FOwnsObject: Boolean;
    FSorted: Boolean;
    procedure Grow;
    procedure SetSorted(const Value: Boolean);
    procedure QuickSort(L, R: Integer; SCompare: TSkyListSortCompare);
    function LinearIndexOf(APointer: Pointer): Integer;
  protected
    function CompareItemsFromIndex(Index1, Index2: Integer): Integer; virtual;
    procedure Changed; virtual;
    procedure Changing; virtual;
    procedure ExchangeItems(Index1, Index2: Integer); virtual;
    function GetCapacity: Integer; virtual;
    function GetCount: Integer; virtual;
    function GetLink(Index: Integer): TObject; virtual;
    procedure PutLink(Index: Integer; ALink: TObject); virtual;
    procedure SetCapacity(NewCapacity: Integer); virtual;
    procedure SetUpdateState(Updating: Boolean); virtual;
    function ComparePointers(APointer1, APointer2: Pointer): Integer; virtual;
    procedure Notify(Ptr: Pointer; Action: TListNotification); virtual;
    function GetItem(Index: Integer): Pointer; virtual;
    procedure PutItem(AIndex:Integer; APointer: Pointer); virtual;
    procedure CheckIndex(AIndex: Integer);
    procedure CheckCapacity(ACapacity, AMaxCapacity: Integer);
    procedure CheckAddMultipleLinksLength(AItemsLength, ALinksLength: Integer);
  public
    constructor Create(AnOwnsObjects: Boolean = False); virtual;
    destructor Destroy; override;
    function Add(APointer: Pointer; ALink: TObject = nil): Integer; virtual;
    function AddObject(APointer: Pointer; ALink: TObject): Integer; virtual;
    procedure InsertItem(Index: Integer; APointer: Pointer; ALink: TObject); virtual;
    procedure AddMultiple(const SomeItems: array of Pointer; const SomeLinks: array of TObject); virtual;
    function GetAllItems: TPointers; virtual;
    procedure Clear; virtual;
    procedure DeleteFromIndex(Index: Integer); virtual;
    procedure Delete(APointer: Pointer); virtual;
    procedure Exchange(Index1, Index2: Integer); virtual;
    function Find(APointer: Pointer; out Index: Integer): Boolean; virtual;
    function IndexOf(APointer: Pointer): Integer; virtual;
    function IndexOfObject(ALink: TObject): Integer; virtual;
    function GetAllObjects: TObjects;
    function GetObjectsLimit(var AStartIndex: Integer; ACount: Integer): TObjects;
    procedure Sort; overload;
    property Count: Integer read FCount;
    property Items[Index: Integer]: Pointer read GetItem write PutItem; default;
    property Objects[Index: Integer]: TObject read GetLink write PutLink;
    property OnChange: TNotifyEvent read FOnChange write FOnChange;
    property OnChanging: TNotifyEvent read FOnChanging write FOnChanging;
    property OwnsObjects: Boolean read FOwnsObject write FOwnsObject;
    property Sorted: Boolean read FSorted write SetSorted;
  end;
{$EndRegion}
{$Region ' TSkyStringList'}
  TSkyStringList = class(TSkyList)
  private
    FList: PStringListItem;
    FLineBreak: TSkyString;
    function LinearIndexOf(const AString: TSkyString): Integer;
    function GetObjectOfValue(const AValue: TSkyString): TObject;
    function GetObjectOfValueDefault(const AValue: TSkyString;
      const ADefault: TObject): TObject;
    procedure SetObjectOfValue(const AValue: TSkyString; const Value: TObject);
  protected
    function CompareItemsFromIndex(Index1, Index2: Integer): Integer; override;
    function CompareStrings(const AString1, AString2: TSkyString): Integer; inline;
    procedure ExchangeItems(Index1, Index2: Integer); override;
    function GetItem(Index: Integer): TSkyString; reintroduce; virtual;
    function GetLink(Index: Integer): TObject; override;
    procedure InsertItem(Index: Integer; const AString: TSkyString; ALink: TObject); reintroduce; virtual;
    procedure PutItem(AIndex:Integer; const AString: TSkyString); reintroduce; virtual;
    procedure PutLink(Index: Integer; ALink: TObject); override;
    procedure SetCapacity(NewCapacity: Integer); override;
    procedure SetTextStr(const Value: TSkyString); virtual;
  public
    constructor Create(AnOwnsObjects: Boolean = False); override;
    class function ExplodeString(const AString, ALineBreak: TSkyString): TStringArray;
    class function ImplodeString(const TheStrings: TStringArray; const ALineBreak: TSkyString): TSkyString;
    class function LoadTextFromStream(AStream: TStream): TSkyString;

    function Add(const AString: TSkyString; ALink: TObject = nil): Integer; reintroduce; virtual;
    function AddObject(const AString: TSkyString; ALink: TObject): Integer; reintroduce; virtual;
    procedure AddMultiple(const SomeItems: array of TSkyString; const SomeLinks: array of TObject); reintroduce; overload;
    procedure AddMultiple(const SomeItems: TStringArray; const SomeLinks: array of TObject); reintroduce; overload;
    function CreateACopy: TSkyStringList;
    procedure CopyFrom(AList: TSkyStringList);

    function GetAllItems: TStringArray; reintroduce;
    function GetTextStr: TSkyString; virtual;
    procedure Clear; override;
    procedure DeleteFromIndex(Index: Integer); override;
    procedure Delete(const AString: TSkyString); reintroduce; virtual;
    function Find(const AString: TSkyString; out Index: Integer): Boolean; reintroduce; virtual;
    function IndexOf(const AString: TSkyString): Integer; reintroduce; virtual;
    procedure LoadFromFile(const FileName: TSkyString);
    procedure LoadFromStream(Stream: TStream);
    procedure SaveToFile(const FileName: TSkyString);
    procedure SaveToStream(Stream: TStream);
    property Items[Index: Integer]: TSkyString read GetItem write PutItem; default;
    property Text: TSkyString read GetTextStr write SetTextStr;
    property LineBreak: TSkyString read FLineBreak write FLineBreak;
    property ObjectOfValue[const AValue: TSkyString]: TObject read GetObjectOfValue write SetObjectOfValue;
    property ObjectOfValueDefault[const AValue: TSkyString; const ADefault: TObject]: TObject read GetObjectOfValueDefault;
  end;
  TSkyStringListClass = class of TSkyStringList;
{$EndRegion}
{$Region ' TSkyObjectList'}
  TSkyObjectList = class(TSkyList)
  private
    FOwnsItems: Boolean;
    function GetObjectOfValue(const AValue: TObject): TObject;
    function GetObjectOfValueDefault(const AValue, ADefault: TObject): TObject;
    procedure SetObjectOfValue(const AValue, Value: TObject);
  protected
    function GetItem(Index: Integer): TObject; reintroduce; virtual;
    procedure PutItem(AIndex:Integer; AObject: TObject); reintroduce; virtual;
  public
    constructor Create(AnOwnsObjects: Boolean = False; AnOwnsItems: Boolean = False); reintroduce;
    function Add(AObject: TObject; ALink: TObject = nil): Integer; reintroduce; virtual;
    function AddObject(AObject: TObject; ALink: TObject): Integer; reintroduce; virtual;
    procedure Clear; override;
    procedure Delete(AObject: TObject); reintroduce; virtual;
    procedure DeleteFromIndex(Index: Integer); override;
    function Find(AObject: TObject; out Index: Integer): Boolean; reintroduce; virtual;
    function IndexOf(AObject: TObject): Integer; reintroduce; virtual;
    procedure AddMultiple(const SomeItems: TObjects; const SomeLinks: TObjects); reintroduce; overload;
    function GetAllItems: TObjects; reintroduce;
    procedure FreeAllObjects;
    property Items[Index: Integer]: TObject read GetItem write PutItem; default;
    property ObjectOfValue[const AValue: TObject]: TObject read GetObjectOfValue write SetObjectOfValue;
    property ObjectOfValueDefault[const AValue, ADefault: TObject]: TObject read GetObjectOfValueDefault;
    property OwnsItems: Boolean read FOwnsItems write FOwnsItems;
  end;
{$EndRegion}
{$Region ' TSkyClassTypeList'}
  TSkyClassTypeList = class(TSkyStringList)
  protected
    function GetItem(Index: Integer): TClass; reintroduce; virtual;
    procedure PutItem(AIndex:Integer; AClass: TClass); reintroduce; virtual;
  public
    function Add(AClassType: TClass; const AClassName: TSkyString): Integer; reintroduce; virtual;
    function AddObject(AClassType: TClass; const AClassName: TSkyString): Integer; reintroduce; virtual;
    procedure AddMultiple(const SomeItems: array of TClass; const SomeLinks: array of TSkyString); reintroduce;
    function GetAllItems: TClasses; reintroduce;
    procedure Delete(AClassType: TClass); reintroduce; virtual;
    function Find(AClassType: TClass; out Index: Integer): Boolean; reintroduce; virtual;
    function FindByName(const AClassName: TSkyString): TClass;
    function IndexOf(AClassType: TClass): Integer; reintroduce; virtual;
    property Items[Index: Integer]: TClass read GetItem write PutItem; default;
  end;
{$EndRegion}
{$Region ' TSkyIntegerList'}
  TSkyIntegerList = class(TSkyList)
  private
    function GetObjectOfValue(const AValue: Integer): TObject;
    function GetObjectOfValueDefault(const AValue: Integer;
      const ADefault: TObject): TObject;
    procedure SetObjectOfValue(const AValue: Integer; const Value: TObject);
  protected
    function GetItem(Index: Integer): Integer; reintroduce; virtual;
    procedure PutItem(AIndex:Integer; AInteger: Integer); reintroduce; virtual;
  public
    function Add(AInteger: Integer; ALink: TObject = nil): Integer; reintroduce; virtual;
    function AddObject(AInteger: Integer; ALink: TObject): Integer; reintroduce; virtual;
    procedure AddMultiple(const SomeItems: array of Integer; const SomeLinks: array of TObject); reintroduce;
    function GetAllItems: TIntegers; reintroduce;
    procedure Delete(AInteger: Integer); reintroduce; virtual;
    function Find(AInteger: Integer; out Index: Integer): Boolean; reintroduce; virtual;
    function IndexOf(AInteger: Integer): Integer; reintroduce; virtual;
    property Items[Index: Integer]: Integer read GetItem write PutItem; default;
    property ObjectOfValue[const AValue: Integer]: TObject read GetObjectOfValue write SetObjectOfValue;
    property ObjectOfValueDefault[const AValue: Integer; const ADefault: TObject]: TObject read GetObjectOfValueDefault;
  end;
{$EndRegion}
{$Region ' TSkyDoubleList'}
  TSkyDoubleList = class(TSkyList)
  private
    FList: PDoubleItemList;
    function LinearIndexOf(ADouble: Double): Integer;
  protected
    function CompareItemsFromIndex(Index1, Index2: Integer): Integer; override;
    function CompareDoubles(const ADouble1, ADouble2: Double): Integer;
    procedure ExchangeItems(Index1, Index2: Integer); override;
    function GetItem(Index: Integer): Double; reintroduce; virtual;
    function GetLink(Index: Integer): TObject; override;
    procedure InsertItem(Index: Integer; const ADouble: Double; ALink: TObject); reintroduce; virtual;
    procedure PutItem(AIndex:Integer; ADouble: Double); reintroduce; virtual;
    procedure PutLink(Index: Integer; ALink: TObject); override;
    procedure SetCapacity(NewCapacity: Integer); override;
  public
    function Add(const ADouble: Double; ALink: TObject = nil): Integer; reintroduce; virtual;
    function AddObject(const ADouble: Double; ALink: TObject): Integer; reintroduce; virtual;
    procedure AddMultiple(const SomeItems: array of Double; const SomeLinks: array of TObject); reintroduce;
    function GetAllItems: TDoubles; reintroduce;
    procedure DeleteFromIndex(Index: Integer); override;
    procedure Delete(const ADouble: Double); reintroduce; virtual;
    function Find(const ADouble: Double; out Index: Integer): Boolean; reintroduce; virtual;
    function IndexOf(const ADouble: Double): Integer; reintroduce; virtual;
    property Items[Index: Integer]: Double read GetItem write PutItem; default;
  end;
{$EndRegion}
{$Region ' TSkyInt64List'}
  TSkyInt64List = class(TSkyList)
  private
    FList: PInt64ItemList;
    function LinearIndexOf(AInt64: Int64): Integer;
    function GetObjectOfValue(const AValue: Int64): TObject;
    function GetObjectOfValueDefault(const AValue: Int64;
      const ADefault: TObject): TObject;
    procedure SetObjectOfValue(const AValue: Int64; const Value: TObject);
  protected
    function CompareItemsFromIndex(Index1, Index2: Integer): Integer; override;
    function CompareInt64s(const AInt641, AInt642: Int64): Integer;
    procedure ExchangeItems(Index1, Index2: Integer); override;
    function GetItem(Index: Integer): Int64; reintroduce; virtual;
    function GetLink(Index: Integer): TObject; override;
    procedure InsertItem(Index: Integer; const AInt64: Int64; ALink: TObject); reintroduce; virtual;
    procedure PutItem(AIndex:Integer; AInt64: Int64); reintroduce; virtual;
    procedure PutLink(Index: Integer; ALink: TObject); override;
    procedure SetCapacity(NewCapacity: Integer); override;
  public
    function Add(const AInt64: Int64; ALink: TObject = nil): Integer; reintroduce; virtual;
    function AddObject(const AInt64: Int64; ALink: TObject): Integer; reintroduce; virtual;
    procedure AddMultiple(const SomeItems: array of Int64; const SomeLinks: array of TObject); reintroduce;
    function GetAllItems: TInt64s; reintroduce;
    function GetItemsLimit(var AStartIndex: Integer; ACount: Integer): TIds; reintroduce;
    procedure DeleteFromIndex(Index: Integer); override;
    procedure Delete(const AInt64: Int64); reintroduce; virtual;
    function Find(const AInt64: Int64; out Index: Integer): Boolean; reintroduce; virtual;
    function IndexOf(const AInt64: Int64): Integer; reintroduce; virtual;
    property Items[Index: Integer]: Int64 read GetItem write PutItem; default;
    property ObjectOfValue[const AValue: Int64]: TObject read GetObjectOfValue write SetObjectOfValue;
    property ObjectOfValueDefault[const AValue: Int64; const ADefault: TObject]: TObject read GetObjectOfValueDefault;
  end;
{$EndRegion}
{$Region ' TSkyStringStringList'}
  TSkyStringStringList = class(TSkyList)
  private
    FLineBreak: TSkyString;
    FList: PStringStringItemList;
    FCompareStringsMethod: TSkyCompareStringsMethod;
    function LinearIndexOf(const AString: TSkyString): Integer;
    function GetCaseSensitive: Boolean;
    procedure SetCaseSensitive(const Value: Boolean);
  protected
    function CompareItemsFromIndex(Index1, Index2: Integer): Integer; override;
    procedure ExchangeItems(Index1, Index2: Integer); override;
    function GetItem(Index: Integer): TSkyString; reintroduce; virtual;
    function GetLink(Index: Integer): TSkyString; reintroduce; virtual;
    procedure InsertItem(Index: Integer; const AString, ALink: TSkyString); reintroduce; virtual;
    procedure PutItem(AIndex:Integer; const AString: TSkyString); reintroduce; virtual;
    procedure PutLink(Index: Integer; const ALink: TSkyString); reintroduce; virtual;
    procedure SetCapacity(NewCapacity: Integer); override;
    procedure SetTextStr(const Value: TSkyString);
    function GetObjectOfValue(const AValue: TSkyString): TSkyString;
    procedure SetObjectOfValue(const AValue, Value: TSkyString);
    function GetObjectOfValueDefault(const AValue, ADefault: TSkyString): TSkyString;
  public
    constructor Create; reintroduce;
    class function ExplodeString(const AString, ALineBreak: TSkyString): TStringArray;
    class function ImplodeString(const TheStrings: TStringArray; const ALineBreak: TSkyString): TSkyString;
    class function LoadTextFromStream(AStream: TStream): TSkyString;

    function Add(const AString: TSkyString; const ALink: TSkyString = ''): Integer; reintroduce; virtual;
    function AddObject(const AString: TSkyString; const ALink: TSkyString): Integer; reintroduce; virtual;
    procedure AddMultiple(const SomeItems, SomeLinks: array of TSkyString); reintroduce; overload;
    procedure AddMultiple(const SomeItems, SomeLinks: TStringArray); reintroduce; overload;
    function CreateACopy: TSkyStringStringList;
    procedure CopyFrom(AList: TSkyStringStringList);
    function GetAllItems: TStringArray; reintroduce;
    function GetTextStr: TSkyString;
    procedure Clear; override;
    procedure DeleteFromIndex(Index: Integer); override;
    procedure Delete(const AString: TSkyString); reintroduce; virtual;
    function Find(const AString: TSkyString; out Index: Integer): Boolean; reintroduce; virtual;
    function IndexOf(const AString: TSkyString): Integer; reintroduce; virtual;
    function IndexOfObject(const ALink: TSkyString): Integer; reintroduce;
    procedure LoadFromFile(const FileName: TSkyString);
    procedure LoadFromStream(Stream: TStream);
    procedure SaveToFile(const FileName: TSkyString);
    procedure SaveToStream(Stream: TStream);
    property CaseSensitive: Boolean read GetCaseSensitive write SetCaseSensitive;
    property Items[Index: Integer]: TSkyString read GetItem write PutItem; default;
    property LineBreak: TSkyString read FLineBreak write FLineBreak;
    property Text: TSkyString read GetTextStr write SetTextStr;
    property Objects[Index: Integer]: TSkyString read GetLink write PutLink;
    property ObjectOfValue[const AValue: TSkyString]: TSkyString read GetObjectOfValue write SetObjectOfValue;
    property ObjectOfValueDefault[const AValue, ADefault: TSkyString]: TSkyString read GetObjectOfValueDefault;
  end;
  TSkyStringStringListClass = class of TSkyStringStringList;
{$EndRegion}
{$Region ' TSkyStringVariantList'}
  TSkyStringVariantList = class(TSkyList)
  private
    FList: PStringVariantItemList;
    function LinearIndexOf(const AString: string): Integer;
    function GetObjectOfValue(const AValue: TSkyString): Variant;
    procedure SetObjectOfValue(const AValue: TSkyString; const Value: Variant);
    function GetObjectOfValueDefault(const AValue: TSkyString; const ADefault: Variant): Variant;
  protected
    function CompareItemsFromIndex(Index1, Index2: Integer): Integer; override;
    function CompareStrings(const AString1, AString2: string): Integer; inline;
    procedure ExchangeItems(Index1, Index2: Integer); override;
    function GetItem(Index: Integer): string; reintroduce; virtual;
    function GetLink(Index: Integer): Variant; reintroduce; virtual;
    procedure InsertItem(Index: Integer; const AString: string; ALink: Variant); reintroduce; virtual;
    procedure PutItem(AIndex:Integer; const AString: string); reintroduce; virtual;
    procedure PutLink(Index: Integer; ALink: Variant); reintroduce; virtual;
    procedure SetCapacity(NewCapacity: Integer); override;
  public
    function Add(const AString: string): Integer; reintroduce; virtual;
    function AddObject(const AString: string; ALink: Variant): Integer; reintroduce; virtual;
    procedure AddMultiple(const SomeItems: array of string; const SomeLinks: array of Variant); reintroduce;
    function GetAllItems: TStringArray; reintroduce;
    procedure Clear; override;
    procedure DeleteFromIndex(Index: Integer); override;
    procedure Delete(const AString: string); reintroduce; virtual;
    function Find(const AString: string; out Index: Integer): Boolean; reintroduce; virtual;
    function IndexOf(const AString: string): Integer; reintroduce; virtual;
    function IndexOfObject(ALink: Variant): Integer; reintroduce;
    property Items[Index: Integer]: string read GetItem write PutItem; default;
    property Objects[Index: Integer]: Variant read GetLink write PutLink;
    property ObjectOfValue[const AValue: TSkyString]: Variant read GetObjectOfValue write SetObjectOfValue;
    property ObjectOfValueDefault[const AValue: TSkyString; const ADefault: Variant]: Variant read GetObjectOfValueDefault;
  end;
{$EndRegion}

implementation

uses
  TypesFunctions, ExceptionClasses, Variants, Windows, Math, Entity;

const
  BOM_LSB_FIRST = WideChar($FEFF);
  BOM_MSB_FIRST = WideChar($FFFE);

{$Region 'TSkyList'}
function TSkyList.Add(APointer: Pointer; ALink: TObject = nil): Integer;
begin
  Result := AddObject(APointer, ALink);
end;

procedure TSkyList.AddMultiple(const SomeItems: array of Pointer; const SomeLinks: array of TObject);
var
  I: Integer;
begin
  CheckAddMultipleLinksLength(Length(SomeItems), Length(SomeLinks));
  if Length(SomeLinks) > 0 then
    for I := 0 to Length(SomeItems) - 1 do
      AddObject(SomeItems[I], SomeLinks[I])
  else
    for I := 0 to Length(SomeItems) - 1 do
      Add(SomeItems[I]);
end;

function TSkyList.AddObject(APointer: Pointer; ALink: TObject): Integer;
begin
  if not Sorted then
    Result := FCount
  else
    if Find(APointer, Result) then
    begin
      if OwnsObjects then
        ALink.Free;
      Exit;
    end;
  InsertItem(Result, APointer, ALink);
end;

procedure TSkyList.Changed;
begin
  if Assigned(FOnChange) then
    FOnChange(Self);
end;

procedure TSkyList.Changing;
begin
  if Assigned(FOnChanging) then
    FOnChanging(Self);
end;

procedure TSkyList.CheckAddMultipleLinksLength(AItemsLength, ALinksLength: Integer);
begin
  if (ALinksLength <> 0) and (ALinksLength <> AItemsLength)then
    raise ESkyListParameterCountMismatch.Create(Self, 'AddMultiple');
end;

procedure TSkyList.CheckCapacity(ACapacity, AMaxCapacity: Integer);
begin
  if (ACapacity < FCount) or (ACapacity > AMaxCapacity) then
    raise ESkyListCapacityError.Create(Self, 'CheckCapacity', ACapacity);
end;

procedure TSkyList.CheckIndex(AIndex: Integer);
begin
  if (AIndex < 0) or (AIndex >= FCount) then
    raise ESkyListIndexError.Create(Self, 'CheckIndex', AIndex);
end;

procedure TSkyList.Clear;
var
  I: Integer;
  Obj: TObject;
begin
  Changing;
  if FCount <> 0 then
  begin
    //Free all objects in the event that this list owns its objects
    if OwnsObjects then
    begin
      for I := 0 to FCount - 1 do
      begin
        Obj := GetLink(I);
        Obj.Free;
      end;
    end;
    FCount := 0;
  end;
  SetCapacity(0);
  Changed;
end;

function TSkyList.CompareItemsFromIndex(Index1, Index2: Integer): Integer;
begin
  Result := ComparePointers(Items[Index1], Items[Index2]);
end;

function TSkyList.ComparePointers(APointer1, APointer2: Pointer): Integer;
begin
  Result := TypesFunctions.ComparePointers(APointer1, APointer2);
end;

constructor TSkyList.Create(AnOwnsObjects: Boolean);
begin
  inherited Create;
  FOwnsObject := AnOwnsObjects;
  FSorted := False;
end;

procedure TSkyList.DeleteFromIndex(Index: Integer);
begin
  if (Index < 0) or (Index >= FCount) then
    Exit;
  Changing;
  // If this list owns its objects then free the associated TObject with this index
  if OwnsObjects then
    GetLink(Index).Free;
  Dec(FCount);
  if Index < FCount then
    System.Move(FList^[Index + 1], FList^[Index],
      (FCount - Index) * SizeOf(TListItem));
  Changed;
end;

procedure TSkyList.Delete(APointer: Pointer);
begin
  DeleteFromIndex(IndexOf(APointer));
end;

destructor TSkyList.Destroy;
begin
  FOnChange := nil;
  FOnChanging := nil;
  Clear;
  inherited Destroy;
end;

procedure TSkyList.Exchange(Index1, Index2: Integer);
begin
  CheckIndex(Index1);
  CheckIndex(Index2);
  Changing;
  ExchangeItems(Index1, Index2);
  Changed;
end;

procedure TSkyList.ExchangeItems(Index1, Index2: Integer);
var
  Temp: Pointer;
  Item1, Item2: PListItem;
begin
  Item1 := @FList^[Index1];
  Item2 := @FList^[Index2];
  Temp := Pointer(Item1^.FData);
  Pointer(Item1^.FData) := Pointer(Item2^.FData);
  Pointer(Item2^.FData) := Temp;
  Temp := Item1^.FLink;
  Item1^.FLink := Item2^.FLink;
  Item2^.FLink := Temp;
end;

function TSkyList.Find(APointer: Pointer; out Index: Integer): Boolean;
var
  L, H, I, C: Integer;
begin
  if not Sorted then
  begin
    Index := IndexOf(APointer);
    Result := Index <> -1;
    Exit;
  end;

  Result := False;
  L := 0;
  H := FCount - 1;
  while L <= H do
  begin
    I := (L + H) shr 1;
    C := ComparePointers(FList^[I].FData, APointer);
    if C < 0 then
      L := I + 1
    else
    begin
      H := I - 1;
      if C = 0 then
      begin
        Result := True;
        L := I;
      end;
    end;
  end;
  Index := L;
end;

function TSkyList.GetAllItems: TPointers;
var
  I: Integer;
begin
  SetLength(Result, Count);
  for I := 0 to Count - 1 do
    Result[I] := Items[I];
end;

function TSkyList.GetAllObjects: TObjects;
var
  I: Integer;
begin
  Result := nil;
  SetLength(Result, Count);
  for I := 0 to Count - 1 do
    Result[I] := Objects[I];
end;

function TSkyList.GetCapacity: Integer;
begin
  Result := FCapacity;
end;

function TSkyList.GetCount: Integer;
begin
  Result := FCount;
end;

function TSkyList.GetItem(Index: Integer): Pointer;
begin
  Result := FList^[Index].FData;
end;

function TSkyList.GetLink(Index: Integer): TObject;
begin
  CheckIndex(Index);
  Result := FList^[Index].FLink;
end;

function TSkyList.GetObjectsLimit(var AStartIndex: Integer; ACount: Integer): TObjects;
var
  TheActualCount: Integer;
  I: Integer;
begin
  Result := nil;
  if AStartIndex >= Count then
    Exit;
  TheActualCount := Min(ACount, Count - AStartIndex);
  SetLength(Result, TheActualCount);
  for I := 0 to TheActualCount - 1 do
    Result[I] := Objects[AStartIndex + I];
  AStartIndex := AStartIndex + TheActualCount;
end;

procedure TSkyList.Grow;
var
  Delta: Integer;
begin
  if FCapacity > 64 then Delta := FCapacity div 4 else
    if FCapacity > 8 then Delta := 16 else
      Delta := 4;
  SetCapacity(FCapacity + Delta);
end;

function TSkyList.LinearIndexOf(APointer: Pointer): Integer;
begin
  for Result := 0 to Count - 1 do
    if ComparePointers(Items[Result], APointer) = 0 then
      Exit;
  Result := -1;
end;

function TSkyList.IndexOf(APointer: Pointer): Integer;
begin
  if not Sorted then
    Result := LinearIndexOf(APointer)
  else
    if not Find(APointer, Result) then
      Result := -1;
end;

function TSkyList.IndexOfObject(ALink: TObject): Integer;
var
  I: Integer;
begin
  for I := 0 to Count - 1 do
    if GetLink(I) = ALink then
    begin
      Result := I;
      Exit;
    end;
  Result := -1;
end;

procedure TSkyList.InsertItem(Index: Integer; APointer: Pointer;
  ALink: TObject);
begin
  Changing;
  if FCount = FCapacity then
    Grow;
  if Index < FCount then
    System.Move(FList^[Index], FList^[Index + 1], (FCount - Index) * SizeOf(TListItem));
  with FList^[Index] do
  begin
    FLink := ALink;
    FData := APointer;
  end;
  Inc(FCount);
  Changed;
end;

procedure TSkyList.Notify(Ptr: Pointer; Action: TListNotification);
begin
end;

procedure TSkyList.PutItem(AIndex:Integer; APointer: Pointer);
begin
  CheckIndex(AIndex);
  Changing;
  FList^[AIndex].FData := APointer;
  Changed;
end;

procedure TSkyList.PutLink(Index: Integer; ALink: TObject);
begin
  CheckIndex(Index);
  Changing;
  FList^[Index].FLink := ALink;
  Changed;
end;

procedure TSkyList.QuickSort(L, R: Integer; SCompare: TSkyListSortCompare);
var
  I, J, P: Integer;
begin
  repeat
    I := L;
    J := R;
    P := (L + R) shr 1;
    repeat
      while SCompare(I, P) < 0 do
        Inc(I);
      while SCompare(J, P) > 0 do
        Dec(J);
      if I <= J then
      begin
        if I <> J then
          ExchangeItems(I, J);
        if P = I then
          P := J
        else if P = J then
          P := I;
        Inc(I);
        Dec(J);
      end;
    until I > J;
    if L < J then
      QuickSort(L, J, SCompare);
    L := I;
  until I >= R;
end;

procedure TSkyList.SetCapacity(NewCapacity: Integer);
begin
  CheckCapacity(NewCapacity, MaxListSize);
  if NewCapacity <> FCapacity then
  begin
    ReallocMem(FList, NewCapacity * SizeOf(TListItem));
    FCapacity := NewCapacity;
  end;
end;

procedure TSkyList.SetSorted(const Value: Boolean);
begin
  if FSorted <> Value then
  begin
    if Value then
      Sort;
    FSorted := Value;
  end;
end;

procedure TSkyList.SetUpdateState(Updating: Boolean);
begin
  if Updating then
    Changing
  else
    Changed;
end;

procedure TSkyList.Sort;
begin
  if not Sorted and (FCount > 1) then
  begin
    Changing;
    QuickSort(0, FCount - 1, CompareItemsFromIndex);
    Changed;
    FSorted := True;
  end;
end;
{$EndRegion}
{$Region 'TSkyStringList'}
function TSkyStringList.Add(const AString: TSkyString; ALink: TObject = nil): Integer;
begin
  Result := AddObject(AString, ALink);
end;

function TSkyStringList.AddObject(const AString: TSkyString;
  ALink: TObject): Integer;
begin
  if not Sorted then
    Result := FCount
  else
    if Find(AString, Result) then
    begin
      Objects[Result] := ALink;
      Exit;
    end;
  InsertItem(Result, AString, ALink);
end;

procedure TSkyStringList.AddMultiple(const SomeItems: array of TSkyString; const SomeLinks: array of TObject);
begin
  AddMultiple(TStringArray.FromArray(SomeItems), SomeLinks);
end;

procedure TSkyStringList.AddMultiple(const SomeItems: TStringArray;
  const SomeLinks: array of TObject);
var
  I: Integer;
begin
  CheckAddMultipleLinksLength(SomeItems.Count, Length(SomeLinks));
  if Length(SomeLinks) > 0 then
    for I := 0 to SomeItems.Count - 1 do
      AddObject(SomeItems[I], SomeLinks[I])
  else
    for I := 0 to SomeItems.Count - 1 do
      Add(SomeItems[I]);
end;

procedure TSkyStringList.Clear;
var
  I: Integer;
  Obj: TObject;
begin
  Changing;
  if FCount <> 0 then
  begin

    //Free all objects in the event that this list owns its objects
    if OwnsObjects then
    begin
      for I := 0 to FCount - 1 do
      begin
        Obj := FList^[I].FObject;
        Obj.Free;
      end;
    end;

    Finalize(FList^[0], FCount);
    FCount := 0;
  end;
  SetCapacity(0);
  Changed;
end;

function TSkyStringList.CompareItemsFromIndex(Index1, Index2: Integer): Integer;
begin
  Result := CompareStrings(Items[Index1], Items[Index2]);
end;

function TSkyStringList.CompareStrings(const AString1,
  AString2: TSkyString): Integer;
begin
  Result := AnsiCompareText(AString1, AString2);
end;

procedure TSkyStringList.CopyFrom(AList: TSkyStringList);
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

constructor TSkyStringList.Create(AnOwnsObjects: Boolean = False);
begin
  inherited;
  FLineBreak := sLineBreak;
end;

function TSkyStringList.CreateACopy: TSkyStringList;
begin
  if Self = nil then
  begin
    Result := nil;
    Exit;
  end;
  Result := TSkyStringListClass(ClassType).Create;
  Result.CopyFrom(Self);
end;

procedure TSkyStringList.Delete(const AString: TSkyString);
begin
  DeleteFromIndex(IndexOf(AString));
end;

procedure TSkyStringList.DeleteFromIndex(Index: Integer);
begin
  if (Index < 0) or (Index >= FCount) then
    Exit;
  Changing;
  if OwnsObjects then
    GetLink(Index).Free;
  Finalize(FList^[Index]);
  Dec(FCount);
  if Index < FCount then
    System.Move(FList^[Index + 1], FList^[Index],
      (FCount - Index) * SizeOf(TStringItem));
  Changed;
end;

procedure TSkyStringList.ExchangeItems(Index1, Index2: Integer);
var
  Temp: Pointer;
  Item1, Item2: PStringItem;
begin
  Item1 := @FList^[Index1];
  Item2 := @FList^[Index2];
  Temp := Pointer(Item1^.FString);
  Pointer(Item1^.FString) := Pointer(Item2^.FString);
  Pointer(Item2^.FString) := Temp;
  Temp := Item1^.FObject;
  Item1^.FObject := Item2^.FObject;
  Item2^.FObject := Temp;
end;

class function TSkyStringList.ExplodeString(const AString, ALineBreak: TSkyString): TStringArray;
var
  TheTempList: TSkyStringList;
  I: Integer;
begin
  TheTempList := TSkyStringList.Create;
  try
    TheTempList.Sorted := False;
    TheTempList.LineBreak := ALineBreak;
    TheTempList.Text := AString;
    Result.Count := TheTempList.Count;
    for I := 0 to TheTempList.Count - 1 do
      Result[I] := TheTempList[I];
  finally
    FreeAndNil(TheTempList);
  end;
end;

class function TSkyStringList.ImplodeString(const TheStrings: TStringArray; const ALineBreak: TSkyString): TSkyString;
var
  TheTempList: TSkyStringList;
begin
  TheTempList := TSkyStringList.Create;
  try
    TheTempList.Sorted := False;
    TheTempList.LineBreak := ALineBreak;
    TheTempList.AddMultiple(TheStrings, []);
    Result := TheTempList.Text;
  finally
    FreeAndNil(TheTempList);
  end;
end;

function TSkyStringList.Find(const AString: TSkyString; out Index: Integer): Boolean;
var
  L, H, I, C: Integer;
begin
  Result := False;
  L := 0;
  H := FCount - 1;
  while L <= H do
  begin
    I := (L + H) shr 1;
    C := CompareStrings(FList^[I].FString, AString);
    if C < 0 then L := I + 1 else
    begin
      H := I - 1;
      if C = 0 then
      begin
        Result := True;
        L := I;
      end;
    end;
  end;
  Index := L;
end;

function TSkyStringList.GetAllItems: TStringArray;
var
  I: Integer;
begin
  Result.Count := Count;
  for I := 0 to Count - 1 do
    Result[I] := Items[I];
end;

function TSkyStringList.GetItem(Index: Integer): TSkyString;
begin
  CheckIndex(Index);
  Result := FList^[Index].FString;
end;

function TSkyStringList.GetLink(Index: Integer): TObject;
begin
  CheckIndex(Index);
  Result := FList^[Index].FObject;
end;

function TSkyStringList.GetObjectOfValue(const AValue: TSkyString): TObject;
var
  TheIndex: Integer;
begin
  TheIndex := IndexOf(AValue);
  if TheIndex = -1 then
    raise ESkyListValueDoesNotExistError.Create(Self, 'GetObjectOfValue', AValue);
  Result := Objects[TheIndex];
end;

function TSkyStringList.GetObjectOfValueDefault(const AValue: TSkyString;
  const ADefault: TObject): TObject;
var
  TheIndex: Integer;
begin
  TheIndex := IndexOf(AValue);
  if TheIndex = -1 then
    Result := ADefault
  else
    Result := Objects[TheIndex];
end;

function TSkyStringList.GetTextStr: TSkyString;
var
  I: Integer;
  Len, LL: Integer;
  P: PWideChar;
  W: PTSkyString;
begin
  Len := 0;
  LL := Length(FLineBreak);
  for I := 0 to Count - 1 do
    Inc(Len, Length(FList^[I].FString) + LL);
  SetLength(Result, Len);
  P := PWideChar(Result);
  for I := 0 to Count - 1 do
  begin
    W := Addr(FList^[I].FString);
    Len := Length(W^);
    if Len > 0 then
    begin
      Move(W^[1], P[0], Len * SizeOf(WideChar));
      Inc(P, Len);
    end;
    if LL > 0 then
    begin
      Move(FLineBreak[1], P[0], LL * SizeOf(WideChar));
      Inc(P, LL);
    end;
  end;
end;

function TSkyStringList.IndexOf(const AString: TSkyString): Integer;
begin
  if not Sorted then
    Result := LinearIndexOf(AString)
  else
    if not Find(AString, Result) then
      Result := -1;
end;

procedure TSkyStringList.InsertItem(Index: Integer; const AString: TSkyString;
  ALink: TObject);
begin
  Changing;
  if FCount = FCapacity then
    Grow;
  if Index < FCount then
    System.Move(FList^[Index], FList^[Index + 1], (FCount - Index) * SizeOf(TStringItem));

  System.Initialize(FList^[Index]);
  FList^[Index].FString := AString;
  FList^[Index].FObject := ALink;

  Inc(FCount);
  Changed;
end;

function TSkyStringList.LinearIndexOf(const AString: TSkyString): Integer;
begin
  for Result := 0 to Count - 1 do
    if CompareStrings(Items[Result], AString) = 0 then
      Exit;
  Result := -1;
end;

procedure TSkyStringList.LoadFromFile(const FileName: TSkyString);
var
  Stream: TStream;
begin
  Stream := TFileStream.Create(FileName, fmOpenRead or fmShareDenyWrite);
  try
    LoadFromStream(Stream);
  finally
    Stream.Free;
  end;
end;

procedure SwapWordByteOrder(P: PWideChar; Len: Integer);
begin
  while Len > 0 do
  begin
    Dec(Len);
    P^ := WideChar((Word(P^) shr 8) or (Word(P^) shl 8));
    Inc(P);
  end;
end;

procedure TSkyStringList.LoadFromStream(Stream: TStream);
begin
  Changing;
  try
    Clear;
    SetTextStr(LoadTextFromStream(Stream));
  finally
    Changed;
  end;
end;

class function TSkyStringList.LoadTextFromStream(AStream: TStream): TSkyString;
var
  AnsiS: AnsiString;
  WideS: TSkyString;
  WC: WideChar;
  TheLength: Integer;
begin
  WC := #0;
  AStream.Read(WC, SizeOf(WC));
  if (Hi(Word(WC)) <> 0) and (WC <> BOM_LSB_FIRST) and (WC <> BOM_MSB_FIRST) then
  begin
    AStream.Seek(-SizeOf(WC), soFromCurrent);
    SetLength(AnsiS, (AStream.Size - AStream.Position) div SizeOf(AnsiChar));
    AStream.Read(AnsiS[1], Length(AnsiS) * SizeOf(AnsiChar));
    Result := TSkyString(AnsiS); // explicit Unicode conversion
  end
  else
  begin
    if (WC <> BOM_LSB_FIRST) and (WC <> BOM_MSB_FIRST) then
      AStream.Seek(-SizeOf(WC), soFromCurrent);
    TheLength := (AStream.Size - AStream.Position + 1) div SizeOf(WideChar);
    SetLength(WideS, TheLength);
    if TheLength > 0 then
      AStream.Read(WideS[1], Length(WideS) * SizeOf(WideChar));
    if WC = BOM_MSB_FIRST then
      SwapWordByteOrder(PWideChar(WideS), Length(WideS));
    Result := WideS;
  end;
end;

procedure TSkyStringList.SetTextStr(const Value: TSkyString);
var
  P, Start: PWideChar;
  S: TSkyString;
  Len: Integer;
  TheEnd: PWideChar;
  TheFound: Boolean;
  I: Integer;
begin
  TheFound := False;
  Changing;
  try
    Clear;
    P := PWideChar(Value);
    if (Value = '') or (P = nil) then
      Exit;
    TheEnd := PWideChar(@Value[Length(Value)]) + 1;
    while P < TheEnd do
    begin
      Start := P;
      while P < TheEnd do
      begin
        TheFound := False;
        for I := 1 to Length(FLineBreak) do
          if P[0] = FLineBreak[I] then
          begin
            TheFound := True;
            Break;
          end;
        if TheFound then
          Break;
        Inc(P);
      end;
      Len := P - Start;
      if Len > 0 then
      begin
        SetString(S, Start, Len);
        AddObject(S, nil); // consumes most time
      end
      else
        AddObject('', nil);
      if TheFound then
        Inc(P);
    end;
  finally
    Changed;
  end;
end;

procedure TSkyStringList.PutItem(AIndex:Integer; const AString: TSkyString);
begin
  CheckIndex(AIndex);
  Changing;
  FList^[AIndex].FString := AString;
  Changed;
end;

procedure TSkyStringList.PutLink(Index: Integer; ALink: TObject);
begin
  CheckIndex(Index);
  Changing;
  FList^[Index].FObject := ALink;
  Changed;
end;

procedure TSkyStringList.SaveToFile(const FileName: TSkyString);
var
  Stream: TStream;
begin
  Stream := TFileStream.Create(FileName, fmCreate);
  try
    SaveToStream(Stream);
  finally
    Stream.Free;
  end;
end;

procedure TSkyStringList.SaveToStream(Stream: TStream);
var
  TheTSkyString: TSkyString;
  TheChar: WideChar;
begin
  TheChar := BOM_LSB_FIRST;
  Stream.Write(TheChar, SizeOf(TheChar));
  TheTSkyString := GetTextStr;
  if Length(TheTSkyString) > 0 then
    Stream.Write(TheTSkyString[1], Length(TheTSkyString) * SizeOf(WideChar));
end;

procedure TSkyStringList.SetCapacity(NewCapacity: Integer);
begin
  CheckCapacity(NewCapacity, MaxListSize);
  if NewCapacity <> FCapacity then
  begin
    ReallocMem(FList, NewCapacity * SizeOf(TStringItem));
    FCapacity := NewCapacity;
  end;
end;
procedure TSkyStringList.SetObjectOfValue(const AValue: TSkyString;
  const Value: TObject);
var
  TheIndex: Integer;
begin
  TheIndex := IndexOf(AValue);
  if TheIndex <> -1 then
    Objects[IndexOf(AValue)] := Value;
end;

{$EndRegion}
{$Region 'TSkyObjectList'}
function TSkyObjectList.Add(AObject: TObject; ALink: TObject = nil): Integer;
begin
  Result := inherited Add(AObject, ALink);
end;

procedure TSkyObjectList.AddMultiple(const SomeItems: TObjects; const SomeLinks: TObjects);
var
  I: Integer;
begin
  CheckAddMultipleLinksLength(Length(SomeItems), Length(SomeLinks));
  if Length(SomeLinks) > 0 then
    for I := 0 to Length(SomeItems) -1 do
      AddObject(SomeItems[I], SomeLinks[I])
  else
    for I := 0 to Length(SomeItems) - 1 do
      Add(SomeItems[I]);
end;

function TSkyObjectList.AddObject(AObject, ALink: TObject): Integer;
begin
  if not Sorted then
    Result := FCount
  else
    if Find(AObject, Result) then
    begin
      if OwnsObjects then
        ALink.Free;
      if OwnsItems then
        AObject.Free;
      Exit;
    end;
  InsertItem(Result, AObject, ALink);
end;

procedure TSkyObjectList.Clear;
var
  I: Integer;
  TheItem, TheObject: TObject;
begin
  Changing;
  if FCount <> 0 then
  begin
    if OwnsObjects then
      for I := 0 to FCount - 1 do
      begin
        TheObject := GetLink(I);
        TheObject.Free;
      end;
    if OwnsItems then
      for I := 0 to FCount - 1 do
      begin
        TheItem := GetItem(I);
        TheItem.Free;
      end;
    FCount := 0;
  end;
  SetCapacity(0);
  Changed;
end;

constructor TSkyObjectList.Create(AnOwnsObjects, AnOwnsItems: Boolean);
begin
  inherited Create(AnOwnsObjects);
  FOwnsItems := AnOwnsItems;
end;

procedure TSkyObjectList.Delete(AObject: TObject);
begin
  inherited Delete(AObject);
end;

procedure TSkyObjectList.DeleteFromIndex(Index: Integer);
begin
  if (Index < 0) or (Index >= FCount) then
    Exit;
  Changing;
  // If this list owns its objects then free the associated TObject with this index
  if OwnsItems then
    GetItem(Index).Free;
  if OwnsObjects then
    GetLink(Index).Free;
  Dec(FCount);
  if Index < FCount then
    System.Move(FList^[Index + 1], FList^[Index],
      (FCount - Index) * SizeOf(TListItem));
  Changed;
end;

function TSkyObjectList.Find(AObject: TObject; out Index: Integer): Boolean;
begin
  Result := inherited Find(AObject, Index);
end;

procedure TSkyObjectList.FreeAllObjects;
var
  I: Integer;
begin
  for I := 0 to Count - 1 do
    Items[I].Free;
  Clear;
end;

function TSkyObjectList.GetAllItems: TObjects;
var
  I: Integer;
begin
  SetLength(Result, Count);
  for I := 0 to Count - 1 do
    Result[I] := Items[I];
end;

function TSkyObjectList.GetItem(Index: Integer): TObject;
begin
  Result := inherited GetItem(Index);
end;

function TSkyObjectList.GetObjectOfValue(const AValue: TObject): TObject;
var
  TheIndex: Integer;
begin
  TheIndex := IndexOf(AValue);
  if TheIndex = -1 then
    raise ESkyListValueDoesNotExistError.Create(Self, 'GetObjectOfValue', '$' + IntToHex(Integer(AValue), 8));
  Result := Objects[TheIndex];
end;

function TSkyObjectList.GetObjectOfValueDefault(const AValue, ADefault: TObject): TObject;
var
  TheIndex: Integer;
begin
  TheIndex := IndexOf(AValue);
  if TheIndex = -1 then
    Result := ADefault
  else
    Result := Objects[TheIndex];
end;

function TSkyObjectList.IndexOf(AObject: TObject): Integer;
begin
  Result := inherited IndexOf(AObject);
end;

procedure TSkyObjectList.PutItem(AIndex:Integer; AObject: TObject);
begin
  inherited PutItem(AIndex, AObject);
end;

procedure TSkyObjectList.SetObjectOfValue(const AValue, Value: TObject);
var
  TheIndex: Integer;
begin
  TheIndex := IndexOf(AValue);
  if TheIndex <> -1 then
    Objects[IndexOf(AValue)] := Value;
end;

{$EndRegion}
{$Region 'TSkyClassTypeList'}
function TSkyClassTypeList.Add(AClassType: TClass; const AClassName: TSkyString): Integer;
begin
  Result := inherited AddObject(AClassName, Pointer(AClassType));
end;

procedure TSkyClassTypeList.AddMultiple(const SomeItems: array of TClass; const SomeLinks: array of TSkyString);
var
  I: Integer;
begin
  CheckAddMultipleLinksLength(Length(SomeItems), Length(SomeLinks));
  if Length(SomeLinks) > 0 then
    for I := 0 to Length(SomeItems) - 1 do
      AddObject(SomeItems[I], SomeLinks[I])
  else
    for I := 0 to Length(SomeItems) - 1 do
      Add(SomeItems[I], SomeItems[I].ClassName);
end;

function TSkyClassTypeList.AddObject(AClassType: TClass;
  const AClassName: TSkyString): Integer;
begin
  Result := inherited AddObject(AClassName, Pointer(AClassType));
end;

procedure TSkyClassTypeList.Delete(AClassType: TClass);
begin
  DeleteFromIndex(IndexOf(AClassType));
end;

function TSkyClassTypeList.Find(AClassType: TClass;
  out Index: Integer): Boolean;
begin
  Index := IndexOfObject(Pointer(AClassType));
  Result := Index <> -1;
end;

function TSkyClassTypeList.FindByName(const AClassName: TSkyString): TClass;
var
  TheIndex: Integer;
begin
  TheIndex := inherited IndexOf(AClassName);
  if TheIndex <> -1 then
    Result := GetItem(TheIndex)
  else
    Result := nil;
end;

function TSkyClassTypeList.GetAllItems: TClasses;
var
  I: Integer;
begin
  SetLength(Result, Count);
  for I := 0 to Count - 1 do
    Result[I] := Items[I];
end;

function TSkyClassTypeList.GetItem(Index: Integer): TClass;
begin
  Result := TClass(inherited GetLink(Index));
end;

function TSkyClassTypeList.IndexOf(AClassType: TClass): Integer;
begin
  Result := inherited IndexOfObject(Pointer(AClassType));
end;

procedure TSkyClassTypeList.PutItem(AIndex: Integer; AClass: TClass);
begin
  inherited PutLink(AIndex, Pointer(AClass));
end;
{$EndRegion}
{$Region 'TSkyIntegerList'}
function TSkyIntegerList.Add(AInteger: Integer; ALink: TObject): Integer;
begin
  Result := AddObject(AInteger, ALink);
end;

procedure TSkyIntegerList.AddMultiple(const SomeItems: array of Integer; const SomeLinks: array of TObject);
var
  I: Integer;
begin
  CheckAddMultipleLinksLength(Length(SomeItems), Length(SomeLinks));
  if Length(SomeLinks) > 0 then
    for I := 0 to Length(SomeItems) - 1 do
      AddObject(SomeItems[I], SomeLinks[I])
  else
    for I := 0 to Length(SomeItems) - 1 do
      Add(SomeItems[I]);
end;

function TSkyIntegerList.AddObject(AInteger: Integer; ALink: TObject): Integer;
begin
  Result := inherited AddObject(Pointer(AInteger), ALink);
end;

procedure TSkyIntegerList.Delete(AInteger: Integer);
begin
  inherited Delete(Pointer(AInteger));
end;

function TSkyIntegerList.Find(AInteger: Integer; out Index: Integer): Boolean;
begin
  Result := inherited Find(Pointer(AInteger), Index);
end;

function TSkyIntegerList.GetAllItems: TIntegers;
var
  I: Integer;
begin
  SetLength(Result, Count);
  for I := 0 to Count - 1 do
    Result[I] := Items[I];
end;

function TSkyIntegerList.GetItem(Index: Integer): Integer;
begin
  Result := Integer(inherited GetItem(Index));
end;

function TSkyIntegerList.GetObjectOfValue(const AValue: Integer): TObject;
begin
  if IndexOf(AValue) = -1 then
    raise ESkyListValueDoesNotExistError.Create(Self, 'GetObjectOfValue', IntToStr(AValue));
  Result := Objects[IndexOf(AValue)];
end;

function TSkyIntegerList.GetObjectOfValueDefault(const AValue: Integer;
  const ADefault: TObject): TObject;
var
  TheIndex: Integer;
begin
  TheIndex := IndexOf(AValue);
  if TheIndex = -1 then
    Result := ADefault
  else
    Result := Objects[TheIndex];
end;

function TSkyIntegerList.IndexOf(AInteger: Integer): Integer;
begin
  Result := Integer(inherited IndexOf(Pointer(AInteger)));
end;

procedure TSkyIntegerList.PutItem(AIndex: Integer; AInteger: Integer);
begin
  inherited PutItem(AIndex, Pointer(AInteger));
end;
procedure TSkyIntegerList.SetObjectOfValue(const AValue: Integer;
  const Value: TObject);
var
  TheIndex: Integer;
begin
  TheIndex := IndexOf(AValue);
  if TheIndex <> -1 then
    Objects[TheIndex] := Value
end;

{$EndRegion}
{$Region 'TSkyDoubleList'}
function TSkyDoubleList.Add(const ADouble: Double; ALink: TObject): Integer;
begin
  Result := AddObject(ADouble, ALink);
end;

procedure TSkyDoubleList.AddMultiple(const SomeItems: array of Double; const SomeLinks: array of TObject);
var
  I: Integer;
begin
  CheckAddMultipleLinksLength(Length(SomeItems), Length(SomeLinks));
  if Length(SomeLinks) > 0 then
    for I := 0 to Length(SomeItems) - 1 do
      AddObject(SomeItems[I], SomeLinks[I])
  else
    for I := 0 to Length(SomeItems) - 1 do
      Add(SomeItems[I]);
end;

function TSkyDoubleList.AddObject(const ADouble: Double;
  ALink: TObject): Integer;
begin
  if not Sorted then
    Result := FCount
  else
    if Find(ADouble, Result) then
      Exit;
  InsertItem(Result, ADouble, ALink);
end;

function TSkyDoubleList.CompareDoubles(const ADouble1,
  ADouble2: Double): Integer;
begin
  Result := TypesFunctions.CompareDoubles(ADouble1, ADouble2);
end;

function TSkyDoubleList.CompareItemsFromIndex(Index1, Index2: Integer): Integer;
begin
  Result := CompareDoubles(Items[Index1], Items[Index2]);
end;

procedure TSkyDoubleList.Delete(const ADouble: Double);
begin
  DeleteFromIndex(IndexOf(ADouble));
end;

procedure TSkyDoubleList.DeleteFromIndex(Index: Integer);
begin
  if (Index < 0) or (Index >= FCount) then
    Exit;
  Changing;
  if OwnsObjects then
    GetLink(Index).Free;
  Dec(FCount);
  if Index < FCount then
    System.Move(FList^[Index + 1], FList^[Index],
      (FCount - Index) * SizeOf(TDoubleListItem));
  Changed;
end;

procedure TSkyDoubleList.ExchangeItems(Index1, Index2: Integer);
var
  Temp: Double;
  Temp2: Pointer;
  Item1, Item2: PDoubleListItem;
begin
  Item1 := @FList^[Index1];
  Item2 := @FList^[Index2];
  Temp := Item1^.FData;
  Item1^.FData := Item2^.FData;
  Item2^.FData := Temp;
  Temp2 := Item1^.FLink;
  Item1^.FLink := Item2^.FLink;
  Item2^.FLink := Temp2;
end;

function TSkyDoubleList.Find(const ADouble: Double;
  out Index: Integer): Boolean;
var
  L, H, I, C: Integer;
begin
  Result := False;
  L := 0;
  H := FCount - 1;
  while L <= H do
  begin
    I := (L + H) shr 1;
    C := CompareDoubles(FList^[I].FData, ADouble);
    if C < 0 then L := I + 1 else
    begin
      H := I - 1;
      if C = 0 then
      begin
        Result := True;
        L := I;
      end;
    end;
  end;
  Index := L;
end;

function TSkyDoubleList.GetAllItems: TDoubles;
var
  I: Integer;
begin
  SetLength(Result, Count);
  for I := 0 to Count - 1 do
    Result[I] := Items[I];
end;

function TSkyDoubleList.GetItem(Index: Integer): Double;
begin
  CheckIndex(Index);
  Result := FList^[Index].FData;
end;

function TSkyDoubleList.GetLink(Index: Integer): TObject;
begin
  CheckIndex(Index);
  Result := FList^[Index].FLink;
end;

function TSkyDoubleList.IndexOf(const ADouble: Double): Integer;
begin
  if not Sorted then
    Result := LinearIndexOf(ADouble)
  else
    if not Find(ADouble, Result) then
      Result := -1;
end;

procedure TSkyDoubleList.InsertItem(Index: Integer; const ADouble: Double; ALink: TObject);
begin
  Changing;
  if FCount = FCapacity then
    Grow;
  if Index < FCount then
    System.Move(FList^[Index], FList^[Index + 1], (FCount - Index) * SizeOf(TDoubleListItem));
  FList^[Index].FData := ADouble;
  FList^[Index].FLink := ALink;
  Inc(FCount);
  Changed;
end;

function TSkyDoubleList.LinearIndexOf(ADouble: Double): Integer;
begin
  for Result := 0 to Count - 1 do
    if CompareDoubles(Items[Result], ADouble) = 0 then
      Exit;
  Result := -1;
end;

procedure TSkyDoubleList.PutItem(AIndex: Integer; ADouble: Double);
begin
  CheckIndex(AIndex);
  Changing;
  FList^[AIndex].FData := ADouble;
  Changed;
end;

procedure TSkyDoubleList.PutLink(Index: Integer; ALink: TObject);
begin
  CheckIndex(Index);
  Changing;
  FList^[Index].FLink := ALink;
  Changed;
end;

procedure TSkyDoubleList.SetCapacity(NewCapacity: Integer);
begin
  CheckCapacity(NewCapacity, MaxDoubleListSize);
  if NewCapacity <> FCapacity then
  begin
    ReallocMem(FList, NewCapacity * SizeOf(TDoubleListItem));
    FCapacity := NewCapacity;
  end;
end;
{$EndRegion}
{$Region 'TSkyInt64List'}
function TSkyInt64List.Add(const AInt64: Int64; ALink: TObject): Integer;
begin
  Result := AddObject(AInt64, ALink);
end;

procedure TSkyInt64List.AddMultiple(const SomeItems: array of Int64; const SomeLinks: array of TObject);
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

function TSkyInt64List.AddObject(const AInt64: Int64;
  ALink: TObject): Integer;
begin
  if not Sorted then
    Result := FCount
  else
    if Find(AInt64, Result) then
      Exit;
  InsertItem(Result, AInt64, ALink);
end;

function TSkyInt64List.CompareInt64s(const AInt641,
  AInt642: Int64): Integer;
begin
  Result := TypesFunctions.CompareInt64s(AInt641, AInt642);
end;

function TSkyInt64List.CompareItemsFromIndex(Index1, Index2: Integer): Integer;
begin
  Result := CompareInt64s(Items[Index1], Items[Index2]);
end;

procedure TSkyInt64List.Delete(const AInt64: Int64);
begin
  DeleteFromIndex(IndexOf(AInt64));
end;

procedure TSkyInt64List.DeleteFromIndex(Index: Integer);
begin
  if (Index < 0) or (Index >= FCount) then
    Exit;
  Changing;
  if OwnsObjects then
    GetLink(Index).Free;
  Dec(FCount);
  if Index < FCount then
    System.Move(FList^[Index + 1], FList^[Index],
      (FCount - Index) * SizeOf(TInt64ListItem));
  Changed;
end;

procedure TSkyInt64List.ExchangeItems(Index1, Index2: Integer);
var
  Temp: Int64;
  Temp2: Pointer;
  Item1, Item2: PInt64ListItem;
begin
  Item1 := @FList^[Index1];
  Item2 := @FList^[Index2];
  Temp := Item1^.FData;
  Item1^.FData := Item2^.FData;
  Item2^.FData := Temp;
  Temp2 := Item1^.FLink;
  Item1^.FLink := Item2^.FLink;
  Item2^.FLink := Temp2;
end;

function TSkyInt64List.Find(const AInt64: Int64;
  out Index: Integer): Boolean;
var
  L, H, I, C: Integer;
begin
  Result := False;
  L := 0;
  H := FCount - 1;
  while L <= H do
  begin
    I := (L + H) shr 1;
    C := CompareInt64s(FList^[I].FData, AInt64);
    if C < 0 then L := I + 1 else
    begin
      H := I - 1;
      if C = 0 then
      begin
        Result := True;
        L := I;
      end;
    end;
  end;
  Index := L;
end;

function TSkyInt64List.GetAllItems: TInt64s;
var
  I: Integer;
begin
  SetLength(Result, Count);
  for I := 0 to Count - 1 do
    Result[I] := Items[I];
end;

function TSkyInt64List.GetItem(Index: Integer): Int64;
begin
  CheckIndex(Index);
  Result := FList^[Index].FData;
end;

function TSkyInt64List.GetItemsLimit(var AStartIndex: Integer; ACount: Integer): TIds;
var
  TheActualCount: Integer;
  I: Integer;
begin
  Result := nil;
  if AStartIndex >= Count then
    Exit;
  TheActualCount := Min(ACount, Count - AStartIndex);
  SetLength(Result, TheActualCount);
  for I := 0 to TheActualCount - 1 do
    Result[I] := Items[AStartIndex + I];
  AStartIndex := AStartIndex + TheActualCount;
end;

function TSkyInt64List.GetLink(Index: Integer): TObject;
begin
  CheckIndex(Index);
  Result := FList^[Index].FLink;
end;

function TSkyInt64List.GetObjectOfValue(const AValue: Int64): TObject;
var
  TheIndex: Integer;
begin
  TheIndex := IndexOf(AValue);
  if TheIndex = -1 then
    raise ESkyListValueDoesNotExistError.Create(Self, 'GetObjectOfValue', IntToStr(AValue));
  Result := Objects[TheIndex];
end;

function TSkyInt64List.GetObjectOfValueDefault(const AValue: Int64;
  const ADefault: TObject): TObject;
var
  TheIndex: Integer;
begin
  TheIndex := IndexOf(AValue);
  if TheIndex = -1 then
    Result := ADefault
  else
    Result := Objects[TheIndex];
end;

function TSkyInt64List.IndexOf(const AInt64: Int64): Integer;
begin
  if not Sorted then
    Result := LinearIndexOf(AInt64)
  else
    if not Find(AInt64, Result) then
      Result := -1;
end;

procedure TSkyInt64List.InsertItem(Index: Integer; const AInt64: Int64; ALink: TObject);
begin
  Changing;
  if FCount = FCapacity then
    Grow;
  if Index < FCount then
    System.Move(FList^[Index], FList^[Index + 1], (FCount - Index) * SizeOf(TInt64ListItem));
  FList^[Index].FData := AInt64;
  FList^[Index].FLink := ALink;
  Inc(FCount);
  Changed;
end;

function TSkyInt64List.LinearIndexOf(AInt64: Int64): Integer;
begin
  for Result := 0 to Count - 1 do
    if CompareInt64s(Items[Result], AInt64) = 0 then
      Exit;
  Result := -1;
end;

procedure TSkyInt64List.PutItem(AIndex: Integer; AInt64: Int64);
begin
  CheckIndex(AIndex);
  Changing;
  FList^[AIndex].FData := AInt64;
  Changed;
end;

procedure TSkyInt64List.PutLink(Index: Integer; ALink: TObject);
begin
  CheckIndex(Index);
  Changing;
  FList^[Index].FLink := ALink;
  Changed;
end;

procedure TSkyInt64List.SetCapacity(NewCapacity: Integer);
begin
  CheckCapacity(NewCapacity, MaxInt64ListSize);
  if NewCapacity <> FCapacity then
  begin
    ReallocMem(FList, NewCapacity * SizeOf(TInt64ListItem));
    FCapacity := NewCapacity;
  end;
end;
procedure TSkyInt64List.SetObjectOfValue(const AValue: Int64;
  const Value: TObject);
var
  TheIndex: Integer;
begin
  TheIndex := IndexOf(AValue);
  if TheIndex <> -1 then
    Objects[IndexOf(AValue)] := Value;
end;

{$EndRegion}
{$Region 'TSkyStringStringList'}
function TSkyStringStringList.Add(const AString: TSkyString; const ALink: TSkyString = ''): Integer;
begin
  Result := AddObject(AString, ALink);
end;

procedure TSkyStringStringList.AddMultiple(const SomeItems: array of TSkyString; const SomeLinks: array of TSkyString);
begin
  AddMultiple(TStringArray.FromArray(SomeItems), TStringArray.FromArray(SomeLinks));
end;

procedure TSkyStringStringList.AddMultiple(const SomeItems, SomeLinks: TStringArray);
var
  I: Integer;
begin
  CheckAddMultipleLinksLength(SomeItems.Count, SomeLinks.Count);
  if SomeLinks.Count > 0 then
    for I := 0 to SomeItems.Count - 1 do
      AddObject(SomeItems[I], SomeLinks[I])
  else
    for I := 0 to SomeItems.Count - 1 do
      Add(SomeItems[I]);
end;

function TSkyStringStringList.AddObject(const AString: TSkyString; const ALink: TSkyString): Integer;
begin
  if not Sorted then
    Result := FCount
  else
    if Find(AString, Result) then
      Exit;
  InsertItem(Result, AString, ALink);
end;

procedure TSkyStringStringList.Clear;
begin
  Changing;
  if FCount <> 0 then
  begin
    Finalize(FList^[0], FCount);
    FCount := 0;
  end;
  SetCapacity(0);
  Changed;
end;

function TSkyStringStringList.CompareItemsFromIndex(Index1, Index2: Integer): Integer;
begin
  Result := FCompareStringsMethod(Items[Index1], Items[Index2]);
end;

procedure TSkyStringStringList.CopyFrom(AList: TSkyStringStringList);
var
  I: Integer;
begin
  Clear;
  if AList = nil then
    Exit;
  for I := 0 to AList.Count - 1 do
    Add(AList.Items[I], AList.Objects[I]);
end;

constructor TSkyStringStringList.Create;
begin
  inherited Create(False);
  FLineBreak := sLineBreak;
  CaseSensitive := False;
end;

function TSkyStringStringList.CreateACopy: TSkyStringStringList;
begin
  if Self = nil then
  begin
    Result := nil;
    Exit;
  end;
  Result := TSkyStringStringListClass(ClassType).Create;
  Result.CopyFrom(Self);
end;

procedure TSkyStringStringList.Delete(const AString: TSkyString);
begin
  DeleteFromIndex(IndexOf(AString));
end;

procedure TSkyStringStringList.DeleteFromIndex(Index: Integer);
begin
  if (Index < 0) or (Index >= FCount) then
    Exit;
  Changing;
  Finalize(FList^[Index]);
  Dec(FCount);
  if Index < FCount then
    System.Move(FList^[Index + 1], FList^[Index],
      (FCount - Index) * SizeOf(TStringStringListItem));
  Changed;
end;

procedure TSkyStringStringList.ExchangeItems(Index1, Index2: Integer);
var
  Temp: TSkyString;
  Item1, Item2: PStringStringListItem;
begin
  Item1 := @FList^[Index1];
  Item2 := @FList^[Index2];
  Temp := Item1^.FData;
  Item1^.FData := Item2^.FData;
  Item2^.FData := Temp;
  Temp := Item1^.FLink;
  Item1^.FLink := Item2^.FLink;
  Item2^.FLink := Temp;
end;

class function TSkyStringStringList.ExplodeString(const AString, ALineBreak: TSkyString): TStringArray;
var
  TheTempList: TSkyStringStringList;
  I: Integer;
begin
  TheTempList := TSkyStringStringList.Create;
  try
    TheTempList.Sorted := False;
    TheTempList.LineBreak := ALineBreak;
    TheTempList.Text := AString;
    Result.Count := TheTempList.Count;
    for I := 0 to TheTempList.Count - 1 do
      Result[I] := TheTempList[I];
  finally
    FreeAndNil(TheTempList);
  end;
end;

function TSkyStringStringList.Find(const AString: TSkyString; out Index: Integer): Boolean;
var
  L, H, I, C: Integer;
begin
  Result := False;
  L := 0;
  H := FCount - 1;
  while L <= H do
  begin
    I := (L + H) shr 1;
    C := FCompareStringsMethod(FList^[I].FData, AString);
    if C < 0 then L := I + 1 else
    begin
      H := I - 1;
      if C = 0 then
      begin
        Result := True;
        L := I;
      end;
    end;
  end;
  Index := L;
end;

function TSkyStringStringList.GetAllItems: TStringArray;
var
  I: Integer;
begin
  Result.Count := Count;
  for I := 0 to Count - 1 do
    Result[I] := Items[I];
end;

function TSkyStringStringList.GetCaseSensitive: Boolean;
begin
  Result := @FCompareStringsMethod = @AnsiCompareStr;
end;

function TSkyStringStringList.GetItem(Index: Integer): TSkyString;
begin
  CheckIndex(Index);
  Result := FList^[Index].FData;
end;

function TSkyStringStringList.GetLink(Index: Integer): TSkyString;
begin
  CheckIndex(Index);
  Result := FList^[Index].FLink;
end;

function TSkyStringStringList.GetTextStr: TSkyString;
var
  I, L, Size, Count: Integer;
  P: PWideChar;
  S, LB: TSkyString;
begin
  Count := GetCount;
  Size := 0;
  LB := LineBreak;
  for I := 0 to Count - 1 do
    Inc(Size, Length(Items[I]) + Length(Objects[I]) + Length(LB) + 1);
  SetString(Result, nil, Size);
  P := Pointer(Result);
  for I := 0 to Count - 1 do
  begin
    S := Items[I] + '=' + Objects[I] + LB;
    L := Length(S);
    if L <> 0 then
    begin
      System.Move(Pointer(S)^, P^, L * SizeOf(WideChar));
      Inc(P, L);
    end;
  end;
end;

class function TSkyStringStringList.ImplodeString(const TheStrings: TStringArray;
  const ALineBreak: TSkyString): TSkyString;
var
  TheTempList: TSkyStringStringList;
begin
  TheTempList := TSkyStringStringList.Create;
  try
    TheTempList.Sorted := False;
    TheTempList.LineBreak := ALineBreak;
    TheTempList.AddMultiple(TheStrings, TStringArray.Empty);
    Result := TheTempList.Text;
  finally
    FreeAndNil(TheTempList);
  end;
end;

function TSkyStringStringList.IndexOf(const AString: TSkyString): Integer;
begin
  if not Sorted then
    Result := LinearIndexOf(AString)
  else
    if not Find(AString, Result) then
      Result := -1;
end;

function TSkyStringStringList.IndexOfObject(const ALink: TSkyString): Integer;
var
  I: Integer;
begin
  for I := 0 to Count - 1 do
    if GetLink(I) = ALink then
    begin
      Result := I;
      Exit;
    end;
  Result := -1;
end;

procedure TSkyStringStringList.InsertItem(Index: Integer; const AString, ALink: TSkyString);
begin
  Changing;
  if FCount = FCapacity then
    Grow;
  if Index < FCount then
    System.Move(FList^[Index], FList^[Index + 1], (FCount - Index) * SizeOf(TStringStringListItem));

  System.Initialize(FList^[Index]);
  FList^[Index].FData := AString;
  FList^[Index].FLink := ALink;

  Inc(FCount);
  Changed;
end;

function TSkyStringStringList.LinearIndexOf(const AString: TSkyString): Integer;
begin
  for Result := 0 to Count - 1 do
    if FCompareStringsMethod(Items[Result], AString) = 0 then
      Exit;
  Result := -1;
end;

procedure TSkyStringStringList.LoadFromFile(const FileName: TSkyString);
var
  Stream: TStream;
begin
  Stream := TFileStream.Create(FileName, fmOpenRead or fmShareDenyWrite);
  try
    LoadFromStream(Stream);
  finally
    Stream.Free;
  end;
end;

procedure TSkyStringStringList.LoadFromStream(Stream: TStream);
begin
  Changing;
  try
    Clear;
    SetTextStr(LoadTextFromStream(Stream));
  finally
    Changed;
  end;
end;

class function TSkyStringStringList.LoadTextFromStream(AStream: TStream): TSkyString;
var
  AnsiS: AnsiString;
  WideS: TSkyString;
  WC: WideChar;
  TheLength: Integer;
begin
  WC := #0;
  AStream.Read(WC, SizeOf(WC));
  if (Hi(Word(WC)) <> 0) and (WC <> BOM_LSB_FIRST) and (WC <> BOM_MSB_FIRST) then
  begin
    AStream.Seek(-SizeOf(WC), soFromCurrent);
    SetLength(AnsiS, (AStream.Size - AStream.Position) div SizeOf(AnsiChar));
    AStream.Read(AnsiS[1], Length(AnsiS) * SizeOf(AnsiChar));
    Result := TSkyString(AnsiS); // explicit Unicode conversion
  end
  else
  begin
    if (WC <> BOM_LSB_FIRST) and (WC <> BOM_MSB_FIRST) then
      AStream.Seek(-SizeOf(WC), soFromCurrent);
    TheLength := (AStream.Size - AStream.Position + 1) div SizeOf(WideChar);
    SetLength(WideS, TheLength);
    if TheLength > 0 then
      AStream.Read(WideS[1], Length(WideS) * SizeOf(WideChar));
    if WC = BOM_MSB_FIRST then
      SwapWordByteOrder(PWideChar(WideS), Length(WideS));
    Result := WideS;
  end;
end;

function TSkyStringStringList.GetObjectOfValue(const AValue: TSkyString): TSkyString;
begin
  if IndexOf(AValue) = -1 then
    raise ESkyListValueDoesNotExistError.Create(Self, 'GetObjectOfValue', AValue);
  Result := Objects[IndexOf(AValue)];
end;

function TSkyStringStringList.GetObjectOfValueDefault(const AValue,
  ADefault: TSkyString): TSkyString;
var
  TheIndex: Integer;
begin
  TheIndex := IndexOf(AValue);
  if TheIndex = -1 then
    Result := ADefault
  else
    Result := Objects[TheIndex];
end;

procedure TSkyStringStringList.PutItem(AIndex:Integer; const AString: TSkyString);
begin
  CheckIndex(AIndex);
  Changing;
  FList^[AIndex].FData := AString;
  Changed;
end;

procedure TSkyStringStringList.PutLink(Index: Integer; const ALink: TSkyString);
begin
  CheckIndex(Index);
  Changing;
  FList^[Index].FLink := ALink;
  Changed;
end;

procedure TSkyStringStringList.SaveToFile(const FileName: TSkyString);
var
  Stream: TStream;
begin
  Stream := TFileStream.Create(FileName, fmCreate);
  try
    SaveToStream(Stream);
  finally
    Stream.Free;
  end;
end;

procedure TSkyStringStringList.SaveToStream(Stream: TStream);
var
  TheTSkyString: TSkyString;
  TheChar: WideChar;
begin
  TheChar := BOM_LSB_FIRST;
  Stream.Write(TheChar, SizeOf(TheChar));
  TheTSkyString := GetTextStr;
  if Length(TheTSkyString) > 0 then
    Stream.Write(TheTSkyString[1], Length(TheTSkyString) * SizeOf(WideChar));
end;

procedure TSkyStringStringList.SetCapacity(NewCapacity: Integer);
begin
  CheckCapacity(NewCapacity, MaxListSize);
  if NewCapacity <> FCapacity then
  begin
    ReallocMem(FList, NewCapacity * SizeOf(TStringStringListItem));
    FCapacity := NewCapacity;
  end;
end;

procedure TSkyStringStringList.SetCaseSensitive(const Value: Boolean);
begin
  if Value then
    FCompareStringsMethod := AnsiCompareStr
  else
    FCompareStringsMethod := AnsiCompareText;
end;

procedure TSkyStringStringList.SetObjectOfValue(const AValue, Value: TSkyString);
var
  TheIndex: Integer;
begin
  TheIndex := IndexOf(AValue);
  if TheIndex <> -1 then
    Objects[TheIndex] := Value
end;

procedure TSkyStringStringList.SetTextStr(const Value: TSkyString);
var
  P, Start: PWideChar;
  S, S1: TSkyString;
  Len, Len1: Integer;
begin
  Changing;
  try
    Clear;
    if Value <> '' then
    begin
      P := PWideChar(Value);
      if P <> nil then
      begin
        while P[0] <> WideChar(0) do
        begin
          Start := P;
          while True do
          begin
            case P[0] of
              WideChar(0), WideChar(10), WideChar(13), WideChar('='):
                Break;
            end;
            Inc(P);
          end;
          Len := P - Start;
          if Len > 0 then
            SetString(S, Start, Len);
          while (P[0] = WideChar(10)) or (P[0] = WideChar(13)) or (P[0] = WideChar('=')) do
            Inc(P);
          Start := P;
          while True do
          begin
            case P[0] of
              WideChar(0), WideChar(10), WideChar(13), WideChar('='):
                Break;
            end;
            Inc(P);
          end;
          Len1 := P - Start;
          if Len1 > 0 then
            SetString(S1, Start, Len1);
          while (P[0] = WideChar(10)) or (P[0] = WideChar(13)) do
            Inc(P);
          if Len > 0 then
            if Len1 > 0 then
              Add(S, S1)
            else
              Add(S)
          else
            if Len1 > 0 then
              Add('', S1)
            else
              Add('')
        end;
      end;
    end;
  finally
    Changed;
  end;
end;

{$EndRegion}
{$Region 'TSkyStringVariantList'}
function TSkyStringVariantList.Add(const AString: string): Integer;
begin
  Result := AddObject(AString, Unassigned);
end;

procedure TSkyStringVariantList.AddMultiple(const SomeItems: array of string; const SomeLinks: array of Variant);
var
  I: Integer;
begin
  CheckAddMultipleLinksLength(Length(SomeItems), Length(SomeLinks));
  if Length(SomeLinks) > 0 then
    for I := 0 to Length(SomeItems) - 1 do
      AddObject(SomeItems[I], SomeLinks[I])
  else
    for I := 0 to Length(SomeItems) - 1 do
      Add(SomeItems[I]);
end;

function TSkyStringVariantList.AddObject(const AString: string; ALink: Variant): Integer;
begin
  if not Sorted then
    Result := FCount
  else
    if Find(AString, Result) then
      Exit;
  InsertItem(Result, AString, ALink);
end;

procedure TSkyStringVariantList.Clear;
begin
  Changing;
  if FCount <> 0 then
  begin
    Finalize(FList^[0], FCount);
    FCount := 0;
  end;
  SetCapacity(0);
  Changed;
end;

function TSkyStringVariantList.CompareItemsFromIndex(Index1,
  Index2: Integer): Integer;
begin
  Result := CompareStrings(Items[Index1], Items[Index2]);
end;

function TSkyStringVariantList.CompareStrings(const AString1,
  AString2: string): Integer;
begin
  Result := AnsiCompareText(AString1, AString2);
end;

procedure TSkyStringVariantList.Delete(const AString: string);
begin
  DeleteFromIndex(IndexOf(AString));
end;

procedure TSkyStringVariantList.DeleteFromIndex(Index: Integer);
begin
  if (Index < 0) or (Index >= FCount) then
    Exit;
  Changing;
  Finalize(FList^[Index]);
  Dec(FCount);
  if Index < FCount then
    System.Move(FList^[Index + 1], FList^[Index],
      (FCount - Index) * SizeOf(TStringVariantListItem));
  Changed;
end;

procedure TSkyStringVariantList.ExchangeItems(Index1, Index2: Integer);
var
  TempItem: TStringVariantListItem;
begin
  TempItem := FList^[Index1];
  FList^[Index1] := FList^[Index2];
  FList^[Index2] := TempItem;
end;

function TSkyStringVariantList.Find(const AString: string; out Index: Integer): Boolean;
var
  L, H, I, C: Integer;
begin
  Result := False;
  L := 0;
  H := FCount - 1;
  while L <= H do
  begin
    I := (L + H) shr 1;
    C := CompareStrings(FList^[I].FData, AString);
    if C < 0 then L := I + 1 else
    begin
      H := I - 1;
      if C = 0 then
      begin
        Result := True;
        L := I;
      end;
    end;
  end;
  Index := L;
end;

function TSkyStringVariantList.GetAllItems: TStringArray;
var
  I: Integer;
begin
  Result.Count := Count;
  for I := 0 to Count - 1 do
    Result[I] := Items[I];
end;

function TSkyStringVariantList.GetItem(Index: Integer): string;
begin
  CheckIndex(Index);
  Result := FList^[Index].FData;
end;

function TSkyStringVariantList.GetLink(Index: Integer): Variant;
begin
  CheckIndex(Index);
  Result := FList^[Index].FLink;
end;

function TSkyStringVariantList.GetObjectOfValue(const AValue: TSkyString): Variant;
var
  TheIndex: Integer;
begin
  TheIndex := IndexOf(AValue);
  if TheIndex = -1 then
    raise ESkyListValueDoesNotExistError.Create(Self, 'GetObjectOfValue', AValue);
  Result := Objects[TheIndex];
end;

function TSkyStringVariantList.GetObjectOfValueDefault(const AValue: TSkyString; const ADefault: Variant): Variant;
var
  TheIndex: Integer;
begin
  TheIndex := IndexOf(AValue);
  if TheIndex = -1 then
    Result := ADefault
  else
    Result := Objects[TheIndex];
end;

function TSkyStringVariantList.IndexOf(const AString: string): Integer;
begin
  if not Sorted then
    Result := LinearIndexOf(AString)
  else
    if not Find(AString, Result) then
      Result := -1;
end;

function TSkyStringVariantList.IndexOfObject(ALink: Variant): Integer;
var
  I: Integer;
begin
  for I := 0 to Count - 1 do
    if CompareVariants(GetLink(I), ALink) = 0 then
    begin
      Result := I;
      Exit;
    end;
  Result := -1;
end;

procedure TSkyStringVariantList.InsertItem(Index: Integer; const AString: string; ALink: Variant);
begin
  Changing;
  if FCount = FCapacity then
    Grow;
  if Index < FCount then
    System.Move(FList^[Index], FList^[Index + 1], (FCount - Index) * SizeOf(TStringVariantListItem));

  System.Initialize(FList^[Index]);
  FList^[Index].FData := AString;
  FList^[Index].FLink := ALink;

  Inc(FCount);
  Changed;
end;

function TSkyStringVariantList.LinearIndexOf(const AString: string): Integer;
begin
  for Result := 0 to Count - 1 do
    if CompareStrings(Items[Result], AString) = 0 then
      Exit;
  Result := -1;
end;

procedure TSkyStringVariantList.PutItem(AIndex:Integer; const AString: string);
begin
  CheckIndex(AIndex);
  Changing;
  FList^[AIndex].FData := AString;
  Changed;
end;

procedure TSkyStringVariantList.PutLink(Index: Integer; ALink: Variant);
begin
  CheckIndex(Index);
  Changing;
  FList^[Index].FLink := ALink;
  Changed;
end;

procedure TSkyStringVariantList.SetCapacity(NewCapacity: Integer);
begin
  CheckCapacity(NewCapacity, MaxVariantListSize);
  if NewCapacity <> FCapacity then
  begin
    ReallocMem(FList, NewCapacity * SizeOf(TStringVariantListItem));
    FCapacity := NewCapacity;
  end;
end;
procedure TSkyStringVariantList.SetObjectOfValue(const AValue: TSkyString; const Value: Variant);
var
  TheIndex: Integer;
begin
  TheIndex := IndexOf(AValue);
  if TheIndex <> -1 then
    Objects[IndexOf(AValue)] := Value;
end;

{$EndRegion}

end.
