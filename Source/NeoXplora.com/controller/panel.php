<?php

namespace NeoX\Controller;

require_once APP_DIR . "/app/system/Object.php";
class TPanel extends \SkyCore\TObject {

  public $accessLevel = 'admin';

  public function index() {
    $this->template->addStyle("style/admin.css");
    $this->template->load("index", "panel");
    $this->template->pageTitle = "Admin Panel";
    $this->template->page = "panel";
    $this->template->hide_right_box = true;
    $this->template->render();
  }
  
  public function ireprules(){
	$this->template->addStyle("style/admin.css");
	$this->template->addStyle("style/admin.irep.css");
	$this->template->addScript("js/panel.irep.js");
    $this->template->load("ireprules", "panel");
    $this->template->pageTitle = "IRep rules | Admin Panel";
    $this->template->page = "ireprules_panel";
	
	$IRepRulesList = $this->core->entity("ireprule")->getRulesList();
	$this->template->rulesList = $IRepRulesList;
	
    $this->template->hide_right_box = true;
    $this->template->render();
  }
  
  public function ireprules_add(){
	$this->template->addStyle("style/admin.css");
	$this->template->addStyle("style/admin.irep.css");
	$this->template->addScript("NeoShared/SkyJs/skyjs.js");
	$this->template->addScript("js/classes/IRepConditionParser.js");
	$this->template->addScript("js/classes/BaseRule.js");
	$this->template->addScript("js/classes/RuleGroup.js");
	$this->template->addScript("js/classes/RuleValue.js");
	$this->template->addScript("js/panel.irep.add.js");
    $this->template->load("ireprules_add", "panel");
    $this->template->pageTitle = "IRep rules - Add | Admin Panel";
    $this->template->page = "ireprules_panel";
	
    $this->template->hide_right_box = true;
    $this->template->render();
  }
  
  public function irep_postRuleName(){
	if(isset($_REQUEST['ruleId']) && isset($_REQUEST['ruleName'])){
		$ruleName = $_REQUEST['ruleName'];
		$ruleId = intval($_REQUEST['ruleId']);
		$result = $this->core->entity("ireprule")->postRuleName($ruleId,$ruleName);
		print $result;
	}
  }
  
  public function irep_updateRuleValues(){
	if(isset($_REQUEST['ruleId'])){
		$ruleId = intval($_REQUEST['ruleId']);
		$updateData = $_REQUEST['updateData'];
		$resultData = $this->core->entity("ireprule")->updateRuleValues($ruleId,$updateData);
		$result = array("result"=>"success","data"=>array());
		
		foreach($resultData as $data){
			$result["data"][] = array(
				"Id"=>$data["Id"],
				"PropertyType"=>$data["PropertyType"],
				"PropertyKey"=>$data["PropertyKey"],
				"PropertyValue"=>$data["PropertyValue"],
				"OperandType"=>$data["OperandType"]
			);
		}
		
		print json_encode($result);
		return;
	}
	
	print "fail";
  }
  
  public function ireprules_UpdateRulePriority(){
	
	if(isset($_REQUEST['priorityData'])){
		$pdata = $_REQUEST['priorityData'];
		$result = $this->core->entity("ireprule")->updatePriority($pdata);
		if($result) {
			print "success";
			exit;
		}
	}
	print "error";
  }
  
  public function stats() {
    $pageCounts = $this->core->entity("page")->advancedCount();
    $sentenceCounts = $this->core->entity("sentence")->advancedCount();
    
    $this->template->pageCounts = $pageCounts;
    $this->template->sentenceCounts = $sentenceCounts;
    
    $this->template->addStyle("style/admin.css");
    $this->template->load("stats", "panel");
    $this->template->pageTitle = "Stats | Admin Panel";
    $this->template->page = "stats_panel";
    $this->template->hide_right_box = true;
    $this->template->render();
  }
}
?>