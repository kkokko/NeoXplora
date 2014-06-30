<?php
require_once APP_DIR . "/app/system/appentity.php";
class ControllerPanel extends TAppEntity {

  public $accessLevel = 'admin';

  public function index() {
    $this->template->addStyle("style/admin.css");
    $this->template->load("index", "panel");
    $this->template->pageTitle = "Admin Panel";
    $this->template->page = "panel";
    $this->template->hide_right_box = true;
    $this->template->render();
  }
  
  public function stats() {
    $pageCounts = $this->core->model("page")->count();
    $sentenceCounts = $this->core->model("sentence")->count();
    
    $this->template->pageCounts = $pageCounts;
    $this->template->sentenceCounts = $sentenceCounts;
    
    $this->template->addStyle("style/admin.css");
    $this->template->load("stats", "panel");
    $this->template->pageTitle = "Stats | Admin Panel";
    $this->template->page = "stats_panel";
    $this->template->hide_right_box = true;
    $this->template->render();
  }

}
?>