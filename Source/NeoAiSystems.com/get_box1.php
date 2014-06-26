<?php



include 'PosTagger.php';

include 'finediff.php';



function POSsimfn($pos1,$pos2)
{
$i=0;

while (substr($pos1, $i,1) == substr($pos2,$i,1 ) and $i < min(strlen($pos1), strlen($pos2))) 
{ 
$i++;
}

$score = $i/max(strlen($pos1), strlen($pos2));
return $score;

}

//--------------------------------------------------------------------------------------------------
/**
 * @brief Split string into array of tokens using whitespace as the delimiter
 *
 * @param str String to be tokenised
 *
 * @return Array of tokens
 */
function tokenise_string($str)
{
        return preg_split("/[\s]+/", $str);
}



/*
 * similarity() will return similarity in %
 *  e.g. 50%
 */
//print_r(similarity($_REQUEST['rep'],$_REQUEST['guessrep']));
function similarity($rep, $guessrep) {
usleep(15);
    if (stripos($rep, ';')) {
        $repExplodArray = explode(';', $rep);
    } elseif (stripos($rep, ',')) {
        $repExplodArray = explode(',', $rep);
    } else {
        $repExplodArray = array($rep);
    }
    if (stripos($guessrep, ';')) {
        $gpExplodArray = explode(';', $guessrep);
    } elseif (stripos($guessrep, ',')) {
        $gpExplodArray = explode(',', $guessrep);
    } else {
        $gpExplodArray = array($guessrep);
    }
    $rep_part_count = count($repExplodArray);
    $matchcount = 0;
    foreach ($repExplodArray as $key => $value) {
        $value = trim($value);
        for ($j = 0; $j < count($gpExplodArray); $j++) {
            $gpExplodArray[$j] = trim($gpExplodArray[$j]);
            if ($value === $gpExplodArray[$j]) {
                $matchcount = $matchcount + 1;
            }
        }
    }
    $similarityRatio = $matchcount / $rep_part_count;
    $percentage=($similarityRatio*100).'%';
	
//	//Something to write to txt log
//	$log  = "date: ".date("F j, Y, g:i a").PHP_EOL.
//        "Guessrep: ". $guessrep.PHP_EOL.
//        "rep: ". $rep.PHP_EOL.		
//        "-------------------------".PHP_EOL;
////Save string to log, use FILE_APPEND to append.
//	file_put_contents('./log_'.date("j.n.Y").'.txt', $log, FILE_APPEND);

    return $percentage;
}

// little helper function to print the results

function getTag($tags) {

    $html = '';

    foreach ($tags as $t) {

        $html .= $t['token'] . "/" . $t['tag'] . " ";

    }

    $html .= "\n";

    return $html;

}

function bubble_sort3(&$arr, &$also1, &$also2, &$also3) {

    $size = count($arr);

    for ($i = 0; $i < $size; $i++) {

        for ($j = 0; $j < $size - 1 - $i; $j++) {

            if ($arr[$j + 1] > $arr[$j]) {

                swap($arr, $j, $j + 1);

                swap($also1, $j, $j + 1);

                swap($also2, $j, $j + 1);

                swap($also3, $j, $j + 1);

            }

        }

    }

    //return $arr;

}

function swap(&$arr, $a, $b) {

    $tmp = $arr[$a];

    $arr[$a] = $arr[$b];

    $arr[$b] = $tmp;

}

