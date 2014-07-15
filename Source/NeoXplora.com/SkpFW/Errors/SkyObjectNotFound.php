<?php
  namespace sky;

  class ESkyObjectNotFound extends \sky\ESkyException{
    public function __construct($ARaisedBy, $ALocation, $ADataChanged, $AValue){
      parent::__construct($ARaisedBy, $ALocation);
      $TheParams = $this->GetProperty("Params");
      $TheParams->SetProperty("Message", "tlInvalidValueForField");
      $TheParams->SetProperty("DataChanged", $ADataChanged);
      $TheParams->SetProperty("DataValue", $AValue);
    }    
  }
?>