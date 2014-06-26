<?php
$output=array();
if(isset($_REQUEST) && count($_REQUEST)>0 && isset($_REQUEST["submit"]))
{
	function smith_waterman ($str1, $str2, &$HTML)
	{
		$s1 = explode(" ", $str1);
		$s2 = explode(" ", $str2);		
		
		$match=1;
		$mismatch=-1;
		$gap=-1;
		$score=array();
		$pointer=array();
		for($i=0;$i<=count($s1);$i++)
		{
			$score[$i]=array();
			$pointer[$i]=array();
			for($j=0;$j<=count($s2);$j++)
			{
				$score[$i][$j]=0;
				$score[$i][$j]="none";
			}
		}


				
		$maxi=0;
		$maxj=0;
		$max_score=0;
		for($i=1;$i<=count($s1);$i++)//Logic to construct the matrix and find maximum edit distance
		{
			for($j=1;$j<=count($s2);$j++)
			{
				$diagonal=-1;
				$left=-1;
				$up=-1;
			 $letter1=$s1[$i-1];
			 $letter2=$s2[$j-1];
//				if($letter1==$letter2)
				if(strcmp ( $letter1 , $letter2 )==0){	
					$diagonal=$score[$i-1][$j-1]+$match;
					}
				else{
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

//		print_r($score);
//displayarray($score,$maxi,$maxj);
//echo $maxi.' ' .$maxj;
//echo "score :<br>";
//displayarray($score);
//echo "<br>";
//echo "<br><code>";
//print_r($pointer);
//echo "</code><br>";

//echo $maxi;
//echo $maxj;

		
		$align1="";
		$align2="";
		$count=0;
//$maxi =4;
//$maxj=3;	
	
	$in=array();
	$out=array();
	
		while($maxi>0 && $maxj>0)//Trace-back
		{
			$count++;
			if($pointer[$maxi][$maxj]=="none") break;
			else if($pointer[$maxi][$maxj]=="diagonal")
			{
//			    echo $s1[$maxi-1]. '  '. $s2[$maxj-1].'<br>';
array_unshift($in,$s1[$maxi-1]);
array_unshift($out,$s2[$maxj-1]);
				$align1=$s1[$maxi-1].' '.$align1;
				$align2=$s2[$maxj-1]. ' '.$align2;
				$maxi--;
				$maxj--;
			}
			else if($pointer[$maxi][$maxj]=="left")
			{
//			    echo "---". '  '. $s2[$maxj-1].'<br>';
//array_unshift($in,"-");
//array_unshift($out,$s2[$maxj-1]);
				
				$align2=$s2[$maxj-1].' ' .$align2;
				$align1="-".' '.$align1;
				$maxj--;
			}
			else if($pointer[$maxi][$maxj]=="up")
			{
//			    echo $s1[$maxi-1]. '  '. "---".'<br>';			
//array_unshift($in,$s1[$maxi-1]);
//array_unshift($out,"-");
				
				$align1=$s1[$maxi-1].' '.$align1;
				$align2="-". ' ' .$align2;
				$maxi--;
			}
		}
//		$HTML[0]=strrev($align1);
//		$HTML[1]=strrev($align2);
		$HTML[0]=$align1;
		$HTML[1]=$align2;
		$HTML[2]=$in;		
		$HTML[3]=$out;
		
		return;
	}
	smith_waterman($_REQUEST["str1"],$_REQUEST["str2"],$output);
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
		<div><?php if(isset($output[0])) echo $output[0];?></div>
		<div><?php if(isset($output[0])) echo $output[1];?></div>
        <br>
		<div>IN=><?php if(isset($output[2])) {
		for ($i=0;$i<count($output[2]);$i++)
		 echo $output[2][$i].' ';
		 }
		 ?></div>        

		<div>OUT=><?php if(isset($output[3])) {
		for ($i=0;$i<count($output[3]);$i++)
		 echo $output[3][$i].' ';
		 }
		 ?></div>        
        
		<input type="submit" value="Submit" name="submit"/>
	</form>
</body>
</html>
