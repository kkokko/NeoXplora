<?php
  namespace sky;
  class TClientResponseLoginCheck extends \sky\TEntity{
    protected function DefineProperties(){
      parent::DefineProperties();
      $this->AddProperties(array(
        "SessionId" => array("check" => "string"),
        "User" => array("check" => "TEntity")
      ));
    }
  }
?>
