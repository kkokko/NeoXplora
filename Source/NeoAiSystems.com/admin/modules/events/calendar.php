<?php
  /**
   * Calendar
   *
   * @version $Id: calendar.php, v2.00 2011-04-20 10:12:05 gewa Exp $
   */
  define("_VALID_PHP", true);
  
  require_once("../../init.php");
  if (!$user->is_Admin())
      redirect_to("../../login.php");
  
  require_once("lang/" . $core->language . ".lang.php");
  require_once("admin_class.php");
  $eventcal = new eventManager();
?>
<?php $eventcal->renderCalendar('large');?>