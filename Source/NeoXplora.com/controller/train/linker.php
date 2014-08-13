<?php
namespace NeoX\Controller;
use NeoX\Entity;

require_once __DIR__ . "/../train.php";
class TTrainLinker extends TTrain {
  
  public $accessLevel = 'user';
  private $entityList;
  private $sentenceList;
  private $colorList = array(
    "#346799",
    "#990000",
    "#ff8000"
  );
  
  public function index() {
    $this->template->addScripts(array(
      "js/system/object.js",
      "js/classes/List.js",
      "js/classes/StringList.js",
      "js/classes/Interval.js",
      "js/classes/rep/CRepRecord.js"
    ));
    $this->template->addJSModules(array(
      "NeoX.Modules.LinkerTrainIndex" => "js/module/linker/train/index.js",
      "NeoX.Modules.LinkerTrainRequests" => "js/module/linker/train/requests.js",
      "NeoX.Modules.ButtonComponent" => "js/module/button.js"
    ));
    $this->template->addStyle("style/train/linker.css");
    $this->template->load("index", "train/linker");
    $this->template->pageTitle = "Train CRep";
    $this->template->page = "traincrep";
    $this->template->hide_right_box = true;
    $this->template->render();
  }
  
  public function loadPage() {
    $pageData = null;
    if(!isset($_SESSION['pageID'])) {
      $ignore = '';
      if(isset($_SESSION['ignoredPageIDs']) && is_array($_SESSION['ignoredPageIDs'])) {
        $ignore .= " AND st.`pageID` NOT IN (";
        for($i = 0; $i < count($_SESSION['ignoredPageIDs']); $i++) {
          $ignore .= "'" . $_SESSION['ignoredPageIDs'][$i] . "'";
          if($i != count($_SESSION['ignoredPageIDs']) - 1) $ignore .= ', ';
        }
        $ignore .= ") ";
      }

      $linkerModel = $this->core->model("linker", "train");
      $category_data = $linkerModel->getCategory()->fetch_array();
      $categoryID = $category_data[Entity\TCategory::$tok_id];
      
      $pageCount = $category_data['pageCount'];
      $max_offset = min(array($pageCount, 5));
      $offset = rand(0, $max_offset - 1);
      
      $pageData = $linkerModel->getPageByCategoryID($categoryID, $offset);
    } else {
      $pageData = $this->core->entity("page")->select(array("id" => $_SESSION['pageID']), "*"); 
    }
    
    $data = array();
    
    if($pageData) {
      $page_data = $pageData->fetch_array();
      $_SESSION['pageID'] = $page_data[Entity\TPage::$tok_id];
      $pagetitle = $page_data[Entity\TPage::$tok_title];
      
      require_once __DIR__ . "/../../model/entity/sentence.php";
      
      $this->core->entity("page")->update(
        $page_data[Entity\TSentence::$tok_id], 
        array(
          "assigneddate" => date("Y-m-d H:i:s")
        )
      );
      
      $query = $this->core->entity("sentence")->select(array("pageid" => $_SESSION['pageID']), "*", array("id" => "ASC"));
      $sentence_data = $query->fetch_array();
      
      while($sentence_data = $query->fetch_array()) {
        $data[] = (object) array(
          "Id" => $sentence_data[Entity\TSentence::$tok_id], 
          "Sentence" => $sentence_data[Entity\TSentence::$tok_name],
          "Rep" => $sentence_data[Entity\TSentence::$tok_rep],
          "Highlights" => array(),
          "Children" => array()
        );
      }
    }

    echo json_encode($data);
  }

}

?>

