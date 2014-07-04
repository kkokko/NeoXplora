<?php
namespace NeoX\Controller;

require_once APP_DIR . "/app/system/Object.php";
class TTrain extends \SkyCore\TObject {

  protected $accessLevel = 'user';

  public function index() {
    $this->template->load("index", "train");
    $this->template->pageTitle = "Train";
    $this->template->page = "train";
    $this->template->hide_right_box = false;
    $this->template->render();
  }

}
?>