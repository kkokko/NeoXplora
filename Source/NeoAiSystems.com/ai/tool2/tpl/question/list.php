<? 

echo "<table border=1 >"; 
echo "<tr>"; 
echo "<td><b>Question ID</b></td>"; 
echo "<td><b>Question</b></td>"; 
echo "<td><b>Answer</b></td>"; 
echo "<td><b>Story</b></td>"; 
echo "</tr>"; 
$sid = $_REQUEST['sid'];
$result = mysql_query("SELECT * FROM `question` where `storyid` = $sid") or trigger_error(mysql_error()); 
while($row = mysql_fetch_array($result)){ 
foreach($row AS $key => $value) { $row[$key] = stripslashes($value); }

$story = new Story();
$story = $story->Get($row['storyid']);

$answer = new Answer();
$answer =$answer->Get($row['questionid']);

echo "<tr>";  
echo "<td valign='top'>" . nl2br( $row['questionid']) . "</td>";  
echo "<td valign='top'>" . nl2br( $row['statement']) . "</td>";  
echo "<td valign='top'>" . nl2br( $answer->statement) . "</td>";  
echo "<td valign='top'>" . nl2br( $story->title) . "</td>";  
echo "<td valign='top'><a href=admin.php?page=question&tpl=edit&qid={$row['questionid']}>Edit</a></td>"; //<td><a href=admin.php?page=question&tpl=delete&qid={$row['questionid']}>Delete</a></td> "; 
echo "</tr>"; 
} 
echo "</table>";
?> 
<a href="admin.php?page=question&tpl=new&sid=<?=$sid ?>">Add New</a> 
