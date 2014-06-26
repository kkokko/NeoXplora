<?php
require_once APP_DIR . "/app/system/appentity.php";
class ControllerTrain extends TAppEntity {

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