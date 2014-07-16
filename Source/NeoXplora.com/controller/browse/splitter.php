<?php 
namespace NeoX\Controller;
use NeoX\Model;
use NeoX\Entity;
use NeoX\Classes;

require_once __DIR__ . "/../train.php";
class TBrowseSplitter extends TTrain {
  
  protected $accessLevel = 'admin';
  
  public function index() {
    $this->template->addScripts(array(
      "js/system/object.js"
    ));
    $this->template->addJSModules(array(
      "NeoX.Modules.SplitterBrowseIndex" => "js/module/splitter/browse/index.js",
      "NeoX.Modules.SplitterBrowseRequests" => "js/module/splitter/browse/requests.js",
      "NeoX.Modules.ButtonComponent" => "js/module/button.js"
    ));
    $this->template->load("index", "browse/splitter");
    $this->template->pageTitle = "Splitter Browse";
    $this->template->page = "trainsplit";
    $this->template->hide_right_box = true;
    $this->template->render();
  }
  
  public function load() { 
    $page = isset($_POST['page'])?$_POST['page']:1;
    $per_page = 5;
    $pagination = array();
    
    $splitterModel = $this->core->model("splitter", "browse");
    $count_data = $splitterModel->countProtos()->fetch_array();
    
    if($count_data['total'] > 0) {
      $pages = ceil($count_data['total'] / $per_page);
      $start = ($page - 1) * $per_page;
      
      $query = $splitterModel->getSentences($start, $per_page);
      
      if(!$query) {
        $query = $splitterModel->getSentences(0, $per_page);
        $page = 1;
      }
      
      $protos = array();
      $rowclass = '';
      while($sentence_data = $query->fetch_array()) {
        if(!isset($protos[$sentence_data['protoId']])) {
          $protos[$sentence_data['protoId']] = array(
            "id" => $sentence_data['protoId'],
            "name" => $sentence_data['protoName'],
            "pageid" => $sentence_data['pageId'],
            "sentences" => array()
          );
          $rowclass = '';
        }
        
        $rowclass = (($rowclass == "row1")?"row2":"row1");
        
        $protos[$sentence_data['protoId']]['sentences'][] = array(
          "id" => $sentence_data['sentenceId'],
          "rowclass" => $rowclass,
          "name" => $sentence_data['sentenceName']
        );
      }
      
      require_once APP_DIR . "classes/Pagination.php";
      $paginationObj = new Classes\Pagination($pages, $page);
      $pagination_array = $paginationObj->generate();
      
      $this->template->pagination = (array) $pagination_array;
      
      $this->template->currentPage = $page;
      $pagination = $this->template->fetch("pagination", "browse/splitter");
      
      $this->template->protos = $protos;
      $data = $this->template->fetch("table", "browse/splitter");
    } else {
      $data = 'There are no sentences to browse.';
    }
    
    $response = array(
      'data' => $data,
      'pagination' => $pagination
    );
    
    echo json_encode($response);
  }

 }
?>