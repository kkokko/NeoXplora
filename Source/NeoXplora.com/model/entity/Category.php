<?php
  namespace NeoX\Entity;

  require_once APP_DIR . "/app/system/Entity.php";
	class TCategory extends \SkyCore\TEntity {
    
    //Table Name in the DB
    public static $tablename = "neox_category";
    
    //Table Fields in the DB
    public static $tok_id = "Id";
    public static $tok_name = "Name";
    public static $tok_parentid = "ParentId";
    
	}
?>