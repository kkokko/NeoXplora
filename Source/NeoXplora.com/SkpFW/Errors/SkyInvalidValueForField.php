<?php
  namespace sky;

  class ESkyInvalidValueForField extends \sky\ESkyException{
    public function __construct($ARaisedBy, $ALocation, $AFieldName, $AValidationText){
      parent::__construct($ARaisedBy, $ALocation);
      $TheParams = $this->GetProperty("Params");
      $TheParams->SetProperty("Message", "tlInvalidValueForField");
      $TheParams->SetProperty("FieldName", $AFieldName);
      $TheParams->SetProperty("ValidationText", $AValidationText);
    }    
  }
?>