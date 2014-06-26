<? 
if(isset($_REQUEST['sid']))
{
	$sid = $_REQUEST['sid'];
}
echo "<table border=1 >"; 
echo "<tr>"; 
echo "<td><b>Representationid</b></td>"; 
echo "<td><b>Text</b></td>"; 
echo "<td><b>Storylineid</b></td>"; 
echo "</tr>"; 
$result = mysql_query("SELECT * FROM `representation`") or trigger_error(mysql_error()); 
while($row = mysql_fetch_array($result)){ 
foreach($row AS $key => $value) { $row[$key] = stripslashes($value); } 
echo "<tr>";  
echo "<td valign='top'>" . nl2br( $row['representationid']) . "</td>";  
echo "<td valign='top'>" . nl2br( $row['text']) . "</td>";  
echo "<td valign='top'>" . nl2br( $row['storylineid']) . "</td>";  
echo "<td valign='top'><a href=edit.php?id={$row['id']}>Edit</a></td><td><a href=delete.php?id={$row['id']}>Delete</a></td> "; 
echo "</tr>"; 
} 
echo "</table>"; 
echo "<a href=new.php>New Row</a>"; 
?>