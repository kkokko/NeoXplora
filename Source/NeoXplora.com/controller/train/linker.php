<?php
namespace NeoX\Controller;

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
    $data = $this->Delphi()->GetLinkerDataForPageId(0);
    $this->entityList = $data->GetProperty('Entities');
    $this->sentenceList = $data->GetProperty('Sentences');
    
    $entities = $this->generate_entities();
    $sentences = $this->generate_sentences();
    
    $this->template->sentences = $sentences;
    $this->template->entities = $entities;
    
    $this->template->addScripts(array(
      "js/system/object.js",
      "js/classes/StringList.js",
      "js/classes/rep/RepRecord.js",
      "js/classes/rep/RepRecordWord.js",
      "js/classes/rep/RepEntity.js",
      "js/classes/rep/RepPropertyKey.js",
      "js/classes/rep/RepPropertyValue.js"
    ));
    $this->template->addJSModules(array(
      "NeoX.Modules.LinkerTrainIndex" => "js/module/linker/train/index.js",
      "NeoX.Modules.LinkerTrainRequests" => "js/module/linker/train/requests.js",
      "NeoX.Modules.EntityControl" => "js/module/linker/train/entity.js",
      "NeoX.Modules.WordControl" => "js/module/linker/train/word.js",
      "NeoX.Modules.ButtonComponent" => "js/module/button.js"
    ));
    $this->template->addStyle("style/train/linker.css");
    $this->template->load("index", "train/linker");
    $this->template->pageTitle = "Train CRep";
    $this->template->page = "traincrep";
    $this->template->hide_right_box = true;
    $this->template->render();
  }

  private function generate_sentences() {
    $sentences = array();
    
    for($i = 0; $i < $this->sentenceList->Count(); $i++) {
      $sentenceID = $this->sentenceList->Item($i);
      $wordsList = $this->sentenceList->Object($i);
      $sentence = '';
      for($j = 0; $j < $wordsList->Count(); $j++) {
        $word = $wordsList->Item($j);
        if($wordsList->Object($j)) {
          $wordId = $wordsList->Object($j)->GetProperty('Id');
          $sentence .= "<span class='word highlighted color" . ($wordId + 1) . "' id='e" . $wordId . "-w" . $j . "'>" . $word . "</span>";
        } else {
          $sentence .= $word;
        }
      }
      $sentences[$sentenceID] = $sentence . ".";
    }
    
    return $sentences;
  }

  private function generate_entities() {
    $entities = array();
    for($i = 0; $i < $this->entityList->Count(); $i++) {
      $entityData = array();
      $entityId = $this->entityList->Item($i);
      $entityType = $this->entityList->Object($i)->GetProperty('Type');
      $groupMembers = array();
      $attributeList = $this->entityList->Object($i)->GetProperty('Attributes');
      
      for($j = 0; $j < $attributeList->Count(); $j++) {
        $attributeKey = $attributeList->Item($j)->GetProperty('Key');
        
        if($entityType == "etGroup" && $attributeKey == "Members") {
          for($k = 0; $k < $valuesData->Count(); $k++) {
            $groupMembers[] = $valuesData->Item($k)->GetProperty('Id');
          }
          continue;
        }
        
        $entityData[$attributeKey] = array();
        $valuesData = $attributeList->Item($j)->GetProperty('Values');
        for($k = 0; $k < $valuesData->Count(); $k++) {
          $entityData[$attributeKey][] = $valuesData->Item($k)->GetProperty('Name');
        }
      }
      
      $type = "";
      switch($entityType) {
        case "etObject":
          $type = "Object";
          break;
        case "etGroup":
          $type = "Group";
          break;
        case "etPerson":
          $type = "Person";
          break;
      }
      
      $entities[$entityId] = array(
        "type" => $type,
        "data" => $entityData
      );  
    }
    return $entities;
  }
  
  private function generate_colors() {
    
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
      $category_data = $linkerModel->getCategory();
      $categoryID = $category_data['categoryID'];
      
      $pageCount = $category_data['pageCount'];
      $max_offset = min(array($pageCount, 5));
      $offset = rand(0, $max_offset - 1);
      
      $pageData = $linkerModel->getPageByCategoryID($categoryID, $offset);
    } else {
      $pageData = $this->core->model("page")->getPageById($_SESSION['pageID']); $this->query("SELECT * FROM `page` WHERE `pageID` = '" . $_SESSION['pageID'] . "'") or die($this->db->error);
    }
    
    if($pageData) {
      $page_data = $pageData;
      $_SESSION['pageID'] = $page_data['pageID'];
      $pagetitle = $page_data['title'];
      $trainername = isset($_COOKIE['trainername'])?$_COOKIE['trainername']:"";
      $this->query("UPDATE `page` SET `is_assigned` = '1', `assigned_date` = '" . date("Y-m-d H:i:s") . "', `reps_added_by` = '" . $trainername . "' WHERE `pageID` = '" . $page_data['pageID'] . "'");
      
      $query = $this->query("SELECT * FROM `sentence` WHERE `pageID` = '" . $page_data['pageID'] .  "' ORDER BY `sentenceID` ASC");
      
      $data = "";
      $data .= '<tr>';
      $data .= '<th>Sentence</th>';
      $data .= '<th>Representation</th>';
      $data .= '<th>Contextual Representation</th>';
      $data .= '</tr>';
      
      while($sentence_data = $query->fetch_array()) {
        $data .= "<tr id='s" . $sentence_data['sentenceID'] . "'>";
        $data .= "<td>" . $sentence_data['sentence'] . "</td>";
        if(trim($sentence_data['representation']) != "") {
          $data .= "<td style='color: #000' class='representation'>" . htmlspecialchars($sentence_data['representation'], ENT_QUOTES) . "</td>";
        } else {
          $data .= "<td style='color: #aaa' class='representation'>" . htmlspecialchars($sentence_data['repguess1'], ENT_QUOTES) . "</td>";
        }
        $data .= "<td style='color: #aaa' class='contextrepresentation'>" . $sentence_data['context_rep'] . "</td>";
        $data .= "</tr>";
      }
    } else {
      $pagetitle = ' - ';
      $data = '<tr><td>There are no stories available at the moment.</td></tr>';
    }
    
    $response = array(
      'title' => $pagetitle,
      'data' => $data
    );
      
    echo json_encode($response);
  }
  
  public function nextSentence() {
    if(!isset($_SESSION['pageID'])) { 
      $sentenceID = -2;
    } else {
      $pageID = isset($_SESSION['pageID'])?$_SESSION['pageID']:0;
      
      $query = $this->query("SELECT * FROM `sentence` WHERE `pageID` = '" . $pageID .  "' AND `sentenceStatus` = 'ssReviewedRep' ORDER BY `sentenceID` ASC LIMIT 1");
      
      if($query->num_rows) {
        $sentence_data = $query->fetch_array();
        $sentenceID = $sentence_data['sentenceID'];
      } else {
        $sentenceID = -1;
        //$this->query("UPDATE `page` SET `is_checked` = '1' WHERE `pageID` = '" . $pageID . "'");
        unset($_SESSION['pageID']);
      }
       
    }

    $response = array(
      'sentenceID' => $sentenceID
    );
    
    echo json_encode($response);
  }

  public function skip() {
    if(!isset($_SESSION['pageID'])) return;
    $pageID = $_SESSION['pageID'];
    if(!isset($_SESSION['ignoredPageIDs']) || !is_array($_SESSION['ignoredPageIDs'])) $_SESSION['ignoredPageIDs'] = array();
    $_SESSION['ignoredPageIDs'][] = $pageID;
    
    $this->query("UPDATE `page` SET `is_assigned` = '0', `assigned_date` = NULL WHERE `pageID` = '" . $pageID . "'");
    unset($_SESSION['pageID']);    
  }

  public function save() {
    if(!isset($_SESSION['pageID']) || !isset($_POST['sentenceID']) || !isset($_POST['newValue'])) return;
          
    $sentenceID = $_POST['sentenceID']; 
    $newValue = htmlspecialchars_decode($_POST['newValue'], ENT_QUOTES);

    $pageID = $_SESSION['pageID'];
    $query = $this->query("SELECT * FROM `sentence` WHERE `pageID` = '" . $pageID .  "' AND `sentenceID` = '" . $this->db->escape_string($sentenceID) . "' AND `sentenceStatus` = 'ssReviewedRep' LIMIT 1");
    if(!$query->num_rows) return;
    $this->query("UPDATE `sentence` SET `context_rep` = '" . $this->db->escape_string($newValue) . "', `sentenceStatus` = 'ssTrainedCRep' WHERE `sentenceID` = '" . $sentenceID . "'");
    
    $this->updatePageStatus($sentenceID);
  }

  public function approve() {
    if(!isset($_SESSION['pageID']) || !isset($_POST['sentenceID'])) return;
    
    $pageID = $_SESSION['pageID'];
    $sentenceID = $_POST['sentenceID'];
    $query = $this->query("SELECT * FROM `sentence` WHERE `pageID` = '" . $pageID .  "' AND `sentenceID` = '" . $this->db->escape_string($sentenceID) . "' AND `is_incorporate_trained` = '0' LIMIT 1") or die($this->db->error);
    if(!$query->num_rows) return;
    
    $this->query("UPDATE `sentence` SET `context_rep` = `representation`, `sentenceStatus` = 'ssTrainedCRep' WHERE `sentenceID` = '" . $sentenceID . "'") or die($this->db->error);
    
    $this->updatePageStatus($sentenceID); 
  }

}

?>

