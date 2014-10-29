<?php 

namespace NeoX\API;

require_once __DIR__ . "/Response.php";

class TResponseGenerateProtoGuess extends TResponse {
  
  protected $properties = array(
    "ApiGeneratedSplit" => array(
      "name" => "Generated Split Sentence",
      "type" => "object",
      "default" => null,
      "template" => array(
        "GeneratedSplit" => array(
          "name" => "Generated Split",
          "default" => "",
          "type" => "text"
        ),
        "GeneratedPos" => array(
          "name" => "Generated POS",
          "default" => "",
          "type" => "text"
        ),
        "MatchedProto" => array(
          "name" => "Matched Proto",
          "default" => "",
          "type" => "text"
        ),
        "MatchedSplit" => array(
          "name" => "Matched Split",
          "default" => "",
          "type" => "text"
        ),
        "SplitMatchScore" => array(
          "name" => "Split Match Score",
          "default" => "",
          "type" => "numeric"
        ),
        "SplitStatus" => array(
          "name" => "Split Status",
          "default" => "",
          "type" => "bool"
        ),
        "Kids" => array(
          "name" => "Kids",
          "default" => array(),
          "type" => "recursive",
          "wrapper" => "ApiGeneratedSplit",
          "friendly_wrapper" => "ApiGeneratedSplit"
        )
      )
    ),
    "ApiGeneratedSplit2" => array(
      "name" => "Generated Split Sentence 2",
      "type" => "object",
      "default" => null,
      "template" => array(
        "GeneratedSplit" => array(
          "name" => "Generated Split",
          "default" => "",
          "type" => "text"
        ),
        "GeneratedPos" => array(
          "name" => "Generated POS",
          "default" => "",
          "type" => "text"
        ),
        "MatchedProto" => array(
          "name" => "Matched Proto",
          "default" => "",
          "type" => "text"
        ),
        "MatchedSplit" => array(
          "name" => "Matched Split",
          "default" => "",
          "type" => "text"
        ),
        "SplitMatchScore" => array(
          "name" => "Split Match Score",
          "default" => "",
          "type" => "numeric"
        ),
        "SplitStatus" => array(
          "name" => "Split Status",
          "default" => "",
          "type" => "bool"
        ),
        "Kids" => array(
          "name" => "Kids",
          "default" => array(),
          "type" => "recursive",
          "wrapper" => "ApiGeneratedSplit",
          "friendly_wrapper" => "ApiGeneratedSplit"
        )
      )
    )
  );
  
}

?>