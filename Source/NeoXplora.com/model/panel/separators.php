<?php
  namespace NeoX\Model;
  
  require_once APP_DIR . "/app/system/Model.php";
  class TPanelSeparators extends \SkyCore\TModel {
    
    public function countSeparators() {
      $query = "
        SELECT
          COUNT(sp.[[separator.id]]) AS `total`
        FROM [[separator]] sp";
      
      $result = $this->query($query);
      
      return $this->result($result);
    }
    
    public function getSeparators($offset, $limit) {
      $query = "
        SELECT DISTINCT
          sp.[[separator.id]], 
          sp.[[separator.value]] 
        FROM [[separator]] sp";
      
      $query .= " ORDER BY TRIM(sp.[[separator.value]]) ASC ";
      $query .= " LIMIT :1, :2 ";
      
      $result = $this->query($query, $offset, $limit);
      
      return $this->result($result);
    }
    
    public function addSeparator($separator) {
      $query = "
        INSERT INTO [[separator]] ([[separator.value]]) VALUES (:1)";
            
      $result = $this->query($query, $separator);
      
      return $this->result($result);
    }
    
    public function deleteSeparator($id) {
      $query = "
        DELETE FROM [[separator]] WHERE [[separator.id]] = :1";
            
      $result = $this->query($query, $id);
      
      return $this->result($result);
    }
    
  }
?>