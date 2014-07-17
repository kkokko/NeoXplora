<?php
namespace NeoX\Controller;

require_once APP_DIR . "/app/system/Object.php";
class TBrowse extends \SkyCore\TObject {

  protected $accessLevel = 'admin';

  public function index() {
    $this->template->redirect = "browse.php?type=splitter";
    $this->template->pageTitle = "Browse";
    $this->template->page = "train";
    $this->template->hide_right_box = false;
    $this->template->render();
  }

}
?>