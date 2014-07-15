<?php
  namespace sky;
  class TEntityStreamWriter{
    public static function WriteEntity($AnEntity){
      $Result = array();

      $Result["ClassName"] = $AnEntity->GetShortClassName();
      
      $TheProperties = new \stdClass();
      ksort($AnEntity->Properties);
      foreach ($AnEntity->Properties as $TheKey => $TheField){
        $TheProperties->$TheKey = TEntityStreamWriter::GetEntityFieldValue($TheField, $AnEntity);
      }
      $Result["Properties"] = $TheProperties;
      return $Result;
    }
    
    public static function WriteObject($AnObject) {
      if($AnObject instanceof TSkyIdList){
        return TEntityStreamWriter::WriteSkyIdList($AnObject);  
      } else if($AnObject instanceof TSkyStringList) {
        return TEntityStreamWriter::WriteSkyStringList($AnObject);
      } else if($AnObject instanceof TSkyStringStringList) {
        return TEntityStreamWriter::WriteSkyStringStringList($AnObject);
      } else {
        return TEntityStreamWriter::WriteEntity($AnObject);
      }
    }
    
    public static function WriteSkyStringList($AList){
      $TheResult = array();

      $TheResult["ClassName"] = "TSkyStringList";
      
      $TheCount = $AList->Count();
      for($i = 0; $i < $TheCount; $i++){
        $TheResult["Values"][$i]["Key"] = $AList->Item($i);
        $TheObject = $AList->Object($i);
        if($TheObject) {
          $TheResult["Values"][$i]["Object"] = TEntityStreamWriter::WriteObject($TheObject);  
        }
      }
      
      return $TheResult;
    }
    
    public static function WriteSkyStringStringList($AList){
      $TheResult = array();

      $TheResult["ClassName"] = "TSkyStringStringList";
      
      $TheCount = $AList->Count();
      for($i = 0; $i < $TheCount; $i++){
        $TheResult["Values"][$i]["Key"] = $AList->Item($i);
        $TheObject = $AList->Object($i);
        if($TheObject) {
          $TheResult["Values"][$i]["Object"] = TEntityStreamWriter::WriteObject($TheObject);  
        }
      }
      
      return $TheResult;
    }
    
    public static function WriteSkyIdList($AList){
      $TheResult = array();

      $TheResult["ClassName"] = "TSkyIdList";
      
      $TheCount = $AList->Count();
      for($i = 0; $i < $TheCount; $i++){
        $TheResult["Values"][$i]["Id"] = $AList->Item($i);
        $TheObject = $AList->Object($i);
        if($TheObject) {
          $TheResult["Values"][$i]["Object"] = TEntityStreamWriter::WriteObject($TheObject);  
        }
      }
      
      return $TheResult;
    }
    
    private static function GetEntityFieldValue($AField, $AnEntity){
      if($AField instanceof TBooleanProperty){
        if($AField->GetValue($AnEntity)){
          return "True";
        }else{
          return "False";
        }
      }
      if(($AField instanceof TFloatProperty) || ($AField instanceof TIntegerProperty)){
        return strval($AField->GetValue($AnEntity));
      }
      if($AField instanceof TEntityProperty){
        $TheEntity = $AField->GetValue($AnEntity);
        return TEntityStreamWriter::WriteEntity($TheEntity);
      }
      if($AField instanceof TEntityListProperty){
        $TheList = $AField->GetValue($AnEntity);
        $TheCount = $TheList->Count();
        $TheResult = array();
        for($i = 0; $i < $TheCount; $i++){
          $TheResult[] = TEntityStreamWriter::WriteEntity($TheList->Item($i));
        }
        return $TheResult;
      }
      if($AField instanceof TSkyIdListProperty) {
        $TheList = $AField->GetValue($AnEntity);
        $TheCount = $TheList->Count();
        $TheResult = array();
        for($i = 0; $i < $TheCount; $i++){
          $TheResult["Values"][$i]["Id"] = $TheList->Item($i);
          $TheObject = $TheList->Object($i);
          if($TheObject) {
            $TheResult["Values"][$i]["Object"] = TEntityStreamWriter::WriteObject($TheObject);  
          }
        }
        $TheResult["ClassName"] = "TSkyIdList";
        return $TheResult;
      }
      if($AField instanceof TSkyStringListProperty) {
        $TheList = $AField->GetValue($AnEntity);
        $TheCount = $TheList->Count();
        $TheResult = array();
        for($i = 0; $i < $TheCount; $i++){
          $TheResult["Values"][$i]["Key"] = $TheList->Item($i);
          $TheObject = $TheList->Object($i);
          if($TheObject) {
            $TheResult["Values"][$i]["Object"] = TEntityStreamWriter::WriteObject($TheObject);  
          }
        }
        $TheResult["ClassName"] = "TSkyStringList";
        return $TheResult;
      }
      if($AField instanceof TSkyStringStringListProperty) {
        $TheList = $AField->GetValue($AnEntity);
        $TheCount = $TheList->Count();
        $TheResult = array();
        for($i = 0; $i < $TheCount; $i++){
          $TheResult["Values"][$i]["Key"] = $TheList->Item($i);
          $TheObject = $TheList->Object($i);
          if($TheObject) {
            $TheResult["Values"][$i]["Object"] = TEntityStreamWriter::WriteObject($TheObject);  
          }
        }
        $TheResult["ClassName"] = "TSkyStringStringList";
        return $TheResult;
      }
      return $AField->GetValue($AnEntity);
    }
  } 
?>