<?php
class SentenceSplitter {
  
   protected $WordList = array();
   private function getConstWordChars(){
     return array_merge(range('A', 'Z'), range('a', 'z'), array('0', '1', '2', '3', '4', '5', '6', '7', '8', '9'), array('-'));
   }
   
  private function ReadWordPunctuation($AString, &$AnIndex){
    $TheWord = '';
    while($AnIndex < strlen($AString)){
      $TheChar = $AString[$AnIndex];
      if(in_array($TheChar, $this->getConstWordChars())) {
        break;
      } 
      $AnIndex = $AnIndex+1;
      if($TheChar == ' '){
        break;
      }
      $TheWord .= $TheChar;
    }
    if($TheWord != ''){
      array_push($this->WordList, $TheWord);
    }          
  }
  
  private function ReadWordString($AString, &$AnIndex){
    $TheWord = '';
    while($AnIndex < strlen($AString) ){
      $TheChar = $AString[$AnIndex];
      if(!in_array($TheChar, $this->getConstWordChars())) {
        break;
      } 
      $TheWord .= $TheChar;
      $AnIndex++;      
    }
    if($TheWord != ''){
      array_push($this->WordList, $TheWord);
    } 
  }
  
  public function SentenceAdjust($ASentence){
    SentenceSplitWords($ASentence);
    return SentenceFromWords();    
  }
  
  public function SentenceFromWords(){
    $TheSentence = '';
    for($i=0; $i<count($this->WordList); $i++){
      $TheSentence .= ' ' + $this->WordList[$i];
    }    
  }  
  
  public function SentenceSplitWords($ASentence){
    $this->WordList = array();
    $i=0;    
    while($i < strlen($ASentence)){
      $this->ReadWordPunctuation($ASentence, $i);
      $this->ReadWordString($ASentence, $i);
    } 
    for($i=1; $i < count($this->WordList) - 1; $i++){      
      // if simple quote is found, check for:
      if(isset($this->WordList[$i]) && $this->WordList[$i] == "'"){        
        if(in_array($this->WordList[$i-1][0], $this->getConstWordChars()) and 
          // 1'st
          ($this->WordList[$i+1] == 'st') or
          // 2'nd
          ($this->WordList[$i+1] == 'nd') or
          // 3'rd
          ($this->WordList[$i + 1] == 'rd') or
          // 4'th, 5'th, etc.
          ($this->WordList[$i + 1] == 'th') or
          // can't
          ($this->WordList[$i + 1] == 't')          
        ){
          $this->WordList[$i-1] = $this->WordList[$i - 1].$this->WordList[$i].$this->WordList[$i + 1];
          unset($this->WordList[$i+1]);
          unset($this->WordList[$i]);
          $this->WordList = array_values($this->WordList);
          $i--;          
        } else if(strtolower($this->WordList[$i - 1]) == 'that' && strtolower($this->WordList[$i + 1]) == 's') {
          $this->WordList[$i - 1] = $this->WordList[$i - 1] . $this->WordList[$i] . $this->WordList[$i + 1];
          unset($this->WordList[$i + 1]);
          unset($this->WordList[$i]);
          $i--;
        } else {
          $this->WordList[$i] = $this->WordList[$i] . $this->WordList[$i + 1];
          unset($this->WordList[$i+1]);                   
          $this->WordList = array_values($this->WordList);
        }     
      }
    }
 
     if( isset($this->WordList[ count($this->WordList)-1 ]) && $this->WordList[ count($this->WordList)-1 ] == '.'){
       unset($this->WordList[count($this->WordList)-1]);                   
       $this->WordList = array_values($this->WordList);
     } 
    return $this->WordList;
  }

}


//test 
//$TheSentenceSplitWords = new SentenceSplitter();
//$TheSentence = "This yesr we can't go to jennie's 15'th birdday.";
//var_dump($TheSentenceSplitWords->SentenceSplitWords($TheSentence)) ;
?>