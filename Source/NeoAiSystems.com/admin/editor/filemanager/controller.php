<?php
  /**
   * Controller
   *
   * @version $Id: controller.php, v2.00 2011-04-20 10:12:05 gewa Exp $
   */
   
  define("_VALID_PHP", true);
  
  require_once("../../init.php");

  if (!$user->is_Admin())
      redirect_to("../../login.php");
  
  require("class_fm.php");
  $fm = new Filemanager();
?>
<?php
  $action = (isset($_REQUEST['fmaction'])) ? sanitize($_REQUEST['fmaction']) : "default";
  $name = (isset($_POST['name'])) ? sanitize($_POST['name']) : "";
  $path = (isset($_POST['path'])) ? sanitize($_POST['path']) : "";
  $filepath = (isset($_POST['filepath'])) ? sanitize($_POST['filepath']) : "";
  $octal = (isset($_POST['octal'])) ? sanitize($_POST['octal']) : "";
  
  switch ($action) {
      case "deleteSingle":
          $fm->delete($path, $name);
          break;

      case "createDir":
          if (empty($name)) {
              $core->msgError(_FM_DIR_NAME_R);
          } else {
              $fm->makeDirectory($path, $name);
          }
          break;

      case "uploadFile":
          if (empty($_FILES['newfile']['name'])) {
              $core->msgError(_FM_UPLOAD_ERR9);
          } else {
              $filepath = $_POST['filepath'];
			  $fm->uploadFile($filepath);
          }
          break;
		  		  	            
      default:
          $fm->renderAll();
          break;
  }
?>