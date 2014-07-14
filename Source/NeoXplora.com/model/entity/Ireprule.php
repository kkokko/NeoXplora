<?php 


namespace NeoX\Entity;
  
  require_once APP_DIR . "/app/system/Entity.php";
  class TIRepRule extends \SkyCore\TEntity {
  
	public static $entityname = "ireprule";
	
	public static $tablename = "neox_ireprule";
	
	public function getRuleName($ruleId){
		$sql = "SELECT * FROM `neox_ireprule` WHERE `Id`=$ruleId";
		$result = $this->query($sql);
		$result = $result->fetch_array();
		return $result['Name'];
	}
	
	public function getRulesList(){
		
		$sql = "SELECT * FROM `neox_ireprule` ORDER BY `Order`";
		
		$result = $this->query($sql);
		$ruleList = array();
		while($rule = $result->fetch_array()){
			$ruleList[] = $rule;
		}
		return $ruleList;
	}
	
	public function postRuleName($ruleId,$ruleName){
		
		if($ruleId == -1){
			
			// get the maximum order
			
			$sql = "select max(`Order`) as maxOrder from `neox_ireprule`";
			$result = $this->db->query($sql);
			$result = $result->fetch_array(MYSQLI_ASSOC);
			$Order = intval($result['maxOrder']) +1;
			
			// insert a new rule
			$sql = "INSERT INTO `neox_ireprule` (`Name`, `Order`) VALUES ('$ruleName', $Order);";
			$result = $this->db->query($sql);
			if($result){
				$insertedId = $this->db->insert_id;
				return '{"actionResult":"success","ruleId":'.$insertedId.'}';
			}else{
				return '{"actionResult":"fail","message":"Could not create rule."}';
			}
		}else{
			$sql = "UPDATE `neox_ireprule` SET `Name`='$ruleName' WHERE  `Id`=$ruleId;";
			$result = $this->db->query($sql);
			if($result){
				return '{"actionResult":"success","ruleId":'.$ruleId.'}';
			}else{
				return '{"actionResult":"fail","message":"Could not update rule name."}';
			}
		}
	}
	
	public function updatePriority($priorityData){
		$success = true;
		foreach($priorityData as $pdata){
			$success =  $success && $this->setPriority($pdata[0],$pdata[1]);
		}
		return $success;
	}
	
	function setPriority($ruleId,$priority){
		$sql = "UPDATE `neox_ireprule` SET `priority`=$priority WHERE `id`=$ruleId";
		$result = $this->query($sql);
		return ($result)?true:false;
	}
	
	// Rule Conditions
	
	function updateRuleConditions($ruleId,$updateData){
		$success = true;
		
		foreach($updateData as &$nodeData){
			if($nodeData['actionType']=="update"){
				$parentId = "null";
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
		
		return $this->getRuleConditions($ruleId);
	}
	
	function insertConditionsGroup($ruleId,$order,$parentId,$conjunctionType){
		$sql = "INSERT INTO `neox_irepgroup` (`RuleId`, `Order`, `ParentId`, `ConjunctionType`) 
				VALUES ($ruleId, $order, $parentId, '$conjunctionType')";
		$result = $this->db->query($sql);
		$insertedId = $this->db->insert_id;
		return $insertedId;
	}
	
	function updateConditionsGroup($groupId,$order,$parentId,$conjuctionType){
		$sql = "UPDATE `neox_irepgroup` SET `Order`=$order, `ParentId`=$parentId, `ConjunctionType`='$conjuctionType' WHERE  `Id`=$groupId;";
		$result = $this->db->query($sql);
	}
	
	function deleteConditionsGroup($groupId){
		$this->deleteRuleConditionByGroupId($groupId);
		$sql = "SELECT * FROM `neox_irepgroup` WHERE `ParentId`=$groupId";
		$result = $this->query($sql);
		while($subGroup = $result->fetch_array()){
			$this->deleteConditionsGroup($subGroup['Id']);
		}
		$sql = "DELETE FROM `neox_irepgroup` WHERE  `Id`=$groupId;";
		$result = $this->query($sql);
	}
	
	function insertRuleCondition($ruleId,$groupId,$order,$propertyType,$propertyKey,$operandType,$propertyValue){
		$sql = "INSERT INTO `neox_ireprulecondition` (`RuleId`, `GroupId`, `Order`, `PropertyType`, `PropertyKey`, `OperandType`, `PropertyValue`) 
				VALUES ($ruleId, $groupId, $order, '$propertyType', '$propertyKey', '$operandType', '$propertyValue');";
		$result = $this->db->query($sql);
		$insertedId = $this->db->insert_id;
		return $insertedId;
	}
	
	function updateRuleCondition($conditionId,$groupId,$order){
		$sql = "UPDATE `neox_ireprulecondition` SET `GroupId`=$groupId, `Order`=$order WHERE  `Id`=$conditionId;";
		$result = $this->db->query($sql);
	}
	
	function deleteRuleCondition($conditionId){
		$sql = "DELETE FROM `neox_ireprulecondition` WHERE  `Id`=$conditionId;";
		$result = $this->db->query($sql);
	}
	
	function deleteRuleConditionByGroupId($groupId){
		$sql = "DELETE FROM `neox_ireprulecondition` WHERE  `GroupId`=$groupId;";
		$result = $this->db->query($sql);
	}
	
	function getRuleConditions($ruleId){
		$sql = "SELECT * FROM `neox_irepgroup` WHERE `RuleId`=$ruleId AND `ParentId` IS NULL";
		$result = $this->query($sql);
		$groupData = $result->fetch_array();
		return array(
				'id'=>$groupData['Id'],
				'ConjunctionType'=>$groupData['ConjunctionType'],
				'Children'=> $this->getConditionGroupChildren($groupData['Id'])
			);
	}
	
	function getConditionGroupChildren($groupId){
	
		$children = array();
		
		$sql = "SELECT * FROM `neox_irepgroup` WHERE `ParentId`=$groupId";
		$result = $this->query($sql);
		while($record = $result->fetch_array()){
			$children[intval($record['Order'])] = array(
				'id'=>$record['Id'],
				'ConjunctionType'=>$record['ConjunctionType'],
				'Children'=> $this->getConditionGroupChildren($record['Id'])
			);
		}
		$sql = "SELECT * FROM `neox_ireprulecondition` WHERE `GroupId`=$groupId";
		$result = $this->query($sql);
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
	
	// Rule values
	
	function updateRuleValues($ruleId,$updateData){
		$success = true;
		foreach($updateData as $valueData){
			$success &= $this->updateRuleValue($ruleId,$valueData);
		}
		if($success){
			return $this->getRuleValues($ruleId);
		}else{
			return false;
		}
	}
	
	function updateRuleValue($ruleId,$valueData){
		$actionType = $valueData['actionType'];
		if($actionType=="delete"){
			
			$dbId = $valueData['dbId'];
			$sql = "DELETE FROM `neox_ireprulevalue` WHERE  `Id`=$dbId;";
			$result = $this->query($sql);
			return ($result)?true:false;
			
		}else{
			$PropertyType = $valueData['PropertyType'];
			$PropertyKey = $valueData['PropertyKey'];
			$OperatorType = $valueData['OperatorType'];
			$PropertyValue = $valueData['PropertyValue'];
			$sql = "INSERT INTO `neox_ireprulevalue` 
					(`RuleId`, `PropertyType`, `PropertyKey`, `OperandType`, `PropertyValue`) 
					VALUES ($ruleId, '$PropertyType', '$PropertyKey', '$OperatorType', '$PropertyValue');";
			$result = $this->query($sql);
			return ($result)?true:false;
		}
	}
	
	function getRuleValues($ruleId){
		
		$sql="SELECT * FROM `neox_ireprulevalue` WHERE `RuleId`=$ruleId";
		$result = $this->query($sql);
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