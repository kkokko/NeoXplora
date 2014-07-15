<?php
  namespace TApp;
  require_once "SentenceBase.php";
  
  class TSentenceWithGuesses extends TSentenceBase {
    protected function DefineProperties(){
      parent::DefineProperties();
      $this->AddProperties(array(
        "Id" => array("check" => "Integer"), 
        "StoryId" => array("check" => "Integer"),
        "GuessIdA" => array("check" => "Integer"),
        "GuessIdB" => array("check" => "Integer"),
        "GuessIdC" => array("check" => "Integer"),
        "GuessIdD" => array("check" => "Integer"),
        "Guesses" => array("check" => "TGuessObject")
      ));
    }
  }
?>