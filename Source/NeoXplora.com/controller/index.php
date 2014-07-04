<?php
namespace NeoX\Controller;

require_once APP_DIR . "/app/system/Object.php";
class TIndex extends \SkyCore\TObject {

  public function index() {
	  $this->core->controller('search')->index();
  }
  
}
?>
