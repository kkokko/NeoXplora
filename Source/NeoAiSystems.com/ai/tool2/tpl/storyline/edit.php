<? 
include('config.php'); 
if (isset($_GET['id']) ) { 
$id = (int) $_GET['id']; 
if (isset($_POST['submitted'])) { 
foreach($_POST AS $key => $value) { $_POST[$key] = mysql_real_escape_string($value); } 
$sql = "UPDATE `storyline` SET  `storylineid` =  '{$_POST['storylineid']}' ,  `text` =  '{$_POST['text']}' ,  `storyid` =  '{$_POST['storyid']}'   WHERE `id` = '$id' "; 
mysql_query($sql) or die(mysql_error()); 
echo (mysql_affected_rows()) ? "Edited row.<br />" : "Nothing changed. <br />"; 
echo "<a href='list.php'>Back To Listing</a>"; 
} 
$row = mysql_fetch_array ( mysql_query("SELECT * FROM `storyline` WHERE `id` = '$id' ")); 
?>

<form action='' method='POST'> 
<p><b>Storylineid:</b><br /><input type='text' name='storylineid' value='<?= stripslashes($row['storylineid']) ?>' /> 
<p><b>Text:</b><br /><textarea name='text'><?= stripslashes($row['text']) ?></textarea> 
<p><b>Storyid:</b><br /><input type='text' name='storyid' value='<?= stripslashes($row['storyid']) ?>' /> 
<p><input type='submit' value='Edit Row' /><input type='hidden' value='1' name='submitted' /> 
</form> 
<? } ?> 
