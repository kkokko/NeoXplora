<?php
  namespace TApp;
  class TEntityRecord extends \sky\TEntity {
    protected function DefineProperties(){
      parent::DefineProperties();
      $this->AddProperties(array(
        "Id" => array("check" => "Integer"), 
        "Type" => array("check" => "string"), //etAnimal, etPerson, etObject ....
        "Attributes" => array("check" => "TEntityList") //Arrayof TAttributeRecord
      ));
    }
  }
?>