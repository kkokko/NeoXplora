<?php 

require_once __DIR__ . "/../ActionRequest.php";
class TReviewCRep extends TActionRequest {
  
  public function load() {
    $page = isset($_POST['page'])?$_POST['page']:1;
    $per_page = 10;
    $pagination = "";
    $query = $this->query("
      SELECT COUNT(s.`sentenceID`) AS total 
      FROM `sentence` s 
      INNER JOIN (
        SELECT `pageID`
        FROM `page`
        WHERE `pageStatus` IN ('psTrainedCRep', 'psReviewingCRep')
        ORDER BY `pageID` ASC
        LIMIT 1
      ) p ON s.`pageID` = p.`pageID`
    ") or die($this->db->error);
    $count_data = $query->fetch_array();
    $pageTitle = "";
    
    if($count_data['total'] > 0) {
      $pages = ceil($count_data['total'] / $per_page);
      $start = ($page - 1) * $per_page;
      
      $query = $this->query("
        SELECT s.*, p.`title`
        FROM `sentence` s 
        INNER JOIN (
          SELECT `pageID`, `title`
          FROM `page`
          WHERE `pageStatus` IN ('psTrainedCRep', 'psReviewingCRep')
          ORDER BY `pageID` ASC
          LIMIT 1
        ) p ON s.`pageID` = p.`pageID`
        ORDER BY p.`pageID` ASC, s.`sentenceID` ASC
        LIMIT " . $start . ", " . $per_page . "
      ") or die($this->db->error);
      
      if(!$query->num_rows) {
        $query = $this->query("
          SELECT s.*, p.`title`
          FROM `sentence` s 
          INNER JOIN (
            SELECT `pageID`, `title`
            FROM `page`
            WHERE `pageStatus` IN ('psTrainedCRep', 'psReviewingCRep')
            ORDER BY `pageID` ASC
            LIMIT 1
          ) p ON s.`pageID` = p.`pageID`
          ORDER BY p.`pageID` ASC, s.`sentenceID` ASC
          LIMIT 0, " . $per_page . "
        ") or die($this->db->error);
        $page = 1;
      }

      $data = "<table class='sentencestbl reviewrep' style='width:100%'>";
      $data .= '<tr>';
      $data .= '<th style="width:30%">Sentence</th>';
      $data .= '<th>Context Representation</th>';
      $data .= '<th style="width:200px">Actions</th>';
      $data .= '</tr>';
      
      while($sentence_data = $query->fetch_array()) {
        $data .= "<tr id='s" . $sentence_data['sentenceID'] . "'";
        if($sentence_data['sentenceStatus'] == 'ssReviewedCRep')
          $data .= " class='approvedrow'";
        $data .= ">";
        $data .= "<td>" . $sentence_data['sentence'] . "</td>";
        $data .= "<td><input type='text' class='newValue' value='" . htmlspecialchars($sentence_data['context_rep'], ENT_QUOTES) . "' /></td>";
        $data .= '<td><a href="javascript:void(0)" class="approveReviewCRepButton">Approve</a> <a href="javascript:void(0)" class="dismissReviewCRepButton">Dismiss</a></td>';
        $data .= "</tr>";
        
        $pageTitle = $sentence_data['title'];
      }
      $data .= "</table>";
      
      $pagination_array = $this->generate_pagination($page, $pages);
      foreach($pagination_array as $apage) {
        if($apage != "skip") {
          if($page == $apage) {
            $pagination .= "<span class='goToPageCRep currentPage'>" . $apage . "</span> ";
          } else {
            $pagination .= "<span class='goToPageCRep'>" . $apage . "</span> ";
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
      'pagination' => $pagination,
      'title' => $pageTitle
    );
    
    echo json_encode($response);
  }
  
  public function approve() {
    if(!isset($_POST['newValue']) || !isset($_POST['sentenceID'])) return;
    $newValue = htmlspecialchars_decode($_POST['newValue'], ENT_QUOTES);
    $sentenceID = $_POST['sentenceID'];
    
    $this->query("UPDATE `sentence` SET `sentenceStatus` = 'ssReviewedCRep', `context_rep` = '" . $this->db->escape_string($newValue) . "' WHERE `sentenceID` = '" . $sentenceID . "'") or die($this->db->error);
    $newStatus = $this->update_status($sentenceID);
    
    $nextPage = "0";
    
    if($newStatus == 'psReviewedCRep') {
      $nextPage = "1";
    }
    
    $response = array(
      "nextPage" => $nextPage
    );
    
    echo json_encode($response);
  }
  
  public function dismiss() {
    if(!isset($_POST['sentenceID'])) return;
    $sentenceID = $_POST['sentenceID'];
    
    $this->query("UPDATE `sentence` SET `sentenceStatus` = 'ssReviewedRep' WHERE `sentenceID` = '" . $sentenceID . "'") or die($this->db->error);
    $this->query("UPDATE `page` p, `sentence` s SET p.`pageStatus` = 'psTrainingCRep' WHERE s.`sentenceID` = '" . $sentenceID . "' AND s.`pageID` = p.`pageID`");
  }
  
  public function approveMultiple() {
    if(!isset($_POST['sentences']) || !is_array($_POST['sentences'])) return;
    $sentences = $_POST['sentences'];
    $sentenceID = 0;
    foreach($sentences AS $sentence) {
      $this->query("UPDATE `sentence` SET `sentenceStatus` = 'ssReviewedCRep', `representation` = '" . $this->db->escape_string($sentence['newValue']) . "' WHERE `sentenceID` = '" . $sentence['sentenceID'] . "'") or die($this->db->error);
      $sentenceID = $sentence['sentenceID'];
    }
    
    $newStatus = $this->update_status($sentenceID);
    $nextPage = "0";
    
    if($newStatus == 'psReviewedCRep') {
      $nextPage = "1";
    }
    
    $response = array(
      "nextPage" => $nextPage
    );
    
    echo json_encode($response);
    
  }

  public function dismissMultiple() {
    if(!isset($_POST['sentences']) || !is_array($_POST['sentences'])) return;
    $sentences = $_POST['sentences'];
    foreach($sentences AS $sentence) {
      $this->query("UPDATE `sentence` SET `sentenceStatus` = 'ssReviewedRep' WHERE `sentenceID` = '" . $sentence['sentenceID'] . "'") or die($this->db->error);
      $this->update_status($sentence['sentenceID']);
    }
  }
  
}

?>