<?php
include 'PosTagger.php';

$output=array();
if(isset($_REQUEST) && count($_REQUEST)>0 && isset($_REQUEST["submit"]))
{


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

function SWweight($a,$b)
{

//	echo "[";
	$POSsim = POSsimfn($a,$b);
//	echo "]";
	$res='0';
	if ($POSsim > 0) 
	{
		$res = 1 + $POSsim;
	}
	else if ($POSsim == 0)
	{
		$res= - 1;
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
//				if (SWweight ($letter1 , $letter2) == 0)				
				{	
					$diagonal=$score[$i-1][$j-1]+$match;
					}
				else{
				 if (!SWweight ($letter1, $letter2) == 0)	{
				 //	echo "<br> match score = ". SWweight ($letter1, $letter2)." ";
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

	
$alignIN=$alignOUT="";
	
$sore=	posSWfn($_REQUEST["str1"],$_REQUEST["str2"],$alignIN, $alignOUT); //,$output
}
//function displayarray($str,$maxi,$maxj)
function displayarray($str)
{
echo "<code>";
for ($i=0;$i<count($str);$i++)
{

	for ($j=0;$j<count($str[$i]);$j++)
	{
		echo " ".$str[$i][$j];
	}
	echo "<br>";
}
echo "</code";
}


function displayarray1($str)
{
echo "<code>";
for ($i=1;$i<count($str);$i++)
{

	for ($j=1;$j<count($str[$i]);$j++)
	{
		echo " ".$str[$i][$j];
	}
	echo "<br>";
}
echo "</code";
}
?>
<!DOCTYPE html>
<html>
<head>
</head>
<body>
	<form action="" method="get">
		<label for="str1">First String</label>
		<input type="text" value="<?php if(isset($_REQUEST['str1'])) echo $_REQUEST['str1']; else echo "i love my green honda cars"?>" name="str1"/>
		<br/>
		<label for="str2">Second String</label>
		<input type="text" value="<?php if(isset($_REQUEST['str2'])) echo $_REQUEST['str2']; else echo  "i love my honda cars "?>" name="str2"/>
		<br/>
		<div><?php //if(isset($output[0])) echo $output[0];?></div>
		<div><?php //if(isset($output[0])) echo $output[1];?></div>
        <br>
        <div>score: <?php echo $sore; ?></div>
		<div>IN=><?php if(isset($alignIN)) {
		for ($i=0;$i<count($alignIN);$i++)
		 echo $alignIN[$i].' ';
		 }
		 ?></div>        

		<div>OUT=><?php if(isset($alignOUT)) {
		for ($i=0;$i<count($alignOUT);$i++)
		 echo $alignOUT[$i].' ';
		 }
		 ?></div>        
        
		<input type="submit" value="Submit" name="submit"/>
	</form>
</body>
</html>
