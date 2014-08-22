<?php
namespace NeoX\Controller;
use NeoX\Model;
use NeoX\Entity;
use NeoX\Classes;

require_once __DIR__ . "/../panel.php";

class TPanelPages extends TPanel {

  // public $accessLevel = 'admin';

  public function index() {
    $this->template->addStyle("style/admin.css");
    $this->template->addStyle("style/admin.pages.css");
    $this->template->addStyle("style/train/linker.css");
    $this->template->addScripts(array("js/system/object.js"));

    $this->template->load("index", "panel/pages");
    $this->template->pageTitle = "Manage Pages | Admin Panel";
    $this->template->page = "pages_panel";
    /*$this->template->addJSModules(array(
      "NeoX.Modules.LinkerRuleBrowseIndex" => "js/module/linkerrules/browse/index.js",
      "NeoX.Modules.LinkerRuleBrowseRequests" => "js/module/linkerrules/browse/requests.js"
    ));*/
    
    $page = isset($_GET['page'])?$_GET['page']:1;
    $per_page = 15;
    $pagination = "";
    $pageData = array();
    
    $linkerModel = $this->core->model("pages", "panel");
    $count_data = $linkerModel->countPages()->fetch_array();
    
    if($count_data['total'] > 0) {
      $pages = ceil($count_data['total'] / $per_page);
      $start = ($page - 1) * $per_page;
      
      $query = $linkerModel->getPages($start, $per_page);
      
      if(!$query || $query->num_rows < 1) {
        $query = $linkerModel->getPages(0, $per_page);
      }
      
      while($result = $query->fetch_array()) {
        $pageData[] = array(
          "Id" => $result[Entity\TPage::$tok_id],
          "Title" => $result[Entity\TPage::$tok_title]
        );
      }
      
      require_once APP_DIR . "classes/Pagination.php";
      $paginationObj = new Classes\Pagination($pages, $page);
      $pagination_array = $paginationObj->generate();
      
      $this->template->pagination = (array) $pagination_array;
      $this->template->currentPage1 = $count_data;
      $this->template->currentPage = $page;
      $pagination = $this->template->fetch("pagination", "panel/pages");
    }
    
    $this->template->pageList = $pageData;
    $this->template->pagination = $pagination;
    
    $this->template->hide_right_box = true;
    $this->template->render();
  }

  public function add() {
    $this->template->addStyle("style/admin.pages.css");
    
    /*$this->template->addJSModules(array(
      "NeoX.Modules.LinkerRuleEditIndex" => "js/module/linkerrules/edit/index.js",
      "NeoX.Modules.LinkerRuleEditRequests" => "js/module/linkerrules/edit/requests.js"
    ));*/
    $this->template->load("add", "panel/pages");
    $this->template->pageTitle = "Add Page | Admin Panel";
    $this->template->page = "add_pages_panel";

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
 

}
?>