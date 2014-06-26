<?php
  namespace TApp;
  class TProtoObject extends \sky\TEntity {
    protected function DefineProperties(){
      parent::DefineProperties();
      $this->AddProperties(array(
        "Name" => array("check" => "string"),
        "Level" => array("check" => "Integer"),
        "Id" => array("check" => "Integer"),
        "StoryId" => array("check" => "Integer")
      ));
    }
  }
?>