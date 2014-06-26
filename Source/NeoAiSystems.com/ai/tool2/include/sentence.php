<?php 
include 'tpl/header.tpl.php';
/*if(isset($_POST['submitted']))
{
	//foreach ()
	$post = $_POST;
	if(!empty($post["question"])){
$tokens[] = explode(" ", $post["question"]);
echo var_dump($tokens);
$index = 0;
$storylines = new StoryLine();
foreach ($tokens as $token1)
{
foreach ($token1 as $k=>$token)
  		 	{ 
  		 		if($index == 0){
  		 		$search = " `text` like '%".$token."%'";
  		 }
else
{
	$search = $search. " or `text` like '%".$token."%'";
}
$index=$index+1;
echo $index;
  		 	}
  		 }
  		 	
$storylines1 =  array();
$storylines1[] = $storylines->GetBody($search);
  		 	echo var_dump($storylines1);
foreach ($storylines1 as $st1) {
	foreach ($st1 as $st) {

	echo $st->text;
}
}
}
else
{
	echo "Empty Question";
}
}*/
?>

	<!--form id="form1" name="form1" method="post" action="">
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
	    		<option value="<?=$aCat->categoryId ?>" <?=$selected ?>><?=$aCat->name ?></option>
	    	<?php 
	    } 
	    ?>
	  </select>
	  </label>
	  <input type="hidden" name="changeCat" value="1"/>
	</form-->
<p>&nbsp;</p>
<p>&nbsp;</p>
<form name="form2" method="post" action="">
<table width="" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <th width="100" scope="col">Matching Stories</th>
    <th scope="col">Matches</th>
  </tr>
  
  <tr>
  	<td width="90">
  		<input type="text" name="question" />
  	</td>
  	
  			
  	<td width="285" style="white-space: nowrap;" class="largCell">
  		<table>
  			<?php 
if(isset($_POST['submitted']))
{
	//foreach ()
	$post = $_POST;
	if(!empty($post["question"])){
$tokens[] = explode(" ", $post["question"]);
//echo var_dump($tokens);
$index = 0;
$storylines = new StoryLine();
foreach ($tokens as $token1)
{
foreach ($token1 as $k=>$token)
  		 	{ 
  		 		if($index == 0){
  		 		$search = " `text` like '%".$token."%'";
  		 }
else
{
	$search = $search. " or `text` like '%".$token."%'";
}
$index=$index+1;
//echo $index;
  		 	}
  		 }
  		 	
$storylines1 =  array();
$storylines1[] = $storylines->GetBody($search);
  		 //echo var_dump($storylines1);
foreach ($storylines1 as $st1) {
	foreach ($st1 as $st) {

	echo "<tr><td><input name='storyline".$st->id."' type='text' value='".$st->text."'/></td></tr>";
}
}
}
else
{
	echo "Empty Question";
}
}
?>
</table>
  	</td>
  	<!--td width="500" style="white-space: nowrap;" class="veryLargCell">
  		<!--?  			
  			foreach ($representations as $aRep)
  		 	{ 
  		 	if(!empty($aRep->text)) 	
  		 	{	
  		 		
  		 		echo '<input name="representationId_'.$aRep->representationId.'" type="text" value="'.$aRep->text.'"/>'.'<br />';
  		 	}
  		 	else
  		 	{
  		 		echo '<input name="representationId_'.$aRep->representationId.'" type="text" value="'.$aRep->text.'"/><a href=#>+</a>'.'<br />';
  		 	}
  		 	}
  		?>
  	</td>
  	<td colspan="3">
  		<table>
  		<!--?php 
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
  			<!--?php 
  			echo '<textarea '.$textarea_height.' name="questionId_'.$quest->questionId.'" cols="">'.$quest->statement .'</textarea><br />';
  			
  			//echo '<br />';
  			?>
  				</td>
  				<td>
  				<!--?php
  				 $answer = new Answer();
	  			$answer = $answer->GetList(array(array('questionId', '=', $quest->questionId)));
	  			$answer = $answer[0];
	  			//echo $answer->statement;
	  			//$textarea_height = '';
	  			if(strlen($answer->statement) > 70)
	  			{
	  				$ht = ceil(strlen($answer->statement) / 10 ) - 2 ;
	  				if($ht<2)	$ht = 2;
	  				if($ht>4)	$ht = 4;  
	  				//$textarea_height = 'rows="'.$ht.'"';
	  			}
	  				
	  			echo '<textarea '.$textarea_height.' name="answerId_'.$answer->answerId.'" rows="" cols="">'.$answer->statement .'</textarea>';
  				?>
  				</td>
  				<!--?php
  					$aRule = $storyRules[$ithRule];
  					$ithRule++;
  				?-->
  				<td>
  					<!--textarea <?=$textarea_height ?> name="storyruleId_<?=$aRule->storyruleId ?>" rows="" cols=""><?= $aRule->statement ?></textarea--> 
  				</td>
  			</tr>
  			<!--?php  
  		}
  		?>	
  		</table>  		
  	</td-->  	
  	  	
  </tr>
  <tr>
  	<td colspan="2">
  		<input type="hidden" name="submitted" value="1" /> 
  		<input style="width: 75px;" type="submit" value="Save" />
  	</td>
  	
  </tr>
</table>
</form>
<p>&nbsp;</p>
	
	<a href="admin.php?page=admin&tpl=dash">Admin</a>
<?php 
include 'tpl/footer.tpl.php';
?>
