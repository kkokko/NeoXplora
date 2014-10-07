<?php
namespace NeoX\Controller;
use NeoX\Entity;

require_once __DIR__ . "/../train.php";
class TTrainLinker extends TTrain {
  
  public $accessLevel = 'user';
  private $data = array();
  private $sentenceIDs = array();
  
  public function __construct($registry) {
    //\SkyCore\TModel::$LogSqls = true;
    parent::__construct($registry);
  }
  
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
    
    if(!isset($_SESSION['linkerCategoryId'])) {
      $_SESSION['linkerCategoryId'] = -1;
    }
    
    $categoryData = $this->core->entity("category")->select();
    $categoryList = array();
    
    while($result = $categoryData->fetch_array()) {
      $categoryList[$result[Entity\TCategory::$tok_id]] = $result[Entity\TCategory::$tok_name]; 
    }
    
    $this->template->thePageId = (isset($_GET['pageId']) && $_GET['pageId'] != "")?$_GET['pageId']:"";
    
    $this->template->currentCategory = $_SESSION['linkerCategoryId'];
    $this->template->categoryList = $categoryList;
    
    $this->template->addStyle("style/train/linker.css");
    $this->template->load("index", "train/linker");
    $this->template->pageTitle = "Train CRep";
    $this->template->page = "traincrep";
    $this->template->hide_right_box = true;
    $this->template->render();
  }
  
  public function catChanged() {
    if(!isset($_POST['categoryId'])) return;
    
    $_SESSION['linkerCategoryId'] = intval($_POST['categoryId']);
    $_SESSION['pageID'] = -1;
    $_SESSION['ignoredLinkerPageIDs'] = array();
    
    echo json_encode("");
  }
  
  public function loadPage() {
    $pageData = null;
    $pageId = (isset($_REQUEST['pageId']) && $_REQUEST['pageId'] != "")?$_REQUEST['pageId']:null;
    
    if($pageId && $this->core->model("linker", "train")->checkPageId($pageId)) {
      $pageData = $this->core->entity("page")->select(array("id" => $pageId), "*");
    } else {
      if(!isset($_SESSION['pageID']) || $_SESSION['pageID'] == -1 || !$this->core->model("linker", "train")->checkPageId($_SESSION['pageID'])) {
        $ignoreIDs = array();
        
        if(isset($_SESSION['ignoredLinkerPageIDs']) && is_array($_SESSION['ignoredLinkerPageIDs'])) {
          $ignoreIDs = array_values($_SESSION['ignoredLinkerPageIDs']);
        }
        
        $linkerModel = $this->core->model("linker", "train");
        
        $pageData = $linkerModel->getPage($ignoreIDs, $_SESSION['linkerCategoryId']);
      } else {
        $pageData = $this->core->entity("page")->select(array("id" => $_SESSION['pageID']), "*"); 
      }
    }
    
    $pageTitle = "-";
    
    if($pageData->num_rows) {
      $page_data = $pageData->fetch_array();
      
      $query = $this->core->entity("page")->select(array("id" => $page_data[Entity\TPage::$tok_id]), "title");
      $res = $query->fetch_array();
      $pageTitle = $res[Entity\TPage::$tok_title];
      
      if(!$pageId) {
        $_SESSION['pageID'] = $page_data[Entity\TPage::$tok_id];
        $pageId = $_SESSION['pageID'];
      }
      $pagetitle = $page_data[Entity\TPage::$tok_title];
      
      require_once __DIR__ . "/../../model/entity/sentence.php";
      
      $this->core->entity("page")->update(
        $page_data[Entity\TSentence::$tok_id], 
        array(
          "assigneddate" => date("Y-m-d H:i:s")
        )
      );
      
      $this->loadSentences($pageId);
      $this->loadHighlights($this->sentenceIDs);
      $this->fixDataArray();
    }

    $response = array(
      'data' => $this->data,
      'pageTitle' => htmlspecialchars($pageTitle, ENT_QUOTES)
    );
    
    echo json_encode($response);
  }

  private function fixDataArray() {
    $this->data = array_values($this->data);
    foreach($this->data as &$sentence) {
      $sentence['Children'] = array_values($sentence['Children']);
    }
  }

  private function loadSentences($pageId) {
    $query = $this->core->model("linker", "train")->getSentences($pageId);
    while($row = $query->fetch_array()) {
      if($row['Type'] == 'se') {
        $this->sentenceIDs[] = $row['Id'];
      }
      $this->data[$row['Id']] = array(
        "Id" => $row['Id'], 
        "Sentence" => htmlspecialchars($row['Name'], ENT_QUOTES),
        "Rep" => $row['Rep'],
        "Indentation" => $row['Indentation'],
        "Type" => $row['Type'],
        "Highlights" => array(),
        "Children" => array()
      );
    }
  }

  private function loadHighlights($sentenceIds) {
    $query = $this->core->model("linker", "train")->getHighlights($sentenceIds);
    
    while($row = $query->fetch_array()) {
      if($row['ParentId'] == null) {
        $this->data[$row['SentenceId']]['Highlights'][] = array(
          "HID" => $row['hid'],
          "From" => $row['From'],
          "Until" => $row['Until'],
          "Style" => $row['Style']
        );
      } else {
        if(!isset($this->data[$row['SentenceId']]['Children'][$row['CRepId']])) {
          $this->data[$row['SentenceId']]['Children'][$row['CRepId']] = array();
        }
        $this->data[$row['SentenceId']]['Children'][$row['CRepId']][] = array(
          "From" => $row['From'],
          "Until" => $row['Until'],
          "Style" => $row['Style']
        );
      }
    }
  }
  
  public function save() {
    if(!isset($_SESSION['pageID']) && !isset($_POST['pageId'])) return;
    $flag = true;
    if(isset($_REQUEST['pageId']) && ($_REQUEST['pageId'] == "" || $_REQUEST['pageId'] == -1)) $flag = false;
    else $flag = true;
    if(isset($_SESSION['pageID']) && ($_SESSION['pageID'] == "" || $_SESSION['pageID'] == -1)) $flag = $flag || false;
    else $flag = $flag || true;
    
    if(!$flag) return;
    
    $data = $_POST['data'];
    $pageId = (isset($_POST['pageId']) && $_POST['pageId'] != "")?$_POST['pageId']:$_SESSION['pageID'];
    
    if(!$this->core->model("linker", "train")->checkPageId($pageId)) return;
    
    $this->saveData($data, $pageId);
    
    echo json_encode('');
  }

  public function finish() {
    if(!isset($_SESSION['pageID']) && !isset($_REQUEST['pageId'])) return;
    $flag = true;
    if(isset($_REQUEST['pageId']) && ($_REQUEST['pageId'] == "" || $_REQUEST['pageId'] == -1)) $flag = false;
    else $flag = true;
    if(isset($_SESSION['pageID']) && ($_SESSION['pageID'] == "" || $_SESSION['pageID'] == -1)) $flag = $flag || false;
    else $flag = $flag || true;
    
    if(!$flag) return;
    
    $data = $_POST['data'];
    $pageId = (isset($_POST['pageId']) && $_POST['pageId'] != "")?$_POST['pageId']:$_SESSION['pageID'];
    
    if(!$this->core->model("linker", "train")->checkPageId($pageId)) return;
    
    $this->saveData($data, $pageId);
    
    $this->core->entity("page")->update(
      $pageId, 
      array(
        "status" => "psReviewedCRep"
      )
    );
    
    unset($_SESSION['pageID']);
    $_SESSION['pageID'] = -1;
    
    echo json_encode('');
  }
  
  public function skip() {
    if(!isset($_SESSION['pageID'])) return;
    
    if(!isset($_SESSION['ignoredLinkerPageIDs'])) { 
      $_SESSION['ignoredLinkerPageIDs'] = array();
    }
    if(!in_array($_SESSION['pageID'], $_SESSION['ignoredLinkerPageIDs']) && $_SESSION['pageID'] != -1) {
      $_SESSION['ignoredLinkerPageIDs'][] = $_SESSION['pageID'];
    }
    
    if(count($_SESSION['ignoredLinkerPageIDs']) > 10) { 
      $_SESSION['ignoredLinkerPageIDs'] = array_values(array_slice($_SESSION['ignoredLinkerPageIDs'], 1));
    }
    
    $linkerModel = $this->core->model("linker", "train");
      
    $pageData = $linkerModel->getPage($_SESSION['ignoredLinkerPageIDs'], $_SESSION['linkerCategoryId']);
    
    $count_data = $this->core->model("linker", "train")->countPages($_SESSION['interpreterCategoryId'])->fetch_array();
    
    if($count_data['total'] == count($_SESSION['ignoredLinkerPageIDs'])) {
      $_SESSION['ignoredInterpreterPageIDs'] = array();
    }
    
    $this->core->entity("page")->update(
      $_SESSION['pageID'], 
      array(
        "assigneddate" => date("Y-m-d H:i:s", 0)
      )
    );
    
    unset($_SESSION['pageID']);
    $_SESSION['pageID'] = -1;
    
    echo json_encode("");
  }
  
  private function saveData($data, $pageId) {
    $this->core->entity("crephighlight")->delete(
      array( "pageid" => array( $pageId ) )
    );
    
    $this->core->entity("crep")->delete(
      array( "pageid" => array( $pageId ) )
    );
    
    for($i = 0; $i < count($data); $i++) {
      $this->core->entity("crep")->insert(
        array( "pageid", "sentenceid" ),
        array( array( $pageId, $data[$i]['Id'] ) )
      );
      
      $crepId = $this->db->insert_id;
      
      if(isset($data[$i]['Highlights'])) {
        for($j = 0; $j < count($data[$i]['Highlights']); $j++) {
          $this->core->entity("crephighlight")->insert(
            array( "pageid", "crepid", "from", "until", "style" ),
            array( array( $pageId, $crepId, $data[$i]['Highlights'][$j]['From'], $data[$i]['Highlights'][$j]['Until'], $data[$i]['Highlights'][$j]['Style'] ) )
          );
        }
      }
      
      if(isset($data[$i]['Children'])) {
        for($j = 0; $j < count($data[$i]['Children']); $j++) {
          $this->core->entity("crep")->insert(
            array( "pageid", "sentenceid", "parentcrepid" ),
            array( array( $pageId, $data[$i]['Id'], $crepId) )
          );
          
          $childCrepId = $this->db->insert_id;
          
          for($k = 0; $k < count($data[$i]['Children'][$j]); $k++) {
            $this->core->entity("crephighlight")->insert(
              array("pageid", "crepid", "from", "until", "style" ),
              array( array( $pageId, $childCrepId, $data[$i]['Children'][$j][$k]['From'], $data[$i]['Children'][$j][$k]['Until'], $data[$i]['Children'][$j][$k]['Style'] ) )
            );
          }
        }
      }
    }
  }

}

?>

