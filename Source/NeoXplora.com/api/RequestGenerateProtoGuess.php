<?php 

namespace NeoX\API;

require_once __DIR__ . "/Request.php";

class TRequestGenerateProtoGuess extends TRequest {
  
  protected $properties = array(
    "ApiKey" => array(
      "name" => "ApiKey",
      "default" => "",
      "type" => "text"
    ),
    "SentenceText" => array(
      "name" => "Proto-Sentence",
      "default" => "I play computer games because I am bored.",
      "type" => "text"
    ),
    "SplitThreshold" => array(
      "name" => "Split Threshold",
      "default" => "0.6",
      "type" => "numeric"
    ),
    "MaxIterations" => array(
      "name" => "Maximum Iterations",
      "default" => "0",
      "type" => "int"
    ), 
    "SepWeight" => array(
      "name" => "Separator Weight",
      "default" => "10",
      "type" => "int"
    ), 
    "UseExact" => array(
      "name" => "Exact Match",
      "default" => "true",
      "type" => "bool"
    ),
    "FullDetails" => array(
      "name" => "Show Full Details",
      "default" => "false",
      "type" => "bool"
    )
  );
  
}

?>