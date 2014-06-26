<?php 
include 'tpl/header.tpl.php';


//if form was saved
if(isset($_POST['submitted']))
{
	//foreach ()
	$post = $_POST;
	$updateStory = new Story();
	$updateStoryId = 0;
	//print_r(array_keys($_POST));
	$storyLineIds = array();
	$updatedStoryLines = array();
	$representationIds = array();
	$questionIds = array();
	$answerIds = array();
	$ruleIds = '';
	foreach ($post as $key=>$val)
	{
		if(!(strpos($key, 'storyLineId_') === false))
		{	
			$mykey = substr($key, strpos($key, '_')+1);			
			$storyLineIds[$mykey] = $val;
			$curObj = new StoryLine();			
			$curObj = $curObj->Get($mykey);
			$updateStoryId = $curObj->storyId;
			$curObj->sentence = $val;
			$curObj->Save();
			$updatedStoryLines[] = $val;
		}
		else if(!(strpos($key, 'representationId_') === false))
		{	
			$mykey = substr($key, strpos($key, '_')+1);	
			
			$representationIds[$mykey] = $val;
				 
			$curObj = new Representation();
			$curObj = $curObj->Get($mykey);
			$curObj->text = $val;
			$curObj->Save(1,$val);			 
		}
		 else if(!(strpos($key, 'representationoldId_') === false))
		{	
			$mykey = substr($key, strpos($key, '_')+1);	
			
			$representationIds[$mykey] = $val;
			
			$curObj = new Representation();
			$curObj = $curObj->Get($mykey);
			$curObj->textold = $val;
			 
			$curObj->Save(2,$val);			 
		}
		else if(!(strpos($key, 'questionId_') === false))
		{	
			$mykey = substr($key, strpos($key, '_')+1);			
			$questionIds[$mykey] = $val;	
			$curObj = new Question();
			$curObj = $curObj->Get($mykey);
			$curObj->statement = $val;
			$curObj->Save();		 
		}
		else if(!(strpos($key, 'answerId_') === false))
		{	
			$mykey = substr($key, strpos($key, '_')+1);			
			$answerIds[$mykey] = $val;
			$curObj = new Answer();
			$curObj = $curObj->Get($mykey);
			$curObj->statement = $val;
			//$curObj->answerId = $mykey;
			$curObj->Save();			 
		}
		else if(!(strpos($key, 'storyruleId_') === false))
		{	
			$mykey = substr($key, strpos($key, '_')+1);			
			$ruleIds[$mykey] = $val;
			$curObj = new StoryRule();
			$curObj = $curObj->Get($mykey);
			$curObj->statement = $val;
			$curObj->Save();			 
		}
		$updateStory = $updateStory->Get($updateStoryId);
		$storyBody = implode('.', $updatedStoryLines);
		$updateStory->body = $storyBody;
		$updateStory->Save();
		
	}
}

$categoryList = new Category();
$storyConditions = null;
$selected_cat = 0;
if(isset($_POST['changeCat']))
{	
	if(isset($_POST['categoryId']))
	{
		$selected_cat = $_POST['categoryId'];			
    	echo '<META HTTP-EQUIV="Refresh" Content="0; URL=index.php?cid='.$selected_cat.'">';    
    	exit;
	}	
}

if(isset($_POST['changestor']))
{	
	if(isset($_POST['storyId']))
	{
		$selected_cat = $_POST['changestor'];
			$selected_stor = $_POST['storyId'];
    	echo '<META HTTP-EQUIV="Refresh" Content="0; URL=index.php?sid='.$selected_stor.'&cid='.$selected_cat.'">';
    	exit;
	}	
}

if(isset($_REQUEST['cid']))
{
	$selected_cat = $_REQUEST['cid'];
}
$categoryList = $categoryList->GetList();
$stories = new Story();

$allStoryConditions = null;
$conditions = null;
if($selected_cat !=0 )
{
	$allStoryConditions = array(array('categoryId', '=', $selected_cat));
}
if(isset($_REQUEST['sid']))
{
	$conditions = array(array('storyId', '=', $_REQUEST['sid']));
	
	$selected_stor = $_REQUEST['sid'];
}
else 
{
	$conditions = $allStoryConditions;
}
$allstories = $stories->GetList($allStoryConditions);
$stories = $stories->GetList($conditions);
if(count($stories) > 0)
{
	$currStory = $stories[0];
}

//questions and answers for curr story

