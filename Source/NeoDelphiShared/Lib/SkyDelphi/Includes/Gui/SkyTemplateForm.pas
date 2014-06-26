unit SkyTemplateForm;

interface

uses
  Classes, Controls, Forms,
  SkyBaseForm;

type
  TfrmSkyTemplate = class(TfrmSkyBase)
  private
  public
    procedure TranslateForm; override;
  end;

var
  frmSkyTemplate: TfrmSkyTemplate;

implementation

{$R *.dfm}

{ TfrmSkyTemplate }

procedure TfrmSkyTemplate.TranslateForm;
begin
  // to implement
end;

end.
