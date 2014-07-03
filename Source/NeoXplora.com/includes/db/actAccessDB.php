<?php
	// Connection parameters
	// 
	$dbserver = $config['DB_SERVER'];
	$dbUserName = $config['DB_USERNAME'] ;
	$dbPassWord = $config['DB_PASSWORD'] ;
	$dbdatabaseName = $config['DB_NAME'] ;
	
	$db = mysql_connect($dbserver,$dbUserName,$dbPassWord) or die("DB COnnect Error=".mysql_error());
	//mysql_select_db($dbdatabaseName);
	mysql_select_db($dbdatabaseName,$db)or die("DN Select Error=".mysql_error());
        mysql_query("SET NAMES 'utf8'");
	$dbAccessed = "Y";
	
	
	
	if(!function_exists('dbQuery'))
		include("dbSQLFunctions.php");
?>