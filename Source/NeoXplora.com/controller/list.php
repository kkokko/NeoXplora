<?php
namespace NeoX\Controller;

require_once APP_DIR . "/app/system/Object.php";
class TList extends \SkyCore\TObject {

  protected $accessLevel = 'admin';

  public function index() {
    $this->template->redirect = "list.php?type=splitter";
    $this->template->pageTitle = "List";
    $this->template->page = "list";
    $this->template->hide_right_box = false;
    $this->template->render();
  }

}
?>