<?php

function replaceInvalidChars($AString){
  $quotes = array(
        "\xC2\xAB"   => '"', // « (U+00AB) in UTF-8
        "\xC2\xBB"   => '"', // » (U+00BB) in UTF-8
        "\xE2\x80\x98" => "'", // ‘ (U+2018) in UTF-8
        "\xE2\x80\x99" => "'", // ’ (U+2019) in UTF-8
        "\xE2\x80\x9A" => "'", // ‚ (U+201A) in UTF-8
        "\xE2\x80\x9B" => "'", // ‛ (U+201B) in UTF-8
        "\xE2\x80\x9C" => '"', // “ (U+201C) in UTF-8
        "\xE2\x80\x9D" => '"', // ” (U+201D) in UTF-8
        "\xE2\x80\x9E" => '"', // „ (U+201E) in UTF-8
        "\xE2\x80\x9F" => '"', // ‟ (U+201F) in UTF-8
        "\xE2\x80\xB9" => "'", // ‹ (U+2039) in UTF-8
        "\xE2\x80\xBA" => "'", // › (U+203A) in UTF-8
        "\r" => "",
        "\n" => " ",
        "\t" => " ",
        "\xe2\x80\x94" => '-',
        "\xe2\x80\x93" => '-'
        
        //http://www.i18nqa.com/debug/table-iso8859-1-vs-windows-1252.html  special characters 
    );
    return strtr($AString, $quotes);
}

function splitIntoSentences($AString, $isBody = true){
	$sentences = array();
	$currentSentence = "";
	$inQuote = false;
	$abbreviations = array(" Mr.", " Mrs.", " Ms.", " e.g.", " etc.", " i.e.", " Dr.", " Prof.", " Sr.", " Jr.", " No.", " St.", "p.m."); 
	//replace quotes

  $AString = replaceInvalidChars(htmlspecialchars_decode($AString));
  
  //If the ENTIRE story is INSIDE ONLY one open and ONLY one closed quote, please remove them BEFORE processing.
  if( $isBody && (substr_count($AString, '"') == 2) && ($AString[0] == '"') && ($AString[strlen($AString)-1] == '"') ){
    $AString = str_replace ('"', '', $AString);
  }
  
	for($i=0; $i< strlen($AString); $i++){
    if ($AString[$i] == '"'){
			$currentSentence .= $AString[$i];
			$inQuote = !$inQuote;
			continue;			
		}
		if( ( (($AString[$i] == ".") || ($AString[$i] == "!") || ($AString[$i] == "?") )  && (!$inQuote)) ){    
			if(trim($currentSentence) == ''){
				continue;
			}
			if( ($AString[$i] == "!") || ($AString[$i] == "?") ){
			  $currentSentence .= $AString[$i];
			}
      
      //Check for possible abbreviations that shouldn't break into sentences
      if( $AString[$i] == ".") {
        $ignore = false;
        foreach($abbreviations as $abbreviation) {
          $flag = true;
          for($j = 0; $j < strlen($abbreviation); $j++) {
            $index = $i - strlen($abbreviation) + $j + 1;
            if($index < 0 || $index >= strlen($AString) || strtolower($abbreviation[$j]) != strtolower($AString[$index])) {
              $flag = false;
              break;
            }
          }
          if($flag) {
            $ignore = true;
            break;
          }
        }
        if($ignore) {
          $currentSentence .= $AString[$i];
          continue;
        }
      }
      
      /*if( ($AString[$i] == ".") && ($i >= 0) && $i ){
        $currentSentence .= $AString[$i];
      }*/     				
			array_push($sentences, trim($currentSentence));
			$currentSentence = "";
			continue;
		}
		$currentSentence .= $AString[$i];
	}
  if(trim($currentSentence) != ''){
    array_push($sentences, $currentSentence);
  }
	return $sentences;
}

function searchStories($query, $exact_match = false) {
  $q = "SELECT s.`pageID`, s.`title`, s.`user`, c.`category`, s.`is_set`, s.`is_finished`, s.`is_checked`, s.`is_assigned`, s.`can_overwrite`, s.`reps_added_by` FROM `page` s LEFT JOIN `category` c ON s.`categoryID` = c.`categoryID`";
  if($exact_match) {
    $q .= " WHERE `title` = '" . mysql_real_escape_string($query) . "'";
  } else {
    $q .= " WHERE `title` LIKE '%" . mysql_real_escape_string($query) . "%'";
  }
  $q .= " ORDER BY `pageID` ASC";
  
  $result = mysql_query($q);
  if($result && mysql_num_rows($result))
    return $result;
  else 
    return false;
}

define("_VALID_PHP", true);
require_once ("init.php");

