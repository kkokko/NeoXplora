<?php
  namespace TApp;
  class TProtoSentence extends \sky\TEntity {
    protected function DefineProperties(){
      parent::DefineProperties();
      $this->AddProperties(array(
        "Name" => array("check" => "string"),
        "Level" => array("check" => "Integer"),
        "StoryID" => array("check" => "Integer"),
        "Sentences" => array("check" => "TEntityList")
      ));
    }
  }
?>