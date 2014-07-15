<?php
  namespace sky;

  class ESkyInvalidSession extends \sky\ESkyException {
    public function __construct(){
      parent::DefineProperties();
    }
    public function GetMessage(){
      return TLanguage::$Instance->Translate("tlInvalidSession");
    }
  }
?>