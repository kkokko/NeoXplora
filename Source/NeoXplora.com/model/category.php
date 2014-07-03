<?php
  require_once APP_DIR . "/app/system/model.php";
	class ModelCategory extends Model {
    
    //Table Name in the DB
    public static $tablename = "category";
    
    //Table Fields in the DB
    public static $tok_id = "Id";
    public static $tok_name = "Name";
    public static $tok_parentid = "ParentId";
    
    public function count() {
      $query = $this->query("
        SELECT 
          COUNT(c.[[category.id]]) AS total
        FROM [[category]] c
      ");
      $result = $query->fetch_array();
      
      return $result['total'];
    }
    
    public function getById($Id) {
      $query = $this->query("SELECT * FROM [[category]] WHERE [[category.id]] = :1", $Id);
      
      return $this->result($query);
    }
    
	}
?>