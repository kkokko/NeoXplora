<?php
  namespace TApp;
  class TStoryObject extends \sky\TEntity {
    protected function DefineProperties(){
      parent::DefineProperties();
      $this->AddProperties(array(
        "Body" => array("check" => "string"),
        "CategoryId" => array("check" => "Integer"),
        "Id" => array("check" => "Integer"),
        "MySqlId" => array("check" => "Integer"),
        "Protos" => array("check" => "TEntityList"),
        "Qas" => array("check" => "TEntityList"),
        "Sentences" => array("check" => "TEntityList"),
        "Title" => array("check" => "string"),
        "User" => array("check" => "string"),
        "CanOverwrite"  => array("check" => "Boolean")       
      ));
    }
  }
?>