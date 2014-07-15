<?php
  namespace sky;
  class TClientRequestLogin extends \sky\TEntity{
    protected function DefineProperties(){
      parent::DefineProperties();
      $this->AddProperties(array(
        "User" => array("check" => "TBasicUserData")
      ));
    }
    function __construct($ABasicUserData){
      parent::__construct();
      $this->SetProperty("User", $ABasicUserData);
    }
  }
?>
