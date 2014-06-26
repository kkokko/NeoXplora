<?php 

require_once __DIR__ . "/../ActionRequest.php";
class TGeneral extends TActionRequest {
  
  public function examples() {
    $page = isset($_POST['page'])?$_POST['page']:1;
    $per_page = 25;
    $query = $this->query("SELECT COUNT(*) AS total FROM `sentence` se INNER JOIN `page` st ON se.`pageID` = st.`pageID` WHERE st.`is_checked` = '1' AND se.`is_incorporate_trained` = '1' AND TRIM(se.`context_rep`) <> ''");
    $count_data = $query->fetch_array();
    $pages = ceil($count_data['total'] / $per_page);
    $start = ($page - 1) * $per_page;
    $query = $this->query("SELECT * FROM `sentence` se INNER JOIN `page` st ON se.`pageID` = st.`pageID` WHERE st.`is_checked` = '1' AND se.`is_incorporate_trained` = '1' AND TRIM(se.`context_rep`) <> '' LIMIT " . $start . ", " . $per_page . "");
   
    $data = "";
    $data .= '<tr>';
    $data .= '<th >Sentence</th>';
    $data .= '<th>Representation</th>';
    $data .= '<th>Contextual Representation</th>';
    $data .= '</tr>';
    
    while($sentence_data = $query->fetch_array()) {
      $data .= "<tr id='s" . $sentence_data['sentenceID'] . "'>";
      $data .= "<td>" . $sentence_data['sentence'] . "</td>";
      $data .= "<td>" . $sentence_data['representation'] . "</td>";
      $data .= "<td>" . $sentence_data['context_rep'] . "</td>";
      $data .= "</tr>";
    }
    
    $pagination_array = $this->generate_pagination($page, $pages);
    $pagination = "";
    foreach($pagination_array as $apage) {
      if($apage != "skip") {
        if($page == $apage) {
          $pagination .= "<span class='goToPage currentPage'>" . $apage . "</span> ";
        } else {
          $pagination .= "<span class='goToPage'>" . $apage . "</span> ";
        }
      } else {
       $pagination .= "...";
      }
    }
    
    if($pagination != "") {
      $pagination = "Pages: " . $pagination;
    }
    
    $response = array(
      'data' => $data,
      'pagination' => $pagination
    );
    
    echo json_encode($response);
  }
  
  public function leave() {
    if(!isset($_SESSION['storyID'])) return;
    $storyID = $_SESSION['storyID'];
    
    $this->query("UPDATE `page` SET `is_assigned` = '0', `assigned_date` = NULL WHERE `pageID` = '" . $storyID . "'");
    unset($_SESSION['storyID']);
  }
  
}

?>