<? 
include('config.php'); 
if (isset($_POST['submitted'])) { 
foreach($_POST AS $key => $value) { $_POST[$key] = mysql_real_escape_string($value); } 
$sql = "INSERT INTO `answer` ( `answerid` ,  `statement` ,  `questionid` ,  `is_correct`  ) VALUES(  '{$_POST['answerid']}' ,  '{$_POST['statement']}' ,  '{$_POST['questionid']}' ,  '{$_POST['is_correct']}'  ) "; 
mysql_query($sql) or die(mysql_error()); 
echo "Added row.<br />"; 
echo "<a href='list.php'>Back To Listing</a>"; 
} 
?>

<form action='' method='POST'> 
<p><b>Answerid:</b><br /><input type='text' name='answerid'/> 
<p><b>Statement:</b><br /><input type='text' name='statement'/> 
<p><b>Questionid:</b><br /><input type='text' name='questionid'/> 
<p><b>Is Correct:</b><br /><input type='text' name='is_correct'/> 
<p><input type='submit' value='Add Row' /><input type='hidden' value='1' name='submitted' /> 
</form> 