function SubFromDffs($sentenceI, $searchterm, $repI, &$diffHTML, &$subs, &$guess_repI) {    //



    $diff = FineDiff::getDiffOpcodes(trim($sentenceI), $searchterm, " \t.\n\r");

    $diffHTML = " " . FineDiff::renderDiffToHTMLFromOpcodes($sentenceI, $diff);





    $needle = "<del>";

    $lastPos = 0;

    $delpos = array();

    while ($lastPos = strpos($diffHTML, $needle, $lastPos)) {

        $delpos[] = $lastPos;

        $lastPos = $lastPos + strlen($needle);

    }

    $needle = "</del>";

    $lastPos = 0;

    $udelpos = array();

    while ($lastPos = strpos($diffHTML, $needle, $lastPos)) {

        $udelpos[] = $lastPos;

        $lastPos = $lastPos + strlen($needle);

    }

    $needle = "<ins>";

    $lastPos = 0;

    $inspos = array();

    while ($lastPos = strpos($diffHTML, $needle, $lastPos)) {

        $inspos[] = $lastPos;

        $lastPos = $lastPos + strlen($needle);

    }

    $needle = "</ins>";

    $lastPos = 0;

    $uinspos = array();

    while ($lastPos = strpos($diffHTML, $needle, $lastPos)) {

        $uinspos[] = $lastPos;

        $lastPos = $lastPos + strlen($needle);

    }



    $subs = "";

    $sub = array();

    $for = array();

    for ($k = 0; $k < max(count($delpos), count($inspos)); $k++) {

        $for[$k] = trim(substr($diffHTML, $delpos[$k] + 5, $udelpos[$k] - $delpos[$k] - 5));

        $sub[$k] = trim(substr($diffHTML, $inspos[$k] + 5, $uinspos[$k] - $inspos[$k] - 5));

        $subs .= $for[$k] . " <-> " . $sub[$k] . " | ";

    }



    $guess_repI = $repI;

    for ($k = 0; $k < count($delpos); $k++) {

        $old_guess = $guess_repI;

        $guess_repI = str_replace($for[$k], $sub[$k], $guess_repI);

        $fork = explode(" ", $for[$k]);

        $subk = explode(" ", $sub[$k]);

        if ($old_guess == $guess_repI && count($fork) == count($subk) && count($fork) > 1) {

            for ($m = 0; $m < count($fork); $m++) {

                $guess_repI = str_replace($fork[$m], $subk[$m], $guess_repI);

            }

        }

    }



    if ($guess_repI == $repI) {

        $guess_repI = "";

    }



    $subtot = 0;

    for ($k = 0; $k < count($sub); $k++) {

		$pos = strpos(" " . $sub[$k], $for[$k]);

		$pos1 = strpos(" " . $for[$k], $sub[$k]);

	

        if ($pos === false or $pos1 === false) {

            $subtot += str_word_count($sub[$k]);

        } else {

            $subtot += abs(str_word_count($sub[$k]) - str_word_count($for[$k]) - 1) / 2;

            //echo "Insubtot ".trim($sentenceI)." ".$sub[$k]." ".$for[$k]."</br>";			

        }

    }

    $fortot = 0;

    for ($k = 0; $k < count($for); $k++) {

		$pos = strpos(" " . $sub[$k], $for[$k]);

		$pos1 = strpos(" " . $for[$k], $sub[$k]);



	

        if ($pos === false  or  $pos1 === false) {

            $fortot += str_word_count($for[$k]);

        } else {

            $fortot += abs(str_word_count($for[$k]) - str_word_count($sub[$k]) - 1) / 2;

            //echo "Infortot ".trim($sentenceI)." ".$sub[$k]." ".$for[$k]."</br>";

        }

    }



    $scoreI = (1 - ($subtot + $fortot) / (str_word_count($searchterm) + str_word_count($sentenceI))) * 100;



    if (trim($sentenceI) == "") {

        $scoreI = 0;

    }



    return $scoreI;

}

//end  SubFromDffs

