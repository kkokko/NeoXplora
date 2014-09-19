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
  
  public function __construct($data = null) {
    if($data) {
      $this->properties['RepText']['value'] = ((string) $data->RepText)?$data->RepText:null;
      $this->properties['MatchedSentence']['value'] = ((string) $data->MatchedSentence)?$data->MatchedSentence:null;
    }
  }
  
}

?>