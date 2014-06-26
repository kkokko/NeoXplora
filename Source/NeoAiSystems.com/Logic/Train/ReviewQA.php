<?php 

require_once __DIR__ . "/../ActionRequest.php";
class TReviewQA extends TActionRequest {
  
  public function load() {
    $query = $this->query("SELECT * FROM `qa` WHERE `QAFlag` = 'AwaitingReview'") or die($this->db->error);
    
    if($query->num_rows) {
      $data = "<table class='sentencestbl reviewrep' style='width:90%'>";
      $data .= '<tr>';
      $data .= '<th>Question</th>';
      $data .= '<th>Answer</th>';
      $data .= '<th style="width:200px">Actions</th>';
      $data .= '</tr>';
      
      while($qa_data = $query->fetch_array()) {
        $data .= "<tr id='q" . $qa_data['questionID'] . "'>";
        $data .= "<td><input type='text' class='newValueQ' value='" . addslashes($qa_data['question']) . "' /></td>";
        $data .= "<td><input type='text' class='newValueA' value='" . addslashes($qa_data['answer']) . "' /></td>";
        $data .= '<td><a href="javascript:void(0)" class="approveReviewQAButton">Approve</a> <a href="javascript:void(0)" class="dismissReviewQAButton">Dismiss</a></td>';
        $data .= "</tr>";
      }
      $data .= "</table>";
    } else {
      $data = '<tr><td>There are no QAs to review.</td></tr>';
    }
    
    $response = array(
      'data' => $data
    );
    
    echo json_encode($response);
  }

  public function approve() {
    if(!isset($_POST['questionID']) || !isset($_POST['newValueQ']) || !isset($_POST['newValueA'])) return;
    $questionID = $_POST['questionID'];
    $newValueQ = $_POST['newValueQ'];
    $newValueA = $_POST['newValueA'];
    
    $this->query("UPDATE `qa` SET `QAFlag` = 'Passed', `question` = '" . $this->db->escape_string($newValueQ) . "', `answer` = '" . $this->db->escape_string($newValueA) . "' WHERE `questionID` = '" . $questionID . "'") or die($this->db->error);
  }
  
  public function dismiss() {
    if(!isset($_POST['questionID'])) return;    
    $questionID = $_POST['questionID'];
    
    $this->query("DELETE FROM `qa` WHERE `questionID` = '" . $questionID . "'") or die($this->db->error);
  }
  
}

?>