function posHTMLfn($searchterm1) {

    $tagger = new PosTagger('lexicon.txt');



    $tags = $tagger->tag(trim($searchterm1));

    $postags = getTag($tags);

    //echo $postags."<br>";				



    $startk = -1;

    $endk = -1;

    $posHTML = "";

    for ($k = 0; $k < strlen($postags); $k++) {

        //$pos .= $k;

        if (substr($postags, $k, 1) == "/") {

            $startk = $k + 1;

        }

        if (substr($postags, $k, 1) == " ") {

            $endk = $k - 1;

        }

        if ($k == strlen($postags) - 1) {

            $endk = $k - 1;

        }

        if ($startk <> -1 && $endk <> -1) {

            $posHTML .= substr($postags, $startk, $endk - $startk + 1) . " ";

            $startk = -1;

            $endk = -1;

        }

    } //$k			



    $posHTML = trim($posHTML);



    $html = '';

    for ($k = 0; $k < strlen($posHTML); $k++) {

        if (ord(substr($posHTML, $k, 1)) <> 10) {

            $html .= substr($posHTML, $k, 1);

        }

    }

    return $html;

}
//end posHTMLfn
function permutation_of_2($sentence) {
    /* For (1 2 3 4 5 )
     * will return combinations  
     * 12 13 14 15 23 24 25 34 35 45  and crete a WHERE condition
     * where 1 2 3 4 5 can be any word
     * 
     * Example
     * <!-- INPUT=I am happy
     * 
     * OUTPUT=(`sentence` LIKE "% I %" AND `sentence` LIKE "% am %") OR (`sentence` LIKE "% I %" AND `sentence` LIKE "% happy %") OR (`sentence` LIKE "% am %" AND `sentence` LIKE "% happy %") 
     */
    $tmp1 = explode(' ', $sentence);
    $tmp2=array_filter(array_map('trim', $tmp1));
    $retunt_str = '';
    $tmp_len = count($tmp2);
    $flag_first = TRUE;//  First step of loop
    for ($i = 0; $i < $tmp_len; $i++) {
        if (($i + 1) <= $tmp_len) {
            for ($j = $i + 1; $j < $tmp_len; $j++) {
                if ($flag_first == TRUE) {
                    $retunt_str.='(`sentence` LIKE "%' . $tmp1[$i] .'%" AND `sentence` LIKE "%' . $tmp1[$j] . '%") ';
                    $flag_first = FALSE;
                } else {
                    $retunt_str.='OR (`sentence` LIKE "%' . $tmp1[$i] . '%" AND `sentence` LIKE "%' . $tmp1[$j] . '%") ';
                }
            }
        }
    }
    return $retunt_str;
}

function neighbour_algo_of_2($sentence) {
    /* For (1 2 3 4 5 )
     * will return combinations  
     * 12 23 34 45  and crete a WHERE condition
     * where 1 2 3 4 5 can be any word
     * 
     * Example
     * <!-- INPUT=My friend lives in California
     * 
     * OUTPUT=(`sentence` LIKE "%My friend%") OR (`sentence` LIKE "%friend lives%") OR (`sentence` LIKE "%lives in%") OR (`sentence` LIKE "%in California%") 
     */
    $tmp1 = explode(' ', $sentence);
    $tmp2 = array_filter(array_map('trim', $tmp1));
    $retunt_str = '';
    $tmp_len = count($tmp2);
    $flag_first = TRUE; //  First step of loop
    for ($i = 0; $i < $tmp_len; $i++) {
        if (($i + 1) < $tmp_len) {

            if ($flag_first == TRUE) {
                $retunt_str.='(`sentence` LIKE "%' . $tmp1[$i] . ' ' . $tmp1[$i + 1] . '%") ';
                $flag_first = FALSE;
            } else {
                $retunt_str.='OR (`sentence` LIKE "%' . $tmp1[$i] . ' ' . $tmp1[$i + 1] . '%") ';
            }
        }
    }
    return $retunt_str;
}

