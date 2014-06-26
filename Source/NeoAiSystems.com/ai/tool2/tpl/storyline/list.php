<? 
$story = new Story();
if(isset($_REQUEST['sid']))
{
	$sid = $_REQUEST['sid'];
	$story = $story->Get($sid);
	$result = mysql_query("SELECT * FROM `storyline` where storyid=$sid order by storylineid") or trigger_error(mysql_error());
}

echo "<table border=1 >"; 
echo "<tr>"; 
echo "<td><b>ID</b></td>"; 
echo "<td><b>Text</b></td>"; 
echo "<td><b>Story</b></td>"; 
echo "</tr>"; 
 
while($row = mysql_fetch_array($result)){ 
foreach($row AS $key => $value) { $row[$key] = stripslashes($value); }
if($row['text']!='')
{ 
	echo "<tr>";  
	echo "<td valign='top'>" . nl2br( $row['storylineid']) . "</td>";  
	echo "<td valign='top'>" . nl2br( $row['text']) . "</td>";  
	echo "<td valign='top'>" . nl2br( $story->title) . "</td>";  
	//echo "<td valign='top'><a href=edit.php?id={$row['storylineid']}>Edit</a></td>";//<td><a href=delete.php?id={$row['storylineid']}>Delete</a></td> "; 
	echo "</tr>";
} 
} 
echo "</table>"; 
//echo "<a href=new.php>New Row</a>"; 
?>