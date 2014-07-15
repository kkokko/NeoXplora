<?php 
  namespace sky;
   
  class TClientInterface{
    public $ServerAddress; // ex. 127.0.0.1 or skyproject.ro
    public $ServerPort;
    public $RequestPage;
    public $SessionId;
    function __construct($AServerAddress, $AServerPort, $ARequestPage, $ASessionId){
      $this->ServerAddress = $AServerAddress;    
      $this->ServerPort = $AServerPort;
      $this->RequestPage = $ARequestPage;    
      $this->SessionId = $ASessionId;    
    }
    // $ARequest = Entity returns Entity
    function ExecuteRequest($ARequest){
      $TheContent = $this->WriteRequestToStream($ARequest);
      $TheServer=curl_init();
      curl_setopt($TheServer, CURLOPT_POST,1);
      curl_setopt($TheServer, CURLOPT_POSTFIELDS, $TheContent);

      $TheURL = "http://" . $this->ServerAddress . ":" . $this->ServerPort . "/" . $this->RequestPage;
      curl_setopt($TheServer, CURLOPT_URL, $TheURL);
      if($this->SessionId != ""){
        curl_setopt($TheServer, CURLOPT_COOKIE, "IDHTTPSESSIONID=" . $this->SessionId);
      }
      curl_setopt($TheServer, CURLOPT_CONNECTTIMEOUT, 5); 
      curl_setopt($TheServer, CURLOPT_TIMEOUT, 30); //timeout in seconds
      curl_setopt($TheServer, CURLOPT_HEADER, false);
      curl_setopt($TheServer, CURLOPT_RETURNTRANSFER, 1);
      $TheResponseOutput = curl_exec($TheServer);
      curl_close($TheServer);
      
      if($TheResponseOutput != FALSE) {
        $TheResult = $this->ReadRequestFromStream($TheResponseOutput);
        if($TheResult->GetShortClassName() == "TResponseServerException") {
          require_once $GLOBALS["SkyFrameworkPath"]."Errors/SkyException.php";
          throw new \sky\TSkyThrowable($TheResult->GetProperty("Exception"));
        } else {
          return $TheResult; 
        }
      } else {
        require_once $GLOBALS["SkyFrameworkPath"]."Errors/SkyException.php";
        require_once $GLOBALS["SkyFrameworkPath"]."Errors/SkyServerUnavailable.php";
        throw new \sky\TSkyThrowable(new ESkyServerUnavailable());
      }
    }

    function LoginCheck() {
      require_once $GLOBALS["SkyFrameworkPath"] . "Communication/Requests/ClientRequestLoginCheck.php";
      $result = $this->ExecuteRequest(new TClientRequestLoginCheck());
      return (object)array(
        'SessionId' => $result->GetProperty("SessionId"), 
        'User' => $result->GetProperty("User")
      );
    }
    
    function Login($AUsername, $APassword) {
      require_once $GLOBALS["SkyFrameworkPath"] . "Communication/Requests/ClientRequestLogin.php";
      require_once $GLOBALS["SkyFrameworkPath"] . "Entity/BasicUserData.php";
      $result = $this->ExecuteRequest(new TClientRequestLogin(new TBasicUserData($AUsername, $APassword)));
      return (object)array(
        'SessionId' => $result->GetProperty("SessionId"), 
        'User' => $result->GetProperty("User")
      );
    }
    
    function Logout() {
      require_once $GLOBALS["SkyFrameworkPath"] . "Communication/Requests/ClientRequestLogout.php";
      $result = $this->ExecuteRequest(new TClientRequestLogout());
    }

  }
?>