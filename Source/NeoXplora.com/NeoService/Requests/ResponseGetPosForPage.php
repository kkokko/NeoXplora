<?php
  namespace TApp;
  class TResponseGetPosForPage extends \sky\TEntity{
    protected function DefineProperties(){
      parent::DefineProperties();
      $this->AddProperties(array(
        "Sentences" => array("check" => "TSkyIdList") // array of Id - TSkyStringStringList
      ));
    }
  }
?>