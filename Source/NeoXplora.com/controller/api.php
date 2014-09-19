<?php
namespace NeoX\Controller;

require_once APP_DIR . "/app/system/Object.php";
class TApi extends \SkyCore\TObject {

  public function index() {
    if(!isset($_GET['name'])) {
      $this->template->redirect = "api.php?name=GenerateRep";
      $this->template->render();
      return;
    }
    
    $name = $_GET['name'];
    
    if(!file_exists(__DIR__ . "/../api/Response" . $name . ".php") || !file_exists(__DIR__ . "/../api/Request" . $name . ".php")) {
      die("No such API Request !");
    }
    
    require_once __DIR__ . "/../api/Request" . $name . ".php";
    require_once __DIR__ . "/../api/Response" . $name . ".php";
    
    $requestClassName = "NeoX\\API\\TRequest" . $name;
    $responseClassName = "NeoX\\API\\TResponse" . $name;
    
    if(!class_exists($requestClassName) || !class_exists($responseClassName)) {
      die("No such API Request !");
    }
    
    $requestData = null;
    $responseData = null;
    $apiKey = "abc";
    
    if(isset($_POST['ApiKey'])) {
      $requestData = $_POST;
      $apiKey = $_POST['ApiKey'];
    }
    
    $request = new $requestClassName($requestData);
    $requestxml = '';
     
    if(isset($_POST['ApiKey'])) {
      $requestxml = $request->toXML();
    }
    
    if($requestData) {
      $responsexml = $this->postRequest($this->template->site_url . "api.xml.php", $requestxml);
        
      if($responsexml != false) {
        $responseData = simplexml_load_string($responsexml);
        $response = new $responseClassName($responseData);
        $this->template->responseHTML = $response->toHTML();
        $this->template->responseXML = $response->toXML();
      }
    }
    
    $this->template->requestName = $name;
    $this->template->ApiKey = $apiKey;
    $this->template->requestHTML = $request->toHTML();
    $this->template->requestXML = $requestxml;
    
    $this->template->load("index", "apixml");
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
  
}
?>