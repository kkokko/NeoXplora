<?php
  namespace sky;
  
  require_once $GLOBALS["SkyFrameworkPath"]."Translations/Language.php";
  if(!is_object(TLanguage::$Instance)) TLanguage::$Instance = new TLanguage();
  
  class TSkyThrowable extends \Exception{
    public function __construct($AnException){
      $this->Exception = $AnException;
      parent::__construct($AnException->GetMessage(), 0, null);
      $this->Exception->SetProperty("EntityClassName", $this->Exception->GetShortClassName());
    }
    protected function GetErrorClass(){
      return "TSkyError";
    }
  }
  
  class ESkyException extends TEntity{
    public function __construct($ARaisedBy, $ALocation){
      parent::__construct();
      require_once $GLOBALS["SkyFrameworkPath"]."Entity/GenericEntity.php";
      $this->SetProperty("EntityClassName", $this->GetShortClassName());
      $this->SetProperty("DateTime", date('Y-m-d H:i:s'));
      if(\TApp\TConfig::$TestRequest){
        $this->SetProperty("DateTime", '2013-01-01 00:00:00');
      }
      $TheParams = new TGenericEntity();
      $TheParams->SetProperty("Message", "tlError");
      $this->SetProperty("Params", $TheParams);
      $this->SetProperty("MessageType", "lmtError");
      if(isset($ARaisedBy)){
        $TheParams->SetProperty("RaisedBy", get_class($ARaisedBy));
      } else {
        $TheParams->SetProperty("RaisedBy", "");
      }
      $TheParams->SetProperty("Location", $ALocation);
      $TheParams->SetProperty("Message", $this->GetMessage());
    }
    public function GetMessage(){
      return $this->GetProperty("Params")->GetProperty("Message");
    }
    protected function DefineProperties(){
      parent::DefineProperties();
      $this->AddProperties(array(
        "EntityClassName" => array("check" => "string"),
        "DateTime" => array("check" => "TDateTime"),
        "Params" => array("check" => "TGenericEntity"),
        "MessageType" => array("check" => "string")
      ));
    }
  }
  
  class ESkyServerUnknownException extends ESkyException{
    public function __construct($ARaisedBy = null, $ALocation = "", $AnError = ""){
      parent::__construct($ARaisedBy, $ALocation);
      $TheParams = $this->GetProperty("Params");
      $TheParams->SetProperty("Error", $AnError);
    }
    public function GetMessage(){
      return TLanguage::$Instance->Translate("tlServerUnknownException") . ": " . $this->GetProperty("Params")->GetProperty("Error");
    }
  }  
?>