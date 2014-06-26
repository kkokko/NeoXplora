<?php
  namespace TApp;

// only used for testing
// not used on the client
  class ESkyUnexpectedResponse extends \sky\ESkyException{
    public function __construct($ARaisedBy, $ALocation, $ARequest, $AnExpectedResponse, $AResponse){
      parent::__construct($ARaisedBy, $ALocation);
      $TheParams = $this->GetProperty("Params");
      $TheParams->SetProperty("Message", "tlUnexpectedResponse");
      $TheParams->SetProperty("Request", $ARequest);
      $TheParams->SetProperty("ExpectedResponse", $AnExpectedResponse);
      $TheParams->SetProperty("Response", $AResponse);
    }    
  }
?>