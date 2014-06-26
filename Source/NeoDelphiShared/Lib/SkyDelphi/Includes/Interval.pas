unit Interval;

interface

uses
  TypesConsts, SkyLists;

type
  TInterval = record
  private
    function ValidFromCompareValue: Double; inline;
    function ValidUntilCompareValue: Double; inline;
  public
    ValidFrom: TDateTime;
    ValidUntil: TDateTime;

    class function Init(AValidFrom, AValidUntil: TDateTime): TInterval; static; inline;
    class function InitDayInterval(AValidFrom, AValidUntil: TDate): TInterval; static; inline;

    /// returns true if the intervals are adjacent
    function Adjacent(AnInterval: TInterval; APrecision: TTimePrecision = dpSECOND): Boolean;
    /// appends AnInterval to self, if the intervals are not adjacent or
    ///  do not Overlap does nothing
    procedure Append(AnInterval: TInterval; APrecision: TTimePrecision = dpSECOND);
    /// returns true if Self contains AnInterval
    function Contains(AnInterval: TInterval; APrecision: TTimePrecision = dpSECOND): Boolean; overload;
    /// returns true if Self contains ADateTime
    function Contains(ADateTime: TDateTime; APrecision: TTimePrecision = dpSECOND): Boolean; overload;
    /// ADateTime is:
    ///  -1 - before Self
    ///   0 - contained by Self
    ///   1 - after Self
    function Compare(ADateTime: TDateTime; APrecision: TTimePrecision = dpSECOND): Integer; overload;
    /// An Interval is:
    ///  -1 - before Self
    ///   0 - overlapping or contained by Self
    ///   1 - after Self
    function Compare(AnInterval: TInterval; APrecision: TTimePrecision = dpSECOND): Integer; overload;
    /// returns true if the interval matches Self
    function Equals(AnInterval: TInterval; APrecision: TTimePrecision = dpSECOND): Boolean; overload;
    // returns the area overlapping with AnInterval
    function GetOverlap(AnInterval: TInterval; APrecision: TTimePrecision = dpSECOND): TInterval;
    // returns an invalid interval
    class function Invalid: TInterval; static;
    /// returns true if the interval is invalid or empty
    function IsInvalid: Boolean;
    /// returns true if:
    ///  1. Self.ValidFrom < AnInterval.ValidFrom
    ///  or
    ///  2. Self.ValidFrom = AnInterval.ValidFrom and Self.ValidUntil < AnInterval.ValidUntil
    function IsBefore(AnInterval: TInterval; APrecision: TTimePrecision = dpSECOND): Boolean;
    /// returns true is the AnInterval overlaps with Self
    function OverlapsWith(AnInterval: TInterval; APrecision: TTimePrecision = dpSECOND): Boolean; overload;
    /// returns true if ValidFrom = ValidUntil
    function ZeroSize(APrecision: TTimePrecision = dpSECOND): Boolean;

    {$IFDEF UNICODE}
    type
      TIntervals = array of TInterval;
    class function GetArray(SomeValues: array of TInterval): TIntervals; static;
    /// returns object as array
    function AsArray: TIntervals;
    /// returns true if the arrays of intervals are identical
    class function Equals(SomeIntervals, SomeOtherIntervals: TIntervals;
      APrecision: TTimePrecision = dpSECOND): Boolean; overload; static;
    /// Merges adjacent intervals, removes 0 length intervals
    ///  SomeIntervals must be sorted by ValidFrom
    class function MergeIntervals(SomeIntervals: TIntervals; APrecision:
      TTimePrecision = dpSECOND): TIntervals; static;
    /// returns true if self overlaps with any of SomeIntervals
    function OverlapsWith(SomeIntervals: TIntervals; APrecision: TTimePrecision = dpSECOND): Boolean;  overload;
    // sorts an array of Intervals
    class procedure Sort(var SomeIntervals: TIntervals; APrecision: TTimePrecision =
      dpSECOND); static;
    /// removes interval that overlaps with Self
    function Subtract(AnInterval: TInterval; APrecision: TTimePrecision =
      dpSECOND): TIntervals;  overload;
    /// removes intervals that overlap with someintervals
    ///   both lists must be sorted and Merged
    class function Subtract(SomeTargets, SomeValues: TIntervals;
      APrecision: TTimePrecision = dpSECOND): TIntervals; overload; static;
    {$ENDIF}
  end;

