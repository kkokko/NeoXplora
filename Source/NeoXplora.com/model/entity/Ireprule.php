<?php 


namespace NeoX\Entity;
  
  require_once APP_DIR . "/app/system/Entity.php";
  class TIRepRule extends \SkyCore\TEntity {
  
	public static $entityname = "ireprule";
	
	public static $tablename = "neox_ireprule";

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
		return $valueList;
	}
	
}

?>