<?php
ini_set('default_charset', 'UTF-8');
header("content-type: text/html; charset=utf-8");

define("_VALID_PHP", true);
require_once ("init.php");
require_once "NeoShared/Server/App/Global.php";

include 'finediff.php';
if (isset($_POST['page'])) {
    $storyId = isset($_POST['storyid']) ? $_POST['storyid']: -1;
    $page = $_POST['page'];
    $cur_page = $page;
    $page -= 1;
    $per_page = 1;
    $previous_btn = true;
    $next_btn = true;
    $first_btn = true;
    $last_btn = true;
    $start = $page * $per_page;
    
    require_once "config_storydb.php"; 
    mysql_connect($configuration['host'], $configuration['user'], $configuration['pass']);
    mysql_set_charset("utf8");
    mysql_select_db($configuration['db']);
    
    
    
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
        $hliteHTML = "";
        $currpos = 0;

        for ($k = 0; $k < count($delpos) + 1; $k++) {            //echo $currpos." ".$delpos[$k]." ".$udelpos[$k]."</br>";
            if ($k == count($delpos)) {
                $length = strlen($diffHTML) - $currpos;
            } else {
                $length = $delpos[$k] - $currpos;
            }
            $hliteHTML .= substr($diffHTML, $currpos, $length);
            if(isset($udelpos[$k])) $currpos = $udelpos[$k] + 6;
        } //$k   
        if (count($delpos) == 0) {
            $hliteHTML = $diffHTML;
        } 
        $hliteHTML = str_replace("<ins>", "<font color = red>", $hliteHTML);
        $hliteHTML = str_replace("</ins>", "</font>", $hliteHTML);
        return $hliteHTML;
    }

    // end hlite function   
     
    if($storyId == -1 ){
      $query_pag_data = "SELECT * from `page` order by `pageID` LIMIT $start, $per_page  ";
    }else{
      $query_pag_data = "SELECT * from `page` where `pageID` = '$storyId' ";
      
      //generate page number 
      if($cur_page == -1){
        // $cur_page =
        $queryCurrentPage =  "SELECT COUNT(*) AS count FROM `page` where pageID <= '$storyId'  ";
        $result_current_page = mysql_query($queryCurrentPage);    
        if(!($result_current_page == false)){          
          $currentPageRow = mysql_fetch_array($result_current_page);
          $cur_page = $currentPageRow['count'];
        } 
      }
      
    }
    $result_pag_data = mysql_query($query_pag_data) or die('MySql Error' . mysql_error());
    $msg = "";
    $ctb_data = "";
    $tb_data = "";
    $htmltext = "";
    $storysentence = "";
    $faqtb_data = "";
    $control_links = "";
    $statspercentages = "";
    $storyID = 0;
    $countr = 0;
    $cats = "";
    $canEdit = 'false';
    while ($row = mysql_fetch_array($result_pag_data)) {
        $storyID = $row['pageID'];
        $tb_data .='<br><table id="rep-data">
            <tr>
              <th align="left" width="20%">Sentences</th>
              <th width="40"></th>
              <th colspan="2" align="left" width="30%">Reps</th>
              <th width="20"></th>
              <th align="left" width="30%">CReps</th>
              <th align="left">Semantic Reps</th>
            </tr>';
            
        $sentences = $server->GetFullSentencesForStoryId((int) $storyID);
        $countcrep = 1;
        $countr = 0;
 
        $percent= '25%';
        $guessrep1=array();
        $guessrep2=array();
        $guessrep3=array();
        
        for($i = 0; $i < $sentences->Count(); $i++) {
          $sentence = $sentences->Item($i);
          if ($sentence->GetProperty('Name') != "") {
            $guessrep1[]= str_replace('%','',similarity($sentence->GetProperty('Rep'), $sentence->GetProperty('Guesses')->GetProperty('RepGuessA')));
            $guessrep2[]= str_replace('%','',similarity($sentence->GetProperty('Rep'), $sentence->GetProperty('Guesses')->GetProperty('RepGuessB')));
            $guessrep3[]= str_replace('%','',similarity($sentence->GetProperty('Rep'), $sentence->GetProperty('Guesses')->GetProperty('RepGuessC')));
          }

          $repI = $sentence->GetProperty('Rep');
          $crepI = $sentence->GetProperty('CRep');            
          $diff = FineDiff::getDiffOpcodes(trim($repI), trim($crepI), "");
          $diffHTML = " " . FineDiff::renderDiffToHTMLFromOpcodes($repI, $diff);
          $hliteHTML = hlite($diffHTML);
          $htmltext .= $hliteHTML;
          
          $allowEditClass = '';
          $allowCrepClass = '';      
          $allowSemanticCreps = 'semanticreps';     
          if( ($user->logged_in)  && ($user->userlevel == 8 || $user->userlevel == 9 || $row['user'] == $user->username) ){
            $allowEditClass = ' class="td-edit" ';
            $allowCrepClass = ' class="td-crep" ';  
          }

          $semantic_rep = ($sentence->GetProperty('SRep'))?$sentence->GetProperty('SRep'):"-";
          $sentenceID = $sentence->GetProperty('Id');
          $tb_data .='
              <tr rowspan="2" class="tab2sentencerow" id="tr' . $sentence->GetProperty('Id') . '"> 
                <td class="sentnc">
                  <span '.$allowEditClass.' id="edit' . $sentenceID . '">' . $sentence->GetProperty('Name') . '</span><br/>
                  <span style="margin-bottom:5px;color;color:#999999;" id="matchsentc4' . $sentenceID . '" guessid="' . $sentence->GetProperty('GuessIdD') .'" class="guess" >' . $sentence->GetProperty('Guesses')->GetProperty('MatchSentenceD') . '</span><br />
                  <span style="margin-bottom:5px;color;color:#999999;" id="matchsentc3' . $sentenceID . '" guessid="' . $sentence->GetProperty('GuessIdC') .'" class="guess" >' . $sentence->GetProperty('Guesses')->GetProperty('MatchSentenceC') . '</span><br />
                  <span style="margin-bottom:5px;color;color:#999999;" id="matchsentc1' . $sentenceID . '"  guessid="' . $sentence->GetProperty('GuessIdA') .'" class="guess">' . $sentence->GetProperty('Guesses')->GetProperty('MatchSentenceA') . '</span><br>
                  <span id="matchsentc2' . $sentenceID . '" style="color;color:#999999;"   guessid="' . $sentence->GetProperty('GuessIdB') .'" class="guess">' . $sentence->GetProperty('Guesses')->GetProperty('MatchSentenceB') . '</span><br>
                </td>
                <td>
                  <a href="javascript:void(0);" style="height:21px;width:21px;background:#fff" id="upd' . ($countr + 1) . '" class="updateGuess" rel="' . $sentenceID . ' "alt="' . ($countr + 1) . '" style="color:#3399FF !important;">
                    <img src="assets/refresh.png" class="refreshicone" style=" visibility:hidden; background-color:#fff;" />
                  </a><br />
                  <a id="4" sid="'. $sentenceID . '" class="tr-hover" href="javascript:void(0);"  style="height:21px;width:21px;background:#fff">
                    <img src="assets/tick.png" alt="4" class="select-repguess" style="visibility:hidden;margin-top:3px;"  />
                  </a><br />
                  <a id="3" sid="'. $sentenceID . '" class="tr-hover" href="javascript:void(0);"  style="height:21px;width:21px;background:#fff">
                    <img src="assets/tick.png" alt="3" class="select-repguess" style="visibility:hidden;margin-top:3px;"  />
                  </a><br />
                  <a id="1" sid="'. $sentenceID . '" class="tr-hover" href="javascript:void(0);" style="height:21px;width:21px;background:#fff">
                    <img src="assets/tick.png" class="select-repguess" alt="1" style="visibility:hidden;margin-top:3px;" />
                  </a><br />
                  <a id="2" sid="'. $sentenceID . '" class="tr-hover" href="javascript:void(0);"  style="height:21px;width:21px;background:#fff">
                    <img src="assets/tick.png" alt="2" class="select-repguess" style="visibility:hidden;margin-top:3px;"  />
                  </a>
                </td>
                <td class="senrep" colspan="2">
                  <span '.$allowEditClass.'>' . ( (trim($sentence->GetProperty('Rep')) == '') ? '-': $sentence->GetProperty('Rep')) . '</span><br/>
                  <span  guessid="' . $sentence->GetProperty('GuessIdD') .'" class="guess"  style="color;color:#999999;"id="4gr' . ($countr + 1) . '">' . $sentence->GetProperty('Guesses')->GetProperty('RepGuessD') . '</span><br />
                  <span  guessid="' . $sentence->GetProperty('GuessIdC') .'" class="guess"  style="color;color:#999999;" id="3gr' . ($countr + 1) . '">' .  $sentence->GetProperty('Guesses')->GetProperty('RepGuessC') . '</span><br />
                  <span  guessid="' . $sentence->GetProperty('GuessIdA') .'" class="guess"  style="color;color:#999999;"id="1gr' . ($countr + 1) . '">'.  $sentence->GetProperty('Guesses')->GetProperty('RepGuessA') . '</span><br />
                  <span  guessid="' . $sentence->GetProperty('GuessIdB') .'" class="guess"  style="color;color:#999999;"id="2gr' . ($countr + 1) . '">'.  $sentence->GetProperty('Guesses')->GetProperty('RepGuessB') . '</span><br />
                  <br/>
                </td>
                <td>
                  <div class="update-crep-row" id="thegr' . $sentenceID . '">
                  </div>
                </td>
                <td class="creprow"><span style="display:none;">' . $sentence->GetProperty('CRep') . '</span><span '.$allowCrepClass.'>' . ( (trim($hliteHTML) =='')?'-':$hliteHTML). '</span><br/>
                    <span class="guess" style="color;color:#999999;">' . $sentence->GetProperty('Guesses')->GetProperty('CRepGuessA') . '</span><br />
                </td>
                <td class="sreprow ' . $allowSemanticCreps . '"><span '.$allowEditClass.'>' . $semantic_rep . '</span><br/>
                  <span  guessid="' . $sentence->GetProperty('GuessIdD') .'" class="guess" id="4'. $sentenceID .'s" style="color;color:#999999;">' . $sentence->GetProperty('Guesses')->GetProperty('SRepGuessD') . '</span><br />
                  <span  guessid="' . $sentence->GetProperty('GuessIdC') .'" class="guess" id="3'. $sentenceID .'s" style="color;color:#999999;">' . $sentence->GetProperty('Guesses')->GetProperty('SRepGuessC') . '</span><br />
                  <span  guessid="' . $sentence->GetProperty('GuessIdA') .'" class="guess" id="1'. $sentenceID .'s" style="color;color:#999999;">'. $sentence->GetProperty('Guesses')->GetProperty('SRepGuessA') . '</span><br />
                  <span  guessid="' . $sentence->GetProperty('GuessIdB') .'" class="guess" id="2'. $sentenceID .'s" style="color;color:#999999;">'. $sentence->GetProperty('Guesses')->GetProperty('SRepGuessB')  . '</span><br />
                  <br/>
                </td>
              </tr>';
          $countcrep = $countcrep + 1;
          $countr = $countr + 1;
          
        }

        $average1 = array_sum($guessrep1) / ((count($guessrep1))?count($guessrep1):1);
        $average2 = array_sum($guessrep2) / ((count($guessrep2))?count($guessrep2):1);
        $average3 = array_sum($guessrep3) / ((count($guessrep3))?count($guessrep3):1);

        $statspercentages =  ' &nbsp;&nbsp;<span style="color:#000; visibility: hidden;" >'. round ($average3)."%  &nbsp;&nbsp; ". round ($average1)."%  &nbsp;&nbsp; ".round ($average2). "% </span>";

        $tb_data .='</table> ';
        $ctb_data .='</table> ';
        
        $storysentence = "";
        $q = mysql_query("SELECT `name` FROM `proto` WHERE `pageID` = '" . $storyID . "' AND `level` = '1' order by `prID` ASC ");
        while($pr_row = mysql_fetch_array($q)) {
          $storysentence .= $pr_row['name'];
          if($pr_row['name'] && !($pr_row['name'][strlen($pr_row['name']) - 1] == "?" || $pr_row['name'][strlen($pr_row['name']) - 1] == "!" || $pr_row['name'][strlen($pr_row['name']) - 1] == ".")) {
            $storysentence .= ".";
          }
          $storysentence .= " ";
        }
        
        $msg .= "<li data-value='" . $row['persnt'] . "'><span  class='stitle'>" . $row['title'] . " " . (($row['user'] != "")?(" <strong> added by ".$row['user']."</strong>"):"<strong> added by  unknown </strong>") . "</span><div style='width:50%'>" . $storysentence . "</div></li>";
        $msg .='</li>';        //DISPLAY q, a & rule
        $faqquery = "select * from qa where pageID=" . $storyID;
        $result_story_faq_data = mysql_query($faqquery) or die('MySql Error' . mysql_error());
        $faqtb_data .='<table id="faq-data" width="100%"><tr><th align="left" width="33%">Question</th><th align="left" width="33%">Answer</th><th align="left" width="33%">QA Rule</th></tr>';

        $allowEditClass = '';
        $allowCrepClass = '';      
        $allowSemanticCreps = ' class="semanticreps" ';     
        if( ($user->logged_in)  && ($user->userlevel == 8 || $user->userlevel == 9 || $row['user'] == $user->username) ){
          $allowEditClass = ' class="td-edit" ';
          $allowCrepClass = ' class="td-crep" ';  
        }

        while ($faqrows = mysql_fetch_array($result_story_faq_data)) {
          //if (!empty($faqrows['question'])) {
            $question =  ($faqrows['question'])?$faqrows['question']:"-";
            $answer =  ($faqrows['answer'])?$faqrows['answer']:"-";
            $qarule =  ($faqrows['qarule'])?$faqrows['qarule']:"-";
            $faqtb_data .='<tr id="tr' . $faqrows['questionID'] . '">
                                <td class="quest"><span '.$allowEditClass.'>' . $question . '</span></td>
                                <td class="ans"><span '.$allowEditClass.'>' . $answer . '</span></td>
                                <td class="qrule"><span '.$allowEditClass.'>' . $qarule . '</span></td></tr>';
          //}     
        }
        /* Display the control links according to the user's permission  */
        
        if($user->logged_in) {
          $control_links .= '<a class="add" href="#">Add</a>';
          if($user->userlevel == 8 || $user->userlevel == 9) {
            $control_links .= ' / <a href="scheduler.php">Schedule</a> ';
          }
          if($user->userlevel == 8 || $user->userlevel == 9 || $row['user'] == $user->username) {
            $control_links .= ' / <a href="#" class="editstory">Edit</a> / <a class="deletestory" href="#">Delete</a> ';
          
            $faqtb_data .='<tr class="addQA">
                  <td><br/><input type="text" value="" class="new-value-q" style="border:#bbb 1px solid;padding-left:5px;width:75%;"></td> 
                  <td><br/><input type="text" value="" class="new-value-a" style="border:#bbb 1px solid;padding-left:5px;width:75%;"></td>
                  <td><br/>
                    <input type="text" value="" class="new-value-r" style="border:#bbb 1px solid;padding-left:5px;width:75%;"> 
                    <input type="submit" value="Add" class="submitQA" style="color: #CC413E;" />
                  </td>
                </tr>';
			     $canEdit = 'true'; 
          }
        }
        
        $faqtb_data .='</table>';
        
        $query_cats = mysql_query("SELECT * FROM `category` WHERE `parentId` = '0'"); 
        while($row_cat = mysql_fetch_array($query_cats)) {
          $cats .= "<option value='" . $row_cat['categoryID'] . "'";
          if($row_cat['categoryID'] == $row['categoryID']) { $cats .= " selected='selected'"; }
          $cats .= ">" . $row_cat['category'] . "</option>";
          $query_scats = mysql_query("SELECT * FROM `category` WHERE `parentId` = '" . $row_cat['categoryID'] . "'");
          while($row_scat = mysql_fetch_array($query_scats)) {
            $cats .= "<option value='" . $row_scat['categoryID'] . "'";
            if($row_scat['categoryID'] == $row['categoryID']) { $cats .= " selected='selected'"; }
            $cats .= ">-- " . $row_scat['category'] . "</option>";
          }
        }
    }

    $msg = "<div class='data'>
                <select class='category_selection'>
                  " . $cats . "
                </select>
            <ul>" . $msg . "</ul></div>"; // Content for Data
        
        
    /* --------------------------------------------- */
    $query_pag_num = "SELECT COUNT(*) AS count FROM page";
    $result_pag_num = mysql_query($query_pag_num);
    $row = mysql_fetch_array($result_pag_num);
    $count = $row['count'];
    $no_of_paginations = ceil($count / $per_page);
    /* ---------------Calculating the starting and endign values for the loop----------------------------------- */
    if ($cur_page >= 7) {
        $start_loop = $cur_page - 3;
        if ($no_of_paginations > $cur_page + 3)
            $end_loop = $cur_page + 3;
        else if ($cur_page <= $no_of_paginations && $cur_page > $no_of_paginations - 6) {
            $start_loop = $no_of_paginations - 6;
            $end_loop = $no_of_paginations;
        } else {
            $end_loop = $no_of_paginations;
        }
    } else {
        $start_loop = 1;
        if ($no_of_paginations > 7)
            $end_loop = 7;
        else
            $end_loop = $no_of_paginations;
    }
    /* ----------------------------------------------------------------------------------------------------------- */
    $msg .= "<div class='pagination'><ul>";
