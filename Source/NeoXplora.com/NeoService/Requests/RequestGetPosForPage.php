<?php
  namespace TApp;
  class TRequestGetPosForPage extends \sky\TEntity{
    protected function DefineProperties(){
      parent::DefineProperties();
      $this->AddProperties(array(
        "Page" => array("check" => "string"),
        "UseModifiedPos" => array("check" => "Boolean")
      ));
    }
  }
?>