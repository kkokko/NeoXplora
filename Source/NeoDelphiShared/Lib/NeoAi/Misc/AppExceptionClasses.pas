unit AppExceptionClasses;

interface

uses
  SkyException;

// Warning message: Properties don't need to be publised because they are
// saved in Params in OnCreate, and loaded from Params in LoadFromStream
type
{$Region 'ESkyCatastrophicFailure'}
  ESkyCatastrophicFailure = class(ESkyException)
  public
    constructor Create(ARaisedBy: TObject; const ALocation: string); reintroduce;
  end; // Format('Catastrophic failure - uncaught exception', [])'

{$EndRegion}
{$Region 'EAppRepDecoderException'}
  EAppRepDecoderException = class(ESkyException)
  private
    function GetErrorString: string;
    procedure SetErrorString(const Value: string);
    function GetStrIndex: Integer;
    procedure SetStrIndex(const Value: Integer);
  protected
    function GetTranslatedMessage: string; override;
  public
    constructor Create(ARaisedBy: TObject; const ALocation, AnErrorString: string; AStrIndex: Integer); reintroduce;
    property ErrorString: string read GetErrorString write SetErrorString;
    property StrIndex: Integer read GetStrIndex write SetStrIndex;
  end; // Format('Rep decoder exception: %s'#13#10'At: %d', [ErrorString, StrIndex])'

{$EndRegion}

implementation

uses
  SysUtils, Translations;

{ ESkyCatastrophicFailure }

constructor ESkyCatastrophicFailure.Create(ARaisedBy: TObject;
  const ALocation: string);
begin
  inherited Create(ARaisedBy, ALocation, 'tlUncaughtException');
end;

{ EAppRepDecoderException }

constructor EAppRepDecoderException.Create(ARaisedBy: TObject; const ALocation, AnErrorString: string; AStrIndex: Integer);
begin
  inherited Create(ARaisedBy, ALocation, 'Rep decoder exception: %s');
  ErrorString := AnErrorString;
  StrIndex := AStrIndex;
end;

function EAppRepDecoderException.GetErrorString: string;
begin
  Result := Params.GetValueForField('ErrorString');
end;

function EAppRepDecoderException.GetStrIndex: Integer;
begin
  Result := Params.GetValueForField('StrIndex');
end;

function EAppRepDecoderException.GetTranslatedMessage: string;
begin
  Result := Format(Translate(Message), [ErrorString, StrIndex]);
end;

procedure EAppRepDecoderException.SetErrorString(const Value: string);
begin
  Params.SetValueForField('ErrorString', Value);
end;

procedure EAppRepDecoderException.SetStrIndex(const Value: Integer);
begin
  Params.SetValueForField('StrIndex', Value);
end;

initialization
  ESkyException.RegisterClasses([
    ESkyCatastrophicFailure,
    EAppRepDecoderException
  ]);

end.
