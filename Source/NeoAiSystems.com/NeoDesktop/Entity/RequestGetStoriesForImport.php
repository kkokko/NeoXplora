<?php
  namespace TApp;
  class TRequestGetStoriesForImport extends \sky\TEntity {
    protected function DefineProperties(){
      parent::DefineProperties();
      $this->AddProperties(array(
        "SpecifiedIds" => array("check" => "string"),
        "IgnoredIds" => array("check" => "string"),
        "UserName" => array("check"=> "string"),
        "UserPassword" => array("check"=> "string")   
      ));
    }
  }
?>