// delete Story
if (isset($_REQUEST['sentenceID'])  && isset($_REQUEST['type']) && $_REQUEST['type']=='getStoryID'   )
{   dbCon();
   $id=$_REQUEST['sentenceID'];   

   $sql="select *  from sentence where sentenceID='".$id."'";
   $result = mysql_query($sql) or die('MySql Error' . mysql_error());        /* START CREP */ 

while ($rows = mysql_fetch_array($result)) {   
echo $rows['pageID'];
}

}

  // delete Story
  if(isset($_REQUEST['id'])  && isset($_REQUEST['action']) && $_REQUEST['action'] == 'Delete' && isset($_REQUEST['agree'])) {
    dbCon();
    if($_REQUEST['agree'] == 'true') {
      $id = $_REQUEST['id'];   
      $action = $_REQUEST['action'];
      $result = mysql_fetch_array(mysql_query("SELECT `user` FROM `page` WHERE `pageID` = '" . $id . "'"));
      if($user->logged_in && ($user->userlevel == 8 || $user->userlevel == 9 || $result['user'] == $user->username)) {
        $sql = "DELETE FROM `page` WHERE `pageID` = '" . $id . "'";
        if(mysql_query($sql) === TRUE){ 
        
          //delete sentences 
          mysql_query("DELETE FROM `sentence` WHERE `pageID` = '" . $id . "'");
          //delete protosentence
          mysql_query("DELETE FROM `proto` WHERE `pageID` = '" . $id . "'");
        
          echo "1";
        } else
          echo "0";
      }
    }
  }

  // add/edit Story
  if(isset($_REQUEST['id']) && isset($_REQUEST['title']) && isset($_REQUEST['detail']) && isset($_REQUEST['Cat1']) && isset($_REQUEST['action'])) {
    dbCon();
    $id = $_REQUEST['id'];   
    $title = replaceInvalidChars(trim($_REQUEST['title']));
    $detail = replaceInvalidChars(trim($_REQUEST['detail']));
    $cat = $_REQUEST['Cat1'];
    $action = $_REQUEST['action'];   
     
    if( (substr_count($detail, '"') == 2) && ($detail[0] == '"') && ($detail[strlen($detail)-1] == '"') ){
      $detail = str_replace ('"', '', $detail);
    }
     
    include 'PosTagger.php';
    $tagger = new PosTagger('lexicon.txt');
    $sentences = splitIntoSentences(trim($_REQUEST['detail']));//preg_split("/\./", $detail);
    
    if($action == 'Add Story') {
      if(!searchStories($title, true)) {
        $sql = "INSERT INTO `page` (`title`, `body`, `categoryID`, `user`) VALUES ('" . mysql_real_escape_string($title) . "', '" . mysql_real_escape_string($detail) . "', '$cat', '" . $user->username . "')";
        if(mysql_query($sql) === TRUE) {
      		$storyID = mysql_insert_id();
      		for($i = 0; $i < count($sentences); $i++) {
      	        $sentence = trim($sentences[$i]);
      	        if($sentence == "") continue;
      	        $tags = $tagger->tag($sentence);
      	        $pos = "";
      	        foreach($tags as $tag) {
      	          $pos .= $tag['tag'] . " ";
      	        }
      	        $pos = trim($pos);
                mysql_query("INSERT INTO `proto`(`name`, `level`, `pageID`) VALUES ('" . mysql_real_escape_string($sentence) . "', '1','" . $storyID . "')");
                $pr1ID = mysql_insert_id();
                mysql_query("INSERT INTO `proto`(`name`, `level`, `pageID`) VALUES ('" . mysql_real_escape_string($sentence) . "', '2','" . $storyID . "')");
                $pr2ID = mysql_insert_id();
      	        mysql_query("INSERT INTO `sentence`(`sentence`, `pr2ID`, `pr1ID`, `pageID`, `POS`) VALUES ('" . mysql_real_escape_string($sentence) . "', '" . $pr2ID . "','" . $pr1ID . "','" . $storyID . "', '" . $pos . "')");
      	    }
                    
            $resultArray = array('storyId' => $storyID, 'success' => '1', 'message'=>'');
            echo json_encode(array_map('utf8_encode', $resultArray));    
        } else {
          $resultArray  =array('storyId' => 0, 'success' => '0', 'message'=>mysql_error);
          echo json_encode(array_map('utf8_encode', $resultArray)); 
        }
      } else {
        $resultArray  =array('storyId' => 0, 'success' => '0', 'message'=> 'A story with that title already exists.');
        echo json_encode(array_map('utf8_encode', $resultArray));
      }
    } else if($action == 'Update Story') {
      $result = mysql_fetch_array(mysql_query("SELECT * FROM `page` WHERE `pageID` = '" . $id . "'"));
      if(($result['title'] != $title && !searchStories($title, true)) || $result['title'] == $title) {
        if($user->logged_in && ($user->userlevel == 8 || $user->userlevel == 9 || $result['user'] == $user->username)) {
       	  $sql = "UPDATE `page` SET";
       	  if($result['title'] != $title) $sql .= " `title` = '". mysql_real_escape_string($title) ."',";
          if($result['body'] != $detail) $sql .= " `body` = '". mysql_real_escape_string($detail) ."',";
          if($result['categoryID'] != $cat) $sql .= " `categoryID` = '".$cat."',";
          $sql = substr($sql, 0, strlen($sql) - 1);
          $sql .= " WHERE `pageID` = '" . $id . "'";
          if(($result['title'] == $title && $result['body'] == $detail && $result['categoryID'] == $cat) || mysql_query($sql) === TRUE) {
            
            //calculate page number to show
            $cntPagequery = mysql_query('select count(*) as cntPage from `page` where `pageID` <= '.$id);
            $cntPage = 0;
            if($cntPagequery){
              $row = mysql_fetch_array($cntPagequery);            
              $cntPage = $row['cntPage'];
            }
            
            $storyID = $id;
            if($result['body'] != $detail) {
              mysql_query("DELETE FROM `sentence` WHERE `pageID` = '" . $storyID . "'");
              mysql_query("DELETE FROM `proto` WHERE `pageID` = '" . $storyID . "'");
              
              for($i = 0; $i < count($sentences); $i++) {
                $sentence = trim($sentences[$i]);
                if($sentence == "") continue;
                $tags = $tagger->tag($sentence);
                $pos = "";
                foreach($tags as $tag) {
                  $pos .= $tag['tag'] . " ";
                }
                $pos = trim($pos);
                mysql_query("INSERT INTO `proto`(`name`, `level`, `pageID`) VALUES ('" . mysql_real_escape_string($sentence) . "', '1','" . $storyID . "')");
                $pr1ID = mysql_insert_id();
                mysql_query("INSERT INTO `proto`(`name`, `level`, `pageID`) VALUES ('" . mysql_real_escape_string($sentence) . "', '2','" . $storyID . "')");
                $pr2ID = mysql_insert_id();
                mysql_query("INSERT INTO `sentence`(`sentence`, `pr2ID`, `pr1ID`, `pageID`, `POS`) VALUES ('" . mysql_real_escape_string($sentence) . "', '" . $pr2ID . "','" . $pr1ID . "','" . $storyID . "', '" . $pos . "')");
              }
            }
          
            $resultArray = array('storyId' => $id, 'success' => '1', 'message'=>'');
            echo json_encode(array_map('utf8_encode', $resultArray));    
          } else {
            $resultArray  =array('storyId' => 0, 'success' => '0', 'message'=>mysql_error());
            echo json_encode(array_map('utf8_encode', $resultArray)); 
          }
        }
        
      } else {
        $resultArray  =array('storyId' => 0, 'success' => '0', 'message'=> 'A story with that title already exists.');
        echo json_encode(array_map('utf8_encode', $resultArray));
      }
    }
  }

if(isset($_POST['type']) && $_POST['type'] == "changeCat" && isset($_POST['newCat']) && isset($_POST['storyId'])) {
  dbCon();
  $newCat = trim($_POST['newCat']);
  $storyId = trim($_POST['storyId']);
  $result = mysql_fetch_array(mysql_query("SELECT `user` FROM `page` WHERE `pageID` = '" . $storyId . "'"));   
  if($user->logged_in && ($user->userlevel == 8 || $user->userlevel == 9 || $result['user'] == $user->username)) {
    mysql_query("UPDATE `page` SET `categoryID` = '" . mysql_real_escape_string((int) $newCat) . "' WHERE `pageID` = '" . mysql_real_escape_string($storyId) . "'");
  }
}

if(isset($_POST['type']) && $_POST['type'] == "updateEdit" && isset($_POST['sentences']) && isset($_POST['storyId'])) {
  dbCon();
  $sentences = $_POST['sentences'];
  $storyId = $_POST['storyId'];
  $result = mysql_fetch_array(mysql_query("SELECT `user` FROM `page` WHERE `pageID` = '" . $storyId . "'"));   
  if($user->logged_in && ($user->userlevel == 8 || $user->userlevel == 9 || $result['user'] == $user->username)) {
    foreach($sentences as $sentence) {
      $query = "UPDATE `sentence` SET `sentence` = '" . mysql_real_escape_string($sentence['sentence']) . "',
         `representation` = '" . mysql_real_escape_string($sentence['representation']) . "',
         `context_rep` = '" . mysql_real_escape_string($sentence['context_rep']) . "',
         `semantic_rep` = '" . mysql_real_escape_string($sentence['semantic_rep']) . "' WHERE `sentenceID` = '" . $sentence['sentenceID'] . "'";
      mysql_query($query) or die($query);      
    }
    $query = mysql_query("SELECT * FROM `sentence` WHERE `pageID` = '" . $storyId . "' AND (TRIM(`representation`) = '' OR `RepFLAG` <> 'Passed')");
    if(mysql_num_rows($query) == 0) {
      mysql_query("UPDATE `page` SET `is_checked` = '1', `has_reps` = '1' WHERE `pageID` = '" . $storyId . "'");
    }
  }
}

if(isset($_POST['type']) && $_POST['type'] == "searchStories" && isset($_POST['query'])) {
  dbCon();
  $query = trim($_POST['query']);
  $search_results = searchStories($query);
  if($search_results) {
    $html = "<br><table width='100%'>
      <tr>
        <th align='left' width='100'>Ready</th>
        <th align='left' width='100'>Assigned</th>
        <th align='left' width='100'>Checked</th>
        <th align='left' width='100'>Finished</th>
        <th align='left' width='100'>Overwrite</th>
        <th align='left'>Story Title</th>
        <th align='left' width='10%'>Added by</th>
        <th align='left' width='10%'>Reps Added by</th>
        <th align='left' width='15%'>Category</th>        
      </tr>";
    while($row = mysql_fetch_array($search_results)) {
      if($row['user']) $user = $row['user'];
      else $user = "Unknown";
      
      if($row['reps_added_by']) $reps_added_by = $row['reps_added_by'];
      else $reps_added_by = "-";
      
      $queryCurrentPage =  "SELECT COUNT(*) AS count FROM `page` where `pageID` <= '" . $row['pageID'] . "'";
      $result_current_page = mysql_query($queryCurrentPage);    
      if(!($result_current_page == false)){          
        $currentPageRow = mysql_fetch_array($result_current_page);
        $cur_page = $currentPageRow['count'];
      }
      
      $isSetText = '';
      if($row['is_set'] == 1){
        $isSetText = 'READY';
      }
      $isCheckedText = '';
      if($row['is_checked'] == 1){
        $isCheckedText = 'CHECK';
      }
      $isAssignedText = '';
      if($row['is_assigned'] == 1){
        $isAssignedText = 'ASSIGNED';
      }
      $isFinishedText = '';
      if($row['is_finished'] == 1){
        $isFinishedText = 'FINISHED';
      }
      $isOverwriteText = '';
      if($row['can_overwrite'] == 1){
        $isOverwriteText = 'OVERWRITE';
      }
      $html .= "
        <tr class='searchrow' id='searchrow" . $cur_page . "'>
          <td>" . $isSetText . "</td>
          <td>" . $isAssignedText . "</td>
          <td>" . $isCheckedText . "</td>
          <td>" . $isFinishedText . "</td>
          <td>" . $isOverwriteText . "</td>
          <td>" . $row['title'] . "</td>
          <td>" . $user . "</td>
          <td>" . $reps_added_by . "</td>
          <td>" . $row['category'] . "</td>
        </tr>";
    }
    $html .= "</table>";
    echo $html;
  } else {
    echo "<br><strong>Search returned no results.</strong>";
  }
}

