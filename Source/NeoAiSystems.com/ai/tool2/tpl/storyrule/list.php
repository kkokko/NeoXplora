<? 
$story = new Story();
if(isset($_REQUEST['sid']))
{
	$sid = $_REQUEST['sid'];
	$storyrules = new StoryRule();
	$storyrules = $storyrules->GetList(array(array('storyId', '=', $sid)));
	$story = $story->Get($sid);
}
echo "<table border=1 >"; 
echo "<tr>"; 
echo "<td><b>ID</b></td>"; 
echo "<td><b>Rule</b></td>"; 
//echo "<td><b>Story</b></td>"; 
echo "</tr>"; 
foreach($storyrules as $rule) 
{
echo "<tr>";  
echo "<td valign='top'>" . nl2br( $rule->storyruleId ) . "</td>";  
echo "<td valign='top'>" . nl2br( $rule->statement ) . "</td>";  
//echo "<td valign='top'>" . nl2br( $story->title) . "</td>";  
echo "<td valign='top'><a href=admin.php?page=storyrule&tpl=edit&srid={$rule->storyruleId}>Edit</a></td>";//<td><a href=admin.php?page=storyrule&tpl=delete&srid={$rule->storyruleId}>Delete</a></td> "; 
echo "</tr>"; 
}
echo "</table>"; 
echo "<a href=admin.php?page=storyrule&tpl=new&sid=$sid>Add New Rule</a>"; 
?>