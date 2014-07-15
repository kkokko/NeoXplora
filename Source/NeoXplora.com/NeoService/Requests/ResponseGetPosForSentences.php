<?php
  namespace TApp;
  class TResponseGetPosForSentences extends \sky\TEntity{
    protected function DefineProperties(){
      parent::DefineProperties();
      $this->AddProperties(array(
        "Sentences" => array("check" => "TEntityList") //Array of TEntityWithName
      ));
    }
  }
?>