<?php
namespace NeoX\Controller;
use NeoX\Model;
use NeoX\Entity;
use NeoX\Classes;

require_once __DIR__ . "/../panel.php";

class TPanelSeparators extends TPanel {

  public $accessLevel = 'admin';

  public function index() {
    $this->template->addStyle("style/admin.css");
    $this->template->addStyle("style/admin.pages.css");
    $this->template->addStyle("style/train/linker.css");
    
    $this->template->load("index", "panel/separators");
    $this->template->pageTitle = "Manage Separators | Admin Panel";
    $this->template->page = "pages_separators";
    
    $page = isset($_GET['page'])?$_GET['page']:1;
    
    $per_page = 15;
    $pagination = "";
    $data = array();
        
    $model = $this->core->model("separators", "panel");
    $count_data = $model->countSeparators()->fetch_array();
    
    if($count_data['total'] > 0) {
      $pages = ceil($count_data['total'] / $per_page);
      $start = ($page - 1) * $per_page;
      
      $query = $model->getSeparators($start, $per_page);
      
      if(!$query || $query->num_rows < 1) {
        $query = $model->getSeparators(0, $per_page);
      }
      
      while($result = $query->fetch_array()) {
        $data[] = array(
          "Id" => $result[Entity\TSeparator::$tok_id],
          "Value" => $result[Entity\TSeparator::$tok_value]
        );
      }
      
      require_once APP_DIR . "classes/Pagination.php";
      $paginationObj = new Classes\Pagination($pages, $page);
      $pagination_array = $paginationObj->generate();
      
      $this->template->pagination = (array) $pagination_array;
      $this->template->currentPage1 = $count_data;
      $this->template->currentPage = $page;
      $pagination = $this->template->fetch("pagination", "panel/separators");
    }
    
    $this->template->separatorList = $data;
    $this->template->pagination = $pagination;
    
    $this->template->hide_right_box = true;
    $this->template->render();
  }

  public function add() {
    if(isset($_POST['submit'])) {
      $separator = isset($_POST['separator'])?$_POST['separator']:'';
      
      if($separator != "") {
        try {
          $this->core->model("separators", "panel")->addSeparator($separator);
        } catch(\Exception $e) {
          
        }
      }
    }
    
    $this->template->redirect = "panel.php?type=separators";
    $this->template->render();
  }

  public function delete() {
    if(!isset($_GET['id'])) return;
    if(!isset($_GET['page'])) {
      $page = 1;
    } else {
      $page = intval($_GET['page']);
    }
    
    $sepid = (int) $_GET['id'];
    
    //delete page
    $query = $this->core->model("separators", "panel")->deleteSeparator($sepid);
    
    $this->template->redirect = "panel.php?type=separators&page=" . $page;
    $this->template->render();
  }
 

}
?>