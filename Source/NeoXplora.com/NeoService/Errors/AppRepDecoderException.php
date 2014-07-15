<?php
  namespace TApp;

  class EAppRepDecoderException extends \sky\ESkyException {
    public function __construct(){
      parent::DefineProperties();
      $this->AddProperties(array(
        "ErrorString" => array("check" => "string"),
        "StrIndex" => array("check" => "Integer")
      ));
    }
    public function GetMessage(){
      return $this->GetProperty("ErrorString");
      //return TLanguage::$Instance->Translate("tlMaximumTwoLevels");
    }
  }
?>