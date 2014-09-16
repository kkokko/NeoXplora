<?php
namespace NeoX\Controller;
use NeoX\Model;
use NeoX\Entity;
use NeoX\Classes;

require_once __DIR__ . "/../panel.php";

class TPanelTests extends TPanel {

  // public $accessLevel = 'admin';

  private $formatted_xml = "";
  
  public function index() {
    $this->template->addStyle("style/admin.css");
    $this->template->addStyle("style/admin.pages.css");
    $this->template->addStyle("style/train/linker.css");

    $this->template->load("index", "panel/tests");
    $this->template->pageTitle = "Run Tests | Admin Panel";
    $this->template->page = "tests_panel";

    $this->template->hide_right_box = true;
    $this->template->render();
  }
  
  public function apixml() {
    $this->template->addStyle("style/admin.css");
    $this->template->addStyle("style/admin.pages.css");
    $this->template->addStyle("style/train/linker.css");
    $this->template->addStyle("style/jquery-ui.theme.css");
    $this->template->addStyle("style/jquery-ui.css");

    $this->template->addScripts(array(
      "js/system/object.js"
    ));
    $this->template->addJSModules(array(
      "NeoX.Modules.APIXMLIndex" => "js/module/apixml/index.js",
      "NeoX.Modules.APIXMLRequests" => "js/module/apixml//requests.js"
    ));

    $this->template->load("apixml", "panel/tests");
    $this->template->pageTitle = "Run Tests - API XML | Admin Panel";
    $this->template->page = "apixml_tests_panel";
    $this->template->requestxml = '';
    
    if(isset($_POST['submit']) && isset($_POST['req']) && $_POST['req'] != "") {
      $requestxml = $_POST['req'];
      $result = simplexml_load_string($requestxml);

      $this->template->requestxml = $this->formatXML($result);
      
      $responsexml = $this->postRequest($this->template->site_url . "api.xml.php", $requestxml);
      
      if($responsexml != false) {
        $result = simplexml_load_string($responsexml);

        $this->template->responsexml = $this->formatXML($result);
      }
    }

    $this->template->hide_right_box = true;
    $this->template->render();
  }
  
  public function apixml_formatted() {
    $this->template->load("apixml_formatted", "panel/tests");
    $this->template->requestxml = '';
    
    if(isset($_POST['submit']) && isset($_POST['req']) && $_POST['req'] != "") {
      $request = $_POST['req'];
      
      $requestxml = '';
      
      switch($request) {
        case "generateRep":
          $requestxml = '<ApiRequestGenerateRep><ApiKey>abc</ApiKey><SentenceText>My name is Mimi</SentenceText></ApiRequestGenerateRep>';
          break;
        case "generateRep2":
          $requestxml = '<ApiRequestGenerateRep><ApiKey>abc</ApiKey><SentenceText>My name is Mimi</SentenceText><OutputSentence>True</OutputSentence></ApiRequestGenerateRep>';
          break; 
        case "guessProto":
          $requestxml = ' <ApiRequestGenerateProtoGuess><ApiKey>abc</ApiKey><SentenceText>The car gives the planet gas and heat</SentenceText></ApiRequestGenerateProtoGuess>';
          break; 
      }
      
      /*$result = simplexml_load_string($requestxml);
      $this->template->requestxml = $this->formatXML($result);*/
      
      $responsexml = $this->postRequest($this->template->site_url . "api.xml.php", $requestxml);
      
      if($responsexml != false) {
        $result = simplexml_load_string($responsexml);
        
        $this->apixml_format($result);
        
        $this->template->responsexml = $this->formatted_xml;
      }
    }

    $this->template->hide_right_box = true;
    $this->template->render();
  }

  public function apixml_xml() {
    $this->template->load("apixml_xml", "panel/tests");
    $this->template->requestxml = '';
    
    if(isset($_POST['submit']) && isset($_POST['req']) && $_POST['req'] != "") {
      libxml_use_internal_errors(true);
      
      $request = $_POST['req'];
      
      $requestxml = $request;
      
      $result = simplexml_load_string($requestxml);
      
      if(!$result) {
        $this->template->requestxml = $requestxml;
      } else {    
        $this->template->requestxml = $this->formatXML($result);
      }
      
      $responsexml = $this->postRequest($this->template->site_url . "api.xml.php", $requestxml);
      
      if($responsexml != false) {
        $result = simplexml_load_string($responsexml);
        
        if(strstr($responsexml, "<HTML>") != false || !$result) {
          $this->template->responsexml = "Invalid API Request";
        } else {
          $this->template->responsexml = $this->formatXML($result);
        }
      }
    }

    $this->template->hide_right_box = true;
    $this->template->render();
  }

  private function apixml_format($currentXML) {
    if($currentXML->count()) {
      foreach($currentXML->children() AS $child) {
        $this->apixml_format($child);
      }
    } else {
      $this->formatted_xml .= "<br/><b>" . $currentXML->getName() . "</b>: " . (string) $currentXML;
    }
  }
  
  public function apijson() {
    $this->template->addStyle("style/admin.css");
    $this->template->addStyle("style/admin.pages.css");
    $this->template->addStyle("style/train/linker.css");

    $this->template->load("apijson", "panel/tests");
    $this->template->pageTitle = "Run Tests - API JSON | Admin Panel";
    $this->template->page = "apijson_tests_panel";
    $this->template->requestjson = '';
    
    if(isset($_POST['submit']) && isset($_POST['req']) && $_POST['req'] != "") {
      $requestjson = $_POST['req'];

      $this->template->requestjson = $this->formatJSON($requestjson);
      
      $responsejson = $this->postRequest($this->template->site_url . "api.json.php", $requestjson);
      
      if($responsejson != false) {
        $this->template->responsejson = $this->formatJSON($responsejson);
      }
    }

    $this->template->hide_right_box = true;
    $this->template->render();
  }

  private function postRequest($url, $request) {
    $TheServer=curl_init();
    curl_setopt($TheServer, CURLOPT_POST,1);
    curl_setopt($TheServer, CURLOPT_POSTFIELDS, $request);
    curl_setopt($TheServer,CURLOPT_HTTPHEADER, 
      Array(
      "X-Forwarded-For: ".$_SERVER['REMOTE_ADDR'],
      "User-Agent: ".$_SERVER['HTTP_USER_AGENT']
    ));
    
    $TheURL = "http://neoxplora.com/api.xml.php";
    curl_setopt($TheServer, CURLOPT_URL, $url);
    curl_setopt($TheServer, CURLOPT_CONNECTTIMEOUT, 5); 
    curl_setopt($TheServer, CURLOPT_TIMEOUT, 30); //timeout in seconds
    curl_setopt($TheServer, CURLOPT_HEADER, false);
    curl_setopt($TheServer, CURLOPT_RETURNTRANSFER, 1);
    $TheResponseOutput = curl_exec($TheServer);
    curl_close($TheServer);
    
    return $TheResponseOutput;
  }
  
  private function formatJSON($json) {
    $dec = json_decode($json);
    return json_encode($dec, JSON_PRETTY_PRINT);
  }
  
  private function formatXML($xml) {
    $domxml = new \DOMDocument('1.0');
    $domxml->preserveWhiteSpace = false;
    $domxml->formatOutput = true;
    $domxml->loadXML($xml->asXML());
    return $domxml->saveXML();
  }

}
?>