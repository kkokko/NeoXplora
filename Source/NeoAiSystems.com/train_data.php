<?php
  session_start();
  define("_VALID_PHP", true);
  require_once ("init.php");
  require_once "config_storydb.php"; 
  $link = mysql_connect($configuration['host'], $configuration['user'], $configuration['pass']);
  mysql_set_charset("utf8", $link);
  mysql_select_db($configuration['db'], $link);
  
  $db = new mysqli($configuration['host'], $configuration['user'], $configuration['pass'], $configuration['db']);
  $db->set_charset("utf8");

  if($db->connect_errno > 0){
    die('Unable to connect to database [' . $db->connect_error . ']');
  }
  
  
  $type = isset($_POST['type'])?$_POST['type']:"";
  $action = isset($_POST['action'])?$_POST['action']:"";
  
  if(file_exists("Logic/Train/" . $type . ".php")) {
    require_once("Logic/Train/" . $type . ".php");
  } else {
    die("Wrong request type");
  }
  
  $type = "T" . $type;
  
  $request = new $type($db);
  
  if(!method_exists($request, $action)) {
    die("Wrong action type");
  }
  
  $request->$action();

?>