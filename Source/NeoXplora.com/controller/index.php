<?php
require_once APP_DIR . "/app/system/appentity.php";
class ControllerIndex extends TAppEntity {

  
    public function index() {
		$this->core->controller('search')->index();
    }

}
?>
