<?php
namespace NeoX\Controller;
use NeoX\Model;
use NeoX\Entity;
use NeoX\Classes;

require_once __DIR__ . "/../panel.php";

class TPanelFixRep extends TPanel {

  public $accessLevel = 'admin';
  
  public function index() {
    
    $themodel = $this->core->model("fixrep", "panel");
    
    $themodel->fixrep(".ref = I", ".spro = I");
    $themodel->fixrep(".ref=I", ".spro = I");
    $themodel->fixrep(".ref= I", ".spro = I");
    $themodel->fixrep(".ref =I", ".spro = I");

    $themodel->fixrep(".ref = he", ".spro = he");
    $themodel->fixrep(".ref=he", ".spro = he");
    $themodel->fixrep(".ref= he", ".spro = he");
    $themodel->fixrep(".ref =he", ".spro = he");
    
    $themodel->fixrep(".ref = she", ".spro = she");
    $themodel->fixrep(".ref=she", ".spro = she");
    $themodel->fixrep(".ref= she", ".spro = she");
    $themodel->fixrep(".ref =she", ".spro = she");
    
    $themodel->fixrep(".ref = you", ".spro = you");
    $themodel->fixrep(".ref=you", ".spro = you");
    $themodel->fixrep(".ref= you", ".spro = you");
    $themodel->fixrep(".ref =you", ".spro = you");

    $themodel->fixrep(".ref = we", ".spro = we");
    $themodel->fixrep(".ref=we", ".spro = we");
    $themodel->fixrep(".ref= we", ".spro = we");
    $themodel->fixrep(".ref =we", ".spro = we");
    
  }

}
?>