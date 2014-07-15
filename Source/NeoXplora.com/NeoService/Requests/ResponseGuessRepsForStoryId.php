<?php
  namespace TApp;
  class TResponseGuessRepsForStoryId extends \sky\TEntity{
    protected function DefineProperties(){
      parent::DefineProperties();
      $this->AddProperties(array(
        "GuessObjects" => array("check" => "TEntityList") //Array of TGuessObject
      ));
    }
  }
?>