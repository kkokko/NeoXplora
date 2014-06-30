<?php
  class TAppEntity {
    protected $db;
    protected $template;
    protected $delphi;
    protected $core;
    protected $accessLevel = 'guest';
    
    public function __construct($core = null, $db = null, $template = null) {
      $this->core = $core;
      $this->db = $db;
      $this->template = $template;
    }
    
    public function Delphi() {
      if($this->delphi != null) {
        return $this->delphi;
      } else {
        require_once __DIR__ . "/../../NeoShared/Server/App/Global.php";
        $this->delphi = $server;
        return $this->delphi; 
      }
    }
    
    public function accessLevel() {
      return $this->accessLevel;
    }
    
  }
  
?>