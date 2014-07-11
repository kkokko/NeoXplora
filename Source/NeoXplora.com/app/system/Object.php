<?php
  namespace SkyCore;

  class TObject {
    private $registry = null;
    protected $accessLevel = 'guest';
    
    public function __construct($registry) {
      $this->registry = $registry;
    }

    public function &__get($key) {
      if(property_exists($this, $key)) {
        $return =& $this->{$key};
        return $return;
      } else if($this->registry->isRegistered($key)) {
        $return =& $this->registry->{$key}; 
        return $return;
      } else {
        throw new \Exception("Class does not have property " . $key);
      }
    }
    
    public function hasProperty($key) {
      return ((property_exists($this, $key)) ||
        ($this->registry->isRegistered($key)));
    }
    
    public function __set($key, $value) {
      if(property_exists($this, $key)) {
        $this->{$key} = $value;
      } else {
        throw new \Exception("Class does not have own property " . $key);
      }
    }
    
    public function Delphi() {
      if($this->hasProperty("delphi")) {
        return $this->delphi;
      } else {
        require_once $GLOBALS['delphi_service_path'];
        $this->registry->delphi = $server;
        return $this->delphi;
      }
    }
    
    public function accessLevel() {
      return $this->accessLevel;
    }
    
  }
?>