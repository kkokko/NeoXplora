<?php 
  namespace TApp;

  require_once $GLOBALS["SkyFrameworkPath"] . "Communication/ClientInterfaceJson.php";
     
  class TAppClientInterface extends \sky\TClientInterfaceJson{
    public function __construct() {
      $this->path = __DIR__ . "/../";
      $session_key = (isset($_COOKIE['session_key']))?htmlentities(trim($_COOKIE['session_key'])):"";
      parent::__construct("127.0.0.1", "2587", "Request.php", $session_key);
	   //parent::__construct("neoxplora.com", "80", "Request.php", $session_key);
    }
    
    public function ExecuteRequest($ARequest){
      try{
        return parent::ExecuteRequest($ARequest);
      } catch(\sky\TSkyThrowable $e) {
        throw $e;
      }
    }

    function GuessRepsForSentenceId($ASentenceId) {
      require_once $this->path . "Requests/RequestGuessRepsForSentenceId.php";
      return $this->ExecuteRequest(new TRequestGuessRepsForSentenceId($ASentenceId))->GetProperty("GuessObject");
    }

    function GuessRepsForStoryId($AStoryId) {
      require_once $this->path . "Requests/RequestGuessRepsForStoryId.php";
      return $this->ExecuteRequest(new TRequestGuessRepsForStoryId($AStoryId))->GetProperty("GuessObjects");
    }
    
    function PredictAll() {
      require_once $this->path . "Requests/RequestPredictAll.php";
      return $this->ExecuteRequest(new TRequestGuessRepsForStoryId($AStoryId));
    }
    
    function GetPosForPage($APage, $UseModifiedPos = false) {
      require_once $GLOBALS["SkyFrameworkPath"] . "Entity/EntityWithName.php";
      require_once $GLOBALS["SkyFrameworkPath"] . "Entity/EntityList.php";
      require_once $this->path . "Requests/RequestGetPosForPage.php";
      
      $TheRequest = new TRequestGetPosForPage();
      $TheRequest->SetProperty("Page", $APage);
      $TheRequest->SetProperty("UseModifiedPos", $UseModifiedPos);
      return $this->ExecuteRequest($TheRequest)->GetProperty("Sentences");
    }
    
    function GetPosForSentences($SomeSentences, $UseModifiedPos = false) {
      require_once $GLOBALS["SkyFrameworkPath"] . "Entity/EntityWithName.php";
      require_once $GLOBALS["SkyFrameworkPath"] . "Entity/EntityList.php";
      require_once $this->path . "Requests/RequestGetPosForSentences.php";
      
      $TheRequest = new TRequestGetPosForSentences();
      $i = 0;
      foreach($SomeSentences AS $ASentence) {
        $TheSentence = new \sky\TEntityWithName();
        $TheSentence->SetProperty("Id", $i++);
        $TheSentence->SetProperty("Name", $ASentence);
        $TheRequest->GetProperty("Sentences")->Add($TheSentence);
      }
      
      $TheRequest->SetProperty("UseModifiedPos", $UseModifiedPos);
      return $this->ExecuteRequest($TheRequest)->GetProperty("Sentences");
    }
	
	// align the functions by name when your done pls
	function Search($AString, $AnOffset){
      require_once $this->path . "Requests/RequestSearch.php";
      $TheRequest = new TRequestSearch();
	  $TheRequest->SetProperty("SearchString", $AString);
	  $TheRequest->SetProperty("Offset", $AnOffset);
      return $this->ExecuteRequest($TheRequest);
	}
    
    function GetFullSentencesForStoryId($AStoryID) {
      require_once $this->path . "Requests/RequestGetFullSentencesForStoryId.php";
      return $this->ExecuteRequest(new TRequestGetFullSentencesForStoryId($AStoryID))->GetProperty("Sentences");
    }

    function PredictAfterSplit($SomeSentenceIDs) {
      require_once $this->path . "Requests/RequestPredictAfterSplit.php";
      require_once $GLOBALS["SkyFrameworkPath"] . "Entity/EntityWithId.php";
      $TheRequest = new TRequestPredictAfterSplit();
      
      foreach($SomeSentenceIDs as $ASentenceID) {
        $TheSentence = new \sky\TEntityWithId();
        $TheSentence->SetProperty("Id", (int) $ASentenceID);
        $TheRequest->GetProperty("Sentences")->Add($TheSentence);
      }
      
      $this->ExecuteRequest($TheRequest);
    }
    
    function ValidateRep($ARep) {
      try {
        require_once $this->path . "Requests/RequestValidateRep.php";
        $this->ExecuteRequest(new TRequestValidateRep($ARep));
        return true;
      } catch(\Exception $e) {
        if($e->Exception->GetProperty("EntityClassName") == "EAppRepDecoderException") {
          return $e->Exception->GetProperty("Params")->GetProperty("StrIndex");
        } else {
          die("Invalid Requeust");
        }
      }
    }
    
    function GetLinkerDataForStoryId($AStoryID) {
      /*require_once $this->path . "Requests/RequestGetLinkerDataForStoryId.php";
      return $this->ExecuteRequest(new TRequestGetLinkerDataForStoryId($AStoryID));*/
      
      $TheResponse = '{"ClassName":"TResponseGetLinkerDataForStoryId","Properties":{"Entities":{"Values":[{"Id":"0","Object":{"ClassName":"TEntityRecord","Properties":{"Attributes":[{"ClassName":"TAttributeRecord","Properties":{"Key":"Name","Values":[{"ClassName":"TEntityWithName","Properties":{"Id":"0","Name":"Eric","Version":""}}]}},{"ClassName":"TAttributeRecord","Properties":{"Key":"Pronoun","Values":[{"ClassName":"TEntityWithName","Properties":{"Id":"0","Name":"I","Version":""}},{"ClassName":"TEntityWithName","Properties":{"Id":"0","Name":"my","Version":""}}]}}],"Type":"etPerson"}}},{"Id":"1","Object":{"ClassName":"TEntityRecord","Properties":{"Attributes":[{"ClassName":"TAttributeRecord","Properties":{"Key":"Members","Values":[{"ClassName":"TEntityWithName","Properties":{"Id":"2","Name":"","Version":""}},{"ClassName":"TEntityWithName","Properties":{"Id":"3","Name":"","Version":""}}]}},{"ClassName":"TAttributeRecord","Properties":{"Key":"ref","Values":[{"ClassName":"TEntityWithName","Properties":{"Id":"0","Name":"sisters","Version":""}},{"ClassName":"TEntityWithName","Properties":{"Id":"0","Name":"their","Version":""}}]}}],"Type":"etGroup"}}},{"Id":"2","Object":{"ClassName":"TEntityRecord","Properties":{"Attributes":[{"ClassName":"TAttributeRecord","Properties":{"Key":"Name","Values":[{"ClassName":"TEntityWithName","Properties":{"Id":"0","Name":"Rachel","Version":""}}]}}],"Type":"etPerson"}}},{"Id":"3","Object":{"ClassName":"TEntityRecord","Properties":{"Attributes":[{"ClassName":"TAttributeRecord","Properties":{"Key":"Name","Values":[{"ClassName":"TEntityWithName","Properties":{"Id":"0","Name":"Carrie","Version":""}}]}}],"Type":"etPerson"}}}],"ClassName":"TSkyIdList"},"Sentences":{"Values":[{"Id":"2124","Object":{"ClassName":"TSkyStringList","Values":[{"Key":"Hi"},{"Key":","},{"Key":" "},{"Key":"my","Object":{"ClassName":"TEntityWithId","Properties":{"Id":"0"}}},{"Key":" "},{"Key":"name"},{"Key":" "},{"Key":"is"},{"Key":" "},{"Key":"Eric","Object":{"ClassName":"TEntityWithId","Properties":{"Id":"0"}}}]}},{"Id":"2125","Object":{"ClassName":"TSkyStringList","Values":[{"Key":"I","Object":{"ClassName":"TEntityWithId","Properties":{"Id":"0"}}},{"Key":" "},{"Key":"am"},{"Key":" "},{"Key":"12"}]}},{"Id":"2126","Object":{"ClassName":"TSkyStringList","Values":[{"Key":"I","Object":{"ClassName":"TEntityWithId","Properties":{"Id":"0"}}},{"Key":" "},{"Key":"want"},{"Key":" "},{"Key":"to"},{"Key":" "},{"Key":"talk"},{"Key":" "},{"Key":"about"},{"Key":" "},{"Key":"my","Object":{"ClassName":"TEntityWithId","Properties":{"Id":"0"}}},{"Key":" "},{"Key":"family"}]}},{"Id":"2127","Object":{"ClassName":"TSkyStringList","Values":[{"Key":"I","Object":{"ClassName":"TEntityWithId","Properties":{"Id":"0"}}},{"Key":" "},{"Key":"have"},{"Key":" "},{"Key":"two"},{"Key":" "},{"Key":"sisters","Object":{"ClassName":"TEntityWithId","Properties":{"Id":"1"}}}]}},{"Id":"2128","Object":{"ClassName":"TSkyStringList","Values":[{"Key":"Their","Object":{"ClassName":"TEntityWithId","Properties":{"Id":"1"}}},{"Key":" "},{"Key":"names"},{"Key":" "},{"Key":"are"},{"Key":" "},{"Key":"Rachel","Object":{"ClassName":"TEntityWithId","Properties":{"Id":"2"}}},{"Key":" "},{"Key":"and"},{"Key":" "},{"Key":"Carrie","Object":{"ClassName":"TEntityWithId","Properties":{"Id":"3"}}}]}}],"ClassName":"TSkyIdList"}}}';
      $TheJsonObject = json_decode($TheResponse);
      
      require_once $GLOBALS["SkyFrameworkPath"] . "/Entity/Streaming/EntityStreamReader.php";
      return \sky\TEntityStreamReader::ReadEntity($TheJsonObject);
    }
    
  }
?>