<?php
  namespace sky;
  class TEntityStreamReader{
    public static function ReadEntity($AnObject){
      if((!isset($AnObject->ClassName))||(!is_string($AnObject->ClassName))){
        throw new \Exception("Invalid request: ClassName field not found.");
      }
      if((!isset($AnObject->Properties))||(!is_object($AnObject->Properties))){
        throw new \Exception("Invalid request: Properties field not found.");
      }
      $TheEntity = TEntity::CreateClassWithName($AnObject->ClassName);
      foreach($AnObject->Properties as $TheKey => $TheValue){
        $TheFieldKind = $TheEntity->GetPropertyCheck($TheKey);
        switch ($TheFieldKind) {
          case 'Boolean':
            $TheEntity->SetProperty($TheKey, strcasecmp (''.$TheValue, 'True') == 0);
            break;
          case 'Float':
            $TheEntity->SetProperty($TheKey, floatval(''.$TheValue));
            break;
          case 'Integer':
            $TheEntity->SetProperty($TheKey, intval(''.$TheValue));
            break;
          case 'TEntityList':
            $TheCount = count($TheValue);
            for($i = 0; $i < $TheCount; $i++){
                $TheListItem = self::ReadEntity($TheValue[$i]);
                $TheEntity->GetProperty($TheKey)->Add($TheListItem);
            }
            break;
          case 'TSkyIdList':
            $TheList = $TheEntity->GetProperty($TheKey);
            self::ReadSkyIdList($TheList, $TheValue);
            break;
          case 'TSkyStringList':
            $TheList = $TheEntity->GetProperty($TheKey);
            self::ReadSkyStringList($TheList, $TheValue);
            break;
          case 'TSkyStringStringList':
            $TheList = $TheEntity->GetProperty($TheKey);
            self::ReadSkyStringStringList($TheList, $TheValue);
            break;
          case 'TEntity':
            $TheEntity->SetProperty($TheKey, self::ReadEntity($TheValue));
            break;
          default:
            $TheEntity->SetProperty($TheKey, $TheValue);
            break;
        } 
      }
      return $TheEntity;
    }
    
    private static function ReadSkyIdList(&$AList, $AValue){
      $TheValueCount = count($AValue->Values);
      for($I = 0; $I < $TheValueCount; $I++){
        $TheId = $AValue->Values[$I]->Id;
        $TheObject = null;
        if(property_exists($AValue->Values[$I], "Object")) {
          $TheObject = $AValue->Values[$I]->Object;
          if($TheObject){
            $TheObject = self::ReadObject($TheObject);
          }
        }
        $AList->Add($TheId, $TheObject);
      }
    }
    
    private static function ReadSkyStringList(&$AList, $AValue){
      $TheValueCount = count($AValue->Values);
      for($I = 0; $I < $TheValueCount; $I++){
        $TheKey = $AValue->Values[$I]->Key;
        $TheObject = null;
        if(property_exists($AValue->Values[$I], "Object")) {
          $TheObject = $AValue->Values[$I]->Object;
          if($TheObject){
            $TheObject = self::ReadObject($TheObject); 
          }
        }
        $AList->Add($TheKey, $TheObject);
      }
    }
    
    private static function ReadSkyStringStringList(&$AList, $AValue){
      $TheValueCount = count($AValue->Values);
      for($I = 0; $I < $TheValueCount; $I++){
        $TheKey = $AValue->Values[$I]->Key;
        $TheObject = null;
        if(property_exists($AValue->Values[$I], "Object")) {
          $TheObject = $AValue->Values[$I]->Object;
          if($TheObject){
            if(!is_string($TheObject)) {
              throw new \Exception("TSkyStringStringList.Object: value is not a string.");
            }
          }
        }
        $AList->Add($TheKey, $TheObject);
      }
    }
    
    private static function ReadObject($AnObject){
      if((!isset($AnObject->ClassName))||(!is_string($AnObject->ClassName))){
        throw new \Exception("Invalid request: ClassName field not found.");
      }
      switch($AnObject->ClassName){
        case 'TSkyIdList':
          require_once __DIR__ . "/../SkyIdList.php";
          $TheList = new TSkyIdList();
          self::ReadSkyIdList($TheList, $AnObject);
          return $TheList;
          break;
        case 'TSkyStringList':
          require_once __DIR__ . "/../SkyStringList.php";
          $TheList = new TSkyStringList();
          self::ReadSkyStringList($TheList, $AnObject);
          return $TheList;
          break;
        case 'TSkyStringStringList':
          require_once __DIR__ . "/../SkyStringStringList.php";
          $TheList = new TSkyStringStringList();
          self::ReadSkyStringStringList($TheList, $AnObject);
          return $TheList;
          break;
        default:
          return self::ReadEntity($AnObject);
      }
    }
  } 
?>