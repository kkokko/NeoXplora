<?php
  namespace TApp;
  class TResponseGuessRepsForSentenceId extends \sky\TEntity{
    protected function DefineProperties(){
      parent::DefineProperties();
      $this->AddProperties(array(
        "GuessObject" => array("check" => "TGuessObject")
      ));
    }
  }
?>