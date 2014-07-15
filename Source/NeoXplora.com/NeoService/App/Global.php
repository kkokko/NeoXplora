<?php
  namespace TApp;
  require_once __DIR__ . "/../Conf/Config.php";
  require_once __DIR__ . "/AppClientInterface.php";
  
  $scriptname = basename($_SERVER["SCRIPT_FILENAME"]);
  $server = new TAppClientInterface();
  
?>