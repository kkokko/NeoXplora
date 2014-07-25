<?php
namespace NeoX\Entity;

require_once APP_DIR . "/app/system/Entity.php";

class TLinkerRule extends \SkyCore\TEntity {

  public static $entityname = "linkerrule";

  //Table Name in the DB
  public static $tablename = "neox_creprule";

  //Table Fields in the DB
  public static $tok_id = "Id";
  public static $tok_name = "Name";
  public static $tok_type = "RuleType";
  public static $tok_score = "RuleScore";
  public static $tok_order = "Order";

  public function getAll() {
    $query = $this->core->entity("linkerrule")->select(null, "*", array("order" => "ASC"));

    $result = array();
    if($query) {
      while($row = $query->fetch_assoc()) {
        $result[] = array(
          "id" => $row[$this::$tok_id],
          "name" => $row[$this::$tok_name],
          "score" => $row[$this::$tok_score],
          "type" => $row[$this::$tok_type],
          "order" => $row[$this::$tok_order]
        );
      }
    }
    return $result;
  }

  public function getMaxOrder() {
    $query = $this->query("SELECT MAX([[linkerrule.order]]) AS `MaxOrder` FROM [[linkerrule]]");
    $result = $query->fetch_array();
    return $result['MaxOrder'];
  }
  
  public function deleteRuleWithData($ruleId) {
    $query = $this->query("
      DELETE rg.*, rc.*
      FROM [[linkerrulegroup]] rg
      INNER JOIN [[linkerrulecondition]] rc ON rg.[[linkerrulegroup.id]] = rc.[[linkerrulecondition.groupId]]
      WHERE rg.[[linkerrulegroup.ruleId]] = :1
    ", intval($ruleId));
    
    $this->core->entity("linkerrule")->delete($ruleId);
  }

}
?>