<?php 
namespace NeoX\Controller;
use NeoX\Model;
use NeoX\Entity;
use NeoX\Classes;

require_once __DIR__ . "/../train.php";
class TReviewSplitter extends TTrain {
  
  protected $accessLevel = 'admin';
  
  public function index() {
    $this->template->addScripts(array(
      "js/system/object.js"
    ));
    $this->template->addJSModules(array(
      "NeoX.Modules.SplitterReviewIndex" => "js/module/splitter/review/index.js",
      "NeoX.Modules.SplitterReviewRequests" => "js/module/splitter/review/requests.js",
      "NeoX.Modules.ButtonComponent" => "js/module/button.js"
    ));
    
    $this->template->thePageId = (isset($_GET['pageId']) && $_GET['pageId'] != "")?$_GET['pageId']:"";
    
    $this->template->load("index", "review/splitter");
    $this->template->pageTitle = "Splitter Review";
    $this->template->page = "trainsplit";
    $this->template->hide_right_box = true;
    $this->template->render();
  }
  
  public function load() {
    $page = isset($_POST['page'])?$_POST['page']:1;
    $per_page = 5;
    $pagination = array();
    $pageId = (isset($_REQUEST['pageId']) && $_REQUEST['pageId'] != "")?$_REQUEST['pageId']:null;
    
    $splitterModel = $this->core->model("splitter", "review");
    $count_data = $splitterModel->countProtos($pageId)->fetch_array();
    
    if($count_data['total'] > 0) {
      $pages = ceil($count_data['total'] / $per_page);
      $start = ($page - 1) * $per_page;
      
      $query = $splitterModel->getSentences($pageId, $start, $per_page);
      
      if(!$query) {
        $query = $splitterModel->getSentences($pageId, 0, $per_page);
        $page = 1;
      }
      
      $protos = array();
      $rowclass = '';
      while($sentence_data = $query->fetch_array()) {
        if(!isset($protos[$sentence_data['protoId']])) {
          $protos[$sentence_data['protoId']] = array(
            "id" => $sentence_data['protoId'],
            "name" => $sentence_data['protoName'],
            "pageid" => $sentence_data['pageId'],
            "sentences" => array()
          );
          $rowclass = '';
        }
        
        $rowclass = (($rowclass == "row1")?"row2":"row1");
        
        $protos[$sentence_data['protoId']]['sentences'][] = array(
          "id" => $sentence_data['sentenceId'],
          "rowclass" => $rowclass,
          "name" => $sentence_data['sentenceName']
        );
      }
      
      require_once APP_DIR . "classes/Pagination.php";
      $paginationObj = new Classes\Pagination($pages, $page);
      $pagination_array = $paginationObj->generate();
      
      $this->template->pagination = (array) $pagination_array;
      
      $this->template->currentPage = $page;
      $pagination = $this->template->fetch("pagination", "review/splitter");
      
      $this->template->protos = $protos;
      $data = $this->template->fetch("table", "review/splitter");
    } else {
      $data = 'There are no sentences to review.';
    }
    
    $response = array(
      'data' => $data,
      'pagination' => $pagination
    );
    
    echo json_encode($response);
  }

  public function revert() {
    if(!isset($_POST['protoID'])) return;
    $protoId = $_POST['protoID'];
    
    $sentence_data = $this->core->model("splitter", "review")->getFirstSentenceIdForProtoId($protoId)->fetch_array();
    $sentenceId = $sentence_data[Entity\TSentence::$tok_id];
    $sentenceNewValue = $sentence_data[Entity\TProto::$tok_name];
    
    $this->core->model("splitter", "review")->revertSentenceToProto($sentenceId, $protoId);
    
    $this->updatePageStatus($sentenceId);
    
    $this->template->proto = array(
      "id" => $protoId
    );
    $this->template->sentence = array(
      "id" => $sentenceId,
      "name" => $sentenceNewValue,
      "rowclass" => "row1"
    );
    $this->template->load("sentence", "review/splitter");
    $data = $this->template->parse();
        
    $response = array(
      "data" => $data,
      "sentenceID" => $sentenceId
    );
    
    echo json_encode($response);
  }

