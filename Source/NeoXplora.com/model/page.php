<?php
  require_once APP_DIR . "/app/system/appentity.php";
	class ModelPage extends TAppEntity {
    
    public function count() {
      $query = $this->db->query("
        SELECT 
          COUNT(p1.`pageID`) AS totalPages, 
          COUNT(p2.`pageID`) AS totalPagesSplitTrained, 
          COUNT(p3.`pageID`) AS totalPagesRepTrained, 
          COUNT(p4.`pageID`) AS totalPagesCRepTrained
        FROM `page` p1
        LEFT JOIN page p2 ON p1.`pageID` = p2.`pageID` AND p2.`pageStatus` IN ('psTrainedSplit', 'psReviewedSplit')
        LEFT JOIN page p3 ON p1.`pageID` = p3.`pageID` AND p3.`pageStatus` IN ('psTrainedRep', 'psReviewedRep')
        LEFT JOIN page p4 ON p1.`pageID` = p4.`pageID` AND p4.`pageStatus` IN ('psTrainedCRep', 'psReviewedCRep')
      ") or die($this->db->error);
      $count_data = $query->fetch_array();
      
      return $count_data;
    }
    
    public function getPageById($pageId) {
      $query = $this->db->query("SELECT * FROM `page` WHERE `pageID` = '" . $pageId . "'") or die($this->db->error);
      
      if($query->num_rows) {
        return $query->fetch_array();
      } else {
        return false;
      }
    }
    
	}
?>