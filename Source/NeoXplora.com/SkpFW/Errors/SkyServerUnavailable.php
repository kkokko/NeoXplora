<?php
  namespace sky;

  class ESkyServerUnavailable extends \sky\ESkyException {
    public function __construct(){
      parent::DefineProperties();
    }
    public function GetMessage(){
      return TLanguage::$Instance->Translate("tlServerUnavailable");
    }
  }
?>