  public function modify() {
    if(!isset($_POST['sentenceID']) || !isset($_POST['newValue'])) return;
    $newValue = htmlspecialchars_decode($_POST['newValue'], ENT_QUOTES);
    $sentenceId = $_POST['sentenceID'];
    $protoId = $this->core->entity("sentence")->select($sentenceId, "protoid")->fetch_array();
    $protoId = $protoId[Entity\TSentence::$tok_protoid];
    
    $result = $this->Delphi()->SplitSentence($sentenceId, $newValue);

    $newSentencesCount = $result->Count();    
    $data = '';
    
    if($newSentencesCount == 1) {
      $this->template->proto = array(
        "id" => $protoId
      );
      $this->template->sentence = array(
        "id" => $sentenceId,
        "name" => $newValue,
        "rowclass" => "row1"
      );
      
      $data = $this->template->fetch("sentence", "review/splitter");
    } elseif($newSentencesCount > 1) {
      for($i = 0; $i < $newSentencesCount; $i++) {
        $sentenceId = $result->Item($i)->GetProperty("Id");
        
        $this->template->proto = array(
          "id" => $protoId
        );
        $this->template->sentence = array(
          "id" => $result->Item($i)->GetProperty("Id"),
          "name" => $result->Item($i)->GetProperty("Name"),
          "rowclass" => "row" . ($i%2 + 1)
        );
        
        $data .= $this->template->fetch("sentence", "review/splitter");
      }
    }

    $this->updatePageStatus($sentenceId);
    
    $response = array(
      'data' => $data,
      'newSentencesCount' => $newSentencesCount,
      'asentenceid' => $sentenceId
    );
    
    echo json_encode($response);
  }

  public function approve() {
    if(!isset($_POST['protoID'])) return;
    $protoID = $_POST['protoID'];
    
    $this->core->entity("sentence")->update(
      array(
        "protoid" => array(
          $protoID
        )
      ),
      array(
        "status" => 'ssReviewedSplit'
      )
    );

    $this->updatePageStatus(0, $protoID);
    
    $sentenceIDs = array();
    $query = $this->core->entity("sentence")->select(
      array(
        "protoid" => array(
          $protoID
        )
      ),
      "id"
    );

    while($sentence_data = $query->fetch_array()) {
      $sentenceIDs[] = $sentence_data[Entity\TSentence::$tok_id]; 
    }
    
    $this->Delphi()->PredictAfterSplit($sentenceIDs);
    
    echo json_encode("");
  }
    
  public function dismiss() {
    if(!isset($_POST['protoID'])) return;
    $protoID = $_POST['protoID'];
    
    $this->core->entity("sentence")->update(
      array(
        "protoid" => array(
          $protoID
        )
      ),
      array(
        "status" => 'ssFinishedGenerate'
      )
    );
    
    $this->updatePageStatus(0, $protoID);
    
    echo json_encode("");
  }
  
  public function approveMultiple() {
    if(!isset($_POST['protoIDs']) || !is_array($_POST['protoIDs'])) return;
    
    $protoIDs = $_POST['protoIDs'];
      
    $this->core->entity("sentence")->update(
      array(
        "protoid" => $protoIDs
      ),
      array(
        "status" => 'ssReviewedSplit'
      )
    );
    
    foreach($protoIDs as $protoID)
      $this->updatePageStatus(0, $protoID);

    $sentenceIDs = array();
    
    $query = $this->core->entity("sentence")->select(
      array(
        "protoid" => $protoIDs
      ),
      "id"
    );
    
    while($sentence_data = $query->fetch_array()) {
      $sentenceIDs[] = $sentence_data[Entity\TSentence::$tok_id]; 
    }

    try {
      $this->Delphi()->PredictAfterSplit($sentenceIDs);
    } catch(\Exception $e) {
      var_dump($e);
    }
    
    echo json_encode("");
  }

  public function dismissMultiple() {
    if(!isset($_POST['protoIDs']) || !is_array($_POST['protoIDs'])) return;
    $protoIDs = $_POST['protoIDs'];
       
    $wherein = '';
    $wherein .= " s.`pr2ID` IN (";
    for($i = 0; $i < count($protoIDs); $i++) {
      $wherein .= "'" . $protoIDs . "'";
      if($i != count($protoIDs) - 1) $wherein .= ', ';
    }
    $wherein .= ") ";
    
    $this->core->entity("sentence")->update(
      array(
        "protoid" => $protoIDs
      ),
      array(
        "status" => 'ssFinishedGenerate'
      )
    );
      
    foreach($protoIDs as $protoID)
      $this->updatePageStatus(0, $protoID);
    
    echo json_encode("");
  }

  
}

?>