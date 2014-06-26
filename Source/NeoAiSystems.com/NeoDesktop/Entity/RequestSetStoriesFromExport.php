<?php
  namespace TApp;
  class TRequestSetStoriesFromExport extends \sky\TEntity {
    protected function DefineProperties(){
      parent::DefineProperties();
      $this->AddProperties(array(
        "Stories" => array("check" => "TEntityList"), // array of TStoryObject
        "UserName" => array("check"=> "string"),
        "UserPassword" => array("check"=> "string")   
      ));
      
    }
  }
?>