implementation

uses
  TypesFunctions, ExceptionClasses;

{ TInterval }

function TInterval.Adjacent(AnInterval: TInterval; APrecision: TTimePrecision): Boolean;
begin
  Result := not (IsInvalid or AnInterval.IsInvalid);
  if not Result then
    Exit;
  Result := (CompareTimes(ValidFromCompareValue, AnInterval.ValidUntilCompareValue, APrecision) = 0) or
    (CompareTimes(ValidUntilCompareValue, AnInterval.ValidFromCompareValue, APrecision) = 0);
end;

function TInterval.Contains(AnInterval: TInterval; APrecision: TTimePrecision): Boolean;
begin
  Result := not (IsInvalid or AnInterval.IsInvalid);
  if not Result then
    Exit;
  Result := Contains(AnInterval.ValidFromCompareValue, APrecision) and
    Contains(AnInterval.ValidUntilCompareValue, APrecision);
end;

procedure TInterval.Append(AnInterval: TInterval; APrecision: TTimePrecision);
begin
  if not (OverlapsWith(AnInterval, APrecision) or (Adjacent(AnInterval, APrecision))) then
    Exit;
  if CompareTimes(ValidFromCompareValue, AnInterval.ValidFromCompareValue) > 0 then
    ValidFrom := AnInterval.ValidFrom;
  if CompareTimes(ValidUntilCompareValue, AnInterval.ValidUntilCompareValue) < 0 then
    ValidUntil := AnInterval.ValidUntil;
end;

{$IFDEF UNICODE}
function TInterval.AsArray: TIntervals;
begin
  SetLength(Result, 1);
  Result[0] := Self;
end;

class function TInterval.Equals(SomeIntervals, SomeOtherIntervals: TIntervals;
  APrecision: TTimePrecision): Boolean;
var
  I: Integer;
begin
  Result := CompareIntegers(Length(SomeIntervals), Length(SomeOtherIntervals)) = 0;
  if not Result then
    Exit;
  Result := False;
  for I := 0 to High(SomeIntervals) do
    if not SomeIntervals[I].Equals(SomeOtherIntervals[I]) then
      Exit;
  Result := True;
end;

class function TInterval.GetArray(SomeValues: array of TInterval): TIntervals;
var
  I: Integer;
begin
  SetLength(Result, Length(SomeValues));
  for I := 0 to High(SomeValues) do
    Result[I] := SomeValues[I];
end;

class function TInterval.MergeIntervals(SomeIntervals: TIntervals;
  APrecision: TTimePrecision): TIntervals;
var
  TheIndex: Integer;
  I: Integer;
begin
  SetLength(Result, Length(SomeIntervals));
  if Length(Result) = 0 then
    Exit;
  TheIndex := -1;
  for I := 0 to High(SomeIntervals) do
    // if intervals are adjacent then merge
    if
      (TheIndex >= 0) and
      (
        Result[TheIndex].Adjacent(SomeIntervals[I], APrecision) or
        Result[TheIndex].OverlapsWith(SomeIntervals[I], APrecision)
      )
    then
      Result[TheIndex].ValidUntil := SomeIntervals[I].ValidUntil
    // if the new interval is zero length, ignore
    else if SomeIntervals[I].ZeroSize(APrecision) then
      Continue
    else
    // add the value to the list
    begin
      Inc(TheIndex);
      Result[TheIndex] := SomeIntervals[I];
    end;
  SetLength(Result, TheIndex + 1);
end;

function TInterval.OverlapsWith(SomeIntervals: TIntervals; APrecision: TTimePrecision = dpSECOND): Boolean;
var
  I: Integer;
begin
  Result := True;
  for I := 0 to High(SomeIntervals) do
    if OverlapsWith(SomeIntervals[I]) then
      Exit;
  Result := False;
end;

class procedure TInterval.Sort(var SomeIntervals: TIntervals; APrecision: TTimePrecision);
var
  TheAux: TInterval;
  TheFirstIncident: Integer;
  ThisRound: Boolean;
  I: Integer;
