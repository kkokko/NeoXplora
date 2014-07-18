<?php 


namespace NeoX\Entity;
  
  require_once APP_DIR . "/app/system/Entity.php";
  class TIRepRule extends \SkyCore\TEntity {
  
	public static $entityname = "ireprule";
	
	public static $tablename = "neox_ireprule";
	
	public static $tok_id = "Id";
    public static $tok_name = "Name";
	public static $tok_order = "Order";
	
	public function getMaxOrder(){
		$query = $this->query("SELECT MAX([[ireprule.order]]) AS `MaxOrder` FROM [[ireprule]]");
        $result = $query->fetch_array();
		return $result['MaxOrder'];
	}
	
}

?>