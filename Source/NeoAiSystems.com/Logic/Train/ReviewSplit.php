<?php 

require_once __DIR__ . "/../ActionRequest.php";
class TReviewSplit extends TActionRequest {
  
  public function load() {
    $page = isset($_POST['page'])?$_POST['page']:1;
    $per_page = 5;
    $pagination = "";
    
    $query = $this->query("
      SELECT COUNT(a.`prID`) AS total
      FROM (
            SELECT DISTINCT p.`prID`
            FROM `proto` p
            INNER JOIN `sentence` s ON p.`prID` = s.`pr2ID`
            WHERE s.`sentenceStatus` = 'ssTrainedSplit'
           ) a
    ") or die($this->db->error);
    $count_data = $query->fetch_array();
    
    if($count_data['total'] > 0) {
      $pages = ceil($count_data['total'] / $per_page);
      $start = ($page - 1) * $per_page;
      
      $query = $this->query("
        SELECT se.*, a.*
        FROM sentence se
        INNER JOIN  (
          SELECT DISTINCT pr.* 
           FROM `proto` pr
           INNER JOIN `sentence` s ON pr.`prID` = s.`pr2ID`
           INNER JOIN `page` p ON p.`pageID` = s.`pageID`
           WHERE s.`sentenceStatus` = 'ssTrainedSplit'
           ORDER BY p.`pageID` ASC, pr.`prID` ASC, s.`sentenceID` ASC
           LIMIT " . $start . ", " . $per_page . "
        ) a ON a.`prID` = se.`pr2ID`
        ORDER BY se.`sentenceID` ASC
      ") or die($this->db->error);

      if(!$query->num_rows) {
        $query = $this->query("
          SELECT se.*, a.*
          FROM sentence se
          INNER JOIN  (
            SELECT DISTINCT pr.* 
             FROM `proto` pr
             INNER JOIN `sentence` s ON pr.`prID` = s.`pr2ID`
             INNER JOIN `page` p ON p.`pageID` = s.`pageID`
             WHERE s.`sentenceStatus` = 'ssTrainedSplit'
             ORDER BY p.`pageID` ASC, pr.`prID` ASC, s.`sentenceID` ASC
             LIMIT 0, " . $per_page . "
          ) a ON a.`prID` = se.`pr2ID`
          ORDER BY se.`sentenceID` ASC
        ") or die($this->db->error);
        $page = 1;
      }
      
      $data = "<table class='reviewsplit sentencestbl'>";
      $data .= '<tr>';
      $data .= '<th>Sentence</th>';
      $data .= '<th style="width:200px">Actions</th>';
      $data .= '</tr>';
      
      $prID = 0;
      $counter = 0;
      while($sentence_data = $query->fetch_array()) {
        $counter++;
        if($sentence_data['prID'] != $prID) {
          $data .= "<tr id='pr" . $sentence_data['prID'] . "' class='aproto'>";
          $data .= "<td><b>" . $sentence_data['name'] . "<input type='hidden' class='pageID' value='" . $sentence_data['pageID'] . "' /></input</b></td>";
          $data .= '<td><a href="javascript:void(0)" class="revertReviewSplitButton">Revert</a> <a href="javascript:void(0)" class="approveReviewSplitButton">Approve</a> <a href="javascript:void(0)" class="dismissReviewSplitButton">Re-Split</a></td>';
          $data .= "</tr>";
          $prID = $sentence_data['prID'];
          $counter = 0;
        }
        $rowclass = ($counter%2 == 0)?'row1':'row2';
        $data .= "<tr id='s" . $sentence_data['sentenceID'] . "' class='asentence " . $rowclass . " pr" . $prID . "'>";
        $data .= "<td><input type='text' class='newValue' value='" . htmlspecialchars($sentence_data['sentence'], ENT_QUOTES) . "' /></td>";
        $data .= '<td><a href="javascript:void(0)" class="modifyReviewSplitButton">Modify</a></td>';
        $data .= "</tr>";
      }
      $data .= "</table>";
      
      $pagination_array = $this->generate_pagination($page, $pages);
      foreach($pagination_array as $apage) {
        if($apage != "skip") {
          if($page == $apage) {
            $pagination .= "<span class='goToPageSplit currentPage'>" . $apage . "</span> ";
          } else {
            $pagination .= "<span class='goToPageSplit'>" . $apage . "</span> ";
          }
        } else {
         $pagination .= "...";
        }
      }
      
      if($pagination != "") {
        $pagination = "Pages: " . $pagination;
      }
      
    } else {
      $data = '<tr><td>There are no sentences to review.</td></tr>';
    }
    
    $response = array(
      'data' => $data,
      'pagination' => $pagination
    );
    
    echo json_encode($response);
  }

  public function revert() {
    if(!isset($_POST['protoID'])) return;
    $protoID = $_POST['protoID'];
    $data = "";
    
    $query = $this->query("
      SELECT s.`sentenceID`, pr.`name`
      FROM `sentence` s
      INNER JOIN `proto` pr ON s.`pr2ID` = pr.`prID` AND pr.`prID` = '" . $protoID . "'
      ORDER BY s.`sentenceID` ASC, pr.`prID` ASC
      LIMIT 1
    ") or die($this->db->error);
    $sentence_data = $query->fetch_array();
    
    $this->query("
      UPDATE `sentence` s
      INNER JOIN `proto` pr 
        ON pr.`prID` = s.`pr2ID`
      SET s.`sentence` = pr.`name`,
          s.`sentenceStatus` = 'ssTrainedSplit'
      WHERE s.`sentenceID` = '" . $sentence_data['sentenceID'] . "'
    ");
    
    $this->query("
      DELETE FROM `sentence` 
      WHERE `pr2ID` = '" . $protoID . "' 
      AND `sentenceID` <> '" . $sentence_data['sentenceID'] . "'
    ");    
    
    $query->free();
    $this->update_status($sentence_data['sentenceID']);
    
    $data .= "<tr id='s" . $sentence_data['sentenceID'] . "' class='asentence row1 pr" . $protoID . "'>";
    $data .= "<td><input type='text' class='newValue' value='" . htmlspecialchars($sentence_data['name'], ENT_QUOTES) . "' /></td>";
    $data .= '<td><a href="javascript:void(0)" class="modifyReviewSplitButton">Modify</a></td>';
    $data .= "</tr>";
    
    $response = array(
      "data" => $data,
      "sentenceID" => $sentence_data['sentenceID']
    );
    
    echo json_encode($response);
  }

  public function modify() {
    if(!isset($_POST['sentenceID']) || !isset($_POST['newValue'])) return;
    $newValue = htmlspecialchars_decode($_POST['newValue'], ENT_QUOTES);
    $sentenceID = $_POST['sentenceID'];
    
    require_once "TrainSplit.php";
    $tool = new TTrainSplit($this->db);
    $tool->split($sentenceID, $newValue, 1, true);
  }

  public function approve() {
    if(!isset($_POST['protoID'])) return;
    $protoID = $_POST['protoID'];
      
    $this->query("UPDATE `sentence` SET `sentenceStatus` = 'ssReviewedSplit' WHERE `pr2ID` = '" . $protoID . "'") or die($this->db->error);
    $this->update_status(0, $protoID);
    
    $SentenceIDs = array();
    $query = $this->query("SELECT `sentenceID` FROM `sentence` WHERE `pr2ID` = '" . $protoID . "'") or die($this->db->error);
    while($sentence_data = $query->fetch_array()) {
      $SentenceIDs[] = $sentence_data['sentenceID']; 
    }
    
    require_once __DIR__ . "/../../NeoShared/Server/App/Global.php";
    $server->PredictAfterSplit($SentenceIDs);
  }
    
  public function dismiss() {
    if(!isset($_POST['protoID'])) return;
    $protoID = $_POST['protoID'];
    
    $this->query("UPDATE `sentence` SET `sentenceStatus` = 'ssFinishedGenerate' WHERE `pr2ID` = '" . $protoID . "'") or die($this->db->error);
    $this->update_status(0, $protoID);
  }
  
  public function approveMultiple() {
    if(!isset($_POST['protoIDs']) || !is_array($_POST['protoIDs'])) return;
    
    $protoIDs = $_POST['protoIDs'];
       
    $wherein = '';
    $wherein .= " `pr2ID` IN (";
    for($i = 0; $i < count($protoIDs); $i++) {
      $wherein .= "'" . $protoIDs[$i] . "'";
      if($i != count($protoIDs) - 1) $wherein .= ', ';
    }
    $wherein .= ") ";
      
    $this->query("UPDATE `sentence` SET `sentenceStatus` = 'ssReviewedSplit' WHERE " . $wherein . "") or die($this->db->error);
    
    foreach($protoIDs as $protoID)
      $this->update_status(0, $protoID);

    $SentenceIDs = array();
    $query = $this->query("SELECT `sentenceID` FROM `sentence` WHERE " . $wherein . "") or die($this->db->error);
    while($sentence_data = $query->fetch_array()) {
      $SentenceIDs[] = $sentence_data['sentenceID']; 
    }
    
    require_once __DIR__ . "/../../NeoShared/Server/App/Global.php";
    try {
      $server->PredictAfterSplit($SentenceIDs);
    } catch(\Exception $e) {
      var_dump($e);
    }
  }

  public function dismissMultiple() {
    if(!isset($_POST['protoIDs']) || !is_array($_POST['protoIDs'])) return;
    $protoIDs = $_POST['protoIDs'];
       
    $wherein = '';
    $wherein .= " s.`pr2ID` IN (";
    for($i = 0; $i < count($protoIDs); $i++) {
      $wherein .= "'" . $protoIDs . "'";
      if($i != count($protoIDs) - 1) $wherein .= ', ';
    }
    $wherein .= ") ";
      
    $this->query("UPDATE `sentence` SET `sentenceStatus` = 'ssFinishedGenerate' WHERE " . $wherein . "") or die($this->db->error);
    
    foreach($protoIDs as $protoID)
      $this->update_status(0, $protoID);
  }

}

?>