// FOR ENABLING THE FIRST BUTTON   
    if ($first_btn && $cur_page > 1) {
        $msg .= "<li p='1' class='active firstactive ui-btn '>&nbsp;</li>";
    } else if ($first_btn) {
        $msg .= "<li p='1' class='inactive firstinactive ui-btn '>&nbsp;</li>";
    }
// FOR ENABLING THE PREVIOUS BUTTON   
    if ($previous_btn && $cur_page > 1) {
        $pre = $cur_page - 1;
        $msg .= "<li p='$pre' class='active prevactive ui-btn '>&nbsp;</li>";
    } else if ($previous_btn) {
        $msg .= "<li class='inactive previnactive ui-btn '>&nbsp;</li>";
    } for ($i = $start_loop; $i <= $end_loop; $i++) {
        if ($cur_page == $i)
            $msg .= "";
        else
            $msg .= "";
    }
// TO ENABLE THE NEXT BUTTON
    if ($next_btn && $cur_page < $no_of_paginations) {
        $nex = $cur_page + 1;
        $msg .= "<li p='$nex' class='active nextactive ui-btn '>&nbsp;</li>";
    } else if ($next_btn) {
        $msg .= "<li class='inactive nextinactive  ui-btn '>&nbsp;</li>";
    }
// TO ENABLE THE END BUTTON
    if ($last_btn && $cur_page < $no_of_paginations) {
        $msg .= "<li p='$no_of_paginations' class='active lastactive ui-btn '>&nbsp;</li>";
    } else if ($last_btn) {
        $msg .= "<li p='$no_of_paginations' class='inactive lastdactive  ui-btn '>&nbsp;</li>";
    } $total_string = "<span class='total' a='$no_of_paginations'>Story <b><input class='currentpage_input' type='text' value='" . $cur_page . "' style='display: inline-block;border: #bbb 1px solid;padding-left: 5px;width: 45px;padding: 0;text-align: center;background-color: #fff;'/></b> of <b>$no_of_paginations</b></span>";
    $msg = $msg . "</ul>" . $total_string . "</div>";
