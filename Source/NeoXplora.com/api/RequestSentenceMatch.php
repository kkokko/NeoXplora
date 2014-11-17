<?php 

namespace NeoX\API;

require_once __DIR__ . "/Request.php";

class TRequestSentenceMatch extends TRequest {
  
  protected $properties = array(
    "ApiKey" => array(
      "name" => "ApiKey",
      "default" => "",
      "type" => "text"
    ),
    "Sentence1Text" => array(
      "name" => "Sentence 1",
      "default" => "The jet flew away before the soldiers could get a lock",
      "type" => "text"
    ),
    "Sentence2Text" => array(
      "name" => "Sentence 2",
      "default" => "He didn't even apply his brakes before he crashed into the support",
      "type" => "text"
    ),
    "SepWeight" => array(
      "name" => "Separator Weight",
      "default" => 10,
      "type" => "int"
    )
  );
  
}

?>