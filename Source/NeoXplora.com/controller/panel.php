<?php

namespace NeoX\Controller;

require_once APP_DIR . "/app/system/Object.php";
class TPanel extends \SkyCore\TObject {

  public $accessLevel = 'admin';
  private $locked = 0;
  
  public function index() {
    $this->template->addStyle("style/admin.pages.css");
    $this->template->load("index", "panel");
    $this->template->pageTitle = "Admin Panel";
    $this->template->page = "panel";
    $this->template->hide_right_box = true;
    $this->template->render();
  }
  
  public function stats() {
    $this->template->pageCounts = $this->core->entity("page")->advancedCount();
    $this->template->sentenceCounts = $this->core->entity("sentence")->advancedCount();
    
    $this->template->addStyle("style/admin.css");
    $this->template->load("stats", "panel");
    $this->template->pageTitle = "Stats | Admin Panel";
    $this->template->page = "stats_panel";
    $this->template->hide_right_box = true;
    $this->template->render();
  }
  
  public function fixorder() {
    if($this->locked) return;
    $query = $this->core->model("fixorder")->getMainProtos();
    
    $theProtoList = array();
    $rowclass = '';
    while($proto_data = $query->fetch_array()) {
      $TheProto = array(
        "type" => "proto",
        "id" => $proto_data['id'],
        "order" => $proto_data['order'],
        "name" => $proto_data['name'],
        "pageid" => $proto_data['pageid'],
        "level" => 1,
        "indentation" => 0
      );

      $kids = array_merge($this->loadChildProtos($proto_data['id'], 1), $this->loadSentences($proto_data['id'], 1));
      
      usort($kids, array("NeoX\\Controller\\TPanel", "compareRows"));
      $TheProto['kids'] = $kids;
      
      $theProtoList[] = $TheProto;
    }
    
    $theResult = $this->getFlatProtoData($theProtoList);
    
    echo "<table border='1' cellpadding='5'>";
    echo "<tr><th>PageId</th><th>Name</th><th>ProtoId</th><th>SentenceId</th><th>Order</th><th>Indentation</th></tr>";
    $order = 1;
    $lastpageid = 0;
    foreach($theResult AS $aRow) {
      if($lastpageid != $aRow['pageid']) {
        $lastpageid = $aRow['pageid'];
        $order = 1;
      }
      if($aRow['type'] == "proto") {
        $this->core->model("fixorder")->insertProtoOrder($aRow['pageid'], $aRow['id'], $order++, $aRow['indentation']);
        echo "<tr><td>" . $aRow['pageid'] . "</td><td>" . $aRow['name'] . "</td><td>" . $aRow['id'] . "</td><td>NULL</td><td>" . ($order) . "</td><td> " . $aRow['indentation'] . "</td></tr>";
      } else {
        $this->core->model("fixorder")->insertSentenceOrder($aRow['pageid'], $aRow['id'], $order++, $aRow['indentation']);
        echo "<tr><td>" . $aRow['pageid'] . "</td><td>" . $aRow['name'] . "</td><td>" . $aRow['protoid'] . "</td><td>" . $aRow['id'] . "</td><td>" . ($order) . "</td><td> " . $aRow['indentation'] . "</td></tr>";
      }
    }
    echo "</table>";
  }
  
  private function getFlatProtoData($someProtos){
    $theResult = array();
    foreach ($someProtos as &$aProto) {
      $theResult[] = $aProto;
      if(isset($aProto['kids'])) {
        $theResult = array_merge($theResult, $this->getFlatProtoData($aProto['kids']));
      }
    }
    foreach ($theResult as &$aProto) {
      if(isset($aProto['kids'])) {
        unset($aProto['kids']);
      }
    }
    return $theResult;
  }
  
  private function loadChildProtos($protoId, $intendation) {
    $data = array();
    $query = $this->core->model("fixorder")->getChildProtos($protoId);
        
    if($query->num_rows > 0) {
      while($proto_data = $query->fetch_array()) {
        $theProtoData = array(
          "type" => "proto",
          "id" => $proto_data['id'],
          "order" => $proto_data['order'],
          "name" => $proto_data['name'],
          "pageid" => $proto_data['pageid'],
          "level" => $intendation + 1,
          "indentation" => $intendation
        );

        $kids = array_merge($this->loadChildProtos($proto_data['id'], $intendation + 1), $this->loadSentences($proto_data['id'], $intendation + 1));
        
        usort($kids, array("NeoX\\Controller\\TPanel", "compareRows"));
       
        $theProtoData['kids'] = $kids;
        $data[] = $theProtoData;
      }
    }
    
    return $data;
  }
  
  private function loadSentences($protoId, $intendation) {
    $data = array();
    $query = $this->core->model("fixorder")->getSentences($protoId);
        
    if($query->num_rows > 0) {
      while($sentence_data = $query->fetch_array()) {
        $data[] = array(
          "type" => "sentence",
          "id" => $sentence_data['id'],
          "protoid" => $protoId,
          "parentid" => $protoId,
          "pageid" => $sentence_data['pageid'],
          "order" => $sentence_data['order'],
          "indentation" => $intendation,
          "status" => $sentence_data['status'],
          "name" => $sentence_data['name']
        );
      }
    }
    
    return $data;
  }

  public static function compareRows(&$a, &$b) {
    if($a['order'] > $b['order']) {
      return 1;
    } else if($a['order'] < $b['order']) {
      return -1;
    } else {
      return 0;
    }
  }
 
  
}
?>