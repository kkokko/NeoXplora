<?php
  namespace TApp;
  class TCommandGetStoriesForImport  {
    static public function Execute($ARequest){
      $workingDir = str_replace('\\', '/', __DIR__)."/../";
      require_once $workingDir . "Database/dbmysql.php";
      require_once $workingDir . "Entity/StoryObject.php";
      require_once $workingDir . "Entity/ProtoObject.php";
      require_once $workingDir . "Entity/SentenceObject.php";
      require_once $workingDir . "Entity/QAObject.php";
      require_once $workingDir . "Entity/ResponseGetStoriesForImport.php";
      
      if(!checkuser($ARequest->GetProperty('UserName'), $ARequest->GetProperty('UserPassword'))) {      
        require_once "Errors/SkyInvalidUser.php";
        throw new \sky\TSkyThrowable(new ESkyInvalidUser(NULL, "TCommandGetImportInfo.Execute"));
      }
      
      connect_db();

      $storyIds = ' ';
      $idsQuery = '  select group_concat(pageID) Ids from `page`  where is_finished = 1 ';
      if($ARequest->GetProperty('IgnoredIds') != ''){
        $idsQuery .= ' and ( can_overwrite = 1 or pageID not in ('.$ARequest->GetProperty('IgnoredIds').') )';
      }else{
        if($ARequest->GetProperty('SpecifiedIds') != ''){
          $idsQuery .= ' and ( pageID in ('.$ARequest->GetProperty('SpecifiedIds').') )';
        }else{
          $idsQuery .= ' and ( can_overwrite = 1 )';
        }
      }
      $idsQuery .=' order by `pageID`';     
      $idsResult = mysql_query($idsQuery);
      $idRow = mysql_fetch_array($idsResult);    
      if($idRow && ($idRow['Ids'] != '') ){
        $storyIds = ' in  ('.$idRow['Ids'].')';
      }
      
      $TheResponse = new TResponseGetStoriesForImport();
      // load data from DB
      $storyQuery = 'select * from `page`  where `pageID` '.$storyIds.' order by `pageID` LIMIT 30';    
      $storyResult = mysql_query($storyQuery);
 
      while ($storyRow = mysql_fetch_array($storyResult)) {
        //Story OBJECT
        $TheStory = new TStoryObject();
        $TheStory->SetProperty('Id', (int) $storyRow['pageID']);
        $TheStory->SetProperty('Title', $storyRow['title']);
        $TheStory->SetProperty('Body', $storyRow['body']);
        $TheStory->SetProperty('CategoryId', (int) $storyRow['categoryID']);
        $TheStory->SetProperty('User', $storyRow['user']);
        $TheStory->SetProperty('CanOverwrite', $storyRow['can_overwrite'] == 1);

        //get story sentences  
        $sentencesQuery =  'select * from `sentence` where `pageID` = '.$storyRow['pageID'].' order by `sentenceID` ASC';
        $sentenceResult = mysql_query($sentencesQuery);
        while($sentenceRow = mysql_fetch_array($sentenceResult)) {
          $TheSentence = new TSentenceObject();
          $TheSentence->SetProperty('ContextRep', $sentenceRow['context_rep']);
          $TheSentence->SetProperty('Name', $sentenceRow['sentence']);
          $TheSentence->SetProperty('Id', (int) $sentenceRow['sentenceID']);
          $TheSentence->SetProperty('POS', $sentenceRow['POS']);
          $TheSentence->SetProperty('Proto1Id', (int) $sentenceRow['pr1ID']);
          $TheSentence->SetProperty('Proto2Id', (int) $sentenceRow['pr2ID']);
          $TheSentence->SetProperty('Representation', $sentenceRow['representation']);
          $TheSentence->SetProperty('SemanticRep', $sentenceRow['semantic_rep']);
          $TheSentence->SetProperty('StoryId', (int) $sentenceRow['pageID']);
          $TheStory->GetProperty('Sentences')->Add($TheSentence);
        }
                
        //get story qas
        $qaQuery = 'select * from `qa` where `pageID` = '.$storyRow['pageID'].' order by `questionID` ASC';
        $qaResult = mysql_query($qaQuery);
        while($qaRow = mysql_fetch_array($qaResult)) {
          $TheQA = new TQAObject();
          $TheQA->SetProperty('Answer', $qaRow['answer']);
          $TheQA->SetProperty('Question', $qaRow['question']);
          $TheQA->SetProperty('QARule', $qaRow['qarule']);
          $TheQA->SetProperty('Id', (int) $qaRow['questionID']);
          $TheQA->SetProperty('StoryId', (int) $qaRow['pageID']);
          $TheStory->GetProperty('Qas')->Add($TheQA);
        }
        
        //get story protos
        $protoQuery = 'select * from `proto` where `pageID` = '.$storyRow['pageID'].' order by `prID` ASC';
        $protoResult = mysql_query($protoQuery);
        while($protoRow = mysql_fetch_array($protoResult)) {
          $TheProto = new TProtoObject();
          $TheProto->SetProperty('Name', $protoRow['name']);
          $TheProto->SetProperty('Level', (int) $protoRow['level']);
          $TheProto->SetProperty('Id', (int) $protoRow['prID']);
          $TheProto->SetProperty('StoryId',(int) $protoRow['pageID']);
          $TheStory->GetProperty('Protos')->Add($TheProto);
        }
        
        //add story
        $TheResponse->GetProperty('Stories')->Add($TheStory);    
      }     

      return $TheResponse;
    }
  }
?>