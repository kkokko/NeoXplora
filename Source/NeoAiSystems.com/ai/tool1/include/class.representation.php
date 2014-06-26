<?php
/*
	This SQL query will create the table to store your object.

	CREATE TABLE `representation` (
	`representationid` int(11) NOT NULL auto_increment,
	`text` VARCHAR(255) NOT NULL,
	`storylineid` int(11) NOT NULL, INDEX(`storylineid`), PRIMARY KEY  (`representationid`)) ENGINE=MyISAM;
*/

/**
* <b>Representation</b> class with integrated CRUD methods.
* @author Php Object Generator
* @version POG 3.0f / PHP5
* @copyright Free for personal & commercial use. (Offered under the BSD license)
* @link http://www.phpobjectgenerator.com/?language=php5&wrapper=pog&objectName=Representation&attributeList=array+%28%0A++0+%3D%3E+%27text%27%2C%0A++1+%3D%3E+%27StoryLine%27%2C%0A%29&typeList=array+%28%0A++0+%3D%3E+%27VARCHAR%28255%29%27%2C%0A++1+%3D%3E+%27BELONGSTO%27%2C%0A%29
*/
include_once('class.pog_base.php');
class Representation extends POG_Base
{
	public $representationId = '';

	/**
	 * @var VARCHAR(255)
	 */
	public $text;
	
	public $textold;
	
	/**
	 * @var INT(11)
	 */
	public $storylineId;
	
	public $pog_attribute_type = array(
		"representationId" => array('db_attributes' => array("NUMERIC", "INT")),
		"text" => array('db_attributes' => array("TEXT", "VARCHAR", "255")),
		"StoryLine" => array('db_attributes' => array("OBJECT", "BELONGSTO")),
		);
	public $pog_query;
	
	
	/**
	* Getter for some private attributes
	* @return mixed $attribute
	*/
	public function __get($attribute)
	{
		if (isset($this->{"_".$attribute}))
		{
			return $this->{"_".$attribute};
		}
		else
		{
			return false;
		}
	}
	
