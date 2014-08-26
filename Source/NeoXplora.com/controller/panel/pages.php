<?php
namespace NeoX\Controller;
use NeoX\Model;
use NeoX\Entity;
use NeoX\Classes;

require_once __DIR__ . "/../panel.php";

class TPanelPages extends TPanel {

  // public $accessLevel = 'admin';

  public function index() {
    $this->template->addStyle("style/admin.css");
    $this->template->addStyle("style/admin.pages.css");
    $this->template->addStyle("style/train/linker.css");
    $this->template->addScripts(array("js/system/object.js"));

    $this->template->load("index", "panel/pages");
    $this->template->pageTitle = "Manage Pages | Admin Panel";
    $this->template->page = "pages_panel";
    
    $page = isset($_GET['page'])?$_GET['page']:1;
    $per_page = 15;
    $pagination = "";
    $pageData = array();
    
    $linkerModel = $this->core->model("pages", "panel");
    $count_data = $linkerModel->countPages()->fetch_array();
    
    if($count_data['total'] > 0) {
      $pages = ceil($count_data['total'] / $per_page);
      $start = ($page - 1) * $per_page;
      
      $query = $linkerModel->getPages($start, $per_page);
      
      if(!$query || $query->num_rows < 1) {
        $query = $linkerModel->getPages(0, $per_page);
      }
      
      while($result = $query->fetch_array()) {
        $pageData[] = array(
          "Id" => $result[Entity\TPage::$tok_id],
          "Title" => $result[Entity\TPage::$tok_title]
        );
      }
      
      require_once APP_DIR . "classes/Pagination.php";
      $paginationObj = new Classes\Pagination($pages, $page);
      $pagination_array = $paginationObj->generate();
      
      $this->template->pagination = (array) $pagination_array;
      $this->template->currentPage1 = $count_data;
      $this->template->currentPage = $page;
      $pagination = $this->template->fetch("pagination", "panel/pages");
    }
    
    $this->template->pageList = $pageData;
    $this->template->pagination = $pagination;
    
    $this->template->hide_right_box = true;
    $this->template->render();
  }

  public function add() {
    $this->template->addStyle("style/admin.pages.css");
    
    $pageTitle = '';
    $pageBody = '';
    
    if(isset($_POST['submit'])) {
      $pageTitle = $_POST['pageTitle'];
      $pageBody = $_POST['pageBody'];
      
      if($_POST['pageTitle'] != "" && $_POST['pageBody'] != "") {
        $this->Delphi()->PageAdd($pageTitle, $pageBody);
        $this->template->redirect = "panel.php?type=pages";
      }
    }
    
    $this->template->pageData = array(
      'Title' => $pageTitle,
      'Body' => $pageBody
    );
    
    $this->template->load("add", "panel/pages");
    $this->template->pageTitle = "Add Page | Admin Panel";
    $this->template->page = "add_pages_panel";

    $this->template->hide_right_box = true;
    $this->template->render();
  }

  public function edit() {
    if(!isset($_GET['pageid'])) return;
    if(!isset($_GET['page'])) {
      $page = 1;
    } else {
      $page = intval($_GET['page']);
    }
    
    $pageId = intval($_GET['pageid']);
    $pageData = $this->core->entity("page")->select(array("id" => $pageId));
    if($pageData->num_rows == 0) {
      $this->template->redirect = "panel.php?type=pages&page=" . $page;
    } else {
      $pageData = $pageData->fetch_array();
      $pageTitle = $pageData[Entity\TPage::$tok_title];
      $pageBody = $pageData[Entity\TPage::$tok_body];
      
      $this->template->pageData = array(
        'Id' => $pageId,
        'Title' => $pageTitle,
        'Body' => $pageBody
      );
      
      $this->template->addStyle("style/admin.pages.css");
  
      $this->template->currentPage = $page;
      $this->template->load("edit", "panel/pages");
      $this->template->pageTitle = "Edit Page | Admin Panel";
      
      $this->template->page = "edit_pages_panel";
      
      if(isset($_POST['submit'])) {
        if($_POST['pageTitle'] != "" && $_POST['pageBody'] != "") {
          //edit request to delphi
          $this->Delphi()->PageAdd($pageId, $pageTitle, $pageBody);
          $this->template->redirect = "panel.php?type=pages&page=" . $page;
        }
      }
    }

    $this->template->hide_right_box = true;
    $this->template->render();
  }
  
  public function delete() {
    if(!isset($_GET['pageid'])) return;
    if(!isset($_GET['page'])) {
      $page = 1;
    } else {
      $page = intval($_GET['page']);
    }
    
    $pageId = (int) $_GET['pageid'];
    
    //delete page
    $query = $this->core->entity("page")->deleteWithData($pageId);
    
    $this->template->redirect = "panel.php?type=pages&page=" . $page;
    $this->template->render();
  }
 

}
?>