// Stats display 
if (isset($_REQUEST['storyId']) && isset($_REQUEST['type']) && isset($_REQUEST['persnt']))
{
  $start = microtime(true); // time count 
  dbCon();
  $storyID=$_REQUEST['storyId'];
  $fntype=$_REQUEST['type'];
  $percent=$_REQUEST['persnt'];
  $guessrep1=array();
  $guessrep2=array();
  $guessrep3=array();

  $story_data = "SELECT * from sentence where pageID='$storyID'";
  $result_story_data = mysql_query($story_data) or die('MySql Error' . mysql_error());

  while ($rows = mysql_fetch_array($result_story_data)) {
    if (!empty($rows['sentence'])) {
      $guessrep1[]= str_replace('%','',similarity($rows['representation'],  $rows['repguess1']));
      $guessrep2[]= str_replace('%','',similarity($rows['representation'],  $rows['repguess2']));
      $guessrep3[]= str_replace('%','',similarity($rows['representation'],  $rows['repguess3']));
	  }
  }

  $average1 = array_sum($guessrep1) / ((count($guessrep1))?count($guessrep1):1);
  $average2 = array_sum($guessrep2) / ((count($guessrep2))?count($guessrep2):1);
  $average3 = array_sum($guessrep3) / ((count($guessrep3))?count($guessrep3):1);

  //if ($percent=='25%'){
  //$str="UPDATE page SET text25='$average1',pos25='$average2',hybrid25='$average3',persnt='$percent' WHERE pageID='$storyID'";
  //}else if ($percent=='50%'){
  //$str="UPDATE page SET text50='$average1',pos50='$average2',hybrid50='$average3',persnt='$percent' WHERE pageID='$storyID'";
  //}else if ($percent=='100%'){
  //$str="UPDATE page SET text100='$average1',pos100='$average2',hybrid100='$average3',persnt='$percent' WHERE pageID='$storyID'";
  //}
  // $chk = mysql_query($str);
  //    if ($chk) {
        //  echo '1';
  //    } else {
  //        echo '0';
  //    }


	$time_taken = microtime(true) - $start; // end time counter
	$sec=$time_taken;///1000000;
	$query2="insert into timelog (function,time) values ('Statsdisplay ','".$sec."')";
	$result2 = mysql_query($query2);

  echo '&nbsp;&nbsp;<span style="color:#000;">'. round ($average3)."%  &nbsp;&nbsp; ". round ($average1)."%  &nbsp;&nbsp; ".round ($average2). "% </span>";
}


// Datatab display 
$data= isset($_REQUEST['type']) ? $_REQUEST['type'] : '';
if (isset($_REQUEST['type']) && $data=='datatab')
{
    dbCon();


$story_data = "SELECT text25 from page where NOT (text25 is null or text25 ='')";
$result_story_data = mysql_query($story_data) or die('MySql Error' . mysql_error());        /* START CREP */ 
$total=$j=0;
while ($rows = mysql_fetch_array($result_story_data)) {
if (!empty($rows['text25']))
$total+=$rows['text25']; $j++;
}

if ($j>0){
$avg= "<tr>
<td>Average</td>
<td>".round(($total/$j),1)."</td> ";
}else {
$avg= "<tr>
<td>Average</td>
<td>"."</td> ";
}

$story_data = "SELECT text50 from page where  NOT (text50 is null or text50 ='')";
$result_story_data = mysql_query($story_data) or die('MySql Error' . mysql_error());        /* START CREP */ 
$total=$j=0;
while ($rows = mysql_fetch_array($result_story_data)) {
if (!empty($rows['text50']))
$total+=$rows['text50']; $j++;
}


if ($j>0){
$avg.= "<td>".round(($total/$j),1)."</td>";
}else {
$avg.= "<td>"."</td>";
}


$story_data = "SELECT text100 from page where  NOT (text100 is null or text100 ='')";
$result_story_data = mysql_query($story_data) or die('MySql Error' . mysql_error());        /* START CREP */ 
$total=$j=0;
while ($rows = mysql_fetch_array($result_story_data)) {
if (!empty($rows['text100']))
$total+=$rows['text100']; $j++;
}


if ($j>0){
$avg.= "<td>".round(($total/$j),1)."</td>";
}else {
$avg.= "<td>"."</td>";
}

$story_data = "SELECT pos25 from page where   NOT (pos25 is null or pos25 ='')";
$result_story_data = mysql_query($story_data) or die('MySql Error' . mysql_error());        /* START CREP */ 
$total=$j=0;
while ($rows = mysql_fetch_array($result_story_data)) {
if (!empty($rows['pos25']))
$total+=$rows['pos25']; $j++;
}


if ($j>0){
$avg.= "<td>".round(($total/$j),1)."</td>";
}else {
$avg.= "<td>"."</td>";
}


$story_data = "SELECT pos50 from page where   NOT (pos50 is null or pos50 ='')";
$result_story_data = mysql_query($story_data) or die('MySql Error' . mysql_error());        /* START CREP */ 
$total=$j=0;
while ($rows = mysql_fetch_array($result_story_data)) {
if (!empty($rows['pos50']))
$total+=$rows['pos50']; $j++;
}


if ($j>0){
$avg.= "<td>".round(($total/$j),1)."</td>";
}else {
$avg.= "<td>"."</td>";
}



$story_data = "SELECT pos100 from page where  NOT (pos100 is null or pos100 ='')";
$result_story_data = mysql_query($story_data) or die('MySql Error' . mysql_error());        /* START CREP */ 
$total=$j=0;
while ($rows = mysql_fetch_array($result_story_data)) {
if (!empty($rows['pos100']))
$total+=$rows['pos100']; $j++;
}


if ($j>0){
$avg.= "<td>".round(($total/$j),1)."</td>";
}else {
$avg.= "<td>"."</td>";
}

$story_data = "SELECT hybrid25 from page where  NOT (hybrid25 is null or hybrid25='')";
$result_story_data = mysql_query($story_data) or die('MySql Error' . mysql_error());        /* START CREP */ 
$total=$j=0;
while ($rows = mysql_fetch_array($result_story_data)) {
if (!empty($rows['hybrid25']))
$total+=$rows['hybrid25']; $j++;
}


if ($j>0){
$avg.= "<td>".round(($total/$j),1)."</td>";
}else {
$avg.= "<td>"."</td>";
}


$story_data = "SELECT hybrid50 from page where NOT (hybrid50 is null or hybrid50='')";
$result_story_data = mysql_query($story_data) or die('MySql Error' . mysql_error());        /* START CREP */ 
$total=$j=0;
while ($rows = mysql_fetch_array($result_story_data)) {
if (!empty($rows['hybrid50']))
$total+=$rows['hybrid50']; $j++;
}


if ($j>0){
$avg.= "<td>".round(($total/$j),1)."</td>";
}else {
$avg.= "<td>"."</td>";
}


$story_data = "SELECT hybrid100 from page where NOT (hybrid100 is null or hybrid100='')";
$result_story_data = mysql_query($story_data) or die('MySql Error' . mysql_error());        /* START CREP */ 
$total=$j=0;
while ($rows = mysql_fetch_array($result_story_data)) {
if (!empty($rows['hybrid100']))
$total+=$rows['hybrid100']; $j++;
}


if ($j>0){
$avg.= "<td>".round(($total/$j),1)."</td></tr>";
}else {
$avg.= "<td>"."</td></tr>";
}









$story_data = "SELECT * from page ";
$result_story_data = mysql_query($story_data) or die('MySql Error' . mysql_error());        /* START CREP */ 

?>
<style>
table.reference, table.tecspec {
    border-collapse: collapse;
    width: 100%;
}
table.reference tr:nth-child(2n+1) {
    background-color: #F6F4F0;
}
table.reference tr:nth-child(2n) {
    background-color: #FFFFFF;
}
table.reference tr.fixzebra {
    background-color: #F6F4F0;
}
table.reference th {
    -moz-border-bottom-colors: none;
    -moz-border-left-colors: none;
    -moz-border-right-colors: none;
    -moz-border-top-colors: none;
    background-color: #555555;
    border-bottom-color: #555555;
    border-bottom-style: solid;
    border-bottom-width: 1px;
    border-image-outset: 0 0 0 0;
    border-image-repeat: stretch stretch;
    border-image-slice: 100% 100% 100% 100%;
    border-image-source: none;
    border-image-width: 1 1 1 1;
    border-left-color-ltr-source: physical;
    border-left-color-rtl-source: physical;
    border-left-color-value: #555555;
    border-left-style-ltr-source: physical;
    border-left-style-rtl-source: physical;
    border-left-style-value: solid;
    border-left-width-ltr-source: physical;
    border-left-width-rtl-source: physical;
    border-left-width-value: 1px;
    border-right-color-ltr-source: physical;
    border-right-color-rtl-source: physical;
    border-right-color-value: #555555;
    border-right-style-ltr-source: physical;
    border-right-style-rtl-source: physical;
    border-right-style-value: solid;
    border-right-width-ltr-source: physical;
    border-right-width-rtl-source: physical;
    border-right-width-value: 1px;
    border-top-color: #555555;
    border-top-style: solid;
    border-top-width: 1px;
    color: #FFFFFF;
    padding-bottom: 3px;
    padding-left: 3px;
    padding-right: 3px;
    padding-top: 3px;
    text-align: left;
    vertical-align: top;
}
table.reference th a:link, table.reference th a:visited {
    color: #FFFFFF;
}
table.reference th a:hover, table.reference th a:active {
    color: #EE872A;
}
table.reference td {
    -moz-border-bottom-colors: none;
    -moz-border-left-colors: none;
    -moz-border-right-colors: none;
    -moz-border-top-colors: none;
    border-bottom-color: #D4D4D4;
    border-bottom-style: solid;
    border-bottom-width: 1px;
    border-image-outset: 0 0 0 0;
    border-image-repeat: stretch stretch;
    border-image-slice: 100% 100% 100% 100%;
    border-image-source: none;
    border-image-width: 1 1 1 1;
    border-left-color-ltr-source: physical;
    border-left-color-rtl-source: physical;
    border-left-color-value: #D4D4D4;
    border-left-style-ltr-source: physical;
    border-left-style-rtl-source: physical;
    border-left-style-value: solid;
    border-left-width-ltr-source: physical;
    border-left-width-rtl-source: physical;
    border-left-width-value: 1px;
    border-right-color-ltr-source: physical;
    border-right-color-rtl-source: physical;
    border-right-color-value: #D4D4D4;
    border-right-style-ltr-source: physical;
    border-right-style-rtl-source: physical;
    border-right-style-value: solid;
    border-right-width-ltr-source: physical;
    border-right-width-rtl-source: physical;
    border-right-width-value: 1px;
    border-top-color: #D4D4D4;
    border-top-style: solid;
    border-top-width: 1px;
    padding-bottom: 7px;
    padding-left: 5px;
    padding-right: 5px;
    padding-top: 7px;
    vertical-align: top;
}
table.reference td.example_code {
    vertical-align: bottom;
}
</style>
<table class="reference" style="width:99%">
<tbody>
<tr>
<th>Story</th>
<th>Text 25%</th>
<th>Text 50%</th>
<th>Text 100%</th>
<th>Pos 25%</th>
<th>Pos 50%</th>
<th>Pos 100%</th>
<th>Hybrid 25%</th>
<th>Hybrid 50%</th>
<th>Hybrid 100%</th>
</tr>
<?php
echo $avg;
while ($rows = mysql_fetch_array($result_story_data)) {

echo "<tr>
<td>".$rows['pageID']."</td>
<td>".round($rows['text25'],0)."</td>
<td>".round($rows['text50'],0)."</td>
<td>".round($rows['text100'],0)."</td>
<td>".round($rows['pos25'],0)."</td>
<td>".round($rows['pos50'],0)."</td>
<td>".round($rows['pos100'],0)."</td>
<td>".round($rows['hybrid25'],0)."</td>
<td>".round($rows['hybrid50'],0)."</td>
<td>".round($rows['hybrid100'],0)."</td>
</tr>";
}

echo "</tbody></table>";

}