// Content for pagination
//-----------------------------------REP data---------------------------------------

    $arrtmp=array('msg' => $msg, 'table' => $tb_data, 'creptable' => $ctb_data, 'faqtable' => $faqtb_data, 
      'control_links' => $control_links, 'canEdit'=> $canEdit, 'stats' => $statspercentages, 'total' => $countr, 
      'tablePrep' => getTablePrepStory($storyID, $user), 'storyId' => $storyID, 'isFinished'=>false);
    echo json_encode($arrtmp);
    die;
}

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
  
//  //Something to write to txt log
//  $log  = "date: ".date("F j, Y, g:i a").PHP_EOL.
//        "Guessrep: ". $guessrep.PHP_EOL.
//        "rep: ". $rep.PHP_EOL.    
//        "-------------------------".PHP_EOL;
////Save string to log, use FILE_APPEND to append.
//  file_put_contents('./log_'.date("j.n.Y").'.txt', $log, FILE_APPEND);

    return $percentage;
}

function getTablePrepStory($AStoryId, $user){
  
  //check if user can edit sentence
  $allowEditClass = '';
  //check if user can edit parent sentence
  $allowCrepClass = '';   
 
  //check if user is owner of story
  $storyResult = mysql_fetch_array(mysql_query("SELECT `user`, `title`, `body`, `is_split`, `is_finished` FROM `page` WHERE `pageID` = '" . $AStoryId . "'"));
  if($user->logged_in && ($user->userlevel == 8 || $user->userlevel == 9 || $storyResult['user'] == $user->username)) {   
    $allowEditClass = ' class="td-crep-st" ';
    $allowCrepClass = ' class="td-crep-st" ';  
  } 
  $title = $storyResult['title'];
  $isSplit = $storyResult['is_split'];
  $isFinished = $storyResult['is_finished'];  
  if($isFinished == 1){
    $allowEditClass = '';
    $allowCrepClass = '';
  }else{
    if($isSplit == 1){
      // can edit parent sentence only before first split    
      $allowEditClass = '';
    }
  }
  
  
  require_once $GLOBALS["SkyFrameworkPath"]."Entity/SkyIdList.php";
  require_once $GLOBALS["SkyFrameworkPath"]."Entity/EntityWithName.php";
  require_once "NeoDesktop/Entity/ProtoSentence.php";
  
  $pr_list = new \sky\TSkyIdList();
  $crep_data = "SELECT * FROM `sentence` where `pageID` = '" . $AStoryId . "' ORDER BY `sentenceID` ASC";
  $sresult_crep_data = mysql_query($crep_data) or die('MySql Error' . mysql_error());
  $query = "SELECT * FROM `proto` WHERE `pageID` = '" . $AStoryId . "' ORDER BY `prID` ASC";
  $protosentence_data = mysql_query($query) or die('MySql Error' . mysql_error());
  
  while($prrow = mysql_fetch_array($protosentence_data)) {
    $TheProtoSentence = new \TApp\TProtoSentence();
    $TheProtoSentence->SetProperty("Name", $prrow['name']);
    $TheProtoSentence->SetProperty("Level", (int) $prrow['level']);
    $TheProtoSentence->SetProperty("StoryID", (int) $prrow['pageID']);
    $pr_list->Add($prrow['prID'], $TheProtoSentence);
  }
  
  while($srow = mysql_fetch_array($sresult_crep_data)) {
    $TheSentence = new \sky\TEntityWithName();
    $TheSentence->SetProperty("Id", (int) $srow['sentenceID']);
    $TheSentence->SetProperty("Name", $srow['sentence']);
    $prIndex = $pr_list->Search($srow['pr2ID']);
    if($prIndex != "-1") {
      $TheProtoObject = $pr_list->Object($prIndex);
      $TheProtoObject->GetProperty("Sentences")->Add($TheSentence);
    }
  }
  
  $htmltext = '<style type="text/css">
    ul#rep-data li {
      padding: 5px;
    }
    .pr {
      font-weight: bold;
    }
  </style>
  <ul id="rep-data">';
  
  for($i = 0; $i < $pr_list->Count(); $i++) {
    if($pr_list->Object($i)->GetProperty("Level") == 1) {
      $htmltext .= '<li class="pr pr1" id="pr' . $pr_list->Item($i) . '" level="' . $pr_list->Object($i)->GetProperty("Level") . '" ><span '.$allowCrepClass.'>' . $pr_list->Object($i)->GetProperty("Name") . '</span></li>';  
    } else {
      $htmltext .= '<li class="pr pr' . $pr_list->Object($i)->GetProperty("Level") . '" level="' . $pr_list->Object($i)->GetProperty("Level") . '" style="margin-left: ' . ($pr_list->Object($i)->GetProperty("Level") - 1) * 20 . 'px;" id="pr' . $pr_list->Item($i) . '"><span '.$allowCrepClass.' id="edit' . $pr_list->Item($i) . '">' . $pr_list->Object($i)->GetProperty("Name") . '</span></li>';
      for($j = 0; $j < $pr_list->Object($i)->GetProperty("Sentences")->Count(); $j++) {
        $htmltext .= '<li class="se se' . $pr_list->Item($i) . '" style="margin-left: ' . $pr_list->Object($i)->GetProperty("Level") * 20 . 'px;" id="s' . $pr_list->Object($i)->GetProperty("Sentences")->Item($j)->GetProperty("Id") . '"><span '.$allowCrepClass.' id="edit' . $pr_list->Object($i)->GetProperty("Sentences")->Item($j)->GetProperty("Id") . '">' . $pr_list->Object($i)->GetProperty("Sentences")->Item($j)->GetProperty("Name") . '</span></li>';
      }
    }
  }
  $htmltext .= '</ul>';
  
  return $htmltext;
}