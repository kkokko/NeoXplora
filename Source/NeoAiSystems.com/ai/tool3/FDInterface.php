<?php 
define("_VALID_PHP", true);
  require_once("../../init.php");
   if (!$user->logged_in){
      redirect_to("../../login.php");
      
   } 
?>
<html>
<head>
  <title>AI Tool3: FDI</title>
  
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
<h1>AI Tool3 FD Interface</h1>
<form method="post" action="">


<?php	

	include 'finediff.php';

	$sentence1 = trim($_POST['searchEB']);
	$sentence2 = trim($_POST['searchEB2']);
	
	//If ($last == '/') {$last = "";}
	$html = "<p>Diff pair</br>";           
    $html .="<input type=\"text\" style=\"width: 200px\" name=\"searchEB\" value = \"$sentence1\" /> </br>
    		 <input type=\"text\" style=\"width: 200px\" name=\"searchEB2\" value = \"$sentence2\" />
			 <input style=\"width: 75px\" type=\"submit\" /><p>";

	echo $html;
	echo "<br>";

	$diff = FineDiff::getDiffOpcodes($sentence1, $sentence2, "");    //" \t.\n\r"
    $diffHTML = " ".FineDiff::renderDiffToHTMLFromOpcodes($sentence1, $diff);

	echo $diffHTML."</br>";

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
							
				
				$subtot = 0;
				for ($k=0; $k < count($sub); $k++) {
					echo "s: ".$sub[$k]."</br>";
					echo "f: ".$for[$k]."</br>";
					echo "sf: ".strpos($sub[$k],$for[$k])."</br>";
					echo "fs: ".strpos($for[$k],$sub[$k])."</br>";
					if (strpos(" ".$sub[$k],$for[$k]) > 0 or strpos(" ".$for[$k],$sub[$k]) > 0) {
						$subtot += abs(str_word_count($sub[$k])-str_word_count($for[$k])-1)/2;
						echo "Insubtot ".trim($sentence1)." ".$sub[$k]." ".$for[$k]."</br>";
					} else {
						$subtot += str_word_count($sub[$k]);
					}
				}
				echo "</br>";
				$fortot = 0;
				for ($k=0; $k < count($for); $k++) {
					echo "f: ".$for[$k]."</br>";
					echo "s: ".$sub[$k]."</br>";
					echo "sf: ".strpos($sub[$k],$for[$k])."</br>";
					echo "fs: ".strpos($for[$k],$sub[$k])."</br>";
					if (strpos(" ".$sub[$k],$for[$k]) > 0 or strpos(" ".$for[$k],$sub[$k]) > 0) {
						$fortot += abs(str_word_count($for[$k])-str_word_count($sub[$k])-1)/2;
						echo "Infortot ".trim($sentence2)." ".$sub[$k]." ".$for[$k]."</br>";
					} else {
						$fortot += str_word_count($for[$k]);
					}
				}
				
				$scoreI = (1-($subtot+$fortot)/(str_word_count($sentence1)+str_word_count($sentence2)))*100;
					
				if (trim($sentence1) =="") {$scoreI = 0;}

				echo "</br>".$scoreI."</br>";

		

	?>
		 
<input type="hidden" name="request" id="request" />

</form>
</body>
</html>