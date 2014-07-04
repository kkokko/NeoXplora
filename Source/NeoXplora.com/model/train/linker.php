<?php
  namespace NeoX\Model;
  
  require_once __DIR__ . "/../Train.php";
  class TTrainLinker extends TTrain {
    
    public function getCategory() {
      $query = $this->db->query("
        SELECT a.*
        FROM (
          SELECT c2.`categoryID`, c2.`pageCount`, COUNT(p2.`pageID`) AS trainedCount 
          FROM (
            SELECT c.`categoryID`, COUNT(p.`pageID`) AS pageCount FROM `category` c
            INNER JOIN (
              SELECT DISTINCT pg.`pageID`, pg.`pageStatus`, pg.`categoryID`
              FROM `page`pg
              INNER JOIN `sentence` se ON pg.`pageID` = se.`pageID`
            ) p ON c.`categoryID` = p.`categoryID` AND p.`pageStatus` IN ('psReviewedRep', 'psTrainingCRep')
            GROUP BY(c.`categoryID`)
          ) c2
          LEFT JOIN `page` p2 ON p2.`categoryID` = c2.`categoryID` AND p2.`pageStatus` NOT IN ('psFinishedCrawl', 'psFinishedGenerate')
          GROUP BY (c2.`categoryID`)
        ) a INNER JOIN `page` p3 ON p3.`categoryID` = a.`categoryID`
        GROUP BY p3.`categoryID`
        ORDER BY a.`trainedCount` ASC
        LIMIT 1;
      ") or die($this->db->error);
      
      return $query->fetch_array();  
    }
    
    public function getPageByCategoryID($categoryID, $offset = 0) {
      $query = $this->db->query("
        SELECT DISTINCT p.`pageID`, p.`pageStatus`, p.`title`
        FROM `page` p
        INNER JOIN `sentence` se ON p.`pageID` = se.`pageID` AND p.`pageStatus` IN ('psReviewedRep', 'psTrainingCRep')
        WHERE p.`categoryID` = '" . $categoryID . "'
        ORDER BY p.`assigned_date` ASC, `pageID` ASC
        LIMIT " . $offset . ",1
      ") or die($this->db->error);
      
      if($query->num_rows) {
        return $query->fetch_array();
      } else {
        return false;
      }
    }
    
  }
?>