if (isset($_REQUEST['santanceId']) && isset($_REQUEST['snumber'])&& isset($_REQUEST['sid'])) 
{
    dbCon();
    $stId = $_REQUEST['santanceId']; // sentance 
    $sId = $_REQUEST['sid'];   // story id 
    $aId = $_REQUEST['snumber'];   // story id 	

    $result = mysql_fetch_array(mysql_query("SELECT `user` FROM `page` WHERE `pageID` = '" . $sId . "'"));
    if($user->logged_in && ($user->userlevel == 8 || $user->userlevel == 9 || $result['user'] == $user->username)) {
      if (trim($aId) == '1') {
  
          $query_pag_data = "SELECT * from sentence where sentenceID= '$stId'";
          $result_pag_data = mysql_query($query_pag_data);
          $row = mysql_fetch_array($result_pag_data);
          if ($row['tick1'] == 'ON')
              $tick = '';
          else
              $tick = 'ON';
  
  
          $qtmp = 'UPDATE `sentence` SET `tick1` = "' . $tick . '",representation=repguess1  WHERE `sentence`.`sentenceID` =' . $stId . '';
      }else  if (trim($aId) == '2') {
          $query_pag_data = "SELECT * from sentence where sentenceID= '$stId'";
          $result_pag_data = mysql_query($query_pag_data);
          $row = mysql_fetch_array($result_pag_data);
          if ($row['tick2'] == 'ON')
              $tick = '';
          else
              $tick = 'ON';
  
          $qtmp = 'UPDATE `sentence` SET `tick2` = "' . $tick . '" ,representation=repguess2  WHERE `sentence`.`sentenceID` =' . $stId . '';
      }else  if (trim($aId) == '3') {
          $query_pag_data = "SELECT * from sentence where sentenceID= '$stId'";
          $result_pag_data = mysql_query($query_pag_data);
          $row = mysql_fetch_array($result_pag_data);
          if ($row['tick3'] == 'ON')
              $tick = '';
          else
              $tick = 'ON';
  
          $qtmp = 'UPDATE `sentence` SET `tick3` = "' . $tick . '" ,representation=repguess3  WHERE `sentence`.`sentenceID` =' . $stId . '';
      }	
  
      $chk = mysql_query($qtmp);
      if ($chk) {
          echo '1';
      } else {
          echo '0';
      }
    }
    mysql_close();
    die;
}
if (isset($_POST['santanceId']) && isset($_POST['sentnc'])) 
{
    dbCon();
    $stId = $_POST['santanceId'];
    
    $result = mysql_fetch_array(mysql_query("SELECT st.`user` FROM `sentence` se INNER JOIN `page` st ON se.`pageID` = st.`pageID` WHERE se.`sentenceId` = '" . $stId . "'"));
    if($user->logged_in && ($user->userlevel == 8 || $user->userlevel == 9 || $result['user'] == $user->username)) {
      $sentnc = '\'' . $_POST['sentnc'] . '\'';
      $newBody = "";
      $storyId = 0;
      $qtmp = 'UPDATE `sentence` SET `sentence` = ' . $sentnc . ' WHERE `sentence`.`sentenceID` =' . $stId . '';
      $chk = mysql_query($qtmp);
      if ($chk) {
          echo '1';
      } else {
          echo '0';
      }
    } else {
      echo '0';
    }
    mysql_close();
    die;
}
if (isset($_POST['sentenceId']) && isset($_POST['srep']) && isset($_POST['type']) && $_POST['type'] == 'editSRep' ) 
{
    dbCon();
    $stId = $_POST['sentenceId'];
    $srep = $_POST['srep'];
    
    $result = mysql_fetch_array(mysql_query("SELECT st.`user` FROM `sentence` se INNER JOIN `page` st ON se.`pageID` = st.`pageID` WHERE se.`sentenceId` = '" . $stId . "'"));
    if($user->logged_in && ($user->userlevel == 8 || $user->userlevel == 9 || $result['user'] == $user->username)) {
      $qtmp = "UPDATE `sentence` SET `semantic_rep` = '" . $srep . "' WHERE `sentence`.`sentenceID` = '" . $stId . "'";
      $chk = mysql_query($qtmp) or die(mysql_error());
    }
    mysql_close();
    die;
}
if (isset($_POST['santanceId']) && isset($_POST['rep'])) 
{
    dbCon();
    $stId = $_POST['santanceId'];
    $result = mysql_fetch_array(mysql_query("SELECT st.`user` FROM `sentence` se INNER JOIN `page` st ON se.`pageID` = st.`pageID` WHERE se.`sentenceId` = '" . $stId . "'"));
    if($user->logged_in && ($user->userlevel == 8 || $user->userlevel == 9 || $result['user'] == $user->username)) {
      $rep = '\'' . $_POST['rep'] . '\'';
      $qtmp1 = 'UPDATE `sentence` SET `representation` = ' . $rep . ' WHERE `sentence`.`sentenceID` =' . $stId . '';
      $chk1 = mysql_query($qtmp1);
      if ($chk1) {
          echo '1';
      } else {
          echo '0';
      }
      $query = mysql_query("SELECT * FROM `sentence` WHERE `pageID` = (SELECT `pageID` FROM `sentence` WHERE `sentenceID` = '" . $stId . "') AND (TRIM(`representation`) = '' OR `RepFLAG` <> 'Passed')");
      if(mysql_num_rows($query) == 0) {
        mysql_query("UPDATE `page` SET `is_checked` = '1', `has_reps` = '1' WHERE `pageID` = (SELECT `pageID` FROM `sentence` WHERE `sentenceID` = '" . $stId . "')");
      }
    } else {
      echo '0';
    }
    mysql_close();
    die;
}
if (isset($_POST['santanceId']) && isset($_POST['crep'])) 
{
    dbCon();
    $stId = $_POST['santanceId'];
    $crep = trim($_POST['crep']);
    $result = mysql_fetch_array(mysql_query("SELECT st.`user` FROM `sentence` se INNER JOIN `page` st ON se.`pageID` = st.`pageID` WHERE se.`sentenceId`= '" . $stId . "'"));
    if($user->logged_in && ($user->userlevel == 8 || $user->userlevel == 9 || $result['user'] == $user->username)) {
      $qtmp1 = "UPDATE `sentence` SET `context_rep` = '" . mysql_real_escape_string($crep) . "' WHERE `sentence`.`sentenceID` = '" . $stId . "'";
      $chk1 = mysql_query($qtmp1) or die(mysql_error());
      if ($chk1) {
          echo '1';
      } else {
          echo '0';
      }
    } else {
      echo '0';
    }
    mysql_close();
    die;
}

