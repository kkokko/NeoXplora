<?php

$configuration['db']  = 'db179668_ai2';     //  database name
$configuration['host']  = '127.0.0.1';  //  database host
$configuration['user']  = 'userneoai';   //  database user
$configuration['pass']  = 'login141';   //  database password
$configuration['port']  = '';   //  database port

// connect to db
$link = mysql_connect($configuration['host'], $configuration['user'], $configuration['pass']);
if (!$link) {
    die('Not connected : ' . mysql_error());
}

if (! mysql_select_db($configuration['db']) ) {
    die ('Can\'t use db ai2 : ' . mysql_error());
}

?>