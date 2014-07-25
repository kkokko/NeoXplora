<?php

namespace NeoX\Entity;

require_once APP_DIR . "/app/system/Entity.php";
class TIRepRule extends \SkyCore\TEntity {

  public static $tablename = "neox_ireprule";

  public static $tok_id = "Id";
  public static $tok_name = "Name";
  public static $tok_order = "Order";

  public function getMaxOrder() {
    $query = $this->query("SELECT MAX([[ireprule.order]]) AS `MaxOrder` FROM [[ireprule]]");
    $result = $query->fetch_array();
    return $result['MaxOrder'];
  }

  public function deleteRuleWithData($ruleId) {
    $query = $this->query("
      DELETE rg.*, rc.*
      FROM [[ireprulegroup]] rg
      INNER JOIN [[ireprulecondition]] rc ON rg.[[ireprulegroup.id]] = rc.[[ireprulecondition.groupId]]
      WHERE rg.[[ireprulegroup.ruleId]] = :1
    ", intval($ruleId));
    
    $this->core->entity("ireprule")->delete($ruleId);
  }

}
?>