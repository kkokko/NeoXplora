<?php
  namespace NeoX\Entity;
  
  require_once APP_DIR . "/app/system/Entity.php";
  class TPage extends \SkyCore\TEntity {
	  
    //Table Name in the DB
    public static $tablename = "neox_page";
    
    //Table Fields in the DB
    public static $tok_id = "Id";
    public static $tok_title = "Name";
    public static $tok_body = "Body";
    public static $tok_categoryid = "CategoryId";
    public static $tok_status = "Status";
    public static $tok_source = "Source";
    public static $tok_assigneddate = "AssignedDate";

    public function advancedCount() {
      $query = $this->query("
        SELECT 
          COUNT(p1.[[page.id]]) AS totalPages, 
          COUNT(p2.[[page.id]]) AS totalPagesSplitTrained, 
          COUNT(p3.[[page.id]]) AS totalPagesRepTrained, 
          COUNT(p4.[[page.id]]) AS totalPagesCRepTrained
        FROM [[page]] p1
        LEFT JOIN [[page]] p2 ON p1.[[page.id]] = p2.[[page.id]] AND p2.[[page.status]] IN (:1, :2)
        LEFT JOIN [[page]] p3 ON p1.[[page.id]] = p3.[[page.id]] AND p3.[[page.status]] IN (:3, :4)
        LEFT JOIN [[page]] p4 ON p1.[[page.id]] = p4.[[page.id]] AND p4.[[page.status]] IN (:5, :6)
      ", 'psTrainedSplit', 'psReviewedSplit', 'psTrainedRep', 'psReviewedRep', 'psTrainedCRep', 'psReviewedCRep');
      
      return $this->result($query);
    }
    
    public function deleteWithData($pageId) {
      $query = $this->query("
        UPDATE [[sentence]] s1
        INNER JOIN [[sentence]] s2 ON s1.[[sentence.guessaid]] = s2.[[sentence.id]]
        SET s1.[[guessaid]] = null
        WHERE s2.[[sentence.pageid]] = :1
      ", intval($pageId));
      
      $query = $this->query("
        UPDATE [[sentence]] s1
        INNER JOIN [[sentence]] s2 ON s1.[[sentence.guessbid]] = s2.[[sentence.id]]
        SET s1.[[guessbid]] = null
        WHERE s2.[[sentence.pageid]] = :1
      ", intval($pageId));
      
      $query = $this->query("
        UPDATE [[sentence]] s1
        INNER JOIN [[sentence]] s2 ON s1.[[sentence.guesscid]] = s2.[[sentence.id]]
        SET s1.[[guesscid]] = null
        WHERE s2.[[sentence.pageid]] = :1
      ", intval($pageId));
      
      $query = $this->query("
        UPDATE [[sentence]] s1
        INNER JOIN [[sentence]] s2 ON s1.[[sentence.guessdid]] = s2.[[sentence.id]]
        SET s1.[[guessdid]] = null
        WHERE s2.[[sentence.pageid]] = :1
      ", intval($pageId));
      
      $query = $this->query("
        DELETE s.*, pr.*
        FROM [[sentence]] s
        INNER JOIN [[proto]] pr ON s.[[sentence.pageid]] = pr.[[proto.pageid]]
        WHERE s.[[sentence.pageid]] = :1
      ", intval($pageId));
      
      $this->delete(intval($pageId));
    }
    
	}
?>