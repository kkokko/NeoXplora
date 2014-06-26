<?php 

require_once __DIR__ . "/../ActionRequest.php";
class TReviewSummary extends TActionRequest {
  
  public function load() {
    $query = $this->query("SELECT * FROM `paragraph` p INNER JOIN `page` s ON p.`pageID` = s.`pageID` WHERE `AboutFLAG` = 'AwaitingReview'") or die($this->db->error);
    
    if($query->num_rows) {
      $data = "<table class='sentencestbl reviewrep' style='width:90%'>";
      $data .= '<tr>';
      $data .= '<th style="width:30%">Story</th>';
      $data .= '<th>About</th>';
      $data .= '<th style="width:200px">Actions</th>';
      $data .= '</tr>';
      
      while($summary_data = $query->fetch_array()) {
        $data .= "<tr id='s" . $summary_data['pgraphID'] . "'>";
        $data .= "<td style='font-size: 11px'>" . substr($summary_data['body'], 0, '400') . "..." . "</td>";
        $data .= "<td><textarea class='newValue'>" . addslashes($summary_data['about']) . "</textarea></td>";
        $data .= '<td><a href="javascript:void(0)" class="approveReviewSummaryButton">Approve</a> <a href="javascript:void(0)" class="dismissReviewSummaryButton">Dismiss</a></td>';
        $data .= "</tr>";
      }
      $data .= "</table>";
    } else {
      $data = '<tr><td>There are no summaries to review.</td></tr>';
    }
    
    $response = array(
      'data' => $data
    );
    
    echo json_encode($response);
  }

  public function approve() {
    if(!isset($_POST['summaryID']) || !isset($_POST['newValue'])) return;
    $summaryID = $_POST['summaryID'];
    $newValue = $_POST['newValue'];
    
    $this->query("UPDATE `paragraph` SET `AboutFLAG` = 'Passed', `about` = '" . $this->db->escape_string($newValue) . "' WHERE `pgraphID` = '" . $summaryID . "'") or die($this->db->error);
  }
  
  public function dismiss() {
    if(!isset($_POST['summaryID'])) return;
    $summaryID = $_POST['summaryID'];
        
    $this->query("DELETE FROM `paragraph` WHERE `pgraphID` = '" . $summaryID . "'") or die($this->db->error);
  }
  
}

?>