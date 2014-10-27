<?php 

namespace NeoX\API;

require_once __DIR__ . "/Request.php";

class TRequestGenerateProtoGuess extends TRequest {
  
  protected $properties = array(
    "ApiKey" => array(
      "name" => "ApiKey",
      "value" => "",
      "type" => "text"
    ),
    "SentenceText" => array(
      "name" => "Proto-Sentence",
      "value" => "I play computer games because I am bored.",
      "type" => "text"
    ),
    "SplitThreshold" => array(
      "name" => "Split Threshold",
      "value" => "0.1",
      "type" => "numeric"
    ), 
  );
  
}

?>