if (isset($_POST['santanceId']) && isset($_POST['semanticrep'])) 
{
    dbCon();
    $stId = $_POST['santanceId'];
    $result = mysql_fetch_array(mysql_query("SELECT st.`user` FROM `sentence` se INNER JOIN `page` st ON se.`pageID` = st.`pageID` WHERE se.`sentenceId` = '" . $stId . "'"));
    if($user->logged_in && ($user->userlevel == 8 || $user->userlevel == 9 || $result['user'] == $user->username)) {
      $semanticrep = '\'' . $_POST['semanticrep'] . '\'';
      $qtmp1 = 'UPDATE `sentence` SET `semantic_rep` = ' . $semanticrep . ' WHERE `sentence`.`sentenceID` =' . $stId . '';
      $chk1 = mysql_query($qtmp1);
      if ($chk1) {
          echo '1';
      } else {
          echo '0';
      }
    } else {
      echo '0';
    }
    mysql_close();
    die;
}

if (isset($_POST['santanceId']) && isset($_POST['quest'])) 
{
    dbCon();
    $stId = $_POST['santanceId'];
    $result = mysql_fetch_array(mysql_query("SELECT st.`user` FROM `qa` qs INNER JOIN `page` st ON qa.`pageID` = st.`pageID` WHERE `questionID` = '" . $stId . "'"));
    if($user->logged_in && ($user->userlevel == 8 || $user->userlevel == 9 || $result['user'] == $user->username)) {
      $quest = '\'' . $_POST['quest'] . '\'';
      $qtmp1 = 'UPDATE `qa` SET `question` = ' . $quest . ' WHERE `qa`.`questionID` =' . $stId . '';
      $chk1 = mysql_query($qtmp1);
      if ($chk1) {
          echo '1';
      } else {
          echo '0';
      }
    } else {
     echo '0'; 
    }
    mysql_close();
    exit;
}
if (isset($_POST['santanceId']) && isset($_POST['ans'])) 
{
    dbCon();
    $stId = $_POST['santanceId'];
    $result = mysql_fetch_array(mysql_query("SELECT st.`user` FROM `qa` qs INNER JOIN `page` st ON qa.`pageID` = st.`pageID` WHERE `questionID` = '" . $stId . "'"));
    if($user->logged_in && ($user->userlevel == 8 || $user->userlevel == 9 || $result['user'] == $user->username)) {
      $ans = '\'' . $_POST['ans'] . '\'';
      $qtmp1 = 'UPDATE `qa` SET `answer` = ' . $ans . ' WHERE `qa`.`questionID` =' . $stId . '';
      $chk1 = mysql_query($qtmp1);
      if ($chk1) {
          echo '1';
      } else {
          echo '0';
      }
    } else {
      echo '0';
    }
    mysql_close();
    die;
}
if (isset($_POST['santanceId']) && isset($_POST['qrule'])) 
{
    dbCon();
    $stId = $_POST['santanceId'];
    $result = mysql_fetch_array(mysql_query("SELECT st.`user` FROM `qa` qs INNER JOIN `page` st ON qa.`pageID` = st.`pageID` WHERE `questionID` = '" . $stId . "'"));
    if($user->logged_in && ($user->userlevel == 8 || $user->userlevel == 9 || $result['user'] == $user->username)) {
      $qrule = '\'' . $_POST['qrule'] . '\'';
      $qtmp1 = 'UPDATE `qa` SET `qarule` = ' . $qrule . ' WHERE `qa`.`questionID` =' . $stId . '';
      $chk1 = mysql_query($qtmp1);
      if ($chk1) {
          echo '1';
      } else {
          echo '0';
      }
    } else {
      echo '0';
    }
    mysql_close();
    die;
}
if (isset($_POST['type']) && isset($_POST['sid']) && ($_POST['type']=='stats')) 
{
    $storyID=$_POST['sid'];
    dbCon();
    $qtmp1 = 'Select * from page WHERE pageID='.$storyID;
    $chk1 = mysql_query($qtmp1);
    if($rows= mysql_fetch_array($chk1)) {
       echo json_encode(array('stats'=>$rows));
    }

    mysql_close();
    die;
}
if (isset($_POST['storyId']) && isset($_POST['type']) && $_POST['type'] == "updateREP") 
{
    dbCon();
    $storyId = $_POST['storyId'];    
    $result = mysql_fetch_array(mysql_query("SELECT st.`user` FROM `sentence` se INNER JOIN `page` st ON se.`pageID` = st.`pageID` WHERE `pageID` = '" . $storyId . "'"));
    if($user->logged_in && ($user->userlevel == 8 || $user->userlevel == 9 || $result['user'] == $user->username)) {
      $qtmp = 'UPDATE `sentence` SET `representation` = `repguess1` WHERE `sentence`.`pageID` =' . $storyId . '';
      $chk = mysql_query($qtmp);
      if ($chk) {
          echo '1';
      } else {
          echo '0';
      }
      $query = mysql_query("SELECT * FROM `sentence` WHERE `pageID` = '" . $storyId . "' AND (TRIM(`representation`) = '' OR `RepFLAG` <> 'Passed')");
      if(mysql_num_rows($query) == 0) {
        mysql_query("UPDATE `page` SET `is_checked` = '1', `has_reps` = '1' WHERE `pageID` = '" . $storyId . "'");
      }
    } else {
      echo '0';
    }
    mysql_close();
    die;
}
if (isset($_POST['storyId']) && isset($_POST['type']) && $_POST['type'] == "updateCREP") 
{
    dbCon();
    $storyId = $_POST['storyId'];    
    $result = mysql_fetch_array(mysql_query("SELECT st.`user` FROM `sentence` se INNER JOIN `page` st ON se.`pageID` = st.`pageID` WHERE `pageID` = '" . $storyId . "'"));
    if($user->logged_in && ($user->userlevel == 8 || $user->userlevel == 9 || $result['user'] == $user->username)) {
      $qtmp = 'UPDATE `sentence` SET `context_rep` = `representation` WHERE `sentence`.`pageID` =' . $storyId . '';
      $chk = mysql_query($qtmp);
      if ($chk) {
          echo '1';
      } else {
          echo '0';
      }
    } else {
      echo '0';
    }
    mysql_close();
    die;
}
if (isset($_POST['sentenceId']) && isset($_POST['type']) && $_POST['type'] == "copyRepToCrep") 
{
    dbCon();
    $sentenceId = $_POST['sentenceId'];    
    $result = mysql_fetch_array(mysql_query("SELECT st.`user` FROM `sentence` se INNER JOIN `page` st ON se.`pageID` = st.`pageID` WHERE `sentenceID` = '" . $sentenceId . "'"));
    if($user->logged_in && ($user->userlevel == 8 || $user->userlevel == 9 || $result['user'] == $user->username)) {
      mysql_query('UPDATE `sentence` SET `context_rep` = `representation` WHERE `sentenceID` =' . $sentenceId . '');
    }
    mysql_close();
    die;
}    
if (isset($_POST['storyId']) && isset($_POST['type']) && isset($_POST['question']) && isset($_POST['answer']) && isset($_POST['rule']) && $_POST['type'] == "addQA") 
{
    dbCon();
    $storyId = $_POST['storyId'];
    $question = $_POST['question'];
    $answer = $_POST['answer'];
    $rule = $_POST['rule'];
    $result = mysql_fetch_array(mysql_query("SELECT `user` FROM `page` WHERE `pageID` = '" . $storyId . "'"));
    if($user->logged_in && ($user->userlevel == 8 || $user->userlevel == 9 || $result['user'] == $user->username)) {
      $qtmp = "INSERT INTO `qa` (`pageID`, `question`, `answer`, `qarule`) VALUES('" . $storyId . "', '" . $question . "', '" . $answer . "', '" . $rule . "') ";
      $chk = mysql_query($qtmp);
      if ($chk) {
          echo '1';
      } else {
          echo '0';
      }
    } else {
      echo '0';
    }
    mysql_close();
    die;
}
function dbCon() {
  require_once "config_storydb.php"; 
  mysql_connect($configuration['host'], $configuration['user'], $configuration['pass']);
  mysql_set_charset("utf8");
  mysql_select_db($configuration['db']);
}
/*
 * similarity() will return similarity in %
 *  e.g. 50%
 */
