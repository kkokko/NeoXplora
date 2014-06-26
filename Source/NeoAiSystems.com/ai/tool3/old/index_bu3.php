<?php
	//1. DB CONNECTION
	@ $db = new mysqli('127.0.0.1', 'userneoai', 'login141', 'db179668_ai2');

  	if (mysqli_connect_errno()) {
		 echo 'Error: Could not connect to database.  Please try again later.';
		 exit;
  	} else {/*echo 'Got in';*/}

	//2. IF ROWS SUBMITTED, SEPARATE $_POST['rows'] INTO SENTENCES & SAVE TO DB (WHAT IS REQUEST?)
	if(isset($_POST['rows']) && !empty($_POST['rows'])){
		
		$request = $_POST['request'];
		//WHAT IS EXPLODE? WHY NOT EXTRACT FIELDS LIKE NORMAL?
		$idSentences = explode(",", $_POST['rows']);
		$idQuestions = explode(",", $_POST['qarows']);
		$quantity = count($idSentences);
		$Qquantity = count($idQuestions);
				
		for($z=0; $z<$quantity; $z++){
			
			$query = "UPDATE sentenceTBL SET sentence='".$_POST['sentenceID'.$idSentences[$z]]."', representation='".$_POST['representationID'.$idSentences[$z]]."', context_rep='".$_POST['contextrepID'.$idSentences[$z]]."' WHERE sentenceID=".$idSentences[$z];
			$result = $db->query($query);
		}

		for($z=0; $z<$Qquantity; $z++){

			$query = "UPDATE qaTBL SET question='".$_POST['questionID'.$idQuestions[$z]]."', answer='".$_POST['answerID'.$idQuestions[$z]]."', qarule='".$_POST['qaruleID'.$idQuestions[$z]]."' WHERE questionID =".$idQuestions[$z];
			$result = $db->query($query);


		}
	
		header("Location: $request");
	}

?>

<!-- 3. THE WEB PAGE STARTS HERE -->
<html>
<head>
  <title>AI Tool3</title>
  
  <!-- //4. THE CSS IS FOR FORMATTING -->
  <link href="css/styles.css" rel="stylesheet" type="text/css">
  <!-- //5. SMOOTHNESS. WHAT IS THIS? -->
  <link href="css/smoothness/jquery-ui-1.10.3.custom.min.css" rel="stylesheet" type="text/css">
  
  <!-- //6. JQUERY PAGE TAB LIBRARY (WHY 2 FILES? SHOULD WE COPY THESE TO OUR SERVER? ARE THESE JUST FOR PAGE TABS OR OTHER THINGS AS WELL?) -->
  <script src="http://code.jquery.com/jquery-1.9.1.min.js"></script>
  <script src="js/jquery-ui-1.10.3.custom.min.js"></script>
  
  <!-- //7. IS THIS JAVASCRIPT? -->
  <script>
  
	//8. PAGE CONTROLS & SAVE BUTTON
	//9. WHAT IS DOCUMENT? WHAT IS THIS READY? WHAT IS FUNCTION(E)?
  	$(document).ready(function(e) {
    		//10. INSTANTIATE TWO PAGE CONTROLS CALLED LEFT & RIGHT
		$( "#tabs-left" ).tabs();
		$( "#tabs-right" ).tabs();
		//11 SOMETHING WITH SAVE BUTTON. WHAT ARE WE DOING HERE? DECLARING A METHOD? WHAT IS REQUEST?
		$("#save-changes").click(function(){
			$("#request").val(window.location.href);
			return true;
		});
    });
	//12. /HERE WE MAKE DROP-DOWNS AUTO-REFRESH PAGE UPON SELECT
  	function filter(){
		document.forms['categories'].submit();
	}
	function filterStory(){
		document.forms['categories'].submit();
	}
  </script>
