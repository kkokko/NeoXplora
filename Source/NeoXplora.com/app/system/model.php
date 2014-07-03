<?php
  require_once APP_DIR . "/app/system/appentity.php";
  class Model extends TAppEntity {
    
    public function query() {
      //get first argument, the query string
      $query = func_get_arg(0);
      $fields = array();
      $search = array();
      $first_replace = array();
      $second_search = array();
      $replace = array();
      $k = 0;
      
      //get all the fields wrapped in [[ ]]
      preg_match_all("^\[\[(.*?)\]\]^", $query, $fields);
      
      if(count($fields > 1)) {
        //we want the field names without the wrapper
        $fields = $fields[1];
      } else {
        $fields = array();
      }
      
      foreach($fields as $field) {
        $fieldData = explode(".", $field);
        if(count($fieldData) == 0) { 
          continue;
        }
        
        $fullFieldName = "`" . $field . "`";
        //retrieve the model object
        $model = $this->core->model($fieldData[0]);
        
        if($model) {
          $modelName = $model::$tablename;
          
          //get the real db field name
          if(count($fieldData) == 2) {
            $fieldToken = "tok_" . $fieldData[1];
            $fieldName = $model::$$fieldToken;
            $fullFieldName = "`" . $fieldName . "`";
          } elseif (count($fieldData) == 1) { //or get the table name for the model
            $fullFieldName = "`" . $modelName . "`"; 
          }
        }
        
        //replace the field token with the actual db name
        $search[] = "^\[\[" . preg_quote($field) . "\]\]^";
        $first_replace[] = "&&**%%@#" . ($k) . "#@%%**&&";
        $second_search[] = "/" . preg_quote($first_replace[$k]) . "/";  
        $replace[] = $fullFieldName;
        $k++;
      }
      
      //parse all the remaining parameters and replace the token with the value provided
      for($i = 1; $i < func_num_args(); $i++) {
        $param = func_get_arg($i);
        if(!is_int($param)) {
          $param = "'" . $this->db->escape_string($param) . "'";
        }
        $search[] = "^\:" . $i . "^";
        $first_replace[$k] = "&&**%%@#" . ($k) . "#@%%**&&";
        $second_search[] = "/" . preg_quote($first_replace[$k]) . "/"; 
        $replace[] = $param;
        $k++;
      }
      
      //Doing two replaces to avoid recursiveness
      $query = preg_replace($search, $first_replace, $query);
      $query = preg_replace($second_search, $replace, $query);
      
      $result = $this->db->query($query) or die($this->db->error);
      return $result;
    }

    public function result($query) {
      if($query->num_rows) {
        return $query->fetch_array();
      } else {
        return false;
      }
    }
    
    public function check($query) {
      if($query) {
        return true;
      } else {
        return false;
      }
    }
    
    public function bindResult($result, $pairs) {
      $bindedResults = array();
      foreach($result as $key => $value) {
        if(array_key_exists($key, $pairs)) {
          $bindedResults[$pairs[$key]] = $value; 
        }
      }
    }
    
  }
  
?>
    