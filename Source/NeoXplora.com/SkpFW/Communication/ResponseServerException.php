<?php
  namespace sky;

  class TResponseServerException extends \sky\TEntity{
    function __construct($AnException=null){
      parent::__construct();
      $this->SetProperty("Exception", $AnException);
      if($AnException != null) {
        $this->SetProperty("Message", $AnException->GetMessage());
      }
    }
    protected function DefineProperties(){
      parent::DefineProperties();
      $this->AddProperties(array(
        "Exception" => array("check" => "TEntity"),
        "Message" => array("check" => "string")
      ));
    }
  }
?>