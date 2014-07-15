<?php
namespace sky;

class TLanguage{
  protected $Language;
  
  static public $Instance;
  
  public function __construct() {
    $this->Language = array(
      "tlInvalidUserOrPassword" => "Invalid user name or password.",
      "tlInvalidSession" => "No information found for the current session on the server.",
      "tlError" => "Error",
      "tlServerUnknownException" => "Unknown error occured",
      "tlServerUnavailable" => "Server could not be reached. Try again later",
      "tlDatabaseRecordDoesNotExist" => "Database record does not exist or data version is old.",
      "tlDatabaseReferenceConstraint" => "The %s action could not be completed because the record is being used by one or more %s.",
      "tlDelete" => "delete"
    );
  }
  
  public function Translate($AToken) {
    if(array_key_exists($AToken, $this->Language)) {
      return $this->Language[$AToken];
    } else {
      return $AToken;
    }
  }
}

?>