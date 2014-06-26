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

  require_once("lang/" . $core->language . ".lang.php");
  require_once("admin_class.php");
  
  $tab = new jTabs();
?>
<?php
  /* Proccess Tabs */
  if (isset($_POST['processTabs'])): 
  $tab->tabid = (isset($_POST['tabid'])) ? $_POST['tabid'] : 0; 
  $tab->processTabs();
  endif;
?>
<?php
  /* Update Tab Order */
  if (isset($_GET['sorttabs'])) :
      $tab->updateOrder();
 endif;
?>
<?php
  /* Delete Tab*/
  if (isset($_POST['deleteTab'])):
  
  $id = sanitize($_POST['deleteTab']);
  $db->delete("plug_tabs", "id='" . (int)$id . "'");
  
  $title = sanitize($_POST['title']);
  print ($db->affected()) ? $wojosec->writeLog(PLG_JT_TAB .' <strong>'.$title.'</strong> '._DELETED, "", "no", "module") . $core->msgOk(PLG_JT_TAB .' <strong>'.$title.'</strong> '._DELETED) : $core->msgAlert(_SYSTEM_PROCCESS); 
  endif;
?>