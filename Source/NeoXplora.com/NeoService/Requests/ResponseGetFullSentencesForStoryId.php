<?php
  namespace TApp;
  class TResponseGetFullSentencesForStoryId extends \sky\TEntity{
    protected function DefineProperties(){
      parent::DefineProperties();
      $this->AddProperties(array(
        "Sentences" => array("check" => "TEntityList") //Array of TSentenceWithGuesses
      ));
    }
  }
?>
