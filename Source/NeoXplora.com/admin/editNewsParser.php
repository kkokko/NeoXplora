<?php
include_once '../includes/config.php';
$pagetitle = "News parser";
include_once '../includes/header.php';
include_once '../includes/incArticleFunctions.php';
require_once "../NeoService/App/Global.php";

if (!UserSessionManager::LoggedIn()) {
        header('location: ' . FULLBASE . 'index.php');
        die;
    }

global $db;

date_default_timezone_set("Australia/Darwin");
		
		$myNewsItem = "";
		
		//date("Y-m-d H:i:s", $datetime)
		$action = (isset($_REQUEST['action'])) ? $_REQUEST['action'] : "";
		$intParserId = (isset($_REQUEST['parserId'])) ? $_REQUEST['parserId'] : "new";
		
		$myNewsItem->parserId = (isset($_REQUEST['parserId'])) ? $_REQUEST['parserId'] : "new";
		$myNewsItem->parserName = (isset($_REQUEST['strParserName'])) ? $_REQUEST['strParserName'] : "";
		$myNewsItem->description = (isset($_REQUEST['strDescription'])) ? $_REQUEST['strDescription'] : "";
		$myNewsItem->country = (isset($_REQUEST['strCountry'])) ? $_REQUEST['strCountry'] : "";
		$myNewsItem->beginCueTitle = (isset($_REQUEST['strBeginCueTitle'])) ?  stripslashes($_REQUEST['strBeginCueTitle']) : "";
		$myNewsItem->endCueTitle = (isset($_REQUEST['strEndCueTitle'])) ? stripslashes ($_REQUEST['strEndCueTitle']) : "";
		$myNewsItem->beginCueAuthor = (isset($_REQUEST['strBeginCueAuthor'])) ? stripslashes($_REQUEST['strBeginCueAuthor']) : "";
		$myNewsItem->endCueAuthor = (isset($_REQUEST['strEndCueAuthor'])) ? stripslashes($_REQUEST['strEndCueAuthor']) : "";
		$myNewsItem->beginCueContent = (isset($_REQUEST['strBeginCueContent'])) ? stripslashes($_REQUEST['strBeginCueContent']) : "";
		$myNewsItem->endCueContent = (isset($_REQUEST['strEndCueContent'])) ? stripslashes($_REQUEST['strEndCueContent']) : "";
		$myNewsItem->beginCueDate = (isset($_REQUEST['strBeginCueDate'])) ? stripslashes($_REQUEST['strBeginCueDate']) : "";
		$myNewsItem->endCueDate = (isset($_REQUEST['strEndCueDate'])) ? stripslashes($_REQUEST['strEndCueDate']) : "";
		$myNewsItem->beginCueSection = (isset($_REQUEST['strBeginCueSection'])) ? stripslashes($_REQUEST['strBeginCueSection']) : "";
		$myNewsItem->endCueSection = (isset($_REQUEST['strEndCueSection'])) ?stripslashes( $_REQUEST['strEndCueSection']) : "";
		$myNewsItem->beginCueImageURL = (isset($_REQUEST['strBeginCueImageURL'])) ? stripslashes($_REQUEST['strBeginCueImageURL']) : "";
		$myNewsItem->endCueImageURL = (isset($_REQUEST['strEndCueImageURL'])) ? stripslashes($_REQUEST['strEndCueImageURL']) : "";
		$myNewsItem->beginCueImageCaption = (isset($_REQUEST['strBeginCueImageCaption'])) ? stripslashes($_REQUEST['strBeginCueImageCaption']) : "";
		$myNewsItem->endCueImageCaption = (isset($_REQUEST['strEndCueImageCaption'])) ? stripslashes($_REQUEST['strEndCueImageCaption']) : "";
		$myNewsItem->beginCueImageCaption2 = (isset($_REQUEST['strBeginCueImageCaption2'])) ? stripslashes($_REQUEST['strBeginCueImageCaption2']) : "";
		$myNewsItem->endCueImageCaption2 = (isset($_REQUEST['strEndCueImageCaption2'])) ? stripslashes($_REQUEST['strEndCueImageCaption2']) : "";
		$myNewsItem->beginCueIntro = (isset($_REQUEST['strBeginCueIntro'])) ? stripslashes($_REQUEST['strBeginCueIntro']) : "";
		$myNewsItem->endCueIntro = (isset($_REQUEST['strEndCueIntro'])) ? stripslashes( $_REQUEST['strEndCueIntro']) : "";
		$myNewsItem->beginCueRmExtraContent = (isset($_REQUEST['strBeginCueRmExtraContent'])) ? stripslashes($_REQUEST['strBeginCueRmExtraContent']) : "";
		$myNewsItem->endCueRmExtraContent = (isset($_REQUEST['strEndCueRmExtraContent'])) ? stripslashes( $_REQUEST['strEndCueRmExtraContent']) : "";
		$myNewsItem->beginCueRmExtraContent2 = (isset($_REQUEST['strBeginCueRmExtraContent2'])) ? stripslashes($_REQUEST['strBeginCueRmExtraContent2']) : "";
		$myNewsItem->endCueRmExtraContent2 = (isset($_REQUEST['strEndCueRmExtraContent2'])) ? stripslashes( $_REQUEST['strEndCueRmExtraContent2']) : "";
		$myNewsItem->beginCueRmExtraContent3 = (isset($_REQUEST['strBeginCueRmExtraContent3'])) ? stripslashes($_REQUEST['strBeginCueRmExtraContent3']) : "";
		$myNewsItem->endCueRmExtraContent3 = (isset($_REQUEST['strEndCueRmExtraContent3'])) ? stripslashes( $_REQUEST['strEndCueRmExtraContent3']) : "";
		$myNewsItem->beginCueRmExtraContent4 = (isset($_REQUEST['strBeginCueRmExtraContent4'])) ? stripslashes($_REQUEST['strBeginCueRmExtraContent4']) : "";
		$myNewsItem->endCueRmExtraContent4 = (isset($_REQUEST['strEndCueRmExtraContent4'])) ? stripslashes( $_REQUEST['strEndCueRmExtraContent4']) : "";
		
		$myNewsItem->strArticleURL = (isset($_REQUEST['strArticleURL'])) ? $_REQUEST['strArticleURL'] : "";
		
		$myNewsItem->parserURLId = (isset($_REQUEST['parserURLId'])) ? $_REQUEST['parserURLId'] : "new";
		
	

		if($action == "edit" && $intParserId != "new")
		{
			$qry='SELECT * FROM newsParsers WHERE parserId="' . $intParserId . '"';
			$qryRes = dbQuery($db,$qry);
			$objNewsItem = dbFetchObject($qryRes);
			$myNewsItem = $objNewsItem;
		}
		else if($action=="commitChanges")
		{
            //echo "commitChanges :" . $myNewsItem->newsItemID;exit;
			if($myNewsItem->parserId == "new")//if creating a new article
			{
				addParser($myNewsItem);
				header("Location: " .FULLBASE ."admin/newsParser.php");
			}
			else//if article exists
			{   //echo "INSIDE ELSE CONDITION";exit;
				editParser($myNewsItem);
				header("Location: " .FULLBASE ."admin/newsParser.php");
			}
		}
		else if($action=="deleteParser")
		{
			deleteParser($myNewsItem);
			header("Location: " .FULLBASE ."admin/newsParser.php");
		}
		else if($action=="deleteParserURL")
		{
			deleteParserURL($myNewsItem);
			header("Location: " .FULLBASE ."admin/editNewsParser.php?action=edit&parserId=".$myNewsItem->parserId);
		}
		else if($action=="testParser")
		{
			//echo "om";
			$arrParser = testParser($myNewsItem);
			if(!empty($arrParser['title']) && !empty($arrParser['content']))
			{
				$myNewsItem->strParserFlag = 'PASS';
			}
			else
			{
				$myNewsItem->strParserFlag = 'FAIL';
			}
			
			addParserURL($myNewsItem);
			
			//echo '<PRE>';print_r($arrParser);
			//exit;	
		}
		else 
		{
			
		}
		
