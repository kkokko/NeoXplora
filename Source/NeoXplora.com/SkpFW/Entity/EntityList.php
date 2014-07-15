<?php
  namespace sky;
  class TEntityList{
    public $Ascending = TRUE; // sort type
    private $Entities = array();
    public $SortFields = array();
    
    public function Add($AnEntity, $AnObject = NULL){
      $this->Entities[] = array("Entity" => $AnEntity, "Object" => $AnObject);
    }
    
    public function AddEntities($SomeEntities, $SomeObjects = NULL){
      $TheCount = count($SomeEntities);
      for($i = 0; $i < $TheCount; $i++){
        if($SomeObjects){
          $this->Add($SomeEntities[$i], $SomeObjects[$i]);
        } else {
          $this->Add($SomeEntities[$i]);
        }
      }
    }
    
    public function Clear(){
      unset($this->Entities);
      $this->Entities = array();
    }
    
    public function Count(){
      return count($this->Entities);
    }
    
    public function GetEntityById($AnId){
      $TheCount = $this->Count();
      for($i = 0; $i < $TheCount; $i++){
        $TheItem = $this->Item($i);
        if($TheItem->GetProperty("Id") == $AnId){
          return $TheItem;
        }
      }
      return null;
    }
    
    public function GetAllEntities(){
      $TheResult = array();
      $TheCount = $this->Count();
      for($i = 0; $i < $TheCount; $i++){
        $TheResult[] = $this->Item($i);
      }
      return $TheResult;
    }
    
    public function Item($AnIndex){
      return $this->Entities[$AnIndex]["Entity"];
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
      
      $TheOwnEntities = TEntityList::SortEntities(
        $this->GetAllEntities(),
        array('Id')
      );
      $TheOtherEntities = TEntityList::SortEntities(
        $AnotherList->GetAllEntities(),
        array('Id')
      );
      for($i = 0; $i < $ThisCount; $i++){
        $TheCompare = $TheOwnEntities[$i]->CompareWith($TheOtherEntities[$i]);
        if($TheCompare != 0) {
          return $TheCompare;
        }
      } 
      return 0;
    }
    
    public function Object($AnIndex){
      return $this->Entities[$AnIndex]["Object"];
    }
    
    public function Sort(){
      uksort($this->Entities, array($this, "DoSort"));
    }
    
    public static function SortEntities($SomeEntities, $SomeFields, $Ascending = TRUE){
      $TheInstance = new TEntityList();
      $TheInstance->AddEntities($SomeEntities);
      $TheInstance->SortFields = $SomeFields;
      $TheInstance->Ascending = $Ascending;
      $TheInstance->Sort();
      $TheResults = $TheInstance->GetAllEntities();
      unset($TheInstance);
      return $TheResults;
    }

    private function DoSort($AnEntity1, $AnEntity2){
      if($this->$Ascending){
        return $AnEntity1->CompareWith($AnEntity2, $this->$SortFields);        
      }
    }    
  }
?>