begin
  TheFirstIncident := 0;
  repeat
    ThisRound := False;
    for I := TheFirstIncident to Length(SomeIntervals) - 2 do
      if SomeIntervals[I + 1].IsBefore(SomeIntervals[I], APrecision) then
      begin
        TheAux := SomeIntervals[I + 1];
        SomeIntervals[I + 1] := SomeIntervals[I];
        SomeIntervals[I] := TheAux;
        if not ThisRound then
          TheFirstIncident := I - 1;
        ThisRound := True;
      end;
    if TheFirstIncident = - 1 then
      TheFirstIncident := 0;
  until not ThisRound;
end;

class function TInterval.Subtract(SomeTargets, SomeValues: TIntervals;
  APrecision: TTimePrecision): TIntervals;
var
  TheIndexTargets, TheIndexValues: Integer;
  TheResultIndex: Integer;
  TheValues: TInterval.TIntervals;
// Example:
//  Targets:    ----  --- ---     -------     ------------
//  Values:  - -- ---   ------------  -------  ---     ----
//  Result:      -    --            --        -   -----
begin
  SetLength(Result, 2 * Length(SomeValues) + Length(SomeTargets));
  TheIndexTargets := 0;
  TheIndexValues := 0;
  TheResultIndex := 0;
  while (TheIndexValues < Length(SomeValues)) and (TheIndexTargets < Length(SomeTargets)) do
  begin
    case SomeTargets[TheIndexTargets].Compare(SomeValues[TheIndexValues]) of
      // The value is before the target, select next value
      -1: Inc(TheIndexValues);
      // The target overlaps, subtract the value and select next target
      0: begin
        TheValues := SomeTargets[TheIndexTargets].Subtract(SomeValues[TheIndexValues]);
        case Length(TheValues) of
          0: Inc(TheIndexTargets); // interval was completely destroyed
          1: if TheValues[0].ValidFrom = SomeTargets[TheIndexTargets].ValidFrom then // right side was cut
          begin
            Result[TheResultIndex] := TheValues[0];
            Inc(TheResultIndex);
            Inc(TheIndexTargets);
          end
          else
            SomeTargets[TheIndexTargets].ValidFrom := TheValues[0].ValidFrom; //left side was cut
          2: begin // Target was cut in the middle
            Result[TheResultIndex] := TheValues[0]; // add the first part to results
            Inc(TheResultIndex);
            SomeTargets[TheIndexTargets].ValidFrom := TheValues[1].ValidFrom;
            Inc(TheIndexValues);
          end
        end;
      end;
      // The target is before the value, select next target
      1: begin
        Result[TheResultIndex] := SomeTargets[TheIndexTargets];
        Inc(TheResultIndex);
        Inc(TheIndexTargets);
      end;
    end;
  end;
  while TheIndexTargets < Length(SomeTargets) do
  begin
    Result[TheResultIndex] := SomeTargets[TheIndexTargets];
    Inc(TheResultIndex);
    Inc(TheIndexTargets);
  end;
  SetLength(Result, TheResultIndex);
  Result := TInterval.MergeIntervals(Result, APrecision);
end;

function TInterval.Subtract(AnInterval: TInterval; APrecision: TTimePrecision): TIntervals;
begin
  Result := nil;
  if IsInvalid or (not OverlapsWith(AnInterval, APrecision)) then
    Exit;
  if Contains(AnInterval.ValidFromCompareValue, APrecision) then
    if Contains(AnInterval.ValidUntilCompareValue, APrecision) then
      Result := TInterval.GetArray([
        TInterval.Init(ValidFrom, AnInterval.ValidFrom),
        TInterval.Init(AnInterval.ValidUntil, ValidUntil)
      ])
    else
      if AnInterval.Contains(Self) then
        Result := nil
      else
        Result := TInterval.Init(ValidFrom, AnInterval.ValidFrom).AsArray
  else
    if AnInterval.Contains(Self) then
      Result := nil
    else
      Result := TInterval.Init(AnInterval.ValidUntil, ValidUntil).AsArray;
  Result := TInterval.MergeIntervals(Result);
end;

{$ENDIF}

