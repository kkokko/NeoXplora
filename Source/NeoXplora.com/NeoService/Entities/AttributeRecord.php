<?php
  namespace TApp;
  class TAttributeRecord extends \sky\TEntity {
    protected function DefineProperties(){
      parent::DefineProperties();
      $this->AddProperties(array(
        "Key" => array("check" => "string"),
        "Values" => array("check" => "TEntityList") //Array of TEntityWithName
      ));
    }
  }
?>