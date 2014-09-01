<?php
  namespace NeoX\Model;
  
  require_once APP_DIR . "/app/system/Model.php";
  class TPanelFixRep extends \SkyCore\TModel {
    
    public function fixRep($search, $replace) {
      
      $query = $this->query("
        SELECT * FROM neox_sentence WHERE Rep LIKE \"%" . $search . "%\"
      ");
      
      $result = $this->result($query);
      
      while($s = $result->fetch_array()) {
        $id = $s['Id'];
        echo $id;
        $rep = $s['Rep'];
        
        $rep = mysqli_escape_string(str_replace($search, $replace, $rep), $this->db);
        
        $this->query("
          UPDATE neox_sentence SET Rep = '" . $rep . "' WHERE Id = '" . $id . "'
        ");
      }
      
      return $this->result($query);
    }
  }
?>