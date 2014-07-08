<?php
namespace NeoX\Controller;

use NeoX\Entity;

require_once APP_DIR . "/app/system/Object.php";
class TTrain extends \SkyCore\TObject {

  protected $accessLevel = 'user';

  public function index() {
    $this->template->load("index", "train");
    $this->template->pageTitle = "Train";
    $this->template->page = "train";
    $this->template->hide_right_box = false;
    $this->template->render();
  }

  protected function updatePageStatus($sentenceID, $protoID = 0) {
    $query = null;
    if($protoID > 0) {
      $query = $this->core->model("train")->getSentencesByProtoId($protoID);
    } else {
      $query = $this->core->model("train")->getSentencesFromPageById($sentenceID);
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
      if($pageID == 0) $pageID = $sentence[Entity\TSentence::$tok_pageid]; 
      if($pageStatus == 0) $pageStatus = $sentence['pageStatus'];
      switch($sentence[Entity\TSentence::$tok_status]) {
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
    
    $this->core->entity("page")->update($pageID, array("status" => $newPageStatus));
    return $newPageStatus; 
  }

}
?>