//print_r(similarity($_REQUEST['rep'],$_REQUEST['guessrep']));
function similarity($rep, $guessrep) {
//usleep(15);
    if (stripos($rep, ';')) {
        $repExplodArray = explode(';', $rep);
    } elseif (stripos($rep, ',')) {
        $repExplodArray = explode(',', $rep);
    } else {
        $repExplodArray = array($rep);
    }
    if (stripos($guessrep, ';')) {
        $gpExplodArray = explode(';', $guessrep);
    } elseif (stripos($guessrep, ',')) {
        $gpExplodArray = explode(',', $guessrep);
    } else {
        $gpExplodArray = array($guessrep);
    }
    $rep_part_count = count($repExplodArray);
    $matchcount = 0;
    foreach ($repExplodArray as $key => $value) {
        $value = trim($value);
        for ($j = 0; $j < count($gpExplodArray); $j++) {
            $gpExplodArray[$j] = trim($gpExplodArray[$j]);
            if ($value === $gpExplodArray[$j]) {
                $matchcount = $matchcount + 1;
            }
        }
    }
    $similarityRatio = $matchcount / $rep_part_count;
    $percentage=($similarityRatio*100).'%';
	
//	//Something to write to txt log
//	$log  = "date: ".date("F j, Y, g:i a").PHP_EOL.
//        "Guessrep: ". $guessrep.PHP_EOL.
//        "rep: ". $rep.PHP_EOL.		
//        "-------------------------".PHP_EOL;
////Save string to log, use FILE_APPEND to append.
//	file_put_contents('./log_'.date("j.n.Y").'.txt', $log, FILE_APPEND);

    return $percentage;
}



/** load sentence and parent sentence for story */
if(isset($_POST['sentenceId']) &&  isset($_POST['action']) && ($_POST['action'] == 'loadSentences') ){
   
  dbCon(); 
  $storyID = $_POST['sentenceId'];  
  
  
  //check if user can edit sentence
  $allowEditClass = '';
  //check if user can edit parent sentence
  $allowCrepClass = '';   
 
  //check if user is owner of story
  $storyResult = mysql_fetch_array(mysql_query("SELECT `user`, `title`, `body`, `is_split` FROM `page` WHERE `pageID` = '" . $storyID . "'"));
  if($user->logged_in && ($user->userlevel == 8 || $user->userlevel == 9 || $storyResult['user'] == $user->username)) {   
    $allowEditClass = ' class="td-crep" ';
    $allowCrepClass = ' class="td-crep" ';  
  } 
  $title = $storyResult['title'];
  $userName = isset($storyResult['user']) ? $storyResult['user'] : 'unknown';
  $body = $storyResult['body'];
  $isSplit = $storyResult['is_split'];
  if($isSplit == 1){
    // can edit parent sentence only before first split    
    $allowEditClass = '';
  }
  
  $msg = "<li data-value='25'><span  class='stitle'>" . $title . " by " . $userName . "</span>" .$body. "</li>";
  $msg = "<div class='data'><ul>" . $msg . "</ul></div>"; // Content for Data 
  
  $htmltext = '<table id="rep-data">
  <tr>
    <th align="left" width="35%">Proto-Sentence</th>
    <th width="40"></th>
    <th align="left" width="35%">Sentence</th>      
  </tr>';
  
  $crep_data = "SELECT * from sentence where pageID='$storyID' ORDER BY `sentenceID` ASC";
  $sresult_crep_data = mysql_query($crep_data) or die('MySql Error' . mysql_error());
  $countr = 0;
  
  while ($creprows = mysql_fetch_array($sresult_crep_data)) {          
    $storysentence = $creprows['sentence'];
    $parentSentence = $creprows['parentsentence'];
       
    $htmltext .='
      <tr rowspan ="2" id="tr' . $creprows['sentenceID'] . '">
        <td class="proto_sentnc"><span '.$allowEditClass.' id="edit' . $creprows['sentenceID'] . '"   >'. $parentSentence . '</span></td>          
        <td>&nbsp;</td>    
        <td class="short_sentnc">
          <span '.$allowCrepClass.' id="edit' . $creprows['sentenceID'] . '" >' .$storysentence.'</span>
        </td>              
      </tr>';
    $countr = $countr + 1;
  }
  $htmltext .= '</table>';
  
  $arrtmp=array('msg' => $msg, 'table' => $htmltext, 'total' => $countr, 'storyId' => $storyID);
  echo json_encode(array_map('utf8_encode', $arrtmp));
        
}

