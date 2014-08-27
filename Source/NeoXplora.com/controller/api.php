<?php

namespace NeoX\Controller;

require_once APP_DIR . "/app/system/Object.php";
class TApi extends \SkyCore\TObject {

  public function index() {
    
  }
  
  public function getRep() {
    ob_clean();
  
    header("Content-type: text/xml");
    $output = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
  
    if(!isset($_REQUEST['sentence']) || $_REQUEST['sentence'] == '') {
      $output .= $this->error("GetRep: Sentence not set");
      echo $output;
      return;
    }
    
    if(!isset($_REQUEST['api_key']) || $_REQUEST['api_key'] == '') {
      $output .= $this->error("Invalid API Key");
      echo $output;
      return;
    }
        
    $sentence = $_REQUEST['sentence'];
    $api_key = $_REQUEST['api_key'];
    
    $rep = $this->Delphi()->GetRep($sentence, $api_key);
    
    $output = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
    $output .= "<result>\n";
    $output .= "<predicted_rep>" . $rep->GetProperty("PredictedRep") . "</predicted_rep>\n";
    $output .= "<matched_sentence>" . $rep->GetProperty("MatchedSentence") . "</matched_sentence>\n";
    $output .= "</result>\n";
    
    echo $output;
    
  }
  
  private function error($error) {
    $output = "<errors>\n";
    $output .= "<error>" . $error . "</error>\n";
    $output .= "</errors>\n";
    return $output;
  }
  
}
?>