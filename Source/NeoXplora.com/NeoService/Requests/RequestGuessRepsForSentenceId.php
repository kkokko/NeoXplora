<?php
  namespace TApp;
  class TRequestGuessRepsForSentenceId extends \sky\TEntity{
    protected function DefineProperties(){
      parent::DefineProperties();
      $this->AddProperties(array(
        "SentenceId" => array("check" => "Integer")
      ));
    }
    function __construct($AnId){
      parent::__construct();
      $this->SetProperty("SentenceId", $AnId);
    }
  }
?>