<?php
/*
	This SQL query will create the table to store your object.

	CREATE TABLE `sentenceTBL` (
	`sentenceTBLid` int(11) NOT NULL auto_increment,
	`sentence` VARCHAR(255) NOT NULL,
	`storyID` int(11) NOT NULL, INDEX(`storyID`), PRIMARY KEY  (`storyID`)) ENGINE=MyISAM;
*/

/**
* <b>StoryLine</b> class with integrated CRUD methods.
* @author Php Object Generator
* @version POG 3.0f / PHP5
* @copyright Free for personal & commercial use. (Offered under the BSD license)
* @link http://www.phpobjectgenerator.com/?language=php5&wrapper=pog&objectName=StoryLine&attributeList=array+%28%0A++0+%3D%3E+%27sentence%27%2C%0A++1+%3D%3E+%27Story%27%2C%0A%29&typeList=array+%28%0A++0+%3D%3E+%27VARCHAR%28255%29%27%2C%0A++1+%3D%3E+%27BELONGSTO%27%2C%0A%29
*/
include_once('class.pog_base.php');
class StoryLine extends POG_Base
{
	public $storylineId = '';

	/**
	 * @var VARCHAR(255)
	 */
	public $sentence;
	
	/**
	 * @var INT(11)
	 */
	public $storyId;
	
	public $pog_attribute_type = array(
		"storylineId" => array('db_attributes' => array("NUMERIC", "INT")),
		"sentence" => array('db_attributes' => array("TEXT", "VARCHAR", "255")),
		"Story" => array('db_attributes' => array("OBJECT", "BELONGSTO")),
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
	
	function StoryLine($sentence='')
	{
		$this->sentence = $sentence;
	}
	
	
	/**
	* Gets object from database
	* @param integer $storylineId 
	* @return object $StoryLine
	*/
	function Get($storylineId)
	{
		$connection = Database::Connect();
		$this->pog_query = "select * from `sentenceTBL` where `sentenceID`='".intval($storylineId)."' LIMIT 1";
		$cursor = Database::Reader($this->pog_query, $connection);
		while ($row = Database::Read($cursor))
		{
			$this->storylineId = $row['sentenceID'];
			$this->sentence = $this->Unescape($row['sentence']);
			$this->storyId = $row['storyID'];
		}
		return $this;
	}

	function GetBody($storylinebody)
	{
		$connection = Database::Connect();
		$this->pog_query = "select * from `sentenceTBL` where ".$storylinebody;
		//echo $this->pog_query;
		$thisObjectName = get_class($this);
		$storylineList = Array();
		$cursor = Database::Reader($this->pog_query, $connection);
		while ($row = Database::Read($cursor))
		{
			$storyline = new $thisObjectName();
			$storyline->storylineId = $row['storyID'];
			$storyline->sentence = $this->Unescape($row['sentence']);
			$storyline->storyId = $row['storyID'];
			//echo "ehsan";
			$storylineList[] = $storyline;
			//echo var_dump($storylineList);
			//echo '<br />';
		}
		//echo '<br />';echo '<br />';echo var_dump($storylineList);
		//	echo '<br />';echo '<br />';
		return $storylineList;
	}
	
	
	/**
	* Returns a sorted array of objects that match given conditions
	* @param multidimensional array {("field", "comparator", "value"), ("field", "comparator", "value"), ...} 
	* @param string $sortBy 
	* @param boolean $ascending 
	* @param int limit 
	* @return array $storylineList
	*/
	function GetList($fcv_array = array(), $sortBy='', $ascending=true, $limit='')
	{
		$connection = Database::Connect();
		$sqlLimit = ($limit != '' ? "LIMIT $limit" : '');
		$this->pog_query = "select * from `sentenceTBL` ";
		$storylineList = Array();
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
			$sortBy = "storyID";
		}
		$this->pog_query .= " order by ".$sortBy." ".($ascending ? "asc" : "desc")." $sqlLimit";
		$thisObjectName = get_class($this);
		$cursor = Database::Reader($this->pog_query, $connection);
		while ($row = Database::Read($cursor))
		{
			$storyline = new $thisObjectName();
			$storyline->storylineId = $row['sentenceID'];
			$storyline->sentence = $this->Unescape($row['sentence']);
			$storyline->representation = $this->Unescape($row['representation']);
			$storyline->context_rep = $this->Unescape($row['context_rep']);
			$storyline->storyId = $row['storyID'];
			$storylineList[] = $storyline;
		}
		return $storylineList;
	}
	
	
	/**
	* Saves the object to the database
	* @return integer $storylineId
	*/
	function Save()
	{
		$connection = Database::Connect();
		$this->pog_query = "select `sentenceID` from `sentenceTBL` where `sentenceID`='".$this->storylineId."' LIMIT 1";
		$rows = Database::Query($this->pog_query, $connection);
		if ($rows > 0)
		{
			$this->pog_query = "update `sentenceTBL` set 
			`sentence`='".$this->Escape($this->sentence)."', 
			`storyID`='".$this->storyId."' where `sentenceID`='".$this->storylineId."'";
		}
		else
		{
			$this->pog_query = "insert into `sentenceTBL` (`sentence`, `storyID` ) values (
			'".$this->Escape($this->sentence)."', 
			'".$this->storyId."' )";
		}
		$insertId = Database::InsertOrUpdate($this->pog_query, $connection);
		
		if ($this->storylineId == "")
		{
			$this->storylineId = $insertId;
		}
		//echo $this->storylineId;
		return $this->storylineId;
	}
	
	
	/**
	* Clones the object and saves it to the database
	* @return integer $storylineId
	*/
	function SaveNew()
	{
		$this->storylineId = '';
		return $this->Save();
	}
	
	
	/**
	* Deletes the object from the database
	* @return boolean
	*/
	function Delete()
	{
		$connection = Database::Connect();
		$this->pog_query = "delete from `sentenceTBL` where `storyID`='".$this->storylineId."'";
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
			$pog_query = "delete from `sentenceTBL` where ";
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
	* Associates the Story object to this one
	* @return boolean
	*/
	function GetStory()
	{
		$story = new Story();
		return $story->Get($this->storyId);
	}
	
	
	/**
	* Associates the Story object to this one
	* @return 
	*/
	function SetStory(&$story)
	{
		$this->storyId = $story->storyId;
	}
}
?>