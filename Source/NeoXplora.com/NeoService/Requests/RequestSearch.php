<?php
  namespace TApp;
  class TRequestSearch extends \sky\TEntity{
    protected function DefineProperties(){
      parent::DefineProperties();
      $this->AddProperties(array(
        "SearchString" => array("check" => "string"),
        "Offset" => array("check" => "Integer")
      ));
    }
  }
?>