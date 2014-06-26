<?php
  namespace TApp;
  class TSentenceObject extends \sky\TEntity {
    protected function DefineProperties(){
      parent::DefineProperties();
      $this->AddProperties(array(
        "ContextRep" => array("check" => "string"),
        "Name" => array("check" => "string"),
        "Id" => array("check" => "Integer"),
        "POS" => array("check" => "string"),
        "Proto1Id" => array("check" => "Integer"),
        "Proto2Id" => array("check" => "Integer"),
        "Representation" => array("check" => "string"),
        "SemanticRep" => array("check" => "string"),
        "StoryId" => array("check" => "Integer")
      ));
    }
  }
?>