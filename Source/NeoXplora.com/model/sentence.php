<?php
  require_once APP_DIR . "/app/system/appentity.php";
	class ModelSentence extends TAppEntity {
    
    public function count() {
      $query = $this->db->query("
        SELECT 
          COUNT(s1.`sentenceID`) AS totalSentences, 
          COUNT(s2.`sentenceID`) AS totalSentencesSplitTrained, 
          COUNT(s3.`sentenceID`) AS totalSentencesRepTrained, 
          COUNT(s4.`sentenceID`) AS totalSentencesCRepTrained
        FROM `sentence` s1
        LEFT JOIN sentence s2 ON s1.`sentenceID` = s2.`sentenceID` AND s2.`sentenceStatus` IN ('ssTrainedSplit', 'ssReviewedSplit')
        LEFT JOIN sentence s3 ON s1.`sentenceID` = s3.`sentenceID` AND s3.`sentenceStatus` IN ('ssTrainedRep', 'ssReviewedRep')
        LEFT JOIN sentence s4 ON s1.`sentenceID` = s4.`sentenceID` AND s4.`sentenceStatus` IN ('ssTrainedCRep', 'ssReviewedCRep')
      ") or die($this->db->error);
      $count_data = $query->fetch_array();
      
      return $count_data;
    }
    
	}
?>