<?php
  namespace NeoX\Model;
  
  require_once APP_DIR . "/app/system/Model.php";
  class TPanelPages extends \SkyCore\TModel {
    
    public function countPages() {
      $query = $this->query("
        SELECT COUNT(p.[[page.id]]) AS total
        FROM [[page]] p
      ");
      
      return $this->result($query);
    }
    
    public function getPages($offset, $limit) {
      $query = $this->query("
        SELECT
          p.[[page.id]], 
          p.[[page.status]], 
          p.[[page.title]],
          p.[[page.body]]
        FROM [[page]] p  
        ORDER BY p.[[page.title]] ASC 
        LIMIT :1, :2
      ", intval($offset), intval($limit));
      
      return $this->result($query);
    }
  }
?>