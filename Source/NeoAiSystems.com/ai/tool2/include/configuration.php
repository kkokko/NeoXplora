<?php
//IMPORTANT:
//Rename this file to configuration.php after having inserted all the correct db information

global $configuration;

$configuration['siteurl']='http://127.0.0.1/vworker-projects/eduaid/';

$configuration['setup_password'] = '';
$configuration['db_encoding'] = 0;

// edit the information below to match your database settings

$configuration['db']	= 'db179668_ai2'; 		//	database name
$configuration['host']	= '127.0.0.1';	//	database host
$configuration['user']	= 'userneoai';		//	database user
$configuration['pass']	= 'login141';		//	database password
$configuration['port'] 	= '';		//	database port

//proxy settings - if you are behnd a proxy, change the settings below
$configuration['proxy_host'] = false;
$configuration['proxy_port'] = false;
$configuration['proxy_username'] = false;
$configuration['proxy_password'] = false;

//plugin settings
$configuration['plugins_path'] = '';  //absolute path to plugins folder, e.g c:/mycode/test/plugins or /home/phpobj/public_html/plugins

// connect to db
$link = mysql_connect($configuration['host'], $configuration['user'], $configuration['pass']);
if (!$link) {
    die('Not connected : ' . mysql_error());
}

if (! mysql_select_db($configuration['db']) ) {
    die ('Can\'t use db ai2 : ' . mysql_error());
}

?>