</head>
<body>
<h1>AI Tool3</h1>
<form name="categories" method="get" action="<?php echo $_SERVER['PHP_SELF']; ?>">
<?php

	
	// 13. ACTIONS IF USER SELECTED DROP-DOWNS IN PREVIOUS SCREEN
	if(isset($_GET['categoryIDsel']) && !empty($_GET['categoryIDsel']) && $_GET['categoryIDsel'] != "0"){
		
		// 14. A CATEGORY WAS SELECTED LAST SCREEN
		$catID = $_GET['categoryIDsel'];
		
		if(isset($_GET['storyIDsel']) && !empty($_GET['storyIDsel'])){
			$catStory = $_GET['storyIDsel'];
		}
		
		$query = "select * from categoryTBL";
		
		$result = $db->query($query);
		$num_results = $result->num_rows;
		
		// 15. HTML output: WHY DO THIS IF YOU ARE ABOUT TO OVERWRITE IT?
		$html = "";
			
		// 16. ADD CATEGORY DROP-DOWN WITH PREVIOUSLY SELECTED CATEGORY REMEMBERED
		if($num_results>0){
			$html = '<label>Categories</label><select name="categoryIDsel" onChange="filter();">';
			
			$html .= '<option value="0">All</option>';
			for($i=0; $i<$num_results; $i++){
				$row = $result->fetch_assoc();
				
				// select the required category --> use "selected" HTML attribute
				if($row['categoryID'] == $catID){
					$html .= '<option value="'.$row['categoryID'].'" selected >'.$row['category'].'</option>';
				} else {
					$html .= '<option value="'.$row['categoryID'].'" >'.$row['category'].'</option>';
				}
				
			}
			
			$html .= '</select>';
			
			echo $html;
		}
		
		
		// 17. FIND MATCHING STORIES
		$query = "select * from page where categoryID=".$catID;
		
		$result = $db->query($query);
		$num_results = $result->num_rows;
		
		
		// 16. ADD STORY DROP-DOWN WITH PREVIOUSLY SELECTED STORY REMEMBERED
		$html = "";
	
		if($num_results>0){
			$html = "<label style='margin-left: 100px;'>Stories</label><select name=\"storyIDsel\" onChange=\"filterStory();\">";
			
			for($i=0; $i<$num_results; $i++){
				$row = $result->fetch_assoc();
				
				if($row['storyID'] == $catStory){
					$html .= "<option value=".$row['storyID']." selected>".$row['title']."</option>";
				} else {
					$html .= "<option value=".$row['storyID']." >".$row['title']."</option>";
				}
			}
			$html .= "</select>";
			
			echo $html;
		}
		
		
	} else {
	// 17. NO CATEGORY SELECTED IN PREVIOUS SCREEN	
	
		// 18. print categories (NONE SELECTED)
		
		if(isset($_GET['storyIDsel']) && !empty($_GET['storyIDsel'])){
			$catStory = $_GET['storyIDsel'];
		}
		
		$query = "select * from categoryTBL";
		
		$result = $db->query($query);
		$num_results = $result->num_rows;
		
		// HTML output
		$html = "";
		// run only if there are records
		if($num_results>0){
			
			$html = '<label>Categories</label><select name="categoryIDsel" onChange="filter();">';
			
			$html .= '<option value="0">All</option>';
			for($i=0; $i<$num_results; $i++){
				
				$row = $result->fetch_assoc();
				$html .= '<option value="'.$row['categoryID'].'" >'.$row['category'].'</option>';
		
			}
			
			$html .= '</select>';
			
			echo $html;
		}
		
		
		// 19. Print story titles in drop-down REMEMBERING LAST ONE SELECTED
		
		$query = "select * from page";
		
		$result = $db->query($query);
		$num_results = $result->num_rows;
		
		// HTML output
		$html = "";
		// run only if there are records
		if($num_results>0){
			$html = "<label style='margin-left: 100px;'>Stories</label><select name=\"storyIDsel\" onChange=\"filter();\">";
			
			for($i=0; $i<$num_results; $i++){
				$row = $result->fetch_assoc();
				if($row['storyID'] == $catStory){
					$html .= "<option value=".$row['storyID']." selected>".$row['title']."</option>";
				} else {
					$html .= "<option value=".$row['storyID']." >".$row['title']."</option>";
				}
			}
			$html .= "</select>";
			
			echo $html;
		}
		
		
	}

?>
</form>


