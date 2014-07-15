<?php
  namespace sky;

  class ESkyInvalidUserOrPassword extends \sky\ESkyException {
    public function __construct(){
      parent::DefineProperties();
    }
    public function GetMessage(){
      return TLanguage::$Instance->Translate("tlInvalidUserOrPassword");
    }
  }
?>