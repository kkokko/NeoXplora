<?php
include 'PosTagger.php';

/**
 * @file swa.php
 *
 * Smith-Waterman alignment algorithm for words
 *
 * See http://en.wikipedia.org/wiki/Smith-Waterman_algorithm for basic algorithm
 */


/*Build a POSsimfn where 
* if the first letter of the pos is DIFFERENT it gives 0
* Otherwise the score = 
length(maximum substring match between POS1 and POS2 starting at character 1)/length(POS2)
*/
function POSsimfn($pos1,$pos2)
{
$i=0;
//while (substr($pos1, 0,$i) == substr($pos2,0,$i) and  $i < min(strlen($pos1), strlen($pos2))) 
while (substr($pos1, $i,1) == substr($pos2,$i,1 ) and  $i < min(strlen($pos1), strlen($pos2))) 
{ 
$i++;
}

$score = $i/max(strlen($pos1), strlen($pos2));
return $score;

}


function SWweight($a,$b)
{


	$POSsim = POSsimfn($a,$b);
	
	if ($POSsim > 0) 
	{
		$res = 1 + $POSsim;
	}
	else if ($POSsim == 0)
	{
		$res= -1;
	}
	
return $res;


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




//--------------------------------------------------------------------------------------------------
/**
 * @brief Clean token
 *
 * Remove terminal punctuation, such as full stops
 *
 * @param token Text token to be cleaned
 *
 * @return Cleaned token
 */
function clean_token($token)
{
        $token = preg_replace('/\.$/', '', $token);
        return $token;
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

//--------------------------------------------------------------------------------------------------
/**
 * @brief Align words in two strings using Smith-Waterman algorithm
 *
 * Strings are split into words, and the resulting arrays are aligned using Smith-Waterman algorithm
 * which finds a local alignment of the two strings. Aligning words rather than characters saves
 * memory
 *
 * @param str1 First string (haystack)
 * @param str2 First string (needle)
 * @param html Will contain the alignment between str1 and str2 in HTML format
 *
 * @return The score (0-1) of the alignment, where 1 is a perfect match between str2 and a subsequence of str1
 */
function smith_waterman ($str1, $str2, &$html)
{
        $score = 0.0;
       
        // Weights
        $match          = 2;
        $mismatch       = -1;
        $deletion       = -1;
        $insertion      =-1;
       
        // Tokenise input strings, and convert to lower case
        $X = tokenise_string($str1);
        $Y = tokenise_string($str2);
       
	   
        // Lengths of strings
        $m = count($X);
        $n = count($Y);
       
        // Create and initialise matrix for dynamic programming
        $H = array();
       
        for ($i = 0; $i <= $m; $i++)
        {
                $H[$i][0] = 0;
        }
        for ($j = 0; $j <= $m; $j++)
        {
                $H[0][$j] = 0;
        }
       
        $max_i = 0;
        $max_j = 0;
        $max_H = 0;
       
        for ($i = 1; $i <= $m; $i++)
        {
                for ($j = 1; $j <= $n; $j++)
                {              
                        $a = $H[$i-1][$j-1];
                       
                        $s1 = clean_token($X[$i-1]);
                        $s2 = clean_token($Y[$j-1]);
                       
                        // Compute score of four possible situations (match, mismatch, deletion, insertion
//                        if (strcasecmp ($s1, $s2) == 0)
                        if (SWweight ($s1, $s2) == 0)						
						
                        {
                                // Strings are identical
                                $a += $match;
                        }
                        else
                        {
                                // Strings are different
                               // $a -= levenshtein($X[$i-1], $Y[$i-1]); // allow approximate string match
//                                $a += $mismatch; // you're either the same or you're not
							$a += SWweight($s1, $s2);								
                        }
               
                        $b = $H[$i-1][$j] + $deletion;
                        $c = $H[$i][$j-1] + $insertion;
                       
                        $H[$i][$j] = max(max($a,$b),$c);
                                               
                        if ($H[$i][$j] > $max_H)
                        {
                                $max_H = $H[$i][$j];
                                $max_i = $i;
                                $max_j = $j;
                        }
                }
        }
       
        // Best possible score is perfect alignment with no mismatches or gaps
        $maximum_possible_score = count($Y) * $match;
        $score = $max_H / $maximum_possible_score;
       
        echo "<p>Score=$score</p>";
       
               
        // Traceback to recover alignment
        $alignment = array();
       
        $value = $H[$max_i][$max_j];
        $i = $max_i-1;
        $j = $max_j-1;
        while (($value != 0) && (($i != 0) && ($j != 0)))
        {
                echo "H".$H[$i][$j] . "\n <br>";
                echo "Row Colum ".$i . ',' . $j . "\n <br>";
                echo $X[$i] . '-' . $Y[$j] . "\n <br>";
//                print_r($X);
//                print_r($Y);
               
                $s1 = clean_token($X[$i]);
                $s2 = clean_token($Y[$j]);
               
                if ($s2 != '')
                {
                        array_unshift($alignment,
                                array(
                                        'pos' => $i,
                                        'match' => ((strcasecmp($s1,$s2)==0) ? 1 : 0),
                                        'token' => $X[$i]
                                        )
                                );
                }
                       
                $up = $H[$i-1][$j];
                $left =  $H[$i][$j-1];
                $diag = $H[$i-1][$j-1];
       
                if ($up > $left)
                {
                        if ($up > $diag)
                        {
                                $i -= 1;
                        }
                        else
                        {
                                $i -= 1;
                                $j -= 1;
                        }
                }
                else
                {
                        if ($left > $diag)
                        {
                                $j -= 1;
                        }
                        else
                        {
                                $i -= 1;
                                $j -= 1;
                        }
                }
        }
        echo $i . ',' . $j . "\n <br>";
        echo $X[$i] . '-' . $Y[$j] . "\n <br>";
       
        // Store last token in alignment
        $s1 = clean_token($X[$i]);
        $s2 = clean_token($Y[$j]);
echo "<code>";
print_r($s1) ;
print_r($s2) ;         
echo "</code>";
        array_unshift($alignment,
                array(
                        'pos' => $i,
                        'match' => ((strcasecmp($s1,$s2)==0) ? 1 : 0),
                        'token' => $X[$i]
                        )
                );

        // HTML snippet showing alignment
       
        // Local alignment
        $snippet = '';
        $last_pos = -1;
        foreach ($alignment as $a)
        {
                if ($a['pos'] != $last_pos)
                {
               
                        if ($a['match'] == 1)
                        {
                                $snippet .= '<span style="color:black;font-weight:bold;background-color:yellow;">';
                        }
                        else
                        {
                                $snippet .= '<span style="color:rgb(128,128,128);font-weight:bold;background-color:yellow;">';
                        }
                        $snippet .= $a['token'] . ' ';//$Z[$a['pos']] . ' ';
               
                        $snippet .= '</span>';
                }
                $last_pos = $a['pos'];
               
        }      
        // Embed this in haystack string


       
        // Before alignment
         $start_pos = $alignment[0]['pos'] - 1;
        $prefix_start = max(0, $start_pos - 10);
        $prefix = '';
        while ($start_pos > $prefix_start)
        {
                $prefix = $X[$start_pos] . ' ' . $prefix;
                $start_pos--;
        }
        if ($start_pos > 0) $prefix = '…' . $prefix;

        // After alignment      
	    $end_pos = $alignment[count($alignment) - 1]['pos'] + 1;
        $suffix_end = min(count($X), $end_pos + 10);
        $suffix = '';
        while ($end_pos < $suffix_end)
        {
                $suffix .= ' ' . $X[$end_pos];
                $end_pos++;
        }
        if ($end_pos < count($X)) $suffix .= '…';

        $html = $prefix . $snippet . $suffix;  

echo "<br><code>";
	echo $alignment[0]['pos'] ."   ". $alignment[0]['match']."  ". $alignment[0]['token'] ."<br>" ;
	echo $alignment[1]['pos'] ."   ". $alignment[1]['match']."  ". $alignment[1]['token'] ."<br>" ;	
	echo $alignment[2]['pos'] ."   ". $alignment[2]['match']."  ". $alignment[2]['token'] ."<br>" ;	
	echo $alignment[3]['pos'] ."   ". $alignment[3]['match']."  ". $alignment[3]['token'] ."<br>" ;		
//for ($i=0;$i<count(alignment);$i++){
//print_r($alignment[$i]['pos']);
//echo $i;
//	echo $alignment[$i]['pos'] ."   ". $alignment[$i]['match']."  ". $alignment[$i]['token'] .'<br>' ;
//}
echo "</code>";

echo "<br><code>";
for ($i=0;$i<count($H);$i++){
for ($j=0;$j<count($H[$i]);$j++){
echo $H[$i][$j]."  ";
}
echo "<br>";
}
//     print_r($H);
echo "</code>";       
        return $score;
}


// test cases

if (1)
{
//$str1 = 'so, This is a test case of my code';
//$str2 = 'This is another test case';

echo "String#1 :".$_REQUEST['string1']."<br><br>" ;
echo "String#2 :".$_REQUEST['string2']."<br><br>" ;
//******************* part of speach ****************************/

echo $posHTML1 = posHTMLfn($_REQUEST['string1']);

echo "<br>";

echo $posHTML2= posHTMLfn($_REQUEST['string2']);

echo "<br>";
$pos1 = explode(" ", $posHTML1);

echo "<br>";

$pos2 = explode(" ", $posHTML2);

echo "<br>";

print_r($pos1);

echo "<br>";

print_r($pos2);


for ($k=0;$k<count($pos1);$k++)
{
echo "<br> $k score is " .POSsimfn($pos1[$k],$pos2[$k]) . " =======  SWweight  is  " . SWweight($pos1[$k],$pos2[$k]);


}





echo "<br>";

$html = '';

 $score = smith_waterman($_REQUEST['string1'], $_REQUEST['string2'], $html);
echo "score : ".$score ; 
echo "<br>";
echo $html;

}

echo "<br>END";




?>

