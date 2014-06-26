<?php 

require_once __DIR__ . "/../ActionRequest.php";
class TTrainSummary extends TActionRequest {
  
  public function load() {
    $ignore = " `pageID` NOT IN ('0'";
    $query = $this->query("SELECT DISTINCT `pageID` FROM `paragraph`") or die($this->db->error);
    $i = 0;
    while($row = $query->fetch_array()) {
      $ignore .= ", '" . $row['pageID'] . "'";
    }
    $ignore .= ")";

    $query = $this->query("SELECT * FROM `page` WHERE " . $ignore) or die($this->db->error);
    
    if($query->num_rows) {
      $trainername = isset($_COOKIE['trainername'])?$_COOKIE['trainername']:"";
      $random = rand(0, $query->num_rows -1);
      $query->data_seek($random);
      $story_data = $query->fetch_array();
      $query = $this->query("SELECT * FROM `sentence` WHERE `pageID` = '" . $story_data['pageID'] . "'");
      if($query->num_rows) {
        $random = rand(0, $query->num_rows -1);
        $query->data_seek($random);
        $sentence_data = $query->fetch_array();
      }
      $data = '';
      $data .= '<table class="about sentencestbl">';
      $data .= '<tr>';
      $data .= '<td>Story</td>';
      $data .= '<td class="story-body"><input type="hidden" class="storyID" value="' . $story_data['pageID'] . '" />' . $story_data['body'] . '</td>';
      $data .= '<td></td>';
      $data .= '</tr>';
      $data .= '<tr>';
      $data .= '<td>Summary:</td>';
      $data .= '<td><textarea style="width: 80%; height: 100px;" class="aboutValue">' . $sentence_data['sentence'] . '</textarea></td>';
      $data .= '<td></td>';
      $data .= '</tr>';
      $data .= '<td colspan="3"><a href="javascript:void(0)" class="doneAboutButton">Add SUMMARY</a> <a href="javascript:void(0)" class="skipAboutButton">Skip Story</a></td>';
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
    if(!isset($_POST['storyID']) || !isset($_POST['aboutValue'])) return;
    $storyID = $_POST['storyID'];
    $aboutValue = $_POST['aboutValue'];
    $trainername = isset($_COOKIE['trainername'])?$_COOKIE['trainername']:"";
    
    $this->query("INSERT INTO `paragraph`(`about`, `pageID`, `added_by`) VALUES ('" . $this->db->escape_string($aboutValue) . "', '" . $this->db->escape_string($storyID) . "', '" . $this->db->escape_string($trainername) . "')") or die($this->db->error);
  }
  
}

?>