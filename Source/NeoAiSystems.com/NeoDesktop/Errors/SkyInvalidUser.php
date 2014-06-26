<?php
  namespace TApp;

  class ESkyInvalidUser extends \sky\ESkyException{
    public function __construct($ARaisedBy, $ALocation){
      parent::__construct($ARaisedBy, $ALocation);
      $TheParams = $this->GetProperty("Params");
      $TheParams->SetProperty("Message", "tlInvalidUser");
    }
  }
?>