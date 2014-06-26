<?php 
require_once "trainentity.php";
class ControllerTrainLinker extends TTrainEntity {
  
  public $accessLevel = 'user';
  private $entityList;
  private $sentenceList;
  private $colorList = array(
    "#346799",
    "#990000",
    "#ff8000"
  );
  
  public function index() {
    $data = $this->Delphi()->GetLinkerDataForStoryId(0);
    $this->entityList = $data->GetProperty('Entities');
    $this->sentenceList = $data->GetProperty('Sentences');
    
    $entities = $this->generate_entities();
    $sentences = $this->generate_sentences();
    
    $this->template->sentences = $sentences;
    $this->template->entities = $entities;
    
    $this->template->addScript("js/system/object.js");
    $this->template->addScript("js/module/linker/main.js");
    $this->template->addScript("js/module/linker/entity.js");
    $this->template->addScript("js/module/linker/word.js");
    $this->template->addScript("js/train2.js");
    $this->template->addStyle("style/train2.css");
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
          $sentence .= "<span class='word highlighted color" . ($wordsList->Object($j)->GetProperty('Id') + 1) . "' id='e" . $wordsList->Object($j)->GetProperty('Id') . "-w" . $j . "'>" . $word . "</span>";
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
      $attributeList = $this->entityList->Object($i)->GetProperty('Attributes');
      
      for($j = 0; $j < $attributeList->Count(); $j++) {
        $entityData[$attributeList->Item($j)->GetProperty('Key')] = array();
        $valuesData = $attributeList->Item($j)->GetProperty('Values');
        for($k = 0; $k < $valuesData->Count(); $k++) {
          $entityData[$attributeList->Item($j)->GetProperty('Key')][] = $valuesData->Item($k)->GetProperty('Name');
        }
      }
      
      $entities[$entityId] = array(
        "type" => $entityType,
        "data" => $entityData
      );  
    }
    return $entities;
  }
  
  private function generate_colors() {
    
  }
  
  public function loadStory() {
    $pageData = null;
    if(!isset($_SESSION['storyID'])) {
      $ignore = '';
      if(isset($_SESSION['ignoredStoryIDs']) && is_array($_SESSION['ignoredStoryIDs'])) {
        $ignore .= " AND st.`pageID` NOT IN (";
        for($i = 0; $i < count($_SESSION['ignoredStoryIDs']); $i++) {
          $ignore .= "'" . $_SESSION['ignoredStoryIDs'][$i] . "'";
          if($i != count($_SESSION['ignoredStoryIDs']) - 1) $ignore .= ', ';
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
      $pageData = $this->core->model("page")->getPageById($_SESSION['storyID']); $this->query("SELECT * FROM `page` WHERE `pageID` = '" . $_SESSION['storyID'] . "'") or die($this->db->error);
    }
    
    if($pageData) {
      $story_data = $pageData;
      $_SESSION['storyID'] = $story_data['pageID'];
      $storytitle = $story_data['title'];
      $trainername = isset($_COOKIE['trainername'])?$_COOKIE['trainername']:"";
      $this->query("UPDATE `page` SET `is_assigned` = '1', `assigned_date` = '" . date("Y-m-d H:i:s") . "', `reps_added_by` = '" . $trainername . "' WHERE `pageID` = '" . $story_data['pageID'] . "'");
      
      $query = $this->query("SELECT * FROM `sentence` WHERE `pageID` = '" . $story_data['pageID'] .  "' ORDER BY `sentenceID` ASC");
      
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
      $storytitle = ' - ';
      $data = '<tr><td>There are no stories available at the moment.</td></tr>';
    }
    
    $response = array(
      'title' => $storytitle,
      'data' => $data
    );
      
    echo json_encode($response);
  }
  
  public function nextSentence() {
    if(!isset($_SESSION['storyID'])) { 
      $sentenceID = -2;
    } else {
      $storyID = isset($_SESSION['storyID'])?$_SESSION['storyID']:0;
      
      $query = $this->query("SELECT * FROM `sentence` WHERE `pageID` = '" . $storyID .  "' AND `sentenceStatus` = 'ssReviewedRep' ORDER BY `sentenceID` ASC LIMIT 1");
      
      if($query->num_rows) {
        $sentence_data = $query->fetch_array();
        $sentenceID = $sentence_data['sentenceID'];
      } else {
        $sentenceID = -1;
        //$this->query("UPDATE `page` SET `is_checked` = '1' WHERE `pageID` = '" . $storyID . "'");
        unset($_SESSION['storyID']);
      }
       
    }

    $response = array(
      'sentenceID' => $sentenceID
    );
    
    echo json_encode($response);
  }

  public function skip() {
    if(!isset($_SESSION['storyID'])) return;
    $storyID = $_SESSION['storyID'];
    if(!isset($_SESSION['ignoredStoryIDs']) || !is_array($_SESSION['ignoredStoryIDs'])) $_SESSION['ignoredStoryIDs'] = array();
    $_SESSION['ignoredStoryIDs'][] = $storyID;
    
    $this->query("UPDATE `page` SET `is_assigned` = '0', `assigned_date` = NULL WHERE `pageID` = '" . $storyID . "'");
    unset($_SESSION['storyID']);    
  }

  public function save() {
    if(!isset($_SESSION['storyID']) || !isset($_POST['sentenceID']) || !isset($_POST['newValue'])) return;
          
    $sentenceID = $_POST['sentenceID']; 
    $newValue = htmlspecialchars_decode($_POST['newValue'], ENT_QUOTES);

    $storyID = $_SESSION['storyID'];
    $query = $this->query("SELECT * FROM `sentence` WHERE `pageID` = '" . $storyID .  "' AND `sentenceID` = '" . $this->db->escape_string($sentenceID) . "' AND `sentenceStatus` = 'ssReviewedRep' LIMIT 1");
    if(!$query->num_rows) return;
    $this->query("UPDATE `sentence` SET `context_rep` = '" . $this->db->escape_string($newValue) . "', `sentenceStatus` = 'ssTrainedCRep' WHERE `sentenceID` = '" . $sentenceID . "'");
    
    $this->update_status($sentenceID);
  }

  public function approve() {
    if(!isset($_SESSION['storyID']) || !isset($_POST['sentenceID'])) return;
    
    $storyID = $_SESSION['storyID'];
    $sentenceID = $_POST['sentenceID'];
    $query = $this->query("SELECT * FROM `sentence` WHERE `pageID` = '" . $storyID .  "' AND `sentenceID` = '" . $this->db->escape_string($sentenceID) . "' AND `is_incorporate_trained` = '0' LIMIT 1") or die($this->db->error);
    if(!$query->num_rows) return;
    
    $this->query("UPDATE `sentence` SET `context_rep` = `representation`, `sentenceStatus` = 'ssTrainedCRep' WHERE `sentenceID` = '" . $sentenceID . "'") or die($this->db->error);
    
    $this->update_status($sentenceID); 
  }

}

?>

