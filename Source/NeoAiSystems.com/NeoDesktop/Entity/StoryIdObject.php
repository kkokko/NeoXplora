<?php
  namespace TApp;
  class TStoryIdObject extends \sky\TEntity {
    protected function DefineProperties(){
      parent::DefineProperties();
      $this->AddProperties(array(
        "SQLiteId" => array("check" => "Integer"),
        "MySQLId" => array("check" => "Integer")
      ));
    }
  }
?>