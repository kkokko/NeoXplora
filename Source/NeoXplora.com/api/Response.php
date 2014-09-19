<?php

namespace NeoX\API;

class TResponse {

  protected $properties = array(
  );
  
  public function &__get($key) {
    $return = null;
    if(isset($this->properties[$key])) {
      $return =& $this->properties[$key]["value"];
    }
    return $return;
  }
  
  public function setProperty($key, $name, $value = "", $type = "text") {
    if(!isset($this->properties[$name])) {
      $this->properties[$key] = array(
        "name" => $name,
        "value" => $value,
        "type" => $type
      );
    }
  }
  
  public function fromXML() {
    //
  }
  
  public function toXML() {
    $className = str_replace("NeoX\API\T", "Api", get_class($this));
    $xml = "<" . $className . ">";
    $xml .= "\r\n";
    foreach($this->properties AS $aPropertyKey => $aPropertyValue) {
      $xml .= $this->propertyToXML($aPropertyKey, $aPropertyValue, 1);
    }
    $xml .= "</" . $className . ">";
    return $xml;
  }
  
  private function propertyToXML($aPropertyKey, $aPropertyValue, $depth) {
    $xml = "";
    if($aPropertyValue['value'] !== null) {
      if(is_array($aPropertyValue['value']) && $aPropertyValue['type'] == "array") {
        foreach($aPropertyValue['value'] AS $aSubValue) {
          $xml .= $this->getPropertyXML($aPropertyKey, $aSubValue['value'], $aSubValue['type'], $depth);
        }
      } else {
        $xml .= $this->getPropertyXML($aPropertyKey, $aPropertyValue['value'], $aPropertyValue['type'], $depth);
      }
    }
    return $xml;
  }
  
  private function getPropertyXML($aPropertyKey, $aPropertyValue, $aPropertyType, $depth) {
    $xml = '';
    $xml .= str_repeat(" ", $depth * 2);
    $xml .= "<" . $aPropertyKey . ">";
    switch($aPropertyType) {
      case "text":
        $xml .= $aPropertyValue;
        break;
      case "bool":
        $xml .= ($aPropertyValue)?"True":"False";
        break;
      default:
        $xml .= $this->propertyToXML($aPropertyKey, $aPropertyValue, $depth + 1);
    }
    $xml .= "</" . $aPropertyKey . ">";
    $xml .= "\r\n";
    return $xml;
  }
    
  public function toHTML() {
    $html = '';
    foreach($this->properties AS $aPropertyName => $aPropertyValue) {
      $html .= $this->propertyToHTML($aPropertyName, $aPropertyValue);
    }
    return $html;
  }
  
  private function propertyToHTML($aPropertyKey, $aPropertyValue) {
    $html = "";
    if($aPropertyValue['value'] !== null) {
      if(!is_array($aPropertyValue['value'])) {
        $html .= $this->getPropertyHTML($aPropertyKey, $aPropertyValue['name'], $aPropertyValue['value'], $aPropertyValue['type']);
      } else if(is_array($aPropertyValue['value']) && $aPropertyValue['type'] == "array") {
        foreach($aPropertyValue['value'] AS $aSubValue) {
          $html .= $this->getPropertyHTML($aPropertyKey, $aPropertyValue['name'], $aSubValue['value'], $aSubValue['type']);
        }
      } else {
        foreach($aPropertyValue['value'] AS $aSubKey => $aSubValue) {
          $html .= $this->propertyToHTML($aSubKey, $aSubValue);
        }
      }
    }
    return $html;
  }

  private function getPropertyHTML($aPropertyKey, $aPropertyName, $aPropertyValue, $aPropertyType) {
    $html = '';
    switch($aPropertyType) {
      case "text":
        $html .= '<div class="field">
          <label>' . $aPropertyName . '</label>
          <div class="ui left labeled input">
            <input type="text" id="' . $aPropertyKey . '" readonly="readyonly" value="' . $aPropertyValue . '">
          </div>
        </div>';
        break;
      case "bool":
        $html .= '<div class="field">
              <div class="ui toggle checkbox">
                <input id="' . $aPropertyKey . '" type="checkbox" readonly="readyonly" ' . (($aPropertyValue == true)?' checked="checked"':'') . '>
                <label for="' . $aPropertyKey . '">' . $aPropertyName . '</label>
              </div>
            </div>';
        break;
    }
    return $html;
  }

}

?>