<?php
  namespace TApp;
  class TResponseGetStoriesForImport extends \sky\TEntity {
    protected function DefineProperties(){
      parent::DefineProperties();
      $this->AddProperties(array(
        "Stories" => array("check" => "TEntityList")      
      ));
    }
  }  
?>