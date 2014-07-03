<?php 
require_once "trainentity.php";
class ControllerTrainSplitter extends TTrainEntity {
  
  public function index() {
    $this->template->addScripts(array(
      "js/system/object.js"
    ));
    $this->template->addJSModules(array(
      "NeoX.Modules.SplitterIndex" => "js/module/splitter/index.js",
      "NeoX.Modules.ButtonComponent" => "js/module/button.js"
    ));
    $this->template->load("index", "train/splitter");
    $this->template->pageTitle = "Train Split";
    $this->template->page = "trainsplit";
    $this->template->hide_right_box = true;
    $this->template->render();
  }
  
  public function load() {
    $ignoreIDs = array();
    if(isset($_SESSION['ignoredPageIDs']) && is_array($_SESSION['ignoredPageIDs'])) {
      $ignoreIDs = $_SESSION['ignoredPageIDs'];
    }
    $splitterModel = $this->core->model("splitter", "train");
    
    $category_data = $splitterModel->getCategory($ignoreIDs);
    
    $categoryID = $category_data['id'];
    $pageCount = $category_data['pageCount'];
    $max_offset = min(array($pageCount, 5));
    $offset = rand(0, $max_offset - 1);
    
    $sentenceCount = $splitterModel->countSentences($categoryID, $offset);
    $sentence_offset = rand(0, $sentenceCount['sentenceCount'] - 1);
      
    $sentence_data = $splitterModel->getSentence($categoryID, $offset, $sentence_offset, $ignoreIDs);
    
    $data = 'No sentence to display';
    
    if($sentence_data) {
      $this->core->model("sentence")->setAssignedDate(date("Y-m-d H:i:s"), $sentence_data[ModelSentence::$tok_id]);
      
      $this->template->sentence = $sentence_data[ModelSentence::$tok_name];
      $this->template->sentenceID = $sentence_data[ModelSentence::$tok_id];
      $this->template->load("single", "train/splitter");
      
      $data = $this->template->parse();
    }
    
    $response = array(
      'data' => $data
    );
    
    echo json_encode($response);
  }

}

?>