/** split sentences  */
if(isset($_POST['storyId']) &&  isset($_POST['action']) && ($_POST['action'] == 'splitSentence') ){
 //storyId
 //sentences
 dbCon(); 
 
 $postSentences = isset($_POST['sentences']) ? $_POST['sentences'] : array();
 $storyID = $_POST['storyId'];
 if(count($postSentences) > 0 ){
   //check if user is owner of story
   $result = mysql_fetch_array(mysql_query("SELECT `user`, `is_split` FROM `page` WHERE `pageID` = '" . $storyID . "'"));
   $isSplit = $result['is_split'];
   if($user->logged_in && ($user->userlevel == 8 || $user->userlevel == 9 || $result['user'] == $user->username)) {

      $oldSentences = array();
      $query = mysql_query("SELECT `sentenceID`, `context_rep`, `representation`, `semantic_rep` FROM `sentence` WHERE `pageID` = '" . $storyID . "'");
      while($row = mysql_fetch_array($query)) {
        $oldSentences[$row['sentenceID']] = array(
          "REP" => $row['representation'],
          "CREP" => $row['context_rep'],
          "SREP" => $row['semantic_rep']
        );
      }
      
      $oldProtos = array();
      $query = mysql_query("SELECT `prID`, `name` FROM `proto` WHERE `pageID` = '" . $storyID . "'");
      while($row = mysql_fetch_array($query)) {
        $oldProtos[$row['prID']] = array(
          "name" => $row['name']
        );
      }
      
      //delete old sentences     
      mysql_query("DELETE FROM `sentence` WHERE `pageID` = '" . $storyID . "'");
      mysql_query("DELETE FROM `proto` WHERE `pageID` = '" . $storyID . "'");
      
      //generate new sentences 
      $newProtoSentences = array();
      //$newBody = '';        
      for($i=0; $i<count($postSentences); $i++) {
        if($postSentences[$i]['prLevel'] == 1) {
          array_push($newProtoSentences, array(
              "name" => $postSentences[$i]['prValue'],
              "level" => $postSentences[$i]['prLevel'],
              "sentences" => array()
            )
          );
        } else {
          $Rep = "";
          $cRep = "";
          $sRep = "";
          if(isset($postSentences[$i]['childSentences'][0])) {
            $Rep = isset($oldSentences[$postSentences[$i]['childSentences'][0]['sID']]['REP'])?$oldSentences[$postSentences[$i]['childSentences'][0]['sID']]['REP']:"";
            $cRep = isset($oldSentences[$postSentences[$i]['childSentences'][0]['sID']]['CREP'])?$oldSentences[$postSentences[$i]['childSentences'][0]['sID']]['CREP']:"";
            $sRep =  isset($oldSentences[$postSentences[$i]['childSentences'][0]['sID']]['SREP'])?$oldSentences[$postSentences[$i]['childSentences'][0]['sID']]['SREP']:"";
          }
          
          $splitProtoSentences = splitIntoSentences($postSentences[$i]['prValue'], false);
          
          if(count($splitProtoSentences) == 1) {
            $newChildSentences = array();
            for($j=0; isset($postSentences[$i]['childSentences']) && $j<count($postSentences[$i]['childSentences']); $j++) {
              $splitSentences = splitIntoSentences($postSentences[$i]['childSentences'][$j]['sValue'], false);
              foreach($splitSentences as $newS) {
                array_push($newChildSentences, array(
                    "sentence" => $newS,
                  )
                );
              }
            }
            if(count($newChildSentences) == 0 && (!isset($postSentences[$i+1]) || (isset($postSentences[$i+1]) && $postSentences[$i+1]['prLevel'] <= $postSentences[$i]['prLevel']))) {
              array_push($newChildSentences, array(
                  "sentence" => $postSentences[$i]['prValue'],
                )
              );
            }
            array_push($newProtoSentences, array(
                "name" => mysql_real_escape_string($postSentences[$i]['prValue']),
                "level" => $postSentences[$i]['prLevel'],
                "sentences" => $newChildSentences,
                "rep" => $Rep,
                "crep" => $cRep,
                "srep" => $sRep
              )
            );
          } else {
            array_push($newProtoSentences, array(
                  "name" => mysql_real_escape_string($oldProtos[$postSentences[$i]['prID']]['name']),
                  "level" => $postSentences[$i]['prLevel'],
                  "sentences" => array(
                  ),
                  "rep" => $Rep,
                  "crep" => $cRep,
                  "srep" => $sRep
                )
            );
            foreach($splitProtoSentences as $newPr) {
              $sentenceArray = array();
              if(!isset($postSentences[$i+1]) || (isset($postSentences[$i+1]) && $postSentences[$i+1]['prLevel'] <= $postSentences[$i]['prLevel'])) {
                $sentenceArray[] = array(
                  "sentence" => $newPr
                );
              }
              array_push($newProtoSentences, array(
                  "name" => mysql_real_escape_string($newPr),
                  "level" => $postSentences[$i]['prLevel'] + 1,
                  "sentences" => $sentenceArray,
                  "rep" => $Rep,
                  "crep" => $cRep,
                  "srep" => $sRep
                )
              );
            }
          }
        }
      }
      
      //insert new sentences
      require_once 'PosTagger.php';       
      $tagger = new PosTagger('lexicon.txt');
      $pr1ID = 0;
      $pr2ID = 0;
      for($i = 0; $i < count($newProtoSentences); $i++) {
        $newProtoSentence = mysql_real_escape_string(html_entity_decode(trim($newProtoSentences[$i]['name']), ENT_QUOTES));
        if($newProtoSentence == "")
          continue;                       
        mysql_query("INSERT INTO `proto`(`name`, `level`, `pageID`) VALUES ('" . $newProtoSentence . "', '" . $newProtoSentences[$i]['level'] . "', '" . $storyID . "')") or die(mysql_error());
        if($newProtoSentences[$i]['level'] == 1){
          $pr1ID = mysql_insert_id();
        }else{
          $pr2ID = mysql_insert_id();  
        }        
        for($j = 0; $j < count($newProtoSentences[$i]['sentences']); $j++) {
          $newSentence = html_entity_decode((trim($newProtoSentences[$i]['sentences'][$j]['sentence'])), ENT_QUOTES);
          if($newSentence == "")
            continue;
          $tags = $tagger->tag($newSentence);
          $pos = "";
          foreach($tags as $tag) {
            $pos .= $tag['tag'] . " ";
          }
          $pos = trim($pos);
          //$newSentence = mysql_real_escape_string($newSentence);
          $context_rep = $newProtoSentences[$i]['crep'];
          $semantic_rep = $newProtoSentences[$i]['srep'];
          $representation = $newProtoSentences[$i]['rep'];
          mysql_query("INSERT INTO `sentence`(`sentence`, `pr1ID`, `pr2ID`, `pageID`, `POS`, `context_rep`, `semantic_rep`, `representation`) VALUES ('" . $newSentence . "', '" . $pr1ID . "', '" . $pr2ID . "', '" . $storyID . "', '" . $pos . "', '" . $context_rep . "', '" . $semantic_rep .  "', '" . $representation . "')");
        }
      }
      
      // set story as split      
      if($isSplit == 0){
        $updateQuery = "UPDATE `page` set `is_split` = 1 "; 
        //$updateQuery .= ', `body` = "'.$newBody.'"';
        $updateQuery .= 'WHERE `pageID` = "' . $storyID . '" ' ;
        if(!(mysql_query($updateQuery) === true)){          
          echo mysql_error();
          echo $updateQuery;
        }
      }      
   }
 }  
}

// mark story as closed by story owner
if(isset($_POST['storyId']) &&  isset($_POST['action']) && ($_POST['action'] == 'finishStory') ){
  $storyID = $_POST['storyId'];  
  dbCon();
  $result = mysql_fetch_array(mysql_query("SELECT `user` FROM `page` WHERE `pageID` = '" . $storyID . "'"));   
  if($user->logged_in && ($user->userlevel == 8 || $user->userlevel == 9 || $result['user'] == $user->username)) {        
    $updateQuery = "UPDATE `page` set `is_finished` = 1 "; 
    $updateQuery .= 'WHERE `pageID` = "' . $storyID . '" ' ;
    if(mysql_query($updateQuery) === true){          
      echo 1;
    }else{
      echo 0; 
    }
  }else{
    echo 0;
  }
}


if(isset($_POST['storyId']) &&  isset($_POST['action']) && ($_POST['action'] == 'setStory') ){
  $storyID = $_POST['storyId'];  
  dbCon();
  $result = mysql_fetch_array(mysql_query("SELECT `user` FROM `page` WHERE `pageID` = '" . $storyID . "'"));   
  if($user->logged_in && ($user->userlevel == 8 || $user->userlevel == 9 || $result['user'] == $user->username)) {        
    $updateQuery = "UPDATE `page` set `is_set` = 1 "; 
    $updateQuery .= 'WHERE `pageID` = "' . $storyID . '" ' ;
    if(mysql_query($updateQuery) === true){          
      echo 1;
    }else{
      echo 0; 
    }
  }else{
    echo 0;
  }
}

if(isset($_POST['storyId']) &&  isset($_POST['action']) && ($_POST['action'] == 'setStoryAssigned') ){
  $storyID = $_POST['storyId'];  
  dbCon();
  $result = mysql_fetch_array(mysql_query("SELECT `user` FROM `page` WHERE `pageID` = '" . $storyID . "'"));   
  if($user->logged_in && ($user->userlevel == 8 || $user->userlevel == 9 || $result['user'] == $user->username)) {        
    $updateQuery = "UPDATE `page` set `is_assigned` = 1 "; 
    $updateQuery .= 'WHERE `pageID` = "' . $storyID . '" ' ;
    if(mysql_query($updateQuery) === true){          
      echo 1;
    }else{
      echo 0; 
    }
  }else{
    echo 0;
  }
}

