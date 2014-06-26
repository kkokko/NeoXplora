<?php 

require_once __DIR__ . "/../ActionRequest.php";
class TTrainCRep extends TActionRequest {
  
  public function load() {
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

      $query = $this->query("
        SELECT a.*
        FROM (
          SELECT c2.`categoryID`, c2.`pageCount`, COUNT(p2.`pageID`) AS trainedCount 
          FROM (
            SELECT c.`categoryID`, COUNT(p.`pageID`) AS pageCount FROM `category` c
            INNER JOIN (
              SELECT DISTINCT pg.`pageID`, pg.`pageStatus`, pg.`categoryID`
              FROM `page`pg
              INNER JOIN `sentence` se ON pg.`pageID` = se.`pageID`
            ) p ON c.`categoryID` = p.`categoryID` AND p.`pageStatus` IN ('psReviewedRep', 'psTrainingCRep')
            GROUP BY(c.`categoryID`)
          ) c2
          LEFT JOIN `page` p2 ON p2.`categoryID` = c2.`categoryID` AND p2.`pageStatus` NOT IN ('psFinishedCrawl', 'psFinishedGenerate')
          GROUP BY (c2.`categoryID`)
        ) a INNER JOIN `page` p3 ON p3.`categoryID` = a.`categoryID`
        GROUP BY p3.`categoryID`
        ORDER BY a.`trainedCount` ASC
        LIMIT 1;
      ") or die($this->db->error);
        
      $category_data = $query->fetch_array();
      $categoryID = $category_data['categoryID'];
      $pageCount = $category_data['pageCount'];
      $max_offset = min(array($pageCount, 5));
      $offset = rand(0, $max_offset - 1);
      
      $query = $this->query("
        SELECT DISTINCT p.`pageID`, p.`pageStatus`, p.`title`
        FROM `page` p
        INNER JOIN `sentence` se ON p.`pageID` = se.`pageID` AND p.`pageStatus` IN ('psReviewedRep', 'psTrainingCRep')
        WHERE p.`categoryID` = '" . $categoryID . "'
        ORDER BY p.`assigned_date` ASC, `pageID` ASC
        LIMIT " . $offset . ",1
      ") or die($this->db->error);
    } else {
      $query = $this->query("SELECT * FROM `page` WHERE `pageID` = '" . $_SESSION['storyID'] . "'") or die($this->db->error);
    }
    
    if($query->num_rows) {
      $story_data = $query->fetch_array();
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