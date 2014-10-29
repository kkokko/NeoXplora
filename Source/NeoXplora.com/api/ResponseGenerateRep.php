<?php 

namespace NeoX\API;

require_once __DIR__ . "/Response.php";
class TResponseGenerateRep extends TResponse {
  
  protected $properties = array(
    "RepText" => array(
      "name" => "Representation",
      "default" => "",
      "type" => "text"
    ),
    "MatchedSentence" => array(
      "name" => "Matched Sentence",
      "default" => null,
      "type" => "text"
    )
  );
  
}

?>