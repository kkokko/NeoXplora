<?php
  namespace sky;
  class TEntity {
    public $Properties = array();
    private static $Classes = array();
    
    public function __construct(){
      $this->DefineProperties();
    }
      
    protected function AddProperties($SomeProperties){
      if(!is_array($SomeProperties)){
        throw new \Exception("TEntity.AddProperties: Array of Array expected");
      }
      
      foreach($SomeProperties as $TheName => $TheKey){
        if(!is_string($TheName)){
          throw new \Exception("TEntity.AddProperties: Invalid property name");
        }
        if(!is_array($TheKey)){
          throw new \Exception("TEntity.AddProperties: Array expected");
        }
        $TheCheck = $TheKey["check"];
        if (array_key_exists('init', $TheKey)){
          $TheInit = $TheKey["init"];
        } else {
          $TheInit = null;
        }
        if (array_key_exists('get', $TheKey)){
          $TheGetter = $TheKey["get"];
        } else {
          $TheGetter = null;
        }
        if (array_key_exists('set', $TheKey)){
          $TheSetter = $TheKey["set"];
        } else {
          $TheSetter = null;
        }
        require_once "Property.php";
        $TheProperty = \sky\TProperty::CreateProperty($TheCheck);
        if ($TheProperty instanceof TEntityProperty){
          if(!TEntity::IsClassRegistered($TheCheck)){
            throw new \Exception("Class not registered: ".$TheCheck);
          }
        }
        $TheProperty->Initialize($TheCheck, $TheInit, $this, $TheGetter, $TheSetter);
        $this->Properties[$TheName] = $TheProperty; 
      }     
    }
        
    protected function DefineProperties(){
//    $this->AddProperties(array(
//      "name" => array("check" => "<DataType>", "init" => "<initial value>", 
//      "get" => "<getter method name>", "set" => "<setter method name>")
//    ));
    }
    
    public function GetProperty($APropertyName){
      if(!array_key_exists($APropertyName, $this->Properties)){
        throw new \Exception(get_class().".GetProperty(".$APropertyName."): property does not exist.");
      }
      $TheProperty = $this->Properties[$APropertyName];
      return $TheProperty->GetValue($this);      
    }

    public function GetPropertyCheck($APropertyName){
      if(!array_key_exists($APropertyName, $this->Properties)){
        throw new \Exception(get_class().".GetPropertyCheck(".$APropertyName."): property does not exist.");
      }
      $TheProperty = $this->Properties[$APropertyName];
      return $TheProperty->GetCheck();      
    }
      
    public function CompareWith($AnEntity){
      foreach ($AnEntity->Properties as $TheKey => $TheField){
        if (!array_key_exists($TheKey, $this->Properties)) {
          return 1;
        }
        $TheResult = $TheField->CompareWith($this->Properties[$TheKey], $AnEntity, $this);
        if($TheResult != 0)
          return $TheResult;    
      }
      return 0;
    }
       
    public function SetProperty($APropertyName, $AValue){
      if(!array_key_exists($APropertyName, $this->Properties)){
        throw new \Exception(get_class().".GetProperty(".$APropertyName."): property does not exist.");
      }
      $TheProperty = $this->Properties[$APropertyName];
      $TheProperty->SetValue($this, $AValue);      
    }
        
    // methods for registering classes
    public static function CreateClassWithName($AClassName){
      $TheClassInfo = TEntity::$Classes[$AClassName];
      if(!isset($TheClassInfo)){
        throw new \Exception("Class not registered: ".$AClassName);
      }
      require_once $TheClassInfo["FileName"];
      $TheClassName = "\\".$TheClassInfo["NameSpace"]."\\".$AClassName;
      return new $TheClassName; 
    }
    
    public function GetShortClassName(){
      return \join('', \array_slice(explode('\\', \get_class($this)), -1));
    }

    public static function GetFullClassName($AClassName){
      $TheClassInfo = TEntity::$Classes[$AClassName];
      if(!isset($TheClassInfo)){
        throw new \Exception("Class not registered: ".$AClassName);
      }
      $TheClassName = "\\".$TheClassInfo["NameSpace"]."\\".$AClassName;
      return $TheClassName; 
    }    
    
    public static function IsClassRegistered($AClassName){
      return array_key_exists($AClassName, TEntity::$Classes);
    }

    public static function RegisterClass($AClassName, $ANameSpace, $AFileName){
      TEntity::$Classes[$AClassName] = array("NameSpace" => $ANameSpace, "FileName" => $AFileName);
    }
    
    public function ToJson() {
      require_once "Streaming/EntityStreamWriter.php";
      $TheObject = TEntityStreamWriter::WriteEntity($this);
      return json_encode($TheObject);
    }
}
?>