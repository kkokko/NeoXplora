<?php 
namespace NeoX\Controller;
use NeoX\Model;
use NeoX\Entity;

require_once __DIR__ . "/../panel.php";
require_once __DIR__ . "/../../model/entity/IRepRule.php";

class TPanelIRepRules extends TPanel {
  
 
  public function index(){
	$this->template->addStyle("style/admin.css");
	$this->template->addStyle("style/admin.irep.css");
	$this->template->addScript("js/module/ireprules/panel.irep.js");
    $this->template->load("index", "panel/ireprules");
    $this->template->pageTitle = "IRep rules | Admin Panel";
    $this->template->page = "ireprules_panel";
	
	$IRepRulesList = $this->getRulesList();
	$this->template->rulesList = $IRepRulesList;
	
    $this->template->hide_right_box = true;
    $this->template->render();
  }
  
  public function add(){
	$this->template->ruleId = -1;
	$this->template->ruleName = "";
	$this->template->addStyle("style/admin.css");
	$this->template->addStyle("style/admin.irep.css");
	$this->template->addScript("NeoShared/SkyJs/skyjs.js");
	$this->template->addScript("js/classes/IRepConditionParser.js");
	$this->template->addScript("js/classes/BaseRule.js");
	$this->template->addScript("js/classes/RuleGroup.js");
	$this->template->addScript("js/classes/RuleValue.js");
	$this->template->addScript("js/module/ireprules/panel.irep.add.js");
    $this->template->load("add_edit", "panel/ireprules");
    $this->template->pageTitle = "IRep rules - Add | Admin Panel";
    $this->template->page = "ireprules_panel";
	
    $this->template->hide_right_box = true;
    $this->template->render();
  }
  
  public function edit(){
	if(isset($_REQUEST['ruleId'])){
		$ruleId = intval($_REQUEST['ruleId']);
		$this->template->ruleId = $ruleId;
		$this->template->ruleName = $this->getRuleName($ruleId);
		$this->template->addStyle("style/admin.css");
		$this->template->addStyle("style/admin.irep.css");
		$this->template->addScript("NeoShared/SkyJs/skyjs.js");
		$this->template->addScript("js/classes/IRepConditionParser.js");
		$this->template->addScript("js/classes/BaseRule.js");
		$this->template->addScript("js/classes/RuleGroup.js");
		$this->template->addScript("js/classes/RuleValue.js");
		$this->template->addScript("js/module/ireprules/panel.irep.add.js");
		$this->template->load("add_edit", "panel/ireprules");
		$this->template->pageTitle = "IRep rule - Edit | Admin Panel";
		$this->template->page = "ireprules_panel";
		
		$this->template->hide_right_box = true;
		$this->template->render();
	}
  }
  
  public function postRuleName(){
	if(isset($_REQUEST['ruleId']) && isset($_REQUEST['ruleName'])){
		$ruleName = $_REQUEST['ruleName'];
		$ruleId = intval($_REQUEST['ruleId']);
		if($ruleId == -1){
			$Order = $this->core->entity("ireprule")->getMaxOrder() + 1;
			$result = $this->core->entity("ireprule")->insert(array("name","order"),array(array($ruleName,$Order)));
			if($result){
				$insertedId = $this->db->insert_id;
				print '{"actionResult":"success","ruleId":'.$insertedId.'}';
			}else{
				print '{"actionResult":"fail","message":"Could not create rule."}';
			}
		}else{
			$result = $this->core->entity("ireprule")->update($ruleId,array("name"=>$ruleName));
			if($result){
				print '{"actionResult":"success","ruleId":'.$ruleId.'}';
			}else{
				print '{"actionResult":"fail","message":"Could not update rule name."}';
			}
		}
	}else{
		print '{"actionResult":"fail"}';
	}
  }
  
  
  public function updateRulePriority(){
	if(isset($_REQUEST['priorityData'])){
		$priorityData = $_REQUEST['priorityData'];
		$success = true;
		foreach($priorityData as $pdata){
			$success =  $success && $this->setRulePriority($pdata[0],$pdata[1]);
		}
		if($success) {
			print "success";
			exit;
		}
	}
	print "error";
  }
  
  private function getRuleName($ruleId){
	$query = $this->core->entity("ireprule")->select($ruleId,"name");
	$result = $query->fetch_array();
	return $result['Name'];
  }
  