?>
<script language="text/javascript" src="<?php echo FULLBASE; ?>admin/templates/jquery.dataTables.min.js" type="text/javascript"></script>
<link rel="stylesheet" type="text/css" href="<?php echo FULLBASE; ?>admin/templates/datatable.css" />
<link rel="stylesheet" type="text/css" href="<?php echo FULLBASE; ?>admin/templates/datatableui.css" />

<script language="text/javascript" src="<?php echo FULLBASE; ?>admin/js/tabview.js" type="text/javascript"></script>
<link rel="stylesheet" type="text/css" href="<?php echo FULLBASE; ?>admin/js/tabview.css?v=1.0" />



<style>
.content {
	width:70%;
	float:left;
	/*margin-top:20px;*/
	margin-left:20px;
	font-family: Verdana,Geneva,sans-serif;
}
</style>

<div class="content">
    <div class="container relative">
        
  
	<div class="editArticleDiv">
   <script language="JavaScript" type="text/javascript">
	function testParserfun(parserId)
	{
		document.editForm.action = '<?php echo FULLBASE; ?>admin/editNewsParser.php?action=testParser&parserId=' + parserId;
		//alert(document.editForm.tagName);
		document.editForm.submit();
	}
	</script>
	<form name="editForm" method ="POST" action = "?action=commitChanges&parserId=<?php echo $intParserId ?>" >
		<table width="100%" border="0" cellspacing="5" cellpadding="3" style="border-bottom:1px solid #eee;margin-top:3px;font-size:14px;">
			<!--<tr class="tableOddRow">
				<td class="tableUser" colspan="2"> <div style="border-bottom:2px solid #006;text-align:center;"> <H2>News Parser Form</H2></div></td>
			</tr>-->
			<tr class="tableEvenRow">
				<td class="tableUser" colspan="2" align="center"><!--<br />-->
					<a href="<?php echo FULLBASE; ?>admin/newsParser.php">Back to Parser Table</a>
					<input type="submit" Name = "submitParser" VALUE = "Add/Edit Parser" /><br /><br />
				</td>
			</tr>
			<tr class="tableOddRow">
				<td class="tableUserLeft" style="width:20%;"><strong>Parser Name:</strong></td>
				<td class="tableUser"> <input class="required" type="text" name="strParserName" id="strParserName" value="<?php echo $myNewsItem->parserName ?>" size="50" /> </td>
			</tr>
            
            <tr class="tableOddRow">
				<td class="tableUserLeft" style="width:20%;" valign="top"><strong>Description:</strong></td>
				<td class="tableUser"> 
                	<textarea name="strDescription" id="strDescription" rows="6" cols="85" ><?php echo $myNewsItem->description; ?></textarea>
                </td>
			</tr>
            
            <tr class="tableOddRow">
				<td class="tableUserLeft" style="width:20%;"><strong>Country:</strong></td>
				<td class="tableUser"> <?php echo countryDropDown($myNewsItem->country, "strCountry"); ?>  </td>
			</tr>
            
             <!--<tr class="tableOddRow">
				<td colspan="2"> <br /><br /></td>
			</tr>-->
            
            <tr class="tableOddRow">
				<td colspan="2">
                <table width="100%" border="0" cellspacing="5" cellpadding="3" >
                    <tr>
                        <td style="width:21%;"></td>
                        <td ><strong>Begin</strong></td>
                        <td ><strong>End</strong></td>
                     </tr>
                     
                     <tr>
                        <td ><strong>Title:</strong></td>
                        <td ><input class="required" type="text" name="strBeginCueTitle" id="strBeginCueTitle" value="<?php echo htmlspecialchars($myNewsItem->beginCueTitle); ?>" size="50" /></td>
                        <td ><input class="required" type="text" name="strEndCueTitle" id="strEndCueTitle" value="<?php echo htmlspecialchars($myNewsItem->endCueTitle); ?>" size="50" /></td>
                     </tr>
                     
                     <tr>
                        <td ><strong>Author:</strong></td>
                        <td ><input class="required" type="text" name="strBeginCueAuthor" id="strBeginCueAuthor" value="<?php echo htmlspecialchars($myNewsItem->beginCueAuthor); ?>" size="50" /></td>
                        <td ><input class="required" type="text" name="strEndCueAuthor" id="strEndCueAuthor" value="<?php echo htmlspecialchars($myNewsItem->endCueAuthor); ?>" size="50" /></td>
                     </tr>
                     
                     <tr>
                        <td ><strong>Content:</strong></td>
                        <td ><input class="required" type="text" name="strBeginCueContent" id="strBeginCueContent" value="<?php echo htmlspecialchars($myNewsItem->beginCueContent); ?>" size="50" /></td>
                        <td ><input class="required" type="text" name="strEndCueContent" id="strEndCueContent" value="<?php echo htmlspecialchars($myNewsItem->endCueContent); ?>" size="50" /></td>
                     </tr>
                     
                     <tr>
                        <td ><strong>Date:</strong></td>
                        <td ><input class="required" type="text" name="strBeginCueDate" id="strBeginCueDate" value="<?php echo htmlspecialchars($myNewsItem->beginCueDate); ?>" size="50" /></td>
                        <td ><input class="required" type="text" name="strEndCueDate" id="strEndCueDate" value="<?php echo htmlspecialchars($myNewsItem->endCueDate); ?>" size="50" /></td>
                     </tr>
                     
                     <tr>
                        <td ><strong>Section:</strong></td>
                        <td ><input class="required" type="text" name="strBeginCueSection" id="strBeginCueSection" value="<?php echo htmlspecialchars($myNewsItem->beginCueSection); ?>" size="50" /></td>
                        <td ><input class="required" type="text" name="strEndCueSection" id="strEndCueSection" value="<?php echo htmlspecialchars($myNewsItem->endCueSection); ?>" size="50" /></td>
                     </tr>
                     
                     <tr>
                        <td ><strong>Image URL:</strong></td>
                        <td ><input class="required" type="text" name="strBeginCueImageURL" id="strBeginCueImageURL" value="<?php echo htmlspecialchars($myNewsItem->beginCueImageURL); ?>" size="50" /></td>
                        <td ><input class="required" type="text" name="strEndCueImageURL" id="strEndCueImageURL" value="<?php echo htmlspecialchars($myNewsItem->endCueImageURL); ?>" size="50" /></td>
                     </tr>
                     
                     <tr>
                        <td ><strong>Image Caption:</strong></td>
                        <td ><input class="required" type="text" name="strBeginCueImageCaption" id="strBeginCueImageCaption" value="<?php echo htmlspecialchars($myNewsItem->beginCueImageCaption); ?>" size="50" /></td>
                        <td ><input class="required" type="text" name="strEndCueImageCaption" id="strEndCueImageCaption" value="<?php echo htmlspecialchars($myNewsItem->endCueImageCaption); ?>" size="50" /></td>
                     </tr>
                     
                     <tr>
                        <td ><strong>Image Caption:</strong></td>
                        <td ><input class="required" type="text" name="strBeginCueImageCaption2" id="strBeginCueImageCaption2" value="<?php echo htmlspecialchars($myNewsItem->beginCueImageCaption2); ?>" size="50" /></td>
                        <td ><input class="required" type="text" name="strEndCueImageCaption2" id="strEndCueImageCaption2" value="<?php echo htmlspecialchars($myNewsItem->endCueImageCaption2); ?>" size="50" /></td>
                     </tr>
                     
                     <tr>
                        <td ><strong>Intro:</strong></td>
                        <td ><input class="required" type="text" name="strBeginCueIntro" id="strBeginCueIntro" value="<?php echo htmlspecialchars($myNewsItem->beginCueIntro); ?>" size="50" /></td>
                        <td ><input class="required" type="text" name="strEndCueIntro" id="strEndCueIntro" value="<?php echo htmlspecialchars($myNewsItem->endCueIntro); ?>" size="50" /></td>
                     </tr>
                     
                     <tr>
                        <td ><strong>Remove Extra Content:</strong></td>
                        <td ><input class="required" type="text" name="strBeginCueRmExtraContent" id="strBeginCueRmExtraContent" value="<?php echo htmlspecialchars($myNewsItem->beginCueRmExtraContent); ?>" size="50" /></td>
                        <td ><input class="required" type="text" name="strEndCueRmExtraContent" id="strEndCueRmExtraContent" value="<?php echo htmlspecialchars($myNewsItem->endCueRmExtraContent); ?>" size="50" /></td>
                     </tr>
                     
                      <tr>
                        <td ><strong>Remove Extra Content:</strong></td>
                        <td ><input class="required" type="text" name="strBeginCueRmExtraContent2" id="strBeginCueRmExtraContent2" value="<?php echo htmlspecialchars($myNewsItem->beginCueRmExtraContent2); ?>" size="50" /></td>
                        <td ><input class="required" type="text" name="strEndCueRmExtraContent2" id="strEndCueRmExtraContent2" value="<?php echo htmlspecialchars($myNewsItem->endCueRmExtraContent2); ?>" size="50" /></td>
                     </tr>
                     
                     <tr>
                        <td ><strong>Remove Extra Content:</strong></td>
                        <td ><input class="required" type="text" name="strBeginCueRmExtraContent3" id="strBeginCueRmExtraContent3" value="<?php echo htmlspecialchars($myNewsItem->beginCueRmExtraContent3); ?>" size="50" /></td>
                        <td ><input class="required" type="text" name="strEndCueRmExtraContent3" id="strEndCueRmExtraContent3" value="<?php echo htmlspecialchars($myNewsItem->endCueRmExtraContent3); ?>" size="50" /></td>
                     </tr>
                     
                     <tr>
                        <td ><strong>Remove Extra Content:</strong></td>
                        <td ><input class="required" type="text" name="strBeginCueRmExtraContent4" id="strBeginCueRmExtraContent4" value="<?php echo htmlspecialchars($myNewsItem->beginCueRmExtraContent4); ?>" size="50" /></td>
                        <td ><input class="required" type="text" name="strEndCueRmExtraContent4" id="strEndCueRmExtraContent4" value="<?php echo htmlspecialchars($myNewsItem->endCueRmExtraContent4); ?>" size="50" /></td>
                     </tr>
                     
                 </table>
                </td>
             </tr>
			            
            <!--<tr class="tableOddRow">
				<td colspan="2"> <br /><br /></td>
			</tr>-->
            
            <tr class="tableOddRow">
				<td class="tableUserLeft"><strong>URL:</strong></td>
				<td class="tableUser"> 
                	<input class="required" type="text" name="strArticleURL" id="strArticleURL"  size="95" value="<?php echo $myNewsItem->strArticleURL; ?>" /> &nbsp;&nbsp;
                	<input type="button" name="testParser" value="Test Parser"  onclick="return testParserfun('<?php echo $intParserId ?>');" />
                </td>
			</tr>
            
            <!--<tr class="tableOddRow">
				<td colspan="2"> <br /><br /></td>
			</tr>-->
            
            <?php
			
			$cnt = 0;
			
			if(isset($arrParser) && $arrParser['content'] != '' && !empty($arrParser['content']))
		    { 
										
				$ThePOSs = $server->GetPosForPage(strip_tags($arrParser['content']), true);
				
				$arrPOSNouns = array();
				$strPOSNouns = '';
				for($i = 0; $i < $ThePOSs->Count(); $i++) {
					//echo "sentence: ".($i + 1)."<br>";
					$theSentence = $ThePOSs->Object($i);
					for($j = 0; $j < $theSentence->Count(); $j++) {
					 //  echo $theSentence->Item($j) . "(" . $theSentence->Object($j) .") ";
					   $flgBR = 0;
					   if($theSentence->Object($j) == 'NNP' || $theSentence->Object($j) == 'NNPS')
					   {
						   
						   if (!in_array($theSentence->Item($j), $arrPOSNouns)) {
							$strPOSNouns .= $theSentence->Item($j);
							$cnt++;
							$flgBR = 1;
						   }
						   $arrPOSNouns[] = $theSentence->Item($j);
						   
						   
						   if($theSentence->Object($j+1) == 'NNP' || $theSentence->Object($j+1) == 'NNPS')
						   {
								if (!in_array($theSentence->Item($j+1), $arrPOSNouns)) {
									$strPOSNouns .= '&nbsp;'.   $theSentence->Item($j+1);
									$cnt++;
									$flgBR = 1;
								}
								
								$arrPOSNouns[] = $theSentence->Item($j+1);
								$j++;
								
						   }
						   
						   if($flgBR == 1)
						   {
							 $strPOSNouns .= '<br />';
						   }
					   }
					}
				  }
				
		    }
						   
		   ?>
           
            <tr class="tableOddRow">
				<td colspan="2">
                	<div class="TabView" id="TabView">

                        <!-- *** Tabs ************************************************************** -->
                        
                        <div class="Tabs" style="width: 100%;">
                          <a>Parsed </a>
                          <a>Source </a>
                          <a>Pass </a>
                          <a>Fail </a>
                          <a>Proper nouns (<?php echo $cnt; ?>)</a>
                        </div>
                        
                        <!-- *** Pages ************************************************************* -->
                        
                        <div class="Pages" style="width: 100%; height: 400px;">
                        
                          <div class="Page">
                          <div class="Pad">
                        
                          <!-- *** Page1 Start *** -->
                        <?php //echo '<PRE>'; print_r($arrParser); ?>
                          <br />
                          <table>
							  <?php if(isset($arrParser) && $arrParser['title'] != '') { ?>
                              <tr>
                                <td style="font-size:14px;"><strong>Title: </strong></td>
                              </tr>
                              <tr>
                                <td ><?php echo strip_tags($arrParser['title']); ?></td>
                              </tr>
                              <?php } ?>
                              
                         	 <?php if(isset($arrParser) && $arrParser['author'] != '') { 
							 $strAuthorDis = strip_tags(str_replace('By','',str_replace('by:','',$arrParser['author'])));
							 if(!empty($strAuthorDis) && $strAuthorDis != ' ')
							 {
							 ?>
                             <tr>
                                <td style="font-size:14px;padding-top:10px;"><strong>Author: </strong></td>
                              </tr>
                              <tr>
                                <td ><?php echo $strAuthorDis; ?></td>
                              </tr>
                              <?php } } ?>
                              
                             <?php if(isset($arrParser) && $arrParser['date'] != '') { ?>
                             <tr>
                                <td style="font-size:14px;padding-top:10px;"><strong>Date: </strong></td>
                              </tr>
                              <tr>
                                <td ><?php echo str_ireplace($strAuthorDis,'',str_ireplace('By','',strip_tags($arrParser['date']))); ?></td>
                              </tr>
                              <?php } ?>
                              
                              <?php if(isset($arrParser) && $arrParser['imageurl'] != '' && count($arrParser['imageurl']) > 0) { ?>
                             <tr>
                                <td style="font-size:14px;padding-top:10px;"><strong>Image: </strong></td>
                              </tr>
                              <style>
							  img{vertical-align:top;max-width:800px;}
							  </style>
                              <?php 
							  $k=1;
							  foreach ($arrParser['imageurl'] as $key => $value){
								  	if(strpos($value, 'display:none') === false)
									{
								   ?>
                              <tr>
                                <td valign="top" style="padding-top:10px;vertical-align:top;" ><?php echo $k++.')&nbsp;&nbsp;'.$value; ?></td>
                              </tr>
                              <?php } } ?>
                              <?php } ?>
                              
                              <?php if(isset($arrParser) && ($arrParser['imagecaption'] != '' && count($arrParser['imagecaption']) > 0) || ($arrParser['imagecaption2'] != '' && count($arrParser['imagecaption2'])>0)) { ?>
                             <tr>
                                <td style="font-size:14px;padding-top:10px;"><strong>Image Caption: </strong></td>
                              </tr>
                             <?php 
							 $k = 1;
							 foreach ($arrParser['imagecaption'] as $key => $value){
								 //$value = str_replace($arrResult['imageurl'], "", $value);
								 
										if(!empty($value) )
										{ 
							 ?>
                              <tr>
                                <td  valign="top" style="padding-top:10px;vertical-align:top;" ><?php echo $k++.')&nbsp;&nbsp;'.strip_tags(str_replace($myNewsItem->beginCueImageCaption,'',str_replace('itemprop="image"/>','',$value))); ?></td>
                              </tr>
                              <?php } } ?>
                             <?php  
							 	if(count($arrParser['imagecaption2']) > 0)
								{
							 	foreach ($arrParser['imagecaption2'] as $key => $value){
								 //$value = str_replace($arrResult['imageurl'], "", $value);
								 
										if(!empty($value) )
										{ 
							 ?>
                              <tr>
                                <td  valign="top" style="padding-top:10px;vertical-align:top;" ><?php echo $k++.')&nbsp;&nbsp;'.strip_tags(str_replace($myNewsItem->beginCueImageCaption2,'',str_replace('itemprop="image"/>','',$value))); ?></td>
                              </tr>
                              <?php } } ?>
                              <?php } } ?>
                              
                               <?php if(isset($arrParser) && $arrParser['intro'] != '') { ?>
                             <tr>
                                <td style="font-size:14px;padding-top:10px;"><strong>Intro: </strong></td>
                              </tr>
                              <tr>
                                <td ><?php echo $arrParser['intro']; ?></td>
                              </tr>
                              <?php } ?>
                              
                              <?php if(isset($arrParser) && $arrParser['content'] != '') { ?>
                             <tr>
                                <td style="font-size:14px;padding-top:10px;"><strong>Content: </strong></td>
                              </tr>
                              <tr>
                                <td ><?php echo $arrParser['content']; ?></td>
                              </tr>
                              <?php } ?>
                              
                              <?php if(isset($arrParser) && $arrParser['section'] != '') { ?>
                             <tr>
                                <td style="font-size:14px;padding-top:10px;"><strong>Section: </strong></td>
                              </tr>
                              <tr>
                                <td ><?php echo $arrParser['section']; ?></td>
                              </tr>
                              <?php } ?>


                          </table>
                        
                          <!-- *** Page1 End ***** -->
                        
                          </div>
                          </div>
                        
                          <!-- *** Page2 Start *** -->
                          <div class="Page">
                          <div class="Pad" style="width: 100%; height: 400px;">
                          
                        	<textarea id="_src_" style="width:100%;height:400px;"  wrap="virtual"> 
                            <?php if(isset($arrParser) && $arrParser['urlContent'] != '') { 
                           echo htmlspecialchars($arrParser['urlContent']); 
                          } ?>
                        	</textarea>
                         
                        
                          </div>
                          </div>
                           <!-- *** Page2 End ***** -->
                           
                           <!-- *** Page3 Start *** -->
                          <div class="Page">
                          <div class="Pad" style="width: 100%; height: 400px;">
                          
                          <script language="JavaScript" type="text/javascript">
							function getParser(parserId, parserURL){
								document.editForm.strArticleURL.value = parserURL;
								testParserfun(parserId);
							}
							
							function deletePassFailLink(parserURLId, parserId){
								window.location = ('<?php echo FULLBASE; ?>admin/editNewsParser.php?action=deleteParserURL&parserURLId=' + parserURLId+'&parserId='+parserId);
							}
						</script>
                        
                          <?php $arrPassParserURL = getParserURL($myNewsItem->parserId, 'PASS'); ?>
                        	
                           <table width="100%" border="0">
                           <?php if(dbGetRows($arrPassParserURL) ) 
						   			{
										while($arrPassRes = dbFetchObject($arrPassParserURL))
										{ 
											if($arrPassRes->parserURL != '')
											{
										?>
                                           <tr>
                                                <td><a href="javascript:void(0)" onclick="getParser('<?php echo $myNewsItem->parserId; ?>', '<?php echo $arrPassRes->parserURL; ?>');"><?php echo $arrPassRes->parserURL; ?></a></td>
                                                <td width="4%" align="center"><a href="javascript:void(0)" title="Delete" onClick="if(confirm('Are you sure you want to delete this link? '))  deletePassFailLink('<?php echo $arrPassRes->parserURLId ?>','<?php echo $myNewsItem->parserId; ?>');"><img src="images/delete1.png" border="0"></a></td>
                                           </tr>
                            <?php		
											}
										}
									}
							?>
                           </table>
                         
                        
                          </div>
                          </div>
                           <!-- *** Page3 End ***** -->
                           
                           <!-- *** Page4 Start *** -->
                          <div class="Page">
                          <div class="Pad" style="width: 100%; height: 400px;">
                          
                        	<?php $arrFailParserURL = getParserURL($myNewsItem->parserId, 'FAIL'); ?>
                        	
                           <table width="100%" border="0">
                           <?php if(dbGetRows($arrFailParserURL) ) 
						   			{
										while($arrFailRes = dbFetchObject($arrFailParserURL))
										{ 
											if($arrFailRes->parserURL != '')
											{
										?>
                                           <tr>
                                                <td><a href="javascript:void(0)" onclick="getParser('<?php echo $myNewsItem->parserId; ?>', '<?php echo $arrFailRes->parserURL; ?>');"><?php echo $arrFailRes->parserURL; ?></a></td>
                                                <td width="4%" align="center"><a href="javascript:void(0)" title="Delete" onClick="if(confirm('Are you sure you want to delete this link? '))  deletePassFailLink('<?php echo $arrFailRes->parserURLId ?>','<?php echo $myNewsItem->parserId; ?>');"><img src="images/delete1.png" border="0"></a></td>
                                           </tr>
                            <?php		
											}
										}
									}
							?>
                           </table>
                            </div>
                          </div>
                           <!-- *** Page4 End ***** -->
                           
                           <!-- *** Page5 Start *** -->
                          <div class="Page">
                          <div class="Pad" style="width: 100%; height: 400px;">
                          
                           <?php 
						   if(isset($arrParser) && $arrParser['content'] != '')
						   { 							
						   ?>	
                               <table width="100%" border="0">
                                   <tr>
                                        <td>
											<?php  
                                              echo $strPOSNouns;
                                            ?>
                                        </td>
                                   </tr>
                               </table>
                           <?php } ?>
                        
                          </div>
                          </div>
                           <!-- *** Page5 End ***** -->
                           
                         </div>
                       </div>
                </td>
			</tr>
            
            <tr class="tableOddRow">
				<td colspan="2"> <br /><br /></td>
			</tr>
		
		</table>
	</form>

	</div>
    </div>
</div>
 
<script type="text/javascript">

tabview_initialize('TabView');

</script>
<?php
//include_once '../includes/footer.php';
?>