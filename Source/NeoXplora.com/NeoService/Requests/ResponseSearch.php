<?php
  namespace TApp;
  class TResponseSearch extends \sky\TEntity{
    protected function DefineProperties(){
      parent::DefineProperties();
      $this->AddProperties(array(
        "Pages" => array("check" => "TEntityList"), //Array of TSearchPage
		"PageCount" => array("check" => "Integer"),
		"Offset" => array("check" => "Integer")
      ));
    }
  }
?>