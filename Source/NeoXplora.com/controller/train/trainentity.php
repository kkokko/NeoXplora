<?php 
require_once APP_DIR . "/app/system/appentity.php";
class TTrainEntity extends TAppEntity {

  protected $accessLevel = 'user';

  protected function query($string) {
    return $this->db->query($string);
  }
  
  protected function update_status($sentenceID, $protoID = 0) {
    $query = null;
    if($protoID > 0) {
      $query = $this->query("
        SELECT s.*, p2.`pageStatus` 
        FROM `sentence` s
        INNER JOIN `page` p2 ON s.`pageID` = p2.`pageID`
        WHERE s.`pageID` = 
          (
            SELECT se.`pageID` 
            FROM `sentence` se 
            WHERE se.`pr2ID` = '" . $protoID . "'
            LIMIT 1
          )
      "); 
    } else {
      $query = $this->query("
        SELECT s.*, p2.`pageStatus` 
        FROM `sentence` s
        INNER JOIN `page` p2 ON s.`pageID` = p2.`pageID`
        WHERE s.`pageID` = 
          (
            SELECT se.`pageID` 
            FROM `sentence` se 
            WHERE se.`sentenceID` = '" . $sentenceID . "'
            LIMIT 1
          )
      ");
    }
    $fGen = 0;
    $tSplit = 0;
    $rSplit = 0;
    $tRep = 0;
    $rRep = 0;
    $tCRep = 0;
    $rCRep = 0;
    $pageID = 0;
    $pageStatus = 0;
    
    //Gather how many of each type of sentence this page has
    while($sentence = $query->fetch_array()) {
      if($pageID == 0) $pageID = $sentence['pageID']; 
      if($pageStatus == 0) $pageStatus = $sentence['pageStatus'];
      switch($sentence['sentenceStatus']) {
        case 'ssReviewedCRep':
          $rCRep++;
          break;
        case 'ssTrainedCRep':
          $tCRep++;
          break;
        case 'ssReviewedRep':
          $rRep++;
          break;
        case 'ssTrainedRep':
          $tRep++;
          break;
        case 'ssReviewedSplit':
          $rSplit++;
          break;
        case 'ssTrainedSplit':
          $tSplit++;
          break;
        case 'ssFinishedGenerate':
          $fGen++;
          break;
      }
    }

    $newPageStatus = 'psFinishedGenerate';
 
    //based on the below criteria a new pageStatus is set
    if($rCRep > 0 && $tCRep == 0 && $rRep == 0) {
      $newPageStatus = 'psReviewedCRep';
    } else if($rCRep > 0 && $tCRep > 0 && $rRep == 0) {
      $newPageStatus = 'psReviewingCRep';
    } else if($rCRep == 0 && $tCRep > 0 && $rRep == 0) {
      $newPageStatus = 'psTrainedCRep';
    } else if($tCRep > 0 && $rRep > 0) {
      $newPageStatus = 'psTrainingCRep';
    } else if($rRep > 0 && $tRep == 0 && $rSplit == 0) {
      $newPageStatus = 'psReviewedRep';
    } else if($rRep > 0 && $tRep > 0 && $rSplit == 0) {
      $newPageStatus = 'psReviewingRep';
    } else if($rRep == 0 && $tRep > 0 && $rSplit == 0) {
      $newPageStatus = 'psTrainedRep';
    } else if($tRep > 0 && $rSplit > 0) {
      $newPageStatus = 'psTrainingRep';
    } else if($rSplit > 0 && $tSplit == 0 && $fGen == 0) {
      $newPageStatus = 'psReviewedSplit';
    } else if($rSplit > 0 && $tSplit > 0 && $fGen == 0) {
      $newPageStatus = 'psReviewingSplit';
    } else if($rSplit == 0 && $tSplit > 0 && $fGen == 0) {
      $newPageStatus = 'psTrainedSplit';
    } else if($tSplit > 0 && $fGen > 0) {
      $newPageStatus = 'psTrainingSplit';
    }
    
    $this->query("UPDATE `page` SET `pageStatus` = '". $newPageStatus ."' WHERE `pageID` = '". $pageID ."'") or die(mysql_error());
    return $newPageStatus; 
  }
  
  protected function generate_pagination($currentpage, $pagecount) {
    $pagination = array();
    if($pagecount > 5) {
      if($currentpage > 3 && $currentpage < $pagecount - 2) {
        $pagination[] = 1;
        $pagination[] = "skip";
        $pagination[] = $currentpage - 1;
        $pagination[] = $currentpage;
        $pagination[] = $currentpage + 1;
        $pagination[] = "skip";
        $pagination[] = $pagecount;
      } else if($currentpage <= 3) {
        $pagination[] = 1;
        $pagination[] = 2;
        $pagination[] = 3;
        $pagination[] = 4;
        $pagination[] = "skip";
        $pagination[] = $pagecount;
      } else {
        $pagination[] = 1;
        $pagination[] = "skip";
        $pagination[] = $pagecount - 3;
        $pagination[] = $pagecount - 2;
        $pagination[] = $pagecount - 1;
        $pagination[] = $pagecount;
      }
    } else if($pagecount != 1) {
      for($i = 1; $i <= $pagecount; $i++)
        $pagination[] = $i;
    }
    return $pagination;
  }
  
}

?>