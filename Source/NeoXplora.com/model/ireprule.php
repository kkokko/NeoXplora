<?php 


namespace NeoX\Controller;
require_once APP_DIR . "/app/system/Model.php";

class ModelIRepRule extends \SkyCore\TModel {


	public function getRulesList(){
		
		$sql = "SELECT * FROM `ireprules` ORDER BY `priority`";
		
		$result = $this->core->userdb->query($sql);
		$ruleList = array();
		while($rule = $result->fetch_array()){
			$ruleList[] = $rule;
		}
		
		return $ruleList;
	}
	
	public function updatePriority($priorityData){
		$success = true;
		foreach($priorityData as $pdata){
			$success =  $success && $this->setPriority($pdata[0],$pdata[1]);
		}
		return $success;
	}
	
	function setPriority($ruleId,$priority){
		$sql = "UPDATE `ireprules` SET `priority`=$priority WHERE `id`=$ruleId";
		$result = $this->core->userdb->query($sql);
		return ($result)?true:false;
	}
	
}

?>