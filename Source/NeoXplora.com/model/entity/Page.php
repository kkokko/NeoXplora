<?php
  namespace NeoX\Entity;
  
  require_once APP_DIR . "/app/system/Entity.php";
  class TPage extends \SkyCore\TEntity {
	  
    public static $entityname = "page";
    
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
      $count_data = $query->fetch_array();
      
      return $this->result($query);
    }
    
	}
?>