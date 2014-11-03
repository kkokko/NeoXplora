<?php 

namespace NeoX\API;

require_once __DIR__ . "/Response.php";

class TResponseGenerateProtoGuess extends TResponse {

  protected $displayAs = "text";
  protected $properties = array(
    "DataSimple" => array(
      "name" => "",
      "type" => "object",
      "default" => null,
      "template" => array(
        "ApiGeneratedSplit" => array(
          "name" => "ApiGeneratedSplit",
          "type" => "object",
          "default" => null,
          "template" => array(
            "Sentence" => array(
              "name" => "Sentence",
              "default" => "",
              "type" => "text"
            ),
            "SplitStatus" => array(
              "name" => "Split Status",
              "default" => "",
              "type" => "bool"
            ),
            "Splits" => array(
              "name" => "Splits",
              "default" => array(),
              "type" => "recursive",
              "wrapper" => "ApiGeneratedSplit",
              "friendly_wrapper" => "ApiGeneratedSplit"
            )
          )
        )
      )
    ),
    "DataFull" => array(
      "name" => "",
      "type" => "object",
      "default" => null,
      "template" => array(
        "ApiGeneratedSplitFull" => array(
          "name" => "",
          "type" => "object",
          "default" => null,
          "template" => array(
            "Sentence" => array(
              "name" => "Sentence",
              "default" => "",
              "type" => "text"
            ),
            "Pos" => array(
              "name" => "Generated POS",
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
            "Splits" => array(
              "name" => "Splits",
              "default" => array(),
              "type" => "recursive",
              "wrapper" => "ApiGeneratedSplitFull",
              "friendly_wrapper" => "ApiGeneratedSplitFull"
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
    if(isset($someProperties['DataSimple']) && $someProperties['DataSimple']['value'] !== null) {
      $text .= $this->dataToText("ApiGeneratedSplit", $someProperties['DataSimple']['value']['ApiGeneratedSplit'], $aDepth + 1, $someProperties);
      return $text;
    } else {
      $text = "<table style='width:100%;". (($aDepth > 0)?"border-bottom: 1px solid #aaa; border-left: 1px solid #000; padding-left: 15px;":'') ."'><tr><td>";
      foreach($someProperties AS $aPropertyKey => $aPropertyValue) {
        $text .= $this->propertyToText($aPropertyKey, $aPropertyValue, $aDepth + 1);
      }
      $text .= "</td></tr></table>";
    }
    return $text;
  }

  protected function dataToText($aPropertyKey, $aPropertyValue, $depth, $someProperties) {
    if($aPropertyValue['value'] === null || (is_array($aPropertyValue['value']) && count($aPropertyValue['value']) == 0)){
      return '';
    }
    return $this->getDataText($aPropertyKey, $aPropertyValue, $depth, $someProperties);
  }
  
  protected function getDataText($aPropertyKey, $aPropertyValue, $depth, $someProperties) {
    $text = "<table style='width:100%;". (($depth > 0)?"padding-left: 15px;":'') ."'><tr>";
    if($aPropertyValue['type'] == "recursive") {
      $text .= "<td style='border-top: 1px solid #000;border-left: 1px solid #000;'>";
      foreach($aPropertyValue['value'] AS $anElement) {
        foreach($anElement AS $key => $value) {
          $text .= $this->dataToText($key, $value, $depth + 1, $anElement);
        }
      }
    } else if($aPropertyValue['type'] == "object") {
      $text .= "<td><br/>";
      foreach($aPropertyValue['value'] AS $key => $value) {
        $text .= $this->dataToText($key, $value, $depth, $aPropertyValue['value']);
      }
      $text .= str_repeat("|&nbsp;&nbsp;", $depth);
    } else if($aPropertyKey == "Sentence") {
      $text .= "<td>";
      if($someProperties['SplitStatus']['value'] == "True") {
        $text .= "<b>";
      }
      $text .= $aPropertyValue['value'];
      if($someProperties['SplitStatus']['value'] == "True") {
        $text .= "</b>";
      }
      $text .= "<br/>";
    } else {
      return "";
    }
    $text .= "</td></tr></table>";
    return $text;
  }
  
}

?>