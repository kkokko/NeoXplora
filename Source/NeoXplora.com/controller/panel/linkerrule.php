<?php
namespace NeoX\Controller;
use NeoX\Model;
use NeoX\Entity;

require_once __DIR__ . "/../panel.php";
require_once __DIR__ . "/../../model/entity/linkerrule.php";

class TPanelLinkerRule extends TPanel {

  // public $accessLevel = 'admin';

  public function index() {
    $this->template->addStyle("style/admin.css");
    $this->template->addStyle("style/admin.irep.css");
    
    $this->template->addScripts(array("js/system/object.js"));

    $this->template->load("index", "panel/linkerrules");
    $this->template->pageTitle = "Linker Rule";
    $this->template->page = "linkerrule";
    $this->template->addJSModules(array(
      "NeoX.Modules.LinkerRuleBrowseIndex" => "js/module/linkerrules/browse/index.js",
      "NeoX.Modules.LinkerRuleBrowseRequests" => "js/module/linkerrules/browse/requests.js"
    ));
    
    $LinkerRulesList = $this->core->entity("linkerrule")->getAll();
    $this->template->rulesList = $LinkerRulesList;

    $this->template->hide_right_box = true;
    $this->template->render();
  }

  public function add() {
    $this->template->ruleId = -1;
    $this->template->ruleName = "";
    $this->template->ruleType = "rtNegate";
    $this->template->addStyle("style/admin.css");
    $this->template->addStyle("style/admin.irep.css");
    $this->template->addScript("js/classes/LinkerConditionParser.js");
    $this->template->addScript("js/classes/BaseRule.js");
    $this->template->addScript("js/classes/RuleGroup.js");
    $this->template->addScript("js/classes/RuleValue.js");
    $this->template->addScript("js/classes/LinkerRuleValue.js");
    $this->template->addScripts(array("js/system/object.js"));
    $this->template->addJSModules(array(
      "NeoX.Modules.LinkerRuleEditIndex" => "js/module/linkerrules/edit/index.js",
      "NeoX.Modules.LinkerRuleEditRequests" => "js/module/linkerrules/edit/requests.js"
    ));
    $this->template->load("add_edit", "panel/linkerrules");
    $this->template->pageTitle = "Linker Rules - Add | Admin Panel";
    $this->template->page = "linkerrules_panel";

    $this->template->hide_right_box = true;
    $this->template->render();
  }

  public function edit() {
    if(isset($_REQUEST['ruleId'])) {
      $ruleId = intval($_REQUEST['ruleId']);
      $ruleData = $this->getRuleInfo($ruleId);
      $this->template->ruleId = $ruleId;
      $this->template->ruleName = $ruleData[Entity\TLinkerRule::$tok_name];
      $this->template->ruleType = $ruleData[Entity\TLinkerRule::$tok_type];
      $this->template->ruleScore = $ruleData[Entity\TLinkerRule::$tok_score];
      $this->template->addStyle("style/admin.css");
      $this->template->addStyle("style/admin.irep.css");
      $this->template->addScript("js/classes/LinkerConditionParser.js");
      $this->template->addScript("js/classes/BaseRule.js");
      $this->template->addScript("js/classes/RuleGroup.js");
      $this->template->addScript("js/classes/RuleValue.js");
      $this->template->addScript("js/classes/LinkerRuleValue.js");
      $this->template->addScripts(array("js/system/object.js"));
      $this->template->addJSModules(array(
        "NeoX.Modules.LinkerRuleEditIndex" => "js/module/linkerrules/edit/index.js",
        "NeoX.Modules.LinkerRuleEditRequests" => "js/module/linkerrules/edit/requests.js"
      ));
      $this->template->load("add_edit", "panel/linkerrules");
      $this->template->pageTitle = "Linker rule - Edit | Admin Panel";
      $this->template->page = "linkerrules_panel";

      $this->template->hide_right_box = true;
      $this->template->render();
    }
  }

  
  public function delete() {
    if(!isset($_POST['ruleId'])) echo "Rule Id not set";
    $ruleId = (int) $_POST['ruleId'];
    
    $query = $this->core->entity("LinkerRule")->deleteRuleWithData($ruleId);
    
    echo $query;
  }
  
