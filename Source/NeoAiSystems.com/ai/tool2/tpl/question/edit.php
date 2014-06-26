<? 
 
$question = new Question();
$answer = new Answer();
if(isset($_REQUEST['qid']))
{
	$qid = $_REQUEST['qid'];	
	$question = $question->Get($qid);
	
	$answer = $answer->GetList(array(array('questionId', '=', $qid)));
	if(count($answer) >= 1)
	{
		$answer = $answer[0];
	}
	if (isset($_POST['submitted'])) 
	{
		foreach($_POST AS $key => $value) { $_POST[$key] = mysql_real_escape_string($value); }
		
		
		$question->statement = $_POST['question'];		
		$newid = $question->Save();
		//now save the answer
		
		$answer->statement = $_POST['answer'];		
		$answer->Save();		
		echo "Question has been saved.<br />";
		echo "<a href='admin.php?page=question&tpl=list&sid=$question->storyId'>Back To Listing</a>"; 
	}
} 
?>

<form action='' method='POST'> 
<p><input type='hidden' name='questionid'/> 
<p><b>Question:</b><br /><input type='text' name='question' value="<?=$question->statement ?>"/> 
<p><b>Answer:</b><br /><input type='text' name='answer' value="<?=$answer->statement ?>"/> 
 
<p><input type='submit' value='Save Question' /><input type='hidden' value='1' name='submitted' /> 
</form> 
