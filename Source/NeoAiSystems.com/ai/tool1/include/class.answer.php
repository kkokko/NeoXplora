<?php
/*
	This SQL query will create the table to store your object.

	CREATE TABLE `answer` (
	`answerid` int(11) NOT NULL auto_increment,
	`statement` VARCHAR(255) NOT NULL,
	`questionid` int(11) NOT NULL,
	`is_correct` INT NOT NULL, INDEX(`questionid`), PRIMARY KEY  (`answerid`)) ENGINE=MyISAM;
*/

/**
* <b>Answer</b> class with integrated CRUD methods.
* @author Php Object Generator
* @version POG 3.0f / PHP5
* @copyright Free for personal & commercial use. (Offered under the BSD license)
* @link http://www.phpobjectgenerator.com/?language=php5&wrapper=pog&objectName=Answer&attributeList=array+%28%0A++0+%3D%3E+%27statement%27%2C%0A++1+%3D%3E+%27question%27%2C%0A++2+%3D%3E+%27is_correct%27%2C%0A%29&typeList=array+%28%0A++0+%3D%3E+%27VARCHAR%28255%29%27%2C%0A++1+%3D%3E+%27BELONGSTO%27%2C%0A++2+%3D%3E+%27INT%27%2C%0A%29
*/
include_once('class.pog_base.php');
class Answer extends POG_Base
{
	public $answerId = '';

	/**
	 * @var VARCHAR(255)
	 */
	public $statement;
	
	/**
	 * @var INT(11)
	 */
	public $questionId;
	
	/**
	 * @var INT
	 */
	public $is_correct;
	
	public $pog_attribute_type = array(
		"answerId" => array('db_attributes' => array("NUMERIC", "INT")),
		"statement" => array('db_attributes' => array("TEXT", "VARCHAR", "255")),
		"question" => array('db_attributes' => array("OBJECT", "BELONGSTO")),
		"is_correct" => array('db_attributes' => array("NUMERIC", "INT")),
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
	
	function Answer($statement='', $is_correct='')
	{
		$this->statement = $statement;
		$this->is_correct = $is_correct;
	}
	
	
	/**
	* Gets object from database
	* @param integer $answerId 
	* @return object $Answer
	*/
	function Get($answerId)
	{
		$connection = Database::Connect();
		$this->pog_query = "select * from `answer` where `answerid`='".intval($answerId)."' LIMIT 1";
		$cursor = Database::Reader($this->pog_query, $connection);
		while ($row = Database::Read($cursor))
		{
			$this->answerId = $row['answerid'];
			$this->statement = $this->Unescape($row['statement']);
			$this->questionId = $row['questionid'];
			$this->is_correct = $this->Unescape($row['is_correct']);
		}
		return $this;
	}
	
	
	/**
	* Returns a sorted array of objects that match given conditions
	* @param multidimensional array {("field", "comparator", "value"), ("field", "comparator", "value"), ...} 
	* @param string $sortBy 
	* @param boolean $ascending 
	* @param int limit 
	* @return array $answerList
	*/
	function GetList($fcv_array = array(), $sortBy='', $ascending=true, $limit='')
	{
		$connection = Database::Connect();
		$sqlLimit = ($limit != '' ? "LIMIT $limit" : '');
		$this->pog_query = "select * from `answer` ";
		$answerList = Array();
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
			$sortBy = "answerid";
		}
		$this->pog_query .= " order by ".$sortBy." ".($ascending ? "asc" : "desc")." $sqlLimit";
		$thisObjectName = get_class($this);
		$cursor = Database::Reader($this->pog_query, $connection);
		while ($row = Database::Read($cursor))
		{
			$answer = new $thisObjectName();
			$answer->answerId = $row['answerid'];
			$answer->statement = $this->Unescape($row['statement']);
			$answer->questionId = $row['questionid'];
			$answer->is_correct = $this->Unescape($row['is_correct']);
			$answerList[] = $answer;
		}
		return $answerList;
	}
	
	
	/**
	* Saves the object to the database
	* @return integer $answerId
	*/
	function Save()
	{
		$connection = Database::Connect();
		$this->pog_query = "select `answerid` from `answer` where `answerid`='".$this->answerId."' LIMIT 1";
		$rows = Database::Query($this->pog_query, $connection);
		if ($rows > 0)
		{
			$this->pog_query = "update `answer` set 
			`statement`='".$this->Escape($this->statement)."', 
			`questionid`='".$this->questionId."', 
			`is_correct`='".$this->Escape($this->is_correct)."' where `answerid`='".$this->answerId."'";
		}
		else
		{
			$this->pog_query = "insert into `answer` (`statement`, `questionid`, `is_correct` ) values (
			'".$this->Escape($this->statement)."', 
			'".$this->questionId."', 
			'".$this->Escape($this->is_correct)."' )";
		}
		$insertId = Database::InsertOrUpdate($this->pog_query, $connection);
		if ($this->answerId == "")
		{
			$this->answerId = $insertId;
		}
		return $this->answerId;
	}
	
	
	/**
	* Clones the object and saves it to the database
	* @return integer $answerId
	*/
	function SaveNew()
	{
		$this->answerId = '';
		return $this->Save();
	}
	
	
	/**
	* Deletes the object from the database
	* @return boolean
	*/
	function Delete()
	{
		$connection = Database::Connect();
		$this->pog_query = "delete from `answer` where `answerid`='".$this->answerId."'";
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
			$pog_query = "delete from `answer` where ";
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
	* Associates the question object to this one
	* @return boolean
	*/
	function GetQuestion()
	{
		$question = new question();
		return $question->Get($this->questionId);
	}
	
	
	/**
	* Associates the question object to this one
	* @return 
	*/
	function SetQuestion(&$question)
	{
		$this->questionId = $question->questionId;
	}
}
?>