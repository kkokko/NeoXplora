<?php
	//1. DB CONNECTION
define("_VALID_PHP", true);
  require_once("../../init.php");
   if (!$user->logged_in){
      redirect_to("../../login.php");
      
   }   
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
			
			$query = "UPDATE sentenceTBL SET sentence='".$_POST['sentenceID'.$idSentences[$z]]."', representation='".$_POST['representationID'.$idSentences[$z]]."', context_rep='".strip_tags($_POST['contextrepID'.$idSentences[$z]])."', CRcomment='".$_POST['CRcommentID'.$idSentences[$z]]."',POS='".$_POST['POSID'.$idSentences[$z]]."'WHERE sentenceID=".$idSentences[$z];
			$result = $db->query($query);
		}

		for($z=0; $z<$Qquantity; $z++){

			$query = "UPDATE qaTBL SET question='".$_POST['questionID'.$idQuestions[$z]]."', answer='".$_POST['answerID'.$idQuestions[$z]]."', qarule='".$_POST['qaruleID'.$idQuestions[$z]]."' WHERE questionID =".$idQuestions[$z];
			$result = $db->query($query);


		}
	
		header("Location: $request");
	}
	include 'PosTagger.php';
	include 'finediff.php';
?>

<!-- 3. THE WEB PAGE STARTS HERE -->
<html>
<head>
  <title>AI Tool3: DB</title>
  
  <!-- //4. THE CSS IS FOR FORMATTING -->
  <link href="css/styles.css" rel="stylesheet" type="text/css">
  <!-- //5. SMOOTHNESS. WHAT IS THIS? -->
  <link href="css/smoothness/jquery-ui-1.10.3.custom.min.css" rel="stylesheet" type="text/css">
  
  <!-- //6. JQUERY PAGE TAB LIBRARY (WHY 2 FILES? SHOULD WE COPY THESE TO OUR SERVER? ARE THESE JUST FOR PAGE TABS OR OTHER THINGS AS WELL?) -->
  <script src="http://code.jquery.com/jquery-1.9.1.min.js"></script>
  <script src="js/jquery-ui-1.10.3.custom.min.js"></script>
  <style>
   .noborder{
   	border:none;
   }
   .div,.row input,.number,.qarow input{
   	font-size: 14px !important; 
   }
   
   .qarow{
   	padding: 1.5px 0 !important;
   }
   #left #tabs-2 .row{
   	padding: 0 !important;
   }
	#right #tabs-1 .row{
   	padding: 0 !important;
   }   
   #right #tabs-3 .row{
   	padding: 0 !important;
   }   
   .div{
   	display:inline-block;vertical-align:top;
   }
   .number{
   	display: inline-table;
   }
   </style>
  <!-- //7. IS THIS JAVASCRIPT? -->
  <script>
  
	//8. PAGE CONTROLS & SAVE BUTTON
	//9. WHAT IS DOCUMENT? WHAT IS THIS READY? WHAT IS FUNCTION(E)?
  	$(document).ready(function(e) {
    //10. INSTANTIATE TWO PAGE CONTROLS CALLED LEFT & RIGHT    	

//$('#st').change(function(){
		
    	$('.qarow input,.row input').each(function(){
    		$(this).hide();
    		var x=$(this).val().trim();
			
    		if(x.length<3){
    			x="[empty]";
    		}
			if ($(this).attr('class')!='selectBoxInput')
    		$('<div class="div">'+x+'</div>').insertAfter($(this)).addClass($(this).attr('class'));
			else $(this).show();
	    	$('.div').click(function(){
	    		$(this).hide();    		
	    		$(this).prev().css('display','inline-table');

	    	});    		
    	});
    	setTimeout(function(){
	    	$('#left #tabs-1 .row').each(function(i){
	    		var x= $(this).outerHeight();
	    		console.log(i,x);
	    		$('#right #tabs-1 .row:eq('+i+')').css('height',x);
	    		$('#left #tabs-2 .row:eq('+i+')').css('height',x);
	    		$('#right #tabs-3 .row:eq('+i+')').css('height',x);
	    	});
		},500);
//});
//$('#st').trigger('change');
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

<style type="text/css">
	body{
		font-family: Trebuchet MS, Lucida Sans Unicode, Arial, sans-serif;
		font-size:0.9em;
		line-height:130%;
		margin:2px;
	}
	
	
	.selectBoxArrow{
		margin-top:1px;
		float:left;
		position:absolute;
		right:1px;
		

	}	
	.selectBoxInput{
		border:0px;
		padding-left:1px;
		height:16px;
		position:absolute;
		top:0px;
		left:0px;
		padding:0px !important;
		width:414px;
		overflow:hidden;
	}

	.selectBox{
		border:1px solid #7f9db9;
		height:20px;	
		width: 414px;
		vertical-align:top;
		display:inline-block;
		
	}
	.selectBoxOptionContainer{
		position:absolute;
		border:1px solid #7f9db9;
		height:100px;
		background-color:#FFF;
		left:-1px;
		top:20px;
		visibility:hidden;
		overflow:auto;
		z-index:1000;
	}
	.selectBoxIframe{
		position:absolute;
		background-color:#FFF;
		border:0px;
		z-index:999;
	}
	.selectBoxAnOption{
		font-family:arial;
		font-size:12px;
		cursor:default;
		margin:1px;
		overflow:hidden;
		white-space:nowrap;
	}
	</style>
	<script type="text/javascript">


	
	// Path to arrow images
	var arrowImage = 'http://www.dhtmlgoodies.com/scripts/form_widget_editable_select/images/select_arrow.gif';	// Regular arrow
	var arrowImageOver = 'http://www.dhtmlgoodies.com/scripts/form_widget_editable_select/images/select_arrow_over.gif';	// Mouse over
	var arrowImageDown = 'http://www.dhtmlgoodies.com/scripts/form_widget_editable_select/images/select_arrow_down.gif';	// Mouse down

	
	var selectBoxIds = 0;
	var currentlyOpenedOptionBox = false;
	var editableSelect_activeArrow = false;
	

	
	function selectBox_switchImageUrl()
	{
		if(this.src.indexOf(arrowImage)>=0){
			this.src = this.src.replace(arrowImage,arrowImageOver);	
		}else{
			this.src = this.src.replace(arrowImageOver,arrowImage);
		}
		
		
	}
	
	function selectBox_showOptions()
	{
		var numId = this.id.replace(/[^\d]/g,'');
		
		var txt=$('#selectBox'+numId+' input').attr('selectboxoptions');
		var pp=$('#selectBox'+numId).parent().attr('id');
		var entry_id=pp.substr(3);
		var entry_cat='<?php $catStory = $_GET['storyIDsel']; echo $catStory;?>';
		$.ajax({
				type : "POST",
				url : "get_boxoption.php",
				async : true,
				data : {entry_id:entry_id,entry_cat:entry_cat},
				success : function(msg){
					
					$('#selectBox'+numId+' input').attr('selectboxoptions',msg);
					var options=msg.split(';');
					for (var i=0;i<options.length;i++)
					{
					$('#selectBoxOptions'+numId+' .selectBoxAnOption:eq('+i+')').html(options[i]);
					}
				},
				error:function (xhr, ajaxOptions, thrownError){
					alert("error");				
				}			   
			});
			
		if(editableSelect_activeArrow && editableSelect_activeArrow!=this){
			editableSelect_activeArrow.src = arrowImage;
			
		}
		editableSelect_activeArrow = this;
		
		
		var optionDiv = document.getElementById('selectBoxOptions' + numId);
		if(optionDiv.style.display=='block'){
			optionDiv.style.display='none';
			if(navigator.userAgent.indexOf('MSIE')>=0)document.getElementById('selectBoxIframe' + numId).style.display='none';
			this.src = arrowImageOver;	
		}else{			
			optionDiv.style.display='block';
			if(navigator.userAgent.indexOf('MSIE')>=0)document.getElementById('selectBoxIframe' + numId).style.display='block';
			this.src = arrowImageDown;	
			if(currentlyOpenedOptionBox && currentlyOpenedOptionBox!=optionDiv)currentlyOpenedOptionBox.style.display='none';	
			currentlyOpenedOptionBox= optionDiv;			
		}
	}
	
	function selectOptionValue()
	{
		var parentNode = this.parentNode.parentNode;
		var textInput = parentNode.getElementsByTagName('INPUT')[0];
		textInput.value = this.innerHTML;	
		this.parentNode.style.display='none';	
		document.getElementById('arrowSelectBox' + parentNode.id.replace(/[^\d]/g,'')).src = arrowImageOver;
		
		if(navigator.userAgent.indexOf('MSIE')>=0)document.getElementById('selectBoxIframe' + parentNode.id.replace(/[^\d]/g,'')).style.display='none';
		
	}
	var activeOption;
	function highlightSelectBoxOption()
	{
		if(this.style.backgroundColor=='#316AC5'){
			this.style.backgroundColor='';
			this.style.color='';
		}else{
			this.style.backgroundColor='#316AC5';
			this.style.color='#FFF';			
		}	
		
		if(activeOption){
			activeOption.style.backgroundColor='';
			activeOption.style.color='';			
		}
		activeOption = this;
		
	}
	
	function createEditableSelect(dest)
	{

		dest.className='selectBoxInput';		
		var div = document.createElement('DIV');
		div.style.styleFloat = 'left';
		div.style.width = dest.offsetWidth + 16 + 'px';
		div.style.position = 'relative';
		div.id = 'selectBox' + selectBoxIds;
		var parent = dest.parentNode;
		parent.insertBefore(div,dest);
		div.appendChild(dest);	
		div.className='selectBox';
		div.style.zIndex = 10000 - selectBoxIds;

		var img = document.createElement('IMG');
		img.src = arrowImage;
		img.className = 'selectBoxArrow';
		
		img.onmouseover = selectBox_switchImageUrl;
		img.onmouseout = selectBox_switchImageUrl;
		img.onclick = selectBox_showOptions;
		img.id = 'arrowSelectBox' + selectBoxIds;

		div.appendChild(img);
		
		var optionDiv = document.createElement('DIV');
		optionDiv.id = 'selectBoxOptions' + selectBoxIds;
		optionDiv.className='selectBoxOptionContainer';
		optionDiv.style.width = div.offsetWidth-2 + 'px';
		div.appendChild(optionDiv);
		
		if(navigator.userAgent.indexOf('MSIE')>=0){
			var iframe = document.createElement('<IFRAME src="about:blank" frameborder=0>');
			iframe.style.width = optionDiv.style.width;
			iframe.style.height = optionDiv.offsetHeight + 'px';
			iframe.style.display='none';
			iframe.id = 'selectBoxIframe' + selectBoxIds;
			div.appendChild(iframe);
		}
		
		if(dest.getAttribute('selectBoxOptions')){
			var options = dest.getAttribute('selectBoxOptions').split(';');
			var optionsTotalHeight = 0;
			var optionArray = new Array();
			for(var no=0;no<options.length;no++){
				var anOption = document.createElement('DIV');
				anOption.innerHTML = options[no];
				anOption.className='selectBoxAnOption';
				anOption.onclick = selectOptionValue;
				anOption.style.width = optionDiv.style.width.replace('px','') - 2 + 'px'; 
				anOption.onmouseover = highlightSelectBoxOption;
				optionDiv.appendChild(anOption);	
				optionsTotalHeight = optionsTotalHeight + anOption.offsetHeight;
				optionArray.push(anOption);
			}
			if(optionsTotalHeight > optionDiv.offsetHeight){				
				for(var no=0;no<optionArray.length;no++){
					optionArray[no].style.width = optionDiv.style.width.replace('px','') - 22 + 'px'; 	
				}	
			}		
			optionDiv.style.display='none';
			optionDiv.style.visibility='visible';
		}
		
		selectBoxIds = selectBoxIds + 1;
	}	
	
	</script>
</head>
<body>
<h1>AI Tool3: DB</h1>
<form name="categories" method="get" action="<?php echo $_SERVER['PHP_SELF']; ?>" >
<?php




	function hlite($diffHTML) {
		
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
	
		$hliteHTML ="";	
		$currpos =0;
		for ($k=0; $k < count($delpos)+1; $k++)	{	
			//echo $currpos." ".$delpos[$k]." ".$udelpos[$k]."</br>";
			if ($k == count($delpos)) {
				$length = strlen($diffHTML)-$currpos;
			}
			else {
				$length = $delpos[$k]-$currpos;
			}
			$hliteHTML .= substr($diffHTML, $currpos, $length);
			$currpos = $udelpos[$k]+6;
		} //$k
	
		if (count($delpos) == 0) {$hliteHTML = $diffHTML;}
		
		$hliteHTML = str_replace("<ins>","<font color = red>",$hliteHTML);
		$hliteHTML = str_replace("</ins>","</font>",$hliteHTML);
		
        return $hliteHTML;
	
	} // end hlite function


	$tagger = new PosTagger('lexicon.txt');
	
	
	
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
			$html = "<label style='margin-left: 100px;'>Stories</label><select id='st' name=\"storyIDsel\" onChange=\"filterStory();\">";
			
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
			$html = "<label style='margin-left: 100px;'>Stories</label><select id='st' name=\"storyIDsel\" onChange=\"filter();\">";
			
			for($i=0; $i<$num_results; $i++){
				$row = $result->fetch_assoc();
				if($row['storyID'] == $catStory){
					$html .= "<option value=".$row['storyID']." selected>".$row['title']."</option>";
				} else {
					$html .= "<option value=".$row['storyID']." >".$row['title']."</option>";
				}
			}
			$html .= "</select>";
			
			
		}
		$html .= "<button id='copy_rep' style='position:absolute;right:1px;font-size:12px;padding:1px 9px;background:-moz-linear-gradient(center top , #F5F5F5, #F1F1F1) repeat scroll 0 0 rgba(0, 0, 0, 0);border:1px solid rgba(0, 0, 0, 0.1);color:#444444'>Copy Rep</button>";
        echo $html;
	}