$questions = new Question();
if(isset($currStory))
{
	$questions = $questions->GetList(array(array('storyId', '=', $currStory->storyId)));
}
$answers = new Answer();
//print_r($allstories);
?>

<div class="mainformsdiv">

	<form id="form1" name="form1" method="post" action="">
	  <label>categories
	  <select name="categoryId" id="categories" onchange="javascript:document.forms['form1'].submit();">
	    <option value="0">All</option>
	    <?php
	    foreach($categoryList as $aCat)
	    {
	    	$selected = "";
	    	if($selected_cat !=0  && $aCat->categoryId==$selected_cat)
	    	{
	    		$selected = 'selected="'.$selected_cat.'"' ;
	    	}
	    	?>
	    		<option value="<?=$aCat->categoryId ?>" <?=$selected ?>><?=$aCat->category ?></option>
	    	<?php 
	    } 
	    ?>
	  </select>
	  </label>
	  <input type="hidden" name="changeCat" value="1"/>
	</form>
	
	<form id="form3" name="form3" method="post" action=""  onchange="javascript:document.forms['form3'].submit();">
	  <label>Stories
	  <select name="storyId" id="stories">
	    <option value="0">All</option>
	    <?php
	    foreach($allstories as $astory)
	    {
	    	$selected = "";
	    	if($selected_stor !=0  && $astory->storyId==$selected_stor)
	    	{
	    		$selected = 'selected="'.$selected_stor.'"' ;
	    	}
	    	?>
	    		<option value="<?=$astory->storyId ?>" <?=$selected ?>><?=$astory->title ?></option>
	    	<?php 
	    } 
	    ?>
	  </select>
	  </label>
	  <input type="hidden" name="changestor" value="<?php echo $selected_cat; ?>"/>
	</form>
</div>	
<p>&nbsp;</p>
<p>&nbsp;</p>
<form name="form2" method="post" action="" id="form2"> 

<div class="usualskm">
<div class="skmfloatlf">
<ul class="idTabs"> 
  <li><a href="#representation">Sentence</a></li> 
 </ul>
<ul style="padding-left: 275px;"> 
  <li><a href="#" class="selected">Representation</a></li> 
 </ul>
 <div id="representation">
 <?php 
  	if(count($stories)>0)
  	{
  	?>
	<table>
            <?
  			$storylines = new StoryLine();
  			$representations = array();
  			if(isset($currStory))
  			{
  				$storylines = $storylines->GetList(array(array('storyID', '=', $currStory->storyId)));
  			
	  		 	//$currStory->body
	  		 	$linenumber = 1;
	  		 	foreach ($storylines as $line)
	  		 	{
	  		 		echo '<tr><td width="285" style="white-space: nowrap;" class="largCell">'.str_pad($linenumber, 2, "0", STR_PAD_LEFT). '. </label>';
		  		 		echo '<input name="storyLineId_'.$line->storylineId.'" type="text" value="'.$line->sentence.'"/>';  	
		  		 		//echo $line->text;
		  		 		echo '</td>
						<td width="500" style="white-space: nowrap;" class="veryLargCell">
						<input name="representationoldId_'.$line->storylineId.'" type="text" value="'.$line->representation.'"/>
						</td>						
						</tr>';
					 // echo '<input name="representationoldId_'.$aRep->representationId.'" type="text" value="'.$aRep->textold.'"/>'.'<br />';		 		
	  		 		/*if($line->text != '')
	  		 		{
	  		 			//set the representation of this line
	  		 			$representation = new Representation();
		  		 		$representation = $representation->GetList(array(array('storylineId', '=', $line->storylineId)));
		  		 		$representation = $representation[0];
		  		 		$representations[] = $representation;
		  		 		//display story lines inputs
		  		 		echo '<label>'.str_pad($linenumber, 2, "0", STR_PAD_LEFT). '. </label>';
		  		 		echo '<input name="storyLineId_'.$line->storylineId.'" type="text" value="'.$line->text.'"/>';  	
		  		 		//echo $line->text;
		  		 		echo '<br />';
		  		 		$linenumber++;
	  		 		}*/
	  		 $linenumber++;	}
  			}
  		 ?>

  	<!--<td width="500" style="white-space: nowrap;" class="veryLargCell">
  		vv<?  		
  		//echo var_dump($representations)	;
		
  			foreach ($representations as $aRep)
  		 	{ 
  		 	if(!empty($aRep->textold)) 	
  		 	{	
  		 		
  		 		echo '<input name="representationoldId_'.$aRep->storylineId.'" type="text" value="'.$aRep->textold.'"/>'.'<br />';
  		 	}
  		 	else
  		 	{
  		 		echo '<input name="representationoldId_'.$aRep->storylineId.'" type="text" value="'.$aRep->textold.'"/><a href=#>+</a>'.'<br />';
  		 	}
  		 	}
  		?>
  	</td>-->
	</table>
	<?php } ?>
 </div>
 </div>
 <div class="skmfloatlf">
 <ul class="idTabs"> 
  <li><a href="#contextrep">Context representation</a></li> 
  <li><a href="#qanda">Q & A</a></li>
 </ul>
 <div id="contextrep">
 <table>
 <?php 
  	if(count($stories)>0)
  	{
  	?>
 
	
  		<?  		
			if(isset($currStory))
  			{
  				//$storylines = $storylines->GetList(array(array('storyID', '=', $currStory->storyId)));
  		//echo var_dump($representations)	;
		//print_r($storylines);
		foreach ($storylines as $line)
	  		 	{
					if(!empty($line->context_rep)) 	
  		 	{	
  		 		
  		 		echo '<tr><td width="500" style="white-space: nowrap;" class="veryLargCell"><input name="representationId_'.$line->storylineId.'" type="text" value="'.$line->context_rep.'"/>'.'</td></tr>';
  		 	}
  		 	else
  		 	{
  		 		echo '<tr><td width="500" style="white-space: nowrap;" class="veryLargCell"><input name="representationId_'.$line->storylineId.'" type="text" value="'.$line->context_rep.'"/><a href=#>+</a>'.'</td></tr>';
  		 	}
				}
  			/*foreach ($representations as $aRep)
  		 	{ 
  		 	if(!empty($aRep->text)) 	
  		 	{	
  		 		
  		 		echo '<input name="representationId_'.$aRep->representationId.'" type="text" value="'.$aRep->text.'"/>'.'<br />';
  		 	}
  		 	else
  		 	{
  		 		echo '<input name="representationId_'.$aRep->representationId.'" type="text" value="'.$aRep->text.'"/><a href=#>+</a>'.'<br />';
  		 	}
  		 	}*/
			}
  		?>
  
 
 
 <?php } ?>
