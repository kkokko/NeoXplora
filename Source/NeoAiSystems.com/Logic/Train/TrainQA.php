<?php 

require_once __DIR__ . "/../ActionRequest.php";
class TTrainQA extends TActionRequest {
  
  public function load() {
    $query = $this->query("SELECT * FROM `page`") or die($this->db->error);
    
    if($query->num_rows) {
      $trainername = isset($_COOKIE['trainername'])?$_COOKIE['trainername']:"";
      $random = rand(0, $query->num_rows -1);
      $query->data_seek($random);
      $story_data = $query->fetch_array();
      $data = '';
      $data .= '<table class="addqa sentencestbl">';
      $data .= '<tr>';
      $data .= '<td>Story</td>';
      $data .= '<td class="story-body"><input type="hidden" class="storyID" value="' . $story_data['pageID'] . '" />' . $story_data['body'] . '</td>';
      $data .= '<td></td>';
      $data .= '</tr>';
      $data .= '<tr>';
      $data .= '<td>Question:</td>';
      $data .= '<td><input type="text" class="questionValue" /></td>';
      $data .= '<td></td>';
      $data .= '</tr>';
      $data .= '<tr>';
      $data .= '<td>Answer:</td>';
      $data .= '<td><input type="text" class="answerValue" /></td>';
      $data .= '<td></td>';
      $data .= '</tr>';
      $data .= '<tr>';
      $data .= '<td>Because... (optional):</td>';
      $data .= '<td><input type="text" class="whyValue" /></td>';
      $data .= '<td></td>';
      $data .= '</tr>';
      $data .= '<tr>';
      $data .= '<td colspan="3"><a href="javascript:void(0)" class="doneAddQAButton">ADD QA</a> <a href="javascript:void(0)" class="skipQAButton">NEXT</a></td>';
      $data .= '</tr>';
      $data .= '</table>';
    } else {
      $data = 'No story to dispaly';
    }

    $response = array(
      'data' => $data
    );
    
    echo json_encode($response);
  }
  
  public function add() {
    if(!isset($_POST['storyID']) || !isset($_POST['questionValue']) || !isset($_POST['answerValue']) || !isset($_POST['whyValue'])) return;
    
    $storyID = $_POST['storyID'];
    $answerValue = $_POST['answerValue'];
    $whyValue = $_POST['whyValue'];
    $questionValue = $_POST['questionValue'];
    $trainername = isset($_COOKIE['trainername'])?$_COOKIE['trainername']:"";
    $this->query("INSERT INTO `qa`(`question`, `answer`, `pageID`, `added_by`, `why`) VALUES ('" . $this->db->escape_string($questionValue) . "', '" . $this->db->escape_string($answerValue) . "', '" . $this->db->escape_string($storyID) . "', '" . $this->db->escape_string($trainername) . "', '" . $this->db->escape_string($whyValue) . "')") or die($this->db->error);
  }
  
}

?>