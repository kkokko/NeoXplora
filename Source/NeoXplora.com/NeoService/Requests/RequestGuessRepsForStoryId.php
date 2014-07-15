<?php
  namespace TApp;
  class TRequestGuessRepsForStoryId extends \sky\TEntity{
    protected function DefineProperties(){
      parent::DefineProperties();
      $this->AddProperties(array(
        "StoryId" => array("check" => "Integer")
      ));
    }
    function __construct($AnId){
      parent::__construct();
      $this->SetProperty("StoryId", $AnId);
    }
  }
?>