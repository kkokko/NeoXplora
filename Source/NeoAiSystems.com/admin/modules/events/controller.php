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
  
  $event = new eventManager();
?>
<?php
  /* Process Events*/
  if (isset($_POST['processEvent'])):
  $event->eventid = (isset($_POST['eventid'])) ? $_POST['eventid'] : 0; 
  $event->processEvent();
  endif;
?>
<?php
  /* Delete events*/
  if (isset($_POST['deleteEvent'])):
  $id = sanitize($_POST['deleteEvent']);
  $db->delete("mod_events", "id='" . (int)$id . "'");
  
  $title = sanitize($_POST['title']);
  print ($db->affected()) ? $wojosec->writeLog(PLG_EM_EVENT .' <strong>'.$title.'</strong> '._DELETED, "", "no", "module") . $core->msgOk(PLG_EM_EVENT .' <strong>'.$title.'</strong> '._DELETED) : $core->msgAlert(_SYSTEM_PROCCESS); 
  endif;
?>