  private function getRulesList(){
	$query = $this->core->entity("ireprule")->select(null,"*",array("order"=>'ASC'));
	$ruleList = array();
	while($rule = $query->fetch_array()){
		$ruleList[] = $rule;
	}
	return $ruleList;
  }
  
  private function setRulePriority($ruleId,$priority){
	$result = $this->core->entity("ireprule")->update($ruleId,array("order"=>$priority));
	return $result;
  }
  
  // Asynch resquest controllers for Value/Condition CRUD
  
  public function getRuleConditionsData(){
	if(isset($_REQUEST['ruleId'])){
		$ruleId = intval($_REQUEST['ruleId']);
		$ruleConditionData = $this->getRuleConditions($ruleId);
		print json_encode($ruleConditionData);
	}
  }
  
	private function getRuleConditions($ruleId){
		$result = $this->core->entity("irepgroup")->getRuleMainGroup($ruleId);
		$groupData = $result->fetch_array();
		return array(
				'id'=>$groupData['Id'],
				'ConjunctionType'=>$groupData['ConjunctionType'],
				'Children'=> $this->getConditionGroupChildren($groupData['Id'])
			);
	}
	
	private function getConditionGroupChildren($groupId){
		
		$children = array();
		$result = $this->core->entity("irepgroup")->getGroupChildren($groupId);
		while($record = $result->fetch_array()){
			$children[intval($record['Order'])] = array(
				'id'=>$record['Id'],
				'ConjunctionType'=>$record['ConjunctionType'],
				'Children'=> $this->getConditionGroupChildren($record['Id'])
			);
		}
		$result = $this->core->entity("ireprulecondition")->select(array('groupId'=>array($groupId)),"*");
		while($record = $result->fetch_array()){
			$children[intval($record['Order'])] = array(
				'id'=>$record['Id'],
				'PropertyType'=>$record['PropertyType'],
				'PropertyKey'=>$record['PropertyKey'],
				'PropertyValue'=>$record['PropertyValue'],
				'OperandType'=>$record['OperandType']
			);
		}
		ksort($children);
		return $children;
		
	}
	
