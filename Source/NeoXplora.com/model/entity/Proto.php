<?php
  namespace NeoX\Entity;

  require_once APP_DIR . "/app/system/Entity.php";
  class TProto extends \SkyCore\TEntity {
	  
    //Table Name in the DB
    public static $tablename = "neox_proto";
    
    //Table Fields in the DB
    public static $tok_id = "Id";
    public static $tok_name = "Name";
    public static $tok_level = "Level";
    public static $tok_pageid = "PageId";
    public static $tok_parentid = "ParentId";
    public static $tok_mainprotoid = "MainProtoId";
    public static $tok_order = "Order";
    
	}
?>