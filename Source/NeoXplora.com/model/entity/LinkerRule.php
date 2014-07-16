<?php
  namespace NeoX\Entity;

  require_once APP_DIR . "/app/system/Entity.php";
 
	class TLinkerRule extends \SkyCore\TEntity {
    
    public static $entityname = "linkerrule";
    
    //Table Name in the DB
    public static $tablename = "neox_creprule";
    
    //Table Fields in the DB
    public static $tok_id = "Id";
    public static $tok_name = "Name";
    public static $tok_type = "RuleType";
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
    
    if(is_array($query) && isset($query['Id']))
    {
      $result = $query;
    }
    else if(isset($query) && $query != '' )
    {
      $result = $query->fetch_assoc();  
    }
    return $result;
  }
  
  public function getMaxId(){
    
    $query = $this->core->entity("linkerrule")->select(null, "*", array("order" => "ASC"));
    
    if(is_array($query) && isset($query['Id']))
    {
      
      $result = $query;
      
      return $result;
    }
    else if(isset($query) && $query != '' )
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