function TInterval.Compare(ADateTime: TDateTime; APrecision: TTimePrecision): Integer;
begin
  if IsInvalid then
    raise ESkyInvalidInterval.Create(nil, 'TInterval.Compare');
  Result := CompareTimes(ADateTime, ValidFromCompareValue, APrecision);
  if Result = 0 then
    Result := -1;
  // if ADateTime is before ValidFrom or ADateTime = ValidFrom, return result
  if Result < 0 then
    Exit;
  Result := CompareTimes(ADateTime, ValidUntilCompareValue, APrecision);
  if Result = 0 then
    Result := 1;
  // if ADateTime is before Valid or ADateTime = ValidFrom, return result
  if Result < 0 then
    Result := 0;
end;

function TInterval.Compare(AnInterval: TInterval; APrecision: TTimePrecision): Integer;
begin
  Result := Compare(AnInterval.ValidFromCompareValue, APrecision);
  if Result >= 0 then
    Exit;
  Result := Compare(AnInterval.ValidUntilCompareValue, APrecision);
  if Result = 1 then
    Result := 0;
end;

function TInterval.Contains(ADateTime: TDateTime; APrecision: TTimePrecision): Boolean;
begin
  Result := (CompareTimes(ValidUntilCompareValue, ADateTime, APrecision) >= 0) and
    (CompareTimes(ValidFromCompareValue, ADateTime, APrecision) <= 0);
end;

function TInterval.Equals(AnInterval: TInterval; APrecision: TTimePrecision): Boolean;
begin
  Result := (CompareTimes(ValidFrom, AnInterval.ValidFrom) = 0) and
    (CompareTimes(ValidUntil, AnInterval.ValidUntil) = 0);
end;

function TInterval.IsInvalid: Boolean;
begin
  Result := CompareTimes(ValidFromCompareValue, ValidUntilCompareValue) > 0;
end;

function TInterval.GetOverlap(AnInterval: TInterval; APrecision: TTimePrecision): TInterval;
begin
  if not OverlapsWith(AnInterval, APrecision) then
  begin
    Result := TInterval.Invalid;
    Exit;
  end;
  if Compare(AnInterval.ValidFromCompareValue, APrecision) = -1 then
    Result.ValidFrom := ValidFrom
  else
    Result.ValidFrom := AnInterval.ValidFrom;
  if Compare(AnInterval.ValidUntilCompareValue, APrecision) = 1 then
    Result.ValidUntil := ValidUntil
  else
    Result.ValidUntil:= AnInterval.ValidUntil;
end;

class function TInterval.Invalid: TInterval;
begin
  Result := TInterval.Init(1, -1);
end;

class function TInterval.Init(AValidFrom, AValidUntil: TDateTime): TInterval;
begin
  Result.ValidFrom := AValidFrom;
  Result.ValidUntil := AValidUntil;
end;

class function TInterval.InitDayInterval(AValidFrom, AValidUntil: TDate): TInterval;
begin
  if AValidUntil = 0 then
    Result := Init(AValidFrom, 0)
  else
    Result := Init(AValidFrom, AValidUntil + 1);
end;

function TInterval.IsBefore(AnInterval: TInterval; APrecision: TTimePrecision): Boolean;
begin
  case CompareTimes(ValidFromCompareValue, AnInterval.ValidFromCompareValue, APrecision) of
    -1: Result := True;
    0: Result := CompareTimes(ValidUntilCompareValue, AnInterval.ValidUntilCompareValue, APrecision) = -1;
  else //1:
    Result := False;
  end;
end;

function TInterval.OverlapsWith(AnInterval: TInterval; APrecision: TTimePrecision): Boolean;
begin
  Result := not (IsInvalid or AnInterval.IsInvalid);
  if not Result then
    Exit;
  Result := (CompareTimes(ValidUntilCompareValue, AnInterval.ValidFromCompareValue, APrecision) > 0) and
    (CompareTimes(ValidFromCompareValue, AnInterval.ValidUntilCompareValue, APrecision) < 0);
end;

function TInterval.ValidFromCompareValue: Double;
begin
  if ValidFrom = 0 then
    Result := -2000000
  else
    Result := ValidFrom;
end;

function TInterval.ValidUntilCompareValue: Double;
begin
  if ValidUntil = 0 then
    Result := 2000000
  else
    Result := ValidUntil;
end;

function TInterval.ZeroSize(APrecision: TTimePrecision): Boolean;
begin
  Result := CompareTimes(ValidFromCompareValue, ValidUntilCompareValue, APrecision) = 0;
end;

end.

