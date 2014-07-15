<?php
  namespace TApp;
  class TGuessObject extends \sky\TEntity {
    protected function DefineProperties(){
      parent::DefineProperties();
      $this->AddProperties(array(
        "Id" => array("check" => "Integer"), 
        "RepGuessA" => array("check" => "string"),
        "GuessIdA" => array("check" => "Integer"),
        "MatchSentenceA" => array("check" => "string"),
        "RepGuessB" => array("check" => "string"),
        "GuessIdB" => array("check" => "Integer"),
        "MatchSentenceB" => array("check" => "string"),
        "RepGuessC" => array("check" => "string"),
        "GuessIdC" => array("check" => "Integer"),
        "MatchSentenceC" => array("check" => "string"),
        "RepGuessD" => array("check" => "string"),
        "GuessIdD" => array("check" => "Integer"),
        "MatchSentenceD" => array("check" => "string"),
        "CRepGuessA" => array("check" => "string"),
        "SRepGuessA" => array("check" => "string"),
        "SRepGuessB" => array("check" => "string"),
        "SRepGuessC" => array("check" => "string"),
        "SRepGuessD" => array("check" => "string")
      ));
    }
  }
?>