<?php
  namespace NeoX\Model;
  
  require_once __DIR__ . "/../Train.php";
  class TReviewInterpreter extends TTrain {
    
    public function countSentences($status, $dummy1 = null, $dummy2 = null, $dummy3 = null) {
      $query = $this->query("
        SELECT COUNT(s.[[sentence.id]]) AS total 
        FROM [[sentence]] s
        WHERE s.[[sentence.status]] = :1
      ", $status);
      
      return $this->result($query);
    }
    
    public function getSentences($status, $offset, $limit, $dummy1 = null, $dummy2 = null) {
      $query = $this->query("
        SELECT DISTINCT s.* 
        FROM [[sentence]] s 
        INNER JOIN [[page]] p ON s.[[sentence.pageid]] = p.[[page.id]]
        WHERE s.[[sentence.status]] = :1
        ORDER BY p.[[page.id]] ASC, s.[[sentence.order]] ASC, s.[[sentence.id]] ASC
        LIMIT :2, :3
      ", $status, intval($offset), intval($limit));
      
      return $this->result($query);
    }
    
  }
?>