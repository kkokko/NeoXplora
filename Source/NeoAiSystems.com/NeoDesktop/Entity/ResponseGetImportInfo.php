<?php
  namespace TApp;
  class TResponseGetImportInfo extends \sky\TEntity {
    protected function DefineProperties(){
      parent::DefineProperties();
      $this->AddProperties(array(
        "StoryTotal" => array("check" => "Integer"),
        "StoryInProgress" => array("check"=> "Integer"),
        "StoryNew" => array("check"=> "Integer"),
        "IdList" => array("check"=> "TEntityList")        
      ));
    }
  }  
?>