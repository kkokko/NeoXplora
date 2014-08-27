<?php
namespace NeoX\Controller;
use NeoX\Model;
use NeoX\Entity;
use NeoX\Classes;

require_once __DIR__ . "/../panel.php";

class TPanelTests extends TPanel {

  // public $accessLevel = 'admin';

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

    $this->template->load("apixml", "panel/tests");
    $this->template->pageTitle = "Run Tests - API XML | Admin Panel";
    $this->template->page = "tests_panel";
    $this->template->requestxml = '<RequestGetPosForSentences><Sentences><EntityWithName><Id>0</Id><Name>At first he was running</Name></EntityWithName><EntityWithName><Id>1</Id><Name>The running hurt him</Name></EntityWithName><EntityWithName><Id>2</Id><Name>His legs hurt</Name></EntityWithName></Sentences></RequestGetPosForSentences>';
    
    if(isset($_POST['submit']) && isset($_POST['req']) && $_POST['req'] != "") {
      $requestxml = $_POST['req'];
      $result = simplexml_load_string($requestxml);
  
      $domxml = new \DOMDocument('1.0');
      $domxml->preserveWhiteSpace = false;
      $domxml->formatOutput = true;
      $domxml->loadXML($result->asXML());
      $this->template->requestxml = $domxml->saveXML();
      
      
      $TheServer=curl_init();
      curl_setopt($TheServer, CURLOPT_POST,1);
      curl_setopt($TheServer, CURLOPT_POSTFIELDS, $requestxml);
      curl_setopt($TheServer,CURLOPT_HTTPHEADER, 
        Array(
        "X-Forwarded-For: ".$_SERVER['REMOTE_ADDR'],
        "User-Agent: ".$_SERVER['HTTP_USER_AGENT']
      ));
      
      $TheURL = "http://neoxplora.com/api.xml.php";
      curl_setopt($TheServer, CURLOPT_URL, $TheURL);
      curl_setopt($TheServer, CURLOPT_CONNECTTIMEOUT, 5); 
      curl_setopt($TheServer, CURLOPT_TIMEOUT, 30); //timeout in seconds
      curl_setopt($TheServer, CURLOPT_HEADER, false);
      curl_setopt($TheServer, CURLOPT_RETURNTRANSFER, 1);
      $TheResponseOutput = curl_exec($TheServer);
      curl_close($TheServer);
      
      if($TheResponseOutput != FALSE) {
        $responsexml = $TheResponseOutput;
        $result = simplexml_load_string($responsexml);
    
        $domxml = new \DOMDocument('1.0');
        $domxml->preserveWhiteSpace = false;
        $domxml->formatOutput = true;
        $domxml->loadXML($result->asXML());
        $this->template->responsexml = $domxml->saveXML();
      }
    
    }

    $this->template->hide_right_box = true;
    $this->template->render();
  }

}
?>