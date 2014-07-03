<?php ;
	function sql_safe($strIn, $blnEncode=false){
		$arrBad = array('CAST(', ';DECLARE', 'EXEC(', 'CAST (', 'EXEC (', ';SET%20', 'CHAR(', 'CHAR (');
		foreach($arrBad as $v){
			if(stripos($strIn,$v))
				$strIn="POSSIBLE INJECTION ATTACK - VARIABLE DESTROYED";
		}
		if($blnEncode)
			return htmlspecialchars($strIn,ENT_QUOTES);
		else
			return $strIn;
	}
	function dbQuery($db,$sql){
		$result = mysql_query(sql_safe($sql, false));
		if (!$result) {
   			$message  = 'Invalid query: ' . mysql_error() . "\n";
   			$message .= 'Whole query: ' . $sql;
   			die($message);
		}
		return $result;
	}
	
	function dbFetchArray($result){
		return mysql_fetch_array($result);	
	}
	function dbFetchAssocArray($result){
		return mysql_fetch_array($result, MYSQL_ASSOC);	
	}
	
	function dbGetRows($result){
		return mysql_num_rows($result);
	}
	
	function dbGetLast(){
		return mysql_insert_id();
	}
	
	function dbGetLastID($db,$tablename){
		$result = mysql_query($db,"select MAX(ID) AS Maxid FROM " . $tableName);
		if(mysql_num_rows($result)){
			$row = mysql_fetch_array($result);
			$lastid = $row["Maxid"];
		}else{
			$lastid = 0;
		}
		return $lastid;
	}
	function dbFetchObject($result){
		return mysql_fetch_object($result);	
	}
	
	function dbReset($result){
		mysql_data_seek($result,0);
	}

?>