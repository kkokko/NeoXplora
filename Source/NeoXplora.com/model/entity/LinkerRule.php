<?php
  namespace NeoX\Entity;

  require_once APP_DIR . "/app/system/Entity.php";
 
	class TLinkerRule extends \SkyCore\TEntity {
    
    public static $entityname = "linkerrule";
    
    //Table Name in the DB
    public static $tablename = "neox_linkerrule";
    
    //Table Fields in the DB
    public static $tok_id = "Id";
    public static $tok_name = "Name";
    public static $tok_type = "Type";
	  public static $tok_value = "Value";
	  public static $tok_conditions = "Conditions";
	  public static $tok_order = "Order";
	  
	
	public function myGetLinkerRules(){
		
		$query = $this->core->entity("linkerrule")->select(null, "*", array("order" => "ASC"));
 
		
    //print_r($query);exit;
    if(is_array($query) && isset($query['Id']))
    {
      
      $arrResult[] = $query;
      return $arrResult;
    }
    if(isset($query) && $query != '' )
    {
      //  $result = $this->fullresult($query);
        
      while($resultLinkerRule = $query->fetch_assoc()) 
      {
        $arrResult[] = $resultLinkerRule;
      }
      return $arrResult;
    }
    else
      {return false;}
		
	}
  
  public function getLinkerRuleForId($linkerRuleId){
    
    $query = $this->core->entity("linkerrule")->select($linkerRuleId, "*");
    
    return $query;
  }
  
  public function getMaxId(){
    
    $query = $this->core->entity("linkerrule")->select(null, "*", array("order" => "ASC"));
    
    if(is_array($query) && isset($query['Id']))
    {
      
      $result[] = $query;
      
      return $result;
    }
    if(isset($query) && $query != '' )
    {
      $result = $query->fetch_array();
      
      return $result;
    }
    else 
    {
      $result['Id'] = 1;
      return $result;
    }
  }
    
	}
?>