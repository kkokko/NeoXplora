<? 

$category = new Category();
$category = $category->GetList();
$editStory = new Story();
if(isset($_REQUEST['id']))
{	
	$id = $_REQUEST['id'];
	$editStory = $editStory->Get($id);

	if (isset($_POST['submitted'])) 
	{
		if(isset($_REQUEST['id']))
		{
			
			$editStory->title = $_POST['title'];
			$editStory->body = $_POST['body'];
			$editStory->categoryId = $_POST['categoryid'];
			$editStory->Save();
			//delete extracted lines and save new ones
			$storyline = new StoryLine();
			$storyline = $storyline->GetList(array(array('storyId', '=', $id)));
			foreach ($storyline as $aline)
			{
				//delete representation for each line
				$representation = new Representation();
				$representation = $representation->GetList(array(array('storylineid', '=', $aline->storylineId)));
				foreach ($representation as $arep)
				{
					$arep->Delete();
				}
				$aline->Delete();
			}
			
			//now extract and add lines again		
			$storytext = $editStory->body;
			$extracted_lines = explode(".", $storytext);  
			
			foreach($extracted_lines as $aline)
			{
				$storyline = new StoryLine();
				$storyline->text = $aline;
				$storyline->storyId = $id;
				$newid = $storyline->Save();
				$representation = new Representation();
				$representation->storylineId = $newid ;
				$representation->Save(); 
			}
			echo "Story has been saved.<br />"; 
			
			echo "<a href='admin.php?page=story&tpl=list'>Back To Listing</a>";
		}
	} 
}
?>

<form action='' method='POST'> 
<p><br /><input type='hidden' name='storyid' value="<?=$editStory->storyId ?>"/> 
<p><b>Title:</b><br /><input type='text' name='title' value="<?=$editStory->title ?>"/> 
<p><b>Body:</b><br /><textarea style="width: 300px; height: 150px;" type='text' name='body'><?=$editStory->body ?></textarea> 
<p><b>Category:</b><br />
<select type='text' name='categoryid'>
	<? 
	foreach($category as $cat)
	{
		$selected = '';
		if($cat->categoryId == $editStory->categoryId)
		{
			$selected = 'selected="selected"';
		}
		else 
		{
			$selected = '';
		}
		?>
		<option value="<?=$cat->categoryId ?>" <?=$selected ?>><?= $cat->name ?></option>
		<?php 
	}
	?>	
</select> 
<p><input type='submit' value='Save Story' /><input type='hidden' value='1' name='submitted' /> 
</form> 
