<? 
 
if (isset($_POST['submitted'])) { 
foreach($_POST AS $key => $value) { $_POST[$key] = mysql_real_escape_string($value); } 
$sql = "INSERT INTO `category` ( `name`  ) VALUES(  '{$_POST['name']}'  ) "; 
mysql_query($sql) or die(mysql_error()); 
echo "Category has been added.<br />"; 
echo "<a href='admin.php?page=category&tpl=list'>Back To Listing</a>"; 
} 
?>

<form action='' method='POST'> 
<p><b>Name:</b><br /><input type='text' name='name'/> 
<!-- <p><b>Categoryid:</b><br /><input type='text' name='categoryid'/>  --> 
<p><input type='submit' value='Add Row' /><input type='hidden' value='1' name='submitted' /> 
</form> 
