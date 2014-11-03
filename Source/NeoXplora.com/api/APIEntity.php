<?php

namespace NeoX\API;

class TAPIEntity {

  protected $readonly = false;
  protected $displayAs = "html";
  protected $properties = array(
  );
  
  public function __construct($data = null) {
    $this->readValuesIntoProperties($this->properties, $data);
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
        "default" => $value,
        "value" => $value,
        "type" => $type
      );
    }
  }
  
  protected function readValuesIntoProperties(&$properties, $data) {
    foreach($properties as $key => $property) {
      $properties[$key]['value'] = $this->getCheckedValueFromSource($key, $data, $properties);
    }
  }
  
  protected function cloneProperties($properties) {
    $propertyList = array();
    foreach($properties AS $key => $property) {
      unset($property["value"]);
      $propertyList[$key] = $property; 
    }
    return $propertyList;
  }
  
  protected function getCheckedValueFromSource($key, $source, $properties = null) {
    if($properties === null) {
      $properties = $this->properties;
    }
    
    $theValue = $this->getValueFromSource($key, $source, $properties[$key]['default']);
    
    if(!isset($properties[$key]['type'])) {
      return $theValue;
    }
    
    switch($properties[$key]['type']) {
      case 'text':
        return $theValue;
        break;
      case 'numeric':
        return (is_numeric((string) $theValue) || $theValue === null)?$theValue:$properties[$key]['default'];
        break;
      case 'int':
        return ($this->checkInt((string) $theValue) || $theValue === null)?$theValue:$properties[$key]['default'];
        break;
      case 'bool':
        return ($theValue !== null)?(($theValue !== false && strtolower($theValue) != "false"?'True':'False')):null;
        break;
      case 'recursive':
        if($theValue !== null && $theValue !== false) {
          $theList = $this->getValueFromSource($properties[$key]["wrapper"], $theValue);
          if($theList !== null && $theList !== false) {
            $result = array();
            foreach($theList AS $anElement) {
              $theObject = $this->cloneProperties($properties);
              $this->readValuesIntoProperties($theObject, $anElement);
              $result[] = $theObject;
            }
            return $result;
          }
        }
        return null;
      case 'object':
        
        if($theValue !== null && $theValue !== false) {
          $theObject = $this->cloneProperties($properties[$key]['template']);
          $this->readValuesIntoProperties($theObject, $theValue);
          return $theObject;
        }
        return null;
        break;
      default:
        
    }
  }

  protected function getValueFromSource($key, $source, $default = null) {
    if($source === null) {
      return $default;
    }
    if(is_object($source)) {
      if(($source->$key->count() > 0 && $source->$key->children()->count() > 0) || ((string) $source->$key) !== '') {
        return $source->$key;
      } else {
        return null;
      }
    } else if(is_array($source) && array_key_exists($key, $source)) {
      return $source[$key];
    } else if(!is_array($source)) {
      return $source;
    } else {
      return false;
    }
  }
  
  public function fromXML() {
  }
  
  public function toXML() {
    $className = str_replace("NeoX\API\T", "Api", get_class($this));
    return $this->propertiesToXML($this->properties, $className, 0);
  }
  
  private function propertiesToXML($someProperties, $aWrapper, $aDepth) {
    $xml = "\r\n";
    if($aWrapper !== '') {
      $xml .= str_repeat(" ", $aDepth * 2);
      $xml .= "<" . $aWrapper . ">";
      $xml .= "\r\n";
    }
    foreach($someProperties AS $aPropertyKey => $aPropertyValue) {
      $xml .= $this->propertyToXML($aPropertyKey, $aPropertyValue, $aDepth + 1);
    }
    if($aWrapper !== '') {
      $xml .= str_repeat(" ", $aDepth * 2);
      $xml .= "</" . $aWrapper . ">";
    }
    return $xml;
  }
  
  private function propertyToXML($aPropertyKey, $aPropertyValue, $depth) {
    if($aPropertyValue['value'] === null || (is_array($aPropertyValue['value']) && count($aPropertyValue['value']) == 0)){
      return '';
    }
    return $this->getPropertyXML($aPropertyKey, $aPropertyValue, $depth);
  }
  
  private function getPropertyXML($aPropertyKey, $aPropertyValue, $depth) {
    $xml = '';
    $xml .= str_repeat(" ", $depth * 2);
    $xml .= "<" . $aPropertyKey . ">";
    switch($aPropertyValue['type']) {
      case "text":
      case "numeric":
      case "int":
        $xml .= $aPropertyValue['value'];
        break;
      case "bool":
        $xml .= (strtolower($aPropertyValue['value']) == "true")?"True":"False";
        break;
      case "recursive":
        foreach($aPropertyValue['value'] AS $anElement) {
          $xml .= $this->propertiesToXML($anElement, $aPropertyValue['wrapper'], $depth + 1);
        }
        $xml .= "\r\n";
        $xml .= str_repeat(" ", $depth * 2);
        break;
      case "object":
        $xml .= $this->propertiesToXML($aPropertyValue['value'], '', $depth);
        $xml .= str_repeat(" ", $depth * 2);
        break;
      default:
        $xml .= $this->propertyToXML($aPropertyKey, $aPropertyValue['value'], $depth + 1);
    }
    $xml .= "</" . $aPropertyKey . ">";
    $xml .= "\r\n";
    return $xml;
  }

  public function display() {
    if($this->displayAs == "html") {
      return $this->toHTML();
    } else {
      
      return $this->toText();
    }
  }

  public function toText() {
    $html = '<div class="ui tabular filter menu top attached">
               <a class="active item">Response</a>
             </div>
             <div class="ui segment stacked selection bottom attached">
               <div class="ui form" style="font-size:20px;">';
    $html .= $this->propertiesToText($this->properties, '', -1);
    $html .= "</div>
              </div>";
    return $html;
  }
  
  protected function propertiesToText($someProperties, $aWrapper, $aDepth) {
    $text = "<table style='width:100%;". (($aDepth > 0)?"border-bottom: 1px solid #aaa; border-left: 1px solid #000; padding-left: 15px;":'') ."'><tr><td>";
    foreach($someProperties AS $aPropertyKey => $aPropertyValue) {
      $text .= $this->propertyToText($aPropertyKey, $aPropertyValue, $aDepth + 1);
    }
    $text .= "</td></tr></table>";
    return $text;
  }

  protected function propertyToText($aPropertyKey, $aPropertyValue, $depth) {
    if($aPropertyValue['value'] === null || (is_array($aPropertyValue['value']) && count($aPropertyValue['value']) == 0)){
      return '';
    }
    return $this->getPropertyText($aPropertyKey, $aPropertyValue, $depth);
  }
  
  protected function getPropertyText($aPropertyKey, $aPropertyValue, $depth) {
    $text = "<table style='width:100%;". (($depth > 0)?"border-left: 1px solid #000; padding-left: 15px;":'') ."'><tr><td  style='border-bottom: 1px solid #ccc';>";
    if($aPropertyValue['type'] != "object") {
      $text .= $aPropertyKey . ": ";
    }
    switch($aPropertyValue['type']) {
      case "text":
      case "numeric":
      case "int":
        $text .= $aPropertyValue['value'];
        break;
      case "bool":
        $text .= (strtolower($aPropertyValue['value']) == "true")?"True":"False";
        break;
      case "recursive":
        foreach($aPropertyValue['value'] AS $anElement) {
          $text .= $this->propertiesToText($anElement, $aPropertyValue['wrapper'], $depth + 1);
        }
        break;
      case "object":
        $text .= $this->propertiesToText($aPropertyValue['value'], '', $depth);
        $text .= str_repeat("&nbsp;", $depth * 2);
        break;
      default:
        $text .= $this->propertyToText($aPropertyKey, $aPropertyValue['value'], $depth + 1);
    }
    $text .= "</td></tr></table>";
    return $text;
  }

  public function toHTML() {
    $className = str_replace("NeoX\API\T", "", get_class($this));
    if(stripos($className, "request") === 0) {
      $wrapper = "Request";
      $showExecute = true;
    } else {
      $wrapper = "Response";
      $showExecute = false;
    }
    return $this->propertiesToHTML($this->properties, $wrapper, $showExecute);
  }
  
  public function propertiesToHTML($someProperties, $aWrapper, $showExecute = false) {
    $html = '<div class="ui tabular filter menu top attached">
                <a class="active item">' . $aWrapper . '</a>';
    if($showExecute) {          
      $html .= '<div class="item right fitted" style="padding-top: 10px;">
            <div class="ui label floating right" style="top: 6px; left: -55px; width: 85px; background-color: transparent; color: #666;">
              API Key:
            </div>
            <div class="ui right input">
              <input type="text" id="ApiKey" value="' . $this->properties['ApiKey']['value'] . '">
            </div>
          </div>';
    }
    $html .= '</div>
              <div class="ui segment stacked selection bottom attached">
              <div class="ui form">';
    if($showExecute) {
      $html .= '<form action="api.php?name=' . str_replace("NeoX\API\TRequest", "", get_class($this)) . '" method="post" id="requestForm">';
    }
    foreach($someProperties AS $aPropertyName => $aPropertyValue) {
      if($aPropertyName != "ApiKey") {
        $html .= $this->propertyToHTML($aPropertyName, $aPropertyValue);
      }
    }
    if($showExecute) {
      $html .= '<input type="hidden" name="ApiKey" />
        <div class="ui purple submit button" id="executeRequest">Execute</div>
        </form>';
    }
    $html .= "</div>";
    $html .= "</div><br/>";
    return $html;
  }
  
  private function propertyToHTML($aPropertyKey, $aPropertyValue) {
    if($aPropertyValue['value'] === null) {
      return "";
    }
    $html = "";
    
    switch($aPropertyValue['type']) {
      case 'object':
        $html .= $this->propertiesToHTML($aPropertyValue['value'], $aPropertyValue['name']);
        break;
      case 'recursive':
        foreach($aPropertyValue['value'] AS $aPropertyList) {
          $html .= $this->propertiesToHTML($aPropertyList, $aPropertyValue["friendly_wrapper"]);
        }
        break;
      default:
        $html .= $this->getPropertyHTML($aPropertyKey, $aPropertyValue['name'], $aPropertyValue['value'], $aPropertyValue['type']);
    }
    
    return $html;
  }

  private function getPropertyHTML($aPropertyKey, $aPropertyName, $aPropertyValue, $aPropertyType) {
    $html = '';
    switch($aPropertyType) {
      case "text":
      case "numeric":
      case "int":
        $html .= '<div class="field">
          <label>' . $aPropertyName . '</label>
          <div class="ui left labeled input">
            <input type="text" ' . ((!$this->readonly)?'name="' . $aPropertyKey . '"':' readonly') . ' id="' . $aPropertyKey . '" value="' . $aPropertyValue . '">
          </div>
        </div>';
        break;
      case "bool":
        $html .= '<div class="field">
              <div class="ui toggle checkbox">
                <input ' . ((!$this->readonly)?'name="' . $aPropertyKey . '"':' readonly') . ' id="' . $aPropertyKey . '" type="checkbox" ' . (($aPropertyValue === true || strtolower($aPropertyValue) == "true")?' checked="checked"':'') . '>
                <label for="' . $aPropertyKey . '">' . $aPropertyName . '</label>
              </div>
            </div>';
        break;
    }
    return $html;
  }
  
  protected function checkInt($aValue) {
    if(is_array($aValue) || is_object($aValue)) return false;
    return ((string) $aValue == ((string) intval($aValue)));
  }

}

?>