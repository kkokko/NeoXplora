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
    if(isset($_SESSION['ignoredPageIDs']) && is_array($_SESSION['ignoredPageIDs'])) {
      $ignoreIDs = $_SESSION['ignoredPageIDs'];
    }
    $splitterModel = $this->core->model("splitter", "train");
    
    $category_data = $splitterModel->getCategory("ssFinishedGenerate", "psFinishedCrawl", "psFinishedGenerate", $ignoreIDs);
    
    $categoryID = $category_data['id'];
    $pageCount = $category_data['pageCount'];
    $max_offset = min(array($pageCount, 5));
    $offset = rand(0, $max_offset - 1);
    
    $sentenceCount = $splitterModel->countSentences($categoryID, $offset, "ssFinishedGenerate");
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
      
      $this->template->sentence = $sentence_data[Entity\TSentence::$tok_name];
      $this->template->sentenceID = $sentence_data[Entity\TSentence::$tok_id];
      $this->template->load("single", "train/splitter");
      
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
    
    if(!isset($_SESSION['ignoredPageIDs'])) $_SESSION['ignoredPageIDs'] = array();
    $_SESSION['ignoredPageIDs'][] = $sentenceID;
    
    if(count($_SESSION['ignoredPageIDs']) > 10) unset($_SESSION['ignoredPageIDs'][0]);
    
    $this->core->entity("sentence")->update(
      $sentenceID, 
      array(
        "assigneddate" => date("Y-m-d H:i:s", 0)
      )
    );
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
    
    $wherein = "(";
      for($i = 0; $i < count($sentenceIDs); $i++) {
        $wherein .= $sentenceIDs[$i];
        if($i + 1 != count($sentenceIDs)) $wherein .= ", ";
      }
      $wherein .= ")";
      
    $this->query("UPDATE `sentence` SET `sentenceStatus` = 'ssReviewedSplit' WHERE `sentenceID` IN ". $wherein) or die($this->db->error);
    $this->update_status(0, $sentenceIDs[0]);
  }

  public function split($ASentenceID = "", $ANewValue = "", $ALevel = "", $IsReview = false) {
    $level = '';
    $sentenceID = '';
    $newValue = '';
    
    if($ASentenceID == "" && $ANewValue == "" && $ALevel == "") {
      if(!isset($_POST['newValue']) || !isset($_POST['sentenceID']) || !isset($_POST['level'])) return;
      $level = $_POST['level'];
      $sentenceID = $_POST['sentenceID'];
      $newValue = $_POST['newValue'];
    } else {
      $level = $ALevel;
      $sentenceID = $ASentenceID;
      $newValue = $ANewValue;
    }
    $newSentencesCount = 0;
    $newSentenceIDs = array();
    $newSentences = array();
    $data = '';
    
    if(trim($newValue) == '') {
      $this->query("DELETE FROM `sentence` WHERE `sentenceID` = '" . $ASentenceID . "'");
    } else {
      require_once __DIR__ . "/../../split.php";
      $sentences = generate_sentences(trim($newValue));
      
      if(count($sentences) == 1) {
        $sStatus = 'ssTrainedSplit';
        if($IsReview) $sStatus = 'ssTrainedSplit'; 
        
        $q = "UPDATE `sentence` SET `sentenceStatus` = '" . $sStatus . "', `sentence` = '" . $this->db->escape_string($sentences['0']['sentence']) . "', `POS` = '" . $sentences['0']['pos'] . "' WHERE `sentenceID` = '" . $this->db->escape_string($sentenceID) . "'";
        $this->query($q);
        
        if($IsReview) {
          $query = $this->query("SELECT `pr2ID` FROM `sentence` WHERE `sentenceID` = '" . $sentenceID . "'");
          $proto = $query->fetch_array();
          $protoID = $proto['pr2ID'];
          $data .= "<tr id='s" . $sentenceID . "' class='asentence row1 pr" . $protoID . "'>";
          $data .= "<td><input type='text' class='newValue' value='" . htmlspecialchars($sentences['0']['sentence'], ENT_QUOTES) . "' /></td>";
          $data .= '<td><a href="javascript:void(0)" class="modifyReviewSplitButton">Modify</a></td>';
          $data .= "</tr>";
          $newSentenceIDs[$sentenceID] = $sentenceID;
          $newSentences[] = $sentenceID;
        } else {
          $newSentencesCount++;
          $data .= '<tr>';
          $data .= '<td>Sentence ' . (((int) $level)+2) . '</td>';
          $data .= '<td><input type="hidden" class="originalValue" value="' . htmlspecialchars($sentence_data['sentence']) . '" />
                        <input type="text" style="width:100%" class="newSplitValue" value="' . htmlspecialchars($newValue) . '" />
                        <input type="hidden" class="level" value="' . (((int) $level)+1) . '" />
                        <input type="hidden" class="sentenceID" value="' . $sentenceID . '" /></td>';
          if((((int) $level)+1) < 3) {
            $data .= '<td><a href="javascript:void(0)" class="doneSplitButton">SPLIT</a> <a href="javascript:void(0)" class="doneNoSplitButton">NO NEED</a></td>';
          } else {
            $data .= '<td></td>';
          }
          $data .= '</tr>';
        }
      } else {
        $query = $this->query("SELECT `pageID` FROM `sentence` WHERE `sentenceID` = '" . $sentenceID . "'");
        $page_data = $query->fetch_array();
        $pageID = $page_data['pageID'];
        $query = $this->query("SELECT DISTINCT s1.* FROM `sentence` s1 INNER JOIN `sentence` s2 ON s1.`pageID` = s2.`pageID` WHERE s2.`sentenceID` = '" . $this->db->escape_string($sentenceID) . "' ORDER BY s1.`sentenceID` ASC");
        $this->query("DELETE FROM `sentence` WHERE `pageID` = '" . $this->db->escape_string($pageID) . "'");
        while($sentence_data = $query->fetch_array()) {
          if($sentence_data['sentenceID'] == $sentenceID) {
            foreach($sentences as $sentence) {
              $newSentencesCount++;
              $sStatus = 'ssTrainedSplit';
              if($IsReview) $sStatus = 'ssTrainedSplit'; 
              $this->query("INSERT INTO  `sentence` (`sentence`, `POS`, `is_split`, `splitFLAG`, `pageID`, `pr1ID`, `pr2ID`, `sentenceStatus`) VALUES ('" . $this->db->escape_string($sentence['sentence']) . "', '" . $this->db->escape_string($sentence['pos']) . "', '1', 'AwaitingReview', '" . $sentence_data['pageID'] . "', '" . $sentence_data['pr1ID'] . "', '" . $sentence_data['pr2ID'] . "', '". $sStatus ."')");
              $newSentenceID = $this->db->insert_id;
              if($IsReview) {
                $data .= "<tr id='s" . $newSentenceID . "' class='asentence row1 pr" . $sentence_data['pr2ID'] . "'>";
                $data .= "<td><input type='text' class='newValue' value='" . htmlspecialchars($sentence['sentence'], ENT_QUOTES) . "' /></td>";
                $data .= '<td><a href="javascript:void(0)" class="modifyReviewSplitButton">Modify</a></td>';
                $data .= "</tr>";
              } else {
                $data .= '<tr>';
                $data .= '<td style="padding-left: ' . (8 * ($level + 2)) . 'px">Sentence ' . ($level+2) . '.' . $newSentencesCount . '</td>';
                $data .= '<td><input type="text" style="width:100%" ' . (((((int) $level)+1) < 7)?' class="newSplitValue" ':' disabled="disabled"') . ' value="' . htmlspecialchars($sentence['sentence']) . '" />
                              <input type="hidden" class="level" value="' . (((int) $level)+1) . '" />
                              <input type="hidden" class="sentenceID" value="' . $newSentenceID . '" /></td>';
                if((((int) $level)+1) < 7) {
                  $data .= '<td><a href="javascript:void(0)" class="doneSplitButton">SPLIT</a> <a href="javascript:void(0)" class="doneNoSplitButton">NO NEED</a></td>';
                } else {
                  $data .= '<td></td>';
                }
                $data .= '</tr>';
              }
              $newSentences[] = $newSentenceID;
            }
          } else {
            $this->query("INSERT INTO  `sentence` 
                        (`sentence`, `pageID`, `pr1ID`, `pr2ID`, `representation`, `repguess1`, `repguess2`, `repguess3`, `repguess4`, `guesses1`, `guesses2`, `guesses3`, `guesses4`,
                        `context_rep`, `crepguess1`, `POS`, `sntid1`, `sntid2`, `sntid3`, `sntid4`, `semantic_rep`, `srepguess1`, `srepguess2`, `srepguess3`, `srepguess4`, `is_understand_trained`,
                        `is_incorporate_trained`, `is_assigned`, `assigned_to`, `is_split`, `RepFLAG`, `CRepFLAG`, `SplitFLAG`, `sentenceStatus`) 
                        VALUES
                        (
                          '" . $this->db->escape_string($sentence_data['sentence']) . "', '" . $this->db->escape_string($sentence_data['pageID']) . "', '" . $this->db->escape_string($sentence_data['pr1ID']) . "', '" . $this->db->escape_string($sentence_data['pr2ID']) . "',
                          '" . $this->db->escape_string($sentence_data['representation']) . "', '" . $this->db->escape_string($sentence_data['repguess1']) . "', '" . $this->db->escape_string($sentence_data['repguess2']) . "', '" . $this->db->escape_string($sentence_data['repguess3']) . "', '" . $this->db->escape_string($sentence_data['repguess4']) . "',
                          '" . $this->db->escape_string($sentence_data['guesses1']) . "', '" . $this->db->escape_string($sentence_data['guesses2']) . "', '" . $this->db->escape_string($sentence_data['guesses3']) . "', '" . $this->db->escape_string($sentence_data['guesses4']) . "',
                          '" . $this->db->escape_string($sentence_data['context_rep']) . "', '" . $this->db->escape_string($sentence_data['crepguess1']) . "', '" . $this->db->escape_string($sentence_data['POS']) . "', '" . $this->db->escape_string($sentence_data['sntid1']) . "',
                          '" . $this->db->escape_string($sentence_data['sntid2']) . "', '" . $this->db->escape_string($sentence_data['sntid3']) . "', '" . $this->db->escape_string($sentence_data['sntid4']) . "',
                          '" . $this->db->escape_string($sentence_data['semantic_rep']) . "', '" . $this->db->escape_string($sentence_data['srepguess1']) . "', '" . $this->db->escape_string($sentence_data['srepguess2']) . "', '" . $this->db->escape_string($sentence_data['srepguess3']) . "',
                          '" . $this->db->escape_string($sentence_data['srepguess4']) . "', '" . $this->db->escape_string($sentence_data['is_understand_trained']) . "', '" . $this->db->escape_string($sentence_data['is_incorporate_trained']) . "', '" . $this->db->escape_string($sentence_data['is_assigned']) . "',
                          '" . $this->db->escape_string($sentence_data['assigned_to']) . "', '" . $this->db->escape_string($sentence_data['is_split']) . "', '" . $this->db->escape_string($sentence_data['RepFLAG']) . "', '" . $this->db->escape_string($sentence_data['CRepFLAG']) . "',
                          '" . $this->db->escape_string($sentence_data['SplitFLAG']) . "', '" . $this->db->escape_string($sentence_data['sentenceStatus']) . "')");
            $newSentenceIDs[$sentence_data['sentenceID']] = $this->db->insert_id;
          }
        }
      }
    }

    $this->update_status($sentenceID);
    
    $response = array(
      'data' => $data,
      'level' => ((int) $level) + 1,
      'newSentencesCount' => $newSentencesCount,
      'newSentenceIDs' => $newSentenceIDs,
      'newSentences' => $newSentences
    );
    
    echo json_encode($response);
  }
  
  function dont_split() {
    if(!isset($_POST['sentenceID'])) return;
    $sentenceID = $_POST['sentenceID'];
    $this->query("UPDATE `sentence` SET `sentenceStatus` = 'ssTrainedSplit' WHERE `sentenceID` = '" . $sentenceID . "'") or die($this->db->error);
    
    $this->update_status($sentenceID);
  }
  
}

?>