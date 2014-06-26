<?php
  namespace TApp;
  class TResponseSetStoriesFromExport extends \sky\TEntity {
    protected function DefineProperties(){
      parent::DefineProperties();
      $this->AddProperties(array(
        "StoryIds" => array("check" => "TEntityList"),
        "AddedCount" => array("check" => "Integer"),
        "UpdatedCount" => array("check" => "Integer")
      ));
    }
  }  
?>