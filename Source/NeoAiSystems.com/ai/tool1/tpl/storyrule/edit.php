<? 
if (isset($_GET['srid']) ) {
$srid = $_REQUEST['srid'];

$editRule = new StoryRule();
$editRule = $editRule->Get($srid);
 
//$id = (int) $_GET['id']; 
if (isset($_POST['submitted'])) { 
foreach($_POST AS $key => $value) { $_POST[$key] = mysql_real_escape_string($value); } 
$sql = "UPDATE `storyrule` SET  `storyruleid` =  '{$_POST['storyruleid']}' ,  `statement` =  '{$_POST['statement']}' ,  `storyid` =  '{$_POST['storyid']}'   WHERE `storyruleid` = '$srid' "; 
mysql_query($sql) or die(mysql_error()); 
echo (mysql_affected_rows()) ? "Edited row.<br />" : "Nothing changed. <br />"; 
echo "<a href='admin.php?page=storyrule&tpl=list&sid=$editRule->storyId'>Back To Listing</a>"; 
} 
$row = mysql_fetch_array ( mysql_query("SELECT * FROM `storyrule` WHERE `storyruleid` = '$srid' ")); 
?>

<form action='' method='POST'> 
<p><input type='hidden' name='storyruleid' value='<?= stripslashes($row['storyruleid']) ?>' /> 
<p><b>Statement:</b><br /><input type='text' name='statement' value='<?= stripslashes($row['statement']) ?>' /> 
<p><input type='hidden' name='storyid' value='<?= stripslashes($row['storyid']) ?>' /> 
<p><input type='submit' value='Update' /><input type='hidden' value='1' name='submitted' /> 
</form> 
<? } ?> 
