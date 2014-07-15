<?php
  namespace sky;
  class TSkyStringList{
    public $Ascending = TRUE; // sort type
    private $Values = array();
    
    public function Add($AKey, $AnObject = NULL){
      $this->Values[] = array("Key" => $AKey, "Object" => $AnObject);
    }
    
    public function AddValues($SomeValues, $SomeObjects = NULL){
      $TheCount = count($SomeValues);
      for($i = 0; $i < $TheCount; $i++){
        if($SomeObjects){
          $this->Add($SomeValues[$i], $SomeObjects[$i]);
        } else {
          $this->Add($SomeValues[$i]);
        }
      }
    }
    
    public function Clear(){
      unset($this->Values);
      $this->Values = array();
    }
    
    public function Count(){
      return count($this->Values);
    }
    
    public function GetAllValues(){
      $TheResult = array();
      $TheCount = $this->Count();
      for($i = 0; $i < $TheCount; $i++){
        $TheResult[] = $this->Item($i);
      }
      return $TheResult;
    }
    
    public function Item($AnIndex){
      return $this->Values[$AnIndex]["Key"];
    }
    
    public function CompareWith($AnotherList){
      $ThisCount = $this->Count();
      $ThatCount = $AnotherList->Count();
      if($ThisCount > $ThatCount){
        return 1;
      }
      if($ThisCount < $ThatCount){
        return -1;
      }
      
      $TheOwnValues = self::SortValues(
        $this->GetAllValues(),
        array('Key')
      );
      $TheOtherValues = self::SortValues(
        $AnotherList->GetAllValues(),
        array('Key')
      );
      for($i = 0; $i < $ThisCount; $i++){
        $TheCompare = $TheOwnValues[$i]->CompareWith($TheOtherValues[$i]);
        if($TheCompare != 0) {
          return $TheCompare;
        }
      } 
      return 0;
    }
    
    public function Object($AnIndex){
      return $this->Values[$AnIndex]["Object"];
    }
    
    public function Sort(){
      uksort($this->Values, array($this, "DoSort"));
    }
    
    public function Search($AValue) {
      $L = 0; $H = count($this->Values) - 1;
      while($L <= $H) {
        $TheCompare = strcmp($this->Values[$I]['Key'], $AValue);
        $I = ($L + $H) >> 1;
        if($TheCompare < 0) {
          $L = $I + 1;
        } else {
          $H = $I - 1;
          if($TheCompare == 0) {
            return $I;
          }
        } 
      }
      return -1;
    }
    
    public static function SortValues($SomeValues, $SomeFields, $Ascending = TRUE){
      $TheInstance = new TSkyStringList();
      $TheInstance->AddValues($SomeValues);
      $TheInstance->SortFields = $SomeFields;
      $TheInstance->Ascending = $Ascending;
      $TheInstance->Sort();
      $TheResults = $TheInstance->GetAllValues();
      unset($TheInstance);
      return $TheResults;
    }

    private function DoSort($AKey1, $AKey2) {
      $TheCompare = strcmp($AKey1, $AKey2);
      if($TheCompare < 0) {
        return -1;
      } else if($TheCompare > 0) {
        return 1;
      } else { 
        return 0;
      }
    }
      
  }
?>