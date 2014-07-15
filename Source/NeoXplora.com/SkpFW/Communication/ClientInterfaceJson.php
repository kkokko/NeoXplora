<?php 
  namespace sky;
  
  require_once $GLOBALS["SkyFrameworkPath"]."Communication/ClientInterface.php";
  class TClientInterfaceJson extends TClientInterface{
    // input: string, returns TEntity
    function ReadRequestFromStream($AStream){
      $TheJsonObject = json_decode($AStream);
      if(JSON_ERROR_NONE!=json_last_error()){
        require_once $GLOBALS["SkyFrameworkPath"]."Errors/SkyException.php";
        throw new \sky\TSkyThrowable(new ESkyServerUnknownException($this, "ReadRequestFromStream", "JSON Stream decoding failed"));
        return;
      }
      require_once($GLOBALS["SkyFrameworkPath"]."/Entity/Streaming/EntityStreamReader.php");
      return TEntityStreamReader::ReadEntity($TheJsonObject);
    }
    
    // input: TEntity, returns string
    function WriteRequestToStream($AnEntity){
      require_once($GLOBALS["SkyFrameworkPath"]."/Entity/Streaming/EntityStreamWriter.php");
      $TheObject = \sky\TEntityStreamWriter::WriteEntity($AnEntity);
      return json_encode($TheObject);
    }
  }
?>