<?php 

require_once __DIR__ . "/../ActionRequest.php";
class TTrainRep extends TActionRequest {
  
  public function load() {
    if(isset($_POST['sentenceID']) && $_POST['sentenceID'] != -1) {
      $this->query(" UPDATE `sentence` SET `is_assigned` = '0', `assigned_to` = '', `assigned_date` = '".date("Y-m-d H:i:s", 0)."' WHERE `sentenceID` = '" . $this->db->escape_string($_POST['sentenceID']) . "'");
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
            WHERE se.`sentenceStatus` = 'ssReviewedSplit' 
          ) p ON c.`categoryID` = p.`categoryID`
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
      SELECT COUNT(se.`sentenceID`) AS sentenceCount
      FROM (
        SELECT DISTINCT p.`pageID`, p.`pageStatus` 
        FROM `page` p
        INNER JOIN `sentence` s ON p.`pageID` = s.`pageID`
        WHERE p.`categoryID` = '" . $categoryID . "'
        AND s.`sentenceStatus` = 'ssReviewedSplit'
        LIMIT " . $offset . ",1
      ) a INNER JOIN `sentence` se ON a.`pageID` = se.`pageID`
      WHERE se.`sentenceStatus` = 'ssReviewedSplit'
    ") or die($this->db->error);
    
    $sentenceCount = $query->fetch_array();
    $sentence_offset = rand(0, $sentenceCount['sentenceCount'] - 1);
    
    $query = $this->query("
      SELECT *
      FROM (
        SELECT DISTINCT p.`pageID`, p.`pageStatus` 
        FROM `page` p
        INNER JOIN `sentence` se ON p.`pageID` = se.`pageID`
        WHERE p.`categoryID` = '" . $categoryID . "'
        AND se.`sentenceStatus` = 'ssReviewedSplit'
        LIMIT " . $offset . ",1
      ) a INNER JOIN `sentence` s ON a.`pageID` = s.`pageID`
      WHERE s.`sentenceStatus` = 'ssReviewedSplit'
      ORDER BY s.`assigned_date` ASC, s.`sentenceID` DESC
      LIMIT " . $sentence_offset . ", 1
    ") or die($this->db->error);

    if($query->num_rows) {
      $trainername = isset($_COOKIE['trainername'])?$_COOKIE['trainername']:"";
      $sentence_data = $query->fetch_array();
      $this->query("UPDATE `sentence` SET `assigned_date` = '" . date("Y-m-d H:i:s") . "' WHERE `sentenceID` = '" . $sentence_data['sentenceID'] . "'");
      $data = '';
      $data .= '<table class="understand-sentences sentencestbl">';
      $data .= '<tr>';
      $data .= '<td>Sentence</td>';
      $data .= '<td>' . $sentence_data['sentence'] . '</td>';
      $data .= '<td><input type="hidden" class="sentenceID" value="' . htmlspecialchars($sentence_data['sentenceID'], ENT_QUOTES) . '" /></td>';
      $data .= '</tr>';
      $data .= '<tr>';
      $data .= '<td>Representation</td>';
      $data .= '<td><input type="text" style="width:100%" class="newRepValue" value="' . htmlspecialchars($sentence_data['representation'], ENT_QUOTES) . '" /></td>';
      $data .= '<td><a href="javascript:void(0)" class="doneUnderstandButton">DONE</a> <a href="javascript:void(0)" class="skipUnderstandButton">SKIP</a>';
      require_once "init.php";
      global $user;
      if($user->logged_in  && ($user->userlevel == 8 || $user->userlevel == 9)) {
        $data .= ' <a href="javascript:void(0)" class="approveUnderstandButton">APPROVE</a>';
      }
      $data .= '</td>';
      $data .= '</tr>';
      $data .= '<tr>';
      $data .= '<td>Neo\'s Guess</td>';
      $data .= '<td class="repguess">' . $sentence_data['repguess1'] . '</td>';
    $data .= '<td><a href="javascript:void(0)" class="useUnderstandButton">CORRECT!</a> <a href="javascript:void(0)" class="editUnderstandButton">EDIT</a></td>';
      $data .= '</tr>';
      $data .= '</table>';
      $this->query("UPDATE `sentence` SET `is_assigned` = '1', `assigned_to` = '" . $trainername . "' WHERE `sentenceID` = '" . $sentence_data['sentenceID'] . "'");
    } else {
      $data = 'No sentence to display';
    }

    $response = array(
      'data' => $data
    );
    
    echo json_encode($response);
  }
  
  public function save() {
    if(!isset($_POST['sentenceID']) || !isset($_POST['newValue'])) return;
          
    $sentenceID = $_POST['sentenceID'];
    $newValue = htmlspecialchars_decode($_POST['newValue'], ENT_QUOTES);
    
    if(trim($newValue) == ''){
      return;
    }
    
    require_once __DIR__ . "/../../NeoShared/Server/App/Global.php";
    $validator = $server->ValidateRep($newValue);
    if($validator === true) {
      $this->query("UPDATE `sentence` SET `representation` = '" . $this->db->escape_string($newValue) . "', `sentenceStatus` = 'ssTrainedRep' WHERE `sentenceID` = '" . $sentenceID . "'");  
      $this->update_status($sentenceID);
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
    
    require_once __DIR__ . "/../../NeoShared/Server/App/Global.php";
    $repguess = $server->GuessRepsForSentenceId($sentenceID)->GetProperty("RepGuessA");
    
    $validator = $server->ValidateRep($repguess);
    if($validator === true) {
      $this->query("UPDATE `sentence` SET `representation` = '" . $repguess . "', `sentenceStatus` = 'ssTrainedRep' WHERE `sentenceID` = '" . $sentenceID . "'") or die($this->db->error);
      $this->update_status($sentenceID);
    } else {
      $response = array(
        "ErrorString" => $validator['ErrorString'],
        "StrIndex" => $validator['StrIndex']
      ); 
      echo json_encode($response);
    }
    
    $this->update_status($sentenceID);
  }

  public function approve() {
    if(!isset($_POST['sentenceID']) || !isset($_POST['newValue'])) return;
          
    $sentenceID = $_POST['sentenceID'];
    $newValue = htmlspecialchars_decode($_POST['newValue'], ENT_QUOTES);
    
    if(trim($newValue) == ''){
      return;
    }
    
    require_once __DIR__ . "/../../NeoShared/Server/App/Global.php";
    $validator = $server->ValidateRep($newValue);
    if($validator === true) {
      $this->query("UPDATE `sentence` SET `representation` = '" . $this->db->escape_string($newValue) . "', `sentenceStatus` = 'ssReviewedRep' WHERE `sentenceID` = '" . $sentenceID . "'");  
      $this->update_status($sentenceID);
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