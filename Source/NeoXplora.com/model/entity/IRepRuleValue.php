<?php 

namespace NeoX\Entity;
  
  require_once APP_DIR . "/app/system/Entity.php";
  class TIRepRuleValue extends \SkyCore\TEntity {
  
	public static $entityname = "ireprulevalue";
	
	public static $tablename = "neox_ireprulevalue";
	
	public static $tok_id = "Id";
	public static $tok_ruleId = "RuleId";
	public static $tok_keyPropertyType = "KeyPropertyType";
	public static $tok_propertyKey = "PropertyKey";
	public static $tok_operandType = "OperandType";
	public static $tok_propertyValue = "PropertyValue";
	
  }

?>