<?php
  namespace NeoX\Model;
  
  require_once __DIR__ . "/../Train.php";
  class TReviewInterpreter extends TTrain {
    
    public function countSentences($status, $pageId, $dummy2 = null, $dummy3 = null) {
      $condition = '';
      $status = "s.[[sentence.status]] = 'ssTrainedRep'";
      if($pageId) {
        $condition = "AND s.[[sentence.pageid]] = " . intval($pageId);
        $status = "s.[[sentence.status]] IN ('ssReviewedSplit', 'ssTrainedRep')";
      }
      
      $query = $this->query("
        SELECT COUNT(s.[[sentence.id]]) AS total 
        FROM [[sentence]] s
        WHERE " . $status . "
        " . $condition . "
      ");
      
      return $this->result($query);
    }
    
    public function getSentences($status, $pageId, $offset, $limit, $dummy2 = null) {
      $condition = '';
      $status = "s.[[sentence.status]] = 'ssTrainedRep'";
      if($pageId) {
        $condition = "AND s.[[sentence.pageid]] = " . intval($pageId);
        $status = "s.[[sentence.status]] IN ('ssReviewedSplit', 'ssTrainedRep')";
      }
      
      $query = $this->query("
        SELECT DISTINCT s.* 
        FROM [[sentence]] s 
        INNER JOIN [[page]] p ON s.[[sentence.pageid]] = p.[[page.id]]
        WHERE " . $status . "
        " . $condition . "
        ORDER BY p.[[page.id]] ASC, s.[[sentence.order]] ASC, s.[[sentence.id]] ASC
        LIMIT :1, :2
      ", intval($offset), intval($limit));
      
      return $this->result($query);
    }
    
  }
?>