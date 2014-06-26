<? 
$sid = $_REQUEST['sid'];
if (isset($_POST['submitted'])) { 
foreach($_POST AS $key => $value) { $_POST[$key] = mysql_real_escape_string($value); } 
$sql = "INSERT INTO `storyrule` ( `storyruleid` ,  `statement` ,  `storyid`  ) VALUES(  '{$_POST['storyruleid']}' ,  '{$_POST['statement']}' ,  '{$sid}'  ) "; 
mysql_query($sql) or die(mysql_error()); 
echo "Added row.<br />"; 
echo "<a href='admin.php?page=storyrule&tpl=list&sid=$sid'>Back To Listing</a>"; 
} 
?>

<form action='' method='POST'> 
<p><input type='hidden' name='storyruleid'/> 
<p><b>Rule Statement:</b><br /><input type='text' name='statement'/> 
<p><!-- <b>Story:</b><br /><input type='text' name='storyid'/> --> 
<p><input type='submit' value='Add' /><input type='hidden' value='1' name='submitted' /> 
</form> 
