<?php
  namespace NeoX\Entity;

  require_once APP_DIR . "/app/system/Entity.php";
	class TSeparator extends \SkyCore\TEntity {
    
    //Table Name in the DB
    public static $tablename = "neox_separator";
    
    //Table Fields in the DB
    public static $tok_id = "Id";
    public static $tok_value = "Value";
    
	}
?>