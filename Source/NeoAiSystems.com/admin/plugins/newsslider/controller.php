<?php
  /**
   * Controller
   *
   * @version $Id: delete.php, v2.00 2011-04-20 10:12:05 gewa Exp $
   */
  define("_VALID_PHP", true);
  
  require_once("../../init.php");
  if (!$user->is_Admin())
      redirect_to("../../login.php");

  require_once("lang/" . $core->language . ".lang.php");
  require_once("admin_class.php");
  
  $slider = new newsSlider();
?>
<?php
  /* Process News*/
  if (isset($_POST['processNews'])):
  $slider->sliderid = (isset($_POST['sliderid'])) ? $_POST['sliderid'] : 0; 
  $slider->processNews();
  endif;
?>
<?php
  /* Update News Order */
  if (isset($_GET['sortnews'])) :
      $slider->updateOrder();
 endif;
?>
<?php
  /* Delete News Item*/
  if (isset($_POST['deleteNews'])):
  
  $id = sanitize($_POST['deleteNews']);
  $db->delete("plug_newsslider", "id='" . (int)$id . "'");
  
  $title = sanitize($_POST['title']);
  print ($db->affected()) ? $wojosec->writeLog(PLG_NS_ITEM .' <strong>'.$title.'</strong> '._DELETED, "", "no", "plugin") . $core->msgOk(PLG_NS_ITEM .' <strong>'.$title.'</strong> '._DELETED) : $core->msgAlert(_SYSTEM_PROCCESS); 
  endif;
?>