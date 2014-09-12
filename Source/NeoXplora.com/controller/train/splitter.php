<?php 
namespace NeoX\Controller;
use NeoX\Model;
use NeoX\Entity;

require_once __DIR__ . "/../train.php";
class TTrainSplitter extends TTrain {
  
  protected $accessLevel = 'user';
  
  public function index() {
    $this->template->addScripts(array(
      "js/system/object.js"
    ));
    $this->template->addJSModules(array(
      "NeoX.Modules.SplitterTrainIndex" => "js/module/splitter/train/index.js",
      "NeoX.Modules.SplitterTrainRequests" => "js/module/splitter/train/requests.js",
      "NeoX.Modules.ButtonComponent" => "js/module/button.js"
    ));
    $this->template->addStyle("style/train.css");
    
    if(!isset($_SESSION['splitCategoryId'])) {
      $_SESSION['splitCategoryId'] = -1;
    }
    
    $categoryData = $this->core->entity("category")->select();
    $categoryList = array();
    
    while($result = $categoryData->fetch_array()) {
      $categoryList[$result[Entity\TCategory::$tok_id]] = $result[Entity\TCategory::$tok_name]; 
    }
    
    $this->template->currentCategory = $_SESSION['splitCategoryId'];
    $this->template->categoryList = $categoryList;
    
    $this->template->load("index", "train/splitter");
    $this->template->pageTitle = "Splitter";
    $this->template->page = "trainsplit";
    $this->template->hide_right_box = true;
    $this->template->render();
  }
  
  public function load() {
    $ignoreIDs = array();
    
    if(isset($_SESSION['ignoredSplitPageIDs']) && is_array($_SESSION['ignoredSplitPageIDs'])) {
      $ignoreIDs = array_values($_SESSION['ignoredSplitPageIDs']);
    }
    
    if(!isset($_SESSION['splitCategoryId'])) {
      $_SESSION['splitCategoryId'] = -1;
    }
    
    $trainModel = $this->core->model("train");
    $sentence_data = null;
    
    if($GLOBALS['random_training'] == true) {
      $category_data = $trainModel->getCategory("ssFinishedGenerate", "psFinishedCrawl", "psFinishedGenerate", $ignoreIDs)->fetch_array();
      
      $categoryID = $category_data['id'];
      $pageCount = $category_data['pageCount'];
      $max_offset = min(array($pageCount, 5));
      $offset = rand(0, $max_offset - 1);
      
      $sentenceCount = $trainModel->countSentences($categoryID, $offset, "ssFinishedGenerate", $ignoreIDs)->fetch_array();
      $sentence_offset = rand(0, $sentenceCount['sentenceCount'] - 1);
        
      $sentence_data = $trainModel->getSentence($categoryID, $offset, $sentence_offset, "ssFinishedGenerate", $ignoreIDs);
    } else {
      $sentence_data = $trainModel->getSentenceNotRandom("ssFinishedGenerate", $ignoreIDs, $_SESSION['splitCategoryId']);    
    }
    
    $data = 'No sentence to display';
    $pageTitle = "-";
    
    if($sentence_data && $sentence_data->num_rows) {
      $sentence_data = $sentence_data->fetch_array();
      
      $query = $this->core->entity("page")->select(array("id" => $sentence_data[Entity\TSentence::$tok_pageid]), "title");
      $res = $query->fetch_array();
      $pageTitle = $res[Entity\TPage::$tok_title];
      
      $this->core->entity("sentence")->update(
        $sentence_data[Entity\TSentence::$tok_id], 
        array(
          "assigneddate" => date("Y-m-d H:i:s")
        )
      );

      $this->template->sentence = array(
        "id" => $sentence_data[Entity\TSentence::$tok_id],
        "level" => 0,
        "name" =>  $sentence_data[Entity\TSentence::$tok_name],
        "newName" => $sentence_data[Entity\TSentence::$tok_name],
        "index" => 1,
        "indentation" => 0,
        "splitBtn" => true,
        "dontSplitBtn" => true,
        "skipBtn" => true,
        "approveBtn" => true
      );
      
      $this->template->load("table", "train/splitter");
      
      $data = $this->template->parse();
    }
    
    $response = array(
      'data' => $data,
      'pageTitle' => $pageTitle
    );
    
    echo json_encode($response);
  }

