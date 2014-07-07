<?php 
namespace NeoX\Controller;
use NeoX\Model;
use NeoX\Entity;

require_once "TrainObject.php";
class TTrainSplitter extends TTrainObject {
  
  public function index() {
    $this->template->addScripts(array(
      "js/system/object.js"
    ));
    $this->template->addJSModules(array(
      "NeoX.Modules.SplitterIndex" => "js/module/splitter/index.js",
      "NeoX.Modules.SplitterRequests" => "js/module/splitter/requests.js",
      "NeoX.Modules.ButtonComponent" => "js/module/button.js"
    ));
    $this->template->load("index", "train/splitter");
    $this->template->pageTitle = "Train Split";
    $this->template->page = "trainsplit";
    $this->template->hide_right_box = true;
    $this->template->render();
  }
  
  public function load() {
    $ignoreIDs = array();
    //unset($_SESSION['ignoredPageIDs']);
    if(isset($_SESSION['ignoredPageIDs']) && is_array($_SESSION['ignoredPageIDs'])) {
      $ignoreIDs = array_values($_SESSION['ignoredPageIDs']);
    }
    $splitterModel = $this->core->model("splitter", "train");
    
    $category_data = $splitterModel->getCategory("ssFinishedGenerate", "psFinishedCrawl", "psFinishedGenerate", $ignoreIDs);
    
    $categoryID = $category_data['id'];
    $pageCount = $category_data['pageCount'];
    $max_offset = min(array($pageCount, 5));
    $offset = rand(0, $max_offset - 1);
    
    $sentenceCount = $splitterModel->countSentences($categoryID, $offset, "ssFinishedGenerate", $ignoreIDs);
    $sentence_offset = rand(0, $sentenceCount['sentenceCount'] - 1);
      
    $sentence_data = $splitterModel->getSentence($categoryID, $offset, $sentence_offset, "ssFinishedGenerate", $ignoreIDs);
    
    $data = 'No sentence to display';
    
    if($sentence_data) {
      $this->core->entity("sentence")->update(
        $sentence_data[Entity\TSentence::$tok_id], 
        array(
          "assigneddate" => date("Y-m-d H:i:s")
        )
      );
      
      $this->template->level = 0;
      $this->template->sentence = $sentence_data[Entity\TSentence::$tok_name];
      $this->template->newSentence = $sentence_data[Entity\TSentence::$tok_name];
      $this->template->index = 1;
      $this->template->indentation = 0;
      $this->template->sentenceID = $sentence_data[Entity\TSentence::$tok_id];
      
      $this->template->splitBtn = true;
      $this->template->dontSplitBtn = true;
      $this->template->skipBtn = true;
      $this->template->approveBtn = true;
      
      $this->template->load("table", "train/splitter");
      
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
    
    if(!isset($_SESSION['ignoredPageIDs'])) { 
      $_SESSION['ignoredPageIDs'] = array();
    }
    $_SESSION['ignoredPageIDs'][] = $sentenceID;
    
    if(count($_SESSION['ignoredPageIDs']) > 10) { 
      unset($_SESSION['ignoredPageIDs'][0]);
    }
    
    $this->core->entity("sentence")->update(
      $sentenceID, 
      array(
        "assigneddate" => date("Y-m-d H:i:s", 0)
      )
    );
    
    echo json_encode("");
  }
  
  public function reset() {
    if(!isset($_POST['sentenceID']) || !isset($_POST['originalValue']) || !isset($_POST['deleteSentences'])) return;
    $sentenceID = $_POST['sentenceID'];
    $originalValue = $_POST['originalValue'];
    $deleteSentences = $_POST['deleteSentences'];
    $newSentenceID = 0;
    
    $sentence_data = $this->core->model("splitter", "train")->getSentenceIdFromIds($deleteSentences);
    
    if($sentence_data) {
      $newSentenceID = $sentence_data[Entity\TSentence::$tok_id];
      $this->core->entity("sentence")->update(
        $newSentenceID,
        array(
          "name" => $originalValue,
          "status" => 'ssFinishedGenerate'
        )
      );
      $this->core->entity("sentence")->delete(
        $deleteSentences,
        array(
          $newSentenceID
        )
      );      
    }
    
    if($newSentenceID > 0) $this->update_status($newSentenceID);
    
    $response = array(
      'newSentenceID' => $newSentenceID
    );
    
    echo json_encode($response);
  }

  public function approve() {
    if(!isset($_POST['sentenceIDs']) || !is_array($_POST['sentenceIDs'])) return;
    $sentenceIDs = $_POST['sentenceIDs'];

    $this->core->entity("sentence")->update(
      $sentenceIDs,
      array(
        'status' => 'ssReviewedSplit'
      )
    );

    $this->update_status(0, $sentenceIDs[0]);
    
    echo json_encode("");
  }

  public function split() {
    if(!isset($_POST['newValue']) || !isset($_POST['sentenceID']) || !isset($_POST['level'])) return;
    
    $level = $_POST['level'];
    $sentenceID = $_POST['sentenceID'];
    $newValue = $_POST['newValue'];
    
    $originalValue = $this->core->entity("sentence")->select($sentenceID, "name");
    $result = $this->Delphi()->SplitSentence($sentenceID, $newValue);

    $newSentencesCount = $result->Count();    
    $data = '';
        
    if($newSentencesCount == 1) {
      $this->template->level = intval($level) + 1;
      $this->template->sentence = $originalValue;
      $this->template->newSentence = $newValue;
      $this->template->index = intval($level) + 2;
      $this->template->indentation = 0;
      $this->template->sentenceID = $sentenceID;
      $this->template->splitBtn = true;
      $this->template->dontSplitBtn = true;
      
      $data = $this->template->fetch("row", "train/splitter");
    } elseif($newSentencesCount > 1) {
      for($i = 0; $i < $newSentencesCount; $i++) {
        $this->template->level = intval($level) + 1;
        $this->template->sentence = $result->Item($i)->GetProperty("Name");
        $this->template->newSentence = $result->Item($i)->GetProperty("Name");
        $this->template->index = (intval($level) + 2) . '.' . ($i +1);
        $this->template->indentation = 8 * (intval($level) + 2);
        $this->template->sentenceID = $result->Item($i)->GetProperty("Id");
        $this->template->splitBtn = true;
        $this->template->dontSplitBtn = true;
        
        $data .= $this->template->fetch("row", "train/splitter");
      }
    }

    $this->update_status($sentenceID);
    
    $response = array(
      'data' => $data,
      'level' => ((int) $level) + 1,
      'newSentencesCount' => $newSentencesCount
    );
    
    echo json_encode($response);
  }
  
  public function dont_split() {
    if(!isset($_POST['sentenceID'])) return;
    $sentenceID = $_POST['sentenceID'];
    $query = $this->core->entity("sentence")->update(
      intval($sentenceID),
      array(
        'status' => 'ssTrainedSplit'
      )
    );
    
    $this->update_status($sentenceID);
    
    echo json_encode("test");
  }
  
}

/*
      if(count($sentences) == 1) {        
          $proto = $this->core->entity("sentence")->select($sentenceID, "protoid");
          
          $protoID = $proto[Entity\TSentence::$tok_protoid];
          $data .= "<tr id='s" . $sentenceID . "' class='asentence row1 pr" . $protoID . "'>";
          $data .= "<td><input type='text' class='newValue' value='" . htmlspecialchars($sentences['0']['sentence'], ENT_QUOTES) . "' /></td>";
          $data .= '<td><a href="javascript:void(0)" class="modifyReviewSplitButton">Modify</a></td>';
          $data .= "</tr>";
          $newSentenceIDs[$sentenceID] = $sentenceID;
          $newSentences[] = $sentenceID;
      */
      /*
        if($IsReview) {
          $data .= "<tr id='s" . $newSentenceID . "' class='asentence row1 pr" . $sentence_data['pr2ID'] . "'>";
          $data .= "<td><input type='text' class='newValue' value='" . htmlspecialchars($sentence['sentence'], ENT_QUOTES) . "' /></td>";
          $data .= '<td><a href="javascript:void(0)" class="modifyReviewSplitButton">Modify</a></td>';
          $data .= "</tr>";
        }*/

?>