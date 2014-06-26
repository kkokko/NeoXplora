<html>
<head>
  <title>AI Tool3: POSI</title>
  
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
<h1>AI Tool3: POS interface</h1>
<form method="post" action="">


<?php	

	include 'PosTagger.php';

	// little helper function to print the results
	function printTag($tags) {
        foreach($tags as $t) {
                echo $t['token'] . "/" . $t['tag'] .  " ";
        }
        echo "\n";
	}

	$tagger = new PosTagger('lexicon.txt');


	$searchterm = $_POST['searchEB'];
	$searchterm = trim($searchterm);
	//If ($last == '/') {$last = "";}
	$html = "<p>POS Sequence";           
    $html .= "<input type=\"text\" style=\"width: 200px\" name=\"searchEB\" value = $searchterm />
			<input style=\"width: 75px\" type=\"submit\" /><p>";

	echo $html;

	$tags = $tagger->tag($searchterm);
	echo "<br>";
	printTag($tags);

	
    function guess($searchterm)
    {

  	          
	}  //end function guess
			
	guess($searchterm);
	?>
		 
<input type="hidden" name="request" id="request" />

</form>
</body>
</html>