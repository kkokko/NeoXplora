<?php
  namespace sky;
  class TSkyIdList{
    public $Ascending = TRUE; // sort type
    private $Values = array();
    
    public function Add($AnId, $AnObject = NULL){
      $this->Values[] = array("Id" => $AnId, "Object" => $AnObject);
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
      return $this->Values[$AnIndex]["Id"];
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
        array('Id')
      );
      $TheOtherValues = self::SortValues(
        $AnotherList->GetAllValues(),
        array('Id')
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
    
    public function Search($AnInt) {
      $L = 0; $H = count($this->Values) - 1;
      while($L <= $H) {
        $I = ($L + $H) >> 1;
        if($this->Values[$I]['Id'] < $AnInt) {
          $L = $I + 1;
        } else {
          $H = $I - 1;
          if($this->Values[$I]['Id'] == $AnInt) {
            return $I;
          }
        } 
      }
      return -1;
    }
    
    public static function SortValues($SomeValues, $SomeFields, $Ascending = TRUE){
      $TheInstance = new TSkyIdList();
      $TheInstance->AddValues($SomeValues);
      $TheInstance->SortFields = $SomeFields;
      $TheInstance->Ascending = $Ascending;
      $TheInstance->Sort();
      $TheResults = $TheInstance->GetAllValues();
      unset($TheInstance);
      return $TheResults;
    }

    private function DoSort($AnId1, $AnId2) {
      if($AnId1 > $AnId2){
        return 1;
      }
      if($AnId1 < $AnId1){
        return -1;
      }
      return 0;
    }    
  }
?>