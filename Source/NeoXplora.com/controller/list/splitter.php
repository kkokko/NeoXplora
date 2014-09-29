<?php 
namespace NeoX\Controller;
use NeoX\Model;
use NeoX\Entity;
use NeoX\Classes;

require_once APP_DIR . "/app/system/Object.php";
class TListSplitter extends \SkyCore\TObject {
  
  protected $accessLevel = 'admin';
  
  public function index() {
    $this->template->addScripts(array(
      "js/system/object.js"
    ));
    $this->template->addJSModules(array(
      "NeoX.Modules.SplitterListIndex" => "js/module/splitter/list/index.js",
      "NeoX.Modules.SplitterListRequests" => "js/module/splitter/list/requests.js",
      "NeoX.Modules.ButtonComponent" => "js/module/button.js"
    ));
    
    $this->template->per_page = 1000;
    $this->template->load("index", "list/splitter");
    $this->template->pageTitle = "Splitter List";
    $this->template->page = "listsplit";
    $this->template->hide_right_box = true;
    $this->template->render();
  }
  
  public function load() {
    $valid_per_pages = array(15,50,100,1000);
    $page = isset($_POST['page'])?$_POST['page']:1;
    $per_page = (isset($_POST['per_page']) && in_array(intval($_POST['per_page']), $valid_per_pages))?intval($_POST['per_page']):1000;
    $status = isset($_POST['status'])?$_POST['status']:'';
    $search = isset($_POST['search'])?$_POST['search']:'';
    $pagination = array();
    $pages = 0;
    
    $model = $this->core->model("splitter", "list");
    $count_data = $model->countSplitsList($status, $search)->fetch_array();
    
    if($count_data['total'] > 0) {
      $pages = ceil($count_data['total'] / $per_page);
      $start = ($page - 1) * $per_page;
      
      $query = $model->getSplitsList($start, $per_page, $status, $search);
      
      if(!$query) {
        $query = $model->getSplitsList(0, $per_page, $status, $search);
        $page = 1;
      }
      
      $splitsList = array();
      $rowclass = 'row2';
      while($splits_data = $query->fetch_array()) {
        $rowclass = ($rowclass == "row2")?"row1":"row2";
        $splitsList[] = array(
          "id" => $splits_data['id'],
          "proto" => $splits_data['proto'],
          "splits" => $splits_data['SplitText'],
          "rowclass" => $rowclass
        );
      }
      
      require_once APP_DIR . "classes/Pagination.php";
      $paginationObj = new Classes\Pagination($pages, $page);
      $pagination_array = $paginationObj->generate();
      
      $this->template->pagination = (array) $pagination_array;
      
      $this->template->currentPage = $page;
      $pagination = $this->template->fetch("pagination", "list/splitter");
      
      $this->template->splits = $splitsList;
      $data = $this->template->fetch("table", "list/splitter");
    } else {
      $data = '<br/>There are no sentences to list.';
    }
    
    $response = array(
      'data' => $data,
      'pagination' => $pagination
    );
    
    echo json_encode($response);
  }

}

?>