<!-- //20. RENDER PAGE CONTROLS -->
<form name="core-data" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
<!-- LEFT SIDE -->
<div id="left">
	
	<div id="tabs-left">
        <ul>
            <li><a href="#tabs-1">Representation</a></li>
        </ul>
        <div id="tabs-1">
        	<?php
			
				if(isset($catStory) && !empty($catStory)){ 
					
				} else { 
				$catStory=1;
				}

				//21. DISPLAY sentence & rep	
					$query = "select * from sentenceTBL where storyID=".$catStory;
		
					$result = $db->query($query);
					$num_results = $result->num_rows;
					
					// HTML output
					$html = "";
					$rows = "";
					// run only if there are records
					if($num_results>0){
						
						$counter = 1;
						
						for($i=0; $i<$num_results; $i++){
							
							$row = $result->fetch_assoc();
							
							if($counter < 10){
								$number = "0".$counter;
							} else {
								$number = $counter;
							}
							
							$html .= "<div class=\"row\">
							<span class=\"number\">$number</span>
							<input type=\"text\" class=\"sentencetxt\" name=\"sentenceID".$row['sentenceID']."\" value=\"".$row['sentence']."\">
							<input type=\"text\" class=\"representationtxt\" name=\"representationID".$row['sentenceID']."\" value=\"".$row['representation']."\">
							</div>";
							
							$counter++;
							$rows .= $row['sentenceID'].",";
							
						}
						
					}
					
					$rows = substr($rows, 0, strlen($rows) -1);
					
					echo $html;
					
				 
			?>
        </div>
    </div>
    
</div>
<!-- END LEFT SIDE -->

<!-- RIGHT SIDE-->
<div id="right">

	<div id="tabs-right">
        <ul>
            <li><a href="#tabs-1">Context Representation</a></li>
            <li><a href="#tabs-2">Q &amp; A</a></li>
        </ul>
        <div id="tabs-1">

<?php
			

		//DISPLAY context rep	

		$query = "select * from sentenceTBL where storyID=".$catStory;
		
		$result = $db->query($query);
		$num_results = $result->num_rows;
		// HTML output
		$html = "";
		// run only if there are records
		if($num_results>0){
						
			for($i=0; $i<$num_results; $i++){
							
			$row = $result->fetch_assoc();
							
			$html .= "<div class=\"row\">
			<input type=\"text\" class=\"contextreptxt\" name=\"contextrepID".$row['sentenceID']."\" value=\"".$row['context_rep']."\">
			</div>";
							
			}
						
		}
			
		echo $html;
					
				 
			?>
	</div>
        <div id="tabs-2">

<?php
			

		//DISPLAY q, a & rule	

		$query = "select * from qaTBL where storyID=".$catStory;
		
		$result = $db->query($query);
		$num_results = $result->num_rows;
		// HTML output
		$html = "";
		$qarows = "";
		// run only if there are records
		if($num_results>0){
						
			for($i=0; $i<$num_results; $i++){
							
			$row = $result->fetch_assoc();
							
			$html .= "<div class=\"qarow\">
			<input type=\"text\" class=\"questiontxt\" name=\"questionID".$row['questionID']."\" value=\"".$row['question']."\">
			<input type=\"text\" class=\"answertxt\" name=\"answerID".$row['questionID']."\" value=\"".$row['answer']."\">
			<input type=\"text\" class=\"qaruletxt\" name=\"qaruleID".$row['questionID']."\" value=\"".$row['qarule']."\">
			</div>";

			$qarows .= $row['questionID'].",";


							
			}
						
		}
					
		$qarows = substr($qarows, 0, strlen($qarows) -1);
			
		echo $html;
					
				 
			?>

	</div>
    </div>
    	
</div>

<!-- //23. WHAT IS THIS? IS THIS SAYING, WHEN SAVE CLICKED, SEND 'ROWS' TO NEXT SCREEN (IMPORTANT: WHEN DID 'ROWS' PICK UP THE DATA TYPED IN BY USER? IT LOOKS TO ME LIKE ROWS IS WHAT WAS IN DB?) WHAT IS REQUEST? -->
<input type="hidden" name="rows" value="<?php echo $rows; ?>"/>
<input type="hidden" name="qarows" value="<?php echo $qarows; ?>"/>
<input type="hidden" name="request" id="request" />

<!-- //24. WHAT'S THIS FOR? -->
<div style="clear: both">
<!-- //25. SAVE BUTTON -->
<button type="submit" id="save-changes">Save</button>
</div>
<!-- //26. IS THIS COMMENT IN WRONG PLACE? WHY USE THIS TYPE OF COMMENT WITH ! MARK? -->
<!-- END RIGHT SIDE-->
</form>

<?php

$result->free();
  $db->close();
  
  ?>
</body>
</html>