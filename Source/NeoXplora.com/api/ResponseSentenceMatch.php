<?php 

namespace NeoX\API;

require_once __DIR__ . "/Response.php";

class TResponseSentenceMatch extends TResponse {

  protected $displayAs = "text";
  protected $properties = array(
    "DataFull" => array(
      "name" => "",
      "type" => "object",
      "default" => null,
      "template" => array(
        "ApiGeneratedSplitFull" => array(
          "name" => "ApiGeneratedSplitFull",
          "type" => "object",
          "default" => null,
          "template" => array(
            "Sentence" => array(
              "name" => "Sentence",
              "default" => "",
              "type" => "text"
            ),
            "MatchSentence" => array(
              "name" => "Matched Sentence",
              "default" => "",
              "type" => "text"
            ),
            "MatchScore" => array(
              "name" => "Match Score",
              "default" => "",
              "type" => "numeric"
            ),
            "SplitStatus" => array(
              "name" => "Split Status",
              "default" => "",
              "type" => "bool"
            ),
            "Substitutions" => array(
              "name" => "Substitutions",
              "type" => "object",
              "default" => null,
              "template" => array(
                "Row" => array(
                  "name" => "",
                  "type" => "array",
                  "default" => null,
                  "template" => array(
                    "Key" => array(
                      "name" => "Key",
                      "default" => "",
                      "type" => "text"
                    ),
                    "Value" => array(
                      "name" => "Value",
                      "default" => "",
                      "type" => "text"
                    )
                  )
                )
              )
            )
            
            
          )
        )
      )
    )
  );
  
  protected function propertiesToText($someProperties, $aWrapper, $aDepth) {
    $text = "";
    if($aWrapper !== '') {
      $text .= str_repeat("&nbsp;", $aDepth * 2);
      $text .= "" . $aWrapper . ":";
      $text .= "<br/>";
    }
    if(isset($someProperties['DataFull']) && $someProperties['DataFull']['value'] !== null) {
      $text .= $this->dataToText("ApiGeneratedSplitFull", $someProperties['DataFull']['value']['ApiGeneratedSplitFull'], $aDepth + 1, $someProperties);
      return $text;
    } else {
      $text = "";
      foreach($someProperties AS $aPropertyKey => $aPropertyValue) {
        $text .= $this->propertyToText($aPropertyKey, $aPropertyValue, $aDepth + 1);
      }
      $text .= "";
    }
    return $text;
  }

  protected function dataToText($aPropertyKey, $aPropertyValue, $depth, $someProperties) {
    if($aPropertyValue['value'] === null || (is_array($aPropertyValue['value']) && count($aPropertyValue['value']) == 0)){
      return '';
    }
    $text = '';
    
    foreach($aPropertyValue['value'] AS $aKey => $aValue) {
      $text .= $this->getDataText($aKey, $aValue, $depth, $aPropertyValue['value']);
    } 
    return $text;
  }
  
  protected function getDataText($aPropertyKey, $aPropertyValue, $depth, $someProperties) {
    $text = "";
    if($aPropertyValue['type'] == "recursive") {
      $text .= "";
      foreach($aPropertyValue['value'] AS $anElement) {
        foreach($anElement AS $key => $value) {
          $text .= $this->dataToText($key, $value, $depth + 1, $anElement);
        }
      }
    } else if($aPropertyValue['type'] == "object") {
      $text .= "<b>" . $aPropertyValue["name"] ."</b>:";
      foreach($aPropertyValue['value'] AS $key => $value) {
        $text .= $this->getDataText($key, $value, $depth, $aPropertyValue['value']);
      }
      $text .= str_repeat("|&nbsp;&nbsp;", $depth);
    } else if($aPropertyValue['type'] == "array") {
      $text .= "<br/>";
      foreach($aPropertyValue['value'] AS $element) {
        $text .= str_repeat("&nbsp;&nbsp;", 2) . $element["Key"]["value"] . " » " . $element["Value"]["value"] . "<br/>";
      }
    } else {
      $text .= "";
      
      if($aPropertyValue['value'] !== null) {
        $text .= "<b>" . $aPropertyValue['name'] . "</b>: " . $aPropertyValue['value'];
        $text .= "<br/>";
      }
    }
    $text .= "";
    return $text;
  }
  
}

?>