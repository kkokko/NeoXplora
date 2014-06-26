<? 
$category = new Category();

echo "<table>"; 
echo "<tr>"; 
echo "<td><b>Storyid</b></td>"; 
echo "<td><b>Title</b></td>"; 
echo "<td><b>Body</b></td>"; 
echo "<td><b>Category</b></td>"; 
echo "</tr>"; 
$result = mysql_query("SELECT * FROM `story`") or trigger_error(mysql_error()); 
while($row = mysql_fetch_array($result)){	
	$category = $category->Get($row['categoryid']); 
foreach($row AS $key => $value) { $row[$key] = stripslashes($value); } 
echo "<tr>";  
echo "<td valign='top'>" . nl2br( $row['storyid']) . "</td>";  
echo "<td valign='top'>" . nl2br( $row['title']) . "</td>";  
echo "<td valign='top'>" . nl2br( $row['body']) . "</td>";  
echo "<td valign='top'>" . nl2br( $category->name) . "</td>";  
//echo "<td valign='top'><a href=admin.php?page=story&tpl=edit&id={$row['storyid']}>Edit</a></td><td><a href=delete.php?id={$row['storyid']}>Delete</a></td> ";
?> 
<td><a href="admin.php?page=question&tpl=list&sid=<?=$row['storyid'] ?>">Question/Answers</a></td>
<td><a href="admin.php?page=storyline&tpl=list&sid=<?=$row['storyid'] ?>">Lines</a></td>

<td><a href="admin.php?page=storyrule&tpl=list&sid=<?=$row['storyid'] ?>">Rules</a></td>
</tr>
<?php  
} 
echo "</table>"; 
echo "<a href=admin.php?page=story&tpl=new>Add New Story</a>"; 
?>