  public function postRuleName() {
    if(isset($_REQUEST['ruleId']) && isset($_REQUEST['ruleName']) && isset($_REQUEST['ruleType']) && isset($_REQUEST['ruleScore'])) {
      $ruleType = $_REQUEST['ruleType'];
      if($ruleType == "rtScoring") {
        $ruleScore = intval($_REQUEST['ruleScore']);
      } else {
        $ruleScore = 0;
      }
      
      $ruleName = $_REQUEST['ruleName'];
      $ruleId = intval($_REQUEST['ruleId']);
      if($ruleId == -1) {
        $Order = $this->core->entity("linkerrule")->getMaxOrder() + 1;
        $result = $this->core->entity("linkerrule")->insert(
          array(
            "name",
            "order",
            "type",
            "score"
          ), 
          array( 
            array(
              $ruleName,
              $Order,
              $ruleType,
              $ruleScore
            )
          )
        );
        if($result) {
          $insertedId = $this->db->insert_id;
          echo '{"actionResult":"success","ruleId":' . $insertedId . '}';
        } else {
          echo '{"actionResult":"fail","message":"Could not create rule."}';
        }
      } else {
        $result = $this->core->entity("linkerrule")->update($ruleId, array("name" => $ruleName, "type" => $ruleType));
        if($result) {
          echo '{"actionResult":"success","ruleId":' . $ruleId . '}';
        } else {
          echo '{"actionResult":"fail","message":"Could not update rule name."}';
        }
      }
    } else {
      echo '{"actionResult":"fail"}';
    }
  }

  public function updateRulePriority() {
    if(isset($_REQUEST['priorityData'])) {
      $priorityData = $_REQUEST['priorityData'];
      $success = true;
      foreach($priorityData as $pdata) {
        $success = $success && $this->setRulePriority($pdata[0], $pdata[1]);
      }
      if($success) {
        print "success";
        exit ;
      }
    }
    print "error";
  }

  public function getRuleConditionsData() {
    if(isset($_REQUEST['ruleId'])) {
      $ruleId = intval($_REQUEST['ruleId']);
      $ruleConditionData = $this->getRuleConditions($ruleId);
      print json_encode($ruleConditionData);
    }
  }
  
  public function updateRuleConditions() {
    if(isset($_REQUEST['ruleId'])) {
      $ruleId = intval($_REQUEST['ruleId']);
      $updateData = $_REQUEST['updateData'];

      $success = true;

      foreach($updateData as &$nodeData) {
        if($nodeData['actionType'] == "update") {
          $parentId = null;
          if(intval($nodeData['ParentLocalId']) >= 0) {
            $parentId = $updateData[$nodeData['ParentLocalId']]['dbId'];
          }
          if($nodeData['nodeType'] == 'Group') {

            if($nodeData['dbId'] > 0) {
              $this->updateConditionsGroup($nodeData['dbId'], $nodeData['Order'], $parentId, $nodeData['ConjunctionType']);
            } else {
              $nodeData['dbId'] = $this->insertConditionsGroup($ruleId, $nodeData['Order'], $parentId, $nodeData['ConjunctionType']);
            }
          } else {
            // value
            if($nodeData['dbId'] > 0) {
              $this->updateRuleCondition($nodeData['dbId'], $parentId, $nodeData['Order']);
            } else {
              $this->insertRuleCondition(
                $ruleId, 
                $parentId, 
                $nodeData['Order'],
                $nodeData['KeyEntity'],  
                $nodeData['KeyPropertyType'], 
                $nodeData['PropertyKey'], 
                $nodeData['OperatorType'],
                $nodeData['ValueEntity'],  
                $nodeData['ValuePropertyType'], 
                $nodeData['PropertyValue']
              );
            }
          }
        } else {
          if($nodeData['nodeType'] == 'Group') {
            $this->deleteConditionsGroup($nodeData['dbId']);
          } else {
            $this->deleteRuleCondition($nodeData['dbId']);
          }
        }
      }

      $resultData = $this->getRuleConditions($ruleId);
      print json_encode($resultData);
      return;
    }

    print "fail";
  }

  private function insertConditionsGroup($ruleId, $order, $parentId, $conjunctionType) {
    if($parentId != null) {
      $result = $this->core->entity("linkerrulegroup")->insert(
        array(
          "ruleId",
          "order",
          "parentId",
          "conjunctionType"
        ), 
        array( 
          array(
            $ruleId,
            $order,
            $parentId,
            $conjunctionType
          )
        )
      );
    } else {
      $result = $this->core->entity("linkerrulegroup")->insert(
        array(
          "ruleId",
          "order",
          "conjunctionType"
        ), 
        array( 
          array(
            $ruleId,
            $order,
            $conjunctionType
          )
        )
      );
    }
    if($result) {
      $insertedId = $this->db->insert_id;
      return $insertedId;
    } else {
      return false;
    }
  }

  private function updateConditionsGroup($groupId, $order, $parentId, $conjunctionType) {
    if($parentId != null) {
      $result = $this->core->entity("linkerrulegroup")->update($groupId, array(
        "order" => $order,
        "parentId" => $parentId,
        "conjunctionType" => $conjunctionType
      ));
    } else {
      $result = $this->core->entity("linkerrulegroup")->update($groupId, array(
        "order" => $order,
        "conjunctionType" => $conjunctionType
      ));
    }
    return $result;
  }

