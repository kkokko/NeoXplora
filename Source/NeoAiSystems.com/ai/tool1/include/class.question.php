<?php
/*
	This SQL query will create the table to store your object.

	CREATE TABLE `question` (
	`questionid` int(11) NOT NULL auto_increment,
	`statement` VARCHAR(255) NOT NULL,
	`story_id` INT NULL,
	`storyid` int(11) NOT NULL, INDEX(`storyid`), PRIMARY KEY  (`questionid`)) ENGINE=MyISAM;
*/

/**
* <b>Question</b> class with integrated CRUD methods.
* @author Php Object Generator
* @version POG 3.0f / PHP5
* @copyright Free for personal & commercial use. (Offered under the BSD license)
* @link http://www.phpobjectgenerator.com/?language=php5&wrapper=pog&objectName=Question&attributeList=array+%28%0A++0+%3D%3E+%27statement%27%2C%0A++1+%3D%3E+%27story_id%27%2C%0A++2+%3D%3E+%27story%27%2C%0A%29&typeList=array+%28%0A++0+%3D%3E+%27VARCHAR%28255%29%27%2C%0A++1+%3D%3E+%27INT%27%2C%0A++2+%3D%3E+%27BELONGSTO%27%2C%0A%29
*/
include_once('class.pog_base.php');
class Question extends POG_Base
{
	public $questionId = '';

	/**
	 * @var VARCHAR(255)
	 */
	public $statement;
	
	/**
	 * @var INT
	 */
	public $story_id;
	
	/**
	 * @var INT(11)
	 */
	public $storyId;
	
	public $pog_attribute_type = array(
		"questionId" => array('db_attributes' => array("NUMERIC", "INT")),
		"statement" => array('db_attributes' => array("TEXT", "VARCHAR", "255")),
		"story_id" => array('db_attributes' => array("NUMERIC", "INT")),
		"story" => array('db_attributes' => array("OBJECT", "BELONGSTO")),
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
	
	function Question($statement='', $story_id='')
	{
		$this->statement = $statement;
		$this->story_id = $story_id;
	}
	
	
	/**
	* Gets object from database
	* @param integer $questionId 
	* @return object $Question
	*/
	function Get($questionId)
	{
		$connection = Database::Connect();
		$this->pog_query = "select * from `question` where `questionid`='".intval($questionId)."' LIMIT 1";
		$cursor = Database::Reader($this->pog_query, $connection);
		while ($row = Database::Read($cursor))
		{
			$this->questionId = $row['questionid'];
			$this->statement = $this->Unescape($row['statement']);
			$this->story_id = $this->Unescape($row['story_id']);
			$this->storyId = $row['storyid'];
		}
		return $this;
	}
	
	
	/**
	* Returns a sorted array of objects that match given conditions
	* @param multidimensional array {("field", "comparator", "value"), ("field", "comparator", "value"), ...} 
	* @param string $sortBy 
	* @param boolean $ascending 
	* @param int limit 
	* @return array $questionList
	*/
	function GetList($fcv_array = array(), $sortBy='', $ascending=true, $limit='')
	{
		$connection = Database::Connect();
		$sqlLimit = ($limit != '' ? "LIMIT $limit" : '');
		$this->pog_query = "select * from `question` ";
		$questionList = Array();
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
			$sortBy = "questionid";
		}
		$this->pog_query .= " order by ".$sortBy." ".($ascending ? "asc" : "desc")." $sqlLimit";
		$thisObjectName = get_class($this);
		$cursor = Database::Reader($this->pog_query, $connection);
		while ($row = Database::Read($cursor))
		{
			$question = new $thisObjectName();
			$question->questionId = $row['questionid'];
			$question->statement = $this->Unescape($row['statement']);
			$question->story_id = $this->Unescape($row['story_id']);
			$question->storyId = $row['storyid'];
			$questionList[] = $question;
		}
		return $questionList;
	}
	
	
	/**
	* Saves the object to the database
	* @return integer $questionId
	*/
	function Save()
	{
		$connection = Database::Connect();
		$this->pog_query = "select `questionid` from `question` where `questionid`='".$this->questionId."' LIMIT 1";
		$rows = Database::Query($this->pog_query, $connection);
		if ($rows > 0)
		{
			$this->pog_query = "update `question` set 
			`statement`='".$this->Escape($this->statement)."', 
			`story_id`='".$this->Escape($this->story_id)."', 
			`storyid`='".$this->storyId."' where `questionid`='".$this->questionId."'";
		}
		else
		{
			$this->pog_query = "insert into `question` (`statement`, `story_id`, `storyid` ) values (
			'".$this->Escape($this->statement)."', 
			'".$this->Escape($this->story_id)."', 
			'".$this->storyId."' )";
		}
		$insertId = Database::InsertOrUpdate($this->pog_query, $connection);
		if ($this->questionId == "")
		{
			$this->questionId = $insertId;
		}
		return $this->questionId;
	}
	
	
	/**
	* Clones the object and saves it to the database
	* @return integer $questionId
	*/
	function SaveNew()
	{
		$this->questionId = '';
		return $this->Save();
	}
	
	
	/**
	* Deletes the object from the database
	* @return boolean
	*/
	function Delete()
	{
		$connection = Database::Connect();
		$this->pog_query = "delete from `question` where `questionid`='".$this->questionId."'";
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
			$pog_query = "delete from `question` where ";
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
	* Associates the story object to this one
	* @return boolean
	*/
	function GetStory()
	{
		$story = new story();
		return $story->Get($this->storyId);
	}
	
	
	/**
	* Associates the story object to this one
	* @return 
	*/
	function SetStory(&$story)
	{
		$this->storyId = $story->storyId;
	}
}
?>