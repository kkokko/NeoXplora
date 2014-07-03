<?php
  require_once APP_DIR . "/app/system/model.php";
	class ModelProto extends Model {
    
    //Table Name in the DB
    public static $tablename = "proto";
    
    //Table Fields in the DB
    public static $tok_id = "Id";
    public static $tok_name = "Name";
    public static $tok_level = "Level";
    public static $tok_pageid = "PageId";
    
    public function count() {
      $query = $this->query("
        SELECT 
          COUNT(pr.[[proto.id]]) AS total
        FROM [[proto]] pr
      ");
      $result = $query->fetch_array();
      
      return $result['total'];
    }
    
    public function getById($Id) {
      $query = $this->query("SELECT * FROM [[proto]] WHERE [[proto.id]] = :1", $Id);
      
      return $this->result($query);
    }
    
	}
?>