  private function deleteConditionsGroup($groupId) {
    $this->deleteRuleConditionByGroupId($groupId);
    $result = $this->core->entity("linkerrulegroup")->select(array('parentId' => array($groupId)), "*");
    while($subGroup = $result->fetch_array()) {
      $this->deleteConditionsGroup($subGroup['Id']);
    }
    $result = $this->core->entity("linkerrulegroup")->delete($groupId);
    return $result;
  }

  private function insertRuleCondition($ruleId, $groupId, $order, $keyEntity, $keyPropertyType, $propertyKey, $operandType, $valueEntity, $valuePropertyType, $propertyValue) {
    $result = $this->core->entity("linkerrulecondition")->insert(
      array(
        "groupId",
        "order",
        "keyEntity",
        "keyPropertyType",
        "propertyKey",
        "operandType",
        "valueEntity",
        "valuePropertyType",
        "propertyValue"
      ), 
      array( 
        array(
          $groupId,
          $order,
          $keyEntity,
          $keyPropertyType,
          $propertyKey,
          $operandType,
          $valueEntity,
          $valuePropertyType,
          $propertyValue
        )
      )
    );
    if($result) {
      $insertedId = $this->db->insert_id;
      return $insertedId;
    } else {
      return false;
    }
  }

  private function updateRuleCondition($conditionId, $groupId, $order) {
    $result = $this->core->entity("linkerrulecondition")->update($conditionId, array(
      "groupId" => $groupId,
      "order" => $order
    ));
    return $result;
  }

  private function deleteRuleCondition($conditionId) {
    $result = $this->core->entity("linkerrulecondition")->delete(array("id" => array($conditionId)));
    return $result;
  }

  private function deleteRuleConditionByGroupId($groupId) {
    $result = $this->core->entity("linkerrulecondition")->delete(array("groupId" => $groupId));
    return $result;
  }
  
  private function getRuleConditions($ruleId) {
    $result = $this->core->entity("linkerrulegroup")->getRuleMainGroup($ruleId);
    $groupData = $result->fetch_array();
    return array(
      'id' => $groupData['Id'],
      'ConjunctionType' => $groupData['ConjunctionType'],
      'Children' => $this->getConditionGroupChildren($groupData['Id'])
    );
  }

  private function getConditionGroupChildren($groupId) {
    $children = array();
    $result = $this->core->entity("linkerrulegroup")->getGroupChildren($groupId);
    while($record = $result->fetch_array()) {
      $children[intval($record['Order'])] = array(
        'id' => $record['Id'],
        'ConjunctionType' => $record['ConjunctionType'],
        'Children' => $this->getConditionGroupChildren($record['Id'])
      );
    }
    $result = $this->core->entity("linkerrulecondition")->select(array('groupId' => array($groupId)), "*");
    while($record = $result->fetch_array()) {
      $children[intval($record['Order'])] = array(
        'id' => $record['Id'],
        'KeyEntity' => $record[Entity\TLinkerRuleCondition::$tok_keyEntity],
        'KeyPropertyType' => $record[Entity\TLinkerRuleCondition::$tok_keyPropertyType],
        'PropertyKey' => $record[Entity\TLinkerRuleCondition::$tok_propertyKey],
        'OperandType' => $record[Entity\TLinkerRuleCondition::$tok_operandType],
        'ValueEntity' => $record[Entity\TLinkerRuleCondition::$tok_valueEntity],
        'ValuePropertyType' => $record[Entity\TLinkerRuleCondition::$tok_valuePropertyType],
        'PropertyValue' => $record[Entity\TLinkerRuleCondition::$tok_propertyValue]
        
      );
    }
    ksort($children);
    return $children;
  }

  private function getRuleName($ruleId) {
    $query = $this->core->entity("linkerrule")->select($ruleId, "name");
    $result = $query->fetch_array();
    return $result['Name'];
  }

  private function getRuleInfo($ruleId) {
    $query = $this->core->entity("linkerrule")->select($ruleId, "*");
    $result = $query->fetch_array();
    return $result;
  }
  
  private function getRulesList() {
    $query = $this->core->entity("linkerrule")->select(null, "*", array("order" => 'ASC'));
    $ruleList = array();
    while($rule = $query->fetch_array()) {
      $ruleList[] = $rule;
    }
    return $ruleList;
  }

  private function setRulePriority($ruleId, $priority) {
    $result = $this->core->entity("linkerrule")->update($ruleId, array("order" => $priority));
    return $result;
  }

}
?>