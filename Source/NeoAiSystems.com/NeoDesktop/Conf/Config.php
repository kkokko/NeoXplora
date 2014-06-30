<?php
  namespace TApp;
  abstract class TConfig{
    public static $LogFilePath = "Log/";
    public static $LogRequests = true;
    public static $TestRequest = false;
    public static $RequestParams;
    // public
    public static function Initialize(){     
      TConfig::$FAppFolder = str_replace('\\', '/', __DIR__)."/../";
      TConfig::$LogFilePath = TConfig::$FAppFolder . "Log/";
      $GLOBALS["SkyFrameworkPath"] = self::$FAppFolder . "../NeoShared/SkyPhp/";
      
      // register application entity classes
      $TheSkyEntityPath = $GLOBALS["SkyFrameworkPath"]."Entity";
      $TheAppEntityPath = TConfig::$FAppFolder . "Entity";
      require_once($TheSkyEntityPath."/Entity.php");
      require_once $GLOBALS["SkyFrameworkPath"]."Errors/SkyException.php";
      \sky\TEntity::RegisterClass("TEntity", "sky", $TheSkyEntityPath."/Entity.php");
      \sky\TEntity::RegisterClass("TGenericEntity", "sky", $TheSkyEntityPath."/GenericEntity.php");
      \sky\TEntity::RegisterClass("TStoryObject", __NAMESPACE__, $TheAppEntityPath."/StoryObject.php");
      \sky\TEntity::RegisterClass("TProtoObject", __NAMESPACE__, $TheAppEntityPath."/ProtoObject.php");
      \sky\TEntity::RegisterClass("TQAObject", __NAMESPACE__, $TheAppEntityPath."/QAObject.php");
      \sky\TEntity::RegisterClass("TSentenceObject", __NAMESPACE__, $TheAppEntityPath."/SentenceObject.php");
      \sky\TEntity::RegisterClass("TStoryIdObject", __NAMESPACE__, $TheAppEntityPath."/StoryIdObject.php");
      \sky\TEntity::RegisterClass("TRequestGetImportInfo", __NAMESPACE__, $TheAppEntityPath."/RequestGetImportInfo.php");
      \sky\TEntity::RegisterClass("TRequestGetStoriesForImport", __NAMESPACE__, $TheAppEntityPath."/RequestGetStoriesForImport.php");
      \sky\TEntity::RegisterClass("TRequestSetStoriesFromExport", __NAMESPACE__, $TheAppEntityPath."/RequestSetStoriesFromExport.php");
      
      require_once $GLOBALS["SkyFrameworkPath"]."/Communication/RequestParams.php";
      TConfig::$RequestParams = new \sky\TRequestParams;
      TConfig::$RequestParams->AppNameSpace = __NAMESPACE__;
      TConfig::$RequestParams->CommandFolder = TConfig::$FAppFolder . "Commands/";
      TConfig::$RequestParams->CommandPrefix = "Request";
      TConfig::$RequestParams->LogFilePath = TConfig::$LogFilePath;
      TConfig::$RequestParams->LogRequests = true;
      TConfig::$RequestParams->RequestKey = "1DC6B0CC5899A15A";
      
    }
    
    //private
    public static $FAppFolder;
    private static $FSkyFrameworkPath;
  }
  
  if (!function_exists('warning_handler')) {
      function warning_handler($errno, $errstr){
         if(!TConfig::$RequestParams->LogRequests){
           return;
         }   
        $TheTimeOfDay = gettimeofday();           
        $TheFileName = TConfig::$RequestParams->LogFilePath.date("Ymd_His_", time()).$TheTimeOfDay['usec']."_warning.txt";
        $TheFileHandle = fopen($TheFileName, 'w');
        fwrite($TheFileHandle, $errno.$errstr);
        fclose($TheFileHandle);    
      }
    }
    
    if (!function_exists('error_handler')) {
      function error_handler($errno, $errstr, $errfile, $errline) {
        $TheTimeOfDay = gettimeofday();           
        $TheFileName = TConfig::$RequestParams->LogFilePath.date("Ymd_His_", time()).$TheTimeOfDay['usec']."_error.txt";
        $TheFileHandle = fopen($TheFileName, 'w');
        fwrite($TheFileHandle, "Error(".$errno.") in ".$errfile.":". $errline." - ".$errstr);
        fclose($TheFileHandle);
        return TRUE;
      }
    }

  set_error_handler("\TApp\warning_handler", E_WARNING);  
  set_error_handler('\TApp\error_handler');
  TConfig::Initialize();
?>
