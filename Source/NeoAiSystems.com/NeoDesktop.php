<?php 
  namespace TApp;
  require_once "NeoDesktop/Conf/Config.php";
  require_once $GLOBALS["SkyFrameworkPath"]."/Communication/RequestJson.php";

  $TheRequest = new \sky\TRequestJson;
  $TheRequest->Execute(TConfig::$RequestParams);  
?>