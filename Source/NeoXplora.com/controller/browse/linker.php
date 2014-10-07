<?php
namespace NeoX\Controller;
use NeoX\Entity;
use NeoX\Classes;

require_once __DIR__ . "/../train.php";
class TBrowseLinker extends TTrain {
  
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
      "NeoX.Modules.LinkerBrowseIndex" => "js/module/linker/browse/index.js",
      "NeoX.Modules.LinkerBrowseRequests" => "js/module/linker/browse/requests.js",
      "NeoX.Modules.ButtonComponent" => "js/module/button.js"
    ));
    $this->template->addStyle("style/train/linker.css");
    $this->template->load("index", "browse/linker");
    $this->template->pageTitle = "Browse CRep";
    $this->template->page = "brwosecrep";
    $this->template->hide_right_box = true;
    $this->template->render();
  }
  
  public function loadPage() {
    $page = isset($_POST['page'])?$_POST['page']:1;
    $per_page = 1;
    $pagination = array();
    
    $linkerModel = $this->core->model("linker", "browse");
    $count_data = $linkerModel->countPages()->fetch_array();
    $pageId = -1;
    
    $data = array();
    
    if($count_data['total'] > 0) {
      $pages = ceil($count_data['total'] / $per_page);
      $start = ($page - 1) * $per_page;
      
      $query = $linkerModel->getPages($start, $per_page);
      
      if(!$query || $query->num_rows < 1) {
        $query = $linkerModel->getPages(0, $per_page);
      }
      
      $page_data = $query->fetch_array();
      $pagetitle = $page_data[Entity\TPage::$tok_title];
      $pageId = $page_data[Entity\TPage::$tok_id];
      
      require_once __DIR__ . "/../../model/entity/sentence.php";
      
      $this->loadSentences($pageId);
      $this->loadHighlights($this->sentenceIDs);
      $this->fixDataArray();

      require_once APP_DIR . "classes/Pagination.php";
      $paginationObj = new Classes\Pagination($pages, $page);
      $pagination_array = $paginationObj->generate();
      
      $this->template->pagination = (array) $pagination_array;
      $this->template->currentPage1 = $count_data;
      $this->template->currentPage = $page;
      $pagination = $this->template->fetch("pagination", "browse/linker");
    }

    $response = array(
      'data' => $this->data,
      'pageid' => $pageId,
      'pagination' => $pagination
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

  public function retrain() {
    if(!isset($_POST['pageId'])) return;
    
    $pageId = $_POST['pageId'];
    
    $this->core->entity("page")->update(
      $pageId, 
      array(
        "status" => "psReviewedRep"
      )
    );
        
    echo json_encode('');
  }

}

?>

