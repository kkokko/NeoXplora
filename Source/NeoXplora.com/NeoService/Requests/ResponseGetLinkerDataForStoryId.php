<?php
  namespace TApp;
  class TResponseGetLinkerDataForStoryId extends \sky\TEntity{
    protected function DefineProperties(){
      parent::DefineProperties();
      $this->AddProperties(array(
        "Words" => array("check" => "TEntityList"), //Array of TEntityWithName
        "Entities" => array("check" => "TEntityList") //Array of TEntityRecord
      ));
    }
  }
?>