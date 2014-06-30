<?php 

require_once __DIR__ . "/../ActionRequest.php";
class TReviewRep extends TActionRequest {
  
  public function load() {
    $page = isset($_POST['page'])?$_POST['page']:1;
    $per_page = 10;
    $pagination = "";
    $query = $this->query("
      SELECT COUNT(s.`sentenceID`) AS total 
      FROM `sentence` s
      WHERE s.`sentenceStatus` = 'ssTrainedRep'
    ") or die($this->db->error);    
    $count_data = $query->fetch_array();
    
    if($count_data['total'] > 0) {
      $pages = ceil($count_data['total'] / $per_page);
      $start = ($page - 1) * $per_page;
      
      $query = $this->query("
        SELECT DISTINCT s.* 
        FROM `sentence` s 
        INNER JOIN `page` p ON s.`pageID` = p.`pageID`
        WHERE s.`sentenceStatus` = 'ssTrainedRep'
        ORDER BY p.`pageID` ASC, s.`sentenceID` ASC
        LIMIT " . $start . ", " . $per_page . "
      ") or die($this->db->error);

      if(!$query->num_rows) {
        $query = $this->query("
          SELECT DISTINCT s.* 
          FROM `sentence` s 
          INNER JOIN `page` p ON s.`pageID` = p.`pageID`
          WHERE s.`sentenceStatus` = 'ssTrainedRep'
          ORDER BY p.`pageID` ASC, s.`sentenceID` ASC
          LIMIT 0, " . $per_page . "
        ") or die($this->db->error);
        $page = 1;
      }

      $data = "<table class='sentencestbl reviewrep' style='width:100%'>";
      $data .= '<tr>';
      $data .= '<th style="width:30%">Sentence</th>';
      $data .= '<th>Representation</th>';
      $data .= '<th style="width:200px">Actions</th>';
      $data .= '</tr>';
      
      while($sentence_data = $query->fetch_array()) {
        $data .= "<tr id='s" . $sentence_data['sentenceID'] . "'>";
        $data .= "<td>" . $sentence_data['sentence'] . "</td>";
        $data .= "<td><input type='text' class='newValue' value='" . htmlspecialchars($sentence_data['representation'], ENT_QUOTES) . "' /></td>";
        $data .= '<td><a href="javascript:void(0)" class="approveReviewRepButton">Approve</a> <a href="javascript:void(0)" class="dismissReviewRepButton">Dismiss</a></td>';
        $data .= "</tr>";
      }
      $data .= "</table>";
      
      $pagination_array = $this->generate_pagination($page, $pages);
      foreach($pagination_array as $apage) {
        if($apage != "skip") {
          if($page == $apage) {
            $pagination .= "<span class='goToPageRep currentPage'>" . $apage . "</span> ";
          } else {
            $pagination .= "<span class='goToPageRep'>" . $apage . "</span> ";
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
  
  public function approve() {
    if(!isset($_POST['newValue']) || !isset($_POST['sentenceID'])) return;
    $newValue = htmlspecialchars_decode($_POST['newValue'], ENT_QUOTES);
    $sentenceID = $_POST['sentenceID'];
    
    require_once __DIR__ . "/../../NeoShared/Server/App/Global.php";
    $validator = $server->ValidateRep($newValue);
    if($validator === true) {
      $this->query("UPDATE `sentence` SET `sentenceStatus` = 'ssReviewedRep', `representation` = '" . $this->db->escape_string($newValue) . "' WHERE `sentenceID` = '" . $sentenceID . "'") or die($this->db->error);
      $this->update_status($sentenceID);
    } else {
      $response = array(
        "ErrorString" => $validator['ErrorString'],
        "StrIndex" => $validator['StrIndex']
      ); 
      echo json_encode($response);
    }
    
  }
  
  public function dismiss() {
    if(!isset($_POST['sentenceID'])) return;
    $sentenceID = $_POST['sentenceID'];
    
    $this->query("UPDATE `sentence` SET `sentenceStatus` = 'ssReviewedSplit' WHERE `sentenceID` = '" . $sentenceID . "'") or die($this->db->error);
    $this->query("UPDATE `page` p, `sentence` s SET p.`pageStatus` = 'psTrainingRep' WHERE s.`sentenceID` = '" . $sentenceID . "' AND s.`pageID` = p.`pageID`");
  }
    
  public function approveMultiple() {
    if(!isset($_POST['sentences']) || !is_array($_POST['sentences'])) return;
    $sentences = $_POST['sentences'];
    require_once __DIR__ . "/../../NeoService/App/Global.php";
    $response = array();
    foreach($sentences AS $sentence) {
      $validator = $server->ValidateRep($sentence['newValue']);
      if($validator === true) {
        $this->query("UPDATE `sentence` SET `sentenceStatus` = 'ssReviewedRep', `representation` = '" . $this->db->escape_string($sentence['newValue']) . "' WHERE `sentenceID` = '" . $sentence['sentenceID'] . "'") or die($this->db->error);
        $this->update_status($sentence['sentenceID']);
      } else {
        $response[$sentence['sentenceID']] = array(
          "ErrorString" => $validator['ErrorString'], 
          "StrIndex" => substr($sentence['newValue'], $validator['StrIndex'], strlen($sentence['newValue']))
        ); 
      }
    }
    
    if(count($response)) echo json_encode($response);
  }

  public function dismissMultiple() {
    if(!isset($_POST['sentences']) || !is_array($_POST['sentences'])) return;
    $sentences = $_POST['sentences'];
    foreach($sentences AS $sentence) {
      $this->query("UPDATE `sentence` SET `sentenceStatus` = 'ssReviewedSplit' WHERE `sentenceID` = '" . $sentence['sentenceID'] . "'") or die($this->db->error);
      $this->update_status($sentence['sentenceID']);
    }
  }
    
}

?>