<?php 
error_reporting(0);

require_once "../config_storydb.php";
mysql_connect($configuration['host'], $configuration['user'], $configuration['pass']);
mysql_select_db($configuration['db']);

$hybrid100=$hybrid50=$hybrid25=$pos100=$pos50=$pos25=$text100=$text50=$text25=0;

$story_data = "SELECT text25 from page where NOT (text25 is null or text25 ='')";
$result_story_data = mysql_query($story_data) or die('MySql Error' . mysql_error());        /* START CREP */ 
$total=$j=0;
while ($rows = mysql_fetch_array($result_story_data)) {
if (!empty($rows['text25']))
$total+=$rows['text25']; $j++;
}

if ($j>0){
$text25=round(($total/$j),1);
}else {
$text25=0;
}

$story_data = "SELECT text50 from page where  NOT (text50 is null or text50 ='')";
$result_story_data = mysql_query($story_data) or die('MySql Error' . mysql_error());        /* START CREP */ 
$total=$j=0;
while ($rows = mysql_fetch_array($result_story_data)) {
if (!empty($rows['text50']))
$total+=$rows['text50']; $j++;
}


if ($j>0){
$text50=round(($total/$j),1);
}else {
$text50=0;
}


$story_data = "SELECT text100 from page where  NOT (text100 is null or text100 ='')";
$result_story_data = mysql_query($story_data) or die('MySql Error' . mysql_error());        /* START CREP */ 
$total=$j=0;
while ($rows = mysql_fetch_array($result_story_data)) {
if (!empty($rows['text100']))
$total+=$rows['text100']; $j++;
}


if ($j>0){
$text100=round(($total/$j),1);
}else {
$text100=0;
}

$story_data = "SELECT pos25 from page where   NOT (pos25 is null or pos25 ='')";
$result_story_data = mysql_query($story_data) or die('MySql Error' . mysql_error());        /* START CREP */ 
$total=$j=0;
while ($rows = mysql_fetch_array($result_story_data)) {
if (!empty($rows['pos25']))
$total+=$rows['pos25']; $j++;
}


if ($j>0){
$pos25=round(($total/$j),1);
}else {
$pos25=0;
}


$story_data = "SELECT pos50 from page where   NOT (pos50 is null or pos50 ='')";
$result_story_data = mysql_query($story_data) or die('MySql Error' . mysql_error());        /* START CREP */ 
$total=$j=0;
while ($rows = mysql_fetch_array($result_story_data)) {
if (!empty($rows['pos50']))
$total+=$rows['pos50']; $j++;
}


if ($j>0){
$pos50=round(($total/$j),1);
}else {
$pos50=0;
}


$story_data = "SELECT pos100 from page where  NOT (pos100 is null or pos100 ='')";
$result_story_data = mysql_query($story_data) or die('MySql Error' . mysql_error());        /* START CREP */ 
$total=$j=0;
while ($rows = mysql_fetch_array($result_story_data)) {
if (!empty($rows['pos100']))
$total+=$rows['pos100']; $j++;
}


if ($j>0){
$pos100=round(($total/$j),1);
}else {
$pos100=0;
}

$story_data = "SELECT hybrid25 from page where  NOT (hybrid25 is null or hybrid25='')";
$result_story_data = mysql_query($story_data) or die('MySql Error' . mysql_error());        /* START CREP */ 
$total=$j=0;
while ($rows = mysql_fetch_array($result_story_data)) {
if (!empty($rows['hybrid25']))
$total+=$rows['hybrid25']; $j++;
}


if ($j>0){
$hybrid25=round(($total/$j),1);
}else {
$hybrid25=0;
}


$story_data = "SELECT hybrid50 from page where NOT (hybrid50 is null or hybrid50='')";
$result_story_data = mysql_query($story_data) or die('MySql Error' . mysql_error());        /* START CREP */ 
$total=$j=0;
while ($rows = mysql_fetch_array($result_story_data)) {
if (!empty($rows['hybrid50']))
$total+=$rows['hybrid50']; $j++;
}


if ($j>0){
$hybrid50=round(($total/$j),1);
}else {
$hybrid50=0;
}

$story_data = "SELECT hybrid100 from page where NOT (hybrid100 is null or hybrid100='')";
$result_story_data = mysql_query($story_data) or die('MySql Error' . mysql_error());        /* START CREP */ 
$total=$j=0;
while ($rows = mysql_fetch_array($result_story_data)) {
if (!empty($rows['hybrid100']))
$total+=$rows['hybrid100']; $j++;
}


if ($j>0){
$hybrid100=round(($total/$j),1);
}else {
$hybrid100=0;
}












//    $qtmp1 = 'Select * from page WHERE storyID='.$_REQUEST['id'];
//    $chk1 = mysql_query($qtmp1);
//    if($rows= mysql_fetch_array($chk1)) {
echo "25,".round($text25,0).','.round($pos25,0).','.round($hybrid25,0)."\n";
echo "50,".round($text50,0).','.round($pos50,0).','.round($hybrid50,0)."\n";
echo "100,".round($text100,0).','.round($pos100,0).','.round($hybrid100,0)."\n";
//    }
?>
