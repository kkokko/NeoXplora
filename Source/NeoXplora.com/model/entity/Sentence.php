<?php
  namespace NeoX\Entity;
  
  require_once APP_DIR . "/app/system/Entity.php";
  class TSentence extends \SkyCore\TEntity {
	  
    //Table Name in the DB
    public static $tablename = "neox_sentence";
    
    //Table Fields in the DB
    public static $tok_id = "Id";
    public static $tok_name = "Name";
    public static $tok_rep = "Rep";
    public static $tok_crep = "CRep";
    public static $tok_srep = "SRep";
    public static $tok_pageid = "PageId";
    public static $tok_protoid = "ProtoId";
    public static $tok_pos = "Pos";
    public static $tok_guessaid = "GuessAId";
    public static $tok_guessbid = "GuessBId";
    public static $tok_guesscid = "GuessCId";
    public static $tok_guessdid = "GuessDId";
    public static $tok_status = "Status"; // ssFinishedGenerate, ssTrainedSplit, ssReviewedSplit, ssTrainedRep, ssReviewedRep, ssReviewedCRep
    public static $tok_assigneddate = "AssignedDate";
    public static $tok_order = "Order";
    
    public function advancedCount() {
      $query = $this->query("
        SELECT 
          COUNT(s1.[[sentence.id]]) AS totalSentences, 
          COUNT(s2.[[sentence.id]]) AS totalSentencesSplitTrained, 
          COUNT(s3.[[sentence.id]]) AS totalSentencesRepTrained, 
          COUNT(s4.[[sentence.id]]) AS totalSentencesCRepTrained
        FROM [[sentence]] s1
        LEFT JOIN [[sentence]] s2 ON s1.[[sentence.id]] = s2.[[sentence.id]] AND s2.[[sentence.status]] IN (:1, :2)
        LEFT JOIN [[sentence]] s3 ON s1.[[sentence.id]] = s3.[[sentence.id]] AND s3.[[sentence.status]] IN (:3, :4)
        LEFT JOIN [[sentence]] s4 ON s1.[[sentence.id]] = s4.[[sentence.id]] AND s4.[[sentence.status]] IN (:5, :6)
      ", 'ssTrainedSplit', 'ssReviewedSplit', 'ssTrainedRep', 'ssReviewedRep', 'ssTrainedCRep', 'ssReviewedCRep');
      
      return $this->result($query);
    }
    
	}
?>