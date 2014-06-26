<?php
  namespace TApp;
  class TCommandGetImportInfo {
    static public function Execute($ARequest){
      $workingDir = str_replace('\\', '/', __DIR__)."/../";
      require_once $workingDir . "Database/dbmysql.php";
      
      if(!checkuser($ARequest->GetProperty('UserName'), $ARequest->GetProperty('UserPassword'))) {      
        require_once "Errors/SkyInvalidUser.php";
        throw new \sky\TSkyThrowable(new ESkyInvalidUser(NULL, "TCommandGetImportInfo.Execute"));
      }
      
      connect_db();

      // check DB
      $theTotalCount = 0;
      $theInProgress = 0;  
      $theNew = 0;  
      $responseQuery = 'SELECT count(pageID) TotalCount, sum(if(is_finished = 0, 1, 0)) InProgress FROM `page`';
      $result = mysql_query($responseQuery);
      if($result) {
        $row = mysql_fetch_array($result);
        $theTotalCount = $row['TotalCount'];
        $theInProgress = $row['InProgress'];
      }
      if($ARequest->GetProperty('ImportIds') == ''){
        $theNew = $theTotalCount - $theInProgress;
      } else {
        $responseQuery =  'SELECT count(pageID) TotalCount, sum(if(is_finished = 0, 1, 0)) InProgress FROM `page` WHERE can_overwrite = 1 or not pageID in ('.$ARequest->GetProperty('ImportIds').')';
        $result = mysql_query($responseQuery);
        if($result) {
          $row = mysql_fetch_array($result);
          $theNew = $row['TotalCount'] - $row['InProgress']; 
        }
      }      

      require_once $workingDir . "Entity/ResponseGetImportInfo.php";
      $TheResponse = new TResponseGetImportInfo();

      // ids to import from web to desktop
      if($ARequest->GetProperty('SendIdList') == true){
        if($ARequest->GetProperty('ImportIds') != ''){
          $responseQuery =  'SELECT pageID FROM `page` WHERE is_finished = 1 and ( can_overwrite = 1 or not pageID in ('.$ARequest->GetProperty('ImportIds').') )';
        }else{
          $responseQuery =  'SELECT pageID FROM `page` WHERE is_finished = 1';
        }
        $result = mysql_query($responseQuery);
        if($result) {
          while ($storyRow = mysql_fetch_array($result)) {
            //Story OBJECT
            require_once $workingDir . "Entity/StoryIdObject.php";
            $TheStoryId = new TStoryIdObject();
            $TheStoryId->SetProperty('MySQLId', (int) $storyRow['pageID']);
            //add story id
            $TheResponse->GetProperty('IdList')->Add($TheStoryId);
          }
        }
      }     
      
      $TheResponse->SetProperty("StoryTotal", (int) $theTotalCount);
      $TheResponse->SetProperty("StoryInProgress", (int) $theInProgress);
      $TheResponse->SetProperty("StoryNew", (int) $theNew);
      
      return $TheResponse;
    }
  }
?>