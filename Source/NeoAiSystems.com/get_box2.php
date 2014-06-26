<?php

include 'PosTagger.php';
include 'finediff.php';

// little helper function to print the results
function getTag($tags) {
    $html = '';
    foreach ($tags as $t) {
        $html .= $t['token'] . "/" . $t['tag'] . " ";
    }
    $html .= "\n";
    return $html;
}

function bubble_sort3(&$arr, &$also1, &$also2) {
    $size = count($arr);
    for ($i = 0; $i < $size; $i++) {
        for ($j = 0; $j < $size - 1 - $i; $j++) {
            if ($arr[$j + 1] > $arr[$j]) {
                swap($arr, $j, $j + 1);
                swap($also1, $j, $j + 1);
                swap($also2, $j, $j + 1);
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

function repguessfn($insentence, $subset) {

//echo $insentence .'   ,   '. $subset;
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

    //Get DB into array
    $query = "select * from sentence";
    $result = $db->query($query);
    $num_results = $result->num_rows;
  //  echo $num_results."</br>";


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
        $sentenceCOPY[$j] = trim($sentence[$i]) . " ";
        $score[$j] = SubFromDffs($sentenceCOPY[$j], $searchterm, $rep[$i], $junk1, $junk2, $guess_rep[$j]);
		$j++;
    } //$j 
    $jmax = $j;
//	exit();    
    //3. Score by POS

    $posHTML = posHTMLfn($searchterm);
	$j =0;	
    for ($i = 0; $i < $num_results; $i += $istep) {
        $sentenceCOPYPOS[$j] = trim($sentence[$i]) . " ";        
        $scorePOS[$j] = SubFromDffs($POS2[$i], $posHTML, $rep[$i], $junk1, $junk2, $junk3);
        $guess_repPOS[$j] = $guess_rep[$i];
		$j++;        
    } //$j
    $jmax = $j;

	//4. HYBRID score by text match + POS
    for ($j = 0; $j < $jmax +1; $j++) {
        $sentenceCOPYHYBRID[$j] = trim($sentenceCOPY[$j]) . " ";        
        $scoreHYBRID[$j] = $score[$j] + $scorePOS[$j];
        $guess_repHYBRID[$j] = $guess_rep[$j];
        $j++;
    } //$j

    bubble_sort3($score, $sentenceCOPY, $guess_rep);
    bubble_sort3($scorePOS, $sentenceCOPYPOS, $guess_repPOS);
    bubble_sort3($scoreHYBRID, $sentenceCOPYHYBRID, $guess_repHYBRID);

    $bestguess = array();

    $k = 0;
	$bestguess[1] = $guess_rep[$k];
	$bestguess['match1'] = $sentenceCOPY[$k];

	while ($guess_rep[$k] == "" && $k < $jmax+1) {
	$k++;
	$bestguess[1] = $guess_rep[$k];
	$bestguess['match1'] = $sentenceCOPY[$k];
	}

	$k = 0;
	$bestguess[0] = $guess_repPOS[$k];
	$bestguess['match2'] = $sentenceCOPYPOS[$k];

	while ($guess_repPOS[$k] == "" && $k < $jmax+1) {

	$k++;
	$bestguess[0] = $guess_repPOS[$k];
	$bestguess['match2'] = $sentenceCOPYPOS[$k]; //2
	}

	$k = 0;
	$bestguess[2] = $guess_repHYBRID[$k];
	$bestguess['match3'] = $sentenceCOPYHYBRID[$k];

//	$num_results -> $jmax+1
	while ($guess_repHYBRID[$k] == "" && $k < $jmax+1) {
	$k++;
	$bestguess[2] = $guess_repHYBRID[$k];
	$bestguess['match3'] = $sentenceCOPYHYBRID[$k]; //2
	}	

    return $bestguess;
}

@ $db = new mysqli('127.0.0.1', 'userneoai', 'login141', 'db179668_ai2');

if (mysqli_connect_errno()) {
    echo 'Error: Could not connect to database.  Please try again later.';
    exit;
} else {/* echo 'Got in'; */
}

 $id = $_REQUEST['entry_id'];
$cat = $_REQUEST['entry_cat'];
$stncId=$_REQUEST['sid'];
$percent=$_REQUEST['persnt'];
if ($cat == "")
    $cat = 1;
else
    $cat = intval($cat);

//echo 'story'. $cat;	

if ($id==1)	{

  	$query = "select * from sentence where pageID= '" . $cat."' and sentenceID = '".trim($stncId)."'";

	$result = $db->query($query);


	for ($i = 0; $i < intval($id); $i++) {
	
	$row = $result->fetch_assoc();
	}



	$bestREPguess = repguessfn($row['sentence'],$percent);
	 $Option2 = $bestREPguess[0];  // 2
	 $Option3 = $bestREPguess[1];  //3
	 $Option4 = $bestREPguess[2];  // 
	 
	 $Option5 = $bestREPguess['match1'];  //4
	 $Option6 = $bestREPguess['match2'];  // 5
	 $Option7 = $bestREPguess['match3'];  //  
	 
	$html = $row['representation'] . ";" . $Option2 . ";" . $Option3;
	
	$query2 = "UPDATE `sentence` SET `repguess1` = \"" . $Option2 . "\",
	`repguess2` = \"" . $Option3 . "\" ,
	`repguess3` = \"" . $Option4 . "\" ,
	`guesses2` = \"" . $Option5 . "\",
	`guesses1` = \"" . $Option6 . "\",
	`guesses3` = \"" . $Option7 . "\"  WHERE `sentence`.`sentenceID` =" . $stncId;
	$result2 = $db->query($query2);
	echo json_encode($bestREPguess);
}
else if ($id>1) {
//do something 

echo 	$query = "select * from sentence where pageID= '" . $cat."' ";

	$result = $db->query($query);


	for ($i = 0; $i < intval($id); $i++) {
	
	    $row = $result->fetch_assoc();
if (!empty($row)) {

		$bestREPguess = repguessfn($row['sentence'],$percent);
		$Option2 = $bestREPguess[0];  // 2
		$Option3 = $bestREPguess[1];  //3
		$Option4 = $bestREPguess[2];  // 
	 
	 	$Option5 = $bestREPguess['match1'];  //4
		$Option6 = $bestREPguess['match2'];  // 5
	 	$Option7 = $bestREPguess['match3'];  //  
	 
		$html = $row['representation'] . ";" . $Option2 . ";" . $Option3;
	
		$query2 = "UPDATE `sentence` SET `repguess1` = \"" . $Option2 . "\",
		`repguess2` = \"" . $Option3 . "\" ,
		`repguess3` = \"" . $Option4 . "\" ,
		`guesses2` = \"" . $Option5 . "\",
		`guesses1` = \"" . $Option6 . "\",
		`guesses3` = \"" . $Option7 . "\"  WHERE `sentence`.`sentenceID` =" . $row['sentenceID'];
		$result2 = $db->query($query2);		
	}		
		
	}



	echo json_encode($bestREPguess);


}
?>