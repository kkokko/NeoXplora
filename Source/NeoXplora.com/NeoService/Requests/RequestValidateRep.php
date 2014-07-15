<?php
  namespace TApp;
  class TRequestValidateRep extends \sky\TEntity{
    protected function DefineProperties(){
      parent::DefineProperties();
      $this->AddProperties(array(
        "Rep" => array("check" => "string")
      ));
    }
    function __construct($AReP){
      parent::__construct();
      $this->SetProperty("Rep", $AReP);
    }
  }
?>