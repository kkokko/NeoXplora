<?php
include 'tpl/header.tpl.php';
/* if(isset($_POST['submitted']))
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
  } */
?>

<!--form id="form1" name="form1" method="post" action="">
  <label>categories
  <select name="categoryId" id="categories" onchange="javascript:document.forms['form1'].submit();">
    <option value="0">All</option>
<?php
foreach ($categoryList as $aCat) {
    $selected = "";
    if ($selected_cat != 0 && $aCat->categoryId == $selected_cat) {
        $selected = 'selected="' . $selected_cat . '"';
    }
    ?>
                    <option value="<?= $aCat->categoryId ?>" <?= $selected ?>><?= $aCat->name ?></option>
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
    <table>
        <tr>
            <td>&nbsp </td>
            <td><?php if (isset($_POST['question'])) {
    echo '<strong>Last Target Sequence : &nbsp </strong>' . $_POST['question'];
} ?></td>
        </tr>
        <tr>
            <td>Target Sequence</td>
            <td>
                <input type="text" name="question" />
            </td>
        </tr>
        <tr>
            <td>&nbsp </td>
            <td>
                <input type="hidden" name="submitted" value="1" autofocus/> 
                <input style="width: 75px;" type="submit" value="Search" />
            </td>
        </tr>
    </table>
    <br /><br /><br />

    <table width="" border="1" cellspacing="1" cellpadding="1" class="sentTable">
        <tr>
            <th scope="col">Matches</th>
            <th scope="col" style="margin-left:30px;">Story Name</th>
            <th scope="col" style="padding-left:30px;padding-right:30px;min-width:30px;">Representation Guess</th>
            <th scope="col" style="padding-left:30px;padding-right:30px;min-width:30px;">Representation</th>
        </tr>
        <?php
        $matchArray = array();
        $titleArray = array();
        $repGuess = array();
        $repArray = array();

        if (isset($_POST['submitted'])) {
            //foreach ()
            $post = $_POST;
            if (!empty($post["question"])) {
                $tokens[] = explode(" ", $post["question"]);
                //echo var_dump($tokens);
                //$index = 0;
                $storylines = new StoryLine();
                $search = "";
                foreach ($tokens as $token1) {
                    $arcount = count($token1);
                    for ($i = 0; $i < $arcount; $i = $i + 1) {
                        if ($i != 0) {
                            $search = $search . "%') or";
                        }
                        $index = 0;
                        $ind = 0;
                        foreach ($token1 as $k => $token) {

                            if ($index != $i) {


                                if ($ind == 0) {
                                    $search = $search . " (`text` like  '%" . $token;
                                } else {
                                    $search = $search . " " . $token;
                                }
                                $ind = $ind + 1;
                            }
                            $index = $index + 1;
                        }
                    }
                    //echo $index;
                }

                $search = $search . "%')";

                //echo $search;
                $storylines1 = array();
                $storylines1[] = $storylines->GetBody($search);

                // echo var_dump($storylines1);
                foreach ($storylines1 as $st1) {

                    foreach ($st1 as $st) {
                        array_push($matchArray, htmlspecialchars(mysql_real_escape_string($st->text)));
                    }
                }
            } else {
                echo "Empty Question";
            }
        }
        ?>
        <?php
        if (isset($st1)) {
            foreach ($st1 as $st) {
                $Story = new Story();
                $stories[] = $Story->Get($st->storyId);

                foreach ($stories as $stories1) {
                    array_push($titleArray, htmlspecialchars(mysql_real_escape_string($stories1->title)));
                }

                unset($stories);
            }
        }
        ?>	
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
<?php
if (isset($_POST['submitted'])) {
    $post = $_POST;
    if (!empty($post["question"])) {

        $tokens[] = explode(" ", $post["question"]);
        $storylines = new StoryLine();
        $search = "";
        foreach ($tokens as $token1) {
            $search = $search . "%' or";
            $arcount = count($token1);
            for ($i = 0; $i < $arcount; $i = $i + 1) {
                if ($i != 0) {
                    $search = $search . "%' or";
                }
                $index = 0;
                $ind = 0;
                foreach ($token1 as $k => $token) {

                    if ($index != $i) {


                        if ($ind == 0) {
                            $search = $search . " sentence like  '%" . $token;
                        } else {
                            $search = $search . " " . $token;
                        }
                        $ind = $ind + 1;
                    }
                    $index = $index + 1;
                }
            }
            //echo $index;
        }

        $search = $search . "%'";
        $search_len = strlen($search);
        $search = substr($search, 5, $search_len);
       echo  $sql = "select * from sentenceTBL where " . $search . "";
									
		$result = mysql_query($sql);
		$m=0;
        while ($a = mysql_fetch_array($result)) {
		//echo '<pre>'; print_r($a);
            $story_text = explode(" ", $a[1]);
			if (isset($story_text[0])) {
                if ($story_text[0] == "") {
                    array_shift($story_text);
                   
                } else {
                   
                }
            }
            $target = $_POST['question'];
            $target = explode(" ", $target);
			$diff_in_target = array_diff($target, $story_text);
            $diff_in_match = array_diff($story_text, $target);
            //echo count($diff_in_match);
            //print_r($diff_in_target);
			//echo count($target);
			//$xym = $a[0]
			$yy = str_replace('p1.likes = ','',$a[0]);
			foreach($target as $mm){
				$yy = str_replace($mm,'', $yy);
			}
			
			$xy=0;
            for ($i = 0; $i <= count($target); $i++) {
                if (!empty($diff_in_target[$i]) && !empty($diff_in_match[$i])) {
                   // echo '1111------->>>'.$diff_in_target[$i];
					//echo '<br><br>';
                   // echo '2222------->>>'.$diff_in_match[$i];
					//echo '<br><br>';
					//echo '3333------->>>'.$a[0];
				//	echo '<br><br>';
					
                    $replace = str_replace($diff_in_match[$i], $diff_in_target[$i], $a[0]);
					//echo'<tr><td>';
					if (strpos($replace,$diff_in_target[$i]) !== false) {
					$replace1 = str_replace($yy, $diff_in_target[$i], $a[0]);
                    array_push($repGuess, $replace1);
					$xy=1;
					}
					//echo $m;
					//$m++;
//echo'</td></tr>';	
                }

                //echo $d.'   ';
            }
			if(empty($xy)){
				array_push($repGuess, " ");
				}
            //echo "<br>";
        }
		//die;
    }
}
//echo '<pre>'; print_r($repGuess);
?>

        <?php
        if (isset($_POST['submitted'])) {
            $post = $_POST;
            if (!empty($post["question"])) {

                $tokens[] = explode(" ", $post["question"]);
                $storylines = new StoryLine();
                $search = "";
                foreach ($tokens as $token1) {
                    $search = $search . "%' or";
                    $arcount = count($token1);
                    for ($i = 0; $i < $arcount; $i = $i + 1) {
                        if ($i != 0) {
                            $search = $search . "%' or";
                        }
                        $index = 0;
                        $ind = 0;
                        foreach ($token1 as $k => $token) {

                            if ($index != $i) {


                                if ($ind == 0) {
                                    $search = $search . " sentence like  '%" . $token;
                                } else {
                                    $search = $search . " " . $token;
                                }
                                $ind = $ind + 1;
                            }
                            $index = $index + 1;
                        }
                    }
                    //echo $index;
                }

                $search = $search . "%'";
                $search_len = strlen($search);
                $search = substr($search, 5, $search_len);
                $sql = "select * from sentenceTBL where " . $search . "";

                $result = mysql_query($sql);

                while ($a = mysql_fetch_array($result)) {

                    array_push($repArray, $a['text']);
                }

                /*
                  $tokens = explode(" ", $post["question"]);
                  $search ="";
                  foreach ($tokens as $token1)
                  {
                  $arcount = count($token1);
                  for($i=0;$i<$arcount;$i=$i+1){
                  $search = $search ."storyline.text like '%".$token1."%' or ";
                  }

                  }
                  $search_len = strlen($search);
                  $search = substr($search, 0 , $search_len-3);
                  $sql = "select representation.text from storyline join representation
                  on storyline.storylineid=representation.storylineid
                  where ".$search."";

                  $result = mysql_query($sql);
                  while($a = mysql_fetch_array($result)){
                  echo $a[0].'<br>';
                  }
                 */
            }
        }
		//echo '<pre>'; print_r($repArray);
		//echo '<pre>'; print_r($repGuess); die;
		foreach ($matchArray as $key => $value) {

            echo '<tr>';
            if (isset($matchArray[$key])) {
                echo'<td style="border:1px solid black;">' . $matchArray[$key] . '</td>';
            } else {
                echo'<td style="border:1px solid black;"></td>';
            }
            if (isset($titleArray[$key])) {
                echo'<td style="border:1px solid black;">' . $titleArray[$key] . '</td>';
            } else {
                echo'<td style="border:1px solid black;"></td>';
            }
            if (isset($repGuess[$key])) {
                echo'<td style="border:1px solid black;">' . $repGuess[$key] . '</td>';
            } else {
                echo'<td style="border:1px solid black;"></td>';
            }
            if (isset($repArray[$key])) {
                echo'<td style="border:1px solid black;">' . $repArray[$key] . '</td>';
            } else {
                echo'<td style="border:1px solid black;"></td>';
            }
            echo '</tr>';
        }
        ?>


    </table>
</form>
<p>&nbsp;</p>

<a href="admin.php?page=admin&tpl=dash">Admin</a>
        <?php
        include 'tpl/footer.tpl.php';
        ?>