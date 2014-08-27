<?php
include("PhpReverseProxy.php");
$proxy=new PhpReverseProxy();
$proxy->port="2589";
$proxy->host="127.0.0.1";
$proxy->forward_path="";
$proxy->connect();
$proxy->output();
?>