  public function skip() {
    if(!isset($_POST['sentenceID'])) return;
    $sentenceID = $_POST['sentenceID'];
    
    if(!isset($_SESSION['ignoredSplitPageIDs'])) { 
      $_SESSION['ignoredSplitPageIDs'] = array();
    }
    
    if(!in_array($sentenceID, $_SESSION['ignoredSplitPageIDs'])) {
      $_SESSION['ignoredSplitPageIDs'][] = $sentenceID;
    }
    
    if(count($_SESSION['ignoredSplitPageIDs']) > 10) {
      $_SESSION['ignoredSplitPageIDs'] = array_values(array_slice($_SESSION['ignoredSplitPageIDs'], 1));
    }
    
    $count_data = $this->core->model("train")->countSentenceNotRandom("ssFinishedGenerate", $_SESSION['splitCategoryId'])->fetch_array();
    
    if($count_data['total'] == count($_SESSION['ignoredSplitPageIDs'])) {
      $_SESSION['ignoredSplitPageIDs'] = array();
    }
    
    $this->core->entity("sentence")->update(
      $sentenceID, 
      array(
        "assigneddate" => date("Y-m-d H:i:s", 0)
      )
    );
    
    echo json_encode("");
  }
  
  public function catChanged() {
    if(!isset($_POST['categoryId'])) return;
    
    $_SESSION['splitCategoryId'] = intval($_POST['categoryId']);
    $_SESSION['ignoredSplitPageIDs'] = array();
    
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
    
    if($newSentenceID > 0) $this->updatePageStatus($newSentenceID);
    
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

    $this->updatePageStatus(0, $sentenceIDs[0]);
    
    echo json_encode("");
  }

  public function split() {
    if(!isset($_POST['newValue']) || !isset($_POST['sentenceID']) || !isset($_POST['level'])) return;
    
    $level = $_POST['level'];
    $sentenceID = $_POST['sentenceID'];
    $newValue = htmlspecialchars_decode($_POST['newValue'], ENT_QUOTES);
    $approved =  $_POST['approved'];
    
    $originalValue = $this->core->entity("sentence")->select($sentenceID, "name")->fetch_array();
    $originalValue = $originalValue[Entity\TSentence::$tok_name];
    $exception = '';
    $data = '';
    $newSentencesCount = 0;
    
    try {
      $result = $this->Delphi()->SplitSentence($sentenceID, $newValue);
      $newSentencesCount = $result->Count();    
          
      if($newSentencesCount == 1) {
        if($approved == "true") {
          $this->core->entity("sentence")->update(
            $sentenceID,
            array(
              'status' => 'ssReviewedSplit'
            )
          );
        }
        
        $this->template->sentence = array(
          "id" => $sentenceID,
          "level" => (intval($level) + 1),
          "name" =>  $originalValue,
          "newName" => $newValue,
          "index" => intval($level) + 2,
          "indentation" => 0,
          "splitBtn" => true,
          "dontSplitBtn" => true
        );
        
        $data = $this->template->fetch("row", "train/splitter");
      } elseif($newSentencesCount > 1) {
        for($i = 0; $i < $newSentencesCount; $i++) {
          $sentenceID = $result->Item($i)->GetProperty("Id");
          
          if($approved == "true") {
            $this->core->entity("sentence")->update(
              $sentenceID,
              array(
                'status' => 'ssReviewedSplit'
              )
            );
          }
          
          $this->template->sentence = array(
            "id" => $result->Item($i)->GetProperty("Id"),
            "level" => intval($level) + 1,
            "name" =>  $result->Item($i)->GetProperty("Name"),
            "newName" => $result->Item($i)->GetProperty("Name"),
            "index" => (intval($level) + 2) . '.' . ($i +1),
            "indentation" => 8 * (intval($level) + 2),
            "splitBtn" => true,
            "dontSplitBtn" => true
          );
          
          $data .= $this->template->fetch("row", "train/splitter");
        }
      }
  
      $this->updatePageStatus($sentenceID);
    } catch(\Exception $e) {
      $exception = $e->getMessage();
    }
    
    $response = array(
      'exception' => $exception,
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
    
    $this->updatePageStatus($sentenceID);
    
    echo json_encode("");
  }
  
}

?>