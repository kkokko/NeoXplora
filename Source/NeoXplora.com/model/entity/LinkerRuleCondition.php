<?php

namespace NeoX\Entity;

require_once APP_DIR . "/app/system/Entity.php";
class TLinkerRuleCondition extends \SkyCore\TEntity {

  public static $tablename = "neox_creprulecondition";

  public static $tok_id = "Id";
  public static $tok_groupId = "GroupId";
  public static $tok_order = "Order";
  public static $tok_keyEntity = "KeyEntity";
  public static $tok_keyPropertyType = "KeyPropertyType";
  public static $tok_propertyKey = "Key";
  public static $tok_operandType = "OperatorType";
  public static $tok_valueEntity = "ValueEntity";
  public static $tok_valuePropertyType = "ValuePropertyType";
  public static $tok_propertyValue = "Value";

}
?>