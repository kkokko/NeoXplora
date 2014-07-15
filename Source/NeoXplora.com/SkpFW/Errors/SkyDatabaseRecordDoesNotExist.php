<?php
  namespace sky;

  class ESkyDatabaseRecordDoesNotExist extends \sky\ESkyException {
    public function __construct(){
      parent::DefineProperties();
    }
    public function GetMessage(){
      return TLanguage::$Instance->Translate("tlDatabaseRecordDoesNotExist");
    }
  }
?>