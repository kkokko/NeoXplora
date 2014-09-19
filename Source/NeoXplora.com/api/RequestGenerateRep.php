<?php 

namespace NeoX\API;

require_once __DIR__ . "/Request.php";

class TRequestGenerateRep extends TRequest {
  
  protected $properties = array(
    "ApiKey" => array(
      "name" => "ApiKey",
      "value" => "",
      "type" => "text"
    ),
    "SentenceText" => array(
      "name" => "Sentence",
      "value" => "My name is Mimi",
      "type" => "text"
    ),
    "OutputSentence" => array(
      "name" => "Output Sentence",
      "value" => null,
      "type" => "bool"
    )
  );
  
  public function __construct($data = null) {
    if($data) {
      $this->properties['ApiKey']['value'] = ($data['ApiKey'])?$data['ApiKey']:'';
      $this->properties['SentenceText']['value'] = ($data['SentenceText'])?$data['SentenceText']:'';
      $this->properties['OutputSentence']['value'] = (isset($data['OutputSentence']))?true:null;
    }
  }
  
}

?>