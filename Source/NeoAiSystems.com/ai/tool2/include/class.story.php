<?php
/*
	This SQL query will create the table to store your object.

	CREATE TABLE `story` (
	`storyID` int(11) NOT NULL auto_increment,
	`title` VARCHAR(255) NOT NULL,
	`body` VARCHAR(255) NOT NULL,
	`categoryID` int(11) NOT NULL, INDEX(`categoryID`), PRIMARY KEY  (`storyID`)) ENGINE=MyISAM;
*/

/**
* <b>Story</b> class with integrated CRUD methods.
* @author Php Object Generator
* @version POG 3.0f / PHP5
* @copyright Free for personal & commercial use. (Offered under the BSD license)
* @link http://www.phpobjectgenerator.com/?language=php5&wrapper=pog&objectName=Story&attributeList=array+%28%0A++0+%3D%3E+%27title%27%2C%0A++1+%3D%3E+%27body%27%2C%0A++2+%3D%3E+%27Category%27%2C%0A++3+%3D%3E+%27StoryLines%27%2C%0A%29&typeList=array+%28%0A++0+%3D%3E+%27VARCHAR%28255%29%27%2C%0A++1+%3D%3E+%27VARCHAR%28255%29%27%2C%0A++2+%3D%3E+%27BELONGSTO%27%2C%0A++3+%3D%3E+%27HASMANY%27%2C%0A%29
*/
include_once('class.pog_base.php');
class Story extends POG_Base
{
	public $storyId = '';

	/**
	 * @var VARCHAR(255)
	 */
	public $title;
	
	/**
	 * @var VARCHAR(255)
	 */
	public $body;
	
	/**
	 * @var INT(11)
	 */
	public $categoryId;
	
	/**
	 * @var private array of StoryLines objects
	 */
	private $_storylinesList = array();
	