	public function updateRuleConditions(){
		if(isset($_REQUEST['ruleId'])){
			$ruleId = intval($_REQUEST['ruleId']);
			$updateData = $_REQUEST['updateData'];
			$success = true;
			
			foreach($updateData as &$nodeData){
				if($nodeData['actionType']=="update"){
					$parentId = null;
					if(intval($nodeData['ParentLocalId'])>=0){
						$parentId = $updateData[$nodeData['ParentLocalId']]['dbId'];
					}
					if($nodeData['nodeType']=='Group'){
						
						if($nodeData['dbId']>0){
							$this->updateConditionsGroup($nodeData['dbId'],$nodeData['Order'],$parentId,$nodeData['ConjunctionType']);
						}else{
							$nodeData['dbId'] = $this->insertConditionsGroup($ruleId,$nodeData['Order'],$parentId,$nodeData['ConjunctionType']);
						}
					}else{
						// value
						if($nodeData['dbId']>0){
							$this->updateRuleCondition($nodeData['dbId'],$parentId,$nodeData['Order']);
						}else{
							$this->insertRuleCondition(
								$ruleId,
								$parentId,
								$nodeData['Order'],
								$nodeData['PropertyType'],
								$nodeData['PropertyKey'],
								$nodeData['OperatorType'],
								$nodeData['PropertyValue']
							);
						}
					}
				}else{
					if($nodeData['nodeType']=='Group'){
						$this->deleteConditionsGroup($nodeData['dbId']);
					}else{
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
	
	private function insertConditionsGroup($ruleId,$order,$parentId,$conjunctionType){
		if($parentId!=null){
			$result = $this->core->entity("irepgroup")->insert(
				array("ruleId","order","parentId","conjunctionType"),
				array(array($ruleId,$order,$parentId,$conjunctionType))
			);
		}else{
			$result = $this->core->entity("irepgroup")->insert(
				array("ruleId","order","conjunctionType"),
				array(array($ruleId,$order,$conjunctionType))
			);
		}
		if($result){
			$insertedId = $this->db->insert_id;
			return $insertedId;
		}else{
			return false;
		}
	}
	
	private function updateConditionsGroup($groupId,$order,$parentId,$conjunctionType){
		if($parentId!=null){
			$result = $this->core->entity("irepgroup")->update(
				$groupId,
				array("order"=>$order,"parentId"=>$parentId,"conjunctionType"=>$conjunctionType)
			);
		}else{
			$result = $this->core->entity("irepgroup")->update(
				$groupId,
				array("order"=>$order,"conjunctionType"=>$conjunctionType)
			);
		}
		return $result;
	}
	
	private function deleteConditionsGroup($groupId){
		$this->deleteRuleConditionByGroupId($groupId);
		$result = $this->core->entity("irepgroup")->select(array('parentId'=>array($groupId)),"*");
		while($subGroup = $result->fetch_array()){
			$this->deleteConditionsGroup($subGroup['Id']);
		}
		$result = $this->core->entity("irepgroup")->delete($groupId);
		return $result;
	}
	
	private function insertRuleCondition($ruleId,$groupId,$order,$propertyType,$propertyKey,$operandType,$propertyValue){
		$result = $this->core->entity("ireprulecondition")->insert(
			array("groupId","order","propertyType","propertyKey","operandType","propertyValue"),
			array(array($groupId, $order, $propertyType, $propertyKey, $operandType, $propertyValue))
		);
		if($result){
			$insertedId = $this->db->insert_id;
			return $insertedId;
		}else{
			return false;
		}
	}
	
	private function updateRuleCondition($conditionId,$groupId,$order){
		$result = $this->core->entity("ireprulecondition")->update(
			$conditionId,
			array("groupId"=>$groupId,"order"=>$order)
		);
		return $result;
	}
	
	private function deleteRuleCondition($conditionId){
		$result = $this->core->entity("ireprulecondition")->delete($conditionId);
		return $result;
	}
	
	private function deleteRuleConditionByGroupId($groupId){
		$result = $this->core->entity("ireprulecondition")->delete(array("groupId"=>$groupId));
		return $result;
	}
	
  // Values
  public function getRuleValuesData(){
	if(isset($_REQUEST['ruleId'])){
		$ruleId = intval($_REQUEST['ruleId']);
		$ruleValueData = $this->getRuleValues($ruleId);
		print json_encode($ruleValueData);
	}
  }
  
  public function updateRuleValues(){
	if(isset($_REQUEST['ruleId'])){
		$ruleId = intval($_REQUEST['ruleId']);
		$updateData = $_REQUEST['updateData'];
		
		$success = true;
		foreach($updateData as $valueData){
			$success = $success && $this->updateRuleValue($ruleId,$valueData);
		}
		if($success){
			print json_encode($this->getRuleValues($ruleId));
			return;
		}
	}
	print "fail";
  }
	
	private function updateRuleValue($ruleId,$valueData){
		$actionType = $valueData['actionType'];
		if($actionType=="delete"){
			
			$dbId = $valueData['dbId'];
			
			$result = $this->core->entity("ireprulevalue")->delete($dbId);
			return $result;
			
		}else{
			$PropertyType = $valueData['PropertyType'];
			$PropertyKey = $valueData['PropertyKey'];
			$OperatorType = $valueData['OperatorType'];
			$PropertyValue = $valueData['PropertyValue'];
			
			$result = $this->core->entity("ireprulevalue")->insert(
				array("ruleId","propertyType","propertyKey","operandType","propertyValue"),
				array(array($ruleId, $PropertyType, $PropertyKey, $OperatorType, $PropertyValue))
			);
			return $result;
		}
	}
	
	private function getRuleValues($ruleId){
		$result = $this->core->entity("ireprulevalue")->select(array("ruleId"=>array($ruleId)));
		$valueList = array();
		while($rule = $result->fetch_array()){
			$valueList[] = $rule;
		}
		
		$result = array("result"=>"success","data"=>array());
		
		foreach($valueList as $data){
			$result["data"][] = array(
				"Id"=>$data["Id"],
				"PropertyType"=>$data["PropertyType"],
				"PropertyKey"=>$data["PropertyKey"],
				"PropertyValue"=>$data["PropertyValue"],
				"OperandType"=>$data["OperandType"]
			);
		}
		return $result;
	}
}
?>