<?php
  namespace sky;
  class TBasicUserData extends TEntity {
    protected function DefineProperties(){
      parent::DefineProperties();
      $this->AddProperties(array(
        "Id" => array("check" => "Integer"), 
        "Name" => array("check" => "string"),
        "Password" => array("check" => "string"),
        "UserName" => array("check" => "string")
      ));
    }
    function __construct($AnUserName, $APassword){
      parent::__construct();
      $this->SetProperty("Password", $APassword);
      $this->SetProperty("UserName", $AnUserName);
    }
  }
?>