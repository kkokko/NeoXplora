<? 
 
if(isset($_REQUEST['sid']))
{
	$sid = $_REQUEST['sid'];
	if (isset($_POST['submitted'])) 
	{
		foreach($_POST AS $key => $value) { $_POST[$key] = mysql_real_escape_string($value); }
		
		$question = new Question();
		$answer = new Answer();
		$question->statement = $_POST['question'];
		$question->story_id = $sid;
		$question->storyId = $sid;
		$newid = $question->Save();
		//now save the answer
		
		$answer->statement = $_POST['answer'];
		$answer->questionId = $newid;
		$answer->Save();		
		echo "Question has been added.<br />";
		echo "<a href='admin.php?page=question&tpl=list&sid=$sid'>Back To Listing</a>"; 
	}
} 
?>

<form action='' method='POST'> 
<p><input type='hidden' name='questionid'/> 
<p><b>Question:</b><br /><input type='text' name='question'/> 
<p><b>Answer:</b><br /><input type='text' name='answer'/> 
 
<p><input type='submit' value='Add Question' /><input type='hidden' value='1' name='submitted' /> 
</form> 
