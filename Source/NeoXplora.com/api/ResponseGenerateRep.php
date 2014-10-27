<?php 

namespace NeoX\API;

require_once __DIR__ . "/Response.php";

class TResponseGenerateRep extends TResponse {
  
  protected $properties = array(
    "RepText" => array(
      "name" => "Representation",
      "value" => "",
      "type" => "text"
    ),
    "MatchedSentence" => array(
      "name" => "Matched Sentence",
      "value" => null,
      "type" => "text"
    )
  );
  
}

?>