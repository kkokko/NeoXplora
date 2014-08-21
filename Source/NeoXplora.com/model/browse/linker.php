<?php
  namespace NeoX\Model;
  
  require_once APP_DIR . "/app/system/Model.php";
  class TBrowseLinker extends \SkyCore\TModel {
    
    public function countPages() {
      $query = $this->query("
        SELECT COUNT(p.[[page.id]]) AS total
        FROM [[page]] p 
        WHERE p.[[page.status]] = :1 ", 'psReviewedCRep');
      
      return $this->result($query);
    }
    
    public function getPages($offset, $limit) {
      $query = $this->query("
        SELECT
          p.[[page.id]], 
          p.[[page.status]], 
          p.[[page.title]] 
        FROM [[page]] p 
        WHERE p.[[page.status]] = :1 
        ORDER BY p.[[page.id]] ASC 
        LIMIT :2, :3
      ", 'psReviewedCRep', intval($offset), intval($limit));
      
      return $this->result($query);
    }
  }
?>