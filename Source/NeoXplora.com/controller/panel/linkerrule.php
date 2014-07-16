<?php 
namespace NeoX\Controller;
use NeoX\Model;
use NeoX\Entity;

require_once __DIR__ . "/../panel.php";
require_once __DIR__ . "/../../model/entity/linkerrule.php";

class TPanelLinkerRule extends TPanel {
  
 // public $accessLevel = 'admin';
  
  public function index() {
    $this->template->addScripts(array(
      "js/system/object.js"
    ));
    $this->template->addStyle("style/jquery-ui.css");
    $this->template->addStyle("style/dataTables.jqueryui.css");
   
   
    
    $this->template->addScript("js/jquery.dataTables.js");
    $this->template->addScript("js/dataTables.jqueryui.js");
    
    $this->template->addScript("js/panel.linkerrule.js");
  
    $this->template->load("linkerrule", "panel");
    $this->template->pageTitle = "Linker Rule";
    $this->template->page = "linkerrule";
    
    $this->template->linkerRulesDB = array(
        "id" => Entity\TLinkerRule::$tok_id,
        "name" =>  Entity\TLinkerRule::$tok_name,
        "type" => Entity\TLinkerRule::$tok_type,
        "order" => Entity\TLinkerRule::$tok_order
      );
      
    $LinkerRulesList = $this->core->entity("linkerrule")->myGetLinkerRules();
    $this->template->rulesList = $LinkerRulesList;
   
    $this->template->hide_right_box = true;
    $this->template->render();
  }
  
  public function addEditLinkerRule() {
    $this->template->load("linkerruleaddEdit", "panel");
    $this->template->pageTitle = "Linker Rule";
    $this->template->page = "linkerruleaddEdit";
    
    $this->template->addStyle("style/panel/linkerrule.css");
    $this->template->addStyle("style/bootstrap.min.css");
    $this->template->addStyle("style/validationEngine.jquery.css");
    
    
    $this->template->addScript("js/bootstrap.min.js");
    $this->template->addScript("js/jquery.validate.js");
     
    $this->template->addScript("js/jquery-validate.bootstrap-tooltip.js");
 
    
      
    $linkerRuleId = isset($_REQUEST['linkerRuleId'])?$_REQUEST['linkerRuleId']:'';
    $LinkerRulesList = array();
    if($linkerRuleId != '')
    {
        $LinkerRulesList = $this->core->entity("linkerrule")->getLinkerRuleForId($linkerRuleId);
        
        if($LinkerRulesList[Entity\TLinkerRule::$tok_type] != 'rtNegate')
        {
          $LinkerRulesList[Entity\TLinkerRule::$tok_type.'2'] = 'checked';
          $LinkerRulesList[Entity\TLinkerRule::$tok_type.'1'] = '';
        }
        else 
        {
      	  $LinkerRulesList[Entity\TLinkerRule::$tok_type.'1'] = 'checked';
          $LinkerRulesList[Entity\TLinkerRule::$tok_type.'2'] = '';
        }
    }
    else 
    {
          $LinkerRulesList[Entity\TLinkerRule::$tok_type.'1'] = 'checked';
          $LinkerRulesList[Entity\TLinkerRule::$tok_type.'2'] = '';
    }
   
    $this->template->rulesList = array(
        "id" => isset($LinkerRulesList[Entity\TLinkerRule::$tok_id])?$LinkerRulesList[Entity\TLinkerRule::$tok_id]:'',
        "name" =>  isset($LinkerRulesList[Entity\TLinkerRule::$tok_name])?$LinkerRulesList[Entity\TLinkerRule::$tok_name]:'',
        "type" => isset($LinkerRulesList[Entity\TLinkerRule::$tok_type])?$LinkerRulesList[Entity\TLinkerRule::$tok_type]:'',
        "type1" => isset($LinkerRulesList[Entity\TLinkerRule::$tok_type.'1'])?$LinkerRulesList[Entity\TLinkerRule::$tok_type.'1']:'',
        "type2" => isset($LinkerRulesList[Entity\TLinkerRule::$tok_type.'2'])?$LinkerRulesList[Entity\TLinkerRule::$tok_type.'2']:'',
        "order" => isset($LinkerRulesList[Entity\TLinkerRule::$tok_order])?$LinkerRulesList[Entity\TLinkerRule::$tok_order]:''
      );
      
   // $this->template->rulesList = $LinkerRulesList;
   
    $this->template->hide_right_box = true;
    $this->template->render();
    
    }
  
  public function deleteLinkerRule() {
    
    $linkerRuleId = $_REQUEST['linkerRuleId'];
    
    $this->core->entity("linkerrule")->delete((int)$linkerRuleId,null);   
    
    $_SESSION['msg'] = "Linker Rule is deleted successfuly.";
    
    header('location: ' .  'panel.php?type=linkerrule'); 
      
    }
  
  public function saveLinkerRule() {
    
    $linkerRuleId = $_REQUEST['linkerRuleId'];
    $linkerRuleName = $_REQUEST['linkerRuleName'];
    $linkerRuleType = $_REQUEST['linkerRuleType'];
   
    $linkerRuleResult = $this->core->entity("linkerrule")->getMaxId();
   
    $linkerRuleOrder = $linkerRuleResult['Id'] + 1;
          
    if($linkerRuleId == '')
    {
        $linkerInsertValue[0][] = $linkerRuleName;
        $linkerInsertValue[0][] = $linkerRuleType;
        $linkerInsertValue[0][] = $linkerRuleOrder;
        
      $this->core->entity("linkerrule")->insert(
      array("name", "type", "order"), 
      $linkerInsertValue
      );
      
      $_SESSION['msg'] = "Linker Rule is inserted successfuly.";
      
      header('location: ' .  'panel.php?type=linkerrule'); 
    }
    else 
    {
	     $this->core->entity("linkerrule")->update(
        (int)$linkerRuleId,
        array(
          'name' => $linkerRuleName, 
          'type' => $linkerRuleType
        )
      );
      
      $_SESSION['msg'] = "Linker Rule is updated successfuly.";
      
      header('location: ' .  'panel.php?type=linkerrule'); 
    }
  
  }
}
?>