?>
</form>
  



<!-- //20. RENDER PAGE CONTROLS -->
<form name="core-data" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>" >
<!-- LEFT SIDE -->
<div id="left">
	
	<div id="tabs-left">
        <ul>
            <li><a href="#tabs-1">Representation</a></li>
            <li><a href="#tabs-2">Context comment</a></li>
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


							//$bestREPguess = repguessfn($row['sentence']);
							
							$Option2 = "Alt2"; //$bestREPguess[0];  //
							$Option3 = "Alt3"; //$bestREPguess[1];  //
							
							$html .= "<div class=\"row\" id=\"row$counter\">
							<span class=\"number\">$number</span>
							<input type=\"text\" class=\"sentencetxt\" name=\"sentenceID".$row['sentenceID']."\" value=\"".$row['sentence']."\">
							<input type=\"text\" class=\"representationtxt opt\" style='overflow:hidden;width:414px' name=\"representationID".$row['sentenceID']."\" selectBoxOptions='".$row['representation'].";".$Option2.";".$Option3."' value=\"".$row['representation']."\"></div>";
							
							$counter++;
							$rows .= $row['sentenceID'].",";
							
						}
						
					}
					
					$rows = substr($rows, 0, strlen($rows) -1);
					
					echo $html;
					
				 
			?>
        </div>
        <div id="tabs-2">

    	<?php
		$html ="";
		if($num_results>0){

			mysqli_data_seek($result, 0);								
			for($i=0; $i<$num_results; $i++){
				$row = $result->fetch_assoc();			
				$html .= "<div class=\"row\">
				<input type=\"text\" class=\"CRcommenttxt\" name=\"CRcommentID".$row['sentenceID']."\" value=\"".$row['CRcomment']."\">
				</div>";
							
			}	
		}
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
            <li><a href="#tabs-1">Context representation</a></li>
            <li><a href="#tabs-2">Q &amp; A</a></li>
            <li><a href="#tabs-3">POS</a></li>
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
			
			$repI = $row['representation'];
			$crepI = $row['context_rep'];
			//echo $repI."</br>";
			
			$diff = FineDiff::getDiffOpcodes(trim($repI),trim($crepI), "");
    		$diffHTML = " ".FineDiff::renderDiffToHTMLFromOpcodes($repI, $diff);
    		$hliteHTML = hlite($diffHTML);
			
			
			$html .= "<div class=\"row\">
			<input type=\"text\" class=\"contextreptxt\" name=\"contextrepID".$row['sentenceID']."\" value=\"".$hliteHTML."\">   
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
    <div id="tabs-3">

<?php
			

		//DISPLAY POS	

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
			<input type=\"text\" class=\"POStxt\" name=\"POSID".$row['sentenceID']."\" value=\"".$row['POS']."\">       
			</div>";

			// $row['POS']   $postags

							
			}
						
		}
			
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
<p>
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
	
<script type="text/javascript">

$('#copy_rep').click(function(){

var a=confirm("Are you sure?");
if (a){
		$('.selectBoxOptionContainer').each(function(){
		
		var id=$(this).attr('id');
		var iid=id.substr(16);
		var sb='#selectBoxOptions'+iid;
		var txt=$(sb+' > div:eq(0)').html();
		
		
		var ind=(parseInt(iid))*2;	 
			
		$('.contextreptxt:eq('+ind+')').val(txt);
		$('.contextreptxt:eq('+ind+')').show();
		$('.contextreptxt:eq('+(parseInt(ind)+1)+')').html(txt);
		$('.contextreptxt:eq('+(parseInt(ind)+1)+')').hide();
		
		
	});
}
return false;
});

</script>
<script type="text/javascript">
$('.opt').each(function(){
createEditableSelect(this);
});
</script>
</html>