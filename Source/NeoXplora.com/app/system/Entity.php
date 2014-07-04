<?php
  namespace SkyCore;
  
  require_once APP_DIR . "/app/system/Model.php";
  class TEntity extends TModel {
    
    public function select($ids, $limit = null) {
      if(!is_array($ids)) {
        return $this->selectSingle($ids);
      } else {
        return $this->selectMultiple($ids, $limit);
      }
    }
    
    public function update($ids, $data) {
      if(!is_array($data) || count($data) == 0) return false;
      
      if(!is_array($ids)) {
        return $this->updateSingle($ids, $data);
      } else {
        return $this->updateMultiple($ids, $data);
      }
      
    }
    
    public function delete($ids, $except = null) {
      if(!is_array($ids)) {
        return $this->deleteSingle($ids);
      } else {
        return $this->deleteMultiple($ids, $except);
      }
    }
    
    public function count() {
      $query = $this->query("
        SELECT 
          COUNT(t.[[" . $this::$entityname . ".id]]) AS total
        FROM [[" . $this::$entityname . "]] t
      ");
      $result = $query->fetch_array();
      
      return $result['total'];
    }
    
    //
    
    private function selectSingle($id) {
      $query = $this->query("SELECT * FROM [[" . $this::$entityname . "]] WHERE [[" . $this::$entityname . ".id]] = :1", $id);
      
      return $this->result($query);
    }
    
    private function selectMultiple($ids, $limit) {
      $condition_limit = "";
      $condition = "";
      $condition = " [[" . $this::$entityname . ".id]] IN (";
      for($i = 0; $i < count($ids); $i++) {
        $condition .= $ids[$i];
        if($i + 1 != count($ids)) $condition .= ", ";
      }
      $condition .= ")";
      
      if(is_int($limit)) {
        $conditon_limit = " LIMIT " . $limit;
      }
      
      $query = $this->query("SELECT * FROM [[" . $this::$entityname . "]] WHERE " . $condition . $limit);
      
      return $this->result($query);
    }
    
    private function updateSingle($id, $data) {
      $updates = array();
      foreach($data AS $key => $value) {
        $updates[] = $this->prepareQueryString(" [[" . $this::$entityname . "." . $key . "]] = :1", $value);
      }
      $updates = implode(",", $updates);

      $query = $this->prepareQueryString("UPDATE [[" . $this::$entityname . "]] SET ") . $updates . $this->prepareQueryString(" WHERE [[" . $this::$entityname . ".id]] = :1", $id);
      $result = $this->db->query($query);
      
      return $this->check($result);
    }
    
    private function updateMultiple($ids, $data) {
      $condition = "";
      $condition = " [[" . $this::$entityname . ".id]] IN (";
      for($i = 0; $i < count($ids); $i++) {
        $condition .= $ids[$i];
        if($i + 1 != count($ids)) $condition .= ", ";
      }
      $condition .= ")";
      
      $updates = array();
      foreach($data AS $key => $value) {
        $updates[] = $this->prepareQueryString(" [[" . $this::$entityname . "." . $key . "]] = :1", $value);
      }
      $updates = implode(",", $updates);

      $query = $this->prepareQueryString("UPDATE [[" . $this::$entityname . "]] SET ") . $updates . $this->prepareQueryString(" WHERE " . $condition, $id);
      $result = $this->db->query($query);
      
      return $this->check($result);
    }
    
    private function deleteSingle($id) {
      $query = $this->query("DELETE FROM [[" . $this::$entityname . "]] WHERE [[" . $this::$entityname . ".id]] = :1", $id);
      
      return $this->check($query);
    }
    
    private function deleteMultiple($ids, $except) {
      $condition_except = "";
      $condition_ids = " [[" . $this::$entityname . ".id]] IN (";
      for($i = 0; $i < count($ids); $i++) {
        $condition_ids .= $ids[$i];
        if($i + 1 != count($ids)) $condition_ids .= ", ";
      }
      $condition_ids .= ")";
      
      if(is_array($except) && count($except) > 0) {
        $condition_except = " AND [[" . $this::$entityname . ".id]] NOT IN (";
        for($i = 0; $i < count($except); $i++) {
          $condition_except .= $except[$i];
          if($i + 1 != count($except)) $condition_except .= ", ";
        }
        $condition_except .= ")";
      }
      
      $query = $this->query("DELETE FROM [[" . $this::$entityname . "]] WHERE " . $condition_ids . $condition_except);
      
      return $this->check($query);
    }
    
  }
?>
    