	function Representation($text='')
	{
		$this->text = $text;
	}
	
	
	/**
	* Gets object from database
	* @param integer $representationId 
	* @return object $Representation
	*/
	function Get($representationId)
	{
		$connection = Database::Connect();
		$this->pog_query = "select * from `sentenceTBL` where `sentenceID`='".intval($representationId)."' LIMIT 1";
		$cursor = Database::Reader($this->pog_query, $connection);
		while ($row = Database::Read($cursor))
		{
			$this->representationId = $row['representationid'];
			 $this->textold = $this->Unescape($row['representation']);
		     $this->text = $this->Unescape($row['context_rep']);
			$this->storylineId = $row['storyID'];
		}
		return $this;
	}
	
	
	/**
	* Returns a sorted array of objects that match given conditions
	* @param multidimensional array {("field", "comparator", "value"), ("field", "comparator", "value"), ...} 
	* @param string $sortBy 
	* @param boolean $ascending 
	* @param int limit 
	* @return array $representationList
	*/
	function GetList($fcv_array = array(), $sortBy='', $ascending=true, $limit='')
	{
		$connection = Database::Connect();
		$sqlLimit = ($limit != '' ? "LIMIT $limit" : '');
		$this->pog_query = "select * from `sentenceTBL` ";
		$representationList = Array();
		if (sizeof($fcv_array) > 0)
		{
			$this->pog_query .= " where ";
			for ($i=0, $c=sizeof($fcv_array); $i<$c; $i++)
			{
				if (sizeof($fcv_array[$i]) == 1)
				{
					$this->pog_query .= " ".$fcv_array[$i][0]." ";
					continue;
				}
				else
				{
					if ($i > 0 && sizeof($fcv_array[$i-1]) != 1)
					{
						$this->pog_query .= " AND ";
					}
					if (isset($this->pog_attribute_type[$fcv_array[$i][0]]['db_attributes']) && $this->pog_attribute_type[$fcv_array[$i][0]]['db_attributes'][0] != 'NUMERIC' && $this->pog_attribute_type[$fcv_array[$i][0]]['db_attributes'][0] != 'SET')
					{
						if ($GLOBALS['configuration']['db_encoding'] == 1)
						{
							$value = POG_Base::IsColumn($fcv_array[$i][2]) ? "BASE64_DECODE(".$fcv_array[$i][2].")" : "'".$fcv_array[$i][2]."'";
							$this->pog_query .= "BASE64_DECODE(`".$fcv_array[$i][0]."`) ".$fcv_array[$i][1]." ".$value;
						}
						else
						{
							$value =  POG_Base::IsColumn($fcv_array[$i][2]) ? $fcv_array[$i][2] : "'".$this->Escape($fcv_array[$i][2])."'";
							$this->pog_query .= "`".$fcv_array[$i][0]."` ".$fcv_array[$i][1]." ".$value;
						}
					}
					else
					{
						$value = POG_Base::IsColumn($fcv_array[$i][2]) ? $fcv_array[$i][2] : "'".$fcv_array[$i][2]."'";
						$this->pog_query .= "`".$fcv_array[$i][0]."` ".$fcv_array[$i][1]." ".$value;
					}
				}
			}
		}
		if ($sortBy != '')
		{
			if (isset($this->pog_attribute_type[$sortBy]['db_attributes']) && $this->pog_attribute_type[$sortBy]['db_attributes'][0] != 'NUMERIC' && $this->pog_attribute_type[$sortBy]['db_attributes'][0] != 'SET')
			{
				if ($GLOBALS['configuration']['db_encoding'] == 1)
				{
					$sortBy = "BASE64_DECODE($sortBy) ";
				}
				else
				{
					$sortBy = "$sortBy ";
				}
			}
			else
			{
				$sortBy = "$sortBy ";
			}
		}
		else
		{
			$sortBy = "representationid";
		}
		$this->pog_query .= " order by ".$sortBy." ".($ascending ? "asc" : "desc")." $sqlLimit";
		$thisObjectName = get_class($this);
		$cursor = Database::Reader($this->pog_query, $connection);
		while ($row = Database::Read($cursor))
		{
			$representation = new $thisObjectName();
			$representation->representationId = $row['sentenceID'];
			$representation->textold = $this->Unescape($row['text']);
			$representation->text = $this->Unescape($row['context_rep']);
			$representation->storylineId = $row['storylineid'];
			$representationList[] = $representation;
		}
		return $representationList;
	}
	
	
	/**
	* Saves the object to the database
	* @return integer $representationId
	*/
	function Save($chageOn,$text)
	{
		$connection = Database::Connect();
		$this->pog_query = "select `sentenceID` from `sentenceTBL` where `sentenceID`='".$this->representationId."' LIMIT 1";
		$rows = Database::Query($this->pog_query, $connection);
		if ($rows > 0)
		{
			/*$this->pog_query = "update `representation` set 
			`text`='".$this->Escape($this->text)."', 
			`storylineid`='".$this->storylineId."' where `representationid`='".$this->representationId."'";*/
			if($chageOn==1){
			$this->pog_query = "update `sentenceTBL` set 
			`context_rep`='".$this->Escape($text)."'  ,
			`storyID`='".$this->storylineId."' where `sentenceID`='".$this->representationId."'";
			}else if($chageOn==2){
			echo  $this->pog_query = "update `sentenceTBL` set 
			 `representation`='".$this->Escape($text)."',
			`storyID`='".$this->storylineId."' where `sentenceID`='".$this->representationId."'";
			}
		}
		else
		{
			/*$this->pog_query = "insert into `representation` (`text`, `storylineid` ) values (
			'".$this->Escape($this->text)."', 
			'".$this->storylineId."' )";*/
		 $this->pog_query = "insert into `sentenceID` (`context_rep`, `storyID` ) values (
			'".$this->Escape($this->text)."', 
			'".$this->storylineId."' )"; 
		}
		$insertId = Database::InsertOrUpdate($this->pog_query, $connection);
		//echo $this->pog_query;
		if ($this->representationId == "")
		{
			$this->representationId = $insertId;
		}
		return $this->representationId;
	}
	
	
	/**
	* Clones the object and saves it to the database
	* @return integer $representationId
	*/
	function SaveNew()
	{
		$this->representationId = '';
		return $this->Save();
	}
	
	
	/**
	* Deletes the object from the database
	* @return boolean
	*/
	function Delete()
	{
		$connection = Database::Connect();
		$this->pog_query = "delete from `representation` where `representationid`='".$this->representationId."'";
		return Database::NonQuery($this->pog_query, $connection);
	}
	
	
	/**
	* Deletes a list of objects that match given conditions
	* @param multidimensional array {("field", "comparator", "value"), ("field", "comparator", "value"), ...} 
	* @param bool $deep 
	* @return 
	*/
	function DeleteList($fcv_array)
	{
		if (sizeof($fcv_array) > 0)
		{
			$connection = Database::Connect();
			$pog_query = "delete from `representation` where ";
			for ($i=0, $c=sizeof($fcv_array); $i<$c; $i++)
			{
				if (sizeof($fcv_array[$i]) == 1)
				{
					$pog_query .= " ".$fcv_array[$i][0]." ";
					continue;
				}
				else
				{
					if ($i > 0 && sizeof($fcv_array[$i-1]) !== 1)
					{
						$pog_query .= " AND ";
					}
					if (isset($this->pog_attribute_type[$fcv_array[$i][0]]['db_attributes']) && $this->pog_attribute_type[$fcv_array[$i][0]]['db_attributes'][0] != 'NUMERIC' && $this->pog_attribute_type[$fcv_array[$i][0]]['db_attributes'][0] != 'SET')
					{
						$pog_query .= "`".$fcv_array[$i][0]."` ".$fcv_array[$i][1]." '".$this->Escape($fcv_array[$i][2])."'";
					}
					else
					{
						$pog_query .= "`".$fcv_array[$i][0]."` ".$fcv_array[$i][1]." '".$fcv_array[$i][2]."'";
					}
				}
			}
			return Database::NonQuery($pog_query, $connection);
		}
	}
	
	
	/**
	* Associates the StoryLine object to this one
	* @return boolean
	*/
	function GetStoryline()
	{
		$storyline = new StoryLine();
		return $storyline->Get($this->storylineId);
	}
	
	
	/**
	* Associates the StoryLine object to this one
	* @return 
	*/
	function SetStoryline(&$storyline)
	{
		$this->storylineId = $storyline->storylineId;
	}
}
?>