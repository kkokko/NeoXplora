<?php
namespace SkyCore;

class TRegistry {
  private static $instance = null;
  private $registry = array();
  
  public static function getInstance() {
    if(self::$instance == null) {
      self::$instance = new TRegistry();
    }
    return self::$instance;
  }
  
  private function __construct() {
    
  }
  
  public function __set($key, $value) {
    if(isset($this->registry[$key])) {
      throw new \Exception("There is already a registry entry for " . $key);
    }
    $this->registry[$key] =& $value;
  }
  
  public function &__get($key) {
    if(isset($this->registry[$key])) {
      $return =& $this->registry[$key];
      return $return;
    } else {
      throw new \Exception("No registry entry for " . $key);
    }
  }
  
  public function isRegistered($key) {
    return isset($this->registry[$key]);
  }
  
} 

?>