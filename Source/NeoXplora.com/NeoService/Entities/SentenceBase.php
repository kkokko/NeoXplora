<?php
  namespace TApp;
  class TSentenceBase extends \sky\TEntity {
    protected function DefineProperties(){
      parent::DefineProperties();
      $this->AddProperties(array(
        "Name" => array("check" => "string"), 
        "Rep" => array("check" => "string"),
        "SRep" => array("check" => "string"),
        "CRep" => array("check" => "string"),
        "Pos" => array("check" => "string")
      ));
    }
  }
?>