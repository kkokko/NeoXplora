<?php

namespace NeoX\API;

class TRequest {

  protected $properties = array(
  );
  
  public function __construct($data = null) {
    $this->properties['ApiKey'] = array(
      "name" => "ApiKey",
      "value" => "",
      "type" => "text"
    );
    
    foreach($this->properties as $key => $property) {
      $this->properties[$key]['value'] = $this->getCheckedValueFromSource($key, $data);
    }
  }
  
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
  
  protected function getCheckedValueFromSource($key, $source) {
    if(!isset($this->properties[$key]['type'])) {
      return $this->getValueFromSource($key, $source);
    }
    
    switch($this->properties[$key]['type']) {
      case 'text':
        return ($this->getValueFromSource($key, $source) !== false)?$this->getValueFromSource($key, $source):$this->properties[$key]['value'];
        break;
      case 'numeric':
        return (is_numeric($this->getValueFromSource($key, $source)))?$this->getValueFromSource($key, $source):$this->properties[$key]['value'];
        break;
      case 'int':
        return (is_int($this->getValueFromSource($key, $source)))?$this->getValueFromSource($key, $source):$this->properties[$key]['value'];
        break;
      case 'bool':
        return $this->getValueFromSource;
        break;
      default:
        
    }
  }
  
  protected function getValueFromSource($key, $source) {
    if(is_array($source) && isset($source[$key])) {
      return $source[$key];
    } else if($source !== null && !is_array($source)) {
      return $source;
    } else {
      return false;
    }
  }
  
  public function fromXML() {
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
      case "numeric":
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
      if($aPropertyName != "ApiKey") {
        $html .= $this->propertyToHTML($aPropertyName, $aPropertyValue);
      }
    }
    return $html;
  }
  
  private function propertyToHTML($aPropertyKey, $aPropertyValue) {
    $html = "";
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
    return $html;
  }

  private function getPropertyHTML($aPropertyKey, $aPropertyName, $aPropertyValue, $aPropertyType) {
    $html = '';
    switch($aPropertyType) {
      case "text":
      case "numeric":
        $html .= '<div class="field">
          <label>' . $aPropertyName . '</label>
          <div class="ui left labeled input">
            <input type="text" name="' . $aPropertyKey . '" id="' . $aPropertyKey . '" value="' . $aPropertyValue . '">
          </div>
        </div>';
        break;
      case "bool":
        $html .= '<div class="field">
              <div class="ui toggle checkbox">
                <input id="' . $aPropertyKey . '" name="' . $aPropertyKey . '" type="checkbox" ' . (($aPropertyValue == true)?' checked="checked"':'') . '>
                <label for="' . $aPropertyKey . '">' . $aPropertyName . '</label>
              </div>
            </div>';
        break;
    }
    return $html;
  }

}

?>