	public $pog_attribute_type = array(
		"storyId" => array('db_attributes' => array("NUMERIC", "INT")),
		"title" => array('db_attributes' => array("TEXT", "VARCHAR", "255")),
		"body" => array('db_attributes' => array("TEXT", "VARCHAR", "255")),
		"Category" => array('db_attributes' => array("OBJECT", "BELONGSTO")),
		"StoryLines" => array('db_attributes' => array("OBJECT", "HASMANY")),
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
	
	function Story($title='', $body='')
	{
		$this->title = $title;
		$this->body = $body;
		$this->_storylinesList = array();
	}
	
	
	/**
	* Gets object from database
	* @param integer $storyId 
	* @return object $Story
	*/
	function Get($storyId)
	{
		$connection = Database::Connect();
		$this->pog_query = "select * from `page` where `storyID`='".intval($storyId)."' LIMIT 1";
		$cursor = Database::Reader($this->pog_query, $connection);
		while ($row = Database::Read($cursor))
		{
			$this->storyId = $row['storyID'];
			$this->title = $this->Unescape($row['title']);
			$this->body = $this->Unescape($row['body']);
			$this->categoryId = $row['categoryID'];
		}
		return $this;
	}
	
	
	/**
	* Returns a sorted array of objects that match given conditions
	* @param multidimensional array {("field", "comparator", "value"), ("field", "comparator", "value"), ...} 
	* @param string $sortBy 
	* @param boolean $ascending 
	* @param int limit 
	* @return array $storyList
	*/
	function GetList($fcv_array = array(), $sortBy='', $ascending=true, $limit='')
	{
		$connection = Database::Connect();
		$sqlLimit = ($limit != '' ? "LIMIT $limit" : '');
		$this->pog_query = "select * from `page` ";
		$storyList = Array();
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
			$story = new $thisObjectName();
			$story->storyId = $row['storyID'];
			$story->title = $this->Unescape($row['title']);
			$story->body = $this->Unescape($row['body']);
			$story->categoryId = $row['categoryID'];
			$storyList[] = $story;
		}
		return $storyList;
	}
	
	
	/**
	* Saves the object to the database
	* @return integer $storyId
	*/
	function Save($deep = true)
	{
		$connection = Database::Connect();
		$this->pog_query = "select `storyID` from `page` where `storyID`='".$this->storyId."' LIMIT 1";
		$rows = Database::Query($this->pog_query, $connection);
		if ($rows > 0)
		{
			$this->pog_query = "update `page` set 
			`title`='".$this->Escape($this->title)."', 
			`body`='".$this->Escape($this->body)."', 
			`categoryID`='".$this->categoryId."'where `storyID`='".$this->storyId."'";
		}
		else
		{
			$this->pog_query = "insert into `page` (`title`, `body`, `categoryID`) values (
			'".$this->Escape($this->title)."', 
			'".$this->Escape($this->body)."', 
			'".$this->categoryId."')";
		}
		$insertId = Database::InsertOrUpdate($this->pog_query, $connection);
		if ($this->storyId == "")
		{
			$this->storyId = $insertId;
		}
		if ($deep)
		{
			foreach ($this->_storylinesList as $storylines)
			{
				$storylines->storyId = $this->storyId;
				$storylines->Save($deep);
			}
		}
		return $this->storyId;
	}
	
	
	/**
	* Clones the object and saves it to the database
	* @return integer $storyId
	*/
	function SaveNew($deep = false)
	{
		$this->storyId = '';
		return $this->Save($deep);
	}
	
	
	/**
	* Deletes the object from the database
	* @return boolean
	*/
	function Delete($deep = false, $across = false)
	{
		if ($deep)
		{
			$storylinesList = $this->GetStorylinesList();
			foreach ($storylinesList as $storylines)
			{
				$storylines->Delete($deep, $across);
			}
		}
		$connection = Database::Connect();
		$this->pog_query = "delete from `page` where `storyID`='".$this->storyId."'";
		return Database::NonQuery($this->pog_query, $connection);
	}
	
	
	/**
	* Deletes a list of objects that match given conditions
	* @param multidimensional array {("field", "comparator", "value"), ("field", "comparator", "value"), ...} 
	* @param bool $deep 
	* @return 
	*/
	function DeleteList($fcv_array, $deep = false, $across = false)
	{
		if (sizeof($fcv_array) > 0)
		{
			if ($deep || $across)
			{
				$objectList = $this->GetList($fcv_array);
				foreach ($objectList as $object)
				{
					$object->Delete($deep, $across);
				}
			}
			else
			{
				$connection = Database::Connect();
				$pog_query = "delete from `page` where ";
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
	}
	
	
	/**
	* Associates the Category object to this one
	* @return boolean
	*/
	function GetCategory()
	{
		$category = new Category();
		return $category->Get($this->categoryId);
	}
	
	
	/**
	* Associates the Category object to this one
	* @return 
	*/
	function SetCategory(&$category)
	{
		$this->categoryId = $category->categoryId;
	}
	
	
	/**
	* Gets a list of StoryLines objects associated to this one
	* @param multidimensional array {("field", "comparator", "value"), ("field", "comparator", "value"), ...} 
	* @param string $sortBy 
	* @param boolean $ascending 
	* @param int limit 
	* @return array of StoryLines objects
	*/
	function GetStorylinesList($fcv_array = array(), $sortBy='', $ascending=true, $limit='')
	{
		$storylines = new StoryLines();
		$fcv_array[] = array("storyId", "=", $this->storyId);
		$dbObjects = $storylines->GetList($fcv_array, $sortBy, $ascending, $limit);
		return $dbObjects;
	}
	
	
	/**
	* Makes this the parent of all StoryLines objects in the StoryLines List array. Any existing StoryLines will become orphan(s)
	* @return null
	*/
	function SetStorylinesList(&$list)
	{
		$this->_storylinesList = array();
		$existingStorylinesList = $this->GetStorylinesList();
		foreach ($existingStorylinesList as $storylines)
		{
			$storylines->storyId = '';
			$storylines->Save(false);
		}
		$this->_storylinesList = $list;
	}
	
	
	/**
	* Associates the StoryLines object to this one
	* @return 
	*/
	function AddStorylines(&$storylines)
	{
		$storylines->storyId = $this->storyId;
		$found = false;
		foreach($this->_storylinesList as $storylines2)
		{
			if ($storylines->storylinesId > 0 && $storylines->storylinesId == $storylines2->storylinesId)
			{
				$found = true;
				break;
			}
		}
		if (!$found)
		{
			$this->_storylinesList[] = $storylines;
		}
	}
}
?>