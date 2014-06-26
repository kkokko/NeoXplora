<?php
  namespace TApp;
  class TRequestGetImportInfo extends \sky\TEntity {
    protected function DefineProperties(){
      parent::DefineProperties();
      $this->AddProperties(array(        
        "ImportIds" => array("check" => "string"),
        "UserName" => array("check"=> "string"),
        "UserPassword" => array("check"=> "string"),
        "SendIdList" =>  array("check"=> "Boolean")  
      ));
    }
  }
?>