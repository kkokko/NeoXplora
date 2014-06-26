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
        if (strpos(" " . $sub[$k], $for[$k]) > 0 or strpos(" " . $for[$k], $sub[$k]) > 0) {
            $subtot += abs(str_word_count($sub[$k]) - str_word_count($for[$k]) - 1) / 2;
            //echo "Insubtot ".trim($sentenceI)." ".$sub[$k]." ".$for[$k]."</br>";
        } else {
            $subtot += str_word_count($sub[$k]);
        }
    }
    $fortot = 0;
    for ($k = 0; $k < count($for); $k++) {
        if (strpos(" " . $sub[$k], $for[$k]) > 0 or strpos(" " . $for[$k], $sub[$k]) > 0) {
            $fortot += abs(str_word_count($for[$k]) - str_word_count($sub[$k]) - 1) / 2;
            //echo "Infortot ".trim($sentenceI)." ".$sub[$k]." ".$for[$k]."</br>";
        } else {
            $fortot += str_word_count($for[$k]);
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

function repguessfn($insentence) {

    //echo $insentence."</br>";
    //1. DB CONNECTION
    @ $db = new mysqli('127.0.0.1', 'userneoai', 'login141', 'db179668_ai2');

    if (mysqli_connect_errno()) {
        echo 'Error: Could not connect to database.  Please try again later.';
        exit;
    } else {/* echo 'Got in'; */
    }

    $request = $_POST['request'];

    //Get DB into arrays
    $query = "select * from sentence";
    $result = $db->query($query);
    $num_results = $result->num_rows;
    //echo $num_results."</br>";

    for ($i = 0; $i < $num_results; $i++) {
        $row = $result->fetch_assoc();
        $senID[$i] = $row['sentenceID'];
        $sentence[$i] = $row['sentence'];
        $rep[$i] = $row['representation'];
        $POS2[$i] = $row['POS'];
    } //$i
    
//2. Find top word score results
    $searchterm = trim($insentence) . " ";

    for ($i = 0; $i < $num_results; $i++) {
        $sentenceCOPY[$i] = trim($sentence[$i]) . " ";
        $score[$i] = SubFromDffs($sentenceCOPY[$i], $searchterm, $rep[$i], $diffHTML[$i], $subs[$i], $guess_rep[$i]);
    } //$i 

bubble_sort3($score, $sentenceCOPY, $guess_rep);

$bestguess = array();

$k = 0;
$bestguess[1] = 'dog';//$guess_rep[$k];
$bestguess['match1'] = 'dog'; //$sentenceCOPY[$k];

while ($guess_rep[$k] == "" && $k < $num_results) {
$k++;
$bestguess[1] = $guess_rep[$k];
$bestguess['match1'] = $sentenceCOPY[$k];
}


//3. Find substring POS matches

    $posHTML = posHTMLfn($searchterm);
    
    for ($i = 0; $i < $num_results; $i++) {
        $sentenceCOPYPOS[$i] = trim($sentence[$i]) . " ";
        $scorePOS[$i] = SubFromDffs($POS2[$i], $posHTML, $rep[$i], $diffHTMLPOS[$i], $subs[$i], $guess_repPOS[$i]);
        SubFromDffs($sentenceCOPY[$i], $searchterm, $rep[$i], $diffHTMLPOS[$i], $subs[$i], $guess_repPOS[$i]);
        
    } //$i table row

bubble_sort3($scorePOS, $sentenceCOPYPOS, $guess_repPOS);
	
$k = 0;
$bestguess[0] = $scorePOS[$k];
$bestguess['match2'] =  $sentenceCOPYPOS[$k];

/*
while ($guess_repPOS[$k] == "" && $k < $num_results) {
$k++;
$bestguess[0] = $guess_repPOS[$k];
$bestguess['match2'] = $sentenceCOPYPOS[$k];
*/
}	

    return $bestguess;
}




@ $db = new mysqli('127.0.0.1', 'userneoai', 'login141', 'db179668_ai2');

if (mysqli_connect_errno()) {
    echo 'Error: Could not connect to database.  Please try again later.';
    exit;
} else {/* echo 'Got in'; */
}

$id = $_POST['entry_id'];
$cat = $_POST['entry_cat'];
$stncId=$_POST['sid'];
if ($cat == "")
    $cat = 1;
else
    $cat = intval($cat);
$query = "select * from sentence where pageID=" . $cat;

$result = $db->query($query);


for ($i = 0; $i < intval($id); $i++) {

    $row = $result->fetch_assoc();
}


$bestREPguess = repguessfn($row['sentence']);
 $Option2 = $bestREPguess[0];  //
 $Option3 = $bestREPguess[1];  //
 $Option4 = $bestREPguess['match1'];  //
 $Option5 = $bestREPguess['match2'];  // 
$html = $row['representation'] . ";" . $Option2 . ";" . $Option3;

$query2 = "UPDATE `sentence` SET `repguess1` = \"" . $Option2 . "\",
`repguess2` = \"" . $Option3 . "\" ,
`guesses2` = \"" . $Option4 . "\",
`guesses1` = \"" . $Option5 . "\"  WHERE `sentence`.`sentenceID` =" . $stncId;
$result2 = $db->query($query2);
echo json_encode($bestREPguess);
?>