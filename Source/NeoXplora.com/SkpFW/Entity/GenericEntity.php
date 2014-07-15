<?php
  namespace sky;

  class TGenericEntity extends TEntity{
    public function GetProperty($APropertyName){
      if(!array_key_exists($APropertyName, $this->Properties)){
        return "";
      }
      $TheProperty = $this->Properties[$APropertyName];
      return $TheProperty->GetValue($this);      
    }

    public function GetPropertyCheck($APropertyName){
      if(!array_key_exists($APropertyName, $this->Properties)){
        return "string";
      }
      $TheProperty = $this->Properties[$APropertyName];
      return $TheProperty->GetCheck();      
    }
       
    public function SetProperty($APropertyName, $AValue){
      if(!array_key_exists($APropertyName, $this->Properties)){
        switch(TRUE) {
          case is_bool($AValue):
            $this->AddProperties(array($APropertyName => array("check" => "Boolean")));
            break;
          case is_int($AValue):
            $this->AddProperties(array($APropertyName => array("check" => "Integer")));
            break;
          case is_float($AValue):
            $this->AddProperties(array($APropertyName => array("check" => "Float")));
            break;
          case $AValue instanceof TEntity:
            $this->AddProperties(array($APropertyName => array("check" => "TEntity")));
            break;
          case TDateTimeProperty::IsValidDateTime($AValue):
            $this->AddProperties(array($APropertyName => array("check" => "TDateTime")));
            break;
          case TDateProperty::IsValidDate($AValue):
            $this->AddProperties(array($APropertyName => array("check" => "TDate")));
            break;
          default:
            $this->AddProperties(array($APropertyName => array("check" => "string")));
            break;
        }
      }
      $TheProperty = $this->Properties[$APropertyName];
      $TheProperty->SetValue($this, $AValue);      
    }
  }
?>