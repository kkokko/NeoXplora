<?php 

namespace NeoX\API;

require_once __DIR__ . "/Response.php";

class TResponseGenerateProtoGuess extends TResponse {
  
  protected $properties = array(
    "GeneratedSplit" => array(
      "name" => "Generated Split",
      "value" => "",
      "type" => "text"
    ),
    "GeneratedPos" => array(
      "name" => "Generated POS",
      "value" => "",
      "type" => "text"
    ),
    "MatchedProto" => array(
      "name" => "Matched Proto",
      "value" => "",
      "type" => "text"
    ),
    "MatchedSplit" => array(
      "name" => "Matched Split",
      "value" => "",
      "type" => "text"
    ),
    "SplitMatchScore" => array(
      "name" => "Split Match Score",
      "value" => "",
      "type" => "numeric"
    ),
    "SplitStatus" => array(
      "name" => "Split Status",
      "value" => "",
      "type" => "bool"
    )
  );
  
}

?>