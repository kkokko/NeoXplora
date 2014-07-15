<?php
  namespace sky;
  class TEntityWithName extends TEntity {
    protected function DefineProperties(){
      parent::DefineProperties();
      $this->AddProperties(array(
        "Id" => array("check" => "Integer"),
        "Name" => array("check" => "string"),
        "Version" => array("check" => "string")
      ));
    }
  }
?>