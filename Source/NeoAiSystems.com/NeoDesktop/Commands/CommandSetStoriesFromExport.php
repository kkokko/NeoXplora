<?php
  namespace TApp;
  class TCommandSetStoriesFromExport  {
    static public function Execute($ARequest){
      $workingDir = str_replace('\\', '/', __DIR__)."/../";
      require_once $workingDir . "Database/dbmysql.php";
      require_once $workingDir . "Entity/StoryIdObject.php";
      require_once $workingDir . "Entity/ResponseSetStoriesFromExport.php";
      
      if(!checkuser($ARequest->GetProperty('UserName'), $ARequest->GetProperty('UserPassword'))) {      
        require_once "Errors/SkyInvalidUser.php";
        throw new \sky\TSkyThrowable(new ESkyInvalidUser(NULL, "TCommandGetImportInfo.Execute"));
      }
      
      connect_db();

      $TheResponse = new TResponseSetStoriesFromExport();
      // load data from DB

      $addedCount = 0;
      $updatedCount = 0;
      $TheStories = $ARequest->GetProperty("Stories");
      for($i = 0; $i < $TheStories->Count(); $i++) {
        $TheStory = $TheStories->Item($i);
        
        $mysqlId = -1;
        $query = mysql_query("SELECT `pageID` FROM `page` WHERE `title` = '" . mysql_real_escape_string($TheStory->GetProperty('Title')) . "'");
        //If a story with the same title has been found in the DB we update it, else we insert it
        if(mysql_num_rows($query)) {
          $result = mysql_fetch_array($query);
          $mysqlId = $result['pageID'];
          mysql_query("UPDATE `page` SET `body` = '" . mysql_real_escape_string($TheStory->GetProperty('Body')) . "', `categoryID` = '" . $TheStory->GetProperty('CategoryId') . "', `user` = '" . mysql_real_escape_string($TheStory->GetProperty('User')) . "' WHERE `pageID` = '" . $mysqlId . "'");

          mysql_query("DELETE FROM `sentence` WHERE `pageID` = '" . $mysqlId . "'");
          mysql_query("DELETE FROM `proto` WHERE `pageID` = '" . $mysqlId . "'");
          $updatedCount++;
        } else {
          //Insert the story into the DB and save its ID
          mysql_query("INSERT INTO `page` (`title`, `body`, `categoryID`, `user`) 
                        VALUES ('" . mysql_real_escape_string($TheStory->GetProperty('Title')) . "', '" . mysql_real_escape_string($TheStory->GetProperty('Body')) . "', 
                        '" . $TheStory->GetProperty('CategoryId') . "', '" . mysql_real_escape_string($TheStory->GetProperty('User')) . "')"); 
          $mysqlId = mysql_insert_id();
          $addedCount++;
        }
          
        $TheSentences = $TheStory->GetProperty('Sentences');
        $TheProtos = $TheStory->GetProperty('Protos');
          
        //parse all the sentences
        for($j = 0; $j < $TheSentences->Count(); $j++) {
          $TheSentence = $TheSentences->Item($j);
          
          $TheProto1Object = $TheProtos->GetEntityById($TheSentence->GetProperty('Proto1Id'));
          $TheProto2Object = $TheProtos->GetEntityById($TheSentence->GetProperty('Proto2Id'));
          
          $TheSentence->Proto1Object = $TheProto1Object; 
          $TheSentence->Proto2Object = $TheProto2Object; 
        }
        
        for($j = 0; $j < $TheProtos->Count(); $j++) {
          $TheProto = $TheProtos->Item($j);
          mysql_query("INSERT INTO `proto` (`name`, `level`, `pageID`) 
                        VALUES ('" . mysql_real_escape_string($TheProto->GetProperty('Name')) . "', 
                        '" . mysql_real_escape_string($TheProto->GetProperty('Level')) . "', '" . $mysqlId . "')");
          $TheProto->SetProperty("Id", mysql_insert_id());
        }
                
        for($j = 0; $j < $TheSentences->Count(); $j++) {
          $TheSentence = $TheSentences->Item($j);
          mysql_query(
            "INSERT INTO `sentence` (`sentence`, `pr1ID`, `pr2ID`, `representation`, `context_rep`, `POS`, `semantic_rep`, `pageID`) 
             VALUES ('" . mysql_real_escape_string($TheSentence->GetProperty('Name')) . "', '" . $TheSentence->Proto1Object->GetProperty('Id') . "',
            '" . $TheSentence->Proto2Object->GetProperty('Id') . "', '" . mysql_real_escape_string($TheSentence->GetProperty('Representation')) . "', 
            '" . mysql_real_escape_string($TheSentence->GetProperty('ContextRep')) . "', '" . mysql_real_escape_string($TheSentence->GetProperty('POS')) . "', 
            '" . mysql_real_escape_string($TheSentence->GetProperty('SemanticRep')) . "', '" . $mysqlId . "')"
          );
        }
          
        mysql_query("DELETE FROM `qa` WHERE `pageID` = '" . $mysqlId . "'");
        $TheQAs = $TheStory->GetProperty('Qas');
        for($j = 0; $j < $TheQAs->Count(); $j++) {
          $TheQA = $TheQAs->Item($j);
          mysql_query("INSERT INTO `qa` (`question`, `answer`, `qarule`, `pageID`) 
                        VALUES ('" . mysql_real_escape_string($TheQA->GetProperty('Question')) . "', '" . mysql_real_escape_string($TheQA->GetProperty('Answer')) . "', 
                        '" . mysql_real_escape_string($TheQA->GetProperty('QARule')) . "', '" . $mysqlId . "')");
        }

        $TheStoryId = new TStoryIdObject();
        $TheStoryId->SetProperty('SQLiteId', (int) $TheStory->GetProperty('Id'));
        $TheStoryId->SetProperty('MySQLId', (int) $mysqlId);
        
        //add story
        $TheResponse->GetProperty('StoryIds')->Add($TheStoryId);    
      }

      $TheResponse->SetProperty('AddedCount', $addedCount);
      $TheResponse->SetProperty('UpdatedCount', $updatedCount);
      
      return $TheResponse;
    }
  }
?>
