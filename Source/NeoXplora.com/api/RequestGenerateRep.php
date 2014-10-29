<?php 

namespace NeoX\API;

require_once __DIR__ . "/Request.php";

class TRequestGenerateRep extends TRequest {
  
  protected $properties = array(
    "ApiKey" => array(
      "name" => "ApiKey",
      "default" => "",
      "type" => "text"
    ),
    "SentenceText" => array(
      "name" => "Sentence",
      "default" => "My name is Mimi",
      "type" => "text"
    ),
    "OutputSentence" => array(
      "name" => "Output Sentence",
      "default" => null,
      "type" => "bool"
    )
  );
  
}

?>