function posSWfn ($str1, $str2, &$alignIN, &$alignOUT)  //, &$html
{
        $score = 0.0;
       
        // Weights
        $match          = 2;
        $mismatch       = -1;
        $deletion       = -1;
        $insertion      =-1;
       
        // Tokenise input strings, and convert to lower case
        $X = tokenise_string('cat dog '.$str1.' cat dog');
        $Y = tokenise_string('cat dog '.$str2.' cat dog');
       
		$posHTML1 = posHTMLfn('cat dog '.$str1.' cat dog');
//echo "<br>";
 		$posHTML2= posHTMLfn('cat dog '.$str2.' cat dog');

		$pos1 = explode(" ", $posHTML1);
		$pos2 = explode(" ", $posHTML2);
	   
	   
	
/**/////////////////////////////
		$gap=-1;
		$score=array();
		$pointer=array();
       
		for($i=0;$i<=count($pos1);$i++)
		{
			$score[$i]=array();
			$pointer[$i]=array();
			for($j=0;$j<=count($pos2);$j++)
			{
				$score[$i][$j]=0;
				$score[$i][$j]="none";
			}
		}

       
	   
	   	$maxi=0;
		$maxj=0;
		$max_score=0;
		//echo count($pos2);
		for($i=1;$i<=count($pos1);$i++)//Logic to construct the matrix and find maximum edit distance
		{
			for($j=1;$j<=count($pos2);$j++)
			{
				$diagonal=-1;
				$left=-1;
				$up=-1;
			 $letter1=$pos1[$i-1];
			 $letter2=$pos2[$j-1];
//				if($letter1==$letter2)
				if(strcmp ( $letter1 , $letter2 )==0)
				{	
					$diagonal=$score[$i-1][$j-1]+$match;
					}
				else{
				 if (!POSsimfn ($letter1, $letter2) == 0)	{
				 
//				 	echo "<br> match score = ". POSsimfn ($letter1, $letter2)." ";
					$diagonal=$score[$i-1][$j-1]+1; }
				else 
					$diagonal=$score[$i-1][$j-1]+$mismatch;
				}					
				
				$left=$score[$i][$j-1]+$gap;
				$up=$score[$i-1][$j]+$gap;
				if($diagonal<=0 && $left<=0 && $up<=0)
				{
					$score[$i][$j]=0;
					$pointer[$i][$j]="none";
					continue;
				}
				if($diagonal>=$up)
				{
					if($diagonal>=$left)
					{
						$score[$i][$j]=$diagonal;
						$pointer[$i][$j]="diagonal";
					}
					else
					{
						$score[$i][$j]=$left;
						$pointer[$i][$j]="left";	
					}
				}
				else
				{
					if($up>=$left)
					{
						$score[$i][$j]=$up;
						$pointer[$i][$j]="up";
					}
					else
					{
						$score[$i][$j]=$left;
						$pointer[$i][$j]="left";
					}
				}
				if($score[$i][$j]>$max_score)
				{
					$maxi=$i;
					$maxj=$j;
					$max_score=$score[$i][$j];
				}
			}
		}

//echo "<br>";
//echo $maxi;
//echo "<br>";
//echo $maxj;
//echo "<br>";
$maximum_possible_score = count($pos2) * $match;
$score= $max_score/$maximum_possible_score;
//echo "<br> score = ". $score ." <br>"	;   
//echo "Score matrix <br>";
//displayarray($score);
//echo "<br>";
//echo "<br>pointer";
//displayarray1($pointer);
//echo "<br>";

		$align1="";
		$align2="";
		$count=0;
		$in=array();
		$out=array();
	
//$maxi=9;	
//$maxj=7;	
		while($maxi>0 && $maxj>0)//Trace-back
		{
			$count++;
			if($pointer[$maxi][$maxj]=="none") break;
			else if($pointer[$maxi][$maxj]=="diagonal")
			{
//			    echo $s1[$maxi-1]. '  '. $s2[$maxj-1].'<br>';
array_unshift($in,$X[$maxi-1]);
array_unshift($out,$Y[$maxj-1]);
				$align1=$pos1[$maxi-1].' '.$align1;
				$align2=$pos2[$maxj-1]. ' '.$align2;
				$maxi--;
				$maxj--;
			}
			else if($pointer[$maxi][$maxj]=="left")
			{
//			    echo "---". '  '. $s2[$maxj-1].'<br>';
//array_unshift($in,"-");
//array_unshift($out,$pos2[$maxj-1]);
				
				$align2=$pos2[$maxj-1].' ' .$align2;
				$align1="-".' '.$align1;
				$maxj--;
			}
			else if($pointer[$maxi][$maxj]=="up")
			{
//			    echo $s1[$maxi-1]. '  '. "---".'<br>';			
//array_unshift($in,$pos1[$maxi-1]);
//array_unshift($out,"-");
				
				$align1=$pos1[$maxi-1].' '.$align1;
				$align2="-". ' ' .$align2;
				$maxi--;
			}
		}
		



array_pop($in);
array_pop($in);

array_pop($out);
array_pop($out);

array_shift($in);
array_shift($in);
array_shift($out);
array_shift($out);




		$html[0]=$align1;
		$html[1]=$align2;
//		$html[2]=$in;		
//		$html[3]=$out; 
$alignIN=$in;
$alignOUT=$out;

       
        return $score;
}
?>
<Table id="tbl" width="100%" >
<tr><td>new-rep</td><td>alignIN</td><td>alignOUT</td></tr>

