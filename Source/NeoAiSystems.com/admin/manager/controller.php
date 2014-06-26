<?php
  /**
   * Controller
   *
   * @version $Id: controller.php, v2.00 2011-04-20 10:12:05 gewa Exp $
   */
   
  define("_VALID_PHP", true);
  
  require_once("../init.php");

  if (!$user->is_Admin())
      redirect_to("../login.php");
  
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
          
      case "chmodSingle":
          if (empty($octal)) {
              $core->msgError(_FM_PER_OCT_ERR);
          } else {
              $fm->chmodall($path, $octal, $name);
          }
          break;

      case "createDir":
          if (empty($name)) {
              $core->msgError(_FM_DIR_NAME_R);
          } else {
              $fm->makeDirectory($path, $name);
          }
          break;

      case "createFile":
          if (empty($name)) {
              $core->msgError(_FM_FILENAME_R);
          } else {
              $fm->makeFile($path, $name);
          }
          break;
		  		            
      case "deleteMulti":
          if (empty($_POST['multid']) && empty($_POST['multif'])) {
              $core->msgAlert(_FM_SEL_ERR);
          } else {
              if (isset($_POST['multid'])) {
                  foreach ($_POST['multid'] as $deldir) {
                      $action = $fm->delete($deldir);
                  }
                  if ($action)
                      $core->msgOK(_FM_DELOK_DIR);
              }
              if (isset($_POST['multif'])) {
                  foreach ($_POST['multif'] as $delfile) {
                      $action = $fm->delete($filepath . $delfile);
                  }
                  if ($action)
                      $core->msgOK(_FM_DELOK_FILE);
              }
          }
          break;
		  		            
      case "viewItem":
          $fm->viewItem($path, $name);
          break;

      case "editItem":
          $fm->editItem($path, $name);
          break;

      case "saveItem":
          $fm->saveItem($path, $name, $_POST['filecontent']);
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
<script type="text/javascript">
  $("input[type=file]").change(function () {
	  $(this).parents(".fileuploader").find(".filename").val($(this).val());
  });
  $("input[type=file]").each(function () {
	  if ($(this).val() == "") {
		  $(this).parents(".fileuploader").find(".filename").val("<?php echo _BROWSE;?>...");
	  }
  });
 $('input[type="checkbox"]').ezMark();
 $(function () {
	$('#masterCheckbox').click(function(e) {
		$(this).parent().toggleClass("ez-checked");
		$('input[name^="multif"], input[name^="multid"]').each(function() {
			($(this).is(':checked')) ? $(this).removeAttr('checked') : $(this).attr({"checked":"checked"});
			 $(this).trigger('change');
		});
		return false;
	});
});
</script>