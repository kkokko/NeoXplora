<?php 
namespace NeoX\Controller;
use NeoX\Model;
use NeoX\Entity;

require_once __DIR__ . "/../train.php";
class TTrainInterpreter extends TTrain {
  
  protected $accessLevel = 'user';
  
  public function index() {
    $this->template->addScripts(array(
      "js/system/object.js"
    ));
    $this->template->addJSModules(array(
      "NeoX.Modules.InterpreterTrainIndex" => "js/module/interpreter/train/index.js",
      "NeoX.Modules.InterpreterTrainRequests" => "js/module/interpreter/train/requests.js",
      "NeoX.Modules.ButtonComponent" => "js/module/button.js"
    ));
    $this->template->load("index", "train/interpreter");
    $this->template->pageTitle = "Interpreter";
    $this->template->page = "trainsplit";
    $this->template->hide_right_box = true;
    $this->template->render();
  }
  
  public function load() {
    $ignoreIDs = array();
    if(isset($_SESSION['ignoredInterpreterPageIDs']) && is_array($_SESSION['ignoredInterpreterPageIDs'])) {
      $ignoreIDs = array_values($_SESSION['ignoredInterpreterPageIDs']);
    }
    $trainModel = $this->core->model("train");
    $sentence_data = null;
    
    if($GLOBALS['random_training'] == true) {
      $category_data = $trainModel->getCategory("ssReviewedSplit", "psFinishedCrawl", "psFinishedGenerate", $ignoreIDs);
  
      $category_data = $category_data->fetch_array();
      $categoryID = $category_data['id'];
      $pageCount = $category_data['pageCount'];
      $max_offset = min(array($pageCount, 5));
      $offset = rand(0, $max_offset - 1);
      
      $sentenceCount = $trainModel->countSentences($categoryID, $offset, "ssReviewedSplit", $ignoreIDs)->fetch_array();
      $sentence_offset = rand(0, $sentenceCount['sentenceCount'] - 1);
        
      $sentence_data = $trainModel->getSentence($categoryID, $offset, $sentence_offset, "ssReviewedSplit", $ignoreIDs);
    } else {
      $sentence_data = $trainModel->getSentenceNotRandom("ssReviewedSplit", $ignoreIDs);
    }
    
    $data = 'No sentence to display';
    
    if($sentence_data && $sentence_data->num_rows) {
      $sentence_data = $sentence_data->fetch_array();
      $this->core->entity("sentence")->update(
        $sentence_data[Entity\TSentence::$tok_id], 
        array(
          "assigneddate" => date("Y-m-d H:i:s")
        )
      );

      $request = $this->Delphi()->GuessRepsForSentenceId(intval($sentence_data[Entity\TSentence::$tok_id]));

      $this->template->sentence = array(
        "id" => $sentence_data[Entity\TSentence::$tok_id],
        "name" =>  $sentence_data[Entity\TSentence::$tok_name],
        "representation" => $sentence_data[Entity\TSentence::$tok_rep],
        "guess" => $request->GetProperty("RepGuessA")
      );
      
      $this->template->load("table", "train/interpreter");
      
      $data = $this->template->parse();
    }
    
    $response = array(
      'data' => $data
    );
    
    echo json_encode($response);
  }

  public function skip() {
    if(!isset($_POST['sentenceID'])) return;
    $sentenceID = $_POST['sentenceID'];
    
    if(!isset($_SESSION['ignoredInterpreterPageIDs'])) { 
      $_SESSION['ignoredInterpreterPageIDs'] = array();
    }
    
    if(!in_array($sentenceID, $_SESSION['ignoredInterpreterPageIDs'])) {
      $_SESSION['ignoredInterpreterPageIDs'][] = $sentenceID;
    }
    
    if(count($_SESSION['ignoredInterpreterPageIDs']) > 10) { 
      unset($_SESSION['ignoredInterpreterPageIDs'][0]);
    }
    
    $this->core->entity("sentence")->update(
      $sentenceID, 
      array(
        "assigneddate" => date("Y-m-d H:i:s", 0)
      )
    );
    
    echo json_encode("");
  }
  
  public function save() {
    if(!isset($_POST['sentenceID']) || !isset($_POST['newValue'])) return;
          
    $sentenceID = $_POST['sentenceID'];
    $newValue = trim(htmlspecialchars_decode($_POST['newValue'], ENT_QUOTES));
    $approved =  $_POST['approved'];
    
    if(trim($newValue) == ''){
      return;
    }
    
    $validator = $this->Delphi()->ValidateRep($newValue);
    $status = 'ssTrainedRep';
    if($approved) $status = 'ssReviewedRep';
    
    if($validator === true) {      
      $this->core->entity("sentence")->update(
        $sentenceID, 
        array(
          "rep" => $newValue,
          "status" => $status
        )
      );
      $this->updatePageStatus($sentenceID);
      echo json_encode("");
    } else {
      $response = array(
        "ErrorString" => $validator['ErrorString'],
        "StrIndex" => $validator['StrIndex']
      ); 
      echo json_encode($response);
    }
  }
  
  public function approveGuess() {
    if(!isset($_POST['sentenceID'])) return;
    $sentenceID = (int) $_POST['sentenceID'];
    $approved =  $_POST['approved'];
    
    $repguess = $this->Delphi()->GuessRepsForSentenceId($sentenceID)->GetProperty("RepGuessA");
    
    $validator = $this->Delphi()->ValidateRep($repguess);
    $status = 'ssTrainedRep';
    if($approved) $status = 'ssReviewedRep';
    
    if($validator === true) {
      $this->core->entity("sentence")->update(
        $sentenceID, 
        array(
          "rep" => $repguess,
          "status" => $status
        )
      );
      $this->updatePageStatus($sentenceID);
      echo json_encode("");
    } else {
      $response = array(
        "ErrorString" => $validator['ErrorString'],
        "StrIndex" => $validator['StrIndex']
      ); 
      echo json_encode($response);
    }
  }

  public function approve() {
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
          "rep" => $newValue,
          "status" => 'ssReviewedRep'
        )
      );
      $this->updatePageStatus($sentenceID);
      echo json_encode("");
    } else {
      $response = array(
        "ErrorString" => $validator['ErrorString'],
        "StrIndex" => $validator['StrIndex']
      ); 
      echo json_encode($response);
    }
  }
  
}

?>