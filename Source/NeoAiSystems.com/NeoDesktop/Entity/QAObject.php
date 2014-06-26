<?php
  namespace TApp;
  class TQAObject extends \sky\TEntity {
    protected function DefineProperties(){
      parent::DefineProperties();
      $this->AddProperties(array(
        "Answer" => array("check" => "string"),
        "Id" => array("check" => "Integer"),
        "StoryId" => array("check" => "Integer"),
        "QARule" => array("check" => "string"),
        "Question" => array("check" => "string")
      ));
    }
  }
?> 