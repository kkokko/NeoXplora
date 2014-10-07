<?php 
namespace NeoX\Controller;
use NeoX\Model;
use NeoX\Entity;
use NeoX\Classes;

require_once __DIR__ . "/../train.php";
class TBrowseInterpreter extends TTrain {
  
  protected $accessLevel = 'user';
  
  public function index() {
    $this->template->addScripts(array(
      "js/system/object.js"
    ));
    $this->template->addJSModules(array(
      "NeoX.Modules.InterpreterBrowseIndex" => "js/module/interpreter/browse/index.js",
      "NeoX.Modules.InterpreterBrowseRequests" => "js/module/interpreter/browse/requests.js",
      "NeoX.Modules.ButtonComponent" => "js/module/button.js"
    ));
    $this->template->load("index", "browse/interpreter");
    $this->template->pageTitle = "Interpreter Browse";
    $this->template->page = "traininterpreter";
    $this->template->hide_right_box = true;
    $this->template->render();
  }
  
  public function load() { 
    $page = isset($_POST['page'])?$_POST['page']:1;
    $per_page = 10;
    $pagination = array();
    
    $interpreterModel = $this->core->model("interpreter", "browse");
    $count_data = $interpreterModel->countSentences()->fetch_array();
    
    if($count_data['total'] > 0) {
      $pages = ceil($count_data['total'] / $per_page);
      $start = ($page - 1) * $per_page;
      
      $query = $interpreterModel->getSentences($start, $per_page);
      
      if(!$query || $query->num_rows < 1) {
        $query = $interpreterModel->getSentences(0, $per_page);
        $page = 1;
      }
      
      $protos = array();
      $rowclass = '';
      while($sentence_data = $query->fetch_array()) {
        if(!isset($sentences[$sentence_data['sentenceId']])) {
          $sentences[$sentence_data['sentenceId']] = array(
            "id" => $sentence_data['sentenceId'],
            "name" => $sentence_data['sentenceName'],
            "rep" => $sentence_data['sentenceRep']
          );
        }
      }
      
      require_once APP_DIR . "classes/Pagination.php";
      $paginationObj = new Classes\Pagination($pages, $page);
      $pagination_array = $paginationObj->generate();
      
      $this->template->pagination = (array) $pagination_array;
      $this->template->currentPage1 = $count_data;
      $this->template->currentPage = $page;
      $pagination = $this->template->fetch("pagination", "browse/interpreter");
      
      $this->template->sentences = $sentences;
      $data = $this->template->fetch("table", "browse/interpreter");
    } else {
      $data = 'There are no sentences to browse.';
    }
    
    $response = array(
      'data' => $data,
      'pagination' => $pagination
    );
    
    echo json_encode($response);
  }
  
  public function save() {
    if(!isset($_POST['sentenceID']) || !isset($_POST['newValue'])) return;
          
    $sentenceID = $_POST['sentenceID'];
    $newValue = trim(htmlspecialchars_decode($_POST['newValue'], ENT_QUOTES));
    
    if(trim($newValue) == ''){
      return;
    }
    
    $validator = $this->Delphi()->ValidateRep($newValue);
    
    if($validator === true) {      
      $this->core->entity("sentence")->update(
        $sentenceID, 
        array(
          "rep" => $newValue
        )
      );
      
      echo json_encode("");
    } else {
      $response = array(
        "ErrorString" => $validator['ErrorString'],
        "StrIndex" => $validator['StrIndex']
      ); 
      echo json_encode($response);
    }
  }
  
  public function resplit() {
    if(!isset($_POST['sentenceID'])) return;
    
    $sentenceID = $_POST['sentenceID'];
    $this->core->entity("sentence")->update(
      $sentenceID,
      array(
        "status" => "ssFinishedGenerate"
      )
    );
    
    $query = $this->core->entity("sentence")->select(
      array("id" => array($sentenceID)),
      "pageid"
    );
    $result = $query->fetch_array();
    $pageId = $result[Entity\TSentence::$tok_pageid];
    
    $this->core->entity("crep")->delete(
      array(
        'pageid' => array($pageId)
      )
    );
    
    $this->core->entity("crephighlight")->delete(
      array(
       'pageid' => array($pageId)
      )
    );
    
    echo json_encode("");
  }

}

?>