<?php

namespace NeoX\API;

require_once "APIEntity.php";
class TRequest extends TAPIEntity {
  
  public function __construct($data = null) {
    $this->properties['ApiKey'] = array(
      "name" => "ApiKey",
      "default" => "",
      "type" => "text"
    );
    
    parent::__construct($data);
  }

}

?>