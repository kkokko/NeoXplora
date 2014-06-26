<? 

$category = new Category();
$category = $category->GetList();
if (isset($_POST['submitted'])) { 

	$newstory = new Story();
	$newstory->title = $_POST['title'];
	$newstory->body = $_POST['body'];
	$newstory->categoryId = $_POST['categoryid'];
	$id = $newstory->Save();
//$sql = "INSERT INTO `story` ( `storyid` ,  `title` ,  `body` ,  `categoryid`  ) VALUES(  '{$_POST['storyid']}' ,  '{$_POST['title']}' ,  '{$_POST['body']}' ,  '{$_POST['categoryid']}'  ) "; 
//mysql_query($sql) or die(mysql_error());

	if($id)
	{
		$storytext = $newstory->body;
		$extracted_lines = explode(".", $storytext);  
		
		foreach($extracted_lines as $aline)
		{
			$storyline = new StoryLine();
			$storyline->text = $aline;
			$storyline->storyId = $newstory->storyId;
			$newid = $storyline->Save();
			$representation = new Representation();
			$representation->text = '';
			$representation->storylineId = $storyline->storylineId ;
			
			$representation->SaveNew();
		}
		///CLIENT REQUIREMENT: add 20 questions/answers/rules for each story
		for($i=0;$i<20;$i++)
		{
			//add questions
			$question = new Question();
			$question->storyId = $newstory->storyId;
			$question->Save();
			//add answers
			$answer = new Answer();
			$answer->questionId = $question->questionId;
			$answer->Save();
			//add rules
			$sRule = new StoryRule();
			$sRule->storyId = $newstory->storyId;
			$sRule->Save();
		}
		echo "Story has been added.<br />"; 
	}
	echo "<a href='admin.php?page=story&tpl=list'>Back To Listing</a>";
} 
?>

<form action='' method='POST'> 
<p><br /><input type='hidden' name='storyid'/> 
<p><b>Title:</b><br /><input type='text' name='title'/> 
<p><b>Body:</b><br /><textarea style="width: 300px; height: 150px;" type='text' name='body'></textarea> 
<p><b>Category:</b><br />
<select type='text' name='categoryid'>
	<? 
	foreach($category as $cat)
	{
		?>
		<option value="<?=$cat->categoryId ?>"><?= $cat->name ?></option>
		<?php 
	}
	?>	
</select> 
<p><input type='submit' value='Add Story' /><input type='hidden' value='1' name='submitted' /> 
</form> 
