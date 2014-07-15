<?php 
  namespace sky;
   
  class TRequestJson{
    public $TimeStamp = "";
    function __construct(){      
      $TheTimeOfDay = gettimeofday();           
      $this->TimeStamp =date("Ymd_His_", time()).$TheTimeOfDay['usec']."_";    
    }
    
    protected function WriteLog($AParams, $AFileName, $AData){
      $FileHandle = fopen($AParams->LogFilePath.$this->TimeStamp."_".$AFileName, 'w');
      fwrite($FileHandle, $AData);
      fclose($FileHandle);    
    }
    
    protected function CheckPost($AParams){
      if("POST" != $_SERVER['REQUEST_METHOD']){
        if($AParams->LogRequests){
          $TheMessage = "GET WAS RECEIVED \r\n"."REMOTE_ADDR:".$_SERVER['REMOTE_ADDR']."\r\n";
          if(isset($_SERVER['HTTP_X_FORWARDED_FOR'])){
            $TheMessage = $TheMessage."HTTP_X_FORWARDED_FOR".$_SERVER['HTTP_X_FORWARDED_FOR'];
          }
          $this->WriteLog($AParams, "InvalidRequest.Txt", $TheMessage);
        }
        \http_response_code(404);
        return false;
      }
      return true;      
    }
  
    public function Execute($AParams){
      if(!$this->CheckPost($AParams)){
        return;
      };
      try{
        $TheRequest = file_get_contents("php://input");
        $TheResponse = $this->ProcessRequest($TheRequest, $AParams);
      }catch (\Exception $e){
        $TheResponse = $this->HandleError($e);
      }
      $this->ProcessResponse($TheResponse, $AParams, false);
    }
    
    public function HandleError($AnError){  
      require_once $GLOBALS["SkyFrameworkPath"]."Errors/SkyException.php";
      if ($AnError instanceof TSkyThrowable){
        $TheError = $AnError->Exception;
      } else {
        $TheMessage = $AnError->getMessage().'<pre>'.$AnError->getTraceAsString().'</pre>';          
        $TheError = new ESkyServerUnknownException($this, "Connect", $TheMessage);
      }
      require_once $GLOBALS["SkyFrameworkPath"]."Communication/ResponseServerException.php";
      return new TResponseServerException($TheError);
    }  

    public function ProcessRequest($ARequest, $AParams){
      $TheJsonObject = json_decode($ARequest);
      if(JSON_ERROR_NONE!=json_last_error()){
        if($AParams->LogRequests){
          $this->WriteLog($AParams, "InvalidJson.Txt", $ARequest);
        }
        http_response_code(404);
        return;
      }
      require_once($GLOBALS["SkyFrameworkPath"]."/Entity/Streaming/EntityStreamReader.php");
      $TheRequest = '';
      try{
        $TheRequest = TEntityStreamReader::ReadEntity($TheJsonObject);
        if($AParams->LogRequests){
          $TheClassName = join('', array_slice(explode('\\', get_class($TheRequest)), -1));
          $this->WriteLog($AParams, $TheClassName.".json", $ARequest);
        }
      } catch(\Exception $e){
        if($AParams->LogRequests){
          $this->WriteLog($AParams, "InvalidJson.Txt", $ARequest."\r\n".$e->getMessage());
        }
        throw $e;
      }
      $TheCommandName = str_replace("T".$AParams->CommandPrefix, "Command", 
        join('', array_slice(explode('\\', get_class($TheRequest)), -1)));
      require_once $AParams->CommandFolder.$TheCommandName.".php";
      return call_user_func_array(array($AParams->AppNameSpace ."\T".$TheCommandName, "Execute"), array($TheRequest));
    }
    
    public function ProcessResponse($AResponse, $AParams, $TestMode){
      if(!isset($AResponse)){
        require_once($GLOBALS["SkyFrameworkPath"]."/Communication/Response.php");
        $TheResponse = new TResponse;
      } else {
        $TheResponse = $AResponse;
      }
      require_once($GLOBALS["SkyFrameworkPath"]."/Entity/Streaming/EntityStreamWriter.php");
      $TheObject = \sky\TEntityStreamWriter::WriteEntity($TheResponse);
      $TheResponseJson = json_encode($TheObject);
      if($AParams->LogRequests){
        $TheClassName = join('', array_slice(explode('\\', get_class($TheResponse)), -1));
        $this->WriteLog($AParams, $TheClassName.".json", $TheResponseJson);
      }
      if(false == $TestMode){
        $this->WriteResponse($TheResponseJson, $AParams);
      }
      return $TheResponseJson;
    }
    
    protected function WriteResponse($AResult, $AParams){
      echo $AResult;
    }    
  }
?>