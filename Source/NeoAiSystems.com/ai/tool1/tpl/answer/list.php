<? 
include('config.php'); 
echo "<table border=1 >"; 
echo "<tr>"; 
echo "<td><b>Answerid</b></td>"; 
echo "<td><b>Statement</b></td>"; 
echo "<td><b>Questionid</b></td>"; 
echo "<td><b>Is Correct</b></td>"; 
echo "</tr>"; 
$result = mysql_query("SELECT * FROM `answer`") or trigger_error(mysql_error()); 
while($row = mysql_fetch_array($result)){ 
foreach($row AS $key => $value) { $row[$key] = stripslashes($value); } 
echo "<tr>";  
echo "<td valign='top'>" . nl2br( $row['answerid']) . "</td>";  
echo "<td valign='top'>" . nl2br( $row['statement']) . "</td>";  
echo "<td valign='top'>" . nl2br( $row['questionid']) . "</td>";  
echo "<td valign='top'>" . nl2br( $row['is_correct']) . "</td>";  
echo "<td valign='top'><a href=edit.php?id={$row['id']}>Edit</a></td><td><a href=delete.php?id={$row['id']}>Delete</a></td> "; 
echo "</tr>"; 
} 
echo "</table>"; 
echo "<a href=new.php>New Row</a>"; 
?>