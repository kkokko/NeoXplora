<?php

namespace NeoX\Controller;

require_once APP_DIR . "/app/system/Object.php";
class TPanel extends \SkyCore\TObject {

  public $accessLevel = 'admin';

  public function index() {
    $this->template->addStyle("style/admin.pages.css");
    $this->template->load("index", "panel");
    $this->template->pageTitle = "Admin Panel";
    $this->template->page = "panel";
    $this->template->hide_right_box = true;
    $this->template->render();
  }
  
  public function stats() {
    $pageCounts = $this->core->entity("page")->advancedCount();
    $sentenceCounts = $this->core->entity("sentence")->advancedCount();
    
    $this->template->pageCounts = $pageCounts->fetch_array();
    $this->template->sentenceCounts = $sentenceCounts->fetch_array();
    
    $this->template->addStyle("style/admin.css");
    $this->template->load("stats", "panel");
    $this->template->pageTitle = "Stats | Admin Panel";
    $this->template->page = "stats_panel";
    $this->template->hide_right_box = true;
    $this->template->render();
  }
}
?>