if(isset($_POST['storyId']) &&  isset($_POST['action']) && ($_POST['action'] == 'setStoryUnAssigned') ){
  $storyID = $_POST['storyId'];  
  dbCon();
  $result = mysql_fetch_array(mysql_query("SELECT `user` FROM `page` WHERE `pageID` = '" . $storyID . "'"));   
  if($user->logged_in && ($user->userlevel == 8 || $user->userlevel == 9 || $result['user'] == $user->username)) {        
    $updateQuery = "UPDATE `page` set `is_assigned` = 0 "; 
    $updateQuery .= 'WHERE `pageID` = "' . $storyID . '" ' ;
    if(mysql_query($updateQuery) === true){          
      echo 1;
    }else{
      echo 0; 
    }
  }else{
    echo 0;
  }
}

if(isset($_POST['storyId']) &&  isset($_POST['action']) && ($_POST['action'] == 'setStoryChecked') ){
  $storyID = $_POST['storyId'];  
  dbCon();
  $result = mysql_fetch_array(mysql_query("SELECT `user` FROM `page` WHERE `pageID` = '" . $storyID . "'"));   
  if($user->logged_in && ($user->userlevel == 8 || $user->userlevel == 9 || $result['user'] == $user->username)) {        
    $updateQuery = "UPDATE `page` set `is_checked` = 1 "; 
    $updateQuery .= 'WHERE `pageID` = "' . $storyID . '" ' ;
    if(mysql_query($updateQuery) === true){          
      echo 1;
    }else{
      echo 0; 
    }
  }else{
    echo 0;
  }
}

if(isset($_POST['storyId']) &&  isset($_POST['action']) && ($_POST['action'] == 'setStoryUnChecked') ){
  $storyID = $_POST['storyId'];  
  dbCon();
  $result = mysql_fetch_array(mysql_query("SELECT `user` FROM `page` WHERE `pageID` = '" . $storyID . "'"));   
  if($user->logged_in && ($user->userlevel == 8 || $user->userlevel == 9 || $result['user'] == $user->username)) {        
    $updateQuery = "UPDATE `page` set `is_checked` = 0 "; 
    $updateQuery .= 'WHERE `pageID` = "' . $storyID . '" ' ;
    if(mysql_query($updateQuery) === true){          
      echo 1;
    }else{
      echo 0; 
    }
  }else{
    echo 0;
  }
}


if(isset($_POST['storyId']) &&  isset($_POST['action']) && ($_POST['action'] == 'setStoryOverwrite') ){
  $storyID = $_POST['storyId'];  
  dbCon();
  $result = mysql_fetch_array(mysql_query("SELECT `user` FROM `page` WHERE `pageID` = '" . $storyID . "'"));   
  if($user->logged_in && ($user->userlevel == 8 || $user->userlevel == 9 || $result['user'] == $user->username)) {        
    $updateQuery = "UPDATE `page` set `can_overwrite` = 1 "; 
    $updateQuery .= 'WHERE `pageID` = "' . $storyID . '" ' ;
    if(mysql_query($updateQuery) === true){          
      echo 1;
    }else{
      echo 0; 
    }
  }else{
    echo 0;
  }
}


if(isset($_POST['storyId']) &&  isset($_POST['action']) && ($_POST['action'] == 'setStoryNotOverwrite') ){
  $storyID = $_POST['storyId'];  
  dbCon();
  $result = mysql_fetch_array(mysql_query("SELECT `user` FROM `page` WHERE `pageID` = '" . $storyID . "'"));   
  if($user->logged_in && ($user->userlevel == 8 || $user->userlevel == 9 || $result['user'] == $user->username)) {        
    $updateQuery = "UPDATE `page` set `can_overwrite` = 0 "; 
    $updateQuery .= 'WHERE `pageID` = "' . $storyID . '" ' ;
    if(mysql_query($updateQuery) === true){          
      echo 1;
    }else{
      echo 0; 
    }
  }else{
    echo 0;
  }
}

if(isset($_POST['storyId']) &&  isset($_POST['action']) && ($_POST['action'] == 'readyStory') ){
  $storyID = $_POST['storyId'];  
  dbCon();
  $result = mysql_fetch_array(mysql_query("SELECT `user` FROM `page` WHERE `pageID` = '" . $storyID . "'"));   
  if($user->logged_in && ($user->userlevel == 8 || $user->userlevel == 9 || $result['user'] == $user->username)) {        
    $updateQuery = "UPDATE `page` set `is_set` = 0 "; 
    $updateQuery .= 'WHERE `pageID` = "' . $storyID . '" ' ;
    if(mysql_query($updateQuery) === true){          
      echo 1;
    }else{
      echo 0; 
    }
  }else{
    echo 0;
  }
}

// reopen story by story owner
if(isset($_POST['storyId']) &&  isset($_POST['action']) && ($_POST['action'] == 'reopenStory') ){
  $storyID = $_POST['storyId'];  
  dbCon();
  $result = mysql_fetch_array(mysql_query("SELECT `user` FROM `page` WHERE `pageID` = '" . $storyID . "'"));   
  if($user->logged_in && ($user->userlevel == 8 || $user->userlevel == 9 || $result['user'] == $user->username)) {        
    $updateQuery = "UPDATE `page` set `is_finished` = 0 "; 
    $updateQuery .= 'WHERE `pageID` = "' . $storyID . '" ' ;
    if(mysql_query($updateQuery) === true){          
      echo 1;
    }else{
      echo 0; 
    }
  }else{
    echo 0;
  }
}

//replace sentence rep

if(isset($_POST['guess']) &&  isset($_POST['action']) && ($_POST['action'] == 'replaceRep') ){ 
    dbCon();
    $stId = $_POST['sentenceID'];
    $result = mysql_fetch_array(mysql_query("SELECT st.`user` FROM `sentence` se INNER JOIN `page` st ON se.`pageID` = st.`pageID` WHERE se.`sentenceId` = '" . $stId . "'"));
    if($user->logged_in && ($user->userlevel == 8 || $user->userlevel == 9 || $result['user'] == $user->username)) {   
      $qtmp1 = "UPDATE `sentence` SET `representation` = '" . mysql_real_escape_string(trim($_POST['guess'])) . "' WHERE `sentence`.`sentenceID` = '" . $stId . "'";
      $chk1 = mysql_query($qtmp1);
    }
    mysql_close();
}

if(isset($_POST['guess']) && isset($_POST['action']) && ($_POST['action'] == 'replaceCRep') ){ 
  dbCon();
  $stId = $_POST['sentenceID'];
  $result = mysql_fetch_array(mysql_query("SELECT st.`user` FROM `sentence` se INNER JOIN `page` st ON se.`pageID` = st.`pageID` WHERE se.`sentenceId` = '" . $stId . "'"));
  if($user->logged_in && ($user->userlevel == 8 || $user->userlevel == 9 || $result['user'] == $user->username)) {   
    $qtmp1 = "UPDATE `sentence` SET `context_rep` = '" . mysql_real_escape_string(trim($_POST['guess'])) . "' WHERE `sentenceID` = '" . $stId . "'";
    $chk1 = mysql_query($qtmp1) or die(mysql_error());
  }
  mysql_close();
}

if(isset($_POST['guess']) &&  isset($_POST['action']) && ($_POST['action'] == 'replaceSRep') ){  
  dbCon();
  $stId = $_POST['sentenceID'];
  $result = mysql_fetch_array(mysql_query("SELECT st.`user` FROM `sentence` se INNER JOIN `page` st ON se.`pageID` = st.`pageID` WHERE se.`sentenceId` = '" . $stId . "'"));
  if($user->logged_in && ($user->userlevel == 8 || $user->userlevel == 9 || $result['user'] == $user->username)) {   
    $qtmp1 = "UPDATE `sentence` SET `semantic_rep` = '". mysql_real_escape_string(trim($_POST['guess'])) ."' WHERE `sentence`.`sentenceID` = '" . $stId . "'";
    $chk1 = mysql_query($qtmp1);
  }
  mysql_close();
}
