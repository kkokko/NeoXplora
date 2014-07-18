<?php 


namespace NeoX\Entity;
  
  require_once APP_DIR . "/app/system/Entity.php";
  class TIRepGroup extends \SkyCore\TEntity {
  
	public static $entityname = "irepgroup";
	
	public static $tablename = "neox_irepgroup";
	
	public static $tok_id = "Id";
    public static $tok_ruleId = "RuleId";
	public static $tok_order = "Order";
	public static $tok_parentId = "ParentId";
	public static $tok_conjunctionType = "ConjunctionType";
	
	public function getRuleMainGroup($ruleId){
		$query = $this->query(
			"SELECT * FROM [[irepgroup]] 
			WHERE 
				[[irepgroup.ruleId]] = :1 AND 
				[[irepgroup.parentId]] IS NULL"
			,$ruleId);
		return $query;
	}
	
	public function getGroupChildren($groupId){
		$query = $this->query(
			"SELECT * FROM [[irepgroup]] 
			WHERE 
				[[irepgroup.parentId]] = :1"
			,$groupId);
		return $query;
	}
	
  }

?>