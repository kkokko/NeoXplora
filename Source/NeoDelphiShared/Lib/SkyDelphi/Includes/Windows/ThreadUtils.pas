unit ThreadUtils;

interface

function CurrentThreadHandle(AThreadHandle: Cardinal = 0): Cardinal;

implementation

uses
  Windows;

function CurrentThreadHandle(AThreadHandle: Cardinal = 0): Cardinal;
begin
  if AThreadHandle = 0 then
    Result := Windows.GetCurrentThread
  else
    Result := AThreadHandle;
end;

end.