<?php 
function GrepFromAlign($rep, $alignIN, $alignOUT)
{
	$newrep=$rep;
	for ($i=0;$i<count($alignIN);$i++)
	{
		 if (strcmp ($alignIN[$i], $alignOUT[$i])==0)
		 {
			 continue;
		 }else 
		 {
		 $newrep=str_replace($alignIN[$i],$alignOUT[$i],$newrep);
		 }
		 
	}
?>
<tr><td><?php if (empty($newrep)) 
					echo "Empty";
			 else 
	echo  $newrep;
	?></td><td><?php for ($i=0;$i<count($alignIN);$i++)
	{
	echo " ". $alignIN[$i];
	} ?></td><td><?php for ($i=0;$i<count($alignOUT);$i++)
	{
	echo " ". $alignOUT[$i];
	} ?></td></tr>
<?php 	
	return $newrep;
}


function repguessfn($insentence, $subset) {

	if ($subset == '100%') {$istep = 1;}

	if ($subset == '50%') {$istep = 2;}

	if ($subset == '25%') {$istep = 4;}

    //echo $insentence."</br>";

    //1. DB CONNECTION

    @ $db = new mysqli('127.0.0.1', 'userneoai', 'login141', 'db179668_ai2');



    if (mysqli_connect_errno()) {

        echo 'Error: Could not connect to database.  Please try again later.';

        exit;

    } else {/* echo 'Got in'; */

    }



    $request = $_POST['request'];

   $tmp1 = explode(' ', $insentence);
    $tmp2=array_filter(array_map('trim', $tmp1));
    $tmp_len = count($tmp2);
    if($tmp_len>1){
        
     //-----------------Select algorithm here- Comment which you don't wish to use.. ----------------------   
       //$where_condition=permutation_of_2($insentence);    //Algo Type:Permutation 
       $where_condition=neighbour_algo_of_2($insentence);     //Algo type:Neighbour
  //-------------------------------------------------------------------     
    }else{  //  Single word
        $where_condition='`sentence` LIKE "%'.$tmp2[0].'%"';
    }
 //Get DB into array
//
    $query = "select * from sentence WHERE ".$where_condition;
  // echo $query; die;
//    $query = "select * from sentence";

  // $result = $db->query($query);    
   $result = $db->query($query);
    

    $num_results = $result->num_rows;

   


    for ($i = 0; $i < $num_results; $i++) {

        $row = $result->fetch_assoc();

        $senID[$i] = $row['sentenceID'];

        $sentence[$i] = $row['sentence'];

        $rep[$i] = $row['representation'];

        $POS2[$i] = $row['POS'];

    } //$i

    

    //2. Score by text match

    $searchterm = trim($insentence) . " ";

	$j =0;

    for ($i = 0; $i < $num_results; $i += $istep) {		

        $senIDTEXT[$j] = $senID[$i];

        $sentenceCOPY[$j] = trim($sentence[$i]) . " ";

        $score[$j] = SubFromDffs($sentenceCOPY[$j], $searchterm, $rep[$i], $junk1, $junk2, $guess_rep[$j]);

		$j++;

    } //$j 

    $jmax = $j;

    

    //3. Score by POS



    $posHTML = posHTMLfn($searchterm);

	$j =0;	

    for ($i = 0; $i < $num_results; $i += $istep) {

        $senIDPOS[$j] = $senID[$i];

        $sentenceCOPYPOS[$j] = trim($sentence[$i]) . " ";        

        $scorePOS[$j] = SubFromDffs($POS2[$i], $posHTML, $rep[$i], $junk1, $junk2, $junk3);

        $guess_repPOS[$j] = $guess_rep[$i];

		$j++;        

    } //$j

    $jmax = $j;



	//4. HYBRID score by text match + POS

    for ($j = 0; $j < $jmax +1; $j++) {

		$senIDHYBRID[$j] = $senIDTEXT[$j];

        $sentenceCOPYHYBRID[$j] = trim($sentenceCOPY[$j]) . " ";        

        $scoreHYBRID[$j] = $score[$j] + $scorePOS[$j];

        $guess_repHYBRID[$j] = $guess_rep[$j];

        $j++;

    } //$j




    //5. Score by text match

    $searchterm = trim($insentence) . " ";

	$j =0;

    for ($i = 0; $i < $num_results; $i += $istep) {		

        $senIDSW[$j] = $senID[$i];

        $sentenceSW[$j] = trim($sentence[$i]) . " ";

        $scoreSW[$j] = posSWfn($searchterm,$sentenceSW[$j],$alignIN, $alignOUT);;
   	    
		$guess_repSW[$j] = GrepFromAlign($guess_rep[$j],$alignIN, $alignOUT);
		
	//	if (strcmp ($alignIN, $alignOUT)==0) $scoreSW[$j]=0;
	  
		$j++;

    } //$j 

    $jmax = $j;



    bubble_sort3($score, $sentenceCOPY, $guess_rep, $senIDTEXT);

    bubble_sort3($scorePOS, $sentenceCOPYPOS, $guess_repPOS, $senIDPOS);

    bubble_sort3($scoreHYBRID, $sentenceCOPYHYBRID, $guess_repHYBRID, $senIDHYBRID);
	
    bubble_sort3($scoreSW, $sentenceSW, $guess_repSW, $senIDSW);	//  Session 5



    $bestguess = array();



    $k = 0;

	$bestguess[1] = $guess_rep[$k];

	$bestguess['match1'] = $sentenceCOPY[$k];

	$bestguess['senID1'] = $senIDTEXT[$k];



	while ($guess_rep[$k] == "" && $k < $num_results) {

	$k++;

	$bestguess[1] = $guess_rep[$k];

	$bestguess['match1'] = $sentenceCOPY[$k];

	$bestguess['senID1'] = $senIDTEXT[$k];


	}



	$k = 0;

	$bestguess[0] = $guess_repPOS[$k];

	$bestguess['match2'] = $sentenceCOPYPOS[$k];

	$bestguess['senID2'] = $senIDPOS[$k];



	while ($guess_repPOS[$k] == "" && $k < $num_results) {

	$k++;

	$bestguess[0] = $guess_repPOS[$k];

	$bestguess['match2'] = $sentenceCOPYPOS[$k]; //2

	$bestguess['senID2'] = $senIDPOS[$k];


	}



	$k = 0;

	$bestguess[2] = $guess_repHYBRID[$k];

	$bestguess['match3'] = $sentenceCOPYHYBRID[$k];

	$bestguess['senID3'] = $senIDHYBRID[$k];


	while ($guess_repHYBRID[$k] == "" && $k < $num_results) {

	$k++;

	$bestguess[2] = $guess_repHYBRID[$k];

	$bestguess['match3'] = $sentenceCOPYHYBRID[$k]; //2

	$bestguess['senID3'] = $senIDHYBRID[$k];
        
	}	


// session 5 


    $k = 0;

	$bestguess[3] = $guess_repSW[$k];

	$bestguess['match4'] = $sentenceSW[$k];

	$bestguess['senID4'] = $senIDSW[$k];



	while ($guess_repSW[$k] == "" && $k < $num_results) {

	$k++;

	$bestguess[3] = $guess_repSW[$k];

	$bestguess['match4'] = $sentenceSW[$k];

	$bestguess['senID4'] = $senIDSW[$k];


	}











$bestguess['count']=$num_results;
    return $bestguess;

}



