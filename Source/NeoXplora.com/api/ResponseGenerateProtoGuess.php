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
    )
  );
  
  public function __construct($data = null) {
    if($data) {
      $this->properties['GeneratedSplit']['value'] = ((string) $data->GeneratedSplit)?$data->GeneratedSplit:null;
      $this->properties['GeneratedPos']['value'] = ((string) $data->GeneratedPos)?$data->GeneratedPos:null;
      $this->properties['MatchedProto']['value'] = ((string) $data->MatchedProto)?$data->MatchedProto:null;
      $this->properties['MatchedSplit']['value'] = ((string) $data->MatchedSplit)?$data->MatchedSplit:null;
    }
  }
  
}

?>