<?php
$output=array();
if(isset($_REQUEST) && count($_REQUEST)>0 && isset($_REQUEST["submit"]))
{
	function smith_waterman ($str1, $str2, &$HTML)
	{
		$match=1;
		$mismatch=-1;
		$gap=-1;
		$score=array();
		$pointer=array();
		for($i=0;$i<=strlen($str1);$i++)
		{
			$score[$i]=array();
			$pointer[$i]=array();
			for($j=0;$j<=strlen($str2);$j++)
			{
				$score[$i][$j]=0;
				$score[$i][$j]="none";
			}
		}
		$maxi=0;
		$maxj=0;
		$max_score=0;
		for($i=1;$i<=strlen($str1);$i++)//Logic to construct the matrix and find maximum edit distance
		{
			for($j=1;$j<=strlen($str2);$j++)
			{
				$diagonal=-1;
				$left=-1;
				$up=-1;
				$letter1=$str1[$i-1];
				$letter2=$str2[$j-1];
				if($letter1==$letter2)
					$diagonal=$score[$i-1][$j-1]+$match;
				else
					$diagonal=$score[$i-1][$j-1]+$mismatch;
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
		$align1="";
		$align2="";
		$count=0;
		while($maxi>0 && $maxj>0)//Trace-back
		{
			$count++;
			if($pointer[$maxi][$maxj]=="none") break;
			else if($pointer[$maxi][$maxj]=="diagonal")
			{
				$align1.=$str1[$maxi-1];
				$align2.=$str2[$maxj-1];
				$maxi--;
				$maxj--;
			}
			else if($pointer[$maxi][$maxj]=="left")
			{
				$align2.=$str2[$maxj-1];
				$align1.="-";
				$maxj--;
			}
			else if($pointer[$maxi][$maxj]=="up")
			{
				$align1.=$str1[$maxi-1];
				$align2.="-";
				$maxi--;
			}
		}
		$HTML[0]=strrev($align1);
		$HTML[1]=strrev($align2);
		return;
	}
	smith_waterman($_REQUEST["str1"],$_REQUEST["str2"],$output);
}

?>
<!DOCTYPE html>
<html>
<head>
</head>
<body>
	<form action="" method="get">
		<label for="str1">First String</label>
		<input type="text" value="<?php if(isset($_REQUEST['str1'])) echo $_REQUEST['str1'];?>" name="str1"/>
		<br/>
		<label for="str2">Second String</label>
		<input type="text" value="<?php if(isset($_REQUEST['str2'])) echo $_REQUEST['str2'];?>" name="str2"/>
		<br/>
		<div><?php if(isset($output[0])) echo $output[0];?></div>
		<div><?php if(isset($output[0])) echo $output[1];?></div>
		<input type="submit" value="Submit" name="submit"/>
	</form>
</body>
</html>





















