<?php
  namespace sky;
  class TEntityWithId extends \sky\TEntity {
    protected function DefineProperties(){
      parent::DefineProperties();
      $this->AddProperties(array(
        "Id" => array("check" => "Integer")
      ));
    }
  }
?>