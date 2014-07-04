<?php
  namespace NeoX\Model;
  
  require_once __DIR__ . "/../Train.php";
  class TTrainSplitter extends TTrain {
    
    public function getSentenceIdFromIds($sentenceIDs = array()) {
      if(!is_array($sentenceIDs) || count($sentenceIDs) == 0) return false;
        
      $wherein = "(";
      for($i = 0; $i < count($sentenceIDs); $i++) {
        $wherein .= $sentenceIDs[$i];
        if($i + 1 != count($sentenceIDs)) $wherein .= ", ";
      }
      $wherein .= ")";
      
      $query = $this->query("SELECT [[sentence.id]] FROM [[sentence]] WHERE [[sentence.id]] IN " . $wherein . " ORDER BY [[sentence.id]] ASC LIMIT 1");
      
      return $this->result($query);
    } 
    
  }
?>