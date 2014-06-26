unit Communication;

interface

uses
  Entity, SkyException;

type
  TRequest = class(TEntity);
  TRequestClass = class of TRequest;

  TGenericResponse = class(TEntity);

  TResponse = class(TGenericResponse);

  TResponseError = class(TGenericResponse)
  protected
    FMessage: string;
  public
    function GetTranslatedMessage: string; virtual;
  published
    property Message: string read FMessage write FMessage;
  end;

  // Errors
  TResponseInvalidRequest = class(TResponseError)
  private
    FReason: string;
  public
    constructor Create(AReason: string); reintroduce;
    function GetTranslatedMessage: string; override;
  published
    property Reason: string read FReason write FReason;
  end;

  TResponseServerException = class(TResponseError)
  private
    FException: ESkyException;
  public
    constructor Create(AnException: ESkyException); reintroduce;
    function GetTranslatedMessage: string; override;
  published
    property Exception: ESkyException read FException write FException;
  end;

implementation

uses
  Classes, Translations, EntityManager, SysUtils;

{ TResponseError }

function TResponseError.GetTranslatedMessage: string;
begin
  Result := Translate(Message);
end;

{ TResponseInvalidRequest }

constructor TResponseInvalidRequest.Create(AReason: string);
begin
  inherited Create;
  Message := tlInvalidRequest;
  Reason := AReason;
end;

function TResponseInvalidRequest.GetTranslatedMessage: string;
begin
  Result := Format(Translate(Message), [Translate(Reason)]);
end;

{ TResponseServerException }

constructor TResponseServerException.Create(AnException: ESkyException);
begin
  inherited Create;
  Message := AnException.Message;
  FException := AnException.CreateACopy;
end;

function TResponseServerException.GetTranslatedMessage: string;
begin
  Result := FException.TranslatedMessage;
end;

initialization
  TEntityManager.RegisterEntityClasses
  ([
    TResponse,
    TResponseInvalidRequest,
    TResponseServerException
  ]);

end.