</table>
 </div>
 
 <div id="qanda">
 <table><tr>
 
 <?php 
  	if(count($stories)>0)
  	{
  	?>
 
 <td colspan="3">
  		<table>
  		<?php 
  		$storyRules = new StoryRule();
  		$storyRules = $storyRules->GetList(array(array('storyId', '=', $currStory->storyId)));
  		$ithRule=0;
  		foreach ($questions as $quest)
  		{
  			$textarea_height = '';
  			if(strlen($quest->statement) > 50)
  			{
  				$ht = ceil(strlen($quest->statement) / 10 ) - 2 ;
  				if($ht<2)	$ht = 2;
  				if($ht>4)	$ht = 4; 
  				$textarea_height = 'rows="'.$ht.'"';
  			}
  			?>
  			<tr>
  				<td>
  			<?php 
  			echo '<textarea '.$textarea_height.' name="questionId_'.$quest->questionId.'" cols="">'.$quest->statement .'</textarea><br />';
  			
  			//echo '<br />';
  			?>
  				</td>
  				
  				<?php
  					//$aRule = $storyRules[$ithRule];
  					$ithRule++;
  				?>
  				<td>
  					<textarea <?=$textarea_height ?> name="answerId_<?php echo $quest->questionId ?>" rows="" cols=""><?php echo $quest->answer ?></textarea> 
  				</td>
                <td>
  					<textarea <?=$textarea_height ?> name="storyruleId_<?php echo $quest->questionId ?>" rows="" cols=""><?php echo $quest->qarule ?></textarea> 
  				</td>
  			</tr>
  			<?php  
  		}
  		?>	
  		</table>  		
  	</td>
 
 
 <?php } ?>
 </tr></table>
 </div>
 </div>
 
<div class="skmsubmit">
<?php 
  	if(count($stories)>0)
  	{
  	?>
<table><tr><td colspan="6">
  		<input type="hidden" name="submitted" value="1" /> 
  		<input style="width: 75px;" type="submit" value="Save" />
  	</td></tr></table>
	<?php } ?>
</div> 

</form>
<p>&nbsp;</p>
	
	<!--<div class="skmadminl"><a href="admin.php?page=admin&tpl=dash">Admin</a></div>-->
<?php 
include 'tpl/footer.tpl.php';
?>



	
