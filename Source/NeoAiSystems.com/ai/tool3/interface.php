<?php 
define("_VALID_PHP", true);
  require_once("../../init.php");
   if (!$user->logged_in){
      redirect_to("../../login.php");
      
   } 
?>
<html>
<head>
  <title>AI Tool3: Search</title>
  
  <!-- //4. THE CSS IS FOR FORMATTING -->
  <link href="css/styles.css" rel="stylesheet" type="text/css">
  
    <script>
  
		$("#save-changes").click(function(){
			$("#request").val(window.location.href);
			return true;
		}

    </script>

</head>
<body>
<h1>AI Tool3: Search</h1>
<form method="post" action="">

<!-- SEARCH -->	


<?php	
			include 'PosTagger.php';
			include 'finediff.php';

			// little helper function to print the results
			function getTag($tags) {
				$html ='';
      		  foreach($tags as $t) {
     		           $html .= $t['token'] . "/" . $t['tag'] .  " ";
     		   }
     		   $html .= "\n";
     		   return $html;
			}			

			function bubble_sort3(&$arr, &$also1, &$also2, &$also3, &$also4, &$also5, &$also6) {
   		 		$size = count($arr);
    			for ($i=0; $i<$size; $i++) {
        			for ($j=0; $j<$size-1-$i; $j++) {
            			if ($arr[$j+1] > $arr[$j]) {
                			swap($arr, $j, $j+1);
                			swap($also1, $j, $j+1);
                			swap($also2, $j, $j+1);
                			swap($also3, $j, $j+1);
                			swap($also4, $j, $j+1);
                			swap($also5, $j, $j+1);
                			swap($also6, $j, $j+1); 			  			}
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
    			$diffHTML = " ".FineDiff::renderDiffToHTMLFromOpcodes($sentenceI, $diff);


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

				$subs="";	
				$sub = array();
				$for = array();
				for ($k=0; $k < max(count($delpos),count($inspos)); $k++)	{
					$for[$k] = trim(substr($diffHTML,$delpos[$k]+5, $udelpos[$k]-$delpos[$k]-5));
					$sub[$k] = trim(substr($diffHTML,$inspos[$k]+5, $uinspos[$k]-$inspos[$k]-5));				
					$subs .= $for[$k]." <-> ".$sub[$k]." | ";	
				}
				
				$guess_repI = $repI;
				for ($k=0; $k < count($delpos); $k++)	{
					$old_guess = $guess_repI;
					$guess_repI = str_replace($for[$k], $sub[$k], $guess_repI);
					$fork = explode(" ",$for[$k]);
					$subk = explode(" ",$sub[$k]);
					if ($old_guess == $guess_repI && count($fork) == count($subk) && count($fork) > 1) {
						for ($m = 0; $m < count($fork); $m++){
							$guess_repI = str_replace($fork[$m], $subk[$m], $guess_repI);			
						}					
					}	
				}
				
				if ($guess_repI == $repI) { $guess_repI ="";}				
				
				$subtot = 0;
				for ($k=0; $k < count($sub); $k++) {
					if (strpos(" ".$sub[$k],$for[$k]) > 0 or strpos(" ".$for[$k],$sub[$k]) > 0) {
						$subtot += abs(str_word_count($sub[$k])-str_word_count($for[$k])-1)/2;
						//echo "Insubtot ".trim($sentenceI)." ".$sub[$k]." ".$for[$k]."</br>";
					} else {
						$subtot += str_word_count($sub[$k]);
					}
				}
				$fortot = 0;
				for ($k=0; $k < count($for); $k++) {
					if (strpos(" ".$sub[$k],$for[$k]) > 0 or strpos(" ".$for[$k],$sub[$k]) > 0) {
						$fortot += abs(str_word_count($for[$k])-str_word_count($sub[$k])-1)/2;
						//echo "Infortot ".trim($sentenceI)." ".$sub[$k]." ".$for[$k]."</br>";
					} else {
						$fortot += str_word_count($for[$k]);
					}
				}
				
				$scoreI = (1-($subtot+$fortot)/(str_word_count($searchterm)+str_word_count($sentenceI)))*100;
					
				if (trim($sentenceI) =="") {$scoreI = 0;}
				
				return $scoreI;
				
	} //end  SubFromDffs

	function posHTMLfn($searchterm1) {    
			$tagger = new PosTagger('lexicon.txt');
			
			$tags = $tagger->tag(trim($searchterm1));    
			$postags = getTag($tags);
			//echo $postags."<br>";				
			
			$startk = -1;
			$endk = -1;
			$posHTML ="";
			for ($k = 0; $k < strlen($postags); $k++) {
				//$pos .= $k;
				if (substr($postags,$k,1) == "/") {
					$startk = $k+1;
				}
				if (substr($postags,$k,1) == " ") {
					$endk = $k-1;
				}
				if ($k == strlen($postags)-1) {
					$endk = $k-1;
				}
				if ($startk <> -1 && $endk <> -1) {
					$posHTML .= substr($postags, $startk, $endk-$startk+1)." ";
					$startk = -1;
					$endk = -1;
				}

			} //$k			
								
			$posHTML = trim($posHTML);	
			
			$html ='';
			for ($k =0; $k < strlen($posHTML); $k++) {
				if (ord(substr($posHTML,$k,1)) <> 10) {$html .= substr($posHTML,$k,1);}			
			}
			return $html;
			
	}	//end posHTMLfn

	//Start of page

	// Search edit box
	$searchterm = $_POST['searchEB'];
	$searchterm = trim($searchterm);
	//If ($last == '/') {$last = "";}
	$html = "<p>Target Sequence";           
    $html .= "<input type=\"text\" style=\"width: 200px\" name=\"searchEB\" value = \"$searchterm\" />
			<input style=\"width: 75px\" type=\"submit\" /><p>";
	echo $html;
	

      	//1. DB CONNECTION
		@ $db = new mysqli('127.0.0.1', 'userneoai', 'login141', 'db179668_ai2');

  		if (mysqli_connect_errno()) {
		 	echo 'Error: Could not connect to database.  Please try again later.';
		 	exit;
  		} else {/*echo 'Got in';*/}
  	
  		$request = $_POST['request'];  		
  		
  			//Get DB into array
			$query = "select * from sentenceTBL";
			$result = $db->query($query);
			$num_results = $result->num_rows;
			echo $num_results."</br>";
			
  			for ($i=0; $i <$num_results; $i++) {
    			$row = $result->fetch_assoc();	
    			$senID[$i] = $row['sentenceID']; 
    			$sentence[$i] = $row['sentence'];
    			$rep[$i] = $row['representation'];
    			$POS2[$i] = $row['POS'];  			
    		} //$i

		//2. Print top word score results
			 
			for ($i=0; $i<$num_results; $i++) {
				//echo "<tr><td>".$i."</td><td>".($i+1)."</td></tr>";
				//find substitutions
				$sentenceI = trim($sentence[$i])." ";
				$searchterm = trim($searchterm)." ";					
				$repI = $rep[$i];
				$score[$i] = SubFromDffs($sentenceI, $searchterm, $repI, $diffHTMLI, $subsI, $guess_repI);	 
				$guess_rep[$i] = $guess_repI;
				$subs[$i] = $subsI;		
				$diffHTML[$i] = $diffHTMLI;		
				 
			} //$i 

			bubble_sort3($score,$senID, $sentence, $subs, $guess_rep, $POS2, $diffHTML);
			// Print
			echo "</br><table >";
			$red =0;
			for ($i=0; $i<10; $i++) {
				$scoreI = $score[$i];	
				$sentenceI = $sentence[$i];
				$guess_repI = $guess_rep[$i];
				$subsI = $subs[$i];			
				
				if ($guess_rep[$i] <> "" && $red == 0) {
				$guessrephtml = "<font color=\"red\">".$guess_rep[$i]."</font>";
				++$red;
				}
				else {
				$guessrephtml = $guess_rep[$i];
				}							
				echo "<tr><td>".number_format($scoreI,0)."% </td><td>".$sentenceI."</td><td>".$subsI."</td><td>".$guessrephtml."</td></tr>"; 
			} //$i table row
			echo "</table></br>";



		//3. Print substring POS matches
			echo "<font color=\"red\">".$searchterm."</font><br>";
				
			$posHTML = posHTMLfn($searchterm);
			
			echo $posHTML."</br>";
			
			$scorePOS = array();
			$diffHTMLPOS = array();
				
			for ($i=0; $i < $num_results; $i++) {
    			$scorePOS[$i] = SubFromDffs($POS2[$i], $posHTML, $rep[$i], $diffHTMLPOSI, $subsPOSI, $junk2);
				$subsPOS[$i] = $subsPOSI;
				$diffHTMLPOS[$i] = $diffHTMLPOSI;				 
			} //$i table row

			bubble_sort3($scorePOS, $senID, $sentence, $subsPOS, $guess_rep, $POS2, $diffHTMLPOS);


			$red =0;
			echo "</br><table >";				
			for ($i=0; $i < 10; $i++) {   //$num_results
				if ($guess_rep[$i] <> "" && $red == 0) {
				$guessrephtml = "<font color=\"red\">".$guess_rep[$i]."</font>";
				++$red;
				}
				else {
				$guessrephtml = $guess_rep[$i];
				}
				echo "<tr><td>".number_format($scorePOS[$i],0)."% </td><td>".$sentence[$i]."</td><td>".$guessrephtml."	</td><td>".$diffHTMLPOS[$i]."</td><td>".$POS2[$i]."</td></tr>";		
			} //$i table row
			
			echo "</table></br>";

  	          
	?>
		 
<input type="hidden" name="request" id="request" />

</form>
</body>
</html>