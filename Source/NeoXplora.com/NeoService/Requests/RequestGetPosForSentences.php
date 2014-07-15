<?php
  namespace TApp;
  class TRequestGetPosForSentences extends \sky\TEntity{
    protected function DefineProperties(){
      parent::DefineProperties();
      $this->AddProperties(array(
        "Sentences" => array("check" => "TEntityList"), //Array of TEntityWithName
        "UseModifiedPos" => array("check" => "Boolean")
      ));
    }
  }
?>