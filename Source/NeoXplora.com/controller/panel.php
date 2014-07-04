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
	
	$IRepRulesList = $this->core->model("ireprule")->getRulesList();
	$this->template->rulesList = $IRepRulesList;
	
    $this->template->hide_right_box = true;
    $this->template->render();
  }
  
  public function ireprules_add(){
	$this->template->addStyle("style/admin.css");
	$this->template->addStyle("style/admin.irep.css");
	$this->template->addScript("js/fw/skyjs.js");
	$this->template->addScript("js/classes/IRepConditionParser.js");
	$this->template->addScript("js/classes/TBaseRule.js");
	$this->template->addScript("js/classes/TRuleGroup.js");
	$this->template->addScript("js/classes/TRuleValue.js");
	$this->template->addScript("js/panel.irep.add.js");
    $this->template->load("ireprules_add", "panel");
    $this->template->pageTitle = "IRep rules - Add | Admin Panel";
    $this->template->page = "ireprules_panel";
	
    $this->template->hide_right_box = true;
    $this->template->render();
  }
  
  public function ireprules_UpdateRulePriority(){
	
	if(isset($_REQUEST['priorityData'])){
		$pdata = $_REQUEST['priorityData'];
		$result = $this->core->model("ireprule")->updatePriority($pdata);
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