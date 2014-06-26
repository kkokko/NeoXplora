<?php
  /**
   * Calendar
   *
   * @version $Id: calendar.php, v2.00 2011-04-20 10:12:05 gewa Exp $
   */
  define("_VALID_PHP", true);
  
  require_once("../../init.php");

  require_once(WOJOLITE . "admin/modules/events/lang/" . $core->language . ".lang.php");
  require_once(WOJOLITE . "admin/modules/events/admin_class.php");
  
  $calendar = new eventManager(); 
?>
<?php $calendar->renderCalendar('responsive');?>