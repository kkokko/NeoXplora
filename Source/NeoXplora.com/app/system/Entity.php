<?php
  namespace SkyCore;
  
  require_once APP_DIR . "/app/system/Model.php";
  class TEntity extends TModel {
    
    /*
     *  INTERFACE 
     */
    
    /* @param $condition - Can be an id, or an array with key value pairs, where the key is the name of the field 
                           and the value is an array of values for the field.
     * @param $data - Can be * (select everything), the name of a field or an array of field names
     * @param $orderby - Array of key value pairs where the key is the field name and the value either ASC or DESC
     * @param $limit - Integer representing the maximum number of results to return
     * 
     * @return - Array with fetched values if $condition is an id returns the fetched array row
     *         - Selected field value if $condition is an id and $data is a field name
     *         - MySQLi Query resource if $condition or $data is an array 
    */                    
    public function select($condition = null, $data = "*", $orderby = null, $limit = null) {
      if(!is_array($condition)) {
        return $this->selectSingle($condition, $data, $orderby);
      } else {
        return $this->selectMultiple($condition, $data, $orderby, $limit);
      }
    }
    
    /* @param $condition - Can be an id, or an array with key value pairs, where the key is the name of the field 
                           and the value is an array of values for the field.
     * @param $data - Array with key value pairs, where the key is the name of the field and the value is the value 
     *                that the field will be updated to
     * 
     * @return boolean - true for success, false otherwise
     */
    public function update($condition = null, $data) {
      if(!is_array($data) || count($data) == 0) return false;
      
      if(!is_array($condition)) {
        return $this->updateSingle($condition, $data);
      } else {
        return $this->updateMultiple($condition, $data);
      }
    }
    
    /* @param $ids - Can be an id, or an array of ids
     * @param $except - Array of ids to not delete
     * 
     * @return boolean - true for success, false otherwise
     */
    public function delete($condition = null, $except = null) {
      if(!is_array($condition)) {
        return $this->deleteSingle($condition);
      } else {
        return $this->deleteMultiple($condition, $except);
      }
    }
    
    /* @param $fields - Array with field list
     * @param $values - Array with values for the above field list (must be in the same order). 
     *                  Can be two-dimensional array for inserting multiple rows simultaniously
     * 
     * @return boolean - true for success, false otherwise
     */
    public function insert($fields, $values) {
      if(!is_array($fields) || !is_array($values)) return false;
      
      return $this->insertSingle($fields, $values);
    }
    
    /* @return int - number of records in the database for the entity
     */
    public function count() {
      $query = $this->query("
        SELECT 
          COUNT(t.[[" . $this::getEntityName() . ".id]]) AS total
        FROM [[" . $this::getEntityName() . "]] t
      ");
      $result = $query->fetch_array();
      
      return $result['total'];
    }
    
    
    /*
     *  IMPLEMENTATION 
     */
    
    private function insertSingle($fields, $values) {
      $fieldlist = " (";
      $valuelist = "";
      
      for($i = 0; $i < count($fields); $i++) {
        $fieldlist .= $this->prepareQueryString("[[" . $this::getEntityName() . "." . $fields[$i] . "]]");
        if($i + 1 != count($fields)) $fieldlist .= ", ";
      }
      $fieldlist .= ")";
      
      for($i = 0; $i < count($values); $i++) {
        $valuelist .= "(";
        for($j = 0; $j < count($values[$i]); $j++) {
          $valuelist .= $this->prepareQueryString(":1", $values[$i][$j]);
          if($j + 1 != count($fields)) $valuelist .= ", ";
        }
        $valuelist .= ")";
        if($i + 1 != count($values)) $valuelist .= ", ";
      }
      
      $query = $this->prepareQueryString("INSERT INTO [[" . $this::getEntityName() . "]]") . $fieldlist . " VALUES " . $valuelist;
      $result = $this->db->query($query);
      
      return $this->result($result);
    }
    
    private function selectSingle($id, $data, $orderby) {
      $fields = "";
      if(!is_array($data)) {
        if($data == "*") {
          $fields = $data;
        } else {
          $fields = "[[" . $this::getEntityName() . "." . $data . "]]";
        }
      } else {
        for($i = 0; $i < count($data); $i++) {
          $fields .= "[[" . $this::getEntityName() . "." . $data[$i] . "]]";
          if($i + 1 != count($data)) $fields .= ", ";
        }
      }
      
      $order = "";
      if(is_array($orderby) && count($orderby)) {
        $order = "ORDER BY";
        $i = 0;
        foreach($orderby AS $key => $value) {
          $order .= " [[" . $this::getEntityName() . "." . $key . "]] " . $value;
          if($i + 1 != count($orderby)) $order .= ", ";
          $i++;
        }
      }
      
      $condition = "";
      if($id) {
        $condition = " WHERE [[" . $this::getEntityName() . ".id]] = :1 ";
      }
      
      $query = $this->prepareQueryString("SELECT " . $fields . " FROM [[" . $this::getEntityName() . "]] " . $condition . $order, $id);
      $query = $this->db->query($query);
      
      return $this->result($query);
    }
    
    private function selectMultiple($conditions, $data, $orderby, $limit) {
      $condition_limit = "";
      $fields = "";
      $condition = "";
      
      $k = 0;
      foreach($conditions AS $key => $value) {
        $condition = $this->prepareQueryString(" [[" . $this::getEntityName() . "." . $key . "]] IN (");
        for($i = 0; $i < count($value); $i++) {
          $condition .= $this->prepareQueryString(":1", $value[$i]);
          if($i + 1 != count($value)) $condition .= ", ";
        }
        $condition .= ")";
        if($k + 1 != count($conditions)) $condition .= " AND ";
        $k++;
      }
      
      if(!is_array($data)) {
        if($data == "*") {
          $fields = $data;
        } else {
          $fields = "[[" . $this::getEntityName() . "." . $data . "]]";
        }
      } else {
        for($i = 0; $i < count($data); $i++) {
          $fields .= "[[" . $this::getEntityName() . "." . $data[$i] . "]]";
          if($i + 1 != count($data)) $fields .= ", ";
        }
      }
      
      $order = "";
      if(is_array($orderby) && count($orderby)) {
        $order = "ORDER BY";
        $i = 0;
        foreach($orderby AS $key => $value) {
          $order .= " [[" . $this::getEntityName() . "." . $key . "]] " . $value;
          if($i + 1 != count($orderby)) $order .= ", ";
          $i++;
        }
      }
      
      if(is_int($limit)) {
        $condition_limit = " LIMIT " . $limit;
      }
      
      $query = $this->prepareQueryString("SELECT " . $fields . " FROM [[" . $this::getEntityName() . "]] WHERE ") . $condition . $this->prepareQueryString($order . $condition_limit);
      $query = $this->db->query($query) or die($this->db->error);
      
      return $this->result($query);
    }
    
    private function updateSingle($id, $data) {
      $updates = array();
      foreach($data AS $key => $value) {
        $updates[] = $this->prepareQueryString(" [[" . $this::getEntityName() . "." . $key . "]] = :1", $value);
      }
      $updates = implode(",", $updates);

      $condition = "";
      if($id) {
        $condition = $this->prepareQueryString(" WHERE [[" . $this::getEntityName() . ".id]] = :1", $id);
      }

      $query = $this->prepareQueryString("UPDATE [[" . $this::getEntityName() . "]] SET ") . $updates . $condition;

      $result = $this->db->query($query);
      
      return $this->result($result);
    }
    
    private function updateMultiple($conditions, $data) {
      $condition = "";
      
      $k = 0;
      foreach($conditions AS $key => $value) {
        $condition = $this->prepareQueryString(" [[" . $this::getEntityName() . "." . $key . "]] IN (");
        for($i = 0; $i < count($value); $i++) {
          $condition .= $this->prepareQueryString(":1", $value[$i]);
          if($i + 1 != count($value)) $condition .= ", ";
        }
        $condition .= ")";
        if($k + 1 != count($conditions)) $condition .= " AND ";
        $k++;
      }
      
      $updates = array();
      foreach($data AS $key => $value) {
        $updates[] = $this->prepareQueryString(" [[" . $this::getEntityName() . "." . $key . "]] = :1", $value);
      }
      $updates = implode(",", $updates);

      $query = $this->prepareQueryString("UPDATE [[" . $this::getEntityName() . "]] SET ") . $updates . " WHERE " . $condition;
      $result = $this->db->query($query);
      
      return $this->result($result);
    }
    
    private function deleteSingle($id) {
      if(is_int($id)) {
        $query = $this->query("DELETE FROM [[" . $this::getEntityName() . "]] WHERE [[" . $this::getEntityName() . ".id]] = :1", $id);
      } else {
        $query = $this->query("DELETE FROM [[" . $this::getEntityName() . "]]");
      }
      
      return $this->result($query);
    }
    
    private function deleteMultiple($conditions, $except) {
      $condition = "";
      $condition_except = "";
      
      $k = 0;
      foreach($conditions AS $key => $value) {
        $condition = $this->prepareQueryString(" [[" . $this::getEntityName() . "." . $key . "]] IN (");
        for($i = 0; $i < count($value); $i++) {
          $condition .= $this->prepareQueryString(":1", $value[$i]);
          if($i + 1 != count($value)) $condition .= ", ";
        }
        $condition .= ")";
        if($k + 1 != count($conditions)) $condition .= " AND ";
        $k++;
      }
      
      if(is_array($except) && count($except) > 0) {
        $k = 0;
        $condition_except .= " AND"; 
        foreach($except AS $key => $value) {
          $condition_except .= $this->prepareQueryString(" [[" . $this::getEntityName() . "." . $key . "]] NOT IN (");
          for($i = 0; $i < count($value); $i++) {
            $condition_except .= $this->prepareQueryString(":1", $value[$i]);
            if($i + 1 != count($value)) $condition_except .= ", ";
          }
          $condition_except .= ")";
          if($k + 1 != count($except)) $condition_except .= " AND ";
          $k++;
        }
      }
      
      $query = $this->prepareQueryString("DELETE FROM [[" . $this::getEntityName() . "]] WHERE ") . $condition . $condition_except;
      $result = $this->db->query($query);
      
      return $this->result($result);
    }
    
    protected function getEntityName() {
      $class = explode('\\', get_class($this));
      $className = $class[count($class) - 1];
      return substr($className, 1);
    }
    
  }
?>
    