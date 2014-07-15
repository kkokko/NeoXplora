<?php
  namespace TApp;
  class TSearchPage extends \sky\TEntity {
    protected function DefineProperties(){
      parent::DefineProperties();
      $this->AddProperties(array(
        "Id" => array("check" => "Integer"), 
        "Title" => array("check" => "string"),
        "Body" => array("check" => "string"),
        "Link" => array("check" => "string")
      ));
    }
  }
?>