@ $db = new mysqli('127.0.0.1', 'userneoai', 'login141', 'db179668_ai2');



if (mysqli_connect_errno()) {

    echo 'Error: Could not connect to database.  Please try again later.';

    exit;

} else {/* echo 'Got in'; */

}



$id = 1;// $_REQUEST['entry_id'];

$cat = $_REQUEST['entry_cat'];  // story ID

$stncId=$_REQUEST['sid'];

$percent='100%';//$_REQUEST['persnt'];

if ($cat == "")

    $cat = 1;

else

    $cat = intval($cat);



//echo 'story'. $cat;	



if ($id==1)	{



// 	$query = "select * from sentence where storyID= '" . $cat."' and sentenceID = '".trim($stncId)."'";



//	$result = $db->query($query);





//	for ($i = 0; $i < intval($id); $i++) {

	

//	$row = $result->fetch_assoc();

//	}







	$bestREPguess = repguessfn($_REQUEST['Sentence'],$percent);

	 $Option2 = $bestREPguess[0];  // 2

	 $Option3 = $bestREPguess[1];  //3

	 $Option4 = $bestREPguess[2];  // 

	 $Option8 = $bestREPguess[3];  // 	 session 5

	 

	 $Option5 = $bestREPguess['match1'];  //4

	 $Option6 = $bestREPguess['match2'];  // 5

	 $Option7 = $bestREPguess['match3'];  //  

	 $Option9 = $bestREPguess['match4'];  //  	 session 5 
	 
	 

	$senID1 = $bestREPguess['senID1'];  //  
	$senID2 = $bestREPguess['senID2'];  //  
	$senID3 = $bestREPguess['senID3'];  //  		
	$senID4 = $bestREPguess['senID4'];  //  			


	$html = $row['representation'] . ";" . $Option2 . ";" . $Option3;


 	$query2 = "UPDATE `sentence` SET `repguess1` = \"" . $Option2 . "\",

	`repguess2` = \"" . $Option3 . "\" ,

	`repguess3` = \"" . $Option4 . "\" ,

	`repguess4` = \"" . $Option8 . "\" ,	

	`guesses2` = \"" . $Option5 . "\",

	`guesses1` = \"" . $Option6 . "\",
	`guesses3` = \"" . $Option7 . "\",
	`guesses4` = \"" . $Option9 . "\",	
	
	`sntid1` = \"" . $senID1 . "\",
	`sntid2` = \"" . $senID2 . "\",			

	`sntid3` = \"" . $senID3 . "\",
	`sntid4` = \"" . $senID4 . "\"  WHERE `sentence`.`sentenceID` =" . $stncId;

//	$result2 = $db->query($query2);

	echo json_encode($bestREPguess);

}

