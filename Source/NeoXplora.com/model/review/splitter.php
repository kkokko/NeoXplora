<?php
  namespace NeoX\Model;
  
  require_once __DIR__ . "/../Train.php";
  class TReviewSplitter extends TTrain {
    
    public function countProtos() {
      $query = $this->query("
        SELECT COUNT(a.[[proto.id]]) AS total
        FROM (
          SELECT DISTINCT p.[[proto.id]]
          FROM [[proto]] p
          INNER JOIN [[sentence]] s ON p.[[proto.id]] = s.[[sentence.protoid]]
          WHERE s.[[sentence.status]] = :1
        ) a
      ", 'ssTrainedSplit');
      
      return $this->result($query);
    }
    
    public function getSentences($offset, $limit) {
      $query = $this->query("
        SELECT 
          se.[[sentence.id]] as sentenceId, 
          se.[[sentence.name]] as sentenceName, 
          a.[[proto.id]] as protoId, 
          a.[[proto.name]] as protoName, 
          a.[[proto.pageid]] as pageId
        FROM [[sentence]] se
        INNER JOIN  (
          SELECT DISTINCT pr.*
           FROM [[proto]] pr
           INNER JOIN [[sentence]] s ON pr.[[proto.id]] = s.[[sentence.protoid]]
           WHERE s.[[sentence.status]] = :1
           ORDER BY s.[[sentence.pageid]] ASC, pr.[[proto.order]] ASC, s.[[sentence.order]] ASC  
           LIMIT :2, :3
        ) a ON a.[[proto.id]] = se.[[sentence.protoid]]
        ORDER BY a.[[proto.order]] ASC, se.[[sentence.order]] ASC
      ", 'ssTrainedSplit', intval($offset), intval($limit));
      
      return $this->result($query);
    }

    public function getFirstSentenceIdForProtoId($protoId) {
      $query = $this->query("
        SELECT s.[[sentence.id]], pr.[[proto.name]]
        FROM [[sentence]] s
        INNER JOIN [[proto]] pr ON s.[[sentence.protoid]] = pr.[[proto.id]] AND pr.[[proto.id]] = :1
        ORDER BY s.[[sentence.order]] ASC, pr.[[proto.order]] ASC
        LIMIT 1
      ", intval($protoId));  
      
      return $this->result($query);    
    }
    
    public function revertSentenceToProto($sentenceId, $protoId) {
      $updateQuery = $this->query("
        UPDATE [[sentence]] s
        INNER JOIN [[proto]] pr 
          ON pr.[[proto.id]] = s.[[sentence.protoid]]
        SET s.[[sentence.name]] = pr.[[proto.name]],
            s.[[sentence.status]] = :1  
        WHERE s.[[sentence.id]] = :2
      ", 'ssTrainedSplit', intval($sentenceId));
      
      $deleteQuery = $this->query("
        DELETE FROM [[sentence]] 
        WHERE [[sentence.protoid]] = :1 
        AND [[sentence.id]] <> :2
      ", $protoId, $sentenceId);
    
      return ($this->result($updateQuery) && $this->result($deleteQuery));
    }
    
  }
?>