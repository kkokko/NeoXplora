<?php
  namespace TApp;
  abstract class TConfig{
    public static $LogFilePath = "Log/";
    public static $LogRequests = false;
    public static $TestRequest = false;
    public static $RequestParams;
    public static $FAppFolder;
    public static $TheAppEntityPath;
    public static $TheAppRequestPath;
    public static $TheAppExceptionPath;
    public static $ItemsPerPage = 15;
    
    // public
    public static function Initialize(){
      TConfig::$FAppFolder = str_replace('\\', '/', __DIR__)."/../";
      TConfig::$LogFilePath = TConfig::$FAppFolder . "Log/";
      $GLOBALS["SkyFrameworkPath"] = self::$FAppFolder . "../SkpFW/";
      
      // register application entity classes
      $TheSkyEntityPath = $GLOBALS["SkyFrameworkPath"]."Entity";
      self::$TheAppEntityPath = "Entities";
      self::$TheAppRequestPath = "Requests";
      self::$TheAppExceptionPath = "Errors";
      require_once($TheSkyEntityPath."/Entity.php");
      require_once $GLOBALS["SkyFrameworkPath"]."Errors/SkyException.php";
      require_once $GLOBALS["SkyFrameworkPath"] . "Communication/ResponseServerException.php";
      require_once $GLOBALS["SkyFrameworkPath"] . "Communication/Response.php";
      
      // framework entities
      \sky\TEntity::RegisterClass("TEntity", "sky", $TheSkyEntityPath."/Entity.php");
      \sky\TEntity::RegisterClass("TEntityWithId", "sky", $TheSkyEntityPath."/EntityWithId.php");
      \sky\TEntity::RegisterClass("TEntityWithName", "sky", $TheSkyEntityPath."/EntityWithName.php");
      \sky\TEntity::RegisterClass("TGenericEntity", "sky", $TheSkyEntityPath."/GenericEntity.php");
      \sky\TEntity::RegisterClass("TBasicUserData", "sky", $TheSkyEntityPath."/BasicUserData.php");
      \sky\TEntity::RegisterClass("TResponseServerException", "sky", $GLOBALS["SkyFrameworkPath"] . "Communication/ResponseServerException.php");
      \sky\TEntity::RegisterClass("TResponse", "sky", $GLOBALS["SkyFrameworkPath"] . "Communication/Response.php");

      \sky\TEntity::RegisterClass("ESkyException", "sky", $GLOBALS["SkyFrameworkPath"] . "Errors/SkyException.php");
      \sky\TEntity::RegisterClass("ESkyServerUnknownException", "sky", $GLOBALS["SkyFrameworkPath"] . "Errors/SkyException.php");
      
      // app entities
      
      \sky\TEntity::RegisterClass("TResponseGuessRepsForSentenceId", "TApp", self::$FAppFolder . self::$TheAppRequestPath . "/ResponseGuessRepsForSentenceId.php");
      \sky\TEntity::RegisterClass("TResponseGetPosForPage", "TApp", self::$FAppFolder . self::$TheAppRequestPath . "/ResponseGetPosForPage.php");
      \sky\TEntity::RegisterClass("TResponseGetPosForSentences", "TApp", self::$FAppFolder . self::$TheAppRequestPath . "/ResponseGetPosForSentences.php");
      \sky\TEntity::RegisterClass("TResponseGetFullSentencesForStoryId", "TApp", self::$FAppFolder . self::$TheAppRequestPath . "/ResponseGetFullSentencesForStoryId.php");
      \sky\TEntity::RegisterClass("TResponseGetLinkerDataForStoryId", "TApp", self::$FAppFolder . self::$TheAppRequestPath . "/ResponseGetLinkerDataForStoryId.php");
      \sky\TEntity::RegisterClass("TGuessObject", "TApp", self::$FAppFolder . self::$TheAppEntityPath . "/GuessObject.php");
      \sky\TEntity::RegisterClass("TSentenceBase", "TApp", self::$FAppFolder . self::$TheAppEntityPath . "/SentenceBase.php");
      \sky\TEntity::RegisterClass("TSentenceWithGuesses", "TApp", self::$FAppFolder . self::$TheAppEntityPath . "/SentenceWithGuesses.php");
      \sky\TEntity::RegisterClass("TEntityRecord", "TApp", self::$FAppFolder . self::$TheAppEntityPath . "/EntityRecord.php");
      \sky\TEntity::RegisterClass("TAttributeRecord", "TApp", self::$FAppFolder . self::$TheAppEntityPath . "/AttributeRecord.php");
      \sky\TEntity::RegisterClass("TRequestSearch", "TApp", self::$FAppFolder . self::$TheAppRequestPath . "/RequestSearch.php");
      \sky\TEntity::RegisterClass("TResponseSearch", "TApp", self::$FAppFolder . self::$TheAppRequestPath . "/ResponseSearch.php");
      \sky\TEntity::RegisterClass("TSearchPage", "TApp", self::$FAppFolder . self::$TheAppEntityPath . "/SearchPage.php");
      
      \sky\TEntity::RegisterClass("EAppRepDecoderException", "TApp", self::$FAppFolder . self::$TheAppExceptionPath . "/AppRepDecoderException.php");
    }
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

  //set_error_handler("\TApp\warning_handler", E_WARNING);  
  //set_error_handler('\TApp\error_handler');
  
  TConfig::Initialize();
?>