else if ($id>1) {

//do something 



 	

$query = "select * from sentence where pageID= '" . $cat."' and sentenceID = '".trim($stncId)."'";



	$result = $db->query($query);





//	for ($i = 0; $i < intval($id); $i++) {

	

	    $row = $result->fetch_assoc();

if (!empty($row)) {



		$bestREPguess = repguessfn($row['sentence'],$percent);

		$Option2 = $bestREPguess[0];  // 2

		$Option3 = $bestREPguess[1];  //3

		$Option4 = $bestREPguess[2];  // 

		$Option8 = $bestREPguess[3];  // 		session 5 

	 

	 	$Option5 = $bestREPguess['match1'];  //4

		$Option6 = $bestREPguess['match2'];  // 5

	 	$Option7 = $bestREPguess['match3'];  //  

	 	$Option9 = $bestREPguess['match4'];  //  		Session 5

	 	$senID1 = $bestREPguess['senID1'];  //  
	$senID2 = $bestREPguess['senID2'];  //  
	$senID3 = $bestREPguess['senID3'];  //  		
	$senID4 = $bestREPguess['senID4'];  //  			Session 5 


		$html = $row['representation'] . ";" . $Option2 . ";" . $Option3;

	

		$query2 = "UPDATE `sentence` SET `repguess1` = \"" . $Option2 . "\",

		`repguess2` = \"" . $Option3 . "\" ,

		`repguess3` = \"" . $Option4 . "\" ,
		`repguess4` = \"" . $Option8 . "\" ,		

		`guesses2` = \"" . $Option5 . "\",

		`guesses1` = \"" . $Option6 . "\",

	`guesses3` = \"" . $Option7 . "\",
	`guesses4` = \"" . $Option9 . "\",	
	
	`sntid3` = \"" . $senID1 . "\",
	`sntid1` = \"" . $senID2 . "\",			

	`sntid2` = \"" . $senID3 . "\",
	`sntid4` = \"" . $senID4 . "\"  WHERE `sentence`.`sentenceID` =" . $row['sentenceID'];

		$result2 = $db->query($query2);		

	}		

		

//	}

/************************************/


$guessrep1=array();
$guessrep2=array();

$story_data = "SELECT * from sentence where pageID='$cat'";
$result_story_data =  $db->query($story_data); //mysql_query($story_data) or die('MySql Error' . mysql_error());        /* START CREP */ 

while ($rows = $result_story_data->fetch_assoc()) {
      if (!empty($rows['sentence'])) {

//	   $rows['representation']
//	   $rows['repguess1']
//	   $rows['repguess2']
$guessrep1[]= str_replace('%','',similarity($rows['representation'],  $rows['repguess1']));
$guessrep2[]= str_replace('%','',similarity($rows['representation'],  $rows['repguess2']));
$guessrep3[]= str_replace('%','',similarity($rows['representation'],  $rows['repguess3']));
	  
	  }
}


$average1 = array_sum($guessrep1) / count($guessrep1);
$average2 = array_sum($guessrep2) / count($guessrep2);
$average3 = array_sum($guessrep3) / count($guessrep3);

//echo $percent;
//echo $cat;
if ($percent=='25%'){
 $str="UPDATE page SET text25='$average1',pos25='$average2',hybrid25='$average3',persnt='$percent' WHERE pageID='$cat'";
}else if ($percent=='50%'){
$str="UPDATE page SET text50='$average1',pos50='$average2',hybrid50='$average3',persnt='$percent' WHERE pageID='$cat'";
}else if ($percent=='100%'){
$str="UPDATE page SET text100='$average1',pos100='$average2',hybrid100='$average3',persnt='$percent' WHERE pageID='$cat'";
}

	$result = $db->query($str);
// $chk = mysql_query($str);
//    if ($chk) {
      //  echo '1';
//    } else {
//        echo '0';
//    }


/**************************************/




	echo json_encode($bestREPguess);

}

?>
</Table>