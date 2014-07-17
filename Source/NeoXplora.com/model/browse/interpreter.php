<?php
  namespace NeoX\Model;
  
  require_once __DIR__ . "/../Train.php";
  class TBrowseInterpreter extends TTrain {
    
    public function countSentences($categoryID = null, $offset = null, $sStatus = null, $ignoreIDs = null) {
      $query = $this->query("
        SELECT COUNT(se.[[sentence.id]]) AS total
        FROM [[sentence]] se 
        WHERE se.[[sentence.status]] = :1 ", 'ssReviewedRep');
      
      return $this->result($query);
    }
    
    public function getSentences($offset, $limit) {
      $query = $this->query("
        SELECT 
          se.[[sentence.id]] as sentenceId, 
          se.[[sentence.name]] as sentenceName, 
          se.[[sentence.rep]] as sentenceRep 
        FROM [[sentence]] se 
        WHERE se.[[sentence.status]] = :1 
        ORDER BY se.[[sentence.order]] ASC 
        LIMIT :2, :3
      ", 'ssReviewedRep', intval($offset), intval($limit));
      
      return $this->result($query);
    }
  }
?>