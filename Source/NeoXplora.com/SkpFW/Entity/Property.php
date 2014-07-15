<?php
  namespace sky;
  class TProperty{
    public static function CreateProperty($ACheck){
      switch ($ACheck) {
        case 'Boolean': return new TBooleanProperty;
        case 'TDate': return new TDateProperty;
        case 'TDateTime': return new TDateTimeProperty;
        case 'TEntityList': return new TEntityListProperty;
        case 'TSkyIdList': return new TSkyIdListProperty;
        case 'TSkyStringList': return new TSkyStringListProperty;
        case 'TSkyStringStringList': return new TSkyStringStringListProperty;
        case 'Float': return new TFloatProperty;
        case 'Integer': return new TIntegerProperty;
        case 'string': return new TStringProperty;
        default:
          return new TEntityProperty;
      }
    }
    protected $Value;
    protected $Getter;
    protected $Setter;
    public function Initialize($ACheck, $AnInit, $AClassInstance, $AGetter, $ASetter){
      if(isset($AnInit)){
        $this->SetValue($AClassInstance, $AnInit);
      } else {
        $this->SetDefaultValue();
      }
      $this->Setter = $ASetter;
      $this->Getter = $AGetter;
    }
    
    public function GetValue($AClassInstance){
      if(isset($this->Getter)){
        $TheFunctionName = $this->Getter;         
        return $AClassInstance->$TheFunctionName($this->Value);        
      }else{
        return $this->Value;
      }           
    }
    
    public function CompareWith($AnotherProperty, $AClassInstance, $AnotherClassInstance){
      $TheValue1 = $this->GetValue($AClassInstance);
      $TheValue2 = $AnotherProperty->GetValue($AnotherClassInstance);
      if($TheValue1 < $TheValue2) {
          return -1;
      }
      if($TheValue1 > $TheValue2) {
          return 1;
      }
      return 0;
    }
    
    protected function DoApply($AClassInstance, $AValue){
      if(isset($this->Setter)){
        $TheFunctionName = $this->Setter;
        $TheNewValue = $AValue;         
        $AClassInstance->$TheFunctionName($TheNewValue);
        return $TheNewValue;
      }else{
        return $AValue;
      }            
    }
        
    protected function SetDefaultValue(){} // override in inherited
  }
  
  class TBooleanProperty extends TProperty{
    public function SetValue($AClassInstance, $AValue){
      $TheValue = $this->DoApply($AClassInstance, $AValue);
      if(!is_bool($TheValue)){
        throw new \Exception("TBooleanProperty.SetValue: ".$TheValue." is not valid.");
      }
      $this->Value = $TheValue;            
    }
    protected function SetDefaultValue(){
      $this->Value = FALSE;            
    }
    public function GetCheck(){
      return "Boolean";
    }
  }

  class TDateProperty extends TProperty{
    public function SetValue($AClassInstance, $AValue){
      $TheValue = $this->DoApply($AClassInstance, $AValue);
      if(!isset($TheValue)){
        $this->SetDefaultValue();
        return;
      }
      if(!is_string($TheValue)){
        throw new \Exception("TDateProperty.SetValue: ".$TheValue." is not valid.");
      }
      if(!TDateProperty::IsValidDate($TheValue)){
        throw new \Exception("TDateProperty.SetValue: ".$TheValue." is not valid.");
      }
      $this->Value = $TheValue;            
    }
    static public function IsValidDate($ADate){
      return is_string($ADate) and (("" == $ADate) or (date('Y-m-d', strtotime($ADate)) == $ADate));
    }
    protected function SetDefaultValue(){
      $this->Value = "";
    }
    public function GetCheck(){
      return "TDate";
    }
  }

  class TDateTimeProperty extends TProperty{
    public function SetValue($AClassInstance, $AValue){
      $TheValue = $this->DoApply($AClassInstance, $AValue);
      if(!isset($TheValue)){
        $this->SetDefaultValue();
        return;
      }      
      if(!is_string($TheValue)){
        throw new \Exception("TDateTimeProperty.SetValue: ".$TheValue." is not valid.");
      }
      if(!TDateTimeProperty::IsValidDateTime($TheValue)){
        throw new \Exception("TDateTimeProperty.SetValue: ".$TheValue." is not valid.");
      }
      $this->Value = $AValue;            
    }
    static public function IsValidDateTime($AValue){
      return is_string($AValue) and (("" == $AValue) or (date('Y-m-d H:i:s', strtotime($AValue)) == $AValue));
    }
    protected function SetDefaultValue(){
      $this->Value = "";
    }
    public function GetCheck(){
      return "TDateTime";
    }
  }

  class TEntityListProperty extends TProperty{
    public function Initialize($ACheck, $AnInit, $AClassInstance, $AGetter, $ASetter){
      if(isset($AnInit) || isset($AGetter) || isset($ASetter)){
        throw new \Exception("TEntityListProperty.Initialize: Init/Get/Set values not allowed");
      }
      require_once "EntityList.php";
      $this->Value = new TEntityList;
    }

    public function CompareWith($AnotherProperty, $AClassInstance, $AnotherClassInstance){
      $TheValue1 = $this->GetValue($AClassInstance);
      $TheValue2 = $AnotherProperty->GetValue($AnotherClassInstance);
      return $TheValue1->CompareWith($TheValue2); 
    }
    
    public function SetValue($AClassInstance, $AValue){
      throw new \Exception("TEntityListProperty.SetValue: Not allowed");
    }
    public function GetCheck(){
      return "TEntityList";
    }
  }

  class TEntityProperty extends TProperty{
    private $FCheck;
    
    public function Initialize($ACheck, $AnInit, $AClassInstance, $AGetter, $ASetter){
      parent::Initialize($ACheck, $AnInit, $AClassInstance, $AGetter, $ASetter);
      $this->FCheck = $ACheck;
    }
    
    public function CompareWith($AnotherProperty, $AClassInstance, $AnotherClassInstance){
      $TheValue1 = $this->GetValue($AClassInstance);
      $TheValue2 = $AnotherProperty->GetValue($AnotherClassInstance);
      return $TheValue1->CompareWith($TheValue2); 
    }

    public function SetValue($AClassInstance, $AValue){
      $TheValue = $this->DoApply($AClassInstance, $AValue);     
      $TheCheck = TEntity::GetFullClassName($this->FCheck);
      if(isset($TheValue) && (!($TheValue instanceof $TheCheck))){
        throw new \Exception("TEntityProperty.SetValue: ".print_r($TheValue, true)." is not an entity.");
      }
      $this->Value = $TheValue;            
    }

    protected function SetDefaultValue(){
      $this->Value = null;
    }
    public function GetCheck(){
      return "TEntity";
    }
  }

  class TFloatProperty extends TProperty{
    public function SetValue($AClassInstance, $AValue){
      $TheValue = $this->DoApply($AClassInstance, $AValue);
      if(!isset($TheValue)){
        $this->SetDefaultValue();
        return;
      }      
      if(!is_numeric($TheValue)){
        throw new \Exception("TFloatProperty.SetValue: ".$TheValue." is not a valid Float.");
      }
      $this->Value = $TheValue;            
    }
    protected function SetDefaultValue(){
      $this->Value = 0;
    }
    public function GetCheck(){
      return "Float";
    }
  }

  class TIntegerProperty extends TProperty{
    public function SetValue($AClassInstance, $AValue){
      $TheValue = $this->DoApply($AClassInstance, $AValue);
      if(!isset($TheValue)){
        $this->SetDefaultValue();
        return;
      }      
      if(!is_int($TheValue)){
        throw new \Exception("TIntegerProperty.SetValue: ".$TheValue." is not valid.");
      }
      $this->Value = $TheValue;            
    }
    protected function SetDefaultValue(){
      $this->Value = 0;
    }
    public function GetCheck(){
      return "Integer";
    }
  }

  class TStringProperty extends TProperty{
    public function SetValue($AClassInstance, $AValue){
      $TheValue = $this->DoApply($AClassInstance, $AValue);
      if(!isset($TheValue)){
        $this->SetDefaultValue();
        return;
      }
      if(!is_string($TheValue)){
        throw new \Exception("TStringProperty.SetValue: value is not valid.");
      }
      $this->Value = $TheValue;            
    }
    protected function SetDefaultValue(){
      $this->Value = "";
    }
    public function GetCheck(){
      return "string";
    }
  }
  
  class TSkyIdListProperty extends TProperty{
    public function Initialize($ACheck, $AnInit, $AClassInstance, $AGetter, $ASetter){
      if(isset($AnInit) || isset($AGetter) || isset($ASetter)){
        throw new \Exception("TSkyIdListProperty.Initialize: Init/Get/Set values not allowed");
      }
      require_once "SkyIdList.php";
      $this->Value = new TSkyIdList;
    }

    public function CompareWith($AnotherProperty, $AClassInstance, $AnotherClassInstance){
      $TheValue1 = $this->GetValue($AClassInstance);
      $TheValue2 = $AnotherProperty->GetValue($AnotherClassInstance);
      return $TheValue1->CompareWith($TheValue2); 
    }
    
    public function SetValue($AClassInstance, $AValue){
      throw new \Exception("TEntityListProperty.SetValue: Not allowed");
    }
    public function GetCheck(){
      return "TSkyIdList";
    }
  }
  
  class TSkyStringListProperty extends TProperty{
    public function Initialize($ACheck, $AnInit, $AClassInstance, $AGetter, $ASetter){
      if(isset($AnInit) || isset($AGetter) || isset($ASetter)){
        throw new \Exception("TSkyStringListProperty.Initialize: Init/Get/Set values not allowed");
      }
      require_once "SkyStringList.php";
      $this->Value = new TSkyStringList;
    }

    public function CompareWith($AnotherProperty, $AClassInstance, $AnotherClassInstance){
      $TheValue1 = $this->GetValue($AClassInstance);
      $TheValue2 = $AnotherProperty->GetValue($AnotherClassInstance);
      return $TheValue1->CompareWith($TheValue2); 
    }
    
    public function SetValue($AClassInstance, $AValue){
      throw new \Exception("TSkyStringListProperty.SetValue: Not allowed");
    }
    public function GetCheck(){
      return "TSkyStringList";
    }
  }
  
  class TSkyStringStringListProperty extends TProperty{
    public function Initialize($ACheck, $AnInit, $AClassInstance, $AGetter, $ASetter){
      if(isset($AnInit) || isset($AGetter) || isset($ASetter)){
        throw new \Exception("TSkyStringStringListProperty.Initialize: Init/Get/Set values not allowed");
      }
      require_once "SkyStringStringList.php";
      $this->Value = new TSkyStringStringList;
    }
    
    public function CompareWith($AnotherProperty, $AClassInstance, $AnotherClassInstance){
      $TheValue1 = $this->GetValue($AClassInstance);
      $TheValue2 = $AnotherProperty->GetValue($AnotherClassInstance);
      return $TheValue1->CompareWith($TheValue2); 
    }
    
    public function SetValue($AClassInstance, $AValue){
      throw new \Exception("TSkyStringStringListProperty.SetValue: Not allowed");
    }
    
    public function GetCheck(){
      return "TSkyStringStringList";
    }
  }
  
?>
