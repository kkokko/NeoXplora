<?php
  namespace sky;

  class ESkyDatabaseReferenceConstraint extends \sky\ESkyException {
    public function __construct(){
      parent::DefineProperties();
    }
    public function GetMessage(){
      $Action = TLanguage::$Instance->Translate($this->GetProperty("Params")->GetProperty("Action"));
      $Table = TLanguage::$Instance->Translate($this->GetProperty("Params")->GetProperty("Table"));
      return sprintf(TLanguage::$Instance->Translate("tlDatabaseReferenceConstraint"), $Action, $Table);
    }
  }
?>