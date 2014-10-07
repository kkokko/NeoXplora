<?php 
namespace NeoX\Controller;
use NeoX\Model;
use NeoX\Entity;
use NeoX\Classes;

require_once __DIR__ . "/../train.php";
class TReviewInterpreter extends TTrain {
  
  protected $accessLevel = 'admin';
  
  public function index() {
    $this->template->addScripts(array(
      "js/system/object.js"
    ));
    $this->template->addJSModules(array(
      "NeoX.Modules.InterpreterReviewIndex" => "js/module/interpreter/review/index.js",
      "NeoX.Modules.InterpreterReviewRequests" => "js/module/interpreter/review/requests.js",
      "NeoX.Modules.ButtonComponent" => "js/module/button.js"
    ));
    
    $this->template->thePageId = (isset($_GET['pageId']) && $_GET['pageId'] != "")?$_GET['pageId']:"";
    
    $this->template->load("index", "review/interpreter");
    $this->template->pageTitle = "Interpreter Review";
    $this->template->hide_right_box = true;
    $this->template->render();
  }
  
  public function load() {
    $page = isset($_POST['page'])?$_POST['page']:1;
    $per_page = 10;
    $pagination = array();
    $pageId = (isset($_REQUEST['pageId']) && $_REQUEST['pageId'] != "")?$_REQUEST['pageId']:null;
    
    $interpreterModel = $this->core->model("interpreter", "review");
    $count_data = $interpreterModel->countSentences('ssTrainedRep', $pageId)->fetch_array();
    
    if($count_data['total'] > 0) {
      $pages = ceil($count_data['total'] / $per_page);
      $start = ($page - 1) * $per_page;
      
      $query = $interpreterModel->getSentences('ssTrainedRep', $pageId, $start, $per_page);
      
      if(!$query) {
        $query = $interpreterModel->getSentences('ssTrainedRep', $pageId, 0, $per_page);
        $page = 1;
      }
      
      $sentences = array();
      $rowclass = '';
      while($sentence_data = $query->fetch_array()) {
        $rep = $sentence_data[Entity\TSentence::$tok_rep];
        try {
         if($rep == "") $rep = $this->Delphi()->GuessRepsForSentenceId(intval($sentence_data[Entity\TSentence::$tok_id]))->GetProperty("RepGuessA");
        } catch(\Exception $e) {
          echo json_encode(array("exception" => $e->getMessage()));
          exit;
        }
        $sentences[] = array(
          "id" => $sentence_data[Entity\TSentence::$tok_id],
          "rowclass" => "row1",
          "name" => $sentence_data[Entity\TSentence::$tok_name],
          "rep" => $rep
        );
      }
      
      require_once APP_DIR . "classes/Pagination.php";
      $paginationObj = new Classes\Pagination($pages, $page);
      $pagination_array = $paginationObj->generate();
      
      $this->template->pagination = (array) $pagination_array;
      
      $this->template->currentPage = $page;
      $pagination = $this->template->fetch("pagination", "review/interpreter");
      
      $this->template->sentences = $sentences;
      $data = $this->template->fetch("table", "review/interpreter");
    } else {
      $data = 'There are no sentences to review.';
    }
    
    $response = array(
      'data' => $data,
      'pagination' => $pagination
    );
    
    echo json_encode($response);
  }

  public function approve() {
    if(!isset($_POST['newValue']) || !isset($_POST['sentenceID'])) return;
    $newValue = htmlspecialchars_decode($_POST['newValue'], ENT_QUOTES);
    $sentenceID = (int) $_POST['sentenceID'];
    $validator;
    try {
      $validator = $this->Delphi()->ValidateRep($newValue);
    } catch(\Exception $e) {
      echo json_encode(array("exception" => $e->getMessage()));
      exit;
    }
    if($validator === true) {
      $this->core->entity("sentence")->update(
        $sentenceID,
        array(
          "status" => 'ssReviewedRep',
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
    
  public function dismiss() {
    if(!isset($_POST['sentenceID'])) return;
    $sentenceID = $_POST['sentenceID'];

    $this->core->entity("sentence")->update(
      $sentenceID,
      array(
        "status" => 'ssReviewedSplit'
      )
    );
    
    echo json_encode("");    
  }
  
  public function approveMultiple() {
    if(!isset($_POST['sentenceIDs']) || !is_array($_POST['sentenceIDs'])) return;
    if(!isset($_POST['newValues']) || !is_array($_POST['newValues'])) return;
    
    $sentenceIDs = $_POST['sentenceIDs'];
    $newValues = $_POST['newValues'];
    
    $response = array();
    $response['sentences'] = array();
    $flag = false;
    for($i = 0; $i < count($sentenceIDs); $i++) {
      $newValue = htmlspecialchars_decode($newValues[$i], ENT_QUOTES);
      $validator;
      try {
        $validator = $this->Delphi()->ValidateRep($newValue);
      } catch(\Exception $e) {
        echo json_encode(array("exception" => $e->getMessage()));
        exit;
      }
      if($validator === true) {
        $this->core->entity("sentence")->update(
          $sentenceIDs[$i],
          array(
            "status" => 'ssReviewedRep',
            "rep" => $newValue
          )
        );
        
        $response['sentences'][$sentenceIDs[$i]] = 'Approved';
      } else {
        $flag = true;
        $response['sentences'][$sentenceIDs[$i]] = array(
          "ErrorString" => $validator['ErrorString'], 
          "StrIndex" => substr($newValue, $validator['StrIndex'], strlen($newValue))
        ); 
      }
      
    }
    
    $response['flag'] = $flag;
    
    echo json_encode($response);
  }

  public function dismissMultiple() {
    if(!isset($_POST['sentenceIDs']) || !is_array($_POST['sentenceIDs'])) return;
    
    $sentenceIDs = $_POST['sentenceIDs'];

    for($i = 0; $i < count($sentenceIDs); $i++) {
      $this->core->entity("sentence")->update(
        $sentenceIDs[$i],
        array(
          "status" => 'ssReviewedSplit'
        )
      );
